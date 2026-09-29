

var paidListingComponent = Object.freeze({

    addNewPaidListing: async function() {
        _this = this;
        this.showLoader("Saving listing details...");
        const response = await $.post(this.urls.main + "listings/exists", {
            user_key_id: this.settings.user_key_id,
            unique_key_id: this.settings.unique_key_id,
            name: this.newListing.name,
            contact_number: this.newListing.contact_number,
        }, 'json');
        var results = await response;
        if (results.status == true && results.data == true) {
            this.hideLoader();
            this.showMessage("A listing with the same name and contact number already exists. Please check your details or contact support for assistance.");
        } else if (results.status == true && results.data == false) {
            $.post(this.urls.main + "listings/insert", {
                id: this.newListing.id,
                user_key_id: this.settings.user_key_id,
                unique_key_id: this.settings.unique_key_id,
                name: this.newListing.name,
                contact_code: this.newListing.contact_code,
                contact_number: this.newListing.contact_number,
                office_number: this.newListing.office_number,
                email: this.newListing.email,
                dob: this.newListing.dob,
                gender: this.newListing.gender,
                age: this.calculateAge(this.newListing.dob),
                description: this.newListing.description,
                location: this.newListing.location,
                cv_path: this.newListing.cv_path,
                logo: this.newListing.logo,
                google_url: this.newListing.google_url,
                website_url: this.newListing.website_url,
                facebook_url: this.newListing.facebook_url,
                x_url: this.newListing.x_url,
                instagram_url: this.newListing.instagram_url,
                admin: this.permissions.admin,
                type: 'paid',
                source: this.newListing.source
            }, function (response) {
                _this.hideLoader();
                if (response.status == true && response.data) {
                    _this.newListing.id = response.data.id;
                    _this.showMakeListingPayment();
                } else {
                    _this.showMessage(response.message);
                }
            }, 'json')
            .fail(function (response) {
                _this.showMessage(response.responseText);
            });
        }
    },

    searchFreeListings: function() {
        _this = this;
        _action = this.newListing.search.action;

        let error = false;
        let message = "";
        if (!this.isValidMobile(this.newListing.search.mobile)) {
            error = true;
            message = "Please enter a valid mobile number.";
        }

        if (error) {
            this.showMessage(message, [{
                text: "Ok",
                onTap: function () { }
            }]);
        } else {
            let decoded = decodeMobileNumber(this.newListing.search.mobile);
            $.post(this.urls.main + "listings/search", {
                user_key_id: this.settings.user_key_id,
                unique_key_id: this.settings.unique_key_id,
                name: this.newListing.search.name,
                contact_number: decoded.number,
                action: _action
            }, function (response) {
                if (response.status == true) {
                    _this.newListing.search.results = response.data;
                    _this.showAddPaidListingSearchResults();
                }
            }, 'json')
            .fail(function(response) {
                _this.showMessage(response.error);
            });
        }
    },

    selectListing: function(listing) {
        this.newListing.id = listing.id,
        this.newListing.name = listing.name;
        this.newListing.contact_code = listing.contact_code,
        this.newListing.contact_number = listing.contact_number;
        this.newListing.description = listing.description;
        this.newListing.location = listing.location;
        this.newListing.source = listing.source;
        if (listing.contact_number.length > 0) {
            this.newListing.maskMobile = this.maskMobileNumber(listing.contact_number);
        }
        this.showAddPaidListing2FA();
    },
    searchWithMobile: function() {
        this.newListing.search.action = "mobile";
        this.newListing.search.showSearchMobile = true;
        this.closeAddPaidListingSearchResults();
    },
    verify2FA: function(action) {
        _this = this;
        if (this.isEmpty(this.newListing.search._2fa)) {
            this.showMessage("Please enter the required " + action + " value.");
            return;
        }

        var passed = false;
        var _2fa = this.newListing.search._2fa;

        if (action == "mobile") {
            let decoded = decodeMobileNumber(_2fa);
            if (decoded.number == this.newListing.contact_number) {
                passed = true;
            }
        }
        
        if (passed) {
            this.showMessage(action + " verified successfully.", [{
                text: "Ok",
                onTap: function () { 
                    _this.showAddListingDetails();
                }
            }]);
        } else {
            this.showMessage("This is not the correct " + action + " to Verify Ownership of this listing.");
        } 
    },
    maskEmailAddress: function(email) {
        temp = email.split('@');
        username = temp[0];

        var muskedEmail = username.charAt(0) + this.ast(username.substring(1, username.length - 2).length) + username.charAt(username.length - 1);
        muskedEmail += '@';

        domain_info = temp[1];
        temp = domain_info.split('.');
        domain_name = temp[0];
        domain = temp[1];

        muskedEmail += domain_name.charAt(0) + this.ast(domain_name.substring(1, domain_name.length - 2).length) + domain_name.charAt(domain_name.length - 1);

        for (var i = 1; i < temp.length; i++) {
            muskedEmail += '.' + temp[i] 
        }

        return muskedEmail.substring(0, muskedEmail.length);
    },
    maskMobileNumber: function(mobile) {
        mobile = mobile.replace('+27', '0');
        return this.createAsterics(mobile.substring(0, mobile.length - 4).length) + mobile.substring(mobile.length - 4, mobile.length);
    },
    createAsterics: function(length) {
        s = ""
        for(var i = 0; i < length; i++) {
            s += "*";
        }
        return s;
    },

    decodeNewListingSearchMobile: function(data) {
        let decoded = decodeMobileNumber(data.phones[0].number);
        this.contact.flag = decoded.icon;
        this.newListing.contact_code = decoded.code;
        this.newListing.search.mobile = decoded.number;
    },

    payWithVoucher: function() {
        _this = this;
        if (this.getVoucher.available == 0) {
            this.showMessage("Voucher has been used, cannot claim voucher.");
        } else {
            this.showLoader("Claiming voucher");
            $.post(this.urls.main + "vouchers/claim", {
                user_key_id: this.settings.user_key_id,
                unique_key_id: this.settings.unique_key_id,
                listing_id: this.newListing.id,
                voucher_code: this.newVoucher.searchCode
            }, async function (response) {
                _this.hideLoader();
                if (response.status == true && response.data) {
                    _this.showMessage(response.message);
                    _this.newVoucher.clear();
                    await _this.aGetMyListings();
                    await _this.aGetListings();
                    _this.updateAllListings();
                    _this.getAdminDashStats();
                    _this.closeVoucherPaymentPage();
                    _this.closeAddListingProcess();
                } else {
                    _this.showMessage(response.message);
                }
            }, 'json')
            .fail(function (response) {
                _this.showMessage(response.responseText);
            });
        }
    },

    /**
     * Create paid listing
     */
    showViewPaidListing: function(){
        this.pages.depth++;
        this.openPage('viewPaidListing');
    },
    closeViewPaidListing: function(){
        this.pages.depth--;
        this.closePage('viewPaidListing');
    },

    showEditPaidListing: function(){
        this.pages.depth++;
        this.detailsTabs.tab = 'details';
        this.openPage('editPaidListing');
    },
    closeEditPaidListing: function(){
        this.pages.depth--;
        this.detailsTabs.tab = '';
        this.closePage('editPaidListing');
    },

    showAddPaidListing: function() {
        this.pages.depth++;
        this.openPage('addPaidListing');
    },
    closeAddPaidListing: function() {
        this.pages.depth--;
        this.closePage('addPaidListing');
    },

    showAddListingSearch: function(){
        this.pages.depth++;
        this.openPage('addPaidListingSearch');
    },
    closeAddListingSearch: function() {
        this.pages.depth--;
        this.closePage('addPaidListingSearch');
    },

    showSearchPage: function() {
        this.pages.depth++;
        this.openPage('addPaidListingSearch');
    },
    closeSearchPage: function() {
        this.pages.depth--;
        this.closePage('addPaidListingSearch');
    },

    showAddPaidListingSearchResults: function() {
        this.pages.depth++;
        this.openPage('addPaidListingSearchResults');
    },
    closeAddPaidListingSearchResults: function() {
        this.pages.depth--;
        this.newListing.clear();
        this.closePage('addPaidListingSearchResults');
    },

    showAddPaidListing2FA: function() {
        this.pages.depth++;
        this.openPage('addPaidListing2FA');
    },
    closeAddPaidListing2FA: function() {
        this.pages.depth--;
        this.newListing.clear();
        this.newListing.search._2fa = "";
        this.closePage('addPaidListing2FA');
    },

    showAddListingDetails: function(){
        this.pages.depth++;
        this.openPage('addPaidListingDetails');
    },
    closeAddListingDetails: function(){
        this.pages.depth--;
        this.closePage('addPaidListingDetails');
    },

    showPreviewAddListing: function(){
        this.pages.depth++;
        this.openPage('previewAddListing');
    },
    closePreviewAddListing: function(){
        this.pages.depth--;
        this.closePage('previewAddListing');
    },

    showMakeListingPayment: function(){
        this.pages.depth++;
        this.openPage('makeListingPayment');
    },
    closeMakeListingPayment: function(){
        this.pages.depth--;
        this.closePage('makeListingPayment');
    },

    closeAddListingProcess: function() {
        this.closeMakeListingPayment();
        this.closePreviewAddListing();
        this.closeAddListingDetails();
        this.closeAddPaidListing2FA();
        this.closeAddPaidListingSearchResults();
        this.closeSearchPage();
        this.closeAddListingSearch();
        this.closeAddPaidListing();
    },

    payListingLater: function(){
        _this = this;
        this.showMessage(
            `Your unpaid listing is saved under 'Paid Listings'. <br/><br/> 
            You can open it anytime to make payment and publish it in the directory`,
            [{
                text: "Ok",
                onTap: async function () {
                    await _this.aGetMyListings();
                    await _this.aGetListings();
                    _this.updateAllListings();
                    _this.closeAddListingProcess();
                }
            }]
        ); 
    },

    cancelCreateNewListing: function(){
        _this = this;
        this.showMessage(
            `Are you sure you want to cancel this listing? <br/><br/> This action cannot be undone and you will have to start from scratch.`,
            [{
                text: "Yes",
                onTap: function () {
                    _this.closeAddListingProcess();
                }
            }, {
                text: "No",
                onTap: function() {
                    
                }
            }]
        ); 
    }

});
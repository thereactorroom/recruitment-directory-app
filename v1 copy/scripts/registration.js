

var registration_methods = Object.freeze({

    searchPreReg: function() {
        _this = this;
        _action = this.registration.search.action;
        $.get(this.urls.main + "registration/search", {
            user_key_id: this.user_key.id,
            unique_key_id: this.unique_key_id,
            dev_id: this.unique_key_dev_id,
            business_name: this.registration.search.name,
            mobile: this.registration.search.mobile,
            action: _action
        }, function (response) {
            if (response.status == true) {
                console.log("here after search");
                _this.registration.search.results = response.data;
                _this.showSearchResultsPage();
            }
        }, 'json')
        .fail(function(response) {
            _this.showMessage(response.error);
        });
    },
    searchReferrals: function() {

    },

    saveBusinessDetails: async function() {
        _this = this;
        this.showLoader("Saving business details...");
        const response = await $.post(this.urls.main + "registration/checkingBusinessExists", {
            user_key_id: this.user_key.id,
            unique_key_id: this.unique_key_id,
            business_name: this.registration.business_name,
            contact_number: this.registration.contact_number,
        }, 'json');
        var results = await response;
        if (results.status == true && results.data == true) {
            this.hideLoader();
            this.showMessage("A business with the same name and contact number already exists. Please check your details or contact support for assistance.");
        } else if (results.status == true && results.data == false) {
            // console.log('continue form here');
            // this.hideLoader();
            $.post(this.urls.main + "registration/details", {
                user_key_id: this.user_key.id,
                unique_key_id: this.unique_key_id,
                business_id: this.registration.id,
                source: this.registration.source,
                business_name: this.registration.business_name,
                vat_number: this.registration.vat_number,
                registration_number: this.registration.registration_number,
                country_code: this.registration.country_code,
                contact_number: this.registration.contact_number,
                office_number: this.registration.office_number,
                email: this.registration.email,
                person_name: this.registration.person_name,
                person_surname: this.registration.person_surname,
                description: this.registration.description,
                logo: this.registration.logo,
                google_url: this.registration.google_url,
                website_url: this.registration.website_url,
                facebook_url: this.registration.facebook_url,
                x_url: this.registration.x_url,
                instagram_url: this.registration.instagram_url,
            }, function (response) {
                _this.hideLoader();
                if (response.status == true && response.data) {
                    _this.registration.id = response.data.id;
                    _this.showRegistrationPaymentPage();
                } else {
                    _this.showMessage(response.message);
                }
            }, 'json')
            .fail(function (response) {
                _this.showMessage(response.responseText);
            });
        }
    },

    generatePayFastIdentifier: async function() {
        _this = this;
        this.showLoader("Generating payment form...");
        const response = await $.post(this.urls.main + "payments/generatePayFastIdentifier", {
            user_key_id: this.user_key.id,
            unique_key_id: this.unique_key_id,
            business_id: this.registration.id,
        }, 'json');
        this.hideLoader();
        var results = await response;

        let uuid = "";
        if (results.status == true && results.data) {
            uuid = results.data;
            console.log('payfast uuid:', uuid);
        }
        return new Promise((resolve, reject) => resolve(uuid));
    },
    
    selectBusiness: function(business) {
        this.registration.id = business.id,
        this.registration.source = business.source,
        this.registration.business_name = business.business_name;
        this.registration.contact_number = business.mobile;
        this.registration.office_number = business.mobile;
        this.registration.email = business.email;
        this.registration.description = business.description;
        this.registration.person_name = this.member.name;
        this.registration.person_surname = this.member.surname;
        console.log(business.email);
        console.log(business.mobile);
        if (business.mobile.length > 0) {
            this.registration.maskMobile = this.maskMobileNumber(business.mobile);
        }
        if (business.email.length > 0) {
            this.registration.maskEmail = this.maskEmailAddress(business.email);
        }
        this.show2FAPage();
    },
    searchWithMobile: function() {
        this.registration.search.action = "mobile";
        this.registration.search.showSearchMobile = true;
        this.closeSearchResultsPage();
    },
    verify2FA: function(action) {
        _this = this;
        if (this.isEmpty(this.registration.search._2fa)) {
            this.showMessage("Please enter the required " + action + " value.");
            return;
        }
        var passed = false;
        var _2fa = this.registration.search._2fa;
        if (action == "email") {
            if (_2fa.toLowerCase() == this.registration.email.toLowerCase()) {
                passed = true;
            }
        } else if (action == "mobile") {
            var _mobile = this.registration.contact_number.replace("+27", "0");
            if (_2fa.toLowerCase() == _mobile.toLowerCase()) {
                passed = true;
            }
        }
        if (passed) {
            this.showMessage(action + " verified successfully.", [{
                text: "Ok",
                onTap: function () { 
                    _this.showBusinessDetailsPage();
                }
            }]);
        } else {
            this.showMessage("This is not the correct " + action + " to Verify Ownership of this business.");
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
        return this.ast(mobile.substring(0, mobile.length - 4).length) + mobile.substring(mobile.length - 4, mobile.length);
    },
    ast: function(length) {
        s = ""
        for(var i = 0; i < length; i++) {
            s += "*";
        }
        return s;
    },
    changeTab: function(tab) {
        this.pages.registration.tab = tab;
        $(`#${tab}Tab${this.unique_key}`).addClass(`f-act-bg-${this.unique_key}`);
        $(`#${tab}Tab${this.unique_key}`).siblings().removeClass(`f-act-bg-${this.unique_key}`);
    },
    toggleOtherChannel: function(){
        this.registration.show_other = !this.registration.show_other;
    },
    cancelListing: function(){
        _this = this;
        this.showMessage(
            `Are you sure you want to cancel this listing? <br/><br/> This action cannot be undone and you will have to start from scratch.`,
            [{
                text: "Yes",
                onTap: function () {
                    _this.exit();
                }
            }, {
                text: "No",
                onTap: function() {
                    
                }
            }]
        ); 
    },
    registrationNoLogo: function (trader) { 
        const names = trader.business_name.split(" ");
        if (names.length == 1) {
            return names[0].substring(0, 1);
        } else {
            return names[0].substring(0, 1) + names[1].substring(0, 1);
        }
    },
    selectLogoFromFileRegistration: function(){
        $("#registrationBusinessLogo").click();
    },
    selectRegistrationContact: function(number = ''){
        _this = this;
        if (this.settings.mobile()) {
            hook = '';
            if (number == 'contact_number') {
                this.registration.contact_number = '';
                hook = `fusion.componentsInstance.service_directory${this.unique_key}._this.decodelContactNumber`;
            } else if (number == 'office_number') {
                this.registration.office_number = '';
                hook = `fusion.componentsInstance.service_directory${this.unique_key}._this.decodelOfficeNumber`;
            }
            CommunicationBridge.postMessage(JSON.stringify({
                request: 'phoneContact',
                payload: {},
                hook: hook
            }));
        }
    },
    decodelContactNumber: function(data){
        let decoded = decodeMobileNumber(data.phones[0].number);
        this.contact.flag = decoded.icon;
        this.registration.country_code = decoded.code;
        this.registration.contact_number = decoded.code + decoded.number;
    },
    decodelOfficeNumber: function(data){
        let decoded = decodeMobileNumber(data.phones[0].number);
        this.contact.flag = decoded.icon;
        this.registration.country_code = decoded.code;
        this.registration.office_number = decoded.code + decoded.number;
    },


    /**
     * 
     */
    showLandingPage: function() {
        if (this.permissions.visitor) {
            this.showMessage(`You are not authorised to post ratings.<br/><br/>To gain access, accept the T&C's in the main menu ≡ (Top Right).`, [{
                text: "Dismiss",
                onTap: function () { }
            }]);
        } else {
            this.pages.depth++;
            this.registration.searchBy = "name";
            this.pages.registration.landing = true;
        }
    },
    closeLandingPage: function() {
        this.pages.registration.landing = false;
        if (this.is_registration) {
            // this.closeMyBusinessesPage();
            // this.pages.depth = 0;
            this.exit();
        } else {
            this.pages.depth--;
        }
    },

    showSearchPage: function() {
        this.pages.depth++;
        this.pages.registration.search = true;
    },
    closeSearchPage: function() {
        this.pages.depth--;
        this.registration.clear();
        this.pages.registration.search = false;
    },

    showSearchResultsPage: function() {
        this.pages.depth++;
        this.pages.registration.results = true;
    },
    closeSearchResultsPage: function() {
        this.pages.depth--;
        this.registration.clear();
        this.pages.registration.results = false;
    },

    show2FAPage: function() {
        this.pages.depth++;
        this.pages.registration._2fa = true;
    },
    close2FAPage: function() {
        this.pages.depth--;
        this.registration.clear();
        this.registration.search._2fa = "";
        this.pages.registration._2fa = false;
    },

    showBusinessDetailsPage: async function() {
        this.pages.depth++;
        this.pages.navTab = "details";
        this.pages.navTabIndex = 0;
        this.pages.registration.business = true;
    },
    closeBusinessDetailsPage: function() {
        this.pages.depth--;
        this.pages.navTab = "details";
        this.pages.navTabIndex = 0;
        this.pages.registration.business = false;
    },

    showRegistrationCroppiePage: function() {
        this.pages.registration.croppie = true;
    },
    closeRegistrationCroppiePage: function() {
        this.pages.registration.croppie = false;
    },  

    showPreviewListingPage: function() { 
        _this = this;
        let error = false;
        let message = "";

        if (this.isEmpty(this.registration.business_name)) {
            error = true;
            message = "Business Name is required.";
        } else if (this.isEmpty(this.registration.contact_number)) {
            error = true;
            message = "Contact Number is required.";
        } else if (!this.isValidMobile(this.registration.contact_number)) {
            error = true;
            message = "Please enter a valid contact number.";
        } else if (this.isEmpty(this.registration.email)) {
            error = true;
            message = "Email Address is required.";
        }  
        
        if (error) {
            this.showMessage(`${message}`, [{
                text: "Ok",
                onTap: function() {}
            }]);
        } else {
            this.pages.depth--;
            this.pages.registration.preview = true;
        }
    },
    closePreviewListingPage: function(){
        this.pages.depth--;
        this.pages.registration.preview = false;
    },

    showRegistrationPaymentPage: function(){
        this.pages.depth++;
        this.pages.registration.payment = true;
    },
    closeRegistrationPaymentPage: function(){
        this.pages.depth--;
        this.pages.registration.payment = false;
    },

    closeRegistrationPages: function() {
        this.closeRegistrationPaymentPage();
        this.closePreviewListingPage();
        this.closeRegistrationCroppiePage();
        this.closeBusinessDetailsPage();
        this.close2FAPage();
        this.closeSearchResultsPage();
        this.closeSearchPage();
        this.closeLandingPage();
        // this.exit();
    },
    payLater: function(){
        _this = this;
        this.showMessage(
            `Your unpaid business listing is saved under 'My Business Listings'. <br/><br/> 
            You can open it anytime to make payment and publish it in the directory`,
            [{
                text: "Ok",
                onTap: function () {
                    _this.getMyBusinesses();
                    _this.getAdminDashStats();
                    _this.closeRegistrationPages();
                }
            }]
        ); 
    },

    testRegistrationUrl: function(media) {
        var url = '';
        if (media == 'google_url') { url = this.registration.google_url; }
        else if (media == 'website_url') { url = this.registration.website_url; }
        else if (media == 'facebook_url') { url = this.registration.facebook_url; }
        else if (media == 'x_url') { url = this.registration.x_url; }
        else if (media == 'instagram_url') { url = this.registration.instagram_url; }
        console.log(url);

        if(!this.isEmpty(url)) {
            url = this.normalizeUrl(url);
            console.log(url);
            processContent({ contentType: "url", url: url });
        } else {
            this.showMessage(`Invalid URL format`, [{
                text: "OK",
                onTap: function () { }
            }]);
        }
    },

    openPayFastRegistrationComponent: async function(amount) {
        _this = this;
        this.showLoader("Generating payment uuid...");
        const response = await $.post(this.urls.main + "payments/generatePayFastIdentifier", {
            user_key_id: this.user_key.id,
            unique_key_id: this.unique_key_id,
            business_id: this.registration.id,
            amount: amount
        }, 'json');
        this.hideLoader();
        var results = await response;

        let uuid = undefined;
        if (results.status == true && results.data) {
            uuid = results.data;
        }

        if (uuid == undefined) {
            this.showMessage("Error generating payment uuid. Please try again later.");
        } else {
            var contentId = "2512";
            var componentObj = `fpay_v1_${this.community_id}2512`;
            var capp = "service_directory" + this.unique_key;
            fusion.getComponent({
                name: componentObj,
                uri: `/modules/module_dev/payments/v1/index.php?component=true&capp=${capp}&uuid=${uuid}&app=listings`,
                communityId: this.community_id,
                contentId: contentId,
                params: { refresh: true }
            }, function() {
                $(`.fusion-body`).addClass('fusion-overlay');
                $(`.components, .${componentObj}`).show();
            });
        }
    },

});


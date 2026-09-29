

var freeListingComponent = Object.freeze({

    addNewFreeListing: function() {
        _this = this;
        var error = false;
        var message = "";
        if (this.isEmpty(this.newListing.name)) {
            error = true;
            message = "Name is required.";
        } else if (this.isEmpty(this.newListing.contact_number)) {
            error = true;
            message = "Contact is required.";
        } else if (!this.isValidMobile(this.newListing.contact_number)) {
            error = true;
            message = "Please enter a valid mobile number.";
        } else if (this.isEmpty(this.newListing.description)) {
            error = true;
            message = "Listed Services is required.";
        } else if (this.isEmpty(this.newListing.location)) {
            error = true;
            message = "Location is required.";
        }  else if (this.newListing.stars == 0) {
            error = true;
            message = "Rating is required.";
        }
            
        if (error) {
            this.showMessage(message, [{
                text: "Ok",
                onTap: function () { }
            }]);
        } else {
            var name = this.newListing.name;
            if (this.listingExists()) {
                this.showMessage(`
                    It looks like <b>${name}</b> is already listed in this community's
                    business listing.<br/><br/>Would you like to post your rating and comment?
                    `, [{
                        text: "Yes",
                        onTap: function () { 
                            var found = false;
                            var business = {};
                            for (var i = 0; i < _this.listings.all.length; i++) {
                                if (_this.listings.all[i].id === _this.newListing.business_id) {
                                    continue;
                                }
                                var name = _this.listings.all[i].name.toLowerCase();
                                var contact_number = _this.listings.all[i].contact_number.toLowerCase();
                                if ((name == _this.newListing.name.toLowerCase()) || (contact_number == _this.newListing.contact_number.toLowerCase())) {
                                    found = true;
                                    business = _this.listings.all[i];
                                    break;
                                }
                            }
                            if(found) {
                                _this.closeAddFreeListing();
                            }
                        }
                    },{
                        text: "No",
                        onTap: function () { 
                            _this.closeAddFreeListing();
                            _this.scrollToBottom();
                        }
                    }]
                );
            } else {
                var stars = this.newListing.stars;
                if (this.isEmpty(this.newListing.contact_code)) {
                    let decoded = decodeMobileNumber(this.newListing.contact_number);
                    this.newListing.contact_code = decoded.code;
                    this.newListing.contact_number = decoded.number;
                }

                var listing_name = this.newListing.name;
                _this.showLoader("Adding listing");
                $.post(this.urls.main + "listings/insert", {
                    user_key_id: this.settings.user_key_id,
                    unique_key_id: this.settings.unique_key_id,
                    name: this.newListing.name,
                    contact_code: this.newListing.contact_code,
                    contact_number: this.newListing.contact_number,
                    description: this.newListing.description,
                    location: this.newListing.location,
                    stars: this.newListing.stars,
                    comment: this.newListing.comment,
                    admin: this.permissions.admin,
                    type: 'free',
                    source: this.newListing.source
                }, async function (response) {
                    _this.hideLoader();
                    if (response.status == true) {
                        // _this.getFavorites();
                        // _this.getReferrals();
                        // _this.getAdminDashStats();

                        await _this.aGetListings();
                        _this.updateAllListings();

                        _this.newListing.clear();
                        _this.clearRatingStars(stars, "add");
                        _this.showMessage(`
                            Thanks for listing <b>${listing_name}</b> to the
                            physio listings.<br/><br/>Would you like to view your listing?
                            `, [{
                                text: "Yes",
                                onTap: function () {
                                    _this.closeAddFreeListing();
                                    _this.scrollToBottom();
                                }
                            },{
                                text: "No",
                                onTap: function () {
                                    _this.closeAddFreeListing();
                                }
                            }]
                        );
                    } else {
                        _this.showMessage(response.message);
                    }
                }, 'json')
                .fail(function (response) {
                    _this.showMessage(response.responseText);
                });
            }
        }
    },

    updateListing: function() {
        _this = this;
        var error = false;
        var message = "";

        if (this.isEmpty(this.listing.name)) {
            error = true;
            message = "Listing Name is required.";
        } else if (this.isEmpty(this.listing.contact_number)) {
            error = true;
            message = "Contact Number is required.";
        } else if (!this.isValidMobile(this.listing.contact_number)) {
            error = true;
            message = "Please enter a valid contact number.";
        } 
        
        if (this.listing.free == 0) {
            if (this.isEmpty(this.listing.email)) {
                error = true;
                message = "Email Address is required.";
            } else if (!this.isValidEmail(this.listing.email)) {
                error = true;
                message = "Please enter a valid email.";
            } 
        } 
        
        if (error) {
            this.showMessage(message);
        } else {
            var free = this.listing.free;
            this.showLoader("Saving business details...");
            $.post(this.urls.main + "listings/update", {
                user_key_id: this.settings.user_key_id,
                unique_key_id: this.settings.unique_key_id,
                id: this.listing.id,
                name: this.listing.name,
                contact_code: this.listing.contact_code,
                contact_number: this.listing.contact_number,
                office_number: this.listing.office_number,
                email: this.listing.email,
                dob: this.listing.dob,
                gender: this.listing.gender,
                age: this.calculateAge(this.listing.dob),
                description: this.listing.description,
                location: this.listing.location,
                cv_path: this.listing.cv_path,
                logo: this.listing.logo,
                google_url: this.listing.google_url,
                website_url: this.listing.website_url,
                facebook_url: this.listing.facebook_url,
                x_url: this.listing.x_url,
                instagram_url: this.listing.instagram_url
            }, async function (response) {
                _this.hideLoader();
                if (response.status == true && response.data) {
                    _this.listing = response.data;
                    await _this.aGetMyListings();
                    await _this.aGetListings();
                    _this.updateAllListings();
                    if (free == 1) {
                        _this.closeEditFreeListing();
                    } else {
                        _this.closeEditPaidListing();
                    }
                } else {
                    _this.showMessage(response.message);
                }
            }, 'json')
            .fail(function (response) {
                _this.showMessage(response.responseText);
            });
        }
    },

    deleteListing: function() {
        _this = this;
        this.showMessage(`
            Are you sure you want to delete <br/><b>${this.listing.name}</b>?
            <br/><br/>
            This action cannot be reversed.
            `, [{
                text: "Yes",
                onTap: function () { 
                    var free = _this.listing.free;
                    _this.showLoader("Deleting business details...");
                    $.post(_this.urls.main + "listings/delete", {
                        user_key_id: _this.settings.user_key_id,
                        unique_key_id: _this.settings.unique_key_id,
                        id: _this.listing.id
                    }, async function (response) {
                        _this.hideLoader();
                        if (response.status == true) {
                            await _this.aGetMyListings();
                            await _this.aGetListings();
                            _this.updateAllListings();
                            if (free == 1) {
                                _this.closeEditFreeListing();
                                _this.closeViewFreeListing();
                            } else {
                                _this.closeViewPaidListing();
                            }
                        } else {
                            _this.showMessage(response.message);
                        }
                    }, 'json')
                    .fail(function (response) {
                        _this.showMessage(response.responseText);
                    });
                }
            },{
                text: "No",
                onTap: function () { 
                }
            }]
        );
    },

    reSubmitListing: function() {
        _this = this;
        this.showLoader("Saving business details...");
        $.post(this.urls.main + "businesses/reSubmit", {
            id: this.listing.id
        }, function (response) {
            _this.hideLoader();
            if (response.status == true && response.data) {
                _this.listing = response.data;
                _this.getMyListings();
                _this.getAllBusinesses();
            } else {
                _this.showMessage(response.message);
            }
        }, 'json')
        .fail(function (response) {
            _this.showMessage(response.responseText);
        });
    },

    searchFreeListings: function(){
        _this = this;
        _action = this.newListing.search.action;
        $.get(this.urls.main + "listing/search", {
            user_key_id: this.user_key_id,
            unique_key_id: this.unique_key_id,
            name: this.newListing.search.name,
            mobile: this.newListing.search.mobile,
            action: _action,
            free: 1
        }, function (response) {
            if (response.status == true) {
                console.log("here after search");
                _this.newListing.search.results = response.data;
                _this.showSearchResultsPage();
            }
        }, 'json')
        .fail(function(response) {
            _this.showMessage(response.error);
        });
    },

    nextTab: function(page = undefined) {        
        if (this.detailsTabs.index >= this.detailsTabs.tabs.length - 1) {
            return;
        }
        
        console.log('index: ', this.detailsTabs.index);
        this.detailsTabs.index++;
        var previous = this.detailsTabs.tab;
        var current = this.detailsTabs.tabs[this.detailsTabs.index];
        this.detailsTabs.tab = current;

        console.log('index: ', this.detailsTabs.index);
        console.log('previous: ', previous);
        console.log('current: ', current);

        $(`.${current}Tab${this.settings.unique_key}`).addClass(`f-act-bg-${this.settings.unique_key}`);
        $(`.${previous}Tab${this.settings.unique_key}`).removeClass(`f-act-bg-${this.settings.unique_key}`);
        $(`.${previous}Tab${this.settings.unique_key}`).addClass(`f-nav-prev-bg`);
    },

    backTab: function(page = undefined) {
        if (this.detailsTabs.index <= 0) {
            return;
        }

        var previous = this.detailsTabs.tabs[this.detailsTabs.index];
        this.detailsTabs.index--;

        var current = this.detailsTabs.tabs[this.detailsTabs.index];
        this.detailsTabs.tab = current;

        $(`.${current}Tab${this.settings.unique_key}`).removeClass(`f-nav-prev-bg`);
        $(`.${current}Tab${this.settings.unique_key}`).addClass(`f-act-bg-${this.settings.unique_key}`);
        $(`.${previous}Tab${this.settings.unique_key}`).removeClass(`f-act-bg-${this.settings.unique_key}`);
    },

    showSelectCVFromFile: function(action) {
        this.listingFiles.action = action;
        console.log(this.listingFiles.action, action, `#select${action}ListingCVFromFile`);
        $(`#select${action}ListingCVFromFile`).click();
    },
    handleUploadCVFromFile: function(files) {
        _this = this;
        let file = undefined;
        for (let i = 0; i < files.length; i++) {
            if (files[i].type === "application/pdf") {
                file = files[i];
                break;
            }
        }

        if (file !== undefined) {
            var reader = new FileReader();
            reader.onloadend = function() {
                if (_this.listingFiles.action == "New") {
                    _this.newListing.cv_path = reader.result;
                } else if (_this.listingFiles.action == "Edit") {
                    if (_this.listing != undefined) {
                        _this.listing.cv_path = reader.result;
                    }
                }
            } 
            reader.readAsDataURL(file);
        }
    },

    showSelectLogoFromFile: function(action) {
        this.listingFiles.action = action;
        console.log(this.listingFiles.action, action, `#select${action}ListingLogoFromFile`);
        $(`#select${action}ListingLogoFromFile`).click();
    },
    handleUploadLogoFromFile: function(files) {
        _this = this;
        let file = undefined;
        for (let i = 0; i < files.length; i++) {
            if (files[i].type.match(/^image\//)) {
                file = files[i];
                break;
            }
        }

        if (file !== undefined) {
            var reader = new FileReader();
            reader.onloadend = function() {
                if (_this.listingFiles.action == "New") {
                    _this.newListing.logo = reader.result;
                } else if (_this.listingFiles.action == "Edit") {
                    if (_this.listing != undefined) {
                        _this.listing.logo = reader.result;
                    }
                }
            }
            reader.readAsDataURL(file);
        }
    },

    displaySelectedListingLogo: function(logo) {
        return `background-image: url(${logo})`; 
    },

    /**
     * Shows the add free listing page.
     */
    showAddFreeListing: function() {
        this.pages.depth++;
        this.openPage('addFreeListing');
    },
    closeAddFreeListing: function() {
        this.pages.depth--;
        this.closePage('addFreeListing');
    },

    showViewFreeListing: function(){
        this.pages.depth++;
        this.openPage('viewFreeListing');
    },
    closeViewFreeListing: function(){
        this.pages.depth--;
        this.closePage('viewFreeListing');
    },

    showEditFreeListing: function(){
        this.pages.depth++;
        this.openPage('editFreeListing');
    },
    closeEditFreeListing: function(){
        this.pages.depth--;
        this.closePage('editFreeListing');
    },

});


/*
        // if (page == 'view_listing' && this.detailsTabs.index == 0) {
        //     if (this.isEmpty(this.listing.name)) {
        //         this.showMessage("Listing Name is required.");
        //         return false;
        //     } else if (this.isEmpty(this.listing.email)) {
        //         this.showMessage("Email Address is required.");
        //         return false;
        //     } else if (!this.isValidEmail(this.listing.email)) {
        //         this.showMessage("Please enter a valid email.");
        //         return false;
        //     } else if (this.isEmpty(this.listing.contact_number)) {
        //         this.showMessage("Contact Number is required.");
        //         return false;
        //     } else if (!this.isValidMobile(this.listing.contact_number)) {
        //         this.showMessage("Please enter a valid contact number.");
        //         return false;
        //     }
        // } else if (page == 'create_listing' && this.detailsTabs.index == 0) {
        //     if (this.isEmpty(this.newListing.name)) {
        //         this.showMessage("Listing Name is required.");
        //         return false;
        //     } else if (this.isEmpty(this.newListing.email)) {
        //         this.showMessage("Email Address is required.");
        //         return false;
        //     } else if (this.isEmpty(this.newListing.email)) {
        //         this.showMessage("Email Address is required.");
        //         return false;
        //     } else if (!this.isValidEmail(this.newListing.email)) {
        //         this.showMessage("Please enter a valid email.");
        //         return false;
        //     } else if (this.isEmpty(this.newListing.contact_number)) {
        //         this.showMessage("Contact Number is required.");
        //         return false;
        //     } else if (!this.isValidMobile(this.newListing.contact_number)) {
        //         this.showMessage("Please enter a valid contact number.");
        //         return false;
        //     }
        // }
*/

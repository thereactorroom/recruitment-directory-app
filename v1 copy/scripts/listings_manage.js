

var manage_listings_methods = Object.freeze({

    /**
     * getTraders: returns a list of traders
     * @param {*} category 
     */
    getAllLisgings: function(){
        _this = this;
        this.loading.businesses = true;
        $.get(this.urls.main + "businesses/list", {
            user_key_id: this.user_key.id,
            unique_key_id: this.unique_key_id
        }, function (response) {
            _this.loading.businesses = false;
            if (response.status == true) {
                for (var i = 0; i < response.data.length; i++) {
                    var business = response.data[i];
                    business.display_name = business.business_name;
                    if (business.display_name.length > 38) {
                        business.display_name = business.display_name.substring(0, 38) + "...";
                    }
                    response.data[i] = business;
                }
                _this.businesses.allAdmin = response.data;
                _this.businesses.allAdminAll = response.data;
            }
        }, 'json')
        .fail(function(response) {
            _this.showMessage(response.error);
        });
    },

    getDowngradedListings: function(){
        _this = this;
        this.loading.businesses = true;
        $.get(this.urls.main + "businesses/downgraded", {
            user_key_id: this.user_key.id,
            unique_key_id: this.unique_key_id
        }, function (response) {
            _this.loading.businesses = false;
            if (response.status == true) {
                for (var i = 0; i < response.data.length; i++) {
                    var business = response.data[i];
                    business.display_name = business.business_name;
                    if (business.display_name.length > 38) {
                        business.display_name = business.display_name.substring(0, 38) + "...";
                    }
                    response.data[i] = business;
                }
                _this.businesses.allAdmin = response.data;
                _this.businesses.allAdminAll = response.data;
            }
        }, 'json')
        .fail(function(response) {
            _this.showMessage(response.error);
        });
    },

    aGetDowngradedListings: async function(){
        const response = await $.get(this.urls.main + "businesses/downgraded", {
            user_key_id: this.user_key.id,
            unique_key_id: this.unique_key_id,
        }, 'json');
        var results = await response;
        if (results.status == true && results.data) {
            for (var i = 0; i < response.data.length; i++) {
                var business = results.data[i];
                business.display_name = business.business_name;
                if (business.display_name.length > 38) {
                    business.display_name = business.display_name.substring(0, 38) + "...";
                }
                results.data[i] = business;
            }
            this.businesses.allAdmin = results.data;
            this.businesses.allAdminAll = results.data;
        }
    },



    downgradeListing: function() {
        _this = this;
        var business_name = this.businesses.business.business_name;
        this.showMessage(`
            Are you sure you want to Downgrade <br/><b>${business_name}</b>?
            `, [{
                text: "Yes",
                onTap: function () { 
                    _this.showLoader("Downgrading business details...");
                    $.post(_this.urls.main + "businesses/downgrade", {
                        user_key_id: _this.user_key.id,
                        unique_key_id: _this.unique_key_id,
                        id: _this.businesses.business.id
                    }, async function (response) {
                        _this.hideLoader();
                        if (response.status == true && response.data) {
                            _this.businesses.business = response.data;
                            _this.getAllBusinesses();
                            await _this.aGetApprovedBusinesses();
                            _this.updateAllBusinesses();
                            _this.closeManageListingPage();
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

    upgradeListing: function() {
        _this = this;
        var business_name = this.businesses.business.business_name;
        this.showMessage(`
            Are you sure you want to Upgrade <br/><b>${business_name}</b>?
            `, [{
                text: "Yes",
                onTap: function () { 
                    _this.showLoader("Upgrding business details...");
                    $.post(_this.urls.main + "businesses/upgrade", {
                        user_key_id: _this.user_key.id,
                        unique_key_id: _this.unique_key_id,
                        id: _this.businesses.business.id
                    }, async function (response) {
                        _this.hideLoader();
                        if (response.status == true && response.data) {
                            // _this.businesses.business = response.data;
                            // _this.getAllBusinesses();
                            // await _this.aGetApprovedBusinesses();
                            // _this.updateAllBusinesses();

                            _this.getDowngradedBusiness();
                            _this.closeManageListingPage();
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

    deleteListing: function() {
        _this = this;
        var business_name = this.businesses.business.business_name;
        this.showMessage(`
            Are you sure you want to Delete <br/><b>${business_name}</b>? <br/><br/> Please note that this action cannot be reversed.
            `, [{
                text: "Yes",
                onTap: function () { 
                    _this.showLoader("Downgrading business details...");
                    $.post(_this.urls.main + "businesses/delete", {
                        user_key_id: _this.user_key.id,
                        unique_key_id: _this.unique_key_id,
                        id: _this.businesses.business.id,
                        action: 'full'
                    }, async function (response) {
                        _this.hideLoader();
                        if (response.status == true && response.data) {
                            _this.getAllBusinesses();
                            await _this.aGetApprovedBusinesses();
                            _this.updateAllBusinesses();
                            _this.closeManageListingPage();
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

    mergeListings: function(){
        _this = this;
        if (this.mergeMainListing == undefined) {
            this.showMessage(`Please select a main listing to merge to.`);
            return false;
        }
        if (this.mergeSecondListing == undefined) {
            this.showMessage(`Please select a second listing to merge to.`);
            return false;
        }
        var main_business_name = this.mergeMainListing.business_name;
        var second_business_name = this.mergeSecondListing.business_name;
        this.showMessage(`
            Are you sure you want to merge <br/><b>${second_business_name}</b> to <b>${main_business_name}</b>? 
            <br/><br/> Please note that this action cannot be reversed.
            `, [{
                text: "Yes",
                onTap: function () { 
                    _this.showLoader("Merging business listings...");
                    $.post(_this.urls.main + "businesses/merge", {
                        user_key_id: _this.user_key.id,
                        unique_key_id: _this.unique_key_id,
                        main_business_id: _this.mergeMainListing.id,
                        second_business_id: _this.mergeSecondListing.id,
                    }, async function (response) {
                        _this.hideLoader();
                        if (response.status == true && response.data) {
                            _this.getAllBusinesses();
                            await _this.aGetApprovedBusinesses();
                            await _this.aGetReferrals();
                            _this.updateAllBusinesses();
                            _this.getAdminDashStats();
                            _this.closeMergeListings();
                            _this.showMessage('Merging has been successful.');
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

    searchManageListingBusinessByName: function (business) {
        var name = business.business_name.toLowerCase();
        var description = business.description.toLowerCase();
        var filter = this.businesses.mainFilter.toLowerCase();

        var name = business.business_name.toLowerCase();
        var description = business.description.toLowerCase();
        var filter = this.businesses.mainFilter.toLowerCase();

        this.businesses.list = this.businesses.list.filter(item => {
            `${item.name}`.indexOf(filter) > -1 || `${item.description}`.indexOf(filter) > -1
        });

        if (`${name}`.indexOf(filter) > -1 || `${description}`.indexOf(filter) > -1) {
            return true;
        }
        return false;
    },

    searchMergeListing: function (business) {
        var name = business.business_name.toLowerCase();
        var description = business.description.toLowerCase();
        var filter = this.mergeListingFilter.toLowerCase();

        if (`${name}`.indexOf(filter) > -1 || `${description}`.indexOf(filter) > -1) {
            return true;
        }
        return false;
    },

    selectMergeListing: function(listing) {

        if (this.mergeOption == 'main') {
            this.mergeMainListing = listing;
        } else if (this.mergeOption == 'second') {
            this.mergeSecondListing = listing;
        }

        this.mergeListingFilter = "";
        this.closeSelectMergeListing();

        // this.mergeListingsIds = [];
        // for (let i = 0; i < this.getManageListingsList.length; i++) {
        //     var item = this.getManageListingsList[i];
        //     $('#mergeListing' + item.id).removeClass('f-act-bg-' + this.unique_key);

        //     if (item.id != listing.id) {
        //         this.mergeListingsIds.push(item.id);
        //     }
        // }
        // this.mergeMainListing = listing;
        // $('#mergeListing' + listing.id).addClass('f-act-bg-' + this.unique_key);
    },


    /**  */
    

    showManageListingPage: function(business) {
        this.pages.depth++;
        this.businesses.business = business;
        this.openPage('manageListing');
    },
    closeManageListingPage: function() {
        this.pages.depth--;
        this.businesses.business = {};
        this.closePage('manageListing');
    },

    showMergeListings: function(){
        if (this.getManageListingsList.length < 2) {
            this.showMessage(
                `You can merge 2 or more Business Listings`, [{
                    text: "No",
                    onTap: function () { }
                }]
            );
            return false;
        } else {
            this.pages.depth++;
            this.openPage('mergeListings');
        }
    },
    closeMergeListings: function() {
        this.pages.depth--;
        this.mergeOption = "main";
        this.mergeListingFilter = "";
        this.mergeMainListing = undefined;
        this.mergeSecondListing = undefined;
        this.closePage('mergeListings');
    },

    showSelectMergeListing: function(option){
        this.mergeList = [];
        for (let i = 0; i < this.getManageListingsList.length; i++) {
            var item = this.getManageListingsList[i];

            if (option == "main" && item.referral == '1') continue;
            if (option == "second" && item.referral == '0') continue;
            
            this.mergeList.push(item);
        }

        this.pages.depth++;
        this.mergeOption = option;
        this.openPage('selectMergeListing');
    },
    closeSelectMergeListing: function(){
        this.pages.depth--;
        this.mergeList = [];
        this.closePage('selectMergeListing');
    },

    showMergeListingDetails: function(listing){
        this.pages.depth++;
        this.mergeListing = listing;
        this.businesses.business = listing;
        this.showEditBusinessListingPage();
        // this.openPage('mergeListingDetails');
    },
    closeMergeListingDetails: function(){
        this.pages.depth--;
        this.mergeListing = undefined;
        this.businesses.business = undefined;
        this.closeEditBusinessListingPage();
        // this.closePage('mergeListingDetails');
    },

    showDowngradedListings: function(){
        this.pages.depth++;
        this.getDowngradedBusiness();
        this.openPage('downgradedListings');
    },
    closeDowngradedListings: function (){
        this.pages.depth--;
        this.closePage('downgradedListings');
    },

    showListingCallLog: function (listing) {
        this.pages.depth++;
        this.businesses.business = listing;
        this.getReferralCallLog();
        this.openPage('listingCallLog');
    },
    closeListingCallLog: function () {
        this.pages.depth--;
        // this.businesses.business = undefined;
        this.closePage('listingCallLog');
    },

    showListingCallLogComment: function (call_log_item) {
        if (call_log_item.comment.length > 0) {
            _this.showMessage("Comment already added");
        } else {
            this.pages.depth++;
            this.referrals.call_log_item = call_log_item;
            this.openPage('listingCallLogComment');
        }
    },
    closeListingCallLogComment: function () {
        this.pages.depth--;
        this.referrals.call_log_item = {};
        this.closePage('listingCallLogComment');
    },

    showListingCallWhatsApp: function(){
        this.pages.depth++;
        this.openPage('listingCallWhatsApp');
    },
    closeListingCallWhatsApp: function(){
        this.pages.depth--;
        this.closePage('listingCallWhatsApp');
    },

});



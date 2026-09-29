

var listings = Object.freeze({

    // getReferrals: function () {
    //     _this = this;
    //     $.get(this.urls.main + "referrals/list", {
    //         user_key_id: this.user_key.id,
    //         unique_key_id: this.unique_key_id,
    //     }, function (response) {
    //         if (response.status == true) {
    //             _this.referrals.list = response.data;
    //         }
    //     }, 'json')
    //     .fail(function(response) {
    //         _this.showMessage(response.error);
    //     });
    // },

    // aGetReferrals: async function () {
    //     _this = this;
    //     const response = await $.get(this.urls.main + "referrals/list", {
    //         user_key_id: this.user_key.id,
    //         unique_key_id: this.unique_key_id,
    //     }, 'json');
    //     var results = await response;
    //     if (results.status == true) {
    //         this.referrals.list = results.data;
    //     }
    // },

    // getReferraCallLog: function () {
    //     _this l= this;
    //     $.get(this.urls.main + "referrals/call_log", {
    //         unique_key_id: this.businesses.business.unique_key_id,
    //         referral_id: this.businesses.business.referral_id,
    //     }, function (response) {
    //         if (response.status == true) {
    //             _this.referrals.call_log = response.data;
    //         }
    //     }, 'json')
    //     .fail(function(response) {
    //         _this.showMessage(response.error);
    //     });
    // },

    /**
     * 
     */

    addNewFreeListing: function () {
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
                                _this.closeAddReferral();
                                _this.showComments(business);
                            }
                        }
                    },{
                        text: "No",
                        onTap: function () { 
                            _this.closeAddReferral();
                            _this.screen.mainTopBottom = false;
                            _this.scrollToBottom();
                        }
                    }]
                );
            } else {
                var stars = this.newListing.stars;
                // let decoded = decodeMobileNumber(this.newListing.contact_number);

                _this.showLoader("Adding listing");
                $.post(this.urls.main + "listings/insert", {
                    user_key_id: this.user_key.id,
                    unique_key_id: this.unique_key_id,
                    business_name: this.newListing.name,
                    contact_number: this.newListing.contact_number,
                    description: this.newListing.description,
                    stars: this.newListing.stars,
                    comment: this.newListing.comment,
                    admin: this.permissions.admin
                }, function (response) {
                    _this.hideLoader();
                    if (response.status == true) {
                        // _this.getFavorites();
                        _this.getReferrals();
                        _this.getAdminDashStats();

                        var listing = response.data;
                        listing.display_name = listing.name;
                        if (listing.display_name.length > 38) {
                            listing.display_name = listing.display_name.substring(0, 38) + "...";
                        }
                        _this.listings.all.push(listing);
                        _this.listings.list.push(listing);

                        _this.referralClearStars(stars, "add");
                        _this.newListing.clear();
                        _this.showMessage(`
                            Thanks for referring <b>${listing.name}</b> to the community
                            business listing.<br/><br/>Would you like to view your referral?
                            `, [{
                                text: "Yes",
                                onTap: function () {
                                    _this.closeAddReferral();
                                    _this.screen.mainTopBottom = false;
                                    _this.scrollToBottom();
                                }
                            },{
                                text: "No",
                                onTap: function () {
                                    _this.closeAddReferral();
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

    updateReferral: function () {
        _this = this;
        var error = false;
        var message = "";
        if (this.isEmpty(this.referral.business_name)) {
            error = true;
            message = "Name is required.";
        } else if (this.isEmpty(this.referral.contact_number)) {
            error = true;
            message = "Contact is required.";
        } else if (!this.isValidMobile(this.referral.contact_number)) {
            error = true;
            message = "Please enter a valid mobile number.";
        } else if (this.isEmpty(this.referral.description)) {
            error = true;
            message = "Listed Services is required.";
        } else if (this.referral.stars == 0) {
            error = true;
            message = "Rating is required.";
        }

        if (error) {
            this.showMessage(message, [{
                text: "Ok",
                onTap: function () { }
            }]);
        } else {
            var business_name = this.referral.business_name;
            if (this.businessExists()) {
                this.showMessage(`
                    It looks like <b>${business_name}</b> is already listed in this communities
                    business listing.<br/><br/>Would you like to post your rating and comment?
                    `, [{
                        text: "Yes",
                        onTap: function () { 
                            _this.closeAddReferral();
                            _this.screen.mainTopBottom = false;
                            _this.scrollToBottom();
                        }
                    },{
                        text: "No",
                        onTap: function () { }
                    }]
                );
            } else {
                _this.showLoader("Saving referral");
                // let decoded = decodeMobileNumber(this.referral.contact_number);
                // var contact_code = decoded.code;
                // this.referral.contact_number = decoded.number;

                $.post(this.urls.main + "referrals/update", {
                    user_key_id: this.user_key.id,
                    unique_key_id: this.unique_key_id,
                    business_id: this.referral.business_id,
                    business_name: this.referral.business_name,
                    // contact_code: contact_code,
                    contact_number: this.referral.contact_number,
                    description: this.referral.description,
                    stars: this.referral.stars,
                    comment: this.referral.comment
                }, async function (response) {
                    _this.hideLoader();
                    if (response.status == true) {
                        await _this.aGetApprovedBusinesses();
                        await _this.aGetReferrals();
                        _this.updateAllBusinesses();

                        _this.getComments(_this.businesses.business);
                        _this.referralClearStars(_this.referral.stars, "edit");
                        _this.closeEditReferral();
                        _this.screen.mainTopBottom = false;
                        _this.scrollToBottom();
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

    updateReferralsList: function (business) {
        const index = this.referrals.list.findIndex(object => {
            return object.id === business.id;
        });
        this.referrals.list[index] = business;
    },

    deleteReferral: function (referral) {
        _this = this;
        this.showMessage(`
            You are sure you want to delete this referral?.<br/><br/>This action cannot be reversed .`, [
            {
                text: "Delete",
                onTap: function () { 
                    var referral_id = 0;
                    var business_id = 0;
                    var fromAdmin = false;

                    if (referral == undefined) {
                        fromAdmin = true;
                        referral_id = _this.businesses.business.referral_id;
                        business_id = _this.businesses.business.id;
                    } else {
                        referral_id = referral.id;
                        business_id = referral.business_id;
                    }
                    _this.showLoader("Deleting referral");
                    $.post(_this.urls.main + "referrals/delete", {
                        user_key_id: _this.user_key.id,
                        unique_key_id: _this.unique_key_id,
                        business_id: business_id,
                        referral_id: referral_id
                    }, async function (response) {
                        _this.hideLoader();
                        if (response.status == true) {
                            _this.getReferrals();
                            _this.getAdminDashStats();
                            if (fromAdmin) {
                                _this.getReferralCallLog();
                                _this.getReferralBusinesses();
                                _this.closeReferralsCallLog();
                            } else {
                                _this.getAllBusinesses();
                                await _this.aGetReferralBusinesses();
                                await _this.aGetApprovedBusinesses();
                                _this.updateAllBusinesses();
                                _this.closeEditReferral();
                                _this.closeBusinessReferralPage();
                            }
                        }
                    }, 'json')
                    .fail(function (response) {
                        _this.showMessage(response.responseText);
                    });
                }
            },
            {
                text: "Cancel",
                onTap: function () { }
            }
        ]);

    },

    addReferralCallLog: function (type = '') {
        _this = this;
        // 'Not availbale on web'\
        var log_call = true;
        if (type == 'call') {
            if (this.settings.mobile()) {
                this.openDialerApp(this.businesses.business.contact_number);
            } else {
                log_call = false;
                this.showMessage('Not available on Web.', [{
                    text: "Ok",
                    onTap: function () { 
                        return false;
                    }
                }]);
            }
        } else if (type == 'whatsapp') {
            this.openWhatsAppApp(this.businesses.business.contact_number);
        }
        if (log_call) {
            $.post(this.urls.main + "referrals/add_call_log", {
                user_key_id: this.user_key.id,
                unique_key_id: this.unique_key_id,
                referral_id: this.businesses.business.referral_id
            }, function (response) {
                if (response.status == true) {
                    _this.getReferrals();
                    _this.getReferralCallLog();
                    _this.getReferralBusinesses();
                }
            }, 'json')
            .fail(function(response) {
                _this.showMessage(response.error);
            });
        }
    },

    addCallLogComment: function () {
        _this = this;
        if (this.referrals.call_log_item.comment.length > 0) {
            _this.showMessage("Comment already added");
        } else {
            console.log(this.referrals.call_log_item);
            $.post(this.urls.main + "referrals/add_call_log_comment", {
                id: this.referrals.call_log_item.id,
                comment: this.referrals.callLogComment,
            }, function (response) {
                if (response.status == true) {
                    _this.referrals.callLogComment = "";
                    _this.getReferrals();
                    _this.closeReferralCallLogComment();
                    _this.closeListingCallLogComment();
                    _this.getReferralCallLog();
                }
            }, 'json')
            .fail(function (response) {
                _this.showMessage(response.error);
            });
        }
    },

    /**
     * 
     */

    listingExists: function () {
        var exists = false;
        for (var i = 0; i < this.listings.all.length; i++) {
            if (this.listings.all[i].id === this.new.business_id) {
                continue;
            }
            var name = this.listings.all[i].name.toLowerCase();
            var contact_number = this.listings.all[i].contact_number.toLowerCase();
            if ((name == this.newListing.name.toLowerCase()) || (contact_number == this.newListing.contact_number.toLowerCase())) {
                exists = true;
                break;
            }
        }
        return exists;
    },

    getReferredUserKeyId: function (business) {
        for (var i = 0; i < this.referrals.list.length; i++) {
            if (this.referrals.list[i].business_id == business.id){
                return this.referrals.list[i].user_key_id;
            }
        }
        return 0;
    },

    getReferredFromList: function (business) {
        for (var i = 0; i < this.referrals.list.length; i++) {
            if (this.referrals.list[i].business_id == business.id){
                return this.referrals.list[i];
            }
        }
        return undefined;
    },

    selectNewListingContact: function(){
        _this = this;
        if (this.settings.mobile()) {
            CommunicationBridge.postMessage(JSON.stringify({
                request: 'phoneContact',
                payload: {},
                hook: `fusion.componentsInstance.service_directory${this.unique_key}._this.decodeNewListingContact`
            }));
        }
    },
    decodeNewListingContact: function(data){
        let decoded = decodeMobileNumber(data.phones[0].number);
        this.contact.flag = decoded.icon;
        this.newListing.code = decoded.code;
        this.newListing.contact_number = decoded.code + decoded.number;
    },

    newListingSelectStars: function (stars, action) {
        if (stars == 1) {
            if ($(`#${action}_rec_star_1_${this.unique_key}`).hasClass('f-gold-star-icon')) {
                this.newListing.stars = 0;
                $(`#${action}_rec_star_1_${this.unique_key}`).addClass('f-grey-star-icon');
                $(`#${action}_rec_star_1_${this.unique_key}`).removeClass('f-gold-star-icon');
            } else {
                this.newListing.stars = 1;
                $(`#${action}_rec_star_1_${this.unique_key}`).addClass('f-gold-star-icon');
                $(`#${action}_rec_star_1_${this.unique_key}`).removeClass('f-grey-star-icon');
            }
        } else {
            this.newListing.stars = stars;
            for (var i = 1; i <= stars; i++) {
                $(`#${action}_rec_star_${i}_${this.unique_key}`).addClass('f-gold-star-icon');
                $(`#${action}_rec_star_${i}_${this.unique_key}`).removeClass('f-grey-star-icon');
            }
        }
        for (var i = stars + 1; i <= 5; i++) {
            $(`#${action}_rec_star_${i}_${this.unique_key}`).removeClass('f-gold-star-icon');
            $(`#${action}_rec_star_${i}_${this.unique_key}`).addClass('f-grey-star-icon');
        }
    },

    newListingClearStars: function (stars, action) {
        for (var i = 1; i <= stars; i++) {
            $(`#${action}_rec_star_${i}_${this.unique_key}`).removeClass('f-gold-star-icon');
            $(`#${action}_rec_star_${i}_${this.unique_key}`).addClass('f-grey-star-icon');
        }
    },

    searchReferralsByName: function (business) {
        var name = business.business_name.toLowerCase();
        var description = business.description.toLowerCase();
        var filter = this.referrals.mainFilter.toLowerCase();
        if (`${name}`.indexOf(filter) > -1 || `${description}`.indexOf(filter) > -1) {
            return true;
        }
        return false;
    },

    /**
     * 
     */

    showAddFreeListing: function() {
        if (this.permissions.visitor) {
            this.showMessage(`
                You are not authorized to post listings.<br/><br/>To gain access, accept the T&C's in the main menu ≡ (Top Right).`, [
                {
                    text: "Dismiss",
                    onTap: function () { }
                }
            ]);
        } else if (this.isMemberBlacklisted(this.member)) {
            this.showMessage(`
                You are blacklisted and not authorized to post listings.<br/><br/>Please contact support for assistance.`, [
                {
                    text: "Dismiss",
                    onTap: function () { }
                }
            ]);
        } else {
            this.pages.depth++;
            this.newListingClearStars(5, 'add');
            this.openPage('addFreeListing');
        }
    }, 
    closeAddFreeListing: function() {
        this.pages.depth--;
        this.newListing.clear();
        this.closePage('addFreeListing');
    },

    showEditReferral: async function (business) {
        this.pages.depth++;
        var comment = {};
        var referral = this.getReferredFromList(business);
        const response = await $.get(this.urls.main + "referrals/comment", {
            user_key_id: referral.user_key_id,
            unique_key_id: referral.unique_key_id,
            business_id: business.id
        }, 'json');

        var results = await response;
        if (results.status == true) {
            comment = results.data;
        }

        this.referral.id = referral.id;
        this.referral.business_id = referral.business_id;
        this.referral.business_name = business.business_name;
        this.referral.contact_number = business.contact_number;
        this.referral.description = business.description;
        this.referral.comment = comment.comment;
        this.referral.stars = comment.stars;

        decoded = decodeMobileNumber(business.contact_number);
        this.contact.flag = decoded.icon;

        console.log('stars: ', this.referral.stars, '=', comment.stars);

        this.referralSelectStars(this.referral.stars, "edit");
        this.openPage('editReferral');
    },
    closeEditReferral: function () {
        this.pages.depth--;
        this.referralClearStars(this.referral.stars, "edit");
        this.referral.clear();
        this.closePage('editReferral');
    },

    showReferralsAdmin: function () {
        this.pages.depth++;
        this.pages.page = "referralsAdmin";
        this.getReferralBusinesses();
        this.openPage('referralsAdmin');
    },
    closeReferralsAdmin: function() {
        this.pages.depth--;
        this.pages.page = "";
        this.businesses.sort.nameAscending = false;
        this.businesses.sort.dateAscending = false;
        this.businesses.sort.ratingAscending = false;
        this.closePage('referralsAdmin');
    },

    showReferralsCallLog: function (business) {
        this.pages.depth++;
        this.businesses.business = business;
        this.getReferralCallLog();
        this.openPage('referralsCallLog');
    },
    closeReferralsCallLog: function () {
        this.pages.depth--;
        this.businesses.business = {};
        this.closePage('referralsCallLog');
    },

    showReferralCallLogComment: function (call_log_item) {
        if (call_log_item.comment.length > 0) {
            _this.showMessage("Comment already added");
        } else {
            this.pages.depth++;
            this.referrals.call_log_item = call_log_item;
            this.openPage('referralsCallLogComment');
        }
    },
    closeReferralCallLogComment: function () {
        this.pages.depth--;
        this.referrals.call_log_item = {};
        this.closePage('referralsCallLogComment');
    },

    showReferralCallWhatsApp: function(){
        this.pages.depth++;
        this.openPage('referralCallWhatsApp');
    },
    closeReferralCallWhatsApp: function(){
        this.pages.depth--;
        this.closePage('referralCallWhatsApp');
    },

});


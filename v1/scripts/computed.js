

var computedProperties = Object.freeze({

    getListingsList: function () {
        var filter = this.filters.main.toLowerCase();
        if (filter) {
            return this.listings.list.filter(item => {
                return item.name.toLowerCase().includes(filter) || 
                    item.location.toLowerCase().includes(filter) ||
                    item.description.toLowerCase().includes(filter);
            });
        } else {
            return this.listings.list;
        }
    },

    getAllListingsList: function () {
        var filter = this.filters.all.toLowerCase();
        if (filter) {
            return this.listings.all.filter(item => {
                return item.name.toLowerCase().includes(filter) || item.description.toLowerCase().includes(filter);
            });
        } else {
            return this.listings.all;
        }
    },

    getAdminListingsList: function () {
        var filter = this.filters.admin.toLowerCase();
        if (filter) {
            return this.listings.admin.filter(item => {
                return item.name.toLowerCase().includes(filter) || item.description.toLowerCase().includes(filter);
            });
        } else {
            return this.listings.admin;
        }
    },

    getApprovalsListingsList: function () {
        var filter = this.filters.approvals.toLowerCase();
        if (filter) {
            return this.listings.approvals.filter(item => {
                return item.name.toLowerCase().includes(filter) || item.description.toLowerCase().includes(filter);
            });
        } else {
            return this.listings.approvals;
        }
    },

    getMyListingsList: function () {
        var filter = this.filters.listings.toLowerCase();
        if (filter) {
            return this.listings.mine.filter(item => {
                return item.name.toLowerCase().includes(filter) || item.description.toLowerCase().includes(filter);
            });
        } else {
            return this.listings.mine;
        }
    },

    getFreeListingsList: function() {
        var filter = this.filters.free.toLowerCase();
        if (filter) {
            return this.listings.free.filter(item => {
                return item.name.toLowerCase().includes(filter) || item.description.toLowerCase().includes(filter);
            });
        } else {
            return this.listings.free;
        }
    },

    getDowngradedListingsList: function() {
        var filter = this.filters.downgraded.toLowerCase();
        if (filter) {
            return this.listings.downgraded.filter(item => {
                return item.name.toLowerCase().includes(filter) || item.description.toLowerCase().includes(filter);
            });
        } else {
            return this.listings.downgraded;
        }
    },

    getFavoritesList: function() {
        return this.listings.favorites;
    },

    getFreeListingCallLog: function (){
        return this.listings.call_log;
    },

    getListingDescription: function(){
        if (Object.keys(this.listing).length === 0) {
            return "";
        }
        return this.listing.description;
    },

    getFilterCondition: function(){
        return this.filters.condition;
    },

    isMobile: function() {
        return this.settings.platform == 'ios' || this.settings.platform == 'android';
    },

    getComment: function(){
        return this.comments.comment;
    },
    
    getCommentsList: function() {
        return this.comments.list;
    },

    getBlacklistedList: function (){
        return this.members.blacklisted;
    },

    getAlreadyCommented: function() {
        // if (this.comments.list.length == 0) {
        //     return false;
        // }
        // var found = false;
        // for(var i = 0; i < this.comments.list.length; i++) {
        //     if (this.isContentCreator(this.comments.list[i])) {
        //         found = true; 
        //         break;
        //     }
        // }
        // return found;

        return this.comments.alreadyCommented;
    },

    getListingLogo: function() {
        if (this.listingFiles.action == "New") {
            return this.newListing.logo;
        } else if (this.listingFiles.action == "Edit") {
            return this.listing.logo;
        } else if (this.listing != undefined) {
            return this.listing.logo;
        }
        return "";
    },

    getListingCVFile: function() {
        if (this.listingFiles.action == "New") {
            return this.newListing.cv_path;
        } else if (this.listingFiles.action == "Edit") {
            return this.listing.cv_path;
        } else if (this.listing != undefined) {
            return this.listing.cv_path;
        }
        return "";
    },

    getAddListingSearchResults: function() {
        return this.newListing.search.results;
    },

    getVoucher: function() {
        return this.vouchers.voucher;
    },

    getVouchersList: function() {
        return this.vouchers.list;
    },

    getVoucherClaimsList: function() {
        return this.vouchers.claims;
    },

    getActiveVouchersList: function() {
        return this.vouchers.list.filter(voucher => voucher.status == "LIVE" && voucher.available > 0);
    },

    getNonMemberComplianceMessage: function() {
        var message = `
            You are not authorized to make referrals, comment on or rate listings.
        `;
        if (this.permissions.visitor) {
            message += `<br/><br/>To gain access, accept the T&C's in the main menu ≡ (Top Right).`
        }
        return message;
    }

});
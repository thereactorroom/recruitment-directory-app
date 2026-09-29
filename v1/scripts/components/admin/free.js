
var adminFreeComponent = Object.freeze({

    getFreeListings: function () {
        _this = this;
        this.loading.free = true;
        $.get(this.urls.main + "listings/list", {
            user_key_id: this.settings.user_key_id,
            unique_key_id: this.settings.unique_key_id,
            type: 'free'
        }, function (response) {
            _this.loading.free = false;
            if (response.status == true && response.data) {
                _this.listings.free = response.data;
                for (var i = 0; i < _this.listings.free.length; i++) {
                    var item = _this.listings.free[i];
                    item.display_name = item.name;
                    if (item.display_name.length > 30) {
                        item.display_name = item.display_name.substring(0, 30) + "...";
                    }
                    _this.listings.free[i] = item;
                }
            }
        }, 'json')
        .fail(function(response) {
            _this.showMessage(response.error);
        });
    },

    getFreeLisingCallLog: function (listing) {
        _this = this;
        $.get(this.urls.main + "listings/listingCallLog", {
            unique_key_id: this.settings.unique_key_id,
            listing_id: listing.id
        }, function (response) {
            if (response.status == true) {
                _this.listings.call_log = response.data;
            }
        }, 'json')
        .fail(function(response) {
            _this.showMessage(response.error);
        });
    },
    
    addFreeListingCallLog: function () {
        _this = this;
        this.openDialerApp(this.listing.contact_number);
        $.post(this.urls.main + "listings/insertCallLog", {
            user_key_id: this.settings.unique_key_id,
            unique_key_id: this.settings.unique_key_id,
            listing_id: this.listing.id
        }, function (response) {
            if (response.status == true) {
                _this.getFreeListings();
                _this.getFreeLisingCallLog(_this.listing);
            }
        }, 'json')
        .fail(function(response) {
            _this.showMessage(response.error);
        });
    },

    addFreeListingCallLogComment: function () {
        _this = this;
        $.post(this.urls.main + "listings/insertCallLogComment", {
            id: this.callLogItem.id,
            comment: this.callLogItemComment,
        }, function (response) {
            if (response.status == true) {
                _this.getFreeListings();
                _this.getFreeLisingCallLog(_this.listing);
                _this.closeAdminFreeListingCallLogComment();
            }
        }, 'json')
        .fail(function (response) {
            _this.showMessage(response.error);
        });
    },

    searchAdminFreeListingsByName: function (listing) {
        var name = listing.name.toLowerCase();
        var description = listing.description.toLowerCase();
        var filter = this.filters.free.toLowerCase();
        if (`${name}`.indexOf(filter) > -1 || `${description}`.indexOf(filter) > -1) {
            return true;
        }
        return false;
    },

    /**
     * 
     */

    showAdminFreeListings: function(){
        this.pages.depth++;
        this.getFreeListings();
        this.openPage('adminFreeListings');
    },
    closeAdminFreeListings: function(){
        this.pages.depth--;
        this.listing = {};
        this.closePage('adminFreeListings');
    },

    showAdminFreeListingCallLog: function(listing) {
        this.pages.depth++;
        this.listing = listing;
        this.getFreeLisingCallLog(listing);
        this.openPage('adminFreeListingCallLog');
    },
    closeAdminFreeListingCallLog: function() {
        this.pages.depth--;
        this.closePage('adminFreeListingCallLog');
    },

    showAdminFreeListingCallLogComment: function(callLogItem) {
        this.pages.depth++;
        this.callLogItem = callLogItem;
        this.openPage('adminFreeListingCallLogComment');
    },
    closeAdminFreeListingCallLogComment: function() {
        this.pages.depth--;
        this.callLogItem = {};
        this.closePage('adminFreeListingCallLogComment');
    },

});
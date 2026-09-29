
var adminApprovalsComponent = Object.freeze({

    getApprovalListings: function () {
        _this = this;
        this.loading.approvals = true;
        $.get(this.urls.main + "listings/list", {
            user_key_id: this.settings.user_key_id,
            unique_key_id: this.settings.unique_key_id,
            type: 'status',
            status: "waiting approval",
        }, function (response) {
            _this.loading.approvals = false;
            if (response.status == true && response.data) {
                _this.listings.approvals = response.data;
                for (var i = 0; i < _this.listings.approvals.length; i++) {
                    var item = _this.listings.approvals[i];
                    item.display_name = item.name;
                    if (item.display_name.length > 30) {
                        item.display_name = item.display_name.substring(0, 30) + "...";
                    }
                    _this.listings.approvals[i] = item;
                }
            }
        }, 'json')
        .fail(function(response) {
            _this.showMessage(response.error);
        });
    },

    approveListing: function() {
        _this = this;
        this.showLoader("Approving Listing");
        $.post(this.urls.main + "listings/approve", {
            id: this.listing.id,
        }, async function(response) {
            _this.hideLoader();
            if (response.status == true) {
                await _this.aGetListings();
                _this.updateAllListings();
                _this.getAdminDashStats();
                _this.closeAdminApprovalsItem();
            } else {
                showMessage(response.message);
            }
        }, 'json')
        .fail(function(response) {
            showMessage(response.responseText);
        });
    },

    rejectListing: function() {
        _this = this;
        this.showLoader("Approving Listing");
        $.post(this.urls.main + "listings/reject", {
            id: this.listing.id,
            comment: this.callLogItemComment
        }, async function(response) {
            _this.hideLoader();
            if (response.status == true) {
                await _this.aGetListings();
                _this.updateAllListings();
                _this.getAdminDashStats();
                _this.closeAdminApprovalsItem();
                _this.closeAdminApprovalItemReject();
            } else {
                showMessage(response.message);
            }
        }, 'json')
        .fail(function(response) {
            showMessage(response.responseText);
        });
    },

    seachAllListingByName: function (listing) {
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

    showAdminApprovals: function(){
        this.pages.depth++;
        this.getApprovalListings();
        this.openPage('adminApprovals');
    },
    closeAdminApprovals: function(){
        this.pages.depth--;
        this.closePage('adminApprovals');
    },

    showAdminApprovalsItem: function(listing) {
        this.pages.depth++;
        this.listing = listing;
        this.openPage('adminApprovalsItem');
    },
    closeAdminApprovalsItem: function() {
        this.pages.depth--;
        this.listing = {};
        this.closePage('adminApprovalsItem');
    },

    showAdminApprovalItemReject: function() {
        this.pages.depth++;
        this.openPage('adminApprovalItemReject');
    },
    closeAdminApprovalItemReject: function() {
        this.pages.depth--;
        this.callLogItemComment = "";
        this.closePage('adminApprovalItemReject');
    },

});


var approvals_methods = Object.freeze({

    /**
     * getTraders: returns a list of traders
     * @param {*} category 
     */
    getWaitingApprovalBusiness: function(){
        _this = this;
        this.loading.businesses = true;
        $.get(this.urls.main + "businesses/list_by_status", {
            user_key_id: this.user_key.id,
            unique_key_id: this.unique_key_id,
            dev_id: this.unique_key_dev_id,
            status: "Waiting Approval"
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
                _this.approvals.list = response.data;
            }
        }, 'json')
        .fail(function(response) {
            _this.showMessage(response.error);
        });
    },

    approveBusiness: function() {
        _this = this;
        this.showLoader("Approving Business");
        $.post(this.urls.main + "businesses/approve", {
            id: this.businesses.business.id,
        }, async function(response) {
            _this.hideLoader();
            if (response.status == true) {
                _this.getAllBusinesses();
                _this.getWaitingApprovalBusiness();
                await _this.aGetApprovedBusinesses();
                _this.updateAllBusinesses();
                _this.getAdminDashStats();
                _this.closeBusinessApprovals();
            } else {
                showMessage(response.message);
            }
        }, 'json')
        .fail(function(response) {
            showMessage(response.responseText);
        });
    },

    rejectBusiness: function() {
        _this = this;
        this.showLoader("Approving Business");
        $.post(this.urls.main + "businesses/reject", {
            id: this.businesses.business.id,
            comment: this.businesses.business.comment
        }, async function(response) {
            _this.hideLoader();
            if (response.status == true) {
                _this.getAllBusinesses();
                _this.getWaitingApprovalBusiness();
                await _this.aGetApprovedBusinesses();
                _this.updateAllBusinesses();

                _this.closeApprovalRejectComment();
                _this.closeBusinessApprovals();
            } else {
                showMessage(response.message);
            }
        }, 'json')
        .fail(function(response) {
            showMessage(response.responseText);
        });
    },

    /**
     * searchTradersByName
     * Search if trader matched any of the given traders in the list
     * @param {*} trader 
     * @returns true ? false
     */
    searchApprovalBusinessByName: function (business) {
        var name = business.business_name.toLowerCase();
        var description = business.description.toLowerCase();
        var filter = this.businesses.mainFilter.toLowerCase();
        if (`${name}`.indexOf(filter) > -1 || `${description}`.indexOf(filter) > -1) {
            return true;
        }
        return false;
    },

    showApprovals: function() {
        this.pages.depth++;
        this.getWaitingApprovalBusiness();
        this.openPage('approvals');
    }, 
    closeApprovals: function() {
        this.pages.depth--;
        this.closePage('approvals');
    },

    showBusinessApprovals: function(business) {
        this.pages.depth++;
        this.businesses.business = business;
        this.openPage('approvalBusiness');
    },
    closeBusinessApprovals: function() {
        this.pages.depth--;
        this.closePage('approvalBusiness');
    },

    showApprovalRejectComment: function() {
        this.pages.depth++;
        this.businesses.business.comment = "";
        this.openPage('approvalRejectComment');
    },
    closeApprovalRejectComment: function() {
        this.pages.depth--;
        this.businesses.business.comment = "";
        this.closePage('approvalRejectComment');
    },

});





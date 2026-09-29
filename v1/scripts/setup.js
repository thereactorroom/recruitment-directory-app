
var setupMethods = Object.freeze({
    
    aGetUniqueKey: async function () {
        _this = this;
        let decoded = decodeMobileNumber(this.settings.user.mobile);
        const results = await $.get(this.urls.main + "uniqueKey/get", {
            community_id: this.settings.community_id, 
            content_id: this.settings.content_id, 
            user_id: this.settings.user.userId,
            country_code: decoded.code, 
            mobile: decoded.number
        }, 'json');
        var uniqueKey = await results;
        if (uniqueKey.status == true) {
            this.settings.user_key_id = uniqueKey.data.user_key.id;
            this.settings.unique_key_id = uniqueKey.data.unique_key.id;
        };
    },

    getAdminDashStats: function() {
        _this = this;
        $.get(this.urls.main + "listings/stats", {
            user_key_id: this.settings.user_key_id,
            unique_key_id: this.settings.unique_key_id
        }, function (response) {
            if (response.status == true && response.data) {
                _this.adminDashboardStats = response.data;
            }
        }, 'json')
        .fail(function(response) {
            _this.showMessage(response.error);
        });
    },
    
    aGetAllOnMounted: async function (loading = true) {
        if (loading) {
            this.showLoader("Getting listings");
        }

        try {
            this.listings.all = [];
            this.listings.list = [];

            this.getMember();
            this.getMembers();
            this.getCommunityGroups();

            await this.aGetUniqueKey();
            this.getBlacklisted();
            
            this.getFavorites();
            this.getProposition();
            this.getVouchers();
            this.getModuleConfig();
            
            await this.aGetListings();
            this.updateAllListings();
            this.getAdminDashStats();
        } catch(ex) {
            // this.showMessage(JSON.stringify(ex.getMessage));
        }
        
        if (loading) {
            this.hideLoader();
        }
    },

});



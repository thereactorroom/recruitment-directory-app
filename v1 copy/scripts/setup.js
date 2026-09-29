var setup = Object.freeze({
    
    aGetUniqueKey: async function (section) {
        _this = this;

        let decoded = decodeMobileNumber(user.mobile);
        const results = await $.get(this.urls.main + "uniqueKey/get", {
            community_id: _this.community_id, 
            content_id: _this.content_id, 
            user_id: _this.user.userId,
            country_code: decoded.code, 
            mobile: decoded.number,
            section: section
        }, 'json');
        var uniqueKey = await results;
        console.log(uniqueKey);
        if (uniqueKey.status == true) {
            _this.user_key = uniqueKey.data.user_key;
            _this.unique_key_id = uniqueKey.data.unique_key.id;
            _this.unique_key_dev_id = uniqueKey.data.unique_key.dev_id;
            _this.unique_key_area_id = uniqueKey.data.unique_key.area_id;
        };
    },

    getAdminDashStats: function() {
        _this = this;
        $.get(this.urls.main + "listings/admin_dash_stats", {
            user_key_id: this.user_key.id,
            unique_key_id: this.unique_key_id,
            status: "Waiting Approval",
            referral: 1
        }, function (response) {
            if (response.status == true && response.data) {
                _this.admin_dash_stats = response.data;
            }
        }, 'json')
        .fail(function(response) {
            _this.showMessage(response.error);
        });
    },
    
    aGetAllOnMounted: async function (loading = true) {
        if (loading) {
            this.showLoader("Getting businesses");
        }

        this.listings.all = [];
        this.listings.list = [];

        this.getMember();
        this.getMembers();
        this.getCommunityGroups();

        await this.aGetUniqueKey("directory");
        this.getBlacklisted();
        
        // this.getFavorites();
        // this.getMyBusinesses();
        // this.getProposition();
        // await this.aGetApprovedBusinesses();
        // await this.aGetReferrals();
        // this.updateAllBusinesses();
        // this.getAdminDashStats();

        // if (this.is_registration) {
        //     this.showMyBusinessesPage();
        //     this.showLandingPage();
        //     this.registration.searchBy = "name";
        // } else {
            
        // }

        // if (this.is_registration) {
        //     await this.aGetUniqueKey("registration");
        //     this.pages.main = false;
        //     this.showLandingPage();
        //     this.getProposition();
        //     this.registration.searchBy = "name";
        // } else {
        //     await this.aGetUniqueKey("directory");
        //     this.getBlacklisted();
        //     this.getFavorites();
        //     this.getMyBusinesses();
        //     this.getProposition();
            
        //     await this.aGetApprovedBusinesses();
        //     await this.aGetReferrals();
        //     this.updateAllBusinesses();
        //     this.getAdminDashStats();
        // }
        
        if (loading) {
            this.hideLoader();
        }
    },

});



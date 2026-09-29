
var filtersMethods = Object.freeze({

    /**
     * applyFilter
     * @param {*} condition 
     */
    applyMainFilter: function (condition) {
        _this = this;
        this.filters.condition = condition;
        if (condition == "faves") {
            var normals = [];
            this.listings.favorites.forEach(favorite => {
                normals.push(favorite.business_id);
            });
            this.listings.list = this.listings.all.filter((listing) => {
                return normals.includes(listing.id);
            });
        } else if (condition == "paid") {
            this.listings.list = this.listings.all.filter((listing) => { 
                return listing.paid;
            });
        } else if (condition == "rated") {
            this.listings.list = this.listings.all.filter((listing) => { 
                return listing.stars_avg > 0;
            });
        } else if (condition == "not_rated") {
            this.listings.list = this.listings.all.filter((listing) => { 
                return listing.stars_avg <= 0;
            });
        }
        this.closeMainFilter();
    },
    resetMainFilter: function() {
        this.filters.condition = "";
        this.listings.list = this.listings.all;
        this.closeMainFilter();
    },

    applyAdminFilter: function (condition) {
        _this = this;
        this.filters.condition = condition;
        if (condition == "faves") {
            var normals = [];
            this.listings.favorites.forEach(favorite => {
                normals.push(favorite.business_id);
            });
            this.listings.admin = this.listings.all.filter((listing) => {
                return normals.includes(listing.id);
            });
        } else if (condition == "paid") {
            this.listings.admin = this.listings.all.filter((listing) => { 
                return listing.paid;
            });
        } else if (condition == "rated") {
            this.listings.list = this.listings.all.filter((listing) => { 
                return listing.stars_avg > 0;
            });
        } else if (condition == "not_rated") {
            this.listings.admin = this.listings.all.filter((listing) => { 
                return listing.stars_avg <= 0;
            });
        }
        this.closeAdminFilter();
    },
    resetAdminFilter: function() {
        this.filters.condition = "";
        this.listings.admin = this.listings.all;
        this.closeAdminFilter();
    },

    /**
     * showMainFilter
     */
    showMainFilter: function () {
        this.openPage('mainFilter');
    },
    closeMainFilter: function () {
        this.closePage('mainFilter');
    },

    showAdminFilter: function() {
        this.openPage('adminFilter');
    },
    closeAdminFilter: function(){
        this.closePage('adminFilter');
    }

});
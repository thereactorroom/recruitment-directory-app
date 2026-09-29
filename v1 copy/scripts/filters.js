

var filters = Object.freeze({

    /**
     * applyFilter
     * @param {*} condition 
     */
    applyFilter: function (condition) {
        _this = this;
        this.filter.condition = condition;
        if (condition == "faves") {
            var normals = [];
            this.listings.favorites.forEach(favorite => {
                normals.push(favorite.business_id);
            });
            this.listings.list = this.listings.all.filter((business) => {
                return normals.includes(business.id);
            });
        } else if (condition == "paid") {
            this.listings.list = this.listings.all.filter((business) => { 
                return business.paid;
            });
        } else if (condition == "rated") {
            this.listings.list = this.listings.all.filter((business) => { 
                return business.stars_avg > 0;
            });
        } else if (condition == "not_rated") {
            this.listings.list = this.listings.all.filter((business) => { 
                return business.stars_avg <= 0;
            });
        }
        this.closeFilter();
    },

    /**
     * resetFilter
     */
    resetFilter: function () {
        this.filter.condition = "";
        this.listings.list = this.listings.all;
        this.closeFilter();
    },


    applyManageFilter: function(condition) {
        _this = this;
        this.filter.condition = condition;
        if (condition == "paid") {
            this.listings.allAdmin = this.listings.allAdminAll.filter((business) => {
                return business.subscription_status == "Paid";
            });
        } else if (condition == "not_paid") {
            this.listings.allAdmin = this.listings.allAdminAll.filter((business) => { 
                return business.subscription_status == "Not Paid";
            });
        } else if (condition == "referred") {
            this.listings.allAdmin = this.listings.allAdminAll.filter((business) => { 
                return business.referral == 1;
            });
        } else if (condition == "waiting") {
            this.listings.allAdmin = this.listings.allAdminAll.filter((business) => { 
                return business.status == "Waiting Approval";
            });
        } else if (condition == "rejected") {
            this.listings.allAdmin = this.listings.allAdminAll.filter((business) => { 
                return business.status == "Rejected";
            });
        }
        this.closeManageFilter();
    },
    resetManageFilter: function() {
        this.filter.condition = "";
        this.listings.allAdmin = this.listings.allAdminAll;
        this.closeManageFilter();
    },

    /**
     * applySort
     * @param {*} condition 
     */
    applySort: function (condition) {
        if (condition == "name") {
            this.listings.sort.nameAscending = !this.listings.sort.nameAscending;
            if (this.listings.sort.nameAscending) { // if ascending, sort descending
                this.listings.list.sort((a, b) => (b.business_name.toLowerCase() > a.business_name.toLowerCase()) ? 1 : -1);
            } else { // else sort ascending
                this.listings.list.sort((a, b) => (a.business_name.toLowerCase() > b.business_name.toLowerCase()) ? 1 : -1);
            }
        } else if (condition == "date") {
            this.listings.sort.dateAscending = !this.listings.sort.dateAscending;
            if (this.listings.sort.dateAscending) { // if ascending, sort descending
                this.listings.list.sort(function(a, b){
                    let date1 = new Date(a.added).getTime();
                    let date2 = new Date(b.added).getTime(); 
                    return date2 > date1 ? 1 : -1;
                });
            } else { // else sort ascending
                this.listings.list.sort(function(a, b){
                    let date1 = new Date(a.added).getTime();
                    let date2 = new Date(b.added).getTime(); 
                    return date1 > date2 ? 1 : -1;
                });
            }
        } else if (condition == "rating") {
            this.listings.sort.ratingAscending = !this.listings.sort.ratingAscending;
            if (this.listings.sort.ratingAscending) { // if ascending, sort descending
                this.listings.list.sort((a, b) => b.stars_avg - a.stars_avg);
            } else { // else sort ascending
                this.listings.list.sort((a, b) => a.stars_avg - b.stars_avg);
            }
        }
        $("." + condition + "-sort").addClass("sort-item-background");
        $("." + condition + "-sort").siblings().removeClass("sort-item-background");
    },

    /**
     * applySortRecommendations
     */
    applySortReferrals: function (condition) {
        if (condition == "name") {
            this.listings.sort.nameAscending = !this.listings.sort.nameAscending;
            if (this.listings.sort.nameAscending) { // if ascending, sort descending
                this.referrals.all.sort((a, b) => (b.business_name > a.business_name) ? 1 : -1);
            } else { // else sort ascending
                this.referrals.all.sort((a, b) => (a.business_name > b.business_name) ? 1 : -1);
            }
        } else if (condition == "date") {
            this.listings.sort.dateAscending = !this.listings.sort.dateAscending;
            if (this.listings.sort.dateAscending) { // if ascending, sort descending
                this.referrals.all.sort(function(a, b){
                    let date1 = new Date(a.added).getTime();
                    let date2 = new Date(b.added).getTime(); 
                    return date2 > date1 ? 1 : -1;
                });
            } else { // else sort ascending
                this.referrals.all.sort(function(a, b){
                    let date1 = new Date(a.added).getTime();
                    let date2 = new Date(b.added).getTime(); 
                    return date1 > date2 ? 1 : -1;
                });
            }
        } else if (condition == "rating") {
            this.listings.sort.ratingAscending = !this.listings.sort.ratingAscending;
            if (this.listings.sort.ratingAscending) { // if ascending, sort descending
                this.referrals.all.sort((a, b) => b.stars_avg - a.stars_avg);
            } else { // else sort ascending
                this.referrals.all.sort((a, b) => a.stars_avg - b.stars_avg);
            }
        }
        $("." + condition + "-sort").addClass("sort-item-background");
        $("." + condition + "-sort").siblings().removeClass("sort-item-background");
    },

    /**
     * 
     * @param {*} condition 
     */
    applySortApprovals: function(condition) {
        if (condition == "name") {
            this.listings.sort.nameAscending = !this.listings.sort.nameAscending;
            if (this.listings.sort.nameAscending) { // if ascending, sort descending
                this.approvals.list.sort((a, b) => (b.business_name > a.business_name) ? 1 : -1);
            } else { // else sort ascending
                this.approvals.list.sort((a, b) => (a.business_name > b.business_name) ? 1 : -1);
            }
        } else if (condition == "date") {
            this.listings.sort.dateAscending = !this.listings.sort.dateAscending;
            if (this.listings.sort.dateAscending) { // if ascending, sort descending
                this.approvals.list.sort(function(a, b){
                    let date1 = new Date(a.added).getTime();
                    let date2 = new Date(b.added).getTime(); 
                    return date2 > date1 ? 1 : -1;
                });
            } else { // else sort ascending
                this.approvals.list.sort(function(a, b){
                    let date1 = new Date(a.added).getTime();
                    let date2 = new Date(b.added).getTime(); 
                    return date1 > date2 ? 1 : -1;
                });
            }
        } else if (condition == "rating") {
            this.listings.sort.ratingAscending = !this.listings.sort.ratingAscending;
            if (this.listings.sort.ratingAscending) { // if ascending, sort descending
                this.approvals.list.sort((a, b) => b.stars_avg - a.stars_avg);
            } else { // else sort ascending
                this.approvals.list.sort((a, b) => a.stars_avg - b.stars_avg);
            }
        }
        $("." + condition + "-sort").addClass("sort-item-background");
        $("." + condition + "-sort").siblings().removeClass("sort-item-background");
    },

    /**
     * 
     * @param {*} condition 
     */
    applySortMyListings: function(condition) {
        if (condition == "name") {
            this.listings.sort.nameAscending = !this.listings.sort.nameAscending;
            if (this.listings.sort.nameAscending) { // if ascending, sort descending
                this.myListings.list.sort((a, b) => (b.business_name > a.business_name) ? 1 : -1);
            } else { // else sort ascending
                this.myListings.list.sort((a, b) => (a.business_name > b.business_name) ? 1 : -1);
            }
        } else if (condition == "date") {
            this.listings.sort.dateAscending = !this.listings.sort.dateAscending;
            if (this.listings.sort.dateAscending) { // if ascending, sort descending
                this.myListings.list.sort(function(a, b){
                    let date1 = new Date(a.added).getTime();
                    let date2 = new Date(b.added).getTime(); 
                    return date2 > date1 ? 1 : -1;
                });
            } else { // else sort ascending
                this.myListings.list.sort(function(a, b){
                    let date1 = new Date(a.added).getTime();
                    let date2 = new Date(b.added).getTime(); 
                    return date1 > date2 ? 1 : -1;
                });
            }
        } else if (condition == "rating") {
            this.listings.sort.ratingAscending = !this.listings.sort.ratingAscending;
            if (this.listings.sort.ratingAscending) { // if ascending, sort descending
                this.myListings.list.sort((a, b) => b.stars_avg - a.stars_avg);
            } else { // else sort ascending
                this.myListings.list.sort((a, b) => a.stars_avg - b.stars_avg);
            }
        }
        $("." + condition + "-sort").addClass("sort-item-background");
        $("." + condition + "-sort").siblings().removeClass("sort-item-background");
    },

    applySortManageListings: function(condition) {
        if (condition == "name") {
            this.listings.sort.nameAscending = !this.listings.sort.nameAscending;
            if (this.listings.sort.nameAscending) { // if ascending, sort descending
                this.listings.allAdmin.sort((a, b) => (b.business_name > a.business_name) ? 1 : -1);
            } else { // else sort ascending
                this.listings.allAdmin.sort((a, b) => (a.business_name > b.business_name) ? 1 : -1);
            }
        } else if (condition == "date") {
            this.listings.sort.dateAscending = !this.listings.sort.dateAscending;
            if (this.listings.sort.dateAscending) { // if ascending, sort descending
                this.listings.allAdmin.sort(function(a, b){
                    let date1 = new Date(a.added).getTime();
                    let date2 = new Date(b.added).getTime(); 
                    return date2 > date1 ? 1 : -1;
                });
            } else { // else sort ascending
                this.listings.allAdmin.sort(function(a, b){
                    let date1 = new Date(a.added).getTime();
                    let date2 = new Date(b.added).getTime(); 
                    return date1 > date2 ? 1 : -1;
                });
            }
        } else if (condition == "rating") {
            this.listings.sort.ratingAscending = !this.listings.sort.ratingAscending;
            if (this.listings.sort.ratingAscending) { // if ascending, sort descending
                this.listings.allAdmin.sort((a, b) => b.stars_avg - a.stars_avg);
            } else { // else sort ascending
                this.listings.allAdmin.sort((a, b) => a.stars_avg - b.stars_avg);
            }
        }
        $("." + condition + "-sort").addClass("sort-item-background");
        $("." + condition + "-sort").siblings().removeClass("sort-item-background");
    },

    /**
     * showFilter
     */
    showFilter: function () {
        this.openPage('filter');
    },
    
    /**
     * closeFilter
     */
    closeFilter: function () {
        this.closePage('filter');
    },

    showManageFilter: function() {
        this.openPage('manageFilter');
    },
    closeManageFilter: function() {
        this.closePage('manageFilter');
    },

    /**
     * showSort
     */
    showSort: function (where = "") {
        if (this.pages.sort) {
            this.listings.sort.sort = false;
            this.closePage('sort');
        } else {
            this.listings.sort.sort = true;
            this.openPage('sort');
        }
    },

    /**
     * closeSort
     */
    closeSort: function () {
        this.closePage('sort');
    }

});


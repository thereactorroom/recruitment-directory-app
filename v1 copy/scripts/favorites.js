

var favorites_methods = Object.freeze({

    /**
     * getFavorites: returns a list of favorites traders
     * @param {*} category 
     */
    getFavorites: function(){
        _this = this;
        this.loading.favorites = true;
        $.get(this.urls.main + "favorites/list", {
            user_key_id: this.user_key.id,
            unique_key_id: this.unique_key_id,
        }, function (response) {
            _this.loading.favorites = false;
            if (response.status == true) {
                _this.businesses.favorites = response.data;
            }
        }, 'json')
        .fail(function(response) {
            _this.showMessage(response.error);
        });
    },

    /**
     * aGetFavorites: returns a list of favorites
     * @param {*} category 
     */
    aGetFavorites: async function () {
        this.loading.favorites = true;
        const response = await $.get(this.urls.main + "favorites/list", {
            user_key_id: this.user_key.id,
            unique_key_id: this.unique_key_id,
        }, 'json');
        var results = await response;
        if (results.status == true) {
            this.businesses.favorites = results.data;
        }
        this.loading.favorites = false;
    },

    /**
     * addTraderToFavorite
     * Add trader to favorites
     */
    addFavorite: function () {
        _this = this;
        this.showLoader("Adding favorites");
        $.post(this.urls.main + "favorites/insert", {
            user_key_id: this.user_key.id,
            unique_key_id: this.unique_key_id,
            business_id: this.businesses.business.id,
        }, function (response) {
            // _this.logImpression('favoring_listing', _this.businesses.business);
            if (response.status == true) {
                _this.getFavorites();
                _this.hideLoader();
                _this.showMessage(response.message, [{
                    text: "Ok",
                    onTap: function () { }
                }]);
            } else {
                _this.hideLoader();
                _this.showMessage(response.message);
            }
        }, 'json')
        .fail(function (response) {
            _this.showMessage(response.responseText);
        });
    },

    /**
     * deleteFavorites
     * delete trader favorites
     */
    deleteFavorites: function () {
        _this = this;
        this.showLoader("Removing favorites");
        $.post(this.urls.main + "favorites/delete", {
            user_key_id: this.user_key.id,
            unique_key_id: this.unique_key_id,
            business_id: this.businesses.business.id,
        }, function (response) {
            // _this.logImpression('un_favoring_listing', _this.businesses.business);
            if (response.status == true) {
                _this.getFavorites();
                _this.hideLoader();
                _this.showMessage(response.message, [{
                    text: "Ok",
                    onTap: function () { }
                }]);
            } else {
                _this.hideLoader();
                _this.showMessage(response.message);
            }
        }, 'json')
        .fail(function (response) {
            _this.showMessage(response.responseText);
        });
    },

    /**
     * isFavorite
     */
    isFavorite: function (business) {
        if (business == undefined) {
            business = this.businesses.business;
        }
        for (var i = 0; i < this.businesses.favorites.length; i++) {
            var favorite = this.businesses.favorites[i];
            if (favorite.business_id == business.id) {
                return true;
            }
        }
        return false;
    },

});




var favoritesMethods = Object.freeze({

    /**
     * getFavorites: returns a list of favorites traders
     */
    getFavorites: function() {
        _this = this;
        this.loading.favorites = true;
        $.get(this.urls.main + "favorites/list", {
            user_key_id: this.settings.user_key_id,
            unique_key_id: this.settings.unique_key_id
        }, function (response) {
            _this.loading.favorites = false;
            if (response.status == true) {
                _this.listings.favorites = response.data;
            }
        }, 'json')
        .fail(function(response) {
            _this.showMessage(response.error);
        });
    },

    /**
     * aGetFavorites: returns a list of favorites
     */
    aGetFavorites: async function () {
        this.loading.favorites = true;
        const response = await $.get(this.urls.main + "favorites/list", {
            user_key_id: this.settings.user_key_id,
            unique_key_id: this.settings.unique_key_id
        }, 'json');
        var results = await response;
        if (results.status == true) {
            this.listings.favorites = results.data;
        }
        this.loading.favorites = false;
    },

    /**
     * isFavorite
     */
    isFavorite: function (listing) {
        for (var i = 0; i < this.getFavoritesList.length; i++) {
            var favorite = this.getFavoritesList[i];
            if (favorite.listing_id == listing.id) {
                return true;
            }
        }
        return false;
    },

});


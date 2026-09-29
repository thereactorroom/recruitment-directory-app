
var adminManageListingsComponent = Object.freeze({

    mergeListings: function(){
        _this = this;
        if (this.merge.main == undefined) {
            this.showMessage(`Please select a main listing to merge to.`);
            return false;
        }
        if (this.merge.second == undefined) {
            this.showMessage(`Please select a second listing to merge to.`);
            return false;
        }
        var main_name = this.merge.main.name;
        var second_name = this.merge.second.name;
        this.showMessage(`
            Are you sure you want to merge <br/><b>${main_name}</b> to <b>${second_name}</b>? 
            <br/><br/> Please note that this action cannot be reversed.
            `, [{
                text: "Yes",
                onTap: function () { 
                    _this.showLoader("Merging listings...");
                    $.post(_this.urls.main + "listings/merge", {
                        user_key_id: _this.settings.user_key_id,
                        unique_key_id: _this.settings.unique_key_id,
                        main_listing_id: _this.merge.main.id,
                        second_listing_id: _this.merge.second.id,
                    }, async function (response) {
                        _this.hideLoader();
                        if (response.status == true && response.data) {
                            await _this.aGetListings();
                            _this.updateAllListings();

                            _this.getAdminDashStats();
                            _this.closeAdminMergeListings();
                            _this.showMessage('Merging has been successful.');
                        } else {
                            _this.showMessage(response.message);
                        }
                    }, 'json')
                    .fail(function (response) {
                        _this.showMessage(response.responseText);
                    });
                }
            },{
                text: "No",
                onTap: function () { 
                }
            }]
        );
    },

    /**
     * 
     */

    showManageListings: function(){
        this.pages.depth++;
        this.getAllListings();
        this.openPage('adminListings');
    },
    closeManageListings: function() {
        this.pages.depth--;
        this.listing = {};
        this.closePage('adminListings');
    },

    showManageListingItem: function(listing) {
        this.pages.depth++;
        this.listing = listing;
        this.openPage('adminListingItem');
    },
    closeManageListingItem: function() {
        this.pages.depth--;
        this.closePage('adminListingItem');
    },

    showEditAdminListing: function(listing) {
        this.pages.depth++;
        this.listing = listing;
        this.openPage('adminListingItem');
    },
    closeEditAdminListing: function(){
        this.pages.depth;
        this.openPage('adminListingItem');
    },

    showAdminMergeListings: function(){
        if (this.getAdminListingsList.length < 2) {
            this.showMessage(
                `You can only merge 2 or more Listings`, [{
                    text: "No",
                    onTap: function () { }
                }]
            );
            return false;
        } else {
            this.pages.depth++;
            this.openPage('adminMergeListings');
        }
    },
    closeAdminMergeListings: function() {
        this.pages.depth--;
        this.filters.merge = "";
        this.merge.option = "main";
        this.merge.main = undefined;
        this.merge.second = undefined;
        this.closePage('adminMergeListings');
    },

    showAdminSelectMergeListing: function(option){
        this.listings.merge = [];
        for (let i = 0; i < this.getAdminListingsList.length; i++) {
            var item = this.getAdminListingsList[i];

            if (option == "main" && item.free == '1') continue;
            if (option == "second" && item.free == '0') continue;
            
            this.listings.merge.push(item);
        }

        this.pages.depth++;
        this.merge.option = option;
        this.openPage('adminSelectMergeListing');
    },
    closeAdminSelectMergeListing: function(){
        this.pages.depth--;
        this.mergeList = [];
        this.closePage('adminSelectMergeListing');
    },

    selectAdminMergeListing: function(listing) {

        if (this.merge.option == 'main') {
            this.merge.main = listing;
        } else if (this.merge.option == 'second') {
            this.merge.second = listing;
        }

        this.filters.merge = "";
        this.closeAdminSelectMergeListing();
    },

});


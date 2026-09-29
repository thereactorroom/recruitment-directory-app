
var adminDowngradedComponent = Object.freeze({

    getDowngradedListings: function () {
        _this = this;
        this.loading.downgraded = true;
        $.get(this.urls.main + "listings/list", {
            user_key_id: this.settings.user_key_id,
            unique_key_id: this.settings.unique_key_id,
            type: 'downgraded'
        }, function (response) {
            _this.loading.downgraded = false;
            if (response.status == true && response.data) {
                _this.listings.downgraded = response.data;
                for (var i = 0; i < _this.listings.downgraded.length; i++) {
                    var item = _this.listings.downgraded[i];
                    item.display_name = item.name;
                    if (item.display_name.length > 30) {
                        item.display_name = item.display_name.substring(0, 30) + "...";
                    }
                    _this.listings.downgraded[i] = item;
                }
            }
        }, 'json')
        .fail(function(response) {
            _this.showMessage(response.error);
        });
    },

    downgradeListing: function() {
        _this = this;
        this.showMessage(`
            Are you sure you want to Downgrade <br/><b>${this.listing.name}</b>?
            `, [{
                text: "Yes",
                onTap: function () { 
                    _this.showLoader("Downgrading listing...");
                    $.post(_this.urls.main + "listings/downgrade", {
                        user_key_id: _this.settings.user_key_id,
                        unique_key_id: _this.settings.unique_key_id,
                        id: _this.listing.id
                    }, async function (response) {
                        _this.hideLoader();
                        if (response.status == true && response.data) {
                            _this.listing = response.data;
                            await _this.aGetListings();
                            _this.updateAllListings();
                            _this.getDowngradedListings();
                            _this.closeDowngradedListingItem();
                            _this.closeManageListingItem();
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

    upgradeListing: function() {
        _this = this;
        var error = false;
        var message = "";

        if (this.isEmpty(this.upgradeOptions.period_length)) {
            error = true;
            message = "Upgrade period is required";
        } else if (this.upgradeOptions.period_length <= 0) {
            error = true;
            message = "Upgrade period length cannot be 0 or less.";
        } else if (this.isEmpty(this.upgradeOptions.start_date)) {
            error = true;
            message = "Upgrade start date is required.";
        } else if (_this.upgradeOptions.type == 'eft_cash' && this.isEmpty(this.newVoucher.amount)) {
            error = true;
            message = "Upgrade amount is required";
        } else if (_this.upgradeOptions.type == 'eft_cash' && this.newVoucher.amount <= 0) {
            error = true;
            message = "Upgrade amount cannot be 0 or less."
        } 

        if (error) {
            this.showMessage(message);
        } else {
            this.showMessage(`
                Are you sure you want to Upgrade <br/><b>${this.listing.name}</b>?
                `, [{
                    text: "Yes",
                    onTap: function () { 
                        _this.showLoader("Upgrding listing");
                        $.post(_this.urls.main + "listings/upgrade", {
                            user_key_id: _this.settings.user_key_id,
                            unique_key_id: _this.settings.unique_key_id,
                            id: _this.listing.id,
                            type: _this.upgradeOptions.type,
                            start_date: _this.upgradeOptions.start_date,
                            period_length: _this.upgradeOptions.period_length,
                            amount: _this.upgradeOptions.amount
                        }, async function (response) {
                            _this.hideLoader();
                            if (response.status == true && response.data) {
                                _this.listing = response.data;
                                _this.getDowngradedListings();
                                await _this.aGetListings();
                                _this.updateAllListings();
                                
                                _this.closeDowngradedListingItem();
                                _this.closeManageListingItem();

                                _this.closeUpgradeListingPage();
                                _this.upgradeOptions.clear();
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
        }
    },

    setUpgradeType: function(type) {
        this.upgradeOptions.type = type;
        if (type == 'admin_allocation') {
            this.upgradeOptions.description = "Admin Allocation";
        } else if (type == 'voucher') {
            this.upgradeOptions.description = "Voucher";
        } else if (type == 'eft_cash') {
            this.upgradeOptions.description = "EFT / Cash";
        }

        this.closeUpgradeOptionsPage();
        if (type == 'voucher') {
            this.openVoucherPaymentPage();
        } else {
            this.showUpgradeListingPage();
        }
    },


    /**
     * 
     */

    showDowngradedListings: function(){
        this.pages.depth++;
        this.getDowngradedListings();
        this.openPage('adminDowngradedListings');
    },
    closeDowngradedListings: function(){
        this.pages.depth--;
        this.listing = {};
        this.closePage('adminDowngradedListings');
    },

    showDowngradedListingItem: function(listing) {
        this.pages.depth++;
        this.listing = listing;
        this.openPage('adminDowngradedListingItem');
    },
    closeDowngradedListingItem: function() {
        this.pages.depth--;
        this.closePage('adminDowngradedListingItem');
    },

    showUpgradeOptionsPage: function() {
        $('.datepicker').pickadate({
            format: 'yyyy-mm-dd',
            formatSubmit: 'yyyy-mm-dd',
            hiddenName: true,
            min: true
        });

        let now = new Date();
        let year = now.getFullYear();   // e.g., 2026
        let month = now.getMonth() + 1; // Adds 1 to adjust for 0-index
        let day = now.getDate();   
        let formattedDate = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

        this.upgradeOptions.start_date = formattedDate;
        this.newVoucher.action = "add";
        
        this.pages.depth++;
        this.openPage('upgradeOptions');
    },
    closeUpgradeOptionsPage: function() {
        this.pages.depth--;
        this.closePage('upgradeOptions');
    },

    showUpgradeListingPage: function() {
        this.pages.depth++;
        this.settings.action = "add";
        this.openPage('upgradeListing');
    },
    closeUpgradeListingPage: function() {
        this.pages.depth--;
        this.settings.action = "";
        this.closePage('upgradeListing');
    }

});
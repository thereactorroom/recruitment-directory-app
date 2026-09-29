
var vouchersComponent = Object.freeze({

    getVouchers: function() {
        _this = this;
        $.get(this.urls.main + "vouchers/list", {
            user_key_id: this.settings.user_key_id,
            unique_key_id: this.settings.unique_key_id,
        }, function (response) {
            if (response.status == true) {
                _this.vouchers.list = response.data;
            }
        }, 'json')
        .fail(function(response) {
            _this.showMessage(response.error);
        });
    },

    getActiveVouchers: function() {
        _this = this;
        $.get(this.urls.main + "vouchers/active", {
            user_key_id: this.settings.user_key_id,
            unique_key_id: this.settings.unique_key_id,
        }, function (response) {
            if (response.status == true) {
                _this.vouchers.active = response.data;
            }
        }, 'json')
        .fail(function(response) {
            _this.showMessage(response.error);
        });
    },

    getVoucherClaims: function(){
        _this = this;
        this.loading.vouchers = true;
        $.get(this.urls.main + "vouchers/claims", {
            unique_key_id: this.settings.unique_key_id,
            voucher_id: this.getVoucher.id
        }, function (response) {
            _this.loading.vouchers = false;
            if (response.status == true) {
                _this.vouchers.claims = [];
                for (var i = 0; i < response.data.length; i++) {
                    var listing = response.data[i];
                    listing.display_name = listing.name;
                    if (listing.display_name.length > 38) {
                        listing.display_name = listing.display_name.substring(0, 38) + "...";
                    }
                    _this.vouchers.claims.push(listing);
                }
            }
        }, 'json')
        .fail(function(response) {
            _this.showMessage(response.error);
        });
    },

    addVoucher: function() {
        _this = this;
        var error = false;
        var message = "";

        if (this.isEmpty(this.newVoucher.code)) {
            error = true;
            message = "Voucher code is required";
        } else if (this.isEmpty(this.newVoucher.capacity)) {
            error = true;
            message = "Voucher capacity is required";
        } else if (this.newVoucher.capacity <= 0) {
            error = true;
            message = "Voucher capacity cannot be 0.";
        } else if (this.isEmpty(this.newVoucher.period_length)) {
            error = true;
            message = "Voucher period is required";
        } else if (this.newVoucher.period_length <= 0) {
            error = true;
            message = "Voucher period length cannot be 0.";
        } else if (this.isEmpty(this.newVoucher.start_date)) {
            error = true;
            message = "Voucher start date is required.";
        } else if (this.isEmpty(this.newVoucher.end_date)) {
            error = true;
            message = "Voucher end date is required.";
        }
        
        if (error) {
            this.showMessage(message);
        } else {
            _this.showLoader("Adding voucher");
            $.post(this.urls.main + "vouchers/insert", {
                user_key_id: this.settings.user_key_id,
                unique_key_id: this.settings.unique_key_id,
                code: this.newVoucher.code,
                capacity: this.newVoucher.capacity,
                period_type: this.newVoucher.period_type,
                period_length: this.newVoucher.period_length,
                start_date: this.newVoucher.start_date,
                end_date: this.newVoucher.end_date
            }, function (response) {
                _this.hideLoader();
                if (response.status == true) {
                    _this.getVouchers();
                    _this.closeAddVoucherPage();
                    _this.newVoucher.clear();
                } else {
                    _this.showMessage(response.message);
                }
            }, 'json')
            .fail(function (response) {
                _this.showMessage(response.responseText);
            });
        }
    },

    updateVoucher: function(){
        _this = this;
        var error = false;
        var message = "";

        if (this.isEmpty(this.newVoucher.capacity)) {
            error = true;
            message = "Voucher capacity is required";
        } else if (this.newVoucher.capacity <= 0) {
            error = true;
            message = "Voucher capacity cannot be 0.";
        } else if (this.isEmpty(this.newVoucher.start_date)) {
            error = true;
            message = "Voucher start date is required.";
        } else if (this.isEmpty(this.newVoucher.end_date)) {
            error = true;
            message = "Voucher end date is required.";
        }

        if (error) {
            this.showMessage(message);
        } else {
            _this.showLoader("Saving voucher");
            $.post(this.urls.main + "vouchers/update", {
                id: this.getVoucher.id,
                user_key_id: this.settings.user_key_id,
                unique_key_id: this.settings.unique_key_id,
                capacity: this.newVoucher.capacity,
                start_date: this.newVoucher.start_date,
                end_date: this.newVoucher.end_date,
            }, function (response) {
                _this.hideLoader();
                if (response.status == true && response.data) {
                    _this.getVouchers();
                    _this.vouchers.voucher = response.data;
                    _this.closeEditVoucherPage();
                    _this.showMessage(response.message);
                } else {
                    _this.showMessage(response.message);
                }
            }, 'json')
            .fail(function (response) {
                _this.showMessage(response.responseText);
            });
        }
    },

    closeVoucher: function(){
        _this = this;
        this.showMessage(`
            Are sure you want to close this voucher?.<br/><br/>This action cannot be reversed .`, [
            {
                text: "Close",
                onTap: function () { 
                    _this.showLoader("Closing voucher");
                    $.post(_this.urls.main + "vouchers/close", {
                        id: _this.vouchers.voucher.id,
                        user_key_id: _this.user_key.id,
                    }, function (response) {
                        _this.hideLoader();
                        if (response.status == true) {
                            _this.vouchers.voucher = response.data;
                            _this.getVouchers();
                            // _this.closeViewVoucherPage();
                        } else {
                            _this.showMessage(response.message);
                        }
                    }, 'json')
                    .fail(function (response) {
                        _this.showMessage(response.responseText);
                    });
                }
            },
            {
                text: "Cancel",
                onTap: function () { }
            }
        ]);
    },

    deleteVoucher: function(){
        _this = this;
        this.showMessage(`
            Are sure you want to A this voucher?.<br/><br/>This action cannot be reversed .`, [
            {
                text: "Delete",
                onTap: function () { 
                    _this.showLoader("Deleting voucher");
                    $.post(_this.urls.main + "vouchers/delete", {
                        id: _this.vouchers.voucher.id,
                        user_key_id: _this.user_key.id,
                    }, function (response) {
                        _this.hideLoader();
                        if (response.status == true) {
                            _this.showMessage(response.message);
                            _this.vouchers.voucher = response.data;
                            _this.getVouchers();
                            _this.closeViewVoucherPage();
                        } else {
                            _this.showMessage(response.message);
                        }
                    }, 'json')
                    .fail(function (response) {
                        _this.showMessage(response.responseText);
                    });
                }
            },
            {
                text: "Cancel",
                onTap: function () { }
            }
        ]);
    },

    claimVoucher: function(){
        _this = this;
        if (this.getVoucher.available == 0) {
            this.showMessage("Voucher has been used, cannot claim voucher.");
        } else {
            this.showLoader("Claiming voucher");
            $.post(this.urls.main + "vouchers/claim", {
                user_key_id: this.settings.user_key_id,
                unique_key_id: this.settings.unique_key_id,
                listing_id: this.listing.id,
                voucher_code: this.newVoucher.searchCode
            }, function (response) {
                _this.hideLoader();
                if (response.status == true && response.data) {
                    _this.showMessage(response.message);
                    _this.listing = response.data;
                    _this.newVoucher.clear();
                    _this.getVouchers();
                    _this.getListings();
                    _this.updateAllListings();
                    _this.getAdminDashStats();
                    _this.closeVoucherPaymentPage();
                    _this.closeListingPayment();
                } else {
                    _this.showMessage(response.message);
                }
            }, 'json')
            .fail(function (response) {
                _this.showMessage(response.responseText);
            });
        }
    },

    searchVoucherByCode: function(){
        _this = this;
        if (this.isEmpty(this.newVoucher.searchCode)) {
            this.showMessage("Voucher Code required");
        } else {
            this.showLoader("Searching voucher");
            $.post(this.urls.main + "vouchers/search", {
                user_key_id: this.settings.user_key_id,
                unique_key_id: this.settings.unique_key_id,
                voucher_code: this.newVoucher.searchCode
            }, function (response) {
                _this.hideLoader();
                if (response.status == true && response.data) {
                    _this.newVoucher.codeFound = true;
                    _this.vouchers.voucher = response.data;
                    _this.getVouchers();
                } else {
                    _this.showMessage(response.message);
                }
            }, 'json')
            .fail(function (response) {
                _this.showMessage(response.responseText);
            });
        }
    },

    toggleIsActive: function(action){
        this.vouchers.voucher.is_active = action == 'yes' ? 1 : 0;
    },

    selectPeriodType: function(type) {
        this.newVoucher.period_type = type;
        this.closeSelectPeriodTypePage();
    },


    showListVouchersPage: function() {
        this.pages.depth++;
        this.getVouchers();
        this.openPage('listVouchers');
    }, 
    closeListVouchersPage: function() {
        this.pages.depth--;
        console.log("I am here");
        this.closePage('listVouchers');
    },

    showViewVoucherPage: function(voucher){
        this.pages.depth++;
        this.vouchers.voucher = voucher;
        this.getVoucherClaims();
        this.openPage('viewVoucher');
    },
    closeViewVoucherPage: function() {
        this.pages.depth--;
        this.vouchers.voucher = {};
        this.closePage('viewVoucher');
    },

    showAddVoucherPage: function() {
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

        this.pages.depth++;
        this.newVoucher.start_date = formattedDate;
        this.newVoucher.action = 'add';
        this.openPage('addVoucher');
    },  
    closeAddVoucherPage: function() {
        this.pages.depth--;
        this.newVoucher.action = '';
        this.closePage('addVoucher');
    },

    showEditVoucherPage: function(){
        this.pages.depth++;
        $('.datepicker').pickadate({
            format: 'yyyy-mm-dd',
            formatSubmit: 'yyyy-mm-dd',
            hiddenName: true,
            min: true
        });
        this.newVoucher.action = 'edit';
        this.newVoucher.capacity = this.getVoucher.capacity;
        this.newVoucher.end_date = this.getVoucher.end_date;
        this.newVoucher.start_date = this.getVoucher.start_date;
        this.openPage('editVoucher');
    },  
    closeEditVoucherPage: function() {
        this.pages.depth--;
        this.newVoucher.action = '';
        this.newVoucher.capacity = 0;
        this.newVoucher.end_date = '';
        this.newVoucher.start_date = '';
        this.closePage('editVoucher');
    },

    showSelectPeriodTypePage: function(){
        this.pages.depth++;
        this.openPage('selectPeriodType');
    },
    closeSelectPeriodTypePage: function(){
        this.pages.depth--;
        this.closePage('selectPeriodType');
    },

    showListVoucherClaimsPage: function(){
        this.pages.depth++;
        this.getVoucherClaims();
        this.openPage('listVoucherClaims');
    },
    closeListVoucherClaimsPage: function(){
        this.pages.depth--;
        this.closePage('listVoucherClaims');
    },

    showViewVoucherClaimPage: function(listing) {
        this.pages.depth++;
        this.listing = listing;
        this.openPage('viewVoucherClaim');
    },
    closeViewVoucherClaimPage: function() {
        this.pages.depth--;
        this.listing = {};
        this.closePage('viewVoucherClaim');
    },

    openVoucherPaymentPage: function(){
        this.pages.depth++;
        this.openPage('voucherPayment');
    },
    closeVoucherPaymentPage: function(){
        this.pages.depth--;
        this.closePage('voucherPayment');
    },

});

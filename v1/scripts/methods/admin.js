
var adminMethods = Object.freeze({

    checkAvailableDownloadMonths: function(){
        _this = this;
        this.showLoader("Getting availbale download log");
        $.get(this.urls.main + "payments/checkAvailableLog", {
            unique_key_id: this.settings.unique_key_id
        }, function (response) {
            _this.hideLoader();
            if (response.status == true) {
                _this.availablePaymentLogs = response.data;
            }
        }, 'json')
        .fail(function(response) {
            _this.showMessage(response.error);
        });
    },

    downloadMonthlyLog: function(logItem) {
        _this = this;
        this.showLoader("Downloading payment log");
        $.get(this.urls.main + "payments/monthlyLog", {
            unique_key_id: this.settings.unique_key_id,
            start_date: logItem.unique_month_year,
        }, function (response) {
            _this.hideLoader();
            if (response.status == true) {
                _this.paymentLogLink = response.data;
                if (_this.isMobile) {
                    CommunicationBridge.postMessage(JSON.stringify({
                        request: 'download',
                        payload: { url: response.data },
                        hook: ''
                    }));
                } else {
                    _this.openUrl(response.data);
                }
            }
        }, 'json')
        .fail(function(response) {
            _this.showMessage(response.error);
        });
    },

    showAdminMenu: function(){
        this.pages.depth++;
        this.openPage('adminMenu');
    },
    closeAdminMenu: function(){
        this.pages.depth--;
        this.closePage('adminMenu');
    },

    showPaymentLogsPage: function() {
        this.pages.depth++;
        this.checkAvailableDownloadMonths();
        this.openPage('paymentLogs');
    },
    closePaymentLogsPage: function() {
        this.pages.depth--;
        this.closePage('paymentLogs');
    },

    // showApprovalListings: function(){

    // },
    // closeApprovalListings: function(){

    // },

    // showManageListings: function(){

    // },
    // closeManageListings: function(){

    // },

    // showDowngradedListings: function(){

    // },
    // closeDowngradedListings: function(){

    // },

});
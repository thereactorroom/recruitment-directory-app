
var admin_methods = Object.freeze({

    // getViews: function(business){
    //     _this = this;
    //     this.loading.businesses = true;
        // $.get(this.urls.analytics + "impressions/search", {
        //     event: "listing_opened",
        //     event_id: business.id
        // }, function (response) {
        //     _this.loading.businesses = false;
        //     if (response.status == true) {
        //         for (var i = 0; i < response.data.length; i++) {
        //             var business = response.data[i];
        //             business.display_name = business.business_name;
        //             if (business.display_name.length > 38) {
        //                 business.display_name = business.display_name.substring(0, 38) + "...";
        //             }
        //             response.data[i] = business;
        //         }
        //         _this.approvals.list = response.data;
        //     }
        // }, 'json')
        // .fail(function(response) {
        //     _this.showMessage(response.error);
        // });
    // },

    checkAvailableDownloadMonths: function(){
        _this = this;
        this.showLoader("Getting availbale download log");
        $.get(this.urls.main + "payments/checkAvailableLog", {
            unique_key_id: this.unique_key_id
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
            unique_key_id: this.unique_key_id,
            start_date: logItem.unique_month_year,
        }, function (response) {
            _this.hideLoader();
            if (response.status == true) {
                _this.paymentLogLink = response.data;
                if (_this.settings.mobile()) {
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

    /**
     * Pages
     */
    showPaymentLogsPage: function() {
        this.pages.depth++;
        this.checkAvailableDownloadMonths();
        this.openPage('paymentLogs');
    },
    closePaymentLogsPage: function() {
        this.pages.depth--;
        this.closePage('paymentLogs');
    },

    showEngagementStatsPage: function() {
        this.pages.depth++;
        // this.getEngagementStats();
        this.openPage('engagementStats');
    },
    closeEngagementStatsPage: function() {
        this.pages.depth--;
        this.closePage('engagementStats');
    },

});
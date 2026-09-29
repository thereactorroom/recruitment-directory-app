

var recommendations_methods = Object.freeze({

    getRecommendationCallLog: function () {
        _this = this;
        $.get(this.urls.main + "recommendations/get_call_log", {
            unique_key_id: this.traders.trader.unique_key_id,
            recommendation_id: this.traders.trader.recommendation_id
        }, function (response) {
            if (response.status == true) {
                _this.recommendations.call_log = response.data;
            }
        }, 'json')
        .fail(function(response) {
            _this.showMessage(response.error);
        });
    },

    addRecommendationCallLog: function () {
        _this = this;
        this.openDialerApp(this.traders.trader.mobile);
        $.post(this.urls.main + "recommendations/add_call_log", {
            user_key_id: this.user_key.id,
            unique_key_id: this.unique_key_id,
            recommendation_id: this.traders.trader.recommendation_id
        }, function (response) {
            if (response.status == true) {
                _this.getRecommendations();
                _this.getRecommendationCallLog();
            }
        }, 'json')
        .fail(function(response) {
            _this.showMessage(response.error);
        });
    },

    addCallLogComment: function () {
        _this = this;
        // if (this.recommendations.call_log_item.comment.length > 0) {
        //     _this.showMessage("Comment already added");
        // } else {
        //     console.log(this.recommendations.call_log_item);
        $.post(this.urls.main + "recommendations/add_call_log_comment", {
            id: this.recommendations.call_log_item.id,
            comment: this.recommendations.callLogComment,
        }, function (response) {
            if (response.status == true) {
                _this.getRecommendations();
                _this.closeCallLogComment();
                _this.getRecommendationCallLog();
            }
        }, 'json')
        .fail(function (response) {
            _this.showMessage(response.error);
        });
        // }
    },

    /**
     * filterRecommendations: returns a list of traders
     * @param {*} category 
     */
    filterRecommendations: function(){
        _this = this;
        this.recommendations.list = this.traders.all.filter((trader) => { 
            return trader.recommended;
        });
    },

    formatRecommendationDateTime: function () {
        
    },

    searchRecommendationsByName: function (trader) {
        var name = trader.trader_name.toLowerCase();
        var description = trader.description.toLowerCase();
        var filter = this.recommendations.mainFilter.toLowerCase();
        if (`${name}`.indexOf(filter) > -1 || `${description}`.indexOf(filter) > -1) {
            return true;
        }
        return false;
    },

    recommendationTraderName: function (trader) {
        var trader_name = trader.trader_name;
        if (trader_name.length > 38) {
            trader_name = trader_name.substring(0, 38) + "...";
        }
        return trader_name;
    },

    showRecommendations: function () {
        this.filterRecommendations();
        this.pages.depth++;
        this.pages.page = "recommendations";
        this.openPage('recommendations');
    },

    closeRecommendations: function () {
        this.pages.depth--;
        this.pages.page = "";
        this.traders.sort.nameAscending = false;
        this.traders.sort.dateAscending = false;
        this.traders.sort.ratingAscending = false;
        this.closePage('recommendations');
    },

    showRecommendationsCallLog: function (trader) {
        this.pages.depth++;
        this.traders.trader = trader;
        this.getRecommendationCallLog();
        this.openPage('recommendations_call_log');
    },

    closeRecommendationsCallLog: function () {
        this.pages.depth--;
        this.traders.trader = {};
        this.closePage('recommendations_call_log');
    },

    showCallLogComment: function (call_log_item) {
        if (call_log_item.comment.length > 0) {
            _this.showMessage("Comment already added");
        } else {
            this.pages.depth++;
            this.recommendations.call_log_item = call_log_item;
            this.openPage('call_log_comment');
        }
    },

    closeCallLogComment: function () {
        this.pages.depth--;
        this.recommendations.call_log_item = {};
        this.closePage('call_log_comment');
    },

});




var analytics_methods = Object.freeze({

    getViews: function(business){
        _this = this;
        this.loading.businesses = true;
        $.get(this.urls.analytics + "impressions/search", {
            event: "listing_opened",
            event_id: business.id
        }, function (response) {
            _this.loading.businesses = false;
            if (response.status == true) {
                for (var i = 0; i < response.data.length; i++) {
                    var business = response.data[i];
                    business.display_name = business.business_name;
                    if (business.display_name.length > 38) {
                        business.display_name = business.display_name.substring(0, 38) + "...";
                    }
                    response.data[i] = business;
                }
                _this.approvals.list = response.data;
            }
        }, 'json')
        .fail(function(response) {
            _this.showMessage(response.error);
        });
    },

    startAnalyticsSession: function(business){
        _this = this;
        $.post(this.urls.analytics + "sessions/start", {
            community_id: this.community_id,
            content_id: this.content_id,
            name: this.module_name,
            user_id: this.member.userId,
            member_id: this.member.memberId,
            trigger: this.getTriger(),
            event: "listing_opened",
            event_id: business.id,
            event_index: `${business.business_name} ${business.description}`
        }, function (response) {
            if (response.status == true) {
                _this.session = response.data;
            }
        }, 'json')
        .fail(function(response) {
            _this.showMessage(response.error);
        });
    },

    endAnalyticsSession: function(business){
        _this = this;
        $.post(this.urls.analytics + "sessions/end", {
            session_id: this.session.id,
            trigger: this.getTriger(),
            event: "listing_closed",
            event_id: business.id,
            event_index: `${business.business_name} ${business.description}`
        }, function (response) {
            if (response.status == true) {
                _this.session = undefined;
            }
        }, 'json')
        .fail(function(response) {
            _this.showMessage(response.error);
        });
    },

    logImpression: function(event, business){
        _this = this;
        $.post(this.urls.analytics + "impressions/log", {
            session_id: this.session.id,
            trigger: this.getTriger(),
            event: event,
            event_id: business.id,
            event_index: `${business.business_name} ${business.description}`
        }, function (response) {
            if (response.status == true) {
                
            }
        }, 'json')
        .fail(function(response) {
            _this.showMessage(response.error);
        });
    },

    /** Helpers */
    getTriger: function(){
        if (this.settings.mobile()) {
            return "touch";
        } else {
            return "click";
        }
    },

});
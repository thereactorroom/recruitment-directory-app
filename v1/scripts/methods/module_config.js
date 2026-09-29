
var moduleCondigMethods = Object.freeze({

    /**
     * getModuleConfig: get module config
     * 
     */
    getModuleConfig: function () {
        _this = this;
        this.loading.favorites = true;
        $.get(this.urls.main + "moduleConfig/get", {
            unique_key_id: this.settings.unique_key_id,
        }, function (response) {
            if (response.status == true && response.data) {
                _this.moduleConfig = response.data;
            }
        }, 'json')
        .fail(function(response) {
            _this.showMessage(response.error);
        });
    },

    /**
     * saveModuleConfigChanges: 
     */
    saveModuleConfigChanges: function() {
        _this = this;
        let error = false;
        let message = "";

        if (this.isEmpty(this.moduleConfig.app_title)) {
            error = true;
            message = "App Title";
        } 
        
        if (error) {
            this.showMessage(`${message} is required.`, [{
                text: "Ok",
                onTap: function() {}
            }]);
        } else {
            console.log(this.moduleConfig.app_title, ' - ' , this.moduleConfig.base44_specials_url);
            this.showLoader("Saving module config");
            $.post(this.urls.main + "moduleConfig/save", {
                unique_key_id: this.settings.unique_key_id,
                app_title: this.moduleConfig.app_title,
                base44_specials_url: this.moduleConfig.base44_specials_url
            }, function(response) {
                _this.hideLoader();
                _this.showMessage(response.message);
                if (response.status == true) {
                    _this.getModuleConfig();
                }
            }, 'json')
            .fail(function(response) {
                _this.showMessage(response.responseText);
            });
        }
    },

    /**
     * showModuleConfigPage
     */
    showModuleConfigPage: function () {
        this.pages.depth++;
        this.openPage('moduleConfig');
    },

    /**
     * closeModuleConfigPage
     */
    closeModuleConfigPage: function () {
        this.pages.depth--;
        this.closePage('moduleConfig');
    }


});


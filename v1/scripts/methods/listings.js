
var listingsMethods = Object.freeze({

    /**
     * getTraders: returns a list of traders
     * @param {*} category 
     */
    getAllListings: function(){
        _this = this;
        this.loading.all = true;
        $.get(this.urls.main + "listings/list", {
            user_key_id: this.settings.user_key_id,
            unique_key_id: this.settings.unique_key_id,
            type: 'all',
        }, function (response) {
            _this.loading.all = false;
            if (response.status == true && response.data) {
                _this.listings.all = [];
                _this.listings.list = [];
                _this.listings.all = response.data;
                _this.updateAllListings();
            }
        }, 'json')
        .fail(function(response) {
            _this.showMessage(response.error);
        });
    },

    getListings: function(){
        _this = this;
        this.loading.listings = true;
        $.get(this.urls.main + "listings/list", {
            user_key_id: this.settings.user_key_id,
            unique_key_id: this.settings.unique_key_id,
            type: 'status',
            status: "approved",
        }, function (response) {
            _this.loading.listings = false;
            if (response.status == true && response.data) {
                _this.listings.all = [];
                _this.listings.list = [];
                _this.listings.all = response.data;
            }
        }, 'json')
        .fail(function(response) {
            _this.showMessage(response.error);
        });
    },

    aGetListings: async function () {
        this.loading.listings = true;
        const response = await $.get(this.urls.main + "listings/list", {
            user_key_id: this.settings.user_key_id,
            unique_key_id: this.settings.unique_key_id,
            type: 'status',
            status: "approved",
        }, 'json');
        var results = await response;
        if (results.status == true && results.data) {
            this.listings.all = [];
            this.listings.list = [];
            this.listings.all = results.data;
        }
        this.loading.listings = false;
    },

    getMyListings: function () {
        _this = this;
        this.loading.listings = true;
        $.get(this.urls.main + "listings/list", {
            user_key_id: this.settings.user_key_id,
            unique_key_id: this.settings.unique_key_id,
            type: 'mine'
        }, function (response) {
            _this.loading.listings = false;
            if (response.status == true && response.data) {
                _this.listings.mine = [];
                for (var i = 0; i < response.data.length; i++) {
                    var item = response.data[i];
                    item.display_name = item.name;
                    if (item.display_name.length > 38) {
                        item.display_name = item.display_name.substring(0, 38) + "...";
                    }
                    _this.listings.mine.push(item);
                }
            }
        }, 'json')
        .fail(function(response) {
            _this.showMessage(response.error);
        });
    },

    aGetMyListings: async function () {
        const response = await $.get(this.urls.main + "listings/list", {
            user_key_id: this.settings.user_key_id,
            unique_key_id: this.settings.unique_key_id,
            type: 'mine'
        }, 'json');
        var results = await response;
        if (results.status == true && results.data) {
            this.listings.mine = [];
            for (var i = 0; i < results.data.length; i++) {
                var item = response.data[i];
                item.display_name = item.name;
                if (item.display_name.length > 38) {
                    item.display_name = item.display_name.substring(0, 38) + "...";
                }
                this.listings.mine.push(item);
            }
        }
    },

    updateAllListings: function () {
        for (var i = 0; i < this.listings.all.length; i++) {
            var item = this.listings.all[i];
            item.display_name = item.name;
            if (item.display_name.length > 30) {
                item.display_name = item.display_name.substring(0, 30) + "...";
            }
            this.listings.all[i] = item;
        }

        this.listings.list = [];
        this.listings.admin = [];
        for (var i = 0; i < this.listings.all.length; i++) {                
            this.listings.list.push(this.listings.all[i]);
            this.listings.admin.push(this.listings.all[i]);
        }
    },

    listingExists: function () {
        var exists = false;
        for (var i = 0; i < this.listings.all.length; i++) {
            if (this.listings.all[i].id === this.newListing.listing_id) {
                continue;
            }
            var name = this.listings.all[i].name.toLowerCase();
            var contact_number = this.listings.all[i].contact_number.toLowerCase();
            if ((name == this.newListing.name.toLowerCase()) || (contact_number == this.newListing.contact_number.toLowerCase())) {
                exists = true;
                break;
            }
        }
        return exists;
    },

    listingNoLogo: function (name) { 
        if (!this.isEmpty(name)) {
            console.log(name, " this is still a test");
            const names = name.split(" ");
            if (names.length == 1) {
                return names[0].substring(0, 1);
            } else {
                return names[0].substring(0, 1) + names[1].substring(0, 1);
            }
        }
        return "";
    },

    listingLogo: function (logo, thumb) {
        if (logo != undefined) {
            if (logo.includes(host) && thumb != undefined) {
                return `background-image: url(${this.urls.thumb}${logo}${thumb})`;
            } else {
                return `background-image: url(${logo})`;
            }
        }
        return "";
    },

    selectListingGender: function(gender) {
        if (this.listing !== undefined) {
            this.listing.gender = gender
        }
        this.newListing.gender = gender;
        this.closeSelectListingGender();
    },


    callListintg: function(listing){
        this.openDialerApp(listing.contact_number)
    },
    whatsAppListing: function(listing){
        // this.logImpression('whatsapp_listing', business);
        this.openWhatsAppApp(listing.whatsapp);
    },
    emailListing: function(listing){
        // this.logImpression('email_listing', business);
        this.openEmailApp(listing.email);
    },
    openListingUrl: function(url) {
        if(!this.isEmpty(url)) {
            url = this.normalizeUrl(url);
            processContent({ contentType: "url", url: url });
        } else {
            this.showMessage(`Invalid URL format`, [{
                text: "OK",
                onTap: function () { }
            }]);
        }
    },
    openPDFDocument: function(url) {
        if (this.isMobile) {
            CommunicationBridge.postMessage(JSON.stringify({
                request: 'download',
                payload: { url: url },
                hook: ''
            }));
        } else {
            _this.openUrl(url);
        }
    },

    shareListing: function(platform = 'whatsapp'){
        var full_name = this.settings.user.name + ' ' + this.settings.user.surname;
        var listing_name = this.listing.name;
        var contact_number = this.listing.contact_number;
        var copy = `
${full_name} is referring ${listing_name} Physio to you. Contact Physio: ${contact_number}. Supplied by the ${this.settings.community_name} Physio directory.
        `;
        if (platform == 'whatsapp') {
            this.openWhatsAppApp(undefined, copy);
        } else if (platform == 'sms') {
            if (this.isMobile) {
                this.sendSMS(undefined, copy);
            } else {
                this.showMessage("Sending SMS not allowed on Web.");
            }
        }
    },

    decodeNewListingContact: function(data) {
        let decoded = decodeMobileNumber(data.phones[0].number);
        this.contact.flag = decoded.icon;
        this.newListing.contact_code = decoded.code;
        this.newListing.contact_number = decoded.number;
    },
    decodeNewListingOffice: function(data) {
        let decoded = decodeMobileNumber(data.phones[0].number);
        this.contact.flag = decoded.icon;
        this.newListing.contact_code = decoded.code;
        this.newListing.office_number = decoded.number;
    },
    decodeEditListingContact: function(data) {
        let decoded = decodeMobileNumber(data.phones[0].number);
        this.contact.flag = decoded.icon;
        this.listing.contact_code = decoded.code;
        this.listing.contact_number = decoded.number;
    },
    decodeEditListingOffice: function(data) {
        let decoded = decodeMobileNumber(data.phones[0].number);
        this.contact.flag = decoded.icon;
        this.listing.contact_code = decoded.code;
        this.listing.office_number = decoded.number;
    },

    /**
     *  Pages functions
     */
    showListing: function(listing) {
        this.listing = listing;
        this.getComments(listing.id);
        if (listing.free) {
            this.showViewFreeListing();
        } else {
            this.showViewPaidListing();
        }
    },

    showViewListings: function(){
        this.pages.depth++;
        this.getMyListings();
        this.openPage('viewListings');
    },
    closeViewListings: function(){
        this.pages.depth--;
        this.closePage('viewListings');
    },

    showSelectListingGender: function(){
        this.pages.depth++;
        this.openPage('listingSelectGender');
    },
    closeSelectListingGender: function(){
        this.pages.depth--;
        this.closePage('listingSelectGender');
    },

    showShareListing: function() {
        this.pages.depth++;
        this.openPage('shareListing');
    },
    closeShareListing: function() {
        this.pages.depth--;
        this.closePage('shareListing');
    },

    showListingPayment: function(){
        this.pages.depth++;
        this.openPage('listingPayment');
    },
    closeListingPayment: function(){
        this.pages.depth--;
        this.closePage('listingPayment');
    },

    openPayFastComponent: async function(amount) {
        _this = this;
        this.showLoader("Generating payment uuid...");
        const response = await $.post(this.urls.main + "payments/generatePayFastIdentifier", {
            user_key_id: this.settings.user_key_id,
            unique_key_id: this.settings.unique_key_id,
            listing_id: this.listing.id,
            amount: amount
        }, 'json');
        this.hideLoader();
        var results = await response;

        let uuid = undefined;
        if (results.status == true && results.data) {
            uuid = results.data;
        }

        if (uuid == undefined) {
            this.showMessage("Error generating payment uuid. Please try again later.");
        } else {
            var contentId = "2512";
            var componentObj = `fpay_v1_${this.settings.community_id}2512`;
            var capp = "recruitment_directory" + this.settings.unique_key;
            fusion.getComponent({
                name: componentObj,
                uri: `/modules/module_dev/payments/v1/index.php?component=true&capp=${capp}&uuid=${uuid}&app=listings`,
                communityId: this.settings.community_id,
                contentId: contentId,
                params: { refresh: true }
            }, function() {
                $(`.fusion-body`).addClass('fusion-overlay');
                $(`.components, .${componentObj}`).show();
            });
        }
    },

    openSpecialsModule: function(){
        _this = this;

        if (this.isEmpty(this.moduleConfig.base44_specials_url)) {
            this.showMessage("Specials module URL is not configured. Please configure to continue");
            return;
        }

        var contentId = "";        
        var specialsUrl = '';
        if (this.isContentCreator(this.listing)) {
            specialsUrl = `${this.moduleConfig.base44_specials_url}/?create=true&fID=${user.userId}&BusinessID=${this.listing.id}`;
        } else {
            specialsUrl = `${this.moduleConfig.base44_specials_url}/?create=false&fID=${user.userId}&BusinessID=${this.listing.id}&View=true`;
        }

        fusion.getComponent({
            name: `base44_specials${this.settings.community_id}${contentId}`,
            uri: specialsUrl,
            communityId: this.settings.community_id,
            contentId: contentId,
            type: 'iframe_component',
            params: {
                refresh: true
            }
        }, function() {
            $(`.fusion-body`).addClass('fusion-overlay');
            $(`.components, .base44_specials${this.settings.community_id}${contentId}`).show();
        });
    },

});


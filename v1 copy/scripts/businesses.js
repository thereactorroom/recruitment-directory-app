

var businesses_methods = Object.freeze({

    /**
     * getTraders: returns a list of traders
     * @param {*} category 
     */
    getApprovedBusinesses: function(){
        _this = this;
        this.loading.businesses = true;
        $.get(this.urls.main + "businesses/list_by_status", {
            user_key_id: this.user_key.id,
            unique_key_id: this.unique_key_id,
            status: "Approved",
        }, function (response) {
            _this.businesses.all = [];
            _this.businesses.list = [];
            _this.loading.businesses = false;
            if (response.status == true && response.data) {
                _this.businesses.all = response.data;
            }
        }, 'json')
        .fail(function(response) {
            _this.showMessage(response.error);
        });
    },
    aGetApprovedBusinesses: async function () {
        this.loading.businesses = true;
        const response = await $.get(this.urls.main + "businesses/list_by_status", {
            user_key_id: this.user_key.id,
            unique_key_id: this.unique_key_id,
            status: "Approved"
        }, 'json');
        var results = await response;
        if (results.status == true && results.data) {
            this.businesses.all = [];
            this.businesses.list = [];
            this.businesses.all = results.data;
        }
        this.loading.businesses = false;
    },

    getReferralBusinesses: function () {
        _this = this;
        $.get(this.urls.main + "businesses/referrals", {
            user_key_id: this.user_key.id,
            unique_key_id: this.unique_key_id,
            referral: 1
        }, function (response) {
            if (response.status == true && response.data) {
                _this.referrals.all = [];
                for (var i = 0; i < response.data.length; i++) {
                    var business = response.data[i];
                    business.display_name = business.business_name;
                    if (business.display_name.length > 38) {
                        business.display_name = business.display_name.substring(0, 38) + "...";
                    }
                    _this.referrals.all.push(business);
                }
            }
        }, 'json')
        .fail(function(response) {
            _this.showMessage(response.error);
        });
    },
    aGetReferralBusinesses: async function () {
        const response = await $.get(this.urls.main + "businesses/referrals", {
            user_key_id: this.user_key.id,
            unique_key_id: this.unique_key_id,
            referral: 1
        }, 'json');
        var results = await response;
        if (results.status == true && results.data) {
            this.referrals.all = [];
            for (var i = 0; i < results.data.length; i++) {
                var business = results.data[i];
                business.display_name = business.business_name;
                if (business.display_name.length > 38) {
                    business.display_name = business.display_name.substring(0, 38) + "...";
                }
                this.referrals.all.push(business);
            }
        }
    },

    getMyBusinesses: function () {
        _this = this;
        this.loading.myListings = true;
        $.get(this.urls.main + "businesses/my_listings", {
            user_key_id: this.user_key.id,
            unique_key_id: this.unique_key_id,
        }, function (response) {
            _this.loading.myListings = false;
            if (response.status == true && response.data) {
                _this.myListings.list = [];
                for (var i = 0; i < response.data.length; i++) {
                    var business = response.data[i];
                    business.display_name = business.business_name;
                    if (business.display_name.length > 38) {
                        business.display_name = business.display_name.substring(0, 38) + "...";
                    }
                    _this.myListings.list.push(business);
                }
            }
        }, 'json')
        .fail(function(response) {
            _this.showMessage(response.error);
        });
    },
    aGetMyBusinesses: async function () {
        const response = await $.get(this.urls.main + "businesses/my_listings", {
            user_key_id: this.user_key.id,
            unique_key_id: this.unique_key_id,
        }, 'json');
        var results = await response;
        if (results.status == true && results.data) {
            this.myListings.list = [];
            console.log(results.data.length);
            for (var i = 0; i < results.data.length; i++) {
                var business = results.data[i];
                business.display_name = business.business_name;
                if (business.display_name.length > 38) {
                    business.display_name = business.display_name.substring(0, 38) + "...";
                }
                this.myListings.list.push(business);
            }
        }
    },

    getBusinessRejectedStatus: function(business) {
        _this = this;
        $.get(this.urls.main + "businesses/statuses", {
            user_key_id: this.user_key.id,
            unique_key_id: this.unique_key_id,
            business_id: business.id,
            status: "Rejected"
        }, function (response) {
            if (response.status == true && response.data) {
                _this.businesses.statuses = [];
                _this.businesses.statuses = response.data;
            }
        }, 'json')
        .fail(function(response) {
            _this.showMessage(response.error);
        });
    },

    updateBusiness: function(){
        _this = this;
        var error = false;
        var message = "";
        console.log('i am here');
        if (this.isEmpty(this.businesses.business.business_name)) {
            error = true;
            message = "Business Name is required.";
        } else if (this.isEmpty(this.businesses.business.email)) {
            error = true;
            message = "Email Address is required.";
        } else if (!this.isValidEmail(this.businesses.business.email)) {
            error = true;
            message = "Please enter a valid email.";
        } else if (this.isEmpty(this.businesses.business.contact_number)) {
            error = true;
            message = "Contact Number is required.";
        } else if (!this.isValidMobile(this.businesses.business.contact_number)) {
            error = true;
            message = "Please enter a valid contact number.";
        }
        // } else if (this.isEmpty(this.businesses.business.office_number)) {
        //     error = true;
        //     message = "Office Number is required.";
        // } else if (!this.isValidMobile(this.businesses.business.office_number)) {
        //     error = true;
        //     message = "Please enter a valid office number.";
        // } 
        
        if (error) {
            this.showMessage(message);
        } else {
            this.showLoader("Saving business details...");
            $.post(this.urls.main + "businesses/update", {
                user_key_id: this.user_key.id,
                unique_key_id: this.unique_key_id,
                id: this.businesses.business.id,
                business_name: this.businesses.business.business_name,
                vat_number: this.businesses.business.vat_number,
                registration_number: this.businesses.business.registration_number,
                country_code: this.businesses.business.country_code,
                contact_number: this.businesses.business.contact_number,
                office_number: this.businesses.business.office_number,
                email: this.businesses.business.email,
                person_name: this.businesses.business.person_name,
                person_surname: this.businesses.business.person_surname,
                description: this.businesses.business.description,
                logo: this.businesses.business.logo,
                google_url: this.businesses.business.google_url,
                website_url: this.businesses.business.website_url,
                facebook_url:this.businesses.business.facebook_url,
                x_url:this.businesses.business.x_url,
                instagram_url: this.businesses.business.instagram_url
            }, async function (response) {
                _this.hideLoader();
                if (response.status == true && response.data) {
                    _this.businesses.business = response.data;
                    await _this.aGetMyBusinesses();
                    await _this.aGetApprovedBusinesses();
                    _this.updateAllBusinesses();
                } else {
                    _this.showMessage(response.message);
                }
            }, 'json')
            .fail(function (response) {
                _this.showMessage(response.responseText);
            });
        }
    },

    deleteBusiness: function(){
        _this = this;
        var business_name = this.businesses.business.business_name;
        this.showMessage(`
            Are you sure you want to delete <br/><b>${business_name}</b>?
            <br/><br/>
            This action cannot be reversed.
            `, [{
                text: "Yes",
                onTap: function () { 
                    _this.showLoader("Deleting business details...");
                    $.post(_this.urls.main + "businesses/delete", {
                        user_key_id: _this.user_key.id,
                        unique_key_id: _this.unique_key_id,
                        id: _this.businesses.business.id
                    }, async function (response) {
                        _this.hideLoader();
                        if (response.status == true && response.data) {
                            _this.getMyBusinesses();
                            await _this.aGetApprovedBusinesses();
                            _this.updateAllBusinesses();
                            _this.closeBusinessPage();
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

    reSubmitBusiness: function() {
        _this = this;
        this.showLoader("Saving business details...");
        $.post(this.urls.main + "businesses/reSubmit", {
            id: this.businesses.business.id
        }, function (response) {
            _this.hideLoader();
            if (response.status == true && response.data) {
                _this.businesses.business = response.data;
                _this.getMyListings();
                _this.getAllBusinesses();
            } else {
                _this.showMessage(response.message);
            }
        }, 'json')
        .fail(function (response) {
            _this.showMessage(response.responseText);
        });
    },

    /**
     * searchTradersByName
     * Search if trader matched any of the given traders in the list
     * @param {*} trader 
     * @returns true ? false
     */
    searchBusinessByName: function (business) {
        var name = business.business_name.toLowerCase();
        var description = business.description.toLowerCase();
        var filter = this.businesses.mainFilter.toLowerCase();

        // this.filtered = this.users.filter(user =>
        //     user.name.toLowerCase().includes(this.searchQuery.toLowerCase())
        // );

        this.businesses.list = this.businesses.list.filter(item => {
            `${item.name}`.indexOf(filter) > -1 || `${item.description}`.indexOf(filter) > -1
        });

        // console.log(this.businesses.list);

        // if (`${name}`.indexOf(filter) > -1 || `${description}`.indexOf(filter) > -1) {
        //     return true;
        // }
        // return false;
    },

    updateAllBusinesses: function () {
        for (var i = 0; i < this.businesses.all.length; i++) {
            var business = this.businesses.all[i];
            business.display_name = business.business_name;
            if (business.display_name.length > 38) {
                business.display_name = business.display_name.substring(0, 38) + "...";
            }
            // if (business.referral) {
            //     business.user_key_id = this.getReferredUserKeyId(business);
            // }
            this.businesses.all[i] = business;
        }

        for (var i = 0; i < this.businesses.all.length; i++) {                
            this.businesses.list.push(this.businesses.all[i]);
        }

        // this.businesses.list = this.sortBusinesses(this.businesses.list);
    },

    sortBusinesses: function(data) {
        return data.sort((a, b) => {

            // 1. Most recent date_paid first
            if (a.payment_date !== b.payment_date) {
                return new Date(b.payment_date) - new Date(a.payment_date);
            }

            // 1. Paid first
            if (a.paid !== b.paid) {
                return b.paid - a.paid;
            }

            // 2. Top rated first
            if (a.stars_avg !== b.stars_avg) {
                return b.stars_avg - a.stars_avg;
            }

            // 3. If not paid, referrals next
            if (a.paid === 0 && b.paid === 0) {
                if (a.referral !== b.referral) {
                    return b.referral - a.referral;
                }
            }

            // 4. Optional fallback (sort_index if needed)
            return b.sort_index - a.sort_index;
        });
    },

    /**
     * updateBusinessList - updates businesses list 
     * @param {*} business 
     */
    updateBusinessList: function (business) {
        const index = this.businesses.list.findIndex(object => {
            return object.id === business.id;
        });
        this.businesses.list[index] = trader;

        const allIndex = this.businesses.all.findIndex(object => {
            return object.id === business.id;
        });
        this.businesses.all[allIndex] = trader;
    },

    /**
     * updateRecommendedTraderList - update traders list from recommendation
     * @param {*} trader 
     */
    updateRecommendedTraderList: function (trader) {
        const index = this.traders.list.findIndex(object => {
            return object.recommended && object.id === trader.id;
        });
        this.traders.list[index] = trader;

        const allIndex = this.traders.all.findIndex(object => {
            return object.recommended && object.id === trader.id;
        });
        this.traders.all[allIndex] = trader;
    },
    
    /**
     * businessNoLogo
     * @param {*} business 
     * @returns 
     */
    businessNoLogo: function (business) { 
        const names = business.business_name.split(" ");
        if (names.length == 1) {
            return names[0].substring(0, 1);
        } else {
            return names[0].substring(0, 1) + names[1].substring(0, 1);
        }
    },
    businessLogo: function (logo, thumb) {
        if (logo != undefined) {
            if (logo.includes(host) && thumb != undefined) {
                return `background-image: url(${this.urls.thumb}${logo}${thumb})`;
            } else {
                return `background-image: url(${logo})`;
            }
        }
        return "";
    },
    getTraderByTraderId: function (trader4_id) {
        return this.traders.list.filter(item => item.trader4_id == trader4_id)[0];
    },

    /**
     * handleRecommendedTrader
     * @param {*} trader 
     */
    handleRecommendedTrader: function (trader) {
        if (this.isContentCreator(trader)) {
            this.showEditRecommendation(trader);
        } else {
            this.openDialerApp(trader.mobile);
        }
    },

    nextTab: function(page = undefined) {        
        console.log('index: ', this.pages.navTabIndex);
        if (this.pages.navTabIndex >= this.pages.navTabs.length - 1) {
            return;
        }

        if (page == undefined && this.pages.navTabIndex == 0) {
            if (this.isEmpty(this.businesses.business.business_name)) {
                this.showMessage("Business Name is required.");
                return false;
            } else if (this.isEmpty(this.businesses.business.email)) {
                this.showMessage("Email Address is required.");
                return false;
            } else if (!this.isValidEmail(this.businesses.business.email)) {
                this.showMessage("Please enter a valid email.");
                return false;
            } else if (this.isEmpty(this.businesses.business.contact_number)) {
                this.showMessage("Contact Number is required.");
                return false;
            } else if (!this.isValidMobile(this.businesses.business.contact_number)) {
                this.showMessage("Please enter a valid contact number.");
                return false;
            }
        } else if (page == 'registration' && this.pages.navTabIndex == 0) {
            if (this.isEmpty(this.registration.business_name)) {
                this.showMessage("Business Name is required.");
                return false;
            } else if (this.isEmpty(this.registration.email)) {
                this.showMessage("Email Address is required.");
                return false;
            } else if (this.isEmpty(this.registration.email)) {
                this.showMessage("Email Address is required.");
                return false;
            } else if (!this.isValidEmail(this.registration.email)) {
                this.showMessage("Please enter a valid email.");
                return false;
            } else if (this.isEmpty(this.registration.contact_number)) {
                this.showMessage("Contact Number is required.");
                return false;
            } else if (!this.isValidMobile(this.registration.contact_number)) {
                this.showMessage("Please enter a valid contact number.");
                return false;
            }
        }

        this.pages.navTabIndex++;

        if (page == undefined) {
            var previous = this.pages.navTab;
            var current = this.pages.navTabs[this.pages.navTabIndex];
            this.pages.navTab = current;
        } else if (page == 'registration') {
            var previous = this.pages.navTabRegistration;
            var current = this.pages.navTabs[this.pages.navTabIndex];
            this.pages.navTabRegistration = current;
        }

        console.log('index after increment: ', this.pages.navTabIndex);
        console.log('current: ', current);

        $(`.${current}Tab${this.unique_key}`).addClass(`f-act-bg-${this.unique_key}`);
        $(`.${previous}Tab${this.unique_key}`).removeClass(`f-act-bg-${this.unique_key}`);
        $(`.${previous}Tab${this.unique_key}`).addClass(`f-nav-prev-bg`);
    },
    backTab: function(page = undefined) {
        if (this.pages.navTabIndex <= 0) {
            return;
        }

        // console.log('index: ', this.pages.navTabIndex);

        var previous = this.pages.navTabs[this.pages.navTabIndex];
        this.pages.navTabIndex--;

        if (page == undefined) {
            var current = this.pages.navTabs[this.pages.navTabIndex];
            this.pages.navTab = current;
        } else if (page == 'registration') {
            var current = this.pages.navTabs[this.pages.navTabIndex];
            this.pages.navTabRegistration = current;
        }

        // console.log('index after decrement: ', this.pages.navTabIndex);
        // console.log('current: ', current);

        $(`.${current}Tab${this.unique_key}`).removeClass(`f-nav-prev-bg`);
        $(`.${current}Tab${this.unique_key}`).addClass(`f-act-bg-${this.unique_key}`);
        $(`.${previous}Tab${this.unique_key}`).removeClass(`f-act-bg-${this.unique_key}`);
    },

    changeMyBusinessTab: function(tab) {

        if (this.pages.navTab == 'details') {
            if (this.isEmpty(this.businesses.business.business_name)) {
                this.showMessage("Business Name is required.");
                return false;
            } else if (this.isEmpty(this.businesses.business.email)) {
                this.showMessage("Email Address is required.");
                return false;
            } else if (!this.isValidEmail(this.businesses.business.email)) {
                this.showMessage("Please enter a valid email.");
                return false;
            } else if (this.isEmpty(this.businesses.business.contact_number)) {
                this.showMessage("Contact Number is required.");
                return false;
            } else if (!this.isValidMobile(this.businesses.business.contact_number)) {
                this.showMessage("Please enter a valid contact number.");
                return false;
            }
        }

        this.pages.navTab = tab;
        const index = this.pages.navTabs.indexOf(tab);

        // reset current tab
        $(`.${tab}Tab${this.unique_key}`).addClass(`f-act-bg-${this.unique_key}`);
        $(`.${tab}Tab${this.unique_key}`).removeClass(`f-nav-prev-bg`);

        // mark previous tabs
        for (var i = 0; i < index; i++) {
            var navTab = this.pages.navTabs[i];
            $(`.${navTab}Tab${this.unique_key}`).removeClass(`f-act-bg-${this.unique_key}`);
            $(`.${navTab}Tab${this.unique_key}`).addClass(`f-nav-prev-bg`);
        }

        // mark next tabs
        for (var j = index + 1; j < this.pages.navTabs.length; j++) {
            var navTabNext = this.pages.navTabs[j];
            $(`.${navTabNext}Tab${this.unique_key}`).removeClass(`f-act-bg-${this.unique_key}`);
            $(`.${navTabNext}Tab${this.unique_key}`).removeClass(`f-nav-prev-bg`);
        }
    },

    searchMyListingsByName: function(business){
        var name = business.business_name.toLowerCase();
        var description = business.description.toLowerCase();
        var filter = this.myListings.mainFilter.toLowerCase();
        if (`${name}`.indexOf(filter) > -1 || `${description}`.indexOf(filter) > -1) {
            return true;
        }
        return false;
    },
    changeMyListingsTab: function(tab) {
        this.pages.myListingsTab = tab;
        $(`#${tab}MyListingsTab${this.unique_key}`).addClass(`f-act-bg-${this.unique_key}`);
        $(`#${tab}MyListingsTab${this.unique_key}`).siblings().removeClass(`f-act-bg-${this.unique_key}`);
    },
    displayListingLogo: function(logo, thumb) {
        if (logo.includes("https://fonq.mobi")) {
            return `background-image: url(${this.urls.thumb}${logo}${thumb})`;
        } else {
            return `background-image: url(${logo})`; 
        }
    },  
    selectLogoFromFileBusiness: function(){
        $("#businessLogo").click();
    },
    selectBusinessContact: function(number){
        _this = this;
        if (this.settings.mobile()) {
            hook = '';
            if (number == 'contact_number') {
                this.businesses.business.contact_number = '';
                hook = `fusion.componentsInstance.service_directory${this.unique_key}._this.decodelBusinessContactNumber`;
            } else if (number == 'office_number') {
                this.businesses.business.office_number = '';
                hook = `fusion.componentsInstance.service_directory${this.unique_key}._this.decodelBusinessOfficeNumber`;
            }
            CommunicationBridge.postMessage(JSON.stringify({
                request: 'phoneContact',
                payload: {},
                hook: hook
            }));
        }
    },
    decodelBusinessContactNumber: function(data){
        let decoded = decodeMobileNumber(data.phones[0].number);
        this.contact.flag = decoded.icon;
        this.businesses.business.country_code = decoded.code;
        this.businesses.business.contact_number = decoded.code + decoded.number;
    },
    decodelBusinessOfficeNumber: function(data){
        let decoded = decodeMobileNumber(data.phones[0].number);
        this.contact.flag = decoded.icon;
        this.businesses.business.country_code = decoded.code;
        this.businesses.business.office_number = decoded.code + decoded.number;
    },

    selectShareContactNumber: function(){
        _this = this;
        if (this.settings.mobile()) {
            this.shareContactNumber = '';            
            CommunicationBridge.postMessage(JSON.stringify({
                request: 'phoneContact',
                payload: {},
                hook: `fusion.componentsInstance.service_directory${this.unique_key}._this.decodelShareContactNumber`
            }));
        }
    },
    decodelShareContactNumber: function(data){
        let decoded = decodeMobileNumber(data.phones[0].number);
        this.contact.flag = decoded.icon;
        this.shareContactNumber = decoded.code + decoded.number;
    },
    shareBusiness: function(platform = 'whatsapp'){
        var full_name = this.user.name + ' ' + this.user.surname;
        var business = this.businesses.business.business_name;
        var contact = this.businesses.business.contact_number;
        var copy = `
${full_name} is referring ${business} business to you. Contact business: ${contact}. Supplied by the ${this.settings.community_name} trusted business directory.
        `;
        if (platform == 'whatsapp') {
            this.openWhatsAppApp(undefined, copy);
        } else if (platform == 'sms') {
            if (this.settings.mobile()) {
                this.sendSMS(undefined, copy);
            } else {
                this.showMessage("Sending SMS not allowed on Web.");
            }
        }
    },

    /**
     * Pages
     */
    showMyBusinessesPage: function() {
        this.pages.depth++;
        this.getMyBusinesses();
        this.openPage('businesses');
    },
    closeMyBusinessesPage: function() {
        this.pages.depth--;
        this.closePage('businesses');
    },

    showBusinessAnalyticsPage: function(){
        _this = this;
        // this.getBusinessAnalytics(this.businesses.business);
        this.pages.depth++;
        this.openPage('businessAnalytics');
    },
    closeBusinessAnalyticsPage: function(){
        this.pages.depth--;
        this.closePage('businessAnalytics');
    },

    showBusinessPage: async function (business) {
        _this = this;
        this.businesses.business = business;

        this.getComments(business);
        // this.startAnalyticsSession(business);
        // if (!this.isContentCreator(business)) {
        //     this.startAnalyticsSession(business);
        // }
        // if (this.businesses.business.referral) {
        //     this.showBusinessReferralPage(this.businesses.business);
        // } else {
            this.pages.depth++;
            this.openPage('business');
        // }
    },
    closeBusinessPage: function () {
        this.pages.depth--;
        // this.endAnalyticsSession(this.businesses.business);
        // if (!this.isContentCreator(this.businesses.business)) {
        //     this.endAnalyticsSession(this.businesses.business);
        // }
        this.closePage('business');
    },

    showBusinessReferralPage: async function(business){
        this.pages.depth++;
        this.openPage('businessReferral');
    },
    closeBusinessReferralPage: function(){
        this.pages.depth--;
        this.closePage('businessReferral');
    },

    showEditBusinessPage: function() {
        this.pages.depth++;
        // this.pages.navTab = "details";
        // this.pages.editBusinessTab = "details";
        this.openPage('editBusiness');
    },
    closeEditBusinessPage: function() {
        this.pages.depth--;
        // this.pages.navTab = "details";
        // this.changeMyBusinessTab('details');
        this.closePage('editBusiness');
    },

    showEditBusinessListingPage: function(){
        this.pages.depth++;
        // this.pages.navTab = "details";
        this.openPage('editBusinessListing');
    },
    closeEditBusinessListingPage: function(){
        this.pages.depth--;
        // this.pages.navTab = "details";
        this.closePage('editBusinessListing');
    },

    showRecommend: function () { 
        this.pages.depth++;
        this.openPage('recommend');
    },
    closeRecommend: function () {
        this.pages.depth--;
        this.closePage('recommend');
    },

    showContactInfo: function () {
        this.pages.depth++;
        this.openPage('contact');
    },
    closeContactInfo: function () {
        this.pages.depth--;
        this.closePage('contact');
    },

    showTraderContact: function () {
        this.pages.depth++;
        this.openPage('traderContact');
    },
    closeTraderContact: function () {
        this.pages.depth--;
        this.closePage('traderContact');
    },

    showShareBusinessListing: function () {
        this.pages.depth++;
        this.openPage('shareBusinessListing');
    },
    closeShareBusinessListing: function () {
        this.pages.depth--;
        this.closePage('shareBusinessListing');
    },
    

    showBusinessPaymentPage: function(){
        this.pages.depth++;
        this.openPage('businessPayment');
    },
    closeBusinessPaymentPage: function(){
        this.pages.depth--;
        this.closePage('businessPayment');
    },

    openPayFastComponent: async function(amount) {
        _this = this;
        this.showLoader("Generating payment uuid...");
        const response = await $.post(this.urls.main + "payments/generatePayFastIdentifier", {
            user_key_id: this.user_key.id,
            unique_key_id: this.unique_key_id,
            business_id: this.businesses.business.id,
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
            var componentObj = `fpay_v1_${this.community_id}2512`;
            var capp = "service_directory" + this.unique_key;
            fusion.getComponent({
                name: componentObj,
                uri: `/modules/module_dev/payments/v1/index.php?component=true&capp=${capp}&uuid=${uuid}&app=listings`,
                communityId: this.community_id,
                contentId: contentId,
                params: { refresh: true }
            }, function() {
                $(`.fusion-body`).addClass('fusion-overlay');
                $(`.components, .${componentObj}`).show();
            });
        }
    },

    openRegistrationPage: function() {
        _this = this;
        var params = `registration=true`;
        fusion.getComponent({
            name: `businessdirectory_${_this.community_id}2443`,
            uri: "/modules/module_dev/service_directory/v2/service_directory_module.php?" + params,
            communityId: _this.community_id,
            contentId: "2443",
            params: { refresh: false }
        }, function() {
            $(`.fusion-body`).addClass('fusion-overlay');
            $(`.components, .businessdirectory_${_this.community_id}2443`).show();
        });
    }, 

    openMySpecialsModule: function(){
        _this = this;
        var contentId = "";
        var business_name = this.businesses.business.business_name;
        fusion.getComponent({
            name: `base44_stage${_this.community_id}${contentId}`,
            uri: "/modules/base44_stage/index.php?base44Src=https://local-onq-pulse.base44.app&business=" + business_name,
            communityId: _this.community_id,
            contentId: contentId,
            params: { refresh: false }
        }, function() {
            $(`.fusion-body`).addClass('fusion-overlay');
            $(`.components, .base44_stage${_this.community_id}${contentId}`).show();
        });
    },
    
    callBusiness: function(business){
        // this.logImpression('call_listing', business);
        this.openDialerApp(business.contact_number)
    },
    whatsAppBusiness: function(business){
        // this.logImpression('whatsapp_listing', business);
        this.openWhatsAppApp(business.whatsapp);
    },
    emailBusiness: function(business){
        // this.logImpression('email_listing', business);
        this.openEmailApp(business.email);
    },
    openBusinessUrl: function(business, media) {
        if (media == 'google_url') { this.openUrl(business.google_url); }
        if (media == 'website_url') { this.openUrl(business.website_url); }
        if (media == 'facebook_url') { this.openUrl(business.facebook_url); }
        if (media == 'x_url') { this.openUrl(business.x_url); }
        if (media == 'instagram_url') { this.openUrl(business.instagram_url); }
    },
    testBusinessUrl: function(media) {
        var url = '';
        if (media == 'google_url') { url = this.businesses.business.google_url; }
        else if (media == 'website_url') { url = this.businesses.business.website_url; }
        else if (media == 'facebook_url') { url = this.businesses.business.facebook_url; }
        else if (media == 'x_url') { url = this.businesses.business.x_url; }
        else if (media == 'instagram_url') { url = this.businesses.business.instagram_url; }

        if(!this.isEmpty(url)) {
            url = this.normalizeUrl(url);
            if (this.isValidUrl(url)) {
                processContent({ contentType: "url", url: url });
            } else {
                this.showMessage(`Invalid URL format.`);
            }
        } else {
            this.showMessage(`URL cannot be blank!`);
        }
    }

});


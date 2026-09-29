

var utilitiesMethods = Object.freeze({

    exit: function() {
        _this = this;
        if (!$(".nav").is(":visible")) {
            $(".nav").show();
        }
        this.aGetAllOnMounted(false);
        fusion.closeComponent(function() {});
    },
    showMessage: function(message, buttons) {
        showMessage(message, buttons);
    },
    openPage: function (page) {
        if (s_orientation == "portrait" && this.pages.depth == 1) {
            if ($(".nav").is(":visible")) {
                $(".nav").hide();
            }
        }
        this.pages[page] = true;
    },
    closePage: function (page) {
        if (this.pages.depth == 0 && !this.screen.isFullScreen) {
            if (!$(".nav").is(":visible")) {
                $(".nav").show();
            }
        }
        this.pages[page] = false;
    },
    showLoader: function(message) {
        this.timer = setInterval(function() {
            if ($(`.progress`).text().length < 3) {
                $(`.progress`).append('.');
            } else {
                $(`.progress`).empty();
            }
        }, 500);
        this.showMessage(`${message}<span class="progress"></span>`, [])
    },
    hideLoader: function() {
        clearInterval(this.timer);
        $(`.message-popup`).hide();
    },
    scrollToTop: function () {
        $(`.main-contents-${this.unique_key}`).scrollTop(0);
        setTimeout(function(){ $(`.main-contents-${this.unique_key}`).scrollTop(0); }, 500);
    },
    scrollToBottom: function () {
        $(`.main-contents-${this.unique_key}`).scrollTop($(window).height() * 500);
    },
    isEmpty: function (value) {
        if (value == undefined) {
            return false;
        }
        if (value.length == 0 || value == "") {
            return true;
        }
        return false;
    },
    isContentCreator: function (content) {
        return this.settings.user_key_id == content.user_key_id;
    },
    memberPhoto: function (picture, thumb) {
        if (picture != "") {
            // if (this.channel == "practice") {
            //     picture = picture.replace("..", "");
            //     picture = host + "/modules" + picture;
            // }
            return `background-image: url(${this.urls.thumb}${picture}${thumb})`;
        }
        return "";
    },
    isValidUrl: function(url){
        var urlPattern = new RegExp(
            '^(https?:\\/\\/)?' + // validate protocol
            '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // validate domain name
            '((\\d{1,3}\\.){3}\\d{1,3}))'+ // validate OR ip (v4) address
            '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // validate port and path
            '(\\?[;&a-z\\d%_.~+=-]*)?'+ // validate query string
            '(\\#[-a-z\\d_]*)?$', 'i' // validate fragment locator
        ); 
        return !!urlPattern.test(url);
    },
    isValidEmail: function(email){
        var emailExp = /^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i;
        if (!emailExp.test(email)) {
            return false;
        }
        return true;
    },
    isValidMobile: function(number) {
        return !isNaN(number);
        // var cellExp = /^\(?(\d{3})\)?[- ]?(\d{3})[- ]?(\d{4})$/;
        // if (!cellExp.test(number)) {
        //     return false;
        // }
        // return true;
    },
    isNumberOnWhatsApp: async function(number){
        const response = await $.post(this.urls.main + "businesses/checkWhatsAppNumber", {
            number: number
        }, 'json');
        var results = await response;
        return results.data;
    },
    openWhatsAppApp: function(mobile, message = "") {
        if (mobile == undefined) {
            var url = `https://wa.me/?text=${message}`;
        } else {
            let decoded = decodeMobileNumber(mobile);
            var url = `https://wa.me/27${decoded.number}?text=${message}`;
        }
        openWhatsApp(url);
    },
    openDialerApp: function(mobile) {
        fusion.openDialer(mobile);
    },
    openEmailApp: function (email) {
        f.triggerEmail(email, "", "");
    },
    sendSMS: function(mobile, message = ""){
        if (mobile == undefined) {
            openSMS("", message);
        } else {
            let decoded = decodeMobileNumber(mobile);
            openSMS(decoded.number, message);
        }
    },
    openUrl: function (url) {
        if(!this.isEmpty(url)) {
            url = this.normalizeUrl(url);
            processContent({ contentType: "url", url: url });
        }
    },
    showCallWhatsAppPopup: function (number) {
        this.pages.depth++;
        this.openPage('callWhatsAppPopup');
    },
    closeCallWhatsAppPopup: function () {
        this.pages.depth--;
        this.closePage('callWhatsAppPopup');
    },
    getRelativeTimestamp: function(timestamp) {
        const created = new Date(timestamp).getTime();
        const now = new Date().getTime();

        var difference = Math.abs((created - now) / 1000);
        difference = ~~difference

        let output = ``;
        if (difference < 60) {
            // Less than a minute has passed:
            var _str = difference == 1 ? 'second ago' : 'seconds ago';
            output = `${difference} ${_str}`;
        } else if (difference < 3600) {
            // Less than an hour has passed:
            var _time = Math.floor(difference / 60);
            _time = ~~_time;
            output = _time == 1 ? `${_time} minute ago` : `${_time} minutes ago`;
        } else if (difference < 86400) {
            // Less than a day has passed:
            // output = `${Math.floor(difference / 3600)} hours ago`;
            var _time = Math.floor(difference / 3600);
            _time = ~~_time;
            output = _time == 1 ? `${_time} hour ago` : `${_time} hours ago`;
        } else if (difference < 2620800) {
            // Less than a month has passed:
            // output = `${Math.floor(difference / 86400)} days ago`;
            var _time = Math.floor(difference / 86400);
            _time = ~~_time;
            output = _time == 1 ? `${_time} day ago` : `${_time} days ago`;
        } else if (difference < 31449600) {
            // Less than a year has passed:
            // output = `${Math.floor(difference / 2620800)} months ago`;
            var _time = Math.floor(difference / 2620800);
            _time = ~~_time;
            output = _time == 1 ? `${_time} month ago` : `${_time} months ago`;
        } else {
            // More than a year has passed:
            // output = `${Math.floor(difference / 31449600)} years ago`;
            var _time = Math.floor(difference / 31449600);
            _time = ~~_time;
            output = _time == 1 ? `${_time} year ago` : `${_time} years ago`;
        }
        return output;
    },
    formatDate: function (timestamp) {
        const today = new Date(timestamp);
        const month = today.toLocaleString('default', { month: 'long' });
        return `${today.getDate()} ${month}, ${today.getFullYear()}`;
    },
    formatTime: function (timestamp) {
        const today = new Date(timestamp);
        const minutes = today.getMinutes() <= 9 ? "0" + today.getMinutes() : today.getMinutes();
        return `${today.getHours()}:${minutes}:${today.getSeconds()}`;
    },
    formatDateTime: function (timestamp) {
        return this.formatDate(timestamp) + " @ " + this.formatTime(timestamp);
    },
    
    displayContactFlag: function(flag) {
        return `background-image: url(${host}/images/flags/${flag})`;
    },

    changeContactFlag: function(input, target){
        let decoded = decodeMobileNumber(input);
        $(target).css('background-image', `url(${host}/images/flags/${decoded.icon})` );
    },

    isContentCreator: function (content) {
        return this.settings.user_key_id == content.user_key_id;
    },

    replaceContentHost: function(content = "") {
        return content.replaceAll("**host**", host);
    },

    normalizeUrl: function (input, defaultScheme = 'https') {
        if (!input || typeof input !== 'string') return null;

        input = input.trim();

        if (!input) return null;

        // Add scheme if missing
        if (!/^https?:\/\//i.test(input)) {
            input = `${defaultScheme}://${input}`;
        }

        try {
            const url = new URL(input);
            return url.href;
        } catch {
            return null; // Invalid URL
        }
    },

    isValidUrl: function(string) {
        try {
            new URL(string);
            return true;
        } catch (err) {
            return false;
        }
    },

    selectContactNumber: function(callback = '') {
        _this = this;
        if (this.isMobile) {
            CommunicationBridge.postMessage(JSON.stringify({
                request: 'phoneContact',
                payload: {},
                hook: `fusion.componentsInstance.recruitment_directory${this.settings.unique_key}._this.${callback}`
            }));
        }
    },

    calculateAge: function(dob) {
        if (!this.isEmpty(dob)) {
            const today = new Date();
            const birthDate = new Date(dob);
            
            // 1. Calculate the initial age difference by year
            let age = today.getFullYear() - birthDate.getFullYear();
            
            // 2. Check if the birthday has passed this year
            const monthDifference = today.getMonth() - birthDate.getMonth();
            const dayDifference = today.getDate() - birthDate.getDate();
            
            // 3. Deduct 1 year if the birthday hasn't happened yet
            if (monthDifference < 0 || (monthDifference === 0 && dayDifference < 0)) {
                age--;
            }
            
            return age;
        }
        return "";
    },

    setDateModelOnChange: function() {
        let action = this.newVoucher.action;
        let end_date = $(`#${action}EndDateValue${this.settings.unique_key}`).val();
        let start_date = $(`#${action}StartDateValue${this.settings.unique_key}`).val();
        this.newVoucher.end_date = end_date;
        this.newVoucher.start_date = start_date;
        this.upgradeOptions.start_date = start_date;
    },
    setDateInputOnChange: function(){
        let action = this.settings.action;
        if (action === 'add') {
            $(`#${action}EndDateValue${this.settings.unique_key}`).val(this.newVoucher.end_date);
        } else if (action === 'edit') {
            $(`#${action}StartDateValue${this.settings.unique_key}`).val(this.newVoucher.end_date);
            $(`#${action}EndDateValue${this.settings.unique_key}`).val(this.getVoucher.end_date);
        }
    },

});
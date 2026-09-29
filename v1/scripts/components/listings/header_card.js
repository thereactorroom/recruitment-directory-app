if(!Object.keys(Vue.options.components).includes('listing-header-card')) {
    Vue.component('listing-header-card', {
        template: `
            <div class="f-body-contents-full">
                <div class="listing-listing f-fc f-bb-0">
                    <div class="listing-listing-header f-mt-3 f-ml-3">

                        <div class="f-list-item-main-right-byron" 
                            :style="listingLogo(listing.logo, '&w=360&q=100&zc=6')"
                            style="
                                width: calc(calc(290/750) * var(--seg_width));
                                height: calc(calc(250/750) * var(--seg_width));
                                background-size: cover !important;
                                border-radius: calc(calc(20/750) * var(--seg_width));
                            ">
                        </div>

                        <div class="f-list-item-middle f-df f-fc">
                            <div class="listing-listing-header-txt f-ml-3 f-mt-3" 
                                style="
                                    font-size: calc(calc(35/750) * var(--seg_width));
                                    width: calc(calc(370/750) * var(--seg_width)) !important;
                                ">
                                {{ listing.name }}
                            </div>

                            <div class="listing-listing-body-txt f-ml-3 f-mt-1"
                                style="
                                    font-size: calc(calc(24/750) * var(--seg_width));
                                    width: calc(calc(370/750) * var(--seg_width)) !important;
                                ">
                                Age: {{ listing.age }}
                            </div>

                            <div class="listing-listing-body-txt f-ml-3 f-mt-1"
                                style="
                                    font-size: calc(calc(24/750) * var(--seg_width));
                                    width: calc(calc(370/750) * var(--seg_width)) !important;
                                ">
                                Gender: {{ listing.gender }}
                            </div>

                            <div class="listing-listing-header f-ml-3 f-mt-2" style="width: calc(calc(370/750) * var(--seg_width)) !important;">
                                <div class="f-list-item-main-right-byron-no-iamge"
                                    style="
                                        width: calc(calc(170/750) * var(--seg_width));
                                        background-color: #ffffff !important;
                                    ">

                                    <div v-if="!isFavorite()" class="f-action-btn" @click="addFavorite()" 
                                        style="
                                            border: #fd4985 1px solid;
                                            background-color: #ffffff !important;
                                            height: calc(calc(80/750) * var(--seg_width));
                                        ">
                                        <div class="f-link-btn-icon f-add-fav-heart-icon"
                                            style="
                                                width: calc(calc(65/750)* var(--seg_width));
                                                height: calc(calc(65/750)* var(--seg_width));
                                            ">
                                        </div>
                                    </div>
                                    <div v-else class="f-action-btn" @click="deleteFavorite()" 
                                        style="
                                            border: #fd4985 1px solid;
                                            background-color: #ffffff !important;
                                            height: calc(calc(80/750) * var(--seg_width));
                                        ">
                                        <div class="f-link-btn-icon f-remove-fav-heart-icon"
                                            style="
                                                width: calc(calc(65/750)* var(--seg_width));
                                                height: calc(calc(65/750)* var(--seg_width));
                                            "></div>
                                    </div>

                                </div>

                                <div class="listing-listing-header-nav-holder f-mt-1" v-show="listing.paid"
                                    style="width: calc(calc(80/750) * var(--seg_width)) !important;">

                                    <div class="listing-listing-header-txt-icon f-verified-icon f-mt-1 f-mr-1"
                                        style="background-size: calc(calc(40/750) * var(--seg_width));"></div>
                                    
                                </div>

                                <div class="listing-listing-header-nav-holder f-fc f-mt-1"
                                    style="width: calc(calc(120/750) * var(--seg_width)) !important;">

                                    <div class="f-mt-1" style="
                                            float: right;
                                            display: flex;
                                            justify-content: right;
                                            width: calc(calc(120/750) * var(--seg_width)) !important;
                                            height: calc(calc(55/750) * var(--seg_width));
                                            line-height: calc(calc(24/750) * var(--seg_width));
                                            font-size: calc(calc(24/750) * var(--seg_width));
                                        ">
                                        
                                        <div v-if="listing.stars_avg == 0" 
                                            class="listing-listing-header-txt-icon f-grey-star-icon f-mr-1" 
                                            style="
                                                margin-top: calc(calc(-10/750) * var(--seg_width)) !important;
                                                background-size: calc(calc(40/750) * var(--seg_width));
                                            "></div>
                                        <div v-else class="listing-listing-header-txt-icon f-gold-star-icon f-mr-1" 
                                            style="
                                                margin-top: calc(calc(-10/750) * var(--seg_width)) !important;
                                                background-size: calc(calc(40/750) * var(--seg_width));
                                            "></div>
                                        <div style="
                                                display: flex; 
                                                width: max-content;
                                            ">{{ listing.stars_avg }}</div>
                                    </div>

                                    <div style="
                                            float: right;
                                            display: flex;
                                            justify-content: right;
                                            width: calc(calc(120/750) * var(--seg_width)) !important;
                                            height: calc(calc(55/750) * var(--seg_width));
                                            line-height: calc(calc(30/750) * var(--seg_width));
                                            font-size: calc(calc(20/750) * var(--seg_width));
                                        ">
                                        <div style="
                                                display: flex; 
                                                width: max-content;
                                            ">{{ listing.comments }} Ratings</div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

                <div class="listing-listing f-fc f-bb-1">
                    <div class="listing-listing-header f-mt-2 f-mb-2 f-ml-3">
                        <div v-if="listing.downgraded" class="listing-listing-body-txt">
                            {{ listing.description.substring(0, 90) }}
                        </div>
                        <div v-else class="listing-listing-body-txt" v-html="listing.description"></div>
                    </div>
                </div>

                <div v-if="isContentCreator()" class="listing-listing f-fc f-bb-1">
                    <div class="listing-listing-header f-fc f-mt-2 f-mb-2 f-ml-3"
                        style="font-size: calc(calc(25/750) * var(--seg_width));"
                        >
                        <div class="listing-listing-body-txt">
                            <b>Status:</b> &nbsp; {{ listing.status }} 
                            <div class="listing-status-dot f-ml-1" 
                                :class="{ 
                                    'listing-status-draft': listing.status == 'draft',
                                    'listing-status-waiting': listing.status == 'waiting approval',
                                    'listing-status-active': listing.status == 'approved',
                                    'listing-status-rejected': listing.status == 'rejected',
                                }"></div>
                        </div>
                        <div v-if="!isEmpty(listing.status_comment)" class="listing-listing-body-txt">
                            <b>Comment:</b> &nbsp; {{ listing.status_comment }}
                        </div>
                        <div class="listing-listing-body-txt">
                            <b>Subscription:</b> &nbsp; {{ listing.subscription_status }}
                        </div>
                        <div v-if="listing.downgraded" class="listing-listing-body-txt">
                            <b>Downgraded:</b> &nbsp; at {{ listing.date_downgraded }}
                        </div>
                    </div>
                </div>
            </div>
        `,
        props: [
            'listing', 
            'urls',
            'stats',
            'settings',
            'favorites'
        ],
        mounted: function() {
            // console.log('isContentCreator', this.isContentCreator());
            console.log(this.favorites);
        },
        methods: {
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
            isContentCreator: function () {
                return this.settings.user_key_id == this.listing.user_key_id;
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
            showMessage: function(message, buttons) {
                showMessage(message, buttons);
            },
            showLoader: function(message) {
                this.timer = setInterval(function() {
                    if ($(`.progress`).text().length < 3) {
                        $(`.progress`).append('.');
                    } else {
                        $(`.progress`).empty();
                    }
                }, 500)
                this.showMessage(`${message}<span class="progress"></span>`, [])
            },
            hideLoader: function() {
                clearInterval(this.timer)
                $(`.message-popup`).hide()
            },

            
            addFavorite: function () {
                _this = this;
                this.showLoader("Adding favorite");
                $.post(this.urls.main + "favorites/insert", {
                    user_key_id: this.settings.user_key_id,
                    unique_key_id: this.settings.unique_key_id,
                    listing_id: this.listing.id
                }, function (response) {
                    // _this.logImpression('un_favoring_listing', _this.businesses.business);
                    if (response.status == true) {
                        // _this.getFavorites();
                        _this.hideLoader();
                        _this.showMessage(response.message, [{
                            text: "Ok",
                            onTap: function () { }
                        }]);
                    } else {
                        _this.hideLoader();
                        _this.showMessage(response.message);
                    }
                }, 'json')
                .fail(function (response) {
                    _this.showMessage(response.responseText);
                });
            },

            deleteFavorite: function () {
                _this = this;
                this.showLoader("Removing favorite");
                $.post(this.urls.main + "favorites/delete", {
                    user_key_id: this.settings.user_key_id,
                    unique_key_id: this.settings.unique_key_id,
                    listing_id: this.listing.id
                }, function (response) {
                    // _this.logImpression('un_favoring_listing', _this.businesses.business);
                    if (response.status == true) {
                        // _this.getFavorites();
                        _this.hideLoader();
                        _this.showMessage(response.message, [{
                            text: "Ok",
                            onTap: function () { }
                        }]);
                    } else {
                        _this.hideLoader();
                        _this.showMessage(response.message);
                    }
                }, 'json')
                .fail(function (response) {
                    _this.showMessage(response.responseText);
                });
            },

            isFavorite: function () {
                for (var i = 0; i < this.favorites.length; i++) {
                    if (this.favorites[i].listing_id == this.listing.id) {
                        return true;
                    }
                }
                return false;
            },
        }
    });
}

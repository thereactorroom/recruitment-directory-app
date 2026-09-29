
if(!Object.keys(Vue.options.components).includes('listing-paid-card')) {
    Vue.component('listing-paid-card', {
        template: `
            <div class="f-list-item-wrap f-ml-3 f-mt-1 f-mb-1">
                <div class="f-list-item-side-2x" style="
                    width: calc(calc(160/750) * var(--seg_width)) !important;
                ">
                    <div class="listing-listing-header-icon" 
                        style="
                            background-size: cover !important;
                            border-radius: calc(calc(20/750) * var(--seg_width));
                            width: calc(calc(160/750) * var(--seg_width)) !important;
                            height: calc(calc(180/750) * var(--seg_width)) !important;
                        "
                        :style="listingLogo(listing.logo, '&w=360&q=100&zc=6')"
                    ></div>
                </div>
                <div class="f-list-item-left-2x f-fc" style="">
                    <div class="listing-listing-header f-ml-3" style="
                        width: calc(calc(530/750) * var(--seg_width)) !important;">
                        
                        <div class="listing-listing-header-txt-holder" style="width: calc(calc(300/750) * var(--seg_width)) !important;">
                            <div class="listing-listing-header-txt f-mt-1" style="font-size: calc(calc(30/750) * var(--seg_width));">
                                {{ listing.display_name }}
                            </div>
                        </div>

                        <div class="listing-listing-header-nav-holder"
                            style="width: calc(calc(80/750) * var(--seg_width)) !important;">

                            <div class="listing-listing-header-txt-icon f-verified-icon f-mt-1 f-mr-1" v-if="listing.paid"
                                style="background-size: calc(calc(40/750) * var(--seg_width));"></div>
                            <div class="listing-listing-header-txt-icon f-fav-heart-icon f-mt-1 f-mr-1" v-if="isFavorite(listing)" 
                                style="background-size: calc(calc(35/750) * var(--seg_width));"></div>

                        </div>

                        <div class="listing-listing-header-nav-holder f-fc"
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

                    <div class="listing-listing-body f-ml-3 f-fc" style="width: calc(calc(530/750) * var(--seg_width));">
                        <div class="listing-listing-body-txt">
                            <b>Location: </b>{{ listing.location }}
                        </div>
                        <div class="listing-listing-body-txt f-mt-1">
                            {{ listing.description.substring(0, 140) }}
                        </div>
                        <div v-show="stats" class="listing-listing-body-txt f-mt-1">
                            Status: {{ listing.status }} <br/>
                            Subscription: {{ listing.subscription_status }}
                        </div>
                    </div>

                </div>
            </div>
        `,
        props: [
            'listing', 
            'urls',
            'stats',
            'favorites'
        ],
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
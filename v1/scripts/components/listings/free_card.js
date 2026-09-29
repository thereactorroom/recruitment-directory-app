if(!Object.keys(Vue.options.components).includes('listing-free-card')) {
    Vue.component('listing-free-card', {
        template: `
            <div class="f-list-item-wrap f-ml-3 f-mt-1 f-mb-1">
                <div class="f-list-item-full f-fc">
                    <div class="listing-listing-header listing-listing-recommended" style="
                        width: calc(calc(690/750) * var(--seg_width)) !important;">
                        
                        <div class="listing-listing-header-txt-holder f-fc" style="width: calc(calc(520/750) * var(--seg_width));">
                            <div class="listing-listing-header-txt f-mt-1" style="font-size: calc(calc(30/750) * var(--seg_width));">
                                {{ listing.display_name }}
                            </div>
                        </div>

                        <div class="listing-listing-header-nav-holder listing-listing-recommended"
                            style="width: calc(calc(80/750) * var(--seg_width)) !important;">

                            <div class="listing-listing-header-txt-icon f-verified-icon f-mt-1 f-mr-1" v-if="listing.paid"
                                style="background-size: calc(calc(40/750) * var(--seg_width));"></div>
                            <div class="listing-listing-header-txt-icon f-fav-heart-icon f-mt-1 f-mr-1" v-if="isFavorite(listing)" 
                                style="background-size: calc(calc(35/750) * var(--seg_width));"></div>

                        </div>

                        <div class="listing-listing-header-nav-holder listing-listing-recommended f-fc"
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
                    <div class="listing-listing-body listing-listing-recommended f-fc">
                        <div class="listing-listing-body-txt">
                            <b>Location: </b>{{ listing.location }}
                        </div>
                        <div class="listing-listing-body-txt f-mt-1">
                            {{ listing.description.substring(0, 140) }}
                        </div>
                    </div>
                </div>
            </div>
        `,
        props: [
            'listing', 
            'favorites'
        ],
        methods: {
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
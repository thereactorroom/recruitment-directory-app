

<div class="page f-page" :style="{ display: pages.businesses ? 'flex': 'none' }" >
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$unique_key?> f-fc f-mt-3">
            <div class="f-header-text-full f-ml-3">
                My Business Listings ({{getMyListingsList.length}})
            </div>
        </div>
        <div class="fusion-description f-mt-2">
            Your existing listings are shown below. Click to view or edit, or select New Listing to create one.
        </div>
    </div>

    <div class="f-body">

        <div class="f-body-contents-search-<?=$unique_key?> f-fr">
            <div class="f-input-wrap-byron f-mt-3 f-ml-3 f-mb-3 f-border-bottom">
                <input type="text" class="f-input f-input-left-search f-pl-2"
                        v-model="myListings.mainFilter" placeholder="Search listing by keyword." >
                <div class="f-action-lnk-<?=$unique_key?> f-action-lnk-search" @click="myListings.mainFilter = ''">clear</div>
            </div>
            <div v-if="pages.sort" class="f-icon-icon f-search-left-icon f-sort-dec-icon f-mt-3" @click="showSort()"
                style="
                    margin-top: calc(calc(36/750) * var(--seg_width)) !important;
                    background-position: right;
                "></div>
            <div v-else class="f-icon-icon f-search-left-icon f-sort-asc-icon f-mt-3" @click="showSort()"
                style="
                    margin-top: calc(calc(36/750) * var(--seg_width)) !important;
                    background-position: right;
                "></div>
        </div>

        <div class="f-body-contents-search-<?=$unique_key?> f-fr f-mt-1"
            :style="{ display: (pages.sort) ? 'flex': 'none' }">

            <div v-if="businesses.sort.dateAscending" class="f-button-seg3 date-sort sort-item-background" @click="applySortMyListings('date')">
                <div class="f-button-seg-icon f-sort-date-jan-dec-icon"></div>
                <div class="f-button-text">Date</div>
            </div>
            <div v-else class="f-button-seg3 date-sort sort-item-background" @click="applySortMyListings('date')">
                <div class="f-button-seg-icon f-sort-date-dec-jan-icon"></div>
                <div class="f-button-text">Date</div>
            </div>

            <div v-if="businesses.sort.nameAscending" class="f-button-seg3 name-sort" @click="applySortMyListings('name')">
                <div class="f-button-seg-icon f-sort-za-icon"></div>
                <div class="f-button-text">Name</div>
            </div>
            <div v-else class="f-button-seg3 name-sort" @click="applySortMyListings('name')">
                <div class="f-button-seg-icon f-sort-az-icon"></div>
                <div class="f-button-text">Name</div>
            </div>

            <div v-if="businesses.sort.ratingAscending" class="f-button-seg3 rating-sort" @click="applySortMyListings('rating')">
                <div class="f-button-seg-icon f-star-rating-dec-icon"></div>
                <div class="f-button-text">Rating</div>
            </div>
            <div v-else class="f-button-seg3 rating-sort" @click="applySortMyListings('rating')">
                <div class="f-button-seg-icon f-star-rating-asc-icon "></div>
                <div class="f-button-text">Rating</div>
            </div>
        </div>

        <div class="f-body-contents-full f-mb-3 f-scroll main-contents-<?=$unique_key?>"
            style="height: calc(var(--s_height) - calc(calc(520/750) * var(--seg_width))) !important;">

            <div v-if="getMyListingsList.length == 0 && loading.myListings" 
                class="f-list-item" style="border-bottom: 0px;">
                <div class="f-list-item-wrap f-mt-3 f-ml-3">
                    <div class="loader-<?=$unique_key?>"></div> 
                    <div class="loader-text">Loading ...</div>
                </div>
            </div>

            <div v-else-if="getMyListingsList.length == 0" 
                class="f-list-item" style="border-bottom: 0px;">
                <div class="f-list-item-wrap f-mt-3 f-ml-3 f-fc">
                    You currently have no listings. To Create a paid Listing, select "New Listing" in the bottom Menu. &#128515;
                    <p>
                        By financially supporting the community, you can list your business in this community directory, 
                        allowing at the touch of a button citizens to:
                    </p>
                    <ul>
                        <li>Know about your services and products.</li>
                        <li>Call you or WhatsApp you for free.</li>
                        <li>Connect to your Google business listing.</li>
                        <li>Connect to your social media channels.</li>
                        <li>Rate and review your services.</li>
                    </ul>
                    <p>All accessible from their mobile phones!<br/>Embed your business into the community now.</p>
                </div>
            </div>

            <div v-else v-for="(business, index) in getMyListingsList" :key="index" 
                class="listing-listing f-fc" 
                :class="{
                    'listing-listing-recommended': business.referral
                }"
                v-show="searchMyListingsByName(business)"
                @click="showBusinessPage(business)">

                <div class="listing-listing-header f-mt-3 f-ml-3"
                    :class="{
                        'listing-listing-recommended': business.referral
                    }">

                    <div v-if="business.referral" class="listing-listing-header-icon-recommended"
                        :style="businessLogo('<?=$envHost?><?=$community_icon?>')" 
                        ></div>
                    <div v-else-if="isEmpty(business.logo)" class="listing-listing-header-icon-no-iamge"
                        >{{businessNoLogo(business)}}</div>
                    <div v-else class="listing-listing-header-icon" 
                        :style="businessLogo(business.logo, '&w=360&q=100&zc=6')"
                        ></div>

                    <div v-if="business.referral" class="listing-listing-header-txt-holder f-fc">
                        <div class="listing-listing-header-txt f-ml-2 f-db"
                            :class="{'listing-listing-header-txt-mt': business.business_name.length <= 27}"
                            style="font-size: calc(calc(30/750) * var(--seg_width));">
                            {{ business.display_name }}
                        </div>
                    </div>
                    <div v-else class="listing-listing-header-txt-holder f-fc">
                        <div class="listing-listing-header-txt f-ml-2"
                            :class="{'listing-listing-header-txt-mt': business.business_name.length <= 27}"
                            style="font-size: calc(calc(30/750) * var(--seg_width));">
                            {{ business.display_name }}
                        </div>
                    </div>

                    <div class="listing-listing-header-nav-holder" :class="{'listing-listing-recommended': business.referral}"
                        style="width: calc(calc(80/750) * var(--seg_width)) !important;">

                        <div class="listing-listing-header-txt-icon f-verified-icon f-mt-1 f-mr-1" v-if="business.paid"
                            style="background-size: calc(calc(40/750) * var(--seg_width));"></div>
                        <div class="listing-listing-header-txt-icon f-fav-heart-icon f-mt-1 f-mr-1" v-if="isFavorite(business)" 
                            style="background-size: calc(calc(35/750) * var(--seg_width));"></div>

                    </div>

                    <div class="listing-listing-header-nav-holder f-fc" :class="{'listing-listing-recommended': business.referral}"
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
                            <div v-if="business.stars_avg == 0" 
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
                                ">{{ business.stars_avg }}</div>
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
                                ">{{ business.comments }} Ratings</div>
                        </div>
                        
                        <!-- <div class="listing-listing-header-txt-icon f-verified-icon f-mt-1 f-mr-1" v-if="business.paid"
                            style="background-size: calc(calc(40/750) * var(--seg_width));"></div>
                        <div class="listing-listing-header-txt-icon f-fav-heart-icon f-mt-1 f-mr-1" v-if="isFavorite(business)" 
                            style="background-size: calc(calc(35/750) * var(--seg_width));"></div>

                        <div class="listing-listing-header-txt-icon f-color-grey f-mt-1 f-mr1" v-if="business.stars_avg == 0"
                            style="
                                width: max-content;
                                height: calc(calc(55/750) * var(--seg_width));
                                line-height: calc(calc(30/750) * var(--seg_width));
                                font-size: calc(calc(29/750) * var(--seg_width));
                                padding-right: calc(calc(8/750) * var(--seg_width));
                            ">-</div>
                        <div class="listing-listing-header-txt-icon f-grey-star-icon f-mr-1" v-if="business.stars_avg == 0"
                            style="
                                float: left;
                                background-repeat: no-repeat;
                                width: calc(calc(40/750) * var(--seg_width));
                                height: calc(calc(50/750) * var(--seg_width));
                                background-size: calc(calc(40/750) * var(--seg_width));
                                background-position: calc(calc(0/750) * var(--seg_width)) calc(calc(7/750) * var(--seg_width));
                            "></div>

                        <div class="listing-listing-header-txt-icon f-color-grey f-mt-1 f-mr1" v-if="business.stars_avg > 0"
                            style="
                                width: max-content;
                                height: calc(calc(55/750) * var(--seg_width));
                                line-height: calc(calc(30/750) * var(--seg_width));
                                font-size: calc(calc(29/750) * var(--seg_width));
                                padding-right: calc(calc(8/750) * var(--seg_width));
                            ">{{business.stars_avg}}</div>
                        <div class="listing-listing-header-txt-icon f-gold-star-icon f-mr-1" v-if="business.stars_avg > 0"
                            style="
                                float: left;
                                background-repeat: no-repeat;
                                width: calc(calc(40/750) * var(--seg_width));
                                height: calc(calc(50/750) * var(--seg_width));
                                background-size: calc(calc(40/750) * var(--seg_width));
                                background-position: calc(calc(0/750) * var(--seg_width)) calc(calc(7/750) * var(--seg_width));
                            "></div> -->
                    </div>

                </div>

                <div class="listing-listing-body f-mt-2 f-ml-3"
                    :class="{
                        'listing-listing-recommended': business.referral
                    }">
                    <div class="listing-listing-body-txt">
                        Status: {{ business.status }} <br/>
                        Subscription: {{ business.subscription_status }}
                    </div>
                </div>

                <div class="listing-listing-footer f-ml-3"></div>
            </div>

        </div>
    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$unique_key?>">
            <div class="f-button-seg2" @click="closeMyBusinessesPage()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div class="f-button-seg2 f-act-btn" @click="showLandingPage()">
                <div class="f-button-seg-icon f-add-icon"></div>
                <div class="f-button-text">New Listing</div>
            </div>
        </div>
    </div>

</div>


<div class="page f-page" :style="{ display: (pages.business) ? 'flex': 'none' }" >

    <div class="f-body-no-header">
        
        <div class="f-body-contents-full">

            <div class="listing-listing f-fc f-bb-0">
                <div class="listing-listing-header f-mt-3 f-ml-3">

                    <div v-if="businesses.business.referral" class="listing-listing-header-icon-recommended"
                        :style="businessLogo('<?=$envHost?><?=$community_icon?>')" 
                        ></div>
                    <div v-else-if="isEmpty(businesses.business.logo)" class="f-list-item-main-right-byron-no-iamge" 
                        >{{businessNoLogo(businesses.business)}}</div>
                    <div v-else class="f-list-item-main-right-byron" 
                        :style="businessLogo(businesses.business.logo, '&w=360&q=100&zc=6')"
                        style="
                            width: calc(calc(300/750) * var(--seg_width));
                            height: calc(calc(150/750) * var(--seg_width));
                        "></div>

                    <div class="f-list-item-middle f-df f-fc">
                        <div class="f-df f-fr f-pl-2 f-pr-2 f-mt-1">

                            <div class="listing-listing-header-txt-icon f-color-grey f-mt-1 f-mr1" v-if="businesses.business.stars_avg == 0"
                                style="
                                    line-height: calc(calc(30/750) * var(--seg_width));
                                    font-size: calc(calc(30/750) * var(--seg_width));
                                    padding-right: calc(calc(8/750) * var(--seg_width));
                                ">-</div>
                            <div class="listing-listing-header-txt-icon f-grey-star-icon f-mr-1" v-if="businesses.business.stars_avg == 0"
                                style="
                                    float: left;
                                    background-repeat: no-repeat;
                                    width: calc(calc(40/750) * var(--seg_width));
                                    height: calc(calc(50/750) * var(--seg_width));
                                    margin-top: calc(calc(-4/750) * var(--seg_width));
                                    background-size: calc(calc(45/750) * var(--seg_width));
                                "></div>

                            <div class="listing-listing-header-txt-icon f-color-grey f-mt-1 f-mr1" v-if="businesses.business.stars_avg > 0"
                                style="
                                    line-height: calc(calc(30/750) * var(--seg_width));
                                    font-size: calc(calc(30/750) * var(--seg_width));
                                    padding-right: calc(calc(8/750) * var(--seg_width));
                                ">{{businesses.business.stars_avg}}</div>
                            <div class="listing-listing-header-txt-icon f-gold-star-icon f-mr-1" v-if="businesses.business.stars_avg > 0"
                                style="
                                    float: left;
                                    background-repeat: no-repeat;
                                    width: calc(calc(40/750) * var(--seg_width));
                                    height: calc(calc(50/750) * var(--seg_width));
                                    margin-top: calc(calc(-4/750) * var(--seg_width));
                                    background-size: calc(calc(45/750) * var(--seg_width));
                                "></div>

                            <!-- <div v-if="businesses.business.paid" class="listing-listing-header-txt-icon-heart f-verified-icon" style="float: left;"></div>
                            <div v-if="isFavorite(businesses.business)" class="listing-listing-header-txt-icon f-fav-heart-icon" style="float: left;"></div> -->
                        </div>
                        <div class="f-df f-fr f-pl-2 f-pr-2">
                            <div class="listing-listing-header-txt-icon f-verified-icon f-mt-1 f-mr-1" v-if="businesses.business.paid"
                                style="
                                    background-size: calc(calc(42/750) * var(--seg_width));
                                    margin-top: calc(calc(-2/750) * var(--seg_width));"
                            ></div>
                            <div class="listing-listing-header-txt-icon f-fav-heart-icon f-mt-1 f-mr-1" v-if="isFavorite(businesses.business)" 
                                style="background-size: calc(calc(35/750) * var(--seg_width));"></div>

                            <!-- <div v-if="businesses.business.paid" class="listing-listing-header-txt-icon-heart f-verified-icon" style="float: left;"></div>
                            <div v-if="isFavorite(businesses.business)" class="listing-listing-header-txt-icon f-fav-heart-icon" style="float: left;"></div> -->
                        </div>
                    </div>

                    <div class="f-list-item-main-right-byron-no-iamge"
                        style="
                            width: calc(calc(220/750) * var(--seg_width));
                            background-color: #ffffff !important;
                        ">

                        <div v-if="!isFavorite(businesses.business)" class="f-action-btn f-mt-3" @click="addFavorite()"
                            style="
                                border: #fd4985 1px solid;
                                background-color: #ffffff !important;
                                height: calc(calc(80/750) * var(--seg_width));
                            ">
                            <div class="f-link-btn-icon f-add-fav-heart-icon"
                                style="
                                    width: calc(calc(65/750)* var(--seg_width));
                                    height: calc(calc(65/750)* var(--seg_width));
                                "></div>
                        </div>
                        <div v-else class="f-action-btn f-mt-3" @click="deleteFavorites()" 
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
                </div>
            </div>

            <div class="listing-listing f-fc f-bb-1">
                <div class="listing-listing-header f-mt-2 f-ml-3">
                    <div class="listing-listing-header-txt f-pb-2"
                        style="
                            font-size: calc(calc(30/ *750) * var(--seg_width));
                            width: calc(calc(690/750) var(--seg_width)) !important;
                            padding-bottom: calc(calc(80/750) * var(--seg_width)) !important;
                        ">
                        {{ businesses.business.business_name }}
                    </div>
                </div>
            </div>

            <div class="listing-listing f-fc f-bb-1">
                <div class="listing-listing-header f-mt-2 f-mb-2 f-ml-3">
                    <div v-if="businesses.business.downgraded" class="listing-listing-body-txt">
                        {{ businesses.business.description.substring(0, 90) }}
                    </div>
                    <div v-else class="listing-listing-body-txt" v-html="businesses.business.description"></div>
                </div>
            </div>

            <div v-if="isContentCreator(businesses.business)" class="listing-listing f-fc f-bb-1">
                <div class="listing-listing-header f-fc f-mt-2 f-mb-2 f-ml-3">
                    <div class="listing-listing-body-txt">
                        <b>Status:</b> &nbsp; {{ businesses.business.status }} 
                        <div class="listing-status-dot f-ml-1" 
                            :class="{ 
                                'listing-status-draft': businesses.business.status == 'Draft',
                                'listing-status-waiting': businesses.business.status == 'Waiting Approval',
                                'listing-status-active': businesses.business.status == 'Approved',
                                'listing-status-rejected': businesses.business.status == 'Rejected',
                            }"></div>
                    </div>
                    <div v-if="!isEmpty(businesses.business.status_comment)" class="listing-listing-body-txt">
                        <b>Comment:</b> &nbsp; {{ businesses.business.status_comment }}
                    </div>
                    <div class="listing-listing-body-txt">
                        <b>Subscription:</b> &nbsp; {{ businesses.business.subscription_status }}
                    </div>
                    <div v-if="businesses.business.downgraded" class="listing-listing-body-txt">
                        <b>Downgraded:</b> &nbsp; at {{ businesses.business.date_downgraded }}
                    </div>
                    <div v-if="businesses.business.weekly_views" class="listing-listing-body-txt">
                        <b>Weekly views:</b> &nbsp; {{ businesses.business.weekly_views }}
                    </div>
                    <div v-if="businesses.business.monthly_views" class="listing-listing-body-txt">
                        <b>Monthly views:</b> &nbsp; {{ businesses.business.monthly_views }}
                    </div>
                </div>
            </div>
        </div>

        <div class="f-link-btn-wrap f-scroll">
            <!-- <div v-if="isContentCreator(businesses.business)" class="f-link-btn-byron f-ml-3" 
                @click="showBusinessAnalyticsPage()">
                <div class="f-link-btn-icon f-listing-analytics-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Analytics</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div> -->
            <div class="f-link-btn-byron f-ml-3" @click="callBusiness(businesses.business)">
                <div class="f-link-btn-icon f-contact-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Call</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>

            <div class="f-link-btn-byron f-ml-3" @click="showComments(businesses.business)">
                <div class="f-link-btn-icon f-mt-2 f-ml-2"
                    :class="{
                        'f-grey-star-icon': businesses.business.stars_avg == 0,
                        'f-gold-star-icon': businesses.business.stars_avg > 0
                    }">
                    <div class="f-link-btn-text f-mt-2 f-ml-2" 
                        style="
                            margin-left: calc(calc(100/750)* var(--seg_width)) !important;
                            position: absolute;
                            margin-top: calc(calc(35/750)* var(--seg_width)) !important;
                            font-size: calc(calc(35/750)* var(--seg_width)) !important;
                        ">
                        {{ businesses.business.stars_avg }}
                    </div>
                </div> 
                <div class="f-link-btn-text f-mt-2 f-ml-2">{{ businesses.business.comments }} Ratings</div> 
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>

            <div v-if="!isEmpty(businesses.business.whatsapp)" class="f-link-btn-byron f-ml-3" @click="whatsAppBusiness(businesses.business)">
                <div class="f-link-btn-icon f-whatsapp-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">WhatsApp</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>

            <div v-if="!isEmpty(businesses.business.email) && !businesses.business.downgraded" 
                class="f-link-btn-byron f-ml-3" @click="emailBusiness(businesses.business)">
                <div class="f-link-btn-icon f-invite-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Email</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
            <div v-if="!isEmpty(businesses.business.google_url) && !businesses.business.downgraded" class="f-link-btn-byron f-ml-3" 
                @click="openBusinessUrl(businesses.business, 'google_url')">
                <div class="f-link-btn-icon f-google-listing-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Google</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
            <div v-if="!isEmpty(businesses.business.website_url) && !businesses.business.downgraded" class="f-link-btn-byron f-ml-3" 
                @click="openBusinessUrl(businesses.business, 'website_url')">
                <div class="f-link-btn-icon f-website-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Website</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
            <div v-if="!isEmpty(businesses.business.facebook_url) && !businesses.business.downgraded" class="f-link-btn-byron f-ml-3" 
                @click="openBusinessUrl(businesses.business, 'facebook_url')">
                <div class="f-link-btn-icon f-facebook-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Facebook</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
            <div v-if="!isEmpty(businesses.business.x_url) && !businesses.business.downgraded" class="f-link-btn-byron f-ml-3" 
                @click="openBusinessUrl(businesses.business, 'x_url')">
                <div class="f-link-btn-icon f-x-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">X</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
            <div v-if="!isEmpty(businesses.business.instagram_url) && !businesses.business.downgraded" class="f-link-btn-byron f-ml-3" 
                @click="openBusinessUrl(businesses.business, 'instagram_url')">
                <div class="f-link-btn-icon f-instagram-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Instagram</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>

            <div v-if="businesses.business.paid && !businesses.business.downgraded" class="f-link-btn-byron f-ml-3" 
                @click="openMySpecialsModule()">
                <div class="f-link-btn-icon f-my-specials-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">My Specials</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
        </div>

    </div>

    <div class="f-footer" v-if="isContentCreator(businesses.business)">
        <div class="f-buttons-<?=$unique_key?>">
            <div class="f-button-seg3" @click="closeBusinessPage()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div class="f-button-seg3" @click="reSubmitBusiness()" v-if="businesses.business.status == 'Rejected'">
                <div class="f-button-seg-icon f-save-icon"></div>
                <div class="f-button-text">ReSubmit</div>
            </div>
            <div class="f-button-seg3 f-act-btn" v-if="!businesses.business.paid" @click="showBusinessPaymentPage()">
                <div class="f-button-seg-icon f-pay-icon"></div>
                <div class="f-button-text">Pay</div>
            </div>
            <div v-if="getAlreadyCommented" class="f-button-seg3" 
                :class="{'f-act-btn': businesses.business.paid}"
                @click="showAddCommentChain('edit')">
                <div class="f-button-seg-icon f-edit-icon"></div>
                <div class="f-button-text">Rating</div>
            </div>
            <div v-else class="f-button-seg3" :class="{'f-act-btn': businesses.business.paid}" @click="showAddCommentChain()">
                <div class="f-button-seg-icon f-new-comment-icon"></div>
                <div class="f-button-text">Rating</div>
            </div>
            <div class="f-button-seg3" @click="showEditBusinessPage()">
                <div class="f-button-seg-icon f-edit-icon"></div>
                <div class="f-button-text">Edit</div>
            </div>
            <div class="f-button-seg3" @click="showShareBusinessListing()">
                <div class="f-button-seg-icon f-share-icon"></div>
                <div class="f-button-text">Share</div>
            </div>
            <div class="f-button-seg3" @click="deleteBusiness()">
                <div class="f-button-seg-icon f-delete-icon"></div>
                <div class="f-button-text">Delete</div>
            </div>
        </div>
    </div>
    <div class="f-footer" v-else-if="permissions.admin">
        <div class="f-buttons-<?=$unique_key?>">
            <div class="f-button-seg3" @click="closeBusinessPage()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div v-if="getAlreadyCommented" class="f-button-seg3 f-act-btn" @click="showAddCommentChain('edit')">
                <div class="f-button-seg-icon f-edit-icon"></div>
                <div class="f-button-text">Edit Rating</div>
            </div>
            <div v-else class="f-button-seg3 f-act-btn" @click="showAddCommentChain()">
                <div class="f-button-seg-icon f-new-comment-icon"></div>
                <div class="f-button-text">New Rating</div>
            </div>
            <div class="f-button-seg3" @click="showShareBusinessListing()">
                <div class="f-button-seg-icon f-share-icon"></div>
                <div class="f-button-text">Share</div>
            </div>
            <div class="f-button-seg3" @click="showEditBusinessPage()">
                <div class="f-button-seg-icon f-edit-icon"></div>
                <div class="f-button-text">Edit</div>
            </div>
        </div>
    </div>
    <div class="f-footer" v-else>
        <div class="f-buttons-<?=$unique_key?>">
            <div class="f-button-seg2" @click="closeBusinessPage()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div v-if="getAlreadyCommented" class="f-button-seg2 f-act-btn" @click="showAddCommentChain('edit')">
                <div class="f-button-seg-icon f-edit-icon"></div>
                <div class="f-button-text">Edit Rating</div>
            </div>
            <div v-else class="f-button-seg2 f-act-btn" @click="showAddCommentChain()">
                <div class="f-button-seg-icon f-new-comment-icon"></div>
                <div class="f-button-text">New Rating</div>
            </div>
            <div class="f-button-seg3" @click="showShareBusinessListing()">
                <div class="f-button-seg-icon f-share-icon"></div>
                <div class="f-button-text">Share</div>
            </div>
        </div>
    </div>

</div>


<div class="page f-page" :style="{ display: (pages.editBusiness) ? 'flex': 'none' }" >
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$unique_key?> f-mt-3">
            <div class="f-header-text-full f-ml-3">
                {{businesses.business.business_name}}
            </div> 
        </div>
    </div>

    <div class="f-body">

        <div class="f-body-contents">
            <div class="f-input-wrap">
                <div class="f-tab-btn f-act-bg-<?=$unique_key?> f-mt-1 detailsTab<?=$unique_key?>" 
                    @click="changeMyBusinessTab('details')"
                    >Details</div>
                <div class="f-tab-btn f-mt-1 f-ml-1 servicesTab<?=$unique_key?>" 
                    @click="changeMyBusinessTab('services')"
                    >Services</div>
                <div class="f-tab-btn f-mt-1 f-ml-1 logoTab<?=$unique_key?>" 
                    @click="changeMyBusinessTab('logo')"
                    >Logo</div>
                <div class="f-tab-btn f-mt-1 f-ml-1 channelsTab<?=$unique_key?>" 
                    @click="changeMyBusinessTab('channels')"
                    >Channels</div>
            </div>
        </div>

        <div v-if="pages.navTab == 'details'" class="f-body-contents f-scroll f-scroll-container f-mt-3">
            <div class="f-input-wrap f-border-bottom">
                <input type="text" class="f-input f-input-left" v-model="businesses.business.business_name" placeholder="Business Name *" >
                <div class="f-action-lnk-<?=$unique_key?>" @click="businesses.business.business_name = ''">clear</div>
            </div>
            <div class="f-input-wrap f-border-bottom">
                <input type="text" class="f-input f-input-left" v-model="businesses.business.vat_number" placeholder="VAT Number" >
                <div class="f-action-lnk-<?=$unique_key?>" @click="businesses.business.vat_number = ''">clear</div>
            </div>
            <div class="f-input-wrap f-border-bottom">
                <input type="text" class="f-input f-input-left" v-model="businesses.business.registration_number" placeholder="Registration Number" >
                <div class="f-action-lnk-<?=$unique_key?>" @click="businesses.business.registration_number = ''">clear</div>
            </div>
            <div class="f-input-wrap f-border-bottom f-mt-2">
                <div class="listing-listing-header-icon f-input-side f-b-0 contact_number_flag" :style="displayContactFlag(contact.flag)"></div>
                
                <input type="tel" class="f-input f-input-middle" v-model="businesses.business.contact_number" 
                    @input="changeContactFlag($event.target.value, '.contact_number_flag')" placeholder="0721234567 *" >
                
                <div v-if="settings.mobile()" class="f-action-lnk-<?=$unique_key?> f-action-lnk-<?=$unique_key?>" @click="selectBusinessContact('contact_number')">select</div>
                <div v-else class="f-action-lnk-<?=$unique_key?> f-action-lnk-<?=$unique_key?>" @click="businesses.business.contact_number = ''">clear</div>
            </div>
            <div class="f-input-wrap f-border-bottom f-mt-2">
                <div class="listing-listing-header-icon f-input-side f-b-0 office_number_flag" :style="displayContactFlag(contact.flag)"></div>
                
                <input type="tel" class="f-input f-input-middle" v-model="businesses.business.office_number" 
                    @input="changeContactFlag($event.target.value, '.office_number_flag')" placeholder="0721234567" >
                
                <div v-if="settings.mobile()" class="f-action-lnk-<?=$unique_key?> f-action-lnk-<?=$unique_key?>" @click="selectBusinessContact('office_number')">select</div>
                <div v-else class="f-action-lnk-<?=$unique_key?> f-action-lnk-<?=$unique_key?>" @click="businesses.business.office_number = ''">clear</div>
            </div>
            <div class="f-input-wrap f-border-bottom f-mt-2">
                <input type="text" class="f-input f-input-left" v-model="businesses.business.email" placeholder="Business Email *" >
                <div class="f-action-lnk-<?=$unique_key?>" @click="businesses.business.email = ''">clear</div>
            </div>
            <div class="fusion-descriptio f-mt-3">
                Person responsible for the business
            </div>
            <div class="f-input-wrap f-border-bottom">
                <input type="text" class="f-input f-input-left" v-model="businesses.business.person_name" placeholder="First Name *" >
                <div class="f-action-lnk-<?=$unique_key?>" @click="businesses.business.person_name = ''">clear</div>
            </div>
            <div class="f-input-wrap f-border-bottom f-mt-2">
                <input type="text" class="f-input f-input-left" v-model="businesses.business.person_surname" placeholder="Last Name *" >
                <div class="f-action-lnk-<?=$unique_key?>" @click="businesses.business.person_surname = ''">clear</div>
            </div>
        </div>

        <div v-if="pages.navTab == 'services'" class="f-body-contents f-scroll f-scroll-container f-mt-3">
            <div class="fusion-descriptio f-mt-3">
                Please describe the services your business suppliers.
            </div>
            <div class="f-input-wrap f-border-bottom f-mt-2">
                <textarea type="text" rows="10" class="f-input f-input" v-model="businesses.business.description" placeholder="E.g. Building, Tiling, Etc *" >
                    {{businesses.business.description}}
                </textarea>
            </div>
            <div class="fusion-descriptio f-mt-3" style="justify-content: right;">
                <div>Characters: {{ businesses.business.description.length }}/300</div>
            </div>
        </div>

        <div v-if="pages.navTab == 'logo'" class="f-body-contents f-scroll f-scroll-container f-mt-3">
            <div class="fusion-descriptio f-mt-3">
                Click the image button to upload a picture of your business's logo.
            </div>

            <div v-if="isEmpty(businesses.business.logo)" class="registration-image f-mt-3 f-upload-icon" @click="selectLogoFromFileBusiness()"></div>

            <div v-else class="registration-image f-mt-3" @click="selectLogoFromFileBusiness()">
                <div :style="displayListingLogo(businesses.business.logo, '&w=360&h=360&q=100&zc=6')"
                    style="
                        background-size: contain;
                        background-repeat: no-repeat;
                        background-position: center;
                    "></div>
            </div>

            <input type="file" hidden class="input" name="businessLogo" id="businessLogo" accept="image/*" />
        </div>

        <div v-if="pages.navTab == 'channels'" class="f-body-contents f-scroll f-scroll-container f-mt-3">
            <div class="fusion-descriptio f-mt-3">
                If you have a Google Business listing, enter the URL bellow or click "Other".
            </div>

            <label class="f-mt-3 f-df f-fr">
                Google Business URL 
                <div class="f-tab-btn f-act-bg-<?=$unique_key?> f-ml-2" 
                    @click="testBusinessUrl('google_url')"
                    v-if="!isEmpty(businesses.business.google_url)"
                    style="
                        margin-top: -4px;
                        padding: calc(calc(2/750) * var(--seg_width)) calc(calc(15/750) * var(--seg_width)) calc(calc(2/750) * var(--seg_width)) calc(calc(15/750) * var(--seg_width)) !important;
                    ">Test
                </div>
            </label>
            <div class="f-input-wrap f-border-bottom f-mt-0">
                <input type="text" class="f-input f-input-left" v-model="businesses.business.google_url" 
                    placeholder="Google Business URL " >
                <div class="f-action-lnk-<?=$unique_key?>" 
                    v-if="!isEmpty(businesses.business.google_url)"
                    @click="businesses.business.google_url = ''">clear</div>
            </div>

            <label class="f-mt-2 f-df f-fr">
                Website URL
                <div class="f-tab-btn f-act-bg-<?=$unique_key?> f-ml-2" 
                    @click="testBusinessUrl('website_url')"
                    v-if="!isEmpty(businesses.business.website_url)"
                    style="
                        margin-top: -4px;
                        padding: calc(calc(2/750) * var(--seg_width)) calc(calc(15/750) * var(--seg_width)) calc(calc(2/750) * var(--seg_width)) calc(calc(15/750) * var(--seg_width)) !important;
                    ">Test
                </div>
            </label>
            <div class="f-input-wrap f-border-bottom f-mt-0">
                <input type="text" class="f-input f-input-left" v-model="businesses.business.website_url" 
                    placeholder="Website URL" >
                <div class="f-action-lnk-<?=$unique_key?>" 
                    v-if="!isEmpty(businesses.business.website_url)"
                    @click="businesses.business.website_url = ''">clear</div>
            </div>

            <label class="f-mt-2 f-df f-fr">
                Facebook URL
                <div class="f-tab-btn f-act-bg-<?=$unique_key?> f-ml-2" 
                    @click="testBusinessUrl('facebook_url')"
                    v-if="!isEmpty(businesses.business.facebook_url)"
                    style="
                        margin-top: -4px;
                        padding: calc(calc(2/750) * var(--seg_width)) calc(calc(15/750) * var(--seg_width)) calc(calc(2/750) * var(--seg_width)) calc(calc(15/750) * var(--seg_width)) !important;
                    ">Test
                </div>
            </label>
            <div class="f-input-wrap f-border-bottom f-mt-0">
                <input type="text" class="f-input f-input-left" v-model="businesses.business.facebook_url" 
                    placeholder="Facebook URL" >
                <div class="f-action-lnk-<?=$unique_key?>" 
                    v-if="!isEmpty(businesses.business.facebook_url)"
                    @click="businesses.business.facebook_url = ''">clear</div>
            </div>

            <label class="f-mt-2 f-df f-fr">
                X URL
                <div class="f-tab-btn f-act-bg-<?=$unique_key?> f-ml-2" 
                    @click="testBusinessUrl('x_url')"
                    v-if="!isEmpty(businesses.business.x_url)"
                    style="
                        margin-top: -4px;
                        padding: calc(calc(2/750) * var(--seg_width)) calc(calc(15/750) * var(--seg_width)) calc(calc(2/750) * var(--seg_width)) calc(calc(15/750) * var(--seg_width)) !important;
                    ">Test
                </div>
            </label>
            <div class="f-input-wrap f-border-bottom f-mt-0">
                <input type="text" class="f-input f-input-left" v-model="businesses.business.x_url" 
                    placeholder="X URL" >
                <div class="f-action-lnk-<?=$unique_key?>" 
                    v-if="!isEmpty(businesses.business.x_url)"
                    @click="businesses.business.x_url = ''">clear</div>
            </div>

            <label class="f-mt-2 f-df f-fr">
                Instagram URL
                <div class="f-tab-btn f-act-bg-<?=$unique_key?> f-ml-2" 
                    @click="testBusinessUrl('instagram_url')"
                    v-if="!isEmpty(businesses.business.instagram_url)"
                    style="
                        margin-top: -4px;
                        padding: calc(calc(2/750) * var(--seg_width)) calc(calc(15/750) * var(--seg_width)) calc(calc(2/750) * var(--seg_width)) calc(calc(15/750) * var(--seg_width)) !important;
                    ">Test
                </div>
            </label>
            <div class="f-input-wrap f-border-bottom f-mt-0">
                <input type="text" class="f-input f-input-left" v-model="businesses.business.instagram_url" 
                    placeholder="Instagram URL" >
                <div class="f-action-lnk-<?=$unique_key?>" 
                    v-if="!isEmpty(businesses.business.instagram_url)"
                    @click="businesses.business.instagram_url = ''">clear</div>
            </div>
        </div>
        
    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$unique_key?>">
            <div v-if="pages.navTab == 'details'" class="f-button-seg3" @click="closeEditBusinessPage()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div v-else class="f-button-seg3" @click="backTab()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div class="f-button-seg3" @click="updateBusiness()">
                <div class="f-button-seg-icon f-save-icon"></div>
                <div class="f-button-text">Save</div>
            </div>
            <div v-if="pages.navTab != 'channels'" class="f-button-seg3" @click="nextTab()">
                <div class="f-button-seg-icon f-next-icon"></div>
                <div class="f-button-text">Next</div>
            </div>
        </div>
    </div>

</div>


<div class="f-overlay" :style="{ display: (pages.businessContact) ? 'flex': 'none' }" 
    @click.self="closeTraderContact()">
    <div class="f-overlay-content">

        <div class="f-overlay-header f-fc">
            <div class="fusion-heading f-heading-<?=$unique_key?> f-mt-3 f-mb-3">
                {{businesses.business.trader_name}}
            </div>
        </div>

        <div class="f-overlay-body">
            <div class="f-link-btn-wrap">
                <div class="f-link-btn-byron f-ml-3" @click="openDialerApp(businesses.business.contact_number)">
                    <div class="f-link-btn-icon f-contact-icon f-mt-2 f-ml-2"></div>
                    <div class="f-link-btn-text f-mt-2 f-ml-2">Call</div>
                    <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
                </div>
                <div class="f-link-btn-byron f-ml-3" v-if="businesses.business.whatsapp" 
                    @click="openWhatsAppApp(businesses.business.whatsapp)">
                    <div class="f-link-btn-icon f-whatsapp-icon f-mt-2 f-ml-2"></div>
                    <div class="f-link-btn-text f-mt-2 f-ml-2">WhatsApp</div>
                    <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
                </div>
            </div>
        </div>

        <div class="f-overlay-footer">
            <div class="f-buttons-<?=$unique_key?>">
                <div class="f-button" @click="closeTraderContact()">
                    <div class="f-button-seg-icon f-back-icon"></div>
                    <div class="f-button-text">Back</div>
                </div>
            </div>
        </div>

    </div>
</div>


<div class="page f-page" :style="{ display: (pages.businessReferral) ? 'flex': 'none' }" >

    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$unique_key?> f-mt-3">
            <div class="f-header-text f-ml-3">
                {{businesses.business.business_name}}
            </div>
            <!-- <div class="f-df f-fr f-pl-2 f-pr-2 f-mt-1"></div> -->
            <div class="f-header-icons f-df f-fr">
                <div class="listing-listing-header-txt-icon f-fav-heart-icon f-mt-1 f-mr-1" v-if="isFavorite(businesses.business)" 
                    style="background-size: calc(calc(35/750) * var(--seg_width));"></div>

                <div class="listing-listing-header-txt-icon f-color-grey f-mt-1 f-mr1" v-if="businesses.business.stars_avg == 0"
                    style="
                        line-height: calc(calc(30/750) * var(--seg_width));
                        font-size: calc(calc(30/750) * var(--seg_width));
                        padding-right: calc(calc(8/750) * var(--seg_width));
                    ">-</div>
                <div class="listing-listing-header-txt-icon f-grey-star-icon f-mr-1" v-if="businesses.business.stars_avg == 0"
                    style="
                        float: left;
                        background-repeat: no-repeat;
                        width: calc(calc(40/750) * var(--seg_width));
                        height: calc(calc(50/750) * var(--seg_width));
                        margin-top: calc(calc(-4/750) * var(--seg_width));
                        background-size: calc(calc(45/750) * var(--seg_width));
                    "></div>

                <div class="listing-listing-header-txt-icon f-color-grey f-mt-1 f-mr1" v-if="businesses.business.stars_avg > 0"
                    style="
                        line-height: calc(calc(30/750) * var(--seg_width));
                        font-size: calc(calc(30/750) * var(--seg_width));
                        padding-right: calc(calc(8/750) * var(--seg_width));
                    ">{{businesses.business.stars_avg}}</div>
                <div class="listing-listing-header-txt-icon f-gold-star-icon f-mr-1" v-if="businesses.business.stars_avg > 0"
                    style="
                        float: left;
                        background-repeat: no-repeat;
                        width: calc(calc(40/750) * var(--seg_width));
                        height: calc(calc(50/750) * var(--seg_width));
                        margin-top: calc(calc(-4/750) * var(--seg_width));
                        background-size: calc(calc(45/750) * var(--seg_width));
                    "></div>
            </div> 
        </div>
    </div>

    <div class="f-body">
        <div class="f-link-btn-wrap f-scroll">
            <div class="f-link-btn-byron f-ml-3" @click="openDialerApp(businesses.business.contact_number)">
                <div class="f-link-btn-icon f-contact-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Call</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
            <div class="f-link-btn-byron f-ml-3" v-if="businesses.business.whatsapp" 
                @click="openWhatsAppApp(businesses.business.whatsapp)">
                <div class="f-link-btn-icon f-whatsapp-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">WhatsApp</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
            <div v-if="isContentCreator(businesses.business) || permissions.admin" class="f-link-btn-byron f-ml-3" @click="showEditReferral(businesses.business)">
                <div class="f-link-btn-icon f-edit-listing-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Edit</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
        </div>

        <div class="f-body-contents-search-<?=$unique_key?> f-mt-3">
            <div class="f-input-wrap f-mt-3 f-ml-3 f-mb-3 f-border-bottom">
                <input type="text" class="f-input f-input-left f-pl-2"
                    v-model="comments.mainFilter" placeholder="Search comments by name." >
                <div class="f-action-lnk-<?=$unique_key?> f-action-lnk-search" @click="comments.mainFilter = ''">clear</div>
            </div>
        </div>

        <div class="f-body-contents-full f-scroll main-contents-<?=$unique_key?>">

            <div v-for="(comment, index) in getCommentsList" :key="index" 
                class="listing-listing f-fc" v-show="searchComments(comment)"
                @click="showCommentContact(comment)">

                <div class="listing-listing-body f-fc f-mt-3 f-ml-3" >   
                    <div class="listing-listing-body-txt" v-html="displayCommentContent(comment)"></div>
                    <div class="listing-listing-body-rating-txt f-df" v-html="displayCommentRatings(comment)"></div>
                </div>

                <div class="listing-listing-header f-mt-3 f-ml-3">
                    <!-- <div class="listing-listing-header-icon f-cycle-radius f-b-0" :style="memberPhoto(comment.picture, '&w=360&h=360&q=100&zc=6')"></div> -->
                    <div v-if="isEmpty(comment.picture)" class="listing-listing-header-icon-no-iamge f-cycle-radius f-b-0">
                        {{memberNoPicture(comment)}}
                    </div>
                    <div v-else class="listing-listing-header-icon f-cycle-radius f-b-0" :style="memberPhoto(comment.picture, '&w=360&h=360&q=100&zc=6')"></div>
                    <div class="listing-listing-header-txt-holder f-fc" >
                        <div class="listing-listing-header-txt f-ml-2">{{ comment.name }} {{ comment.surname }}</div>
                        <div class="listing-listing-header-txt-icons f-ml-2">
                            {{ getRelativeTimestamp(comment.updated) }}
                        </div>
                    </div>
                </div>
                <div class="listing-listing-footer f-ml-3"></div>
            </div>

        </div>

    </div>

    <div class="f-footer" >
        <div class="f-buttons-<?=$unique_key?>">
            <div class="f-button-seg4" @click="closeBusinessReferralPage()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div v-if="getAlreadyCommented" class="f-button-seg4 f-act-btn" @click="showEditComment(getMyCommentFromComments())">
                <div class="f-button-seg-icon f-edit-icon"></div>
                <div class="f-button-text">Edit Rating</div>
            </div>
            <div v-else class="f-button-seg4 f-act-btn" @click="showAddComment()">
                <div class="f-button-seg-icon f-new-comment-icon"></div>
                <div class="f-button-text">New Rating</div>
            </div>
            <div v-if="comments.ascending" class="f-button-seg4" @click="sortCommentsByStars()">
                <div class="f-button-seg-icon f-star-rating-small-large-icon"></div>
                <div class="f-button-text">Ascending</div>
            </div>
            <div v-else class="f-button-seg4" @click="sortCommentsByStars()">
                <div class="f-button-seg-icon f-star-rating-large-small-icon"></div>
                <div class="f-button-text">Descending</div>
            </div>
            <div class="f-button-seg3" @click="showShareBusinessListing()">
                <div class="f-button-seg-icon f-share-icon"></div>
                <div class="f-button-text">Share</div>
            </div>
            <div class="f-button-seg4" @click="scrollToTop()">
                <div class="f-button-seg-icon f-to-top-icon"></div>
                <div class="f-button-text">To Top</div>
            </div>
        </div>
    </div>

</div>


<div class="page f-page" :style="{ display: (pages.businessPayment) ? 'flex': 'none' }" >
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$unique_key?> f-mt-3">
            <div class="f-header-text-full f-ml-3">
                List Your Business
            </div> 
        </div>
        <div class="fusion-description f-mt-2">
            Choose your plan:
        </div>
    </div>

    <div class="f-body">
        <div class="f-body-contents-full f-mb-3 f-scroll main-contents-<?=$unique_key?>"
            style="height: calc(var(--s_height) - calc(calc(320/750) * var(--seg_width))) !important;">

            <div class="f-list-item f-fr f-mt-2 f-bb-0 f-mb-3">
                <div class="f-list-item-wrap f-df f-fc f-ml-3" style="
                    display: block;
                    width: calc(calc(335/750) * var(--seg_width)) !important; 
                    height: calc(calc(200/750) * var(--seg_width)) !important; 
                "
                @click="openPayFastComponent('99')">
                    <div class="f-action-btn f-act-bg-<?=$unique_key?> f-mt-1 f-df f-fc" style="
                        width: calc(calc(335/750) * var(--seg_width)) !important;
                        height: calc(calc(200/750) * var(--seg_width)) !important; 
                    ">
                        <span style="width: calc(calc(325/750) * var(--seg_width)) !important; float: left; font-size: calc(calc(35/750) * var(--seg_width));">
                            <strong>Monthly R99</strong>
                        </span>
                        <span class="f-mt-1" style="width: calc(calc(325/750) * var(--seg_width)); float: left; font-size: calc(calc(25/750) * var(--seg_width));">
                            30-Day Listing
                        </span>
                    </div>
                </div>
                <div class="f-list-item-wrap f-df f-fc f-ml-3" style="
                    display: block;
                    width: calc(calc(335/750) * var(--seg_width)) !important; 
                    height: calc(calc(200/750) * var(--seg_width)) !important; 
                "
                @click="openPayFastComponent('270')">
                    <div class="f-action-btn f-act-bg-<?=$unique_key?> f-mt-1 f-df f-fc" style="
                        width: calc(calc(335/750) * var(--seg_width)) !important;
                        height: calc(calc(200/750) * var(--seg_width)) !important; 
                    ">
                        <span style="width: calc(calc(325/750) * var(--seg_width)) !important; float: left; font-size: calc(calc(35/750) * var(--seg_width));">
                            <strong>3 Monthls R270</strong>
                        </span>
                        <span class="f-mt-1" style="width: calc(calc(325/750) * var(--seg_width)); float: left; font-size: calc(calc(25/750) * var(--seg_width));">
                            90-Day Listing
                        </span>
                    </div>
                </div>
            </div>

            <div class="f-list-item f-fr f-bb-0 f-mb-3">
                <div class="f-list-item-wrap f-df f-fc f-ml-3" style="
                        display: block;
                        width: calc(calc(335/750) * var(--seg_width)) !important; 
                        height: calc(calc(200/750) * var(--seg_width)) !important; 
                    "
                    @click="openPayFastComponent('495')">
                    <div class="f-action-btn f-act-bg-<?=$unique_key?> f-mt-1 f-df f-fc" style="
                        width: calc(calc(335/750) * var(--seg_width)) !important;
                        height: calc(calc(200/750) * var(--seg_width)) !important; 
                    ">
                        <span style="width: calc(calc(325/750) * var(--seg_width)) !important; float: left; font-size: calc(calc(35/750) * var(--seg_width));">
                            <strong>6 Months R495</strong>
                        </span>
                        <span class="f-mt-1" style="width: calc(calc(325/750) * var(--seg_width)); float: left; font-size: calc(calc(25/750) * var(--seg_width));">
                            6-Month Listing
                        </span>
                    </div>
                    <div class="f-header-icons">
                        <div class="f-action-btn f-act-bg-<?=$unique_key?> f-act-btn" style="
                            z-index: 10000;
                            position: absolution;
                            font-size: calc(calc(20/750) * var(--seg_width)) !important; 
                            height: calc(calc(60/750) * var(--seg_width)) !important; 
                            width: calc(calc(220/750) * var(--seg_width)) !important;
                            margin-top: calc(calc(-30/750) * var(--seg_width)) !important;">
                            1 Months Free
                        </div>
                    </div>
                </div>

                <div class="f-list-item-wrap f-df f-fc f-ml-3" style="
                        display: block;
                        width: calc(calc(335/750) * var(--seg_width)) !important; 
                        height: calc(calc(200/750) * var(--seg_width)) !important; 
                    "
                    @click="openPayFastComponent('990')">
                    <div class="f-action-btn f-act-bg-<?=$unique_key?> f-mt-1 f-df f-fc" style="
                        width: calc(calc(335/750) * var(--seg_width)) !important;
                        height: calc(calc(200/750) * var(--seg_width)) !important; 
                    ">
                        <span style="width: calc(calc(325/750) * var(--seg_width)) !important; float: left; font-size: calc(calc(35/750) * var(--seg_width));">
                            <strong>Annual R990</strong>
                        </span>
                        <span class="f-mt-1" style="width: calc(calc(325/750) * var(--seg_width)); float: left; font-size: calc(calc(25/750) * var(--seg_width));">
                            12-Month Listing
                        </span>
                    </div>
                    <div class="f-header-icons">
                        <div class="f-action-btn f-act-bg-<?=$unique_key?> f-act-btn" style="
                            z-index: 10000;
                            position: absolution;
                            font-size: calc(calc(20/750) * var(--seg_width)) !important; 
                            height: calc(calc(60/750) * var(--seg_width)) !important; 
                            width: calc(calc(220/750) * var(--seg_width)) !important;
                            margin-top: calc(calc(-30/750) * var(--seg_width)) !important;">
                            2 Months Free
                        </div>
                    </div>
                </div>
            </div>


            <div class="f-list-item f-fc f-mt-2 f-bb-0 f-mt-3">
                <div class="f-list-item-wrap f-fc f-ml-3">
                    <div class="f-list-item-name f-mt-3">Details:</div>
                    <div class="f-list-item-description" style="color: #000">
                        <ul>
                            <li class="f-mb-1">Starts from the date your listing is approved</li>
                            <li class="f-mb-1">Approvals within 24 hours of payment</li>
                            <li>If not approved, we'll contact you to amend it or refund your payment</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="f-list-item f-fc f-mt-2 f-bb-0">

                <?php if($env == "sandbox") { ?>
                <div class="f-list-item-wrap f-ml-3">
                    <div class="f-action-btn f-act-bg-<?=$unique_key?> f-mt-1" 
                        style="width: calc(calc(690/750) * var(--seg_width)) !important;"
                        @click="openPayFastComponent('5')">Test Payment R5</div>
                </div>
                <?php } ?>
                <!-- <div class="f-list-item-wrap f-ml-3 f-mt-2">
                    <div class="f-action-btn f-act-bg-<?=$unique_key?> f-mt-1" 
                        style="width: calc(calc(690/750) * var(--seg_width)) !important;"
                        @click="openPayFastComponent('99')">Monthly R99</div>
                </div>
                <div class="f-list-item-wrap f-ml-3 f-mt-2">
                    <div class="f-action-btn f-act-bg-<?=$unique_key?> f-mt-1" 
                        style="width: calc(calc(690/750) * var(--seg_width)) !important;"
                        @click="openPayFastComponent('990')">Annual R990</div>
                </div> -->
                
            </div>

        </div>
    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$unique_key?>">
            <div class="f-button" @click="closeBusinessPaymentPage()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
        </div>
    </div>

</div>


<div class="page f-page" :style="{ display: (pages.businessAnalytics) ? 'flex': 'none' }" >
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$unique_key?> f-mt-3">
            <div class="f-header-text-full f-ml-3">
                Business Analytics
            </div> 
        </div>
    </div>

    <div class="f-body">
        <div class="f-body-contents-full f-mb-3 f-scroll main-contents-<?=$unique_key?>">
            <!-- <div class="f-list-item f-fc f-mt-2 f-bb-0 f-mt-3">
                <div class="f-list-item-wrap f-fc f-ml-3">
                    <div class="f-list-item-name f-mt-3">Details:</div>
                    <div class="f-list-item-description" style="color: #000">
                        <ul>
                            <li class="f-mb-1">Starts from the date your listing is approved</li>
                            <li class="f-mb-1">Approvals within 24 hours of payment</li>
                            <li>If not approved, we'll contact you to amend it or refund your payment</li>
                        </ul>
                    </div>
                </div>
            </div> -->

            <div class="f-list-item f-fc f-mt-2 f-bb-0">

                <?php if($env == "sandbox") { ?>
                <!-- <div class="f-list-item-wrap f-ml-3">
                    <div class="f-action-btn f-act-bg-<?=$unique_key?> f-mt-1" 
                        style="width: calc(calc(690/750) * var(--seg_width)) !important;"
                        @click="openPayFastComponent('5')">Test Payment R5</div>
                </div> -->
                <?php } ?>
                <!-- <div class="f-list-item-wrap f-ml-3 f-mt-2">
                    <div class="f-action-btn f-act-bg-<?=$unique_key?> f-mt-1" 
                        style="width: calc(calc(690/750) * var(--seg_width)) !important;"
                        @click="openPayFastComponent('99')">Monthly R99</div>
                </div>
                <div class="f-list-item-wrap f-ml-3 f-mt-2">
                    <div class="f-action-btn f-act-bg-<?=$unique_key?> f-mt-1" 
                        style="width: calc(calc(690/750) * var(--seg_width)) !important;"
                        @click="openPayFastComponent('990')">Annual R990</div>
                </div> -->
                
            </div>

        </div>
    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$unique_key?>">
            <div class="f-button-seg2" @click="closeBusinessAnalyticsPage()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
        </div>
    </div>
</div>


<div class="f-overlay" :style="{ display: (pages.shareBusinessListing) ? 'flex': 'none' }" 
    @click.self="closeShareBusinessListing()">
    <div class="f-overlay-content">

        <div class="f-overlay-header f-fc">
            <div class="fusion-heading f-heading-<?=$unique_key?> f-mt-3 f-mb-3">
                Share {{businesses.business.trader_name}}
            </div>
        </div>

        <div class="f-overlay-body">
            <!-- <div class="f-body-contents f-scroll f-scroll-container f-mt-3 f-mb-3">
                <div class="f-input-wrap f-border-bottom f-mt-2">
                    <div class="listing-listing-header-icon f-input-side f-b-0 contact_number_flag" :style="displayContactFlag(contact.flag)"></div>
                    
                    <input type="tel" class="f-input f-input-middle" v-model="shareContactNumber" 
                        @input="changeContactFlag($event.target.value, '.contact_number_flag')" placeholder="0721234567 *" >
                    
                    <div v-if="settings.mobile()" class="f-action-lnk-<?=$unique_key?> f-action-lnk-<?=$unique_key?>" @click="selectShareContactNumber()">select</div>
                    <div v-else class="f-action-lnk-<?=$unique_key?> f-action-lnk-<?=$unique_key?>" @click="shareContactNumber = ''">clear</div>
                </div>
            </div> -->
            <div class="f-link-btn-wrap">
                <div class="f-link-btn-byron f-ml-3" @click="shareBusiness('sms')">
                    <div class="f-link-btn-icon f-contact-icon f-mt-2 f-ml-2"></div>
                    <div class="f-link-btn-text f-mt-2 f-ml-2">SMS</div>
                    <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
                </div>
                <div class="f-link-btn-byron f-ml-3" @click="shareBusiness('whatsapp')">
                    <div class="f-link-btn-icon f-whatsapp-icon f-mt-2 f-ml-2"></div>
                    <div class="f-link-btn-text f-mt-2 f-ml-2">WhatsApp</div>
                    <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
                </div>
            </div>
        </div>

        <div class="f-overlay-footer">
            <div class="f-buttons-<?=$unique_key?>">
                <div class="f-button" @click="closeShareBusinessListing()">
                    <div class="f-button-seg-icon f-back-icon"></div>
                    <div class="f-button-text">Back</div>
                </div>
            </div>
        </div>

    </div>
</div>






<div class="page f-page" :style="{ display: pages.manageListings ? 'flex': 'none' }" >
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$unique_key?> f-fc f-mt-3">
            <div class="f-header-text-full f-ml-3">
                Manage Listings ({{getManageListingsList.length}})
            </div>
        </div>
        <div class="fusion-description f-mt-2">
            Click on a business listing to view more info.
        </div>
        <div class="fusion-description f-mt-0 f-df f-fr" style="margin-top: calc(calc(-30/750) * var(--seg_width)) !important;">
            <div class="listing-listing-header-txt-icon f-fav-heart-icon" 
                style="
                    margin-top: calc(calc(5/750) * var(--seg_width)) !important;
                    background-size: calc(calc(30/750) * var(--seg_width));
                "></div>
            <span class="f-df f-ml-2" style="
                font-size: calc(calc(23/750) * var(--seg_width));
                color: #9d9fa2;
                margin-left: calc(calc(10/750) * var(--seg_width)) !important;">My Favorites</span>
            <div class="listing-listing-header-txt-icon-heart f-verified-icon" 
                style="
                    margin-top: calc(calc(5/750) * var(--seg_width)) !important;
                    margin-left: calc(calc(15/750) * var(--seg_width)) !important;
                "></div>
            <span class="f-df f-ml-2" style="
                font-size: calc(calc(23/750) * var(--seg_width));
                color: #9d9fa2;
                margin-left: calc(calc(10/750) * var(--seg_width)) !important;">Paid Listing</span>
            
            <div class="listing-listing-header-txt-icon f-gold-star-icon" 
                style="
                    margin-top: calc(calc(5/750) * var(--seg_width)) !important;
                    margin-left: calc(calc(15/750) * var(--seg_width)) !important;
                    background-size: calc(calc(35/750) * var(--seg_width));
                "></div>
            <span class="f-df f-ml-2" style="
                font-size: calc(calc(23/750) * var(--seg_width));
                color: #9d9fa2;
                margin-left: calc(calc(10/750) * var(--seg_width)) !important;">Community Ratings</span>
        </div>
    </div>

    <div class="f-body">

        <div class="f-body-contents-search-<?=$unique_key?> f-fr">
            <div class="f-input-wrap-byron f-mt-3 f-ml-3 f-mb-3 f-border-bottom">
                <input type="text" class="f-input f-input-left-search f-pl-2"
                    v-model="businesses.mainFilter" 
                    placeholder="Search listing by keyword." >
                <div class="f-action-lnk-<?=$unique_key?> f-action-lnk-search" @click="businesses.mainFilter = ''">clear</div>
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

            <div v-if="businesses.sort.dateAscending" class="f-button-seg3 date-sort sort-item-background" @click="applySortManageListings('date')">
                <div class="f-button-seg-icon f-sort-date-jan-dec-icon"></div>
                <div class="f-button-text">Date</div>
            </div>
            <div v-else class="f-button-seg3 date-sort sort-item-background" @click="applySortManageListings('date')">
                <div class="f-button-seg-icon f-sort-date-dec-jan-icon"></div>
                <div class="f-button-text">Date</div>
            </div>

            <div v-if="businesses.sort.nameAscending" class="f-button-seg3 name-sort" @click="applySortManageListings('name')">
                <div class="f-button-seg-icon f-sort-za-icon"></div>
                <div class="f-button-text">Name</div>
            </div>
            <div v-else class="f-button-seg3 name-sort" @click="applySortManageListings('name')">
                <div class="f-button-seg-icon f-sort-az-icon"></div>
                <div class="f-button-text">Name</div>
            </div>

            <div v-if="businesses.sort.ratingAscending" class="f-button-seg3 rating-sort" @click="applySortManageListings('rating')">
                <div class="f-button-seg-icon f-star-rating-dec-icon"></div>
                <div class="f-button-text">Rating</div>
            </div>
            <div v-else class="f-button-seg3 rating-sort" @click="applySortManageListings('rating')">
                <div class="f-button-seg-icon f-star-rating-asc-icon "></div>
                <div class="f-button-text">Rating</div>
            </div>
        </div>

        <div class="f-body-contents-full f-mb-3 f-scroll main-contents-<?=$unique_key?>"
            style="height: calc(var(--s_height) - calc(calc(480/750) * var(--seg_width))) !important;">

            <div v-if="getManageListingsList.length == 0 && loading.businesses" 
                class="f-list-item" style="border-bottom: 0px;">
                <div class="f-list-item-wrap f-mt-3 f-ml-3">
                    <div class="loader-<?=$unique_key?>"></div> 
                    <div class="loader-text">Loading ...</div>
                </div>
            </div>

            <div v-else-if="getManageListingsList.length == 0" 
                class="f-list-item" style="border-bottom: 0px;">
                <div class="f-list-item-wrap f-mt-3 f-ml-3">
                    There are no businesses to approve. &#128515;
                </div>
            </div>

            <div v-else v-for="(business, index) in getManageListingsList" :key="index" 
                class="listing-listing f-fc"
                :class="{
                    'listing-listing-recommended': business.referral
                }"
                @click="showManageListingPage(business)">

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
            <div class="f-button-seg3" @click="closeManageListings()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div class="f-button-seg3 f-act-btn" @click="showMergeListings()">
                <div class="f-button-seg-icon f-merge-icon"></div>
                <div class="f-button-text">Merge</div>
            </div>
            <div class="f-button-seg3" @click="showManageFilter()">
                <div class="f-button-seg-icon f-fav-heart-icon" v-if="getFilterCondition == 'faves'"></div>
                <div class="f-button-seg-icon f-verified-icon" v-else-if="getFilterCondition == 'access'"></div>
                <div class="f-button-seg-icon f-gold-star-icon" v-else-if="getFilterCondition == 'rated'"></div>
                <div class="f-button-seg-icon f-grey-star-icon" v-else-if="getFilterCondition == 'not_rated'"></div>
                <div class="f-button-seg-icon f-filter-icon" v-else></div>
                <div class="f-button-text">Filter</div>
            </div>
        </div>
    </div>

</div>


<div class="page f-page" :style="{ display: pages.downgradedListings ? 'flex': 'none' }" >
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$unique_key?> f-fc f-mt-3">
            <div class="f-header-text-full f-ml-3">
                Downgraded Listings ({{getManageListingsList.length}})
            </div>
        </div>
        <div class="fusion-description f-mt-2">
            Click on a business listing to view more info.
        </div>
    </div>

    <div class="f-body">

        <div class="f-body-contents-search-<?=$unique_key?> f-fr">
            <div class="f-input-wrap-byron f-mt-3 f-ml-3 f-mb-3 f-border-bottom">
                <input type="text" class="f-input f-input-left-search f-pl-2"
                    v-model="businesses.mainFilter" 
                    placeholder="Search listing by keyword." >
                <div class="f-action-lnk-<?=$unique_key?> f-action-lnk-search" @click="businesses.mainFilter = ''">clear</div>
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

            <div v-if="businesses.sort.dateAscending" class="f-button-seg3 date-sort sort-item-background" @click="applySortManageListings('date')">
                <div class="f-button-seg-icon f-sort-date-jan-dec-icon"></div>
                <div class="f-button-text">Date</div>
            </div>
            <div v-else class="f-button-seg3 date-sort sort-item-background" @click="applySortManageListings('date')">
                <div class="f-button-seg-icon f-sort-date-dec-jan-icon"></div>
                <div class="f-button-text">Date</div>
            </div>

            <div v-if="businesses.sort.nameAscending" class="f-button-seg3 name-sort" @click="applySortManageListings('name')">
                <div class="f-button-seg-icon f-sort-za-icon"></div>
                <div class="f-button-text">Name</div>
            </div>
            <div v-else class="f-button-seg3 name-sort" @click="applySortManageListings('name')">
                <div class="f-button-seg-icon f-sort-az-icon"></div>
                <div class="f-button-text">Name</div>
            </div>

            <div v-if="businesses.sort.ratingAscending" class="f-button-seg3 rating-sort" @click="applySortManageListings('rating')">
                <div class="f-button-seg-icon f-star-rating-dec-icon"></div>
                <div class="f-button-text">Rating</div>
            </div>
            <div v-else class="f-button-seg3 rating-sort" @click="applySortManageListings('rating')">
                <div class="f-button-seg-icon f-star-rating-asc-icon "></div>
                <div class="f-button-text">Rating</div>
            </div>
        </div>

        <div class="f-body-contents-full f-mb-3 f-scroll main-contents-<?=$unique_key?>"
            style="height: calc(var(--s_height) - calc(calc(435/750) * var(--seg_width))) !important;">

            <div v-if="getManageListingsList.length == 0 && loading.businesses" 
                class="f-list-item" style="border-bottom: 0px;">
                <div class="f-list-item-wrap f-mt-3 f-ml-3">
                    <div class="loader-<?=$unique_key?>"></div> 
                    <div class="loader-text">Loading ...</div>
                </div>
            </div>

            <div v-else-if="getManageListingsList.length == 0" 
                class="f-list-item" style="border-bottom: 0px;">
                <div class="f-list-item-wrap f-mt-3 f-ml-3">
                    There are no businesses to approve. &#128515;
                </div>
            </div>

            <div v-else v-for="(business, index) in getManageListingsList" :key="index" 
                class="listing-listing f-fc"
                :class="{
                    'listing-listing-recommended': business.referral
                }"
                @click="showListingCallLog(business)">

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
                        
                    </div>

                </div>

                <div class="listing-listing-body f-mt-2 f-ml-3 f-df f-fc"
                    :class="{
                        'listing-listing-recommended': business.referral
                    }">
                    <div class="listing-listing-body-txt">
                        Status: {{ business.status }} <br/>
                    </div>
                    <div class="listing-listing-body-txt f-df f-fr">
                        <span style="width: 50%">Subscription: {{ business.subscription_status }}</span>
                        <span style="width: 50%; text-align: right;">{{ business.date_downgraded }}</span>
                    </div>
                </div>

                <div class="listing-listing-footer f-ml-3"></div>
            </div>

        </div>
    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$unique_key?>">
            <div class="f-button" @click="closeDowngradedListings()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
        </div>
    </div>

</div>


<div class="page f-page" :style="{ display: (pages.manageListing) ? 'flex': 'none' }" >

    <div class="f-body-no-header">
        <div class="f-body-contents-full">

            <div class="listing-listing f-fc f-bb-0">
                <div class="listing-listing-header f-mt-3 f-ml-3">
                    <div v-if="isEmpty(businesses.business.logo)" class="f-list-item-main-right-byron-no-iamge">{{businessNoLogo(businesses.business)}}</div>
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
                <div class="listing-listing-header f-mt-2 f-mb-2 f-ml-3">
                    <div class="listing-listing-header-txt"
                        style="
                            width: calc(calc(690/750) * var(--seg_width)) !important;
                            font-size: calc(calc(30/750) * var(--seg_width));
                        ">
                        {{ businesses.business.display_name }}
                    </div>
                </div>
            </div>

            <div class="listing-listing f-fc f-bb-1">
                <div class="listing-listing-header f-mt-2 f-mb-2 f-ml-3">
                    <div class="listing-listing-body-txt" v-html="businesses.business.description"></div>
                </div>
            </div>
        </div>

        <div class="f-link-btn-wrap f-scroll">
            <div class="f-link-btn-byron f-ml-3" @click="openDialerApp(businesses.business.contact_number)">
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
            
            <div v-if="!isEmpty(businesses.business.whatsapp)" class="f-link-btn-byron f-ml-3" @click="openWhatsAppApp(businesses.business.whatsapp)">
                <div class="f-link-btn-icon f-whatsapp-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">WhatsApp</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
            <div v-if="!isEmpty(businesses.business.email)" class="f-link-btn-byron f-ml-3" @click="openEmailApp(businesses.business.email)">
                <div class="f-link-btn-icon f-invite-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Email</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
            
            <div v-if="!isEmpty(businesses.business.google_url)" class="f-link-btn-byron f-ml-3" @click="openUrl(businesses.business.google_url)">
                <div class="f-link-btn-icon f-google-listing-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Google</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
            <div v-if="!isEmpty(businesses.business.website_url)" class="f-link-btn-byron f-ml-3" @click="openUrl(businesses.business.website_url)">
                <div class="f-link-btn-icon f-website-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Website</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
            <div v-if="!isEmpty(businesses.business.facebook_url)" class="f-link-btn-byron f-ml-3" @click="openUrl(businesses.business.facebook_url)">
                <div class="f-link-btn-icon f-facebook-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Facebook</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
            <div v-if="!isEmpty(businesses.business.x_url)" class="f-link-btn-byron f-ml-3" @click="openUrl(businesses.business.x_url)">
                <div class="f-link-btn-icon f-website-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">X</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
            <div v-if="!isEmpty(businesses.business.instagram_url)" class="f-link-btn-byron f-ml-3" @click="openUrl(businesses.business.instagram_url)">
                <div class="f-link-btn-icon f-instagram-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Instagram</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
        </div>

    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$unique_key?>">
            <div class="f-button-seg3" @click="closeManageListingPage()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div class="f-button-seg3" v-if="businesses.business.downgraded == 0" @click="downgradeListing()">
                <div class="f-button-seg-icon f-save-icon"></div>
                <div class="f-button-text">Down Grade</div>
            </div>
            <div class="f-button-seg3" v-else-if="businesses.business.downgraded == 1" @click="upgradeListing()">
                <div class="f-button-seg-icon f-pay-icon"></div>
                <div class="f-button-text">Up Grade</div>
            </div>
            <div class="f-button-seg3 f-act-btn" @click="showEditBusinessListingPage()">
                <div class="f-button-seg-icon f-edit-icon"></div>
                <div class="f-button-text">Edit</div>
            </div>
            <div v-if="businesses.business.downgraded == 1" class="f-button-seg3" @click="showListingCallLog()">
                <div class="f-button-seg-icon f-make-call-icon"></div>
                <div class="f-button-text">Call</div>
            </div>
            <div class="f-button-seg3" @click="deleteListing()">
                <div class="f-button-seg-icon f-delete-icon"></div>
                <div class="f-button-text">Delete</div>
            </div>
        </div>
    </div>

</div>


<div class="f-overlay" :style="{ display: (pages.manageFilter) ? 'flex': 'none' }" 
    @click.self="closeManageFilter()">

    <div class="f-overlay-content">
        <div class="f-overlay-footer" style="
                position: relative !important; 
                margin-top: calc(calc(-200/750) * var(--seg_width));
            ">
            <div class="f-buttons-<?=$unique_key?>">
                <div class="f-button-seg2" @click="applyManageFilter('not_paid')">
                    <div class="f-button-seg-icon f-verified-icon"></div>
                    <div class="f-button-text">Not Paid</div>
                </div>
            </div>
        </div>
        <div class="f-overlay-footer">
            <div class="f-buttons-<?=$unique_key?>">

                <div class="f-button-seg2 " @click="applyManageFilter('paid')">
                    <div class="f-button-seg-icon f-verified-icon"></div>
                    <div class="f-button-text">Paid</div>
                </div>

                <div class="f-button-seg2" @click="applyManageFilter('referred')">
                    <div class="f-button-seg-icon f-referred-icon"></div>
                    <div class="f-button-text">Referred</div>
                </div>

                <div class="f-button-seg2" @click="applyManageFilter('waiting')">
                    <div class="f-button-seg-icon f-waiting-icon"></div>
                    <div class="f-button-text">Waiting</div>
                </div>

                <div class="f-button-seg2" @click="applyManageFilter('rejected')">
                    <div class="f-button-seg-icon f-rejected-icon"></div>
                    <div class="f-button-text">Rejected</div>
                </div>

                <div class="f-button-seg2" @click="resetManageFilter()">
                    <div class="f-button-seg-icon f-verified-icon" v-if="getFilterCondition == 'paid'"></div>
                    <div class="f-button-seg-icon f-referred-icon" v-else-if="getFilterCondition == 'referred'"></div>
                    <div class="f-button-seg-icon f-waiting-icon" v-else-if="getFilterCondition == 'waiting'"></div>
                    <div class="f-button-seg-icon f-rejected-icon" v-else-if="getFilterCondition == 'rejected'"></div>
                    <div class="f-button-seg-icon f-clear-icon" v-else></div>
                    <div class="f-button-text">Clear</div>
                </div>
                
            </div>
        </div>
    </div>

</div>


<div class="page f-page" :style="{ display: pages.mergeListings ? 'flex': 'none' }" >
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$unique_key?> f-fc f-mt-3">
            <div class="f-header-text-full f-ml-3">
                Merge Listings ({{getManageListingsList.length}})
            </div>
        </div>
        <div class="fusion-description f-mt-2">
            Select the main listing to merge others into.
        </div>
    </div>

    <div class="f-body">

        <div class="f-body-contents-full f-mb-3 f-mt-3 f-scroll main-contents-<?=$unique_key?>"
            style="height: calc(var(--s_height) - calc(calc(340/750) * var(--seg_width))) !important;">
            
            <div v-if="mergeMainListing" class="listing-listing f-fc"
                @click="showMergeListingDetails(mergeMainListing)">
                <div class="listing-listing-header f-mt-3 f-ml-3">
                    <div v-if="mergeMainListing.referral" class="listing-listing-header-icon-recommended"
                        :style="businessLogo('<?=$envHost?><?=$community_icon?>')" 
                        ></div>
                    <div v-else-if="isEmpty(mergeMainListing.logo)" class="listing-listing-header-icon-no-iamge"
                        >{{businessNoLogo(mergeMainListing)}}</div>
                    <div v-else class="listing-listing-header-icon" 
                        :style="businessLogo(mergeMainListing.logo, '&w=360&h=360&q=100&zc=6')"
                        ></div>

                    <div class="listing-listing-header-txt-holder f-fc">
                        <div class="listing-listing-header-txt f-ml-2"
                            :class="{'listing-listing-header-txt-mt': mergeMainListing.business_name.length <= 27}"
                            style="font-size: calc(calc(30/750) * var(--seg_width));">
                            {{ mergeMainListing.display_name }}
                        </div>
                    </div>

                    <div class="listing-listing-header-nav-holder">
                        
                        <div class="listing-listing-header-txt-icon f-verified-icon f-mt-1 f-mr-1" v-if="mergeMainListing.paid"
                            style="background-size: calc(calc(40/750) * var(--seg_width));"></div>
                        <div class="listing-listing-header-txt-icon f-fav-heart-icon f-mt-1 f-mr-1" v-if="isFavorite(mergeMainListing)" 
                            style="background-size: calc(calc(35/750) * var(--seg_width));"></div>

                        <div class="listing-listing-header-txt-icon f-color-grey f-mt-1 f-mr1" v-if="mergeMainListing.stars_avg == 0"
                            style="
                                width: max-content;
                                height: calc(calc(55/750) * var(--seg_width));
                                line-height: calc(calc(30/750) * var(--seg_width));
                                font-size: calc(calc(29/750) * var(--seg_width));
                                padding-right: calc(calc(8/750) * var(--seg_width));
                            ">-</div>
                        <div class="listing-listing-header-txt-icon f-grey-star-icon f-mr-1" v-if="mergeMainListing.stars_avg == 0"
                            style="
                                float: left;
                                background-repeat: no-repeat;
                                width: calc(calc(40/750) * var(--seg_width));
                                height: calc(calc(50/750) * var(--seg_width));
                                background-size: calc(calc(40/750) * var(--seg_width));
                                background-position: calc(calc(0/750) * var(--seg_width)) calc(calc(7/750) * var(--seg_width));
                            "></div>

                        <div class="listing-listing-header-txt-icon f-color-grey f-mt-1 f-mr1" v-if="mergeMainListing.stars_avg > 0"
                            style="
                                width: max-content;
                                height: calc(calc(55/750) * var(--seg_width));
                                line-height: calc(calc(30/750) * var(--seg_width));
                                font-size: calc(calc(29/750) * var(--seg_width));
                                padding-right: calc(calc(8/750) * var(--seg_width));
                            ">{{mergeMainListing.stars_avg}}</div>
                        <div class="listing-listing-header-txt-icon f-gold-star-icon f-mr-1" v-if="mergeMainListing.stars_avg > 0"
                            style="
                                float: left;
                                background-repeat: no-repeat;
                                width: calc(calc(40/750) * var(--seg_width));
                                height: calc(calc(50/750) * var(--seg_width));
                                background-size: calc(calc(40/750) * var(--seg_width));
                                background-position: calc(calc(0/750) * var(--seg_width)) calc(calc(7/750) * var(--seg_width));
                            "></div>
                    </div>
                </div>
                <div class="listing-listing-body f-mt-2 f-ml-3">
                    <div class="listing-listing-body-txt f-input-half">
                        Status: {{ mergeMainListing.status }} <br/>
                        Subscription: {{ mergeMainListing.subscription_status }}
                    </div>
                </div>
                <div class="listing-listing-body f-mt-2 f-ml-3">
                    <div class="listing-listing-body-txt">{{ mergeMainListing.description.substring(0, 140) }}</div>
                </div>
                <div class="listing-listing-footer f-ml-3"></div>
            </div>

            <div class="f-list-item f-fc f-mb-2 f-mt-1">
                <div @click="showSelectMergeListing('main')" class="f-list-item-content f-action-btn f-ml-3 f-mb-3 f-act-bg-<?=$unique_key?>">
                    Select Main Business
                </div>
            </div>


            <div v-if="mergeSecondListing" class="listing-listing f-fc"
                @click="showMergeListingDetails(mergeSecondListing)"
                :class="{ 
                    'listing-listing-recommended': mergeSecondListing.referral 
                }">

                <div class="listing-listing-header f-mt-3 f-ml-3"
                    :class="{
                        'listing-listing-recommended': mergeSecondListing.referral
                    }">

                    <div v-if="mergeSecondListing.referral" class="listing-listing-header-icon-recommended"
                        :style="businessLogo('<?=$envHost?><?=$community_icon?>')" 
                        ></div>
                    <div v-else-if="isEmpty(mergeSecondListing.logo)" class="listing-listing-header-icon-no-iamge"
                        >{{businessNoLogo(mergeSecondListing)}}</div>
                    <div v-else class="listing-listing-header-icon" 
                        :style="businessLogo(mergeSecondListing.logo, '&w=360&h=360&q=100&zc=6')"
                        ></div>

                    <div v-if="mergeSecondListing.referral" class="listing-listing-header-txt-holder f-fc">
                        <div class="listing-listing-header-txt f-ml-2 f-db"
                            :class="{'listing-listing-header-txt-mt': mergeSecondListing.business_name.length <= 27}"
                            style="font-size: calc(calc(30/750) * var(--seg_width));">
                            {{ mergeSecondListing.display_name }}
                        </div>
                    </div>
                    <div v-else class="listing-listing-header-txt-holder f-fc">
                        <div class="listing-listing-header-txt f-ml-2"
                            :class="{'listing-listing-header-txt-mt': mergeSecondListing.business_name.length <= 27}"
                            style="font-size: calc(calc(30/750) * var(--seg_width));">
                            {{ mergeSecondListing.display_name }}
                        </div>
                    </div>

                    <div class="listing-listing-header-nav-holder" :class="{'listing-listing-recommended': mergeSecondListing.referral}">
                        
                        <div class="listing-listing-header-txt-icon f-verified-icon f-mt-1 f-mr-1" v-if="mergeSecondListing.paid"
                            style="background-size: calc(calc(40/750) * var(--seg_width));"></div>
                        <div class="listing-listing-header-txt-icon f-fav-heart-icon f-mt-1 f-mr-1" v-if="isFavorite(mergeSecondListing)" 
                            style="background-size: calc(calc(35/750) * var(--seg_width));"></div>

                        <div class="listing-listing-header-txt-icon f-color-grey f-mt-1 f-mr1" v-if="mergeSecondListing.stars_avg == 0"
                            style="
                                width: max-content;
                                height: calc(calc(55/750) * var(--seg_width));
                                line-height: calc(calc(30/750) * var(--seg_width));
                                font-size: calc(calc(29/750) * var(--seg_width));
                                padding-right: calc(calc(8/750) * var(--seg_width));
                            ">-</div>
                        <div class="listing-listing-header-txt-icon f-grey-star-icon f-mr-1" v-if="mergeSecondListing.stars_avg == 0"
                            style="
                                float: left;
                                background-repeat: no-repeat;
                                width: calc(calc(40/750) * var(--seg_width));
                                height: calc(calc(50/750) * var(--seg_width));
                                background-size: calc(calc(40/750) * var(--seg_width));
                                background-position: calc(calc(0/750) * var(--seg_width)) calc(calc(7/750) * var(--seg_width));
                            "></div>

                        <div class="listing-listing-header-txt-icon f-color-grey f-mt-1 f-mr1" v-if="mergeSecondListing.stars_avg > 0"
                            style="
                                width: max-content;
                                height: calc(calc(55/750) * var(--seg_width));
                                line-height: calc(calc(30/750) * var(--seg_width));
                                font-size: calc(calc(29/750) * var(--seg_width));
                                padding-right: calc(calc(8/750) * var(--seg_width));
                            ">{{mergeSecondListing.stars_avg}}</div>
                        <div class="listing-listing-header-txt-icon f-gold-star-icon f-mr-1" v-if="mergeSecondListing.stars_avg > 0"
                            style="
                                float: left;
                                background-repeat: no-repeat;
                                width: calc(calc(40/750) * var(--seg_width));
                                height: calc(calc(50/750) * var(--seg_width));
                                background-size: calc(calc(40/750) * var(--seg_width));
                                background-position: calc(calc(0/750) * var(--seg_width)) calc(calc(7/750) * var(--seg_width));
                            "></div>
                    </div>
                </div>

                <div class="listing-listing-body f-mt-2 f-ml-3"
                    :class="{
                        'listing-listing-recommended': mergeSecondListing.referral
                    }">
                    <div class="listing-listing-body-txt f-input-half">
                        Status: {{ mergeSecondListing.status }} <br/>
                        Subscription: {{ mergeSecondListing.subscription_status }}
                    </div>
                </div>

                <div class="listing-listing-body f-mt-2 f-ml-3"
                    :class="{
                        'listing-listing-recommended': mergeSecondListing.referral
                    }">
                    <div class="listing-listing-body-txt">{{ mergeSecondListing.description.substring(0, 140) }}</div>
                </div>

                <div class="listing-listing-footer f-ml-3"></div>

            </div>
            <div class="f-list-item f-fc f-mb-2 f-mt-1">
                <div @click="showSelectMergeListing('second')" class="f-list-item-content f-action-btn f-ml-3 f-mb-3 f-act-bg-<?=$unique_key?>">
                    Select Merging Business
                </div>
            </div>
        </div>
    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$unique_key?>">
            <div class="f-button-seg2" @click="closeMergeListings()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div class="f-button-seg2 f-act-btn" @click="mergeListings()">
                <div class="f-button-seg-icon f-merge-icon"></div>
                <div class="f-button-text">Merge</div>
            </div>
        </div>
    </div>

</div>


<div class="page f-page" :style="{ display: pages.selectMergeListing ? 'flex': 'none' }" >
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$unique_key?> f-fc f-mt-3">
            <div class="f-header-text-full f-ml-3">
                Select Listing to Merge ({{mergeList.length}})
            </div>
        </div>
    </div>

    <div class="f-body">

        <div class="f-body-contents-search-<?=$unique_key?> f-mt-3">
            <div class="f-input-wrap f-mt-3 f-ml-3 f-mb-3 f-border-bottom">
                <input type="text" class="f-input f-input-left f-pl-2"
                    v-model="mergeListingFilter" placeholder="Search listings ..." >
                <div class="f-action-lnk-<?=$unique_key?> f-action-lnk-search" @click="mergeListingFilter = ''">clear</div>
            </div>
        </div>  

        <div class="f-body-contents-full f-mb-3 f-mt-3 f-scroll main-contents-<?=$unique_key?>"
            style="height: calc(var(--s_height) - calc(calc(340/750) * var(--seg_width))) !important;">

            <div v-for="(business, index) in mergeList" :key="index" 
                v-show="searchMergeListing(business)"
                class="listing-listing f-fc"
                :class="{ 
                    'listing-listing-recommended': business.referral 
                }">

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
                        :style="businessLogo(business.logo, '&w=360&h=360&q=100&zc=6')"
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

                    <div class="listing-listing-header-nav-holder" :class="{'listing-listing-recommended': business.referral}">
                        
                        <div class="listing-listing-header-txt-icon f-verified-icon f-mt-1 f-mr-1" v-if="business.paid"
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
                            "></div>
                    </div>

                </div>

                <div class="listing-listing-body f-mt-2 f-ml-3"
                    :class="{
                        'listing-listing-recommended': business.referral
                    }">
                    <div class="listing-listing-body-txt f-input-half">
                        Status: {{ business.status }} <br/>
                        Subscription: {{ business.subscription_status }}
                    </div>
                    <div class="listing-listing-body-txt f-input-half f-ml-3">
                        <div class="f-action-btn f-mt-1 f-input-half f-act-bg-<?=$unique_key?>" 
                            :id="'mergeListing' + business.id"
                            @click="selectMergeListing(business)">
                            Select
                        </div>
                    </div>
                </div>

                <div class="listing-listing-body f-mt-2 f-ml-3"
                    :class="{
                        'listing-listing-recommended': business.referral
                    }">
                    <div class="listing-listing-body-txt">{{ business.description.substring(0, 140) }}</div>
                </div>

                <div class="listing-listing-footer f-ml-3"></div>
            </div>

        </div>
    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$unique_key?>">
            <div class="f-button" @click="closeSelectMergeListing()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
        </div>
    </div>

</div>


<div class="page f-page" :style="{ display: (pages.editBusinessListing) ? 'flex': 'none' }" >
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
                    class="detailsTab<?=$unique_key?>"
                    >Details</div>
                <div class="f-tab-btn f-mt-1 f-ml-1 servicesTab<?=$unique_key?>" 
                    id="servicesTab<?=$unique_key?>"
                    >Services</div>
                <div class="f-tab-btn f-mt-1 f-ml-1 logoTab<?=$unique_key?>" 
                    id="logoTab<?=$unique_key?>"
                    >Logo</div>
                <div class="f-tab-btn f-mt-1 f-ml-1 channelsTab<?=$unique_key?>" 
                    id="channelsTab<?=$unique_key?>"
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
                <input type="text" class="f-input f-input-left" v-model="businesses.business.google_url" placeholder="Google Business URL " >
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
                <input type="text" class="f-input f-input-left" v-model="businesses.business.website_url" placeholder="Website URL" >
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
                <input type="text" class="f-input f-input-left" v-model="businesses.business.facebook_url" placeholder="Facebook URL" >
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
                <input type="text" class="f-input f-input-left" v-model="businesses.business.x_url" placeholder="X URL" >
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
                <input type="text" class="f-input f-input-left" v-model="businesses.business.instagram_url" placeholder="Instagram URL" >
                <div class="f-action-lnk-<?=$unique_key?>" 
                    v-if="!isEmpty(businesses.business.instagram_url)"
                    @click="businesses.business.instagram_url = ''">clear</div>
            </div>
        </div>
        
    </div>

    <!-- <div class="f-footer">
        <div class="f-buttons-<?=$unique_key?>">
            <div class="f-button-seg2" @click="closeEditBusinessListingPage()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div class="f-button-seg2" @click="updateBusiness()">
                <div class="f-button-seg-icon f-save-icon"></div>
                <div class="f-button-text">Save</div>
            </div>
        </div>
    </div> -->
    
    <div class="f-footer">
        <div class="f-buttons-<?=$unique_key?>">
            <div v-if="pages.navTab == 'details'" class="f-button-seg3" @click="closeEditBusinessListingPage()">
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
            <div class="f-button-seg3" @click="nextTab()">
                <div class="f-button-seg-icon f-next-icon"></div>
                <div class="f-button-text">Next</div>
            </div>
        </div>
    </div>

</div>


<div class="page f-page" :style="{ display: pages.listingCallLog ? 'flex': 'none' }" >
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$unique_key?> f-fc f-mt-3">
            <div class="f-header-text-full f-ml-3">
                {{businesses.business.display_name}} Call Log ({{getReferralsCallLogList.length}})
            </div>
        </div>
        <div class="fusion-description f-mt-2">
            Click on a call log to add a comment.
        </div>
    </div>

    <div class="f-body">

        <div class="f-body-contents-full f-mb-3 f-scroll main-contents-<?=$unique_key?>">

            <div v-for="(log_item, index) in getReferralsCallLogList" :key="index" 
                class="listing-listing f-fc"
                @click="showListingCallLogComment(log_item)">
                <div class="listing-listing-header f-mt-3 f-ml-3">
                    <div class="listing-listing-header-txt"
                        style="
                            font-size: calc(calc(30/750) * var(--seg_width));
                            width: calc(calc(690/750) * var(--seg_width));
                        ">
                        {{ formatDateTime(log_item.added) }} 
                    </div>
                </div>
                <div class="listing-listing-body f-mt-1 f-ml-3">
                    <div class="listing-listing-body-txt">{{ log_item.name }} {{ log_item.surname }}</div>
                </div>
                <div class="listing-listing-body f-mt-1 f-ml-3">

                    <div v-if="log_item.comment == ''" class="f-input-wrap">
                        <div class="f-list-item-side" 
                            style="width: calc(calc(300/750) * var(--seg_width)) !important; justify-content: left;"> 
                            <div class="f-action-btn f-act-bg-<?=$unique_key?> f-mt-1"
                                style="
                                    background-color: rgb(9, 141, 183) !important;
                                    border-radius: calc(calc(55/750) * var(--seg_width));
                                    padding-right: calc(calc(30/750) * var(--seg_width));
                                    padding-left: calc(calc(30/750) * var(--seg_width));
                                    color: white;
                                    width: fit-content;
                                    height: calc(calc(55/750) * var(--seg_width));
                                    line-height: calc(calc(55/750) * var(--seg_width));
                                    font-size: calc(calc(28/750) * var(--seg_width));
                                    margin-top: calc(calc(0/750) * var(--seg_width)) !important;
                                "
                            >Add Comment</div>
                        </div>
                    </div>

                    <div v-else class="listing-listing-body-txt">{{ log_item.comment }}</div>

                </div>
                <div class="listing-listing-footer f-ml-3"></div>
            </div>

        </div>
    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$unique_key?>">
            <div class="f-button-seg3" @click="closeListingCallLog()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div class="f-button-seg3" @click="showListingCallWhatsApp()">
                <div class="f-button-seg-icon f-make-call-icon"></div>
                <div class="f-button-text">Call</div>
            </div>
            <div class="f-button-seg3" @click="scrollToTop()">
                <div class="f-button-seg-icon f-to-top-icon"></div>
                <div class="f-button-text">To Top</div>
            </div>
            <div class="f-button-seg3" @click="deleteReferral()">
                <div class="f-button-seg-icon f-delete-icon"></div>
                <div class="f-button-text">Delete</div>
            </div>
        </div>
    </div>

</div>


<div class="f-overlay" :style="{ display: (pages.listingCallLogComment) ? 'flex': 'none' }" 
    @click.self="closeListingCallLogComment()">
    <div class="f-overlay-content">
        <div class="f-overlay-header">
            <div class="fusion-heading f-heading-<?=$unique_key?> f-mt-3 f-mb-3">
                <div class="f-header-text-full">
                    Add Comment
                </div>
            </div>
        </div>

        <div class="f-overlay-body">
            <div class="f-overlay-body-contents f-scroll f-mt-3">

                <div class="fusion-description f-mt-3 f-ml-0 f-mb-0">Enter your comment below.</div>
                <div class="f-input-wrap">
                    <textarea class="f-input" rows="3" v-model="referrals.callLogComment"></textarea>
                </div>

                <div class="f-input-wrap">
                    <div class="f-list-item-left f-fc" style="width: calc(calc(390/750) * var(--seg_width)) !important;"></div>
                    <div class="f-list-item-side" 
                        @click="addCallLogComment()"
                        style="width: calc(calc(300/750) * var(--seg_width)) !important; justify-content: right;"> 
                        <div class="f-action-btn f-act-bg-<?=$unique_key?> f-mt-1">Post Comment</div>
                    </div>
                </div>

                <div class="f-input-wrap f-mb-3"></div>

            </div>
        </div>

        <div class="f-overlay-footer">
            <div class="f-buttons-<?=$unique_key?>">
                <div class="f-button" @click="closeListingCallLogComment()">
                    <div class="f-button-seg-icon f-back-icon"></div>
                    <div class="f-button-text">Back</div>
                </div>
            </div>
        </div>

    </div>
</div>


<div class="f-overlay" :style="{ display: (pages.listingCallWhatsApp) ? 'flex': 'none' }" 
    @click.self="closeListingCallWhatsApp()">
    <div class="f-overlay-content">
        <div class="f-overlay-header">
            <div class="fusion-heading f-heading-<?=$unique_key?> f-mt-3 f-mb-3">
                <div class="f-header-text-full">
                    {{businesses.business.display_name}}
                </div>
            </div>
        </div>

        <div class="f-overlay-body">
            <div class="f-overlay-body-contents f-scroll f-mt-3">
                <div class="f-input-wrap f-mt-0">
                    <div class="f-link-btn" @click="addReferralCallLog('call')">
                        <div class="f-link-btn-icon f-contact-icon f-mt-2 f-ml-2"></div>
                        <div class="f-link-btn-text f-mt-2 f-ml-2">Call</div>
                        <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2">{{members.member.name}}</div>
                    </div>
                    <div class="f-link-btn f-ml-3" @click="addReferralCallLog('whatsapp')">
                        <div class="f-link-btn-icon f-whatsapp-icon f-mt-2 f-ml-2"></div>
                        <div class="f-link-btn-text f-mt-2 f-ml-2">WhatsApp</div>
                        <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2">{{members.member.name}}</div>
                    </div>
                </div>
                <div class="f-input-wrap"></div>
            </div>
        </div>

        <div class="f-overlay-footer">
            <div class="f-buttons-<?=$unique_key?>">
                <div class="f-button" @click="closeListingCallWhatsApp()">
                    <div class="f-button-seg-icon"
                        style="background-image: url('/modules/members-management-v2/images/back_icon.svg')"></div>
                    <div class="f-button-text">Back</div>
                </div>
            </div>
        </div>

    </div>
</div>

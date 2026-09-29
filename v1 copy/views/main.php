
<div class="page f-page" v-show="pages.main" >
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$unique_key?> f-fr f-mt-3">
            <div class="f-header-text f-ml-3" @click="showBusinessDirectory()">
                Physio Directory
            </div>
            <div class="f-header-icons f-mt-">
                <div class="f-action-btn f-act-bg-<?=$unique_key?> f-act-btn"
                    style="
                        font-size: calc(calc(28/750) * var(--seg_width)) !important;
                        height: calc(calc(60/750) * var(--seg_width)) !important;
                        margin-top: calc(calc(-7/750) * var(--seg_width)) !important;
                    "
                    @click="showMyBusinessesPage()"
                    v-if="getMyListingsList.length > 0">Paid Listing</div>

                <div class="f-action-btn f-act-bg-<?=$unique_key?> f-act-btn"
                    style="
                        font-size: calc(calc(28/750) * var(--seg_width)) !important;
                        height: calc(calc(60/750) * var(--seg_width)) !important;
                        margin-top: calc(calc(-7/750) * var(--seg_width)) !important;
                    "
                    @click="showMyBusinessesPage()"
                    v-else>Paid Listing</div>
            </div>
        </div>
        <div class="community-management-description f-ml-3" v-if="permissions.admin">Tap here to manage listings.</div>

        <div class="fusion-description f-mt-2">
            Click on a listing to view more info.
        </div>
        <div class="fusion-description f-mt-0 f-df f-fr" style="margin-top: calc(calc(-30/750) * var(--seg_width)) !important;">
            <!-- <i class="f-shield-icon f-verified-icon f-df"></i> -->
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
                    change="searchBusinessByName"
                    v-model="businesses.mainFilter" placeholder="Search listing by keyword..." >
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

            <div v-if="businesses.sort.dateAscending" class="f-button-seg3 date-sort sort-item-background" @click="applySort('date')">
                <div class="f-button-seg-icon f-sort-date-jan-dec-icon"></div>
                <div class="f-button-text">Date</div>
            </div>
            <div v-else class="f-button-seg3 date-sort sort-item-background" @click="applySort('date')">
                <div class="f-button-seg-icon f-sort-date-dec-jan-icon"></div>
                <div class="f-button-text">Date</div>
            </div>

            <div v-if="businesses.sort.nameAscending" class="f-button-seg3 name-sort" @click="applySort('name')">
                <div class="f-button-seg-icon f-sort-za-icon"></div>
                <div class="f-button-text">Name</div>
            </div>
            <div v-else class="f-button-seg3 name-sort" @click="applySort('name')">
                <div class="f-button-seg-icon f-sort-az-icon"></div>
                <div class="f-button-text">Name</div>
            </div>

            <div v-if="businesses.sort.ratingAscending" class="f-button-seg3 rating-sort" @click="applySort('rating')">
                <div class="f-button-seg-icon f-star-rating-dec-icon"></div>
                <div class="f-button-text">Rating</div>
            </div>
            <div v-else class="f-button-seg3 rating-sort" @click="applySort('rating')">
                <div class="f-button-seg-icon f-star-rating-asc-icon "></div>
                <div class="f-button-text">Rating</div>
            </div>
        </div>

        <div class="f-body-contents-full f-mb-3 f-scroll main-contents-<?=$unique_key?>" 
            style="height: calc(var(--s_height) - calc(calc(510/750) * var(--seg_width))) !important;">

            <div v-if="getListingsList.length == 0 && loading.businesses" 
                class="f-list-item" style="border-bottom: 0px;">
                <div class="f-list-item-wrap f-mt-3 f-ml-3">
                    <div class="loader-<?=$unique_key?>"></div> 
                    <div class="loader-text">Loading ...</div>
                </div>
            </div>

            <div v-else-if="getListingsList.length == 0" 
                class="f-list-item" style="border-bottom: 0px;">
                <div class="f-list-item-wrap f-mt-3 f-ml-3">
                    No Results found. &#128515;
                </div>
            </div>

            <!-- v-show="searchBusinessByName(business)" -->

            <div v-else v-for="(listing, index) in getListingsList" :key="index" 
                class="listing-listing f-fc" 
                :class="{
                    'listing-listing-recommended': listing.referral
                }"
                @click="showBusinessPage(listing)">

                <div class="listing-listing-header f-mt-3 f-ml-3"
                    :class="{
                        'listing-listing-recommended': listing.referral
                    }"
                    >

                    <div v-if="listing.referral" class="listing-listing-header-icon-recommended"
                        :style="businessLogo('<?=$envHost?><?=$community_icon?>')" 
                        ></div>
                    <div v-else-if="isEmpty(listing.logo)" class="listing-listing-header-icon-no-iamge"
                        >{{businessNoLogo(listing)}}</div>
                    <div v-else class="listing-listing-header-icon" 
                        :style="businessLogo(listing.logo, '&w=360&q=100&zc=6')"
                        ></div>

                    <div v-if="listing.referral" class="listing-listing-header-txt-holder f-fc">
                        <div class="listing-listing-header-txt f-ml-2 f-db"
                            :class="{'listing-listing-header-txt-mt': listing.business_name.length <= 27}"
                            style="font-size: calc(calc(30/750) * var(--seg_width));">
                            {{ listing.display_name }}
                        </div>
                    </div>
                    <div v-else class="listing-listing-header-txt-holder f-fc">
                        <div class="listing-listing-header-txt f-ml-2"
                            :class="{'listing-listing-header-txt-mt': listing.business_name.length <= 27}"
                            style="font-size: calc(calc(30/750) * var(--seg_width));">
                            {{ listing.display_name }}
                        </div>
                    </div>

                    <div class="listing-listing-header-nav-holder" :class="{'listing-listing-recommended': listing.referral}"
                        style="width: calc(calc(80/750) * var(--seg_width)) !important;">

                        <div class="listing-listing-header-txt-icon f-verified-icon f-mt-1 f-mr-1" v-if="listing.paid"
                            style="background-size: calc(calc(40/750) * var(--seg_width));"></div>
                        <div class="listing-listing-header-txt-icon f-fav-heart-icon f-mt-1 f-mr-1" v-if="isFavorite(listing)" 
                            style="background-size: calc(calc(35/750) * var(--seg_width));"></div>

                    </div>

                    <div class="listing-listing-header-nav-holder f-fc" :class="{'listing-listing-recommended': listing.referral}"
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

                <div class="listing-listing-body f-mt-2 f-ml-3"
                    :class="{
                        'listing-listing-recommended': listing.referral
                    }">
                    <div class="listing-listing-body-txt">{{ listing.description.substring(0, 140) }}</div>
                </div>

                <div class="listing-listing-footer f-ml-3"></div>
            </div>

        </div>
        
    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$unique_key?>">
            <div class="f-button-seg4" @click="exit()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Close</div>
            </div>
            <div v-if="!screen.mainTopBottom" class="f-button-seg4" @click="toggleMainTopBottom()">
                <div class="f-button-seg-icon f-to-top-icon"></div>
                <div class="f-button-text">To Top</div>
            </div>
            <div v-else class="f-button-seg4" @click="toggleMainTopBottom()">
                <div class="f-button-seg-icon f-to-bottom-icon"></div>
                <div class="f-button-text">To Bottom</div>
            </div>
            <div class="f-button-seg4 f-act-btn" @click="showAddFreeListing()">
                <div class="f-button-seg-icon f-add-icon"></div>
                <div class="f-button-text">New Listing</div>
            </div>
            <div class="f-button-seg4" @click="showFilter()">
                <div class="f-button-seg-icon f-fav-heart-icon" v-if="getFilterCondition == 'faves'"></div>
                <div class="f-button-seg-icon f-verified-icon" v-else-if="getFilterCondition == 'paid'"></div>
                <div class="f-button-seg-icon f-gold-star-icon" v-else-if="getFilterCondition == 'rated'"></div>
                <div class="f-button-seg-icon f-grey-star-icon" v-else-if="getFilterCondition == 'not_rated'"></div>
                <div class="f-button-seg-icon f-filter-icon" v-else></div>
                <div class="f-button-text">Filter</div>
            </div>
        </div>
    </div>

</div>


<div class="f-overlay" :style="{ display: (pages.filter) ? 'flex': 'none' }" 
    @click.self="closeFilter()">
    <div class="f-overlay-content">
        <div class="f-overlay-footer">
            <div class="f-buttons-<?=$unique_key?>">

                <div class="f-button-seg2" @click="applyFilter('faves')">
                    <div class="f-button-seg-icon f-fav-heart-icon"></div>
                    <div class="f-button-text">Faves</div>
                </div>

                <div class="f-button-seg2 " @click="applyFilter('paid')">
                    <div class="f-button-seg-icon f-verified-icon"></div>
                    <div class="f-button-text">Paid</div>
                </div>

                <div class="f-button-seg2" @click="applyFilter('rated')">
                    <div class="f-button-seg-icon f-gold-star-icon"></div>
                    <div class="f-button-text">Rated</div>
                </div>

                <div class="f-button-seg2" @click="applyFilter('not_rated')">
                    <div class="f-button-seg-icon f-grey-star-icon"></div>
                    <div class="f-button-text">Not Rated</div>
                </div>

                <div class="f-button-seg2" @click="resetFilter()">
                    <div class="f-button-seg-icon f-clear-icon" v-if="getFilterCondition == 'faves'"></div>
                    <div class="f-button-seg-icon f-clear-icon" v-else-if="getFilterCondition == 'paid'"></div>
                    <div class="f-button-seg-icon f-clear-icon" v-else-if="getFilterCondition == 'rated'"></div>
                    <div class="f-button-seg-icon f-clear-icon" v-else-if="getFilterCondition == 'not_rated'"></div>
                    <div class="f-button-seg-icon f-clear-icon" v-else></div>
                    <div class="f-button-text">Clear</div>
                </div>
                
            </div>
        </div>
    </div>
</div>


<div class="page f-page" :style="{ display: pages.directory ? 'flex': 'none' }" >
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$unique_key?> f-fc f-mt-3">
            <div class="f-header-text-full f-ml-3">
                Physio Directory
            </div>
            <div class="community-management-description f-ml-3">Management</div>
        </div>
    </div>
    <div class="f-border-line"></div>
    <div class="f-body">
        <div class="f-link-btn-wrap">
            <div class="f-link-btn-byron f-ml-3" @click="showMembers()">
                <div class="f-link-btn-icon f-member-blacklist-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Member</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2">Muting</div>
            </div>
            <!-- <div class="f-link-btn-byron f-ml-3" @click="showReferralsAdmin()">
                <div class="listing-info-badge">{{admin_dash_stats.referrals}}</div>
                <div class="f-link-btn-icon f-listing-listing-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Community</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2">Leads</div>
            </div>
            <div class="f-link-btn-byron f-ml-3" @click="showApprovals()">
                <div class="listing-info-badge">{{admin_dash_stats.waiting_approvals}}</div>
                <div class="f-link-btn-icon f-approvals-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Business</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2">Approvals</div>
            </div>
            <div class="f-link-btn-byron f-ml-3" @click="showManageListings()">
                <div class="listing-info-badge">{{admin_dash_stats.businesses}}</div>
                <div class="f-link-btn-icon f-approvals-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Manage</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2">Listings</div>
            </div>
            <div class="f-link-btn-byron f-ml-3" @click="showDowngradedListings()">
                <div class="listing-info-badge">{{admin_dash_stats.downgraded}}</div>
                <div class="f-link-btn-icon f-approvals-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Downgraded</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2">Listings</div>
            </div> -->
            <div class="f-link-btn-byron f-ml-3" @click="showProposition()">
                <div class="f-link-btn-icon f-usp-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Listing</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2">Proposition</div>
            </div>
            <!-- <div class="f-link-btn-byron f-ml-3" @click="showPaymentLogsPage()">
                <div class="f-link-btn-icon f-usp-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Payment</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2">Log</div>
            </div> -->
        </div>
    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$unique_key?>">
            <div class="f-button" @click="closeBusinessDirectory()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
        </div>
    </div>

</div>


<!-- 
:class="{
    'listing-listing-recommended': business.recommended
}" 
-->


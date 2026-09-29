

<div class="page f-page" :style="{ display: pages.adminListings ? 'flex': 'none' }" >
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$uniqueKey?> f-fc f-mt-3">
            <div class="f-header-text-full f-ml-3">
                Manage Listings ({{getAdminListingsList.length}})
            </div>
        </div>
        <div class="fusion-description f-mt-2">
            Click on a listing to view more info.
        </div>
    </div>

    <div class="f-body">

        <div class="f-body-contents-search-<?=$uniqueKey?> f-fr">
            <div class="f-input-wrap-byron f-mt-3 f-ml-3 f-mb-3 f-border-bottom">
                <input type="text" 
                    change="seachBusinessByName"
                    v-model="filters.all" 
                    class="f-input f-input-left-search f-pl-2"
                    placeholder="Search listing by keyword..." >
                <div class="f-action-lnk-<?=$uniqueKey?> f-action-lnk-search" @click="filters.all = ''">clear</div>
            </div>
        </div>

        <div class="f-body-contents-full f-mb-3 f-scroll main-contents-<?=$uniqueKey?>"
            style="height: calc(var(--s_height) - calc(calc(435/750) * var(--seg_width))) !important;">

            <div v-if="getAdminListingsList.length == 0 && loading.all" 
                class="f-list-item" style="border-bottom: 0px;">
                <div class="f-list-item-wrap f-mt-3 f-ml-3">
                    <div class="loader-<?=$uniqueKey?>"></div> 
                    <div class="loader-text">Loading ...</div>
                </div>
            </div>

            <div v-else-if="getAdminListingsList.length == 0" 
                class="f-list-item" style="border-bottom: 0px;">
                <div class="f-list-item-wrap f-mt-3 f-ml-3">
                    There are no listings yet. &#128515;
                </div>
            </div>

            <div v-else v-for="(listing, index) in getAdminListingsList" :key="index">
                <div v-if="listing.free == 0" class="f-list-item f-fc" @click="showManageListingItem(listing)">
                    <listing-paid-card :listing="listing" :urls="urls" :favorites="getFavoritesList"></listing-paid-card>
                </div>
                <div v-else class="f-list-item f-fc listing-listing-recommended" @click="showManageListingItem(listing)">
                    <listing-free-card :listing="listing" :favorites="getFavoritesList"></listing-free-card>
                </div>
            </div>

        </div>
    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$uniqueKey?>">
            <div class="f-button-seg3" @click="closeManageListings()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div class="f-button-seg3 f-act-btn" @click="showAdminMergeListings()">
                <div class="f-button-seg-icon f-merge-icon"></div>
                <div class="f-button-text">Merge</div>
            </div>
            <div class="f-button-seg4" @click="showAdminFilter()">
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


<div class="f-overlay" v-show="pages.adminFilter" @click.self="closeAdminFilter()">
    <div class="f-overlay-content">
        <div class="f-overlay-footer">
            <div class="f-buttons-<?=$uniqueKey?>">

                <div class="f-button-seg2" @click="applyAdminFilter('faves')">
                    <div class="f-button-seg-icon f-fav-heart-icon"></div>
                    <div class="f-button-text">Faves</div>
                </div>

                <div class="f-button-seg2 " @click="applyAdminFilter('paid')">
                    <div class="f-button-seg-icon f-verified-icon"></div>
                    <div class="f-button-text">Paid</div>
                </div>

                <div class="f-button-seg2" @click="applyAdminFilter('rated')">
                    <div class="f-button-seg-icon f-gold-star-icon"></div>
                    <div class="f-button-text">Rated</div>
                </div>

                <div class="f-button-seg2" @click="applyAdminFilter('not_rated')">
                    <div class="f-button-seg-icon f-grey-star-icon"></div>
                    <div class="f-button-text">Not Rated</div>
                </div>

                <div class="f-button-seg2" @click="resetAdminFilter()">
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


<div class="page f-page" :style="{ display: (pages.adminListingItem) ? 'flex': 'none' }" >
    <div class="f-body-no-header">

        <listing-header-card 
            :listing="listing" 
            :urls="urls" 
            :settings="settings"
            :favorites="getFavoritesList">
        </listing-header-card>

        <listing-buttons
            :listing="listing"
            :is-creator="isContentCreator(listing)"
            :is-admin="permissions.admin"

            @call="callBusiness($event)"
            @whatsapp="whatsAppBusiness($event)"
            @email="emailBusiness($event)"
            @url="openBusinessUrl($event)"
            @open-specials="openMySpecialsModule()"
            @open-comments="showComments($event)"
            @open-cv="openPDFDocument($event)"
        ></listing-buttons>

    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$uniqueKey?>">
            <div class="f-button-seg4" @click="closeManageListingItem()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div class="f-button-seg4" v-if="listing.downgraded == 0" @click="downgradeListing()">
                <div class="f-button-seg-icon f-save-icon"></div>
                <div class="f-button-text">Down Grade</div>
            </div>
            <div class="f-button-seg4" v-else-if="listing.downgraded == 1" @click="showUpgradeOptionsPage()">
                <div class="f-button-seg-icon f-pay-icon"></div>
                <div class="f-button-text">Up Grade</div>
            </div>
            <div class="f-button-seg4 f-act-btn" @click="showEditAdminListing()">
                <div class="f-button-seg-icon f-edit-icon"></div>
                <div class="f-button-text">Edit</div>
            </div>
            <div v-if="listing.downgraded == 1" class="f-button-seg4" @click="showAdminFreeListingCallLog(listing)">
                <div class="f-button-seg-icon f-make-call-icon"></div>
                <div class="f-button-text">Call</div>
            </div>
            <div class="f-button-seg4" @click="deleteListing()">
                <div class="f-button-seg-icon f-delete-icon"></div>
                <div class="f-button-text">Delete</div>
            </div>
        </div>
    </div>
</div>



<div class="page f-page" :style="{ display: pages.adminMergeListings ? 'flex': 'none' }" >
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$uniqueKey?> f-fc f-mt-3">
            <div class="f-header-text-full f-ml-3">
                Merge Listings ({{getAdminListingsList.length}})
            </div>
        </div>
        <div class="fusion-description f-mt-2">
            Select the main listing to merge others into.
        </div>
    </div>

    <div class="f-body">

        <div class="f-body-contents-full f-mb-3 f-mt-3 f-scroll main-contents-<?=$uniqueKey?>"
            style="height: calc(var(--s_height) - calc(calc(340/750) * var(--seg_width))) !important;">
            
            <div v-if="merge.main" class="listing-listing f-fc"
                @click="showMergeListingDetails(merge.main)">

                <div class="listing-listing-header f-mt-3 f-ml-3">

                    <div v-if="merge.main.free || isEmpty(merge.main.logo)" class="listing-listing-header-icon-recommended"
                        :style="listingLogo('<?=$envHost?><?=$icon?>')"></div>
                    <div v-else class="listing-listing-header-icon" 
                        :style="listingLogo(merge.main.logo, '&w=360&q=100&zc=6')"></div>

                    <div class="listing-listing-header-txt-holder f-fc">
                        <div class="listing-listing-header-txt f-ml-2"
                            :class="{'listing-listing-header-txt-mt': merge.main.name.length <= 27}"
                            style="font-size: calc(calc(30/750) * var(--seg_width));">
                            {{ merge.main.display_name }}
                        </div>
                    </div>

                    <div class="listing-listing-header-nav-holder" :class="{'listing-listing-recommended': merge.main.free}"
                        style="width: calc(calc(80/750) * var(--seg_width)) !important;">

                        <div class="listing-listing-header-txt-icon f-verified-icon f-mt-1 f-mr-1" v-if="merge.main.paid"
                            style="background-size: calc(calc(40/750) * var(--seg_width));"></div>
                        <div class="listing-listing-header-txt-icon f-fav-heart-icon f-mt-1 f-mr-1" 
                            style="background-size: calc(calc(35/750) * var(--seg_width));"></div>

                    </div>

                    <div class="listing-listing-header-nav-holder f-fc" :class="{'listing-listing-recommended': merge.main.free}"
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
                            
                            <div v-if="merge.main.stars_avg == 0" 
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
                                ">{{ merge.main.stars_avg }}</div>
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
                                ">{{ merge.main.comments }} Ratings</div>
                        </div>
                    </div>
                </div>

                <div class="listing-listing-body f-mt-2 f-ml-3">
                    <div class="listing-listing-body-txt f-input-half">
                        Status: {{ merge.main.status }} <br/>
                        Subscription: {{ merge.main.subscription_status }}
                    </div>
                </div>

                <div class="listing-listing-body f-mt-2 f-ml-3">
                    <div class="listing-listing-body-txt">{{ merge.main.description.substring(0, 140) }}</div>
                </div>

                <div class="listing-listing-footer f-ml-3"></div>
            </div>
            <div class="f-list-item f-fc f-mb-2 f-mt-1">
                <div @click="showAdminSelectMergeListing('main')" class="f-list-item-content f-action-btn f-ml-3 f-mb-3 f-act-bg-<?=$uniqueKey?>">
                    Select Main Listing
                </div>
            </div>

            <div v-if="merge.second" class="listing-listing f-fc"
                :class="{
                    'listing-listing-recommended': merge.second.free
                }"
                @click="showManageListingItem(merge.second)">

                <div class="listing-listing-header f-mt-3 f-ml-3"
                    :class="{
                        'listing-listing-recommended': merge.second.free
                    }">

                    <div v-if="merge.second.free || isEmpty(merge.second.logo)" class="listing-listing-header-icon-recommended"
                        :style="listingLogo('<?=$envHost?><?=$icon?>')" 
                        ></div>
                    <div v-else class="listing-listing-header-icon" 
                        :style="listingLogo(merge.second.logo, '&w=360&q=100&zc=6')"
                        ></div>

                    <div v-if="merge.second.free" class="listing-listing-header-txt-holder f-fc">
                        <div class="listing-listing-header-txt f-ml-2 f-db"
                            :class="{'listing-listing-header-txt-mt': merge.second.name.length <= 27}"
                            style="font-size: calc(calc(30/750) * var(--seg_width));">
                            {{ merge.second.display_name }}
                        </div>
                    </div>
                    <div v-else class="listing-listing-header-txt-holder f-fc">
                        <div class="listing-listing-header-txt f-ml-2"
                            :class="{'listing-listing-header-txt-mt': merge.second.name.length <= 27}"
                            style="font-size: calc(calc(30/750) * var(--seg_width));">
                            {{ merge.second.display_name }}
                        </div>
                    </div>

                    <div class="listing-listing-header-nav-holder" :class="{'listing-listing-recommended': merge.second.free}"
                        style="width: calc(calc(80/750) * var(--seg_width)) !important;">

                        <div class="listing-listing-header-txt-icon f-verified-icon f-mt-1 f-mr-1" v-if="merge.second.paid"
                            style="background-size: calc(calc(40/750) * var(--seg_width));"></div>
                        <div class="listing-listing-header-txt-icon f-fav-heart-icon f-mt-1 f-mr-1" 
                            style="background-size: calc(calc(35/750) * var(--seg_width));"></div>

                    </div>

                    <div class="listing-listing-header-nav-holder f-fc" :class="{'listing-listing-recommended': merge.second.free}"
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
                            
                            <div v-if="merge.second.stars_avg == 0" 
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
                                ">{{ merge.second.stars_avg }}</div>
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
                                ">{{ merge.second.comments }} Ratings</div>
                        </div>
                    </div>

                </div>

                <div class="listing-listing-body f-mt-2 f-ml-3"
                    :class="{
                        'listing-listing-recommended': merge.second.free
                    }">
                    <div class="listing-listing-body-txt">{{ merge.second.description.substring(0, 140) }}</div>
                </div>

                <div class="listing-listing-footer f-ml-3"></div>
            </div>
            <div class="f-list-item f-fc f-mb-2 f-mt-1">
                <div @click="showAdminSelectMergeListing('second')" class="f-list-item-content f-action-btn f-ml-3 f-mb-3 f-act-bg-<?=$uniqueKey?>">
                    Select Merging Listing
                </div>
            </div>
        </div>
    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$uniqueKey?>">
            <div class="f-button-seg2" @click="closeAdminMergeListings()">
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


<div class="page f-page" :style="{ display: pages.adminSelectMergeListing ? 'flex': 'none' }" >
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$uniqueKey?> f-fc f-mt-3">
            <div class="f-header-text-full f-ml-3">
                Select Listing to Merge ({{listings.merge.length}})
            </div>
        </div>
    </div>

    <div class="f-body">

        <div class="f-body-contents-search-<?=$uniqueKey?> f-fr">
            <div class="f-input-wrap-byron f-mt-3 f-ml-3 f-mb-3 f-border-bottom">
                <input type="text" 
                    change="seachBusinessByName"
                    v-model="filters.merge" 
                    class="f-input f-input-left-search f-pl-2"
                    placeholder="Search listing by keyword..." >
                <div class="f-action-lnk-<?=$uniqueKey?> f-action-lnk-search" @click="filters.merge = ''">clear</div>
            </div>
        </div> 

        <div class="f-body-contents-full f-mb-3 f-mt-3 f-scroll main-contents-<?=$uniqueKey?>"
            style="height: calc(var(--s_height) - calc(calc(340/750) * var(--seg_width))) !important;">

            <div v-for="(listing, index) in listings.merge" :key="index" class="listing-listing f-fc" 
                :class="{
                    'listing-listing-recommended': listing.free
                }">

                <div class="listing-listing-header f-mt-3 f-ml-3"
                    :class="{
                        'listing-listing-recommended': listing.free
                    }">

                    <div v-if="listing.free || isEmpty(listing.logo)" class="listing-listing-header-icon-recommended"
                        :style="listingLogo('<?=$envHost?><?=$icon?>')" 
                        ></div>
                    <div v-else class="listing-listing-header-icon" 
                        :style="listingLogo(listing.logo, '&w=360&q=100&zc=6')"
                        ></div>

                    <div v-if="listing.free" class="listing-listing-header-txt-holder f-fc">
                        <div class="listing-listing-header-txt f-ml-2 f-db"
                            :class="{'listing-listing-header-txt-mt': listing.name.length <= 27}"
                            style="font-size: calc(calc(30/750) * var(--seg_width));">
                            {{ listing.display_name }}
                        </div>
                    </div>
                    <div v-else class="listing-listing-header-txt-holder f-fc">
                        <div class="listing-listing-header-txt f-ml-2"
                            :class="{'listing-listing-header-txt-mt': listing.name.length <= 27}"
                            style="font-size: calc(calc(30/750) * var(--seg_width));">
                            {{ listing.display_name }}
                        </div>
                    </div>

                    <div class="listing-listing-header-nav-holder" :class="{'listing-listing-recommended': listing.free}"
                        style="width: calc(calc(80/750) * var(--seg_width)) !important;">

                        <div class="listing-listing-header-txt-icon f-verified-icon f-mt-1 f-mr-1" v-if="listing.paid"
                            style="background-size: calc(calc(40/750) * var(--seg_width));"></div>
                        <div class="listing-listing-header-txt-icon f-fav-heart-icon f-mt-1 f-mr-1" 
                            style="background-size: calc(calc(35/750) * var(--seg_width));"></div>

                    </div>

                    <div class="listing-listing-header-nav-holder f-fc" :class="{'listing-listing-recommended': listing.free}"
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
                        'listing-listing-recommended': listing.free
                    }">
                    <div class="listing-listing-body-txt f-input-half">
                        Status: {{ listing.status }} <br/>
                        Subscription: {{ listing.subscription_status }}
                    </div>
                    <div class="listing-listing-body-txt f-input-half f-ml-3">
                        <div class="f-action-btn f-mt-1 f-input-half f-act-bg-<?=$uniqueKey?>" 
                            :id="'mergeListing' + listing.id"
                            @click="selectAdminMergeListing(listing)">
                            Select
                        </div>
                    </div>
                </div>

                <div class="listing-listing-body f-mt-2 f-ml-3"
                    :class="{
                        'listing-listing-recommended': listing.free
                    }">
                    <div class="listing-listing-body-txt">{{ listing.description.substring(0, 140) }}</div>
                </div>

                <div class="listing-listing-footer f-ml-3"></div>
            </div>

        </div>
    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$uniqueKey?>">
            <div class="f-button" @click="closeAdminSelectMergeListing()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
        </div>
    </div>

</div>

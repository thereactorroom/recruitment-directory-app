


<div class="page f-page" :style="{ display: (pages.addFreeListing) ? 'flex': 'none' }" >
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$unique_key?> f-mt-3">
            <div class="f-header-text-full f-ml-3">
                Physio Directory
            </div>
        </div>
        <div class="fusion-description f-mt-2">
            Enter the personal details for the directory listings.
        </div>
    </div>

    <div class="f-body">
        <div class="f-body-contents f-scroll main-contents-<?=$unique_key?>">

            <div class="f-input-wrap f-mb-3 f-border-bottom">
                <div class="listing-listing-header-icon f-input-side f-b-0 contact_number_flag" :style="displayContactFlag(contact.flag)"></div>

                <input type="tel" class="f-input f-input-middle" v-model="newListing.contact_number" 
                    @input="changeContactFlag($event.target.value, '.contact_number_flag')" placeholder="0721234567 *" >

                <div v-if="settings.mobile()" class="f-action-lnk-<?=$unique_key?> f-action-lnk-<?=$unique_key?>" @click="selectNewListingContact()">select</div>
                <div v-else class="f-action-lnk-<?=$unique_key?> f-action-lnk-<?=$unique_key?>" @click="newListing.contact_number = ''">clear</div>
            </div>

            <div class="f-input-wrap f-border-bottom">
                <input type="text" class="f-input f-input-left" v-model="newListing.name" placeholder="Name of Person or Business  *" >
                <div class="f-action-lnk-<?=$unique_key?> f-action-lnk-<?=$unique_key?>" @click="newListing.name = ''">clear</div>
            </div>

            <div class="fusion-description f-mt-3 f-ml-0 f-mb-0">List Skills:</div>
            <div class="f-input-wrap f-mb-3 f-border-bottom">
                <input type="text" class="f-input f-input-left" v-model="newListing.description" placeholder="E.g. Physio, Needling, Etc" >
                <div class="f-action-lnk-<?=$unique_key?> f-action-lnk-<?=$unique_key?>" @click="newListing.description = ''">clear</div>
            </div>

            <div class="fusion-description f-mt-3 f-ml-0 f-mb-0">Enter Work Locations:</div>
            <div class="f-input-wrap f-mb-3 f-border-bottom">
                <input type="text" class="f-input f-input-left" v-model="newListing.location" placeholder="City, Suburbs">
                <div class="f-action-lnk-<?=$unique_key?> f-action-lnk-<?=$unique_key?>" @click="newListing.location = ''">clear</div>
            </div>

            <div class="fusion-description f-mt-3 f-ml-0 f-mb-0">Enter your comment below.</div>
            <div class="f-input-wrap">
                <textarea class="f-input" rows="3" v-model="newListing.comment"></textarea>
            </div>

            <div class="f-input-wrap">
                <div class="f-icon-wrap f-mt-2">
                    <div class="f-icon-icon f-grey-star-icon"
                        id="add_rec_star_1_<?=$unique_key?>"
                        @click="newListingSelectStars(1,'add')">
                    </div>
                </div>
                <div class="f-icon-wrap f-mt-2 f-ml-1">
                    <div class="f-icon-icon f-grey-star-icon"
                        id="add_rec_star_2_<?=$unique_key?>"
                        @click="newListingSelectStars(2,'add')">
                    </div>
                </div>
                <div class="f-icon-wrap f-mt-2 f-ml-1">
                    <div class="f-icon-icon f-grey-star-icon"
                        id="add_rec_star_3_<?=$unique_key?>"
                        @click="newListingSelectStars(3,'add')">
                    </div>
                </div>
                <div class="f-icon-wrap f-mt-2 f-ml-1">
                    <div class="f-icon-icon f-grey-star-icon"
                        id="add_rec_star_4_<?=$unique_key?>"
                        @click="newListingSelectStars(4,'add')">
                    </div>
                </div>
                <div class="f-icon-wrap f-mt-2 f-ml-1">
                    <div class="f-icon-icon f-grey-star-icon"
                        id="add_rec_star_5_<?=$unique_key?>"
                        @click="newListingSelectStars(5,'add')">
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$unique_key?>">
            <div class="f-button-seg2" @click="closeAddFreeListing()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div class="f-button-seg2 f-act-btn" @click="addNewFreeListing()">
                <div class="f-button-seg-icon f-add-icon"></div>
                <div class="f-button-text">Post Listing</div>
            </div>
        </div>
    </div>
</div>


<div class="page f-page" :style="{ display: (pages.editReferral) ? 'flex': 'none' }" >
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$unique_key?> f-mt-3">
            <div class="f-header-text-full f-ml-3">
                Edit Referral
            </div>
        </div>
        <!-- <div class="fusion-description f-mt-2">Enter your company listing.</div> -->
    </div>

    <div class="f-body">
        <div class="f-body-contents f-scroll main-contents-<?=$unique_key?>">

            <div class="f-input-wrap f-mb-3 f-border-bottom">
                <!-- <div class="listing-listing-header-icon f-input-side f-b-0" :style="displayContactFlag(contact.flag)"></div>
                <input type="text" class="f-input f-input-middle" v-model="referral.contact_number" placeholder="0721234567 *" > -->

                <div class="listing-listing-header-icon f-input-side f-b-0 contact_number_flag" :style="displayContactFlag(contact.flag)"></div>

                <input type="tel" class="f-input f-input-middle" v-model="referral.contact_number" 
                    @input="changeContactFlag($event.target.value, '.contact_number_flag')" placeholder="0721234567 *" >

                <div v-if="settings.mobile()" class="f-action-lnk-<?=$unique_key?> f-action-lnk-<?=$unique_key?>" @click="selectReferralContact()">select</div>
                <div v-else class="f-action-lnk-<?=$unique_key?> f-action-lnk-<?=$unique_key?>" @click="referral.contact_number = ''">clear</div>
            </div>
            <div class="f-input-wrap f-border-bottom">
                <input type="text" class="f-input f-input-left" v-model="referral.business_name" placeholder="Referral Name *" >
                <div class="f-action-lnk-<?=$unique_key?> f-action-lnk-<?=$unique_key?>" @click="referral.business_name = ''">clear</div>
            </div>

            <div class="fusion-description f-mt-3 f-ml-0 f-mb-0">Listed Services:</div>
            <div class="f-input-wrap f-mb-3 f-border-bottom">
                <input type="text" class="f-input f-input-left" v-model="referral.description" placeholder="E.g. Building, Tiler, Etc *" >
                <div class="f-action-lnk-<?=$unique_key?> f-action-lnk-<?=$unique_key?>" @click="referral.description = ''">clear</div>
            </div>
            <div class="fusion-description f-mt-3 f-ml-0 f-mb-0">Enter your comment below.</div>
            <div class="f-input-wrap">
                <textarea class="f-input" rows="3" v-model="referral.comment"></textarea>
            </div>
            <div class="f-input-wrap">
                <div class="f-icon-wrap f-mt-2">
                    <div class="f-icon-icon f-grey-star-icon"
                        id="edit_rec_star_1_<?=$unique_key?>"
                        @click="referralSelectStars(1,'edit')">
                    </div>
                </div>
                <div class="f-icon-wrap f-mt-2 f-ml-1">
                    <div class="f-icon-icon f-grey-star-icon"
                        id="edit_rec_star_2_<?=$unique_key?>"
                        @click="referralSelectStars(2,'edit')">
                    </div>
                </div>
                <div class="f-icon-wrap f-mt-2 f-ml-1">
                    <div class="f-icon-icon f-grey-star-icon"
                        id="edit_rec_star_3_<?=$unique_key?>"
                        @click="referralSelectStars(3,'edit')">
                    </div>
                </div>
                <div class="f-icon-wrap f-mt-2 f-ml-1">
                    <div class="f-icon-icon f-grey-star-icon"
                        id="edit_rec_star_4_<?=$unique_key?>"
                        @click="referralSelectStars(4,'edit')">
                    </div>
                </div>
                <div class="f-icon-wrap f-mt-2 f-ml-1">
                    <div class="f-icon-icon f-grey-star-icon"
                        id="edit_rec_star_5_<?=$unique_key?>"
                        @click="referralSelectStars(5,'edit')">
                    </div>
                </div>
            </div>
            <!-- <div class="f-input-wrap">
                <div class="f-list-item-left f-fc" style="width: calc(calc(390/750) * var(--seg_width)) !important;"></div>
                <div class="f-list-item-side" style="width: calc(calc(300/750) * var(--seg_width)) !important; justify-content: right;"> 
                    <div class="f-action-btn f-act-bg-<?=$unique_key?> f-mt-1" @click="updateReferral()">Save Changes</div>
                </div>
            </div> -->

        </div>
    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$unique_key?>">
            <div class="f-button-seg3" @click="closeEditReferral()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div class="f-button-seg3" @click="updateReferral()">
                <div class="f-button-seg-icon f-add-icon"></div>
                <div class="f-button-text">Save</div>
            </div>
            <div class="f-button-seg3" @click="deleteReferral(referral)">
                <div class="f-button-seg-icon f-delete-icon"></div>
                <div class="f-button-text">Delete</div>
            </div>
        </div>
    </div>
    
</div>


<div class="page f-page" :style="{ display: pages.referralsAdmin ? 'flex': 'none' }" >
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$unique_key?> f-fc f-mt-3">
            <div class="f-header-text-full f-ml-3">
                Community Leads ({{getReferralsAllList.length}})
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
                        v-model="referrals.mainFilter" placeholder="Search listing by keyword." >
                <div class="f-action-lnk-<?=$unique_key?> f-action-lnk-search" @click="referrals.mainFilter = ''">clear</div>
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

            <div v-if="businesses.sort.dateAscending" class="f-button-seg3 date-sort sort-item-background" @click="applySortReferrals('date')">
                <div class="f-button-seg-icon f-sort-date-jan-dec-icon"></div>
                <div class="f-button-text">Date</div>
            </div>
            <div v-else class="f-button-seg3 date-sort sort-item-background" @click="applySortReferrals('date')">
                <div class="f-button-seg-icon f-sort-date-dec-jan-icon"></div>
                <div class="f-button-text">Date</div>
            </div>

            <div v-if="businesses.sort.nameAscending" class="f-button-seg3 name-sort" @click="applySortReferrals('name')">
                <div class="f-button-seg-icon f-sort-az-icon"></div>
                <div class="f-button-text">Name</div>
            </div>
            <div v-else class="f-button-seg3 name-sort" @click="applySortReferrals('name')">
                <div class="f-button-seg-icon f-sort-za-icon"></div>
                <div class="f-button-text">Name</div>
            </div>

            <div v-if="businesses.sort.ratingAscending" class="f-button-seg3 rating-sort" @click="applySortReferrals('rating')">
                <div class="f-button-seg-icon f-star-rating-dec-icon"></div>
                <div class="f-button-text">Rating</div>
            </div>
            <div v-else class="f-button-seg3 rating-sort" @click="applySortReferrals('rating')">
                <div class="f-button-seg-icon f-star-rating-asc-icon "></div>
                <div class="f-button-text">Rating</div>
            </div>
        </div>

        <div class="f-body-contents-full f-mb-3 f-scroll main-contents-<?=$unique_key?>"
            style="height: calc(var(--s_height) - calc(calc(435/750) * var(--seg_width))) !important;">

            <div v-if="getReferralsAllList.length == 0" 
                class="f-list-item" style="border-bottom: 0px;">
                <div class="f-list-item-wrap f-mt-3 f-ml-3">
                    There are no businesses yet. &#128515;
                </div>
            </div>

            <div v-else v-for="(business, index) in getReferralsAllList" :key="index" 
                class="listing-listing listing-listing-recommended f-fc" 
                :class="{'listing-listing-recommended-green' : business.last_called != ''}"
                v-show="searchReferralsByName(business)">

                <div class="listing-listing-header listing-listing-recommended f-mt-3 f-ml-3"
                    :class="{'listing-listing-recommended-green' : business.last_called != ''}">

                    <div class="listing-listing-header-icon-recommended"
                        :style="businessLogo('<?=$envHost?><?=$community_icon?>')"
                        @click="showReferralsCallLog(business)"></div>
                    
                    <div class="listing-listing-header-txt-holder f-fc" @click="showReferralsCallLog(business)">
                        <div class="listing-listing-header-txt f-ml-2"
                            :class="{'listing-listing-header-txt-mt': business.business_name.length <= 27}"
                            style="font-size: calc(calc(30/750) * var(--seg_width));">
                            {{ business.display_name }}
                        </div>
                    </div>

                    <div class="listing-listing-header-nav-holder" 
                        :class="{
                            'listing-listing-recommended': business.referral,
                            'listing-listing-recommended-green' : business.last_called != ''
                        }"
                        style="width: calc(calc(80/750) * var(--seg_width)) !important;">

                        <div class="listing-listing-header-txt-icon f-verified-icon f-mt-1 f-mr-1" v-if="business.paid"
                            style="background-size: calc(calc(40/750) * var(--seg_width));"></div>
                        <div class="listing-listing-header-txt-icon f-fav-heart-icon f-mt-1 f-mr-1" v-if="isFavorite(business)" 
                            style="background-size: calc(calc(35/750) * var(--seg_width));"></div>

                    </div>

                    <div class="listing-listing-header-nav-holder f-fc" 
                        :class="{
                            'listing-listing-recommended': business.referral,
                            'listing-listing-recommended-green' : business.last_called != ''
                        }"
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

                    <!-- <div class="listing-listing-header-nav-holder listing-listing-recommended"
                        :class="{'listing-listing-recommended-green' : business.last_called != ''}">
                        <div class="listing-listing-header-nav-btn-comment"></div>
                        <div class="listing-listing-header-nav-btn-stars" @click="showComments(business)">
                            <div class="listing-listing-header-nav-btn" v-if="business.stars_avg == 0">
                                <div class="listing-listing-header-nav-btn-txt f-color-grey">-</div>
                                <div class="listing-listing-header-nav-btn-star-icon f-grey-star-icon"></div>
                            </div>
                            <div class="listing-listing-header-nav-btn" v-else>
                                <div class="listing-listing-header-nav-btn-txt">{{business.stars_avg}}</div>
                                <div class="listing-listing-header-nav-btn-star-icon f-gold-star-icon"></div>
                            </div>
                        </div>
                    </div> -->

                </div>

                <div class="listing-listing-body f-fc f-mt-2 f-ml-3 listing-listing-recommended"
                    :class="{'listing-listing-recommended-green' : business.last_called != ''}"
                    @click="showReferralsCallLog(business)">
                    <div class="listing-listing-body-txt">{{ business.description }}</div>
                    <div v-if="business.last_called != ''" class="listing-listing-body-txt f-mt-2"
                        style="justify-content: right">Last Called: {{ formatDateTime(business.last_called) }}</div>
                </div>

                <div class="listing-listing-footer f-ml-3"></div>
            </div>

        </div>
    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$unique_key?>">
            <div class="f-button" @click="closeReferralsAdmin()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
        </div>
    </div>

</div>


<div class="page f-page" :style="{ display: pages.referralsCallLog ? 'flex': 'none' }" >
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
                @click="showReferralCallLogComment(log_item)">
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
            <div class="f-button-seg3" @click="closeReferralsCallLog()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div class="f-button-seg3" @click="showReferralCallWhatsApp()">
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


<div class="f-overlay" :style="{ display: (pages.referralsCallLogComment) ? 'flex': 'none' }" 
    @click.self="closeCallLogComment()">
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
                <div class="f-button" @click="closeCallLogComment()">
                    <div class="f-button-seg-icon f-back-icon"></div>
                    <div class="f-button-text">Back</div>
                </div>
            </div>
        </div>

    </div>
</div>


<div class="f-overlay" :style="{ display: (pages.referralCallWhatsApp) ? 'flex': 'none' }" 
    @click.self="closeReferralCallWhatsApp()">
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
                <div class="f-button" @click="closeReferralCallWhatsApp()">
                    <div class="f-button-seg-icon"
                        style="background-image: url('/modules/members-management-v2/images/back_icon.svg')"></div>
                    <div class="f-button-text">Back</div>
                </div>
            </div>
        </div>

    </div>
</div>






<div class="page f-page" :style="{ display: pages.approvals ? 'flex': 'none' }" >
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$unique_key?> f-fc f-mt-3">
            <div class="f-header-text-full f-ml-3">
                Business Approvals ({{getApprovalsList.length}})
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
                        v-model="businesses.mainFilter" placeholder="Search listing by keyword." >
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

            <div v-if="businesses.sort.dateAscending" class="f-button-seg3 date-sort sort-item-background" @click="applySortApprovals('date')">
                <div class="f-button-seg-icon f-sort-date-jan-dec-icon"></div>
                <div class="f-button-text">Date</div>
            </div>
            <div v-else class="f-button-seg3 date-sort sort-item-background" @click="applySortApprovals('date')">
                <div class="f-button-seg-icon f-sort-date-dec-jan-icon"></div>
                <div class="f-button-text">Date</div>
            </div>

            <div v-if="businesses.sort.nameAscending" class="f-button-seg3 name-sort" @click="applySortApprovals('name')">
                <div class="f-button-seg-icon f-sort-za-icon"></div>
                <div class="f-button-text">Name</div>
            </div>
            <div v-else class="f-button-seg3 name-sort" @click="applySortApprovals('name')">
                <div class="f-button-seg-icon f-sort-az-icon"></div>
                <div class="f-button-text">Name</div>
            </div>

            <div v-if="businesses.sort.ratingAscending" class="f-button-seg3 rating-sort" @click="applySortApprovals('rating')">
                <div class="f-button-seg-icon f-star-rating-dec-icon"></div>
                <div class="f-button-text">Rating</div>
            </div>
            <div v-else class="f-button-seg3 rating-sort" @click="applySortApprovals('rating')">
                <div class="f-button-seg-icon f-star-rating-asc-icon "></div>
                <div class="f-button-text">Rating</div>
            </div>
        </div>

        <div class="f-body-contents-full f-mb-3 f-scroll main-contents-<?=$unique_key?>"
            style="height: calc(var(--s_height) - calc(calc(435/750) * var(--seg_width))) !important;">

            <div v-if="getApprovalsList.length == 0 && loading.businesses" 
                class="f-list-item" style="border-bottom: 0px;">
                <div class="f-list-item-wrap f-mt-3 f-ml-3">
                    <div class="loader-<?=$unique_key?>"></div> 
                    <div class="loader-text">Loading ...</div>
                </div>
            </div>

            <div v-else-if="getApprovalsList.length == 0" 
                class="f-list-item" style="border-bottom: 0px;">
                <div class="f-list-item-wrap f-mt-3 f-ml-3">
                    There are no businesses to approve. &#128515;
                </div>
            </div>

            <div v-else v-for="(business, index) in getApprovalsList" :key="index" 
                class="listing-listing f-fc" v-show="searchApprovalBusinessByName(business)"
                :class="{
                    'listing-listing-recommended': business.recommended
                }"
                @click="showBusinessApprovals(business)" >

                <div class="listing-listing-header f-mt-3 f-ml-3"
                    :class="{
                        'listing-listing-recommended': business.recommended
                    }">

                    <div v-if="business.recommended" class="listing-listing-header-icon-recommended"
                        :style="businessLogo('<?=$envHost?><?=$community_icon?>', '&w=360&h=360&q=100&zc=6')"></div>
                    <div v-else-if="isEmpty(business.logo)" class="listing-listing-header-icon-no-iamge">{{businessNoLogo(business)}}</div>
                    <div v-else class="listing-listing-header-icon" 
                        :style="businessLogo(business.logo, '&w=360&q=100&zc=6')"></div>
                    
                    <div v-if="business.recommended" class="listing-listing-header-txt-holder f-fc">
                        <div class="listing-listing-header-txt f-ml-2"
                            :class="{'listing-listing-header-txt-mt': business.business_name.length <= 27}"
                            style="font-size: calc(calc(30/750) * var(--seg_width));">
                            {{ business.display_name }}
                            <span v-if="isContentCreator(trader)" 
                                class="listing-listing-edit-listing" 
                                @click.self="showEditRecommendation(trader)">edit</span>
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

                    <!-- <div class="listing-listing-header-nav-holder"
                        :class="{'listing-listing-recommended': business.recommended}">
                        <div class="listing-listing-header-nav-btn-comment">
                            <div class="listing-listing-header-txt-icon-heart f-fav-heart-icon f-mt-1 f-mr-1" v-if="isFavorite(business)" 
                                style="background-size: calc(calc(35/750) * var(--seg_width));"></div>
                            <div class="listing-listing-header-txt-icon f-verified-icon f-mt-1 f-mr-1" v-if="business.paid"
                                style="background-size: calc(calc(35/750) * var(--seg_width));"></div>
                        </div>
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
            <div class="f-button-seg" @click="closeApprovals()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
        </div>
    </div>

</div>


<div class="page f-page" :style="{ display: (pages.approvalBusiness) ? 'flex': 'none' }" >
    
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$unique_key?>">
            <div class="f-list-item f-fc f-mb-3 f-bb-0">
                <div class="f-list-item-wrap f-fr f-ml-3" style="justify-content: center">

                    <div v-if="isEmpty(businesses.business.logo)" class="f-list-item-main-right-byron-no-iamge" 
                        >{{businessNoLogo(businesses.business)}}</div>
                    <div v-else class="f-list-item-main-right-byron" 
                        :style="businessLogo(businesses.business.logo, '&w=360&h=360&q=100&zc=6')"
                        style="
                            width: calc(calc(300/750) * var(--seg_width));
                            height: calc(calc(150/750) * var(--seg_width));
                        "></div>
                </div>
            </div>
        </div>
    </div>

    <div class="f-body">
        
        <div class="f-body-contents-full f-mb-3 f-scroll main-contents-<?=$unique_key?>">
            <div class="listing-listing f-fc">
                <div class="listing-listing-header f-mt-3 f-ml-3">
                    <div class="listing-listing-header-txt-holder f-fc"
                        style="
                            width: calc(calc(480/750) * var(--seg_width));
                        ">
                        <div class="listing-listing-header-txt"
                            style="
                                font-size: calc(calc(30/750) * var(--seg_width));
                                width: calc(calc(480/750) * var(--seg_width));
                            ">
                            {{ businesses.business.display_name }}
                        </div>
                    </div>
                    <div class="listing-listing-header-nav-holder">
                        <!-- <div class="listing-listing-header-nav-btn-comment">
                            <div class="listing-listing-header-txt-icon-heart f-fav-heart-icon f-mt-1 f-mr-1" v-if="isFavorite(businesses.business)" 
                                style="background-size: calc(calc(35/750) * var(--seg_width));"></div>
                            <div class="listing-listing-header-txt-icon f-verified-icon f-mt-1 f-mr-1" v-if="businesses.business.paid == 'Y'"
                                style="background-size: calc(calc(35/750) * var(--seg_width));"></div>
                        </div>
                        <div class="listing-listing-header-nav-btn-stars" @click="showComments(businesses.business)">
                            <div class="listing-listing-header-nav-btn" v-if="businesses.business.stars_avg == 0">
                                <div class="listing-listing-header-nav-btn-txt f-color-grey">-</div>
                                <div class="listing-listing-header-nav-btn-star-icon f-grey-star-icon"></div>
                            </div>
                            <div class="listing-listing-header-nav-btn" v-else>
                                <div class="listing-listing-header-nav-btn-txt">{{businesses.business.stars_avg}}</div>
                                <div class="listing-listing-header-nav-btn-star-icon f-gold-star-icon"></div>
                            </div>
                        </div> -->
                    </div>
                </div>
                <div class="listing-listing-body f-mt-2 f-ml-3">
                    <div class="listing-listing-body-txt" v-html="businesses.business.description"></div>
                </div>
                <div class="listing-listing-footer f-ml-3"></div>
            </div>
        </div>

        <div class="f-link-btn-wrap">

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
            
            <div class="f-link-btn-byron f-ml-3" @click="openDialerApp(businesses.business.contact_number)">
                <div class="f-link-btn-icon f-contact-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Call</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
            <div class="f-link-btn-byron f-ml-3" @click="openWhatsAppApp(businesses.business.contact_number)">
                <div class="f-link-btn-icon f-whatsapp-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">WhatsApp</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
            <div v-if="!businesses.business.referral" class="f-link-btn-byron f-ml-3" @click="openEmailApp(businesses.business.email)">
                <div class="f-link-btn-icon f-invite-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Email</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
            <div class="f-link-btn-byron f-ml-3" @click="addFavorite()" v-if="!isFavorite(businesses.business)">
                <div class="f-link-btn-icon f-add-fav-heart-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Add</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
            <div class="f-link-btn-byron f-ml-3" @click="deleteFavorites()" v-else>
                <div class="f-link-btn-icon f-remove-fav-heart-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Remove</div>
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
            <div class="f-button-seg3" @click="closeBusinessApprovals()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div class="f-button-seg3" @click="approveBusiness()">
                <div class="f-button-seg-icon f-approve-icon"></div>
                <div class="f-button-text">Approve</div>
            </div>
            <div class="f-button-seg3" @click="showApprovalRejectComment()">
                <div class="f-button-seg-icon f-delete-icon"></div>
                <div class="f-button-text">Reject</div>
            </div>
        </div>
    </div>

</div>


<div class="f-overlay" :style="{ display: (pages.approvalRejectComment) ? 'flex': 'none' }" 
    @click.self="closeApprovalRejectComment()">
    <div class="f-overlay-content">
        <div class="f-overlay-header">
            <div class="fusion-heading f-heading-<?=$unique_key?> f-mt-3 f-mb-3">
                <div class="f-header-text-full">
                    Add Rejection Comment
                </div>
            </div>
        </div>

        <div class="f-overlay-body">
            <div class="f-overlay-body-contents f-scroll f-mt-3">

                <div class="fusion-description f-mt-3 f-ml-0 f-mb-0">Enter your comment below.</div>
                <div class="f-input-wrap">
                    <textarea class="f-input" rows="3" v-model="businesses.business.comment"></textarea>
                </div>

                <div class="f-input-wrap f-mb-3"></div>

            </div>
        </div>

        <div class="f-overlay-footer">
            <div class="f-buttons-<?=$unique_key?>">
                <div class="f-button-seg2" @click="closeApprovalRejectComment()">
                    <div class="f-button-seg-icon f-back-icon"></div>
                    <div class="f-button-text">Back</div>
                </div>
                <div class="f-button-seg2" @click="rejectBusiness()">
                    <div class="f-button-seg-icon f-add-icon"></div>
                    <div class="f-button-text">Submit</div>
                </div>
            </div>
        </div>

    </div>
</div>


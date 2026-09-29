
<div class="page f-page" :style="{ display: pages.adminApprovals ? 'flex': 'none' }" >
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$uniqueKey?> f-fc f-mt-3">
            <div class="f-header-text-full f-ml-3">
                Listings Approvals ({{getApprovalsListingsList.length}})
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
                    v-model="filters.approvals" 
                    class="f-input f-input-left-search f-pl-2"
                    placeholder="Search listing by keyword..." >
                <div class="f-action-lnk-<?=$uniqueKey?> f-action-lnk-search" @click="filters.approvals = ''">clear</div>
            </div>
        </div>

        <div class="f-body-contents-full f-mb-3 f-scroll main-contents-<?=$uniqueKey?>"
            style="height: calc(var(--s_height) - calc(calc(435/750) * var(--seg_width))) !important;">

            <div v-if="getApprovalsListingsList.length == 0 && loading.approvals" 
                class="f-list-item" style="border-bottom: 0px;">
                <div class="f-list-item-wrap f-mt-3 f-ml-3">
                    <div class="loader-<?=$uniqueKey?>"></div> 
                    <div class="loader-text">Loading ...</div>
                </div>
            </div>

            <div v-else-if="getApprovalsListingsList.length == 0" 
                class="f-list-item" style="border-bottom: 0px;">
                <div class="f-list-item-wrap f-mt-3 f-ml-3">
                    There are no listings to approve. &#128515;
                </div>
            </div>

            <div v-else v-for="(listing, index) in getApprovalsListingsList" :key="index">
                <div class="f-list-item f-fc" @click="showAdminApprovalsItem(listing)">
                    <listing-paid-card :listing="listing" :urls="urls" :favorites="getFavoritesList"></listing-paid-card>
                </div>
            </div>

        </div>
    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$uniqueKey?>">
            <div class="f-button-seg" @click="closeAdminApprovals()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
        </div>
    </div>

</div>


<div class="page f-page" :style="{ display: (pages.adminApprovalsItem) ? 'flex': 'none' }" >
    <div class="f-body-no-header">

        <listing-header-card 
            :listing="listing" 
            :urls="urls" 
            :settings="settings"
            :favorites="getFavoritesList">
        </listing-header-card>

        <div class="f-link-btn-wrap f-scroll">
            <div class="f-link-btn-byron f-ml-3" @click="callListintg(listing)">
                <div class="f-link-btn-icon f-contact-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Call</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>

            <div class="f-link-btn-byron f-ml-3" @click="showComments(listing)">
                <div class="f-link-btn-icon f-mt-2 f-ml-2"
                    :class="{
                        'f-grey-star-icon': listing.stars_avg == 0,
                        'f-gold-star-icon': listing.stars_avg > 0
                    }">
                    <div class="f-link-btn-text f-mt-2 f-ml-2" 
                        style="
                            margin-left: calc(calc(100/750)* var(--seg_width)) !important;
                            position: absolute;
                            margin-top: calc(calc(35/750)* var(--seg_width)) !important;
                            font-size: calc(calc(35/750)* var(--seg_width)) !important;
                        ">
                        {{ listing.stars_avg }}
                    </div>
                </div> 
                <div class="f-link-btn-text f-mt-2 f-ml-2">{{ listing.comments }} Ratings</div> 
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>

            <div v-if="!isEmpty(listing.whatsapp)" class="f-link-btn-byron f-ml-3" @click="whatsAppListing(listing)">
                <div class="f-link-btn-icon f-whatsapp-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">WhatsApp</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>

            <div v-if="!isEmpty(listing.email) && !listing.downgraded" 
                class="f-link-btn-byron f-ml-3" @click="emailListing(listing)">
                <div class="f-link-btn-icon f-invite-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Email</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
            <div v-if="!isEmpty(listing.google_url) && !listing.downgraded" class="f-link-btn-byron f-ml-3" 
                @click="openListingUrl(listing.google_url)">
                <div class="f-link-btn-icon f-google-listing-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Google</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
            <div v-if="!isEmpty(listing.website_url) && !listing.downgraded" class="f-link-btn-byron f-ml-3" 
                @click="openListingUrl(listing.website_url)">
                <div class="f-link-btn-icon f-website-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Website</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
            <div v-if="!isEmpty(listing.facebook_url) && !listing.downgraded" class="f-link-btn-byron f-ml-3" 
                @click="openListingUrl(listing.facebook_url)">
                <div class="f-link-btn-icon f-facebook-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Facebook</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
            <div v-if="!isEmpty(listing.x_url) && !listing.downgraded" class="f-link-btn-byron f-ml-3" 
                @click="openListingUrl(listing.x_url)">
                <div class="f-link-btn-icon f-x-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">X</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
            <div v-if="!isEmpty(listing.instagram_url) && !listing.downgraded" class="f-link-btn-byron f-ml-3" 
                @click="openListingUrl(listing.instagram_url)">
                <div class="f-link-btn-icon f-instagram-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Instagram</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>

            <div class="f-link-btn-byron f-ml-3" @click="openPDFDocument(listing.cv_path)">
                <div class="f-link-btn-icon f-member-blacklist-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">CV - PDF DOC</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
        </div>

    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$uniqueKey?>">
            <div class="f-button-seg3" @click="closeAdminApprovalsItem()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div class="f-button-seg3" @click="approveListing()">
                <div class="f-button-seg-icon f-approve-icon"></div>
                <div class="f-button-text">Approve</div>
            </div>
            <div class="f-button-seg3" @click="showAdminApprovalItemReject()">
                <div class="f-button-seg-icon f-delete-icon"></div>
                <div class="f-button-text">Reject</div>
            </div>
        </div>
    </div>
</div>


<div class="f-overlay" :style="{ display: (pages.adminApprovalItemReject) ? 'flex': 'none' }" 
    @click.self="closeAdminApprovalItemReject()">
    <div class="f-overlay-content">
        <div class="f-overlay-header">
            <div class="fusion-heading f-heading-<?=$uniqueKey?> f-mt-3 f-mb-3">
                <div class="f-header-text-full">
                    Add Comment
                </div>
            </div>
        </div>

        <div class="f-overlay-body">
            <div class="f-overlay-body-contents f-scroll f-mt-3">

                <div class="fusion-description f-mt-3 f-ml-0 f-mb-0">Enter your comment below.</div>
                <div class="f-input-wrap">
                    <textarea class="f-input" rows="3" v-model="callLogItemComment"></textarea>
                </div>
                <div class="f-input-wrap f-mb-3"></div>

            </div>
        </div>

        <div class="f-overlay-footer">
            <div class="f-buttons-<?=$uniqueKey?>">
                <div class="f-button-seg2" @click="closeAdminApprovalItemReject()">
                    <div class="f-button-seg-icon f-back-icon"></div>
                    <div class="f-button-text">Back</div>
                </div>
                <div class="f-button-seg2" @click="rejectListing()">
                    <div class="f-button-seg-icon f-add-icon"></div>
                    <div class="f-button-text">Submit</div>
                </div>
            </div>
        </div>

    </div>
</div>



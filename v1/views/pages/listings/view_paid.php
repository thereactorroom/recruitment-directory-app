
<div class="page f-page" :style="{ display: pages.viewPaidListing ? 'flex': 'none' }">

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

        <!-- <div class="f-link-btn-wrap f-scroll">
            
            <div v-if="
                    listing.paid == 1 && 
                    listing.downgraded == 0
                " class="f-link-btn-byron f-ml-3" 
                @click="openSpecialsModule()">
                <div class="f-link-btn-icon f-my-specials-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">My Specials</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>

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
        </div> -->

    </div>

    <div class="f-footer" v-if="isContentCreator(listing)">
        <div class="f-buttons-<?=$uniqueKey?>">
            <div class="f-button-seg3" @click="closeViewPaidListing()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div class="f-button-seg3" @click="reSubmitListing()" v-if="listing.status == 'rejected'">
                <div class="f-button-seg-icon f-save-icon"></div>
                <div class="f-button-text">ReSubmit</div>
            </div>
            <div class="f-button-seg3 f-act-btn" v-if="!listing.paid" @click="showListingPayment()">
                <div class="f-button-seg-icon f-pay-icon"></div>
                <div class="f-button-text">Pay</div>
            </div>
            <div v-if="getAlreadyCommented" class="f-button-seg3" :class="{'f-act-btn': listing.paid}"  @click="showAddCommentChain('edit')">
                <div class="f-button-seg-icon f-edit-icon"></div>
                <div class="f-button-text">Rating</div>
            </div>
            <div v-else class="f-button-seg3" :class="{'f-act-btn': listing.paid}" @click="showAddCommentChain()">
                <div class="f-button-seg-icon f-new-comment-icon"></div>
                <div class="f-button-text">Rating</div>
            </div>
            <div class="f-button-seg3" @click="showShareListing()">
                <div class="f-button-seg-icon f-share-icon"></div>
                <div class="f-button-text">Share</div>
            </div>
            <div class="f-button-seg3" @click="showEditPaidListing()">
                <div class="f-button-seg-icon f-edit-icon"></div>
                <div class="f-button-text">Edit</div>
            </div>
            <div class="f-button-seg3" @click="deleteListing()">
                <div class="f-button-seg-icon f-delete-icon"></div>
                <div class="f-button-text">Delete</div>
            </div>
        </div>
    </div>
    <div class="f-footer" v-else-if="permissions.admin">
        <div class="f-buttons-<?=$uniqueKey?>">
            <div class="f-button-seg3" @click="closeViewPaidListing()">
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
            <div class="f-button-seg3" @click="showShareListing()">
                <div class="f-button-seg-icon f-share-icon"></div>
                <div class="f-button-text">Share</div>
            </div>
            <div class="f-button-seg3" @click="showEditPaidListing()">
                <div class="f-button-seg-icon f-edit-icon"></div>
                <div class="f-button-text">Edit</div>
            </div>
        </div>
    </div>
    <div class="f-footer" v-else>
        <div class="f-buttons-<?=$uniqueKey?>">
            <div class="f-button-seg2" @click="closeViewPaidListing()">
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
            <div class="f-button-seg3" @click="showShareListing()">
                <div class="f-button-seg-icon f-share-icon"></div>
                <div class="f-button-text">Share</div>
            </div>
        </div>
    </div>

</div>


<div class="page f-page" :style="{ display: pages.listingPayment ? 'flex': 'none' }">
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$uniqueKey?> f-mt-3">
            <div class="f-header-text-full f-ml-3">
                Listing Payment
            </div> 
        </div>
        <div class="fusion-description f-mt-2">
            Choose your plan:
        </div>
    </div>

    <div class="f-body">
        <div class="f-body-contents-full f-mb-3 f-scroll main-contents-<?=$uniqueKey?>"
            style="height: calc(var(--s_height) - calc(calc(320/750) * var(--seg_width))) !important;">
            
            <div v-if="getActiveVouchersList.length > 0" class="f-list-item f-fr f-bb-0">
                <div class="f-list-item-wrap f-df f-fc f-ml-3" style="display: block;" @click="openVoucherPaymentPage()">
                openPayFastRegistrationComponent    <div class="f-action-btn f-act-bg-<?=$uniqueKey?> f-mt-1 f-df f-fc" style="
                            width: calc(calc(690/750) * var(--seg_width)) !important;
                            height: calc(calc(120/750) * var(--seg_width)) !important; 
                            background-color: green !important;
                        ">
                        <span style="width: calc(calc(325/750) * var(--seg_width)) !important; float: left; font-size: calc(calc(35/750) * var(--seg_width));">
                            <strong>Pay with a Voucher</strong>
                        </span>
                    </div>
                </div>
            </div>

            <div class="f-list-item f-fr f-mt-2 f-bb-0 f-mb-3">
                
                <div class="f-list-item-wrap f-df f-fc f-ml-3" style="
                        display: block;
                        width: calc(calc(335/750) * var(--seg_width)) !important; 
                        height: calc(calc(200/750) * var(--seg_width)) !important; 
                    "
                    @click="openPayFastComponent('99')">
                    <div class="f-action-btn f-act-bg-<?=$uniqueKey?> f-mt-1 f-df f-fc" style="
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
                    <div class="f-action-btn f-act-bg-<?=$uniqueKey?> f-mt-1 f-df f-fc" style="
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
                <div class="f-list-item-wrap f-df f-fc f-ml-2" 
                    style="
                        display: block;
                        width: calc(calc(335/750) * var(--seg_width)) !important; 
                        height: calc(calc(200/750) * var(--seg_width)) !important; 
                    "
                    @click="openPayFastComponent('495')">
                    <div class="f-action-btn f-act-bg-<?=$uniqueKey?> f-mt-1 f-df f-fc" style="
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
                        <div class="f-action-btn f-act-bg-<?=$uniqueKey?> f-act-btn" style="
                            z-index: 10000;
                            position: fixed;
                            font-size: calc(calc(20/750) * var(--seg_width)) !important; 
                            height: calc(calc(60/750) * var(--seg_width)) !important; 
                            width: calc(calc(220/750) * var(--seg_width)) !important;
                            margin-top: calc(calc(-30/750) * var(--seg_width)) !important;
                            left: calc(calc(75/750) * var(--seg_width)) !important;">
                            1 Months Free
                        </div>
                    </div>
                </div>
                <div class="f-list-item-wrap f-df f-fc f-ml-2" 
                    style="
                        display: block;
                        width: calc(calc(335/750) * var(--seg_width)) !important; 
                        height: calc(calc(200/750) * var(--seg_width)) !important; 
                    "
                    @click="openPayFastComponent('990')">
                    <div class="f-action-btn f-act-bg-<?=$uniqueKey?> f-mt-1 f-df f-fc" style="
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
                        <div class="f-action-btn f-act-bg-<?=$uniqueKey?> f-act-btn" style="
                            z-index: 10000;
                            position: fixed;
                            font-size: calc(calc(20/750) * var(--seg_width)) !important; 
                            height: calc(calc(60/750) * var(--seg_width)) !important; 
                            width: calc(calc(220/750) * var(--seg_width)) !important;
                            margin-top: calc(calc(-30/750) * var(--seg_width)) !important;
                            right: calc(calc(95/750) * var(--seg_width)) !important;">
                            2 Months Free
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="f-list-item f-fc f-mt-2 f-bb-0">
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
                <div class="f-list-item-wrap f-fc f-ml-3">
                    <div class="f-list-item-description f-mt-1" style="color: #000">
                        If you choose to "Pay Later", your listing will remain hidden in the Physio directory until payment is made.
                    </div>
                </div>
            </div>

            <div class="f-list-item f-fc f-mt-2 f-bb-0">
                <?php if($env == "sandbox") { ?>
                    <div class="f-list-item-wrap f-ml-3 f-mt-3">
                        <div class="f-action-btn f-act-bg-<?=$uniqueKey?> f-mt-1" 
                            style="width: calc(calc(690/750) * var(--seg_width)) !important;"
                            @click="openPayFastComponent('5')">Test Payment R5</div>
                    </div>
                <?php } ?>
            </div>

        </div>
    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$uniqueKey?>">
            <div class="f-button" @click="closeListingPayment()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
        </div>
    </div>

</div>

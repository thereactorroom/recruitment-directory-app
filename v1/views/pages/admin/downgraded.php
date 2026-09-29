
<div class="page f-page" :style="{ display: pages.adminDowngradedListings ? 'flex': 'none' }" >
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$uniqueKey?> f-fc f-mt-3">
            <div class="f-header-text-full f-ml-3">
                Downgraded Listings ({{getDowngradedListingsList.length}})
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
                    v-model="filters.downgraded" 
                    class="f-input f-input-left-search f-pl-2"
                    placeholder="Search listing by keyword..." >
                <div class="f-action-lnk-<?=$uniqueKey?> f-action-lnk-search" @click="filters.downgraded = ''">clear</div>
            </div>
        </div>

        <div class="f-body-contents-full f-mb-3 f-scroll main-contents-<?=$uniqueKey?>"
            style="height: calc(var(--s_height) - calc(calc(435/750) * var(--seg_width))) !important;">

            <div v-if="getDowngradedListingsList.length == 0 && loading.downgraded" 
                class="f-list-item" style="border-bottom: 0px;">
                <div class="f-list-item-wrap f-mt-3 f-ml-3">
                    <div class="loader-<?=$uniqueKey?>"></div> 
                    <div class="loader-text">Loading ...</div>
                </div>
            </div>

            <div v-else-if="getDowngradedListingsList.length == 0" 
                class="f-list-item" style="border-bottom: 0px;">
                <div class="f-list-item-wrap f-mt-3 f-ml-3">
                    There are no downgraded listings. &#128515;
                </div>
            </div>

            <div v-else v-for="(listing, index) in getDowngradedListingsList" :key="index">
                <div class="f-list-item f-fc listing-listing-recommended" @click="showDowngradedListingItem(listing)">
                    <listing-free-card :listing="listing" :favorites="getFavoritesList"></listing-free-card>
                </div>
            </div>

        </div>
    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$uniqueKey?>">
            <div class="f-button" @click="closeDowngradedListings()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
        </div>
    </div>

</div>


<div class="page f-page" :style="{ display: (pages.adminDowngradedListingItem) ? 'flex': 'none' }" >

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

    <div class="f-footer">
        <div class="f-buttons-<?=$uniqueKey?>">
            <div class="f-button-seg2" @click="closeDowngradedListingItem()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div class="f-button-seg2" @click="showUpgradeOptionsPage()">
                <div class="f-button-seg-icon f-pay-icon"></div>
                <div class="f-button-text">Up Grade</div>
            </div>
        </div>
    </div>

</div>



<div class="f-overlay" :style="{ display: (pages.upgradeOptions) ? 'flex': 'none' }" 
    @click.self="closeUpgradeOptionsPage()">
    <div class="f-overlay-content">
        <div class="f-overlay-header">
            <div class="fusion-heading f-heading-<?=$uniqueKey?> f-mt-3 f-mb-3">
                <div class="f-header-text-full">
                    Select Upgrade Option
                </div>
            </div>
        </div>

        <div class="f-overlay-body">
            <div class="f-overlay-body-contents f-scroll f-mt-3">
                <div class="f-input-wrap f-mt-0">
                    <div class="f-link-btn" @click="setUpgradeType('admin_allocation')">
                        <div class="f-link-btn-icon f-contact-icon f-mt-2 f-ml-2"></div>
                        <div class="f-link-btn-text f-mt-2 f-ml-2">Admin</div>
                        <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2">Allocation</div>
                    </div>
                    <div class="f-link-btn f-ml-3" @click="setUpgradeType('voucher')">
                        <div class="f-link-btn-icon f-voucher-icon f-mt-2 f-ml-2"></div>
                        <div class="f-link-btn-text f-mt-2 f-ml-2">Voucher</div>
                        <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2">Payment</div>
                    </div>
                    <div class="f-link-btn f-ml-3" @click="setUpgradeType('eft_cash')">
                        <div class="f-link-btn-icon f-payment-log-icon f-mt-2 f-ml-2"></div>
                        <div class="f-link-btn-text f-mt-2 f-ml-2">Cash</div>
                        <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2">Payment</div>
                    </div>
                </div>
                <div class="f-input-wrap"></div>
            </div>
        </div>

        <div class="f-overlay-footer">
            <div class="f-buttons-<?=$uniqueKey?>">
                <div class="f-button" @click="closeUpgradeOptionsPage()">
                    <div class="f-button-seg-icon"
                        style="background-image: url('/modules/members-management-v2/images/back_icon.svg')"></div>
                    <div class="f-button-text">Back</div>
                </div>
            </div>
        </div>

    </div>
</div>


<div class="f-overlay" :style="{ display: (pages.upgradeListing) ? 'flex': 'none' }" 
    @click.self="closeUpgradeListingPage()">
    <div class="f-overlay-content">
        <div class="f-overlay-header">
            <div class="fusion-heading f-heading-<?=$uniqueKey?> f-mt-3 f-mb-3">
                <div class="f-header-text-full">
                    Upgrade Listing - {{ upgradeOptions.description }}
                </div>
            </div>
        </div>

        <div class="f-overlay-body">
            <div class="f-body-contents f-scroll f-scroll-container f-mt-3 f-mb-3">
                
                <div v-show="upgradeOptions.type == 'eft_cash'" class="fusion-description f-mt-3 f-ml-0 f-mb-0">Enter amount paid*</div>
                <div v-show="upgradeOptions.type == 'eft_cash'" class="f-input-wrap f-border-bottom">
                    <input v-if="isMobile" type="tel" class="f-input f-input-left f-b-0" v-model="upgradeOptions.amount" placeholder="E.g) 200, = R200 " >
                    <input v-else type="text" class="f-input f-input-left f-b-0" v-model="upgradeOptions.amount" placeholder="E.g) 200, = R200 *" >
                    <div v-show="!isEmpty(upgradeOptions.amount)" class="f-action-lnk-<?=$uniqueKey?>" @click="upgradeOptions.amount = ''">clear</div>
                </div>

                <div class="fusion-description f-mt-3 f-ml-0 f-mb-0">Enter Upgrade start date *</div>
                <div class="f-input-wrap f-border-bottom">
                    <input type="text" class="f-input f-input-left datepicker" 
                        id="addStartDateValue<?=$uniqueKey?>"
                        v-model="upgradeOptions.start_date" 
                        placeholder="Select start date" 
                        style="background-color: #ffffff;">

                    <div v-show="!isEmpty(upgradeOptions.start_date)" class="f-action-lnk-<?=$uniqueKey?>" @click="upgradeOptions.start_date = ''">clear</div>
                </div>

                <div class="fusion-description f-mt-3 f-ml-0 f-mb-0">Enter months for the Upgrade*</div>
                <div class="f-input-wrap f-border-bottom">
                    <input v-if="isMobile" type="tel" class="f-input f-input-left f-b-0" v-model="upgradeOptions.period_length" placeholder="E.g) 3, = 3 months " >
                    <input v-else type="text" class="f-input f-input-left f-b-0" v-model="upgradeOptions.period_length" placeholder="E.g) 3, = 3 months *" >
                    <div v-show="!isEmpty(upgradeOptions.period_length)" class="f-action-lnk-<?=$uniqueKey?>" @click="upgradeOptions.period_length = ''">clear</div>
                </div>

                <div class="f-input-wrap f-mt-2">
                    <div class="f-action-btn f-act-bg-<?=$uniqueKey?> f-mt-1 f-act-btn"  
                        style="width: calc(calc(690/750) * var(--seg_width)) !important;"
                        @click="upgradeListing()">Upgrade Listing
                    </div>
                </div>
            </div>
        </div>

        <div class="f-overlay-footer">
            <div class="f-buttons-<?=$uniqueKey?>">
                <div class="f-button" @click="closeUpgradeListingPage()">
                    <div class="f-button-seg-icon"
                        style="background-image: url('/modules/members-management-v2/images/back_icon.svg')"></div>
                    <div class="f-button-text">Back</div>
                </div>
            </div>
        </div>

    </div>
</div>

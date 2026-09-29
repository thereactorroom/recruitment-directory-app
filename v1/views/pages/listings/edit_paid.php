
<div class="page f-page" :style="{ display: pages.editPaidListing ? 'flex': 'none' }" >
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$uniqueKey?> f-mt-3">
            <div class="f-header-text-full f-ml-3">
                {{ listing.name }}
            </div> 
        </div>
    </div>

    <div class="f-body">

        <div class="f-body-contents">
            <div class="f-input-wrap">
                <div class="f-tab-btn f-mt-1 f-act-bg-<?=$uniqueKey?> detailsTab<?=$uniqueKey?>">Details</div>
                <div class="f-tab-btn f-mt-1 f-ml-1 servicesTab<?=$uniqueKey?>">Services</div>
                <div class="f-tab-btn f-mt-1 f-ml-1 logoTab<?=$uniqueKey?>">Logo</div>
                <div class="f-tab-btn f-mt-1 f-ml-1 channelsTab<?=$uniqueKey?>">Channels</div>
            </div>
        </div>

        <div v-if="detailsTabs.tab == 'details'" class="f-body-contents f-scroll f-scroll-container f-mt-3">
            <div class="f-input-wrap f-border-bottom">
                <input type="text" class="f-input f-input-left" v-model="listing.name" placeholder="Listing Name *" >
                <div class="f-action-lnk-<?=$uniqueKey?>" @click="listing.name = ''">clear</div>
            </div>
            <div class="f-input-wrap f-border-bottom f-mt-2">
                <div class="listing-listing-header-icon f-input-side f-b-0 contact_number_flag" :style="displayContactFlag(contact.flag)"></div>
                <input type="tel" class="f-input f-input-middle" v-model="listing.contact_number" 
                    @input="changeContactFlag($event.target.value, '.contact_number_flag')" placeholder="Contact Number *" >

                <div v-if="isMobile" class="f-action-lnk-<?=$uniqueKey?>" @click="selectContactNumber('decodeEditListingContact')">select</div>
                <div v-else class="f-action-lnk-<?=$uniqueKey?>" @click="listing.contact_number = ''">clear</div>
            </div>
            <div class="f-input-wrap f-border-bottom f-mt-2">
                <div class="listing-listing-header-icon f-input-side f-b-0 office_number_flag" :style="displayContactFlag(contact.flag)"></div>
                <input type="tel" class="f-input f-input-middle" v-model="listing.office_number" 
                    @input="changeContactFlag($event.target.value, '.office_number_flag')" placeholder="Office Number" >

                <div v-if="isMobile" class="f-action-lnk-<?=$uniqueKey?>" @click="selectContactNumber('decodeEditListingOffice')">select</div>
                <div v-else class="f-action-lnk-<?=$uniqueKey?>" @click="listing.office_number = ''">clear</div>
            </div>
            <div class="f-input-wrap f-border-bottom f-mt-2">
                <input type="text" class="f-input f-input-left" v-model="listing.email" placeholder="Listing Email *" >
                <div class="f-action-lnk-<?=$uniqueKey?>" @click="listing.email = ''">clear</div>
            </div>
            <div class="f-input-wrap f-border-bottom f-mt-2">
                <input type="text" class="f-input f-input-left" v-model="listing.dob" placeholder="Date of Birth *" >
                <div class="f-action-lnk-<?=$uniqueKey?>" @click="listing.dob = ''">clear</div>
            </div>
            <div class="f-input-wrap f-border-bottom f-mt-2">
                <input type="text" class="f-input f-input-left" v-model="listing.gender" placeholder="Gender *" >
                <div class="f-action-lnk-<?=$uniqueKey?>" @click="showSelectListingGender()">select</div>
            </div>
        </div>

        <div v-if="detailsTabs.tab == 'services'" class="f-body-contents f-scroll f-scroll-container f-mt-3">
            <div class="fusion-descriptio f-mt-3">
                Please describe the skills and services that can be provided.
            </div>
            <div class="f-input-wrap f-border-bottom f-mt-2">
                <textarea type="text" rows="5" class="f-input f-input" v-model="listing.description" placeholder="E.g. Physio, Needling, Etc *" >
                    {{ listing.description }}
                </textarea>
            </div>
            <div class="fusion-descriptio f-mt-3" style="justify-content: right;">
                <div>Characters: {{ getListingDescription.length }}/300</div>
            </div>
            <div class="fusion-description f-mt-3 f-ml-0 f-mb-0">Eneter work Locations</div>
            <div class="f-input-wrap f-mb-3 f-border-bottom">
                <input type="text" class="f-input f-input-left" v-model="listing.location" placeholder="City, Suburbs">
                <div class="f-action-lnk-<?=$uniqueKey?> f-action-lnk-<?=$uniqueKey?>" @click="listing.location = ''">clear</div>
            </div>
            <div class="f-input-wrap f-border-bottom">
                <div class="f-link-btn-byron f-ml-3" @click="showSelectCVFromFile('Edit')">
                    <div class="f-link-btn-icon f-member-blacklist-icon f-mt-2 f-ml-2"></div>
                    <div class="f-link-btn-text f-mt-2 f-ml-2">CV</div>
                    <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2">PDF Document</div>
                </div>
                <div class="f-input f-input-left">
                    <div class="f-list-item-name f-ml-3 f-mt-3">Click to upload CV</div>
                    <input type="file" hidden class="input" id="selectEditListingCVFromFile" accept="application/pdf" />
                </div>
            </div>
            <div class="fusion-description f-mt-3 f-ml-0 f-mb-0" v-show="!isEmpty(getListingCVFile)">
                <object :data="getListingCVFile" type="application/pdf" width="100%" height="600px">
                    <p>
                        Your browser does not support embedded PDFs. 
                        <div @click="openPDFDocument(listing.cv_path)">Click here to download the PDF.</div>
                    </p>
                </object>
            </div>
        </div>

        <div v-if="detailsTabs.tab == 'logo'" class="f-body-contents f-scroll f-scroll-container f-mt-3">
            <div class="fusion-descriptio f-mt-3">
                Click the image button below to upload a profile photo.
            </div>
            <div v-if="isEmpty(getListingLogo)" class="registration-image f-mt-3" @click="showSelectLogoFromFile('Edit')">
                <div class="f-upload-icon" style="
                    background-size: contain;
                    background-repeat: no-repeat;
                    background-position: center;
                "></div>
            </div>
            <div v-else class="registration-image f-mt-3"
                :style="displaySelectedListingLogo(getListingLogo)"
                @click="showSelectLogoFromFile('Edit')">
            </div>
            <input type="file" hidden class="input" id="selectEditListingLogoFromFile" accept="image/*" />
        </div>

        <div v-if="detailsTabs.tab == 'channels'" class="f-body-contents f-scroll f-scroll-container f-mt-3">
            <div class="fusion-descriptio f-mt-3 f-mb-3">
                Add any links you may wish to share.
            </div>

            <label class="f-df f-fr f-mt-3 ">
                Google URL
                <div class="f-tab-btn f-act-bg-<?=$uniqueKey?> f-ml-2" 
                    @click="openListingUrl(listing.google_url)"
                    v-if="!isEmpty(listing.google_url)"
                    style="
                        margin-top: -4px;
                        padding: calc(calc(2/750) * var(--seg_width)) calc(calc(15/750) * var(--seg_width)) calc(calc(2/750) * var(--seg_width)) calc(calc(15/750) * var(--seg_width)) !important;
                    ">Test
                </div>
            </label>
            <div class="f-input-wrap f-border-bottom f-mt-2">
                <input type="text" class="f-input f-input-left" v-model="listing.google_url" placeholder="Google Business URL " >
                <div class="f-action-lnk-<?=$uniqueKey?>" 
                    v-if="!isEmpty(listing.google_url)"
                    @click="listing.google_url = ''">clear</div>
            </div>
            
            <label class="f-mt-2 f-df f-fr">
                Website URL
                <div class="f-tab-btn f-act-bg-<?=$uniqueKey?> f-ml-2" 
                    @click="openListingUrl(listing.website_url)"
                    v-if="!isEmpty(listing.website_url)"
                    style="
                        margin-top: -4px;
                        padding: calc(calc(2/750) * var(--seg_width)) calc(calc(15/750) * var(--seg_width)) calc(calc(2/750) * var(--seg_width)) calc(calc(15/750) * var(--seg_width)) !important;
                    ">Test
                </div>
            </label>
            <div class="f-input-wrap f-border-bottom f-mt-3">
                <input type="text" class="f-input f-input-left" v-model="listing.website_url" placeholder="Website URL" >
                <div class="f-action-lnk-<?=$uniqueKey?>" 
                    v-if="!isEmpty(listing.website_url)"
                    @click="listing.website_url = ''">clear</div>
            </div>

            <label class="f-mt-2 f-df f-fr">
                Facebook URL
                <div class="f-tab-btn f-act-bg-<?=$uniqueKey?> f-ml-2" 
                    @click="openListingUrl(listing.facebook_url)"
                    v-if="!isEmpty(listing.facebook_url)"
                    style="
                        margin-top: -4px;
                        padding: calc(calc(2/750) * var(--seg_width)) calc(calc(15/750) * var(--seg_width)) calc(calc(2/750) * var(--seg_width)) calc(calc(15/750) * var(--seg_width)) !important;
                    ">Test
                </div>
            </label>
            <div class="f-input-wrap f-border-bottom f-mt-2">
                <input type="text" class="f-input f-input-left" v-model="listing.facebook_url" placeholder="Facebook URL" >
                <div class="f-action-lnk-<?=$uniqueKey?>" 
                    v-if="!isEmpty(listing.facebook_url)"
                    @click="listing.facebook_url = ''">clear</div>
            </div>

            <label class="f-mt-2 f-df f-fr">
                X URL
                <div class="f-tab-btn f-act-bg-<?=$uniqueKey?> f-ml-2" 
                    @click="openListingUrl(listing.x_url)"
                    v-if="!isEmpty(listing.x_url)"
                    style="
                        margin-top: -4px;
                        padding: calc(calc(2/750) * var(--seg_width)) calc(calc(15/750) * var(--seg_width)) calc(calc(2/750) * var(--seg_width)) calc(calc(15/750) * var(--seg_width)) !important;
                    ">Test
                </div>
            </label>
            <div class="f-input-wrap f-border-bottom f-mt-2">
                <input type="text" class="f-input f-input-left" v-model="listing.x_url" placeholder="X URL" >
                <div class="f-action-lnk-<?=$uniqueKey?>" 
                    v-if="!isEmpty(listing.x_url)"
                    @click="listing.x_url = ''">clear</div>
            </div>

            <label class="f-mt-2 f-df f-fr">
                Instagram URL
                <div class="f-tab-btn f-act-bg-<?=$uniqueKey?> f-ml-2" 
                    @click="openListingUrl(listing.instagram_url)"
                    v-if="!isEmpty(listing.instagram_url)"
                    style="
                        margin-top: -4px;
                        padding: calc(calc(2/750) * var(--seg_width)) calc(calc(15/750) * var(--seg_width)) calc(calc(2/750) * var(--seg_width)) calc(calc(15/750) * var(--seg_width)) !important;
                    ">Test
                </div>
            </label>
            <div class="f-input-wrap f-border-bottom f-mt-2">
                <input type="text" class="f-input f-input-left" v-model="listing.instagram_url" placeholder="Instagram URL" >
                <div class="f-action-lnk-<?=$uniqueKey?>" 
                    v-if="!isEmpty(listing.instagram_url)"
                    @click="listing.instagram_url = ''">clear</div>
            </div>
            
        </div>
        
    </div>

    <div class="f-footer">
        <div v-if="detailsTabs.tab != 'channels'" class="f-buttons-<?=$uniqueKey?>">
            <div v-if="detailsTabs.tab  == 'details'" class="f-button-seg3" @click="closeEditPaidListing()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div v-else class="f-button-seg3" @click="backTab()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div class="f-button-seg3" @click="updateListing()">
                <div class="f-button-seg-icon f-save-icon"></div>
                <div class="f-button-text">Save</div>
            </div>
            <div class="f-button-seg3" @click="nextTab()">
                <div class="f-button-seg-icon f-next-icon"></div>
                <div class="f-button-text">Next</div>
            </div>
        </div>
        <div v-else class="f-buttons-<?=$uniqueKey?>">
            <div v-if="detailsTabs.tab  == 'details'" class="f-button-seg2" @click="closeEditPaidListing()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div v-else class="f-button-seg2" @click="backTab()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div class="f-button-seg2" @click="updateListing()">
                <div class="f-button-seg-icon f-save-icon"></div>
                <div class="f-button-text">Save</div>
            </div>
        </div>
    </div>

</div>


<div class="page f-page" :style="{ display: pages.addPaidListing ? 'flex': 'none' }" >
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$uniqueKey?> f-mt-3">
            <div class="f-header-text-full f-ml-3">
                Paid listing Sign-up <div style="position: relative; float: right;">1 of 4</div>
            </div> 
        </div>
    </div>

    <div class="f-body">
        <!-- <div class="f-body-contents f-scroll main-contents-<?=$uniqueKey?>">
            <div v-html="proposition.proposition" class="quill-content-<?=$uniqueKey?>"></div>
        </div> -->
    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$uniqueKey?>">
            <div class="f-button-seg2" @click="closeAddPaidListing()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Close</div>
            </div>
            <div class="f-button-seg2" @click="showSearchPage()">
                <div class="f-button-seg-icon f-next-icon"></div>
                <div class="f-button-text">Next</div>
            </div>
        </div>
    </div>
</div>


<div class="page f-page" :style="{ display: pages.addPaidListingSearch ? 'flex': 'none' }" >
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$uniqueKey?> f-mt-3">
            <div class="f-header-text-full f-ml-3">
                Check for existing listing <div style="position: relative; float: right;">2 of 4</div>
            </div> 
        </div>
        <div class="fusion-description f-mt-2">
            First, let's see if you already have a listing.</br><br/>If so, we'll automatically link any rating you have to your new paid listing.
        </div>
    </div>

    <div class="f-body">

        <div v-show="!newListing.search.showSearchMobile" class="f-body-contents f-mb-3">
            <div class="f-input-wrap f-border-bottom">
                <input type="text" class="f-input f-input-left" v-model="newListing.search.name" placeholder="Listing Name *" >
                <div class="f-action-lnk-<?=$uniqueKey?>" @click="newListing.search.name = ''">clear</div>
            </div>
            <div class="f-input-wrap">
                <div class="f-list-item-left f-fc"></div>
                <div class="f-list-item-side"> 
                    <div class="f-action-btn f-act-bg-<?=$uniqueKey?> f-mt-1" @click="searchFreeListings()">Search</div>
                </div>
            </div>
        </div>

        <div v-show="newListing.search.showSearchMobile" class="f-body-contents f-mb-3">
            <div class="f-input-wrap f-border-bottom">
                <input type="text" class="f-input f-input-left" v-model="newListing.search.name" placeholder="Listing Name *" >
                <div class="f-action-lnk-<?=$uniqueKey?>" @click="newListing.search.name = ''">clear</div>
            </div>

            <div class="f-input-wrap f-border-bottom f-mt-2">
                <div class="listing-listing-header-icon f-input-side f-b-0 contact_number_flag" :style="displayContactFlag(contact.flag)"></div>
                <input type="tel" class="f-input f-input-middle" v-model="newListing.search.mobile" 
                    @input="changeContactFlag($event.target.value, '.contact_number_flag')" placeholder="Contact Number *" >

                <div v-if="isMobile" class="f-action-lnk-<?=$uniqueKey?>" @click="selectContactNumber('decodeNewListingSearchMobile')">select</div>
                <div v-else class="f-action-lnk-<?=$uniqueKey?>" @click="newListing.search.mobile = ''">clear</div>
            </div>

            <div class="f-input-wrap">
                <div class="f-list-item-left f-fc"></div>
                <div class="f-list-item-side"> 
                    <div class="f-action-btn f-act-bg-<?=$uniqueKey?> f-mt-1" @click="searchFreeListings()">Search</div>
                </div>
            </div>
        </div>

    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$uniqueKey?>">
            <div class="f-button" @click="closeSearchPage()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div class="f-button" @click="showAddListingDetails()">
                <div class="f-button-seg-icon f-next-icon"></div>
                <div class="f-button-text">Skip</div>
            </div>
        </div>
    </div>

</div>


<div class="page f-page" :style="{ display: (pages.addPaidListingSearchResults) ? 'flex': 'none' }" >
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$uniqueKey?> f-mt-3">
            <div class="f-header-text-full f-ml-3">
                Search Results ({{ getAddListingSearchResults.length }}) <div style="position: relative; float: right;">2 of 4</div>
            </div> 
        </div>
        <div class="fusion-description f-mt-2">
            Select from the results a Listing that best match your Listing or click "Skip" to continue.
        </div>
        <div class="listing-listing-footer f-ml-3"></div>
    </div>

    <div class="f-body">

        <div class="f-body-contents-full f-mb-3 f-scroll main-contents-<?=$uniqueKey?>">

            <div v-if="getAddListingSearchResults.length == 0" class="listing-listing f-fc f-border-none">
                <div class="listing-listing-body f-fc f-mt-2 f-ml-3">
                    <div class="listing-listing-body-txt">No Results Found</div>
                </div>
            </div>

            <div v-else v-for="(listing, index) in getAddListingSearchResults" :key="index" 
                class="listing-listing f-fc listing-listing-recommended"
                @click="selectListing(listing)">

                <div class="listing-listing-header f-mt-3 f-ml-3 listing-listing-recommended">
                    <div class="listing-listing-header-icon-recommended"
                        :style="listingLogo('<?=$envHost?><?=$icon?>')"></div>
                    
                    <div class="listing-listing-header-txt-holder f-fc">
                        <div class="listing-listing-header-txt f-ml-2"
                            :class="{'listing-listing-header-txt-mt': newListing.name.length <= 27}"
                            style="font-size: calc(calc(30/750) * var(--seg_width));">
                            {{ listing.name }}
                        </div>
                    </div>
                </div>

                <div class="listing-listing-body f-mt-2 f-ml-3 listing-listing-recommended">
                    <div class="listing-listing-body-txt">{{ listing.description.substring(0, 140) }}</div>
                </div>

                <div class="listing-listing-footer f-ml-3"></div>
            </div>

            <div v-if="newListing.search.action == 'name'" class="listing-listing f-fc f-border-none f-mt-3">
                <div class="listing-listing-body f-fc f-mt-2 f-ml-3">
                    <div class="f-list-item-side" style="width: calc(calc(450/750) * var(--seg_width)) !important; justify-content: left;"> 
                        <div class="f-action-btn f-act-bg-<?=$uniqueKey?> f-mt-1" @click="searchWithMobile()">Search with Mobile Number</div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$uniqueKey?>">
            <div class="f-button" @click="closeAddPaidListingSearchResults()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div class="f-button" @click="showAddListingDetails()">
                <div class="f-button-seg-icon f-next-icon"></div>
                <div class="f-button-text">Skip</div>
            </div>
        </div>
    </div>

</div>


<div class="page f-page" :style="{ display: (pages.addPaidListing2FA) ? 'flex': 'none' }" >
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$uniqueKey?> f-mt-3">
            <div class="f-header-text-full f-ml-3">
                Verify Ownership <div style="position: relative; float: right;">2 of 4</div>
            </div> 
        </div>
        <div class="fusion-description f-mt-2">
            Please verify you own this business, by confirming the details below.
        </div>
    </div>

    <div class="f-body">
        <div class="f-body-contents f-mb-3 f-scroll main-contents-<?=$uniqueKey?>">

            <div class="listing-listing f-fc">
                <div class="listing-listing-header f-mt-3">
                    <div class="listing-listing-header-icon-recommended"
                        :style="listingLogo('<?=$envHost?><?=$icon?>')"></div>
                    
                    <div class="listing-listing-header-txt-holder f-fc">
                        <div class="listing-listing-header-txt f-ml-2"
                            :class="{'listing-listing-header-txt-mt': newListing.name.length <= 27}"
                            style="font-size: calc(calc(30/750) * var(--seg_width));">
                            {{ newListing.name }}
                        </div>
                    </div>
                </div>
                <div class="listing-listing-body f-fc f-mt-2">
                    <div class="listing-listing-body-txt">{{ newListing.description.substring(0, 140) }}</div>
                </div>
                <div class="listing-listing-footer"></div>
            </div>

            <div v-if="!isEmpty(newListing.maskMobile)" class="listing-listing f-fc f-border-none">
                <div class="f-mt-3">
                    Please confirm the following mobile number:
                </div>
                <div class="f-mt-2">
                    {{newListing.maskMobile}}
                </div>
                <div class="f-input-wrap f-border-bottom f-mt-3">
                    <input type="text" class="f-input f-input-left" v-model="newListing.search._2fa" placeholder="Enter Mobile Number *" >
                    <div class="f-action-lnk-<?=$uniqueKey?>" @click="newListing.search._2fa = ''">clear</div>
                </div>
                <div class="f-list-item-side f-mt-3" style="width: calc(calc(300/750) * var(--seg_width)) !important; justify-content: left;"> 
                    <div class="f-action-btn f-act-bg-<?=$uniqueKey?> f-mt-3" @click="verify2FA('mobile')">Verify Mobile</div>
                </div>
            </div>

        </div>
    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$uniqueKey?>">
            <div class="f-button" @click="closeAddPaidListing2FA()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
        </div>
    </div>

</div>


<div class="page f-page" :style="{ display: pages.addPaidListingDetails ? 'flex': 'none' }" >
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$uniqueKey?> f-mt-3">
            <div class="f-header-text-full f-ml-3">
                Paid Listing <div style="float: right;">3 of 4</div>
            </div> 
        </div>
        <div class="fusion-description f-mt-2">
            Add details below.
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
                <input type="text" class="f-input f-input-left" v-model="newListing.name" placeholder="Listing Name *" >
                <div class="f-action-lnk-<?=$uniqueKey?>" @click="newListing.name = ''">clear</div>
            </div>
            <div class="f-input-wrap f-border-bottom f-mt-2">
                <div class="listing-listing-header-icon f-input-side f-b-0 contact_number_flag" :style="displayContactFlag(contact.flag)"></div>
                <input type="tel" class="f-input f-input-middle" v-model="newListing.contact_number" 
                    @input="changeContactFlag($event.target.value, '.contact_number_flag')" placeholder="Contact Number *" >

                <div v-if="isMobile" class="f-action-lnk-<?=$uniqueKey?>" @click="selectContactNumber('decodeNewListingContact')">select</div>
                <div v-else class="f-action-lnk-<?=$uniqueKey?>" @click="newListing.contact_number = ''">clear</div>
            </div>
            <div class="f-input-wrap f-border-bottom f-mt-2">
                <div class="listing-listing-header-icon f-input-side f-b-0 office_number_flag" :style="displayContactFlag(contact.flag)"></div>
                <input type="tel" class="f-input f-input-middle" v-model="newListing.office_number" 
                    @input="changeContactFlag($event.target.value, '.office_number_flag')" placeholder="Office Number" >

                <div v-if="isMobile" class="f-action-lnk-<?=$uniqueKey?>" @click="selectContactNumber('decodeNewListingOffice')">select</div>
                <div v-else class="f-action-lnk-<?=$uniqueKey?>" @click="newListing.office_number = ''">clear</div>
            </div>
            <div class="f-input-wrap f-border-bottom f-mt-2">
                <input type="text" class="f-input f-input-left" v-model="newListing.email" placeholder="Listing Email *" >
                <div class="f-action-lnk-<?=$uniqueKey?>" @click="newListing.email = ''">clear</div>
            </div>
            <div class="f-input-wrap f-border-bottom f-mt-2">
                <input type="text" class="f-input f-input-left" v-model="newListing.dob" placeholder="Date of Birth *" >
                <div class="f-action-lnk-<?=$uniqueKey?>" @click="newListing.dob = ''">clear</div>
            </div>
            <div class="f-input-wrap f-border-bottom f-mt-2">
                <input type="text" class="f-input f-input-left" v-model="newListing.gender" placeholder="Gender *" >
                <div class="f-action-lnk-<?=$uniqueKey?>" @click="showSelectListingGender()">select</div>
            </div>
        </div>

        <div v-if="detailsTabs.tab == 'services'" class="f-body-contents f-scroll f-scroll-container f-mt-3">
            <div class="fusion-descriptio f-mt-3">
                Please describe the skills and services that can be provided.
            </div>
            <div class="f-input-wrap f-border-bottom f-mt-2">
                <textarea type="text" rows="5" class="f-input f-input" v-model="newListing.description" placeholder="E.g. Physio, Needling, Etc *" >
                    {{newListing.description}}
                </textarea>
            </div>
            <div class="fusion-descriptio f-mt-3" style="justify-content: right;">
                <div>Characters: {{ newListing.description.length }}/300</div>
            </div>
            <div class="fusion-description f-mt-3 f-ml-0 f-mb-0">Eneter work Locations</div>
            <div class="f-input-wrap f-mb-3 f-border-bottom">
                <input type="text" class="f-input f-input-left" v-model="newListing.location" placeholder="City, Suburbs">
                <div class="f-action-lnk-<?=$uniqueKey?> f-action-lnk-<?=$uniqueKey?>" @click="newListing.location = ''">clear</div>
            </div>
            <div class="f-input-wrap f-border-bottom">
                <div class="f-link-btn-byron f-ml-3" @click="showSelectCVFromFile('New')">
                    <div class="f-link-btn-icon f-member-blacklist-icon f-mt-2 f-ml-2"></div>
                    <div class="f-link-btn-text f-mt-2 f-ml-2">CV</div>
                    <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2">PDF Document</div>
                </div>
                <div class="f-input f-input-left">
                    <div class="f-list-item-name f-ml-3 f-mt-3">Click to upload CV</div>
                    <input type="file" hidden class="input" id="selectNewListingCVFromFile" accept="application/pdf" />
                </div>
            </div>
            <!-- <div class="fusion-description f-mt-3 f-ml-0 f-mb-0" v-show="!isEmpty(getListingCVFile)">Document uploaded</div> -->
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
            <div v-if="isEmpty(getListingLogo)" class="registration-image f-mt-3" @click="showSelectLogoFromFile('New')">
                <div class="f-upload-icon" style="
                    background-size: contain;
                    background-repeat: no-repeat;
                    background-position: center;
                "></div>
            </div>
            <div v-else class="registration-image f-mt-3"
                :style="displaySelectedListingLogo(getListingLogo)"
                @click="showSelectLogoFromFile('New')">
            </div>
            <input type="file" hidden class="input" id="selectNewListingLogoFromFile" accept="image/*" />
        </div>

        <div v-if="detailsTabs.tab == 'channels'" class="f-body-contents f-scroll f-scroll-container f-mt-3">
            <div class="fusion-descriptio f-mt-3 f-mb-3">
                Add any links you may wish to share.
            </div>

            <label class="f-df f-fr f-mt-3 ">
                Google URL
                <div class="f-tab-btn f-act-bg-<?=$uniqueKey?> f-ml-2" 
                    @click="openListingUrl(newListing.google_url)"
                    v-if="!isEmpty(newListing.google_url)"
                    style="
                        margin-top: -4px;
                        padding: calc(calc(2/750) * var(--seg_width)) calc(calc(15/750) * var(--seg_width)) calc(calc(2/750) * var(--seg_width)) calc(calc(15/750) * var(--seg_width)) !important;
                    ">Test
                </div>
            </label>
            <div class="f-input-wrap f-border-bottom f-mt-2">
                <input type="text" class="f-input f-input-left" v-model="newListing.google_url" placeholder="Google Business URL " >
                <div class="f-action-lnk-<?=$uniqueKey?>" 
                    v-if="!isEmpty(newListing.google_url)"
                    @click="newListing.google_url = ''">clear</div>
            </div>
            
            <label class="f-mt-2 f-df f-fr">
                Website URL
                <div class="f-tab-btn f-act-bg-<?=$uniqueKey?> f-ml-2" 
                    @click="openListingUrl(newListing.website_url)"
                    v-if="!isEmpty(newListing.website_url)"
                    style="
                        margin-top: -4px;
                        padding: calc(calc(2/750) * var(--seg_width)) calc(calc(15/750) * var(--seg_width)) calc(calc(2/750) * var(--seg_width)) calc(calc(15/750) * var(--seg_width)) !important;
                    ">Test
                </div>
            </label>
            <div class="f-input-wrap f-border-bottom f-mt-3">
                <input type="text" class="f-input f-input-left" v-model="newListing.website_url" placeholder="Website URL" >
                <div class="f-action-lnk-<?=$uniqueKey?>" 
                    v-if="!isEmpty(newListing.website_url)"
                    @click="newListing.website_url = ''">clear</div>
            </div>

            <label class="f-mt-2 f-df f-fr">
                Facebook URL
                <div class="f-tab-btn f-act-bg-<?=$uniqueKey?> f-ml-2" 
                    @click="openListingUrl(newListing.facebook_url)"
                    v-if="!isEmpty(newListing.facebook_url)"
                    style="
                        margin-top: -4px;
                        padding: calc(calc(2/750) * var(--seg_width)) calc(calc(15/750) * var(--seg_width)) calc(calc(2/750) * var(--seg_width)) calc(calc(15/750) * var(--seg_width)) !important;
                    ">Test
                </div>
            </label>
            <div class="f-input-wrap f-border-bottom f-mt-2">
                <input type="text" class="f-input f-input-left" v-model="newListing.facebook_url" placeholder="Facebook URL" >
                <div class="f-action-lnk-<?=$uniqueKey?>" 
                    v-if="!isEmpty(newListing.facebook_url)"
                    @click="newListing.facebook_url = ''">clear</div>
            </div>

            <label class="f-mt-2 f-df f-fr">
                X URL
                <div class="f-tab-btn f-act-bg-<?=$uniqueKey?> f-ml-2" 
                    @click="openListingUrl(newListing.x_url)"
                    v-if="!isEmpty(newListing.x_url)"
                    style="
                        margin-top: -4px;
                        padding: calc(calc(2/750) * var(--seg_width)) calc(calc(15/750) * var(--seg_width)) calc(calc(2/750) * var(--seg_width)) calc(calc(15/750) * var(--seg_width)) !important;
                    ">Test
                </div>
            </label>
            <div class="f-input-wrap f-border-bottom f-mt-2">
                <input type="text" class="f-input f-input-left" v-model="newListing.x_url" placeholder="X URL" >
                <div class="f-action-lnk-<?=$uniqueKey?>" 
                    v-if="!isEmpty(newListing.x_url)"
                    @click="newListing.x_url = ''">clear</div>
            </div>

            <label class="f-mt-2 f-df f-fr">
                Instagram URL
                <div class="f-tab-btn f-act-bg-<?=$uniqueKey?> f-ml-2" 
                    @click="openListingUrl(newListing.instagram_url)"
                    v-if="!isEmpty(newListing.instagram_url)"
                    style="
                        margin-top: -4px;
                        padding: calc(calc(2/750) * var(--seg_width)) calc(calc(15/750) * var(--seg_width)) calc(calc(2/750) * var(--seg_width)) calc(calc(15/750) * var(--seg_width)) !important;
                    ">Test
                </div>
            </label>
            <div class="f-input-wrap f-border-bottom f-mt-2">
                <input type="text" class="f-input f-input-left" v-model="newListing.instagram_url" placeholder="Instagram URL" >
                <div class="f-action-lnk-<?=$uniqueKey?>" 
                    v-if="!isEmpty(newListing.instagram_url)"
                    @click="newListing.instagram_url = ''">clear</div>
            </div>
            
        </div>
    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$uniqueKey?>">
            <div v-if="detailsTabs.tab == 'details'" class="f-button-seg2" @click="closeAddListingDetails()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div v-else class="f-button-seg2" @click="backTab('create_listing')">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div v-if="detailsTabs.tab == 'channels'" class="f-button-seg2" @click="showPreviewAddListing()">
                <div class="f-button-seg-icon f-next-icon"></div>
                <div class="f-button-text">Preview Listing</div>
            </div>
            <div v-else class="f-button-seg2" @click="nextTab('create_listing')">
                <div class="f-button-seg-icon f-next-icon"></div>
                <div class="f-button-text">Next</div>
            </div>
        </div>
    </div>

</div>


<div class="page f-page" :style="{ display: pages.previewAddListing ? 'flex': 'none' }" >

    <div class="f-body-no-header">
    
        <div class="f-body-contents-full">
            <div class="listing-listing f-fc f-bb-0">
                <div class="listing-listing-header f-mt-3 f-ml-3">

                    <div v-if="isEmpty(getListingLogo)" class="f-list-item-main-right-byron-no-iamge" 
                        style="
                            width: calc(calc(290/750) * var(--seg_width));
                            height: calc(calc(250/750) * var(--seg_width));
                            background-size: cover !important;
                            border-radius: calc(calc(20/750) * var(--seg_width));
                        ">{{ listingNoLogo(newListing.name) }}
                    </div>
                    <div v-else class="f-list-item-main-right-byron" 
                        :style="listingLogo(getListingLogo, '&w=360&q=100&zc=6')"
                        style="
                            width: calc(calc(290/750) * var(--seg_width));
                            height: calc(calc(250/750) * var(--seg_width));
                            background-size: cover !important;
                            border-radius: calc(calc(20/750) * var(--seg_width));
                        ">
                    </div>

                    <div class="f-list-item-middle f-df f-fc">
                        <div class="listing-listing-header-txt f-ml-3 f-mt-3" 
                            style="
                                font-size: calc(calc(35/750) * var(--seg_width));
                                width: calc(calc(370/750) * var(--seg_width)) !important;
                            ">
                            {{ newListing.name }}
                        </div>

                        <div class="listing-listing-body-txt f-ml-3 f-mt-1"
                            style="
                                font-size: calc(calc(24/750) * var(--seg_width));
                                width: calc(calc(370/750) * var(--seg_width)) !important;
                            ">
                            Age: {{ calculateAge(newListing.dob) }}
                        </div>

                        <div class="listing-listing-body-txt f-ml-3 f-mt-1"
                            style="
                                font-size: calc(calc(24/750) * var(--seg_width));
                                width: calc(calc(370/750) * var(--seg_width)) !important;
                            ">
                            Gender: {{ newListing.gender }}
                        </div>

                        <div class="listing-listing-header f-ml-3 f-mt-2" style="width: calc(calc(370/750) * var(--seg_width)) !important;">
                            <div class="f-list-item-main-right-byron-no-iamge"
                                style="
                                    width: calc(calc(170/750) * var(--seg_width));
                                    background-color: #ffffff !important;
                                ">
                                <div class="f-action-btn" 
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

                            <div class="listing-listing-header-nav-holder f-mt-1"
                                style="width: calc(calc(80/750) * var(--seg_width)) !important;">

                                <div class="listing-listing-header-txt-icon f-verified-icon f-mt-1 f-mr-1"
                                    style="background-size: calc(calc(40/750) * var(--seg_width));"></div>
                                
                            </div>

                            <div class="listing-listing-header-nav-holder f-fc f-mt-1"
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
                                    
                                    <div class="listing-listing-header-txt-icon f-gold-star-icon f-mr-1" 
                                        style="
                                            margin-top: calc(calc(-10/750) * var(--seg_width)) !important;
                                            background-size: calc(calc(40/750) * var(--seg_width));
                                        "></div>
                                    <div style="
                                            display: flex; 
                                            width: max-content;
                                        ">0</div>
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
                                        ">0 Ratings</div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

            <div class="listing-listing f-fc f-bb-1">
                <div class="listing-listing-header f-mt-2 f-mb-2 f-ml-3">
                    <div class="listing-listing-body-txt" v-html="newListing.description"></div>
                </div>
            </div>
        </div>

        <div class="f-link-btn-wrap f-scroll">
            <div class="f-link-btn-byron f-ml-3" @click="openDialerApp(newListing.contact_number)">
                <div class="f-link-btn-icon f-contact-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Call</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
            <div class="f-link-btn-byron f-ml-3" @click="openWhatsAppApp(newListing.contact_number)">
                <div class="f-link-btn-icon f-whatsapp-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">WhatsApp</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
            <div class="f-link-btn-byron f-ml-3" @click="openEmailApp(newListing.email)">
                <div class="f-link-btn-icon f-invite-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Email</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
            <div class="f-link-btn-byron f-ml-3" @click="">
                <div class="f-link-btn-icon f-add-fav-heart-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Add</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
            <div v-if="!isEmpty(newListing.google_url)" class="f-link-btn-byron f-ml-3" @click="openUrl(newListing.google_url)">
                <div class="f-link-btn-icon f-google-listing-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Google</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
            <div v-if="!isEmpty(newListing.website_url)" class="f-link-btn-byron f-ml-3" @click="openUrl(newListing.website_url)">
                <div class="f-link-btn-icon f-website-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Website</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
            <div v-if="!isEmpty(newListing.facebook_url)" class="f-link-btn-byron f-ml-3" @click="openUrl(newListing.facebook_url)">
                <div class="f-link-btn-icon f-facebook-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Facebook</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
            <div v-if="!isEmpty(newListing.x_url)" class="f-link-btn-byron f-ml-3" @click="openUrl(newListing.x_url)">
                <div class="f-link-btn-icon f-website-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">X</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
            <div v-if="!isEmpty(newListing.instagram_url)" class="f-link-btn-byron f-ml-3" @click="openUrl(newListing.instagram_url)">
                <div class="f-link-btn-icon f-instagram-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Instagram</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
            <div class="f-link-btn-byron f-ml-3" @click="openPDFDocument(newListing.cv_path)">
                <div class="f-link-btn-icon f-member-blacklist-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">CV - PDF DOC</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
        </div>
    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$uniqueKey?>">
            <div class="f-button" @click="closePreviewAddListing()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div class="f-button" @click="addNewPaidListing()">
                <div class="f-button-seg-icon f-pay-icon"></div>
                <div class="f-button-text">Submit Listing</div>
            </div>
            <div class="f-button" @click="cancelCreateNewListing()">
                <div class="f-button-seg-icon f-delete-icon"></div>
                <div class="f-button-text">Cancel Listing</div>
            </div>
        </div>
    </div>
</div>


<div class="page f-page" :style="{ display: pages.makeListingPayment ? 'flex': 'none' }">
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$uniqueKey?> f-mt-3">
            <div class="f-header-text-full f-ml-3">
                Pay Your Listing <div style="float: right;">4 of 4</div>
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
                    <div class="f-action-btn f-act-bg-<?=$uniqueKey?> f-mt-1 f-df f-fc" style="
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
                    @click="openPayFastRegistrationComponent('99')">
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
                    @click="openPayFastRegistrationComponent('270')">
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
                    @click="openPayFastRegistrationComponent('495')">
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
                    @click="openPayFastRegistrationComponent('990')">
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
                            @click="openPayFastRegistrationComponent('5')">Test Payment R5</div>
                    </div>
                <?php } ?>
            </div>
            
        </div>
    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$uniqueKey?>">
            <div class="f-button-seg2" @click="closeMakeListingPayment()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div class="f-button-seg2 f-act-btn" @click="payListingLater()">
                <div class="f-button-seg-icon f-pay-icon"></div>
                <div class="f-button-text">Pay Later</div>
            </div>
        </div>
    </div>

</div>


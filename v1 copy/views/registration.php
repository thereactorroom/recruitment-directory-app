

<div class="page f-page" :style="{ display: (pages.registration.landing) ? 'flex': 'none' }" >
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$unique_key?> f-mt-3">
            <div class="f-header-text-full f-ml-3">
                Sign-up Business <div style="position: relative; float: right;">1 of 4</div>
            </div> 
        </div>
    </div>

    <div class="f-body">
        <div class="f-body-contents f-scroll main-contents-<?=$unique_key?>">
            <div v-html="proposition.proposition" class="quill-content-<?=$unique_key?>"></div>
        </div>
    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$unique_key?>">
            <div class="f-button-seg2" @click="closeLandingPage()">
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


<div class="page f-page" :style="{ display: (pages.registration.search) ? 'flex': 'none' }" >
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$unique_key?> f-mt-3">
            <div class="f-header-text-full f-ml-3">
                Check Business <div style="position: relative; float: right;">2 of 4</div>
            </div> 
        </div>
        <div class="fusion-description f-mt-2">
            First, let's see if the community has already rated your business.</br><br/>If so, we'll automatically link that rating to your new listing.
        </div>
    </div>

    <div class="f-body">

        <div :style="{ display: (!registration.search.showSearchMobile) ? 'flex': 'none' }" class="f-body-contents f-mb-3">
            <div class="f-input-wrap f-border-bottom">
                <input type="text" class="f-input f-input-left" v-model="registration.search.name" placeholder="Business Name *" >
                <div class="f-action-lnk-<?=$unique_key?>" @click="registration.search.name = ''">clear</div>
            </div>
            <div class="f-input-wrap">
                <div class="f-list-item-left f-fc"></div>
                <div class="f-list-item-side"> 
                    <div class="f-action-btn f-act-bg-<?=$unique_key?> f-mt-1" @click="searchPreReg()">Search</div>
                </div>
            </div>
        </div>

        <div :style="{ display: (registration.search.showSearchMobile) ? 'flex': 'none' }" class="f-body-contents f-mb-3">

            <div class="f-input-wrap f-border-bottom">
                <input type="text" class="f-input f-input-left" v-model="registration.search.name" placeholder="Business Name *" >
                <div class="f-action-lnk-<?=$unique_key?>" @click="registration.search.name = ''">clear</div>
            </div>

            <div class="f-input-wrap f-mb-3 f-border-bottom">
                <input type="text" class="f-input-side" readonly placeholder="Country Code" v-model="registration.country_code">
                <input type="tel" class="f-input f-input-middle" v-model="registration.search.mobile" placeholder="Business Contact *" >
                <div class="f-action-lnk-<?=$unique_key?>" @click="registration.search.mobile = ''">clear</div>
            </div>

            <div class="f-input-wrap">
                <div class="f-list-item-left f-fc"></div>
                <div class="f-list-item-side"> 
                    <div class="f-action-btn f-act-bg-<?=$unique_key?> f-mt-1" @click="searchPreReg()">Search</div>
                </div>
            </div>
        </div>

    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$unique_key?>">
            <div class="f-button" @click="closeSearchPage()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div class="f-button" @click="showBusinessDetailsPage()">
                <div class="f-button-seg-icon f-next-icon"></div>
                <div class="f-button-text">Skip</div>
            </div>
        </div>
    </div>

</div>


<div class="page f-page" :style="{ display: (pages.registration.results) ? 'flex': 'none' }" >
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$unique_key?> f-mt-3">
            <div class="f-header-text-full f-ml-3">
                Search Results ({{ getRegistrationSearchResultsList.length }}) <div style="position: relative; float: right;">2 of 4</div>
            </div> 
        </div>
        <div class="fusion-description f-mt-2">
            Select from the results a Business that best match your Business or click "Skip" to continue.
        </div>
        <div class="listing-listing-footer f-ml-3"></div>
    </div>

    <div class="f-body">

        <div class="f-body-contents-full f-mb-3 f-scroll main-contents-<?=$unique_key?>">

            <div v-if="getRegistrationSearchResultsList.length == 0" class="listing-listing f-fc f-border-none">
                <div class="listing-listing-body f-fc f-mt-2 f-ml-3">
                    <div class="listing-listing-body-txt">No Results Found</div>
                </div>
            </div>

            <div v-else v-for="(business, index) in getRegistrationSearchResultsList" :key="index" 
                class="listing-listing f-fc"
                :class="{
                    'listing-listing-recommended': business.source == 'referral'
                }"
                @click="selectBusiness(business)">

                <div class="listing-listing-header f-mt-3 f-ml-3"
                    :class="{
                        'listing-listing-recommended': business.source == 'referral'
                    }">
                    <div class="listing-listing-header-icon-recommended"
                        :style="businessLogo('<?=$envHost?><?=$community_icon?>')"></div>
                    
                    <div class="listing-listing-header-txt-holder f-fc">
                        <div class="listing-listing-header-txt f-ml-2"
                            :class="{'listing-listing-header-txt-mt': business.business_name.length <= 27}"
                            style="font-size: calc(calc(30/750) * var(--seg_width));">
                            {{ business.business_name }}
                        </div>
                    </div>
                </div>

                <div class="listing-listing-body f-mt-2 f-ml-3"
                    :class="{
                        'listing-listing-recommended': business.source == 'referral'
                    }">
                    <div class="listing-listing-body-txt">{{ business.description.substring(0, 140) }}</div>
                </div>

                <div class="listing-listing-footer f-ml-3"></div>
            </div>

            <div v-if="registration.search.action == 'name'" class="listing-listing f-fc f-border-none f-mt-3">
                <div class="listing-listing-body f-fc f-mt-2 f-ml-3">
                    <div class="f-list-item-side" style="width: calc(calc(450/750) * var(--seg_width)) !important; justify-content: left;"> 
                        <div class="f-action-btn f-act-bg-<?=$unique_key?> f-mt-1" @click="searchWithMobile()">Search with Mobile Number</div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$unique_key?>">
            <div class="f-button" @click="closeSearchResultsPage()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div class="f-button" @click="showBusinessDetailsPage()">
                <div class="f-button-seg-icon f-next-icon"></div>
                <div class="f-button-text">Skip</div>
            </div>
        </div>
    </div>

</div>


<div class="page f-page" :style="{ display: (pages.registration._2fa) ? 'flex': 'none' }" >
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$unique_key?> f-mt-3">
            <div class="f-header-text-full f-ml-3">
                Verify Ownership <div style="position: relative; float: right;">2 of 4</div>
            </div> 
        </div>
        <div class="fusion-description f-mt-2">
            Please verify you own this business, by confirming the details below.
        </div>
    </div>

    <div class="f-body">
        <div class="f-body-contents f-mb-3 f-scroll main-contents-<?=$unique_key?>">

            <div class="listing-listing f-fc">
                <div class="listing-listing-header f-mt-3">
                    <div class="listing-listing-header-icon-recommended"
                        :style="businessLogo('<?=$envHost?><?=$community_icon?>')"></div>
                    
                    <div class="listing-listing-header-txt-holder f-fc">
                        <div class="listing-listing-header-txt f-ml-2"
                            :class="{'listing-listing-header-txt-mt': registration.business_name.length <= 27}"
                            style="font-size: calc(calc(30/750) * var(--seg_width));">
                            {{ registration.business_name }}
                        </div>
                    </div>
                </div>
                <div class="listing-listing-body f-fc f-mt-2">
                    <div class="listing-listing-body-txt">{{ registration.description.substring(0, 140) }}</div>
                </div>
                <div class="listing-listing-footer"></div>
            </div>

            <div v-if="!isEmpty(registration.maskEmail)" class="listing-listing f-fc f-border-none f-mt-3">
                <div class="f-mt-3">
                    Please complete the following email address:
                </div>
                <div class="f-mt-2">
                    {{registration.maskEmail}}
                </div>
                <div class="f-input-wrap f-border-bottom f-mt-3">
                    <input type="text" class="f-input f-input-left" v-model="registration.search._2fa" placeholder="Enter Email Address *" >
                    <div class="f-action-lnk-<?=$unique_key?>" @click="registration.search._2fa = ''">clear</div>
                </div>
                <div class="f-list-item-side f-mt-3" style="width: calc(calc(300/750) * var(--seg_width)) !important; justify-content: left;"> 
                    <div class="f-action-btn f-act-bg-<?=$unique_key?> f-mt-3" @click="verify2FA('email')">Verify Email</div>
                </div>
            </div>

            <div v-else-if="!isEmpty(registration.maskMobile)" class="listing-listing f-fc f-border-none">
                <div class="f-mt-3">
                    Please complete the following mobile number:
                </div>
                <div class="f-mt-2">
                    {{registration.maskMobile}}
                </div>
                <div class="f-input-wrap f-border-bottom f-mt-3">
                    <input type="text" class="f-input f-input-left" v-model="registration.search._2fa" placeholder="Enter Mobile Number *" >
                    <div class="f-action-lnk-<?=$unique_key?>" @click="registration.search._2fa = ''">clear</div>
                </div>
                <div class="f-list-item-side f-mt-3" style="width: calc(calc(300/750) * var(--seg_width)) !important; justify-content: left;"> 
                    <div class="f-action-btn f-act-bg-<?=$unique_key?> f-mt-3" @click="verify2FA('mobile')">Verify Mobile</div>
                </div>
            </div>

        </div>
    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$unique_key?>">
            <div class="f-button" @click="close2FAPage()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
        </div>
    </div>

</div>


<div class="page f-page" :style="{ display: (pages.registration.business) ? 'flex': 'none' }" >
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$unique_key?> f-mt-3">
            <div class="f-header-text-full f-ml-3">
                Business Listing <div style="float: right;">3 of 4</div>
            </div> 
        </div>
        <div class="fusion-description f-mt-2">
            Add your business details below.
        </div>
    </div>

    <div class="f-body">

        <!-- <div class="f-body-contents">
            <div class="f-input-wrap">
                <div class="f-tab-btn f-act-bg-<?=$unique_key?> f-mt-1" 
                    id="detailsTab<?=$unique_key?>"
                    @click="changeTab('details')">Details</div>
                <div class="f-tab-btn f-mt-1 f-ml-1" 
                    id="servicesTab<?=$unique_key?>"
                    @click="changeTab('services')">Services</div>
                <div class="f-tab-btn f-mt-1 f-ml-1" 
                    id="logoTab<?=$unique_key?>"
                    @click="changeTab('logo')">Logo</div>
                <div class="f-tab-btn f-mt-1 f-ml-1" 
                    id="channelsTab<?=$unique_key?>"
                    @click="changeTab('channels')">Channels</div>
            </div>
        </div> -->
        <div class="f-body-contents">
            <div class="f-input-wrap">
                <div class="f-tab-btn f-act-bg-<?=$unique_key?> f-mt-1 detailsTab<?=$unique_key?>" 
                    >Details</div>
                <div class="f-tab-btn f-mt-1 f-ml-1 servicesTab<?=$unique_key?>" 
                    >Services</div>
                <div class="f-tab-btn f-mt-1 f-ml-1 logoTab<?=$unique_key?>" 
                    >Logo</div>
                <div class="f-tab-btn f-mt-1 f-ml-1 channelsTab<?=$unique_key?>" 
                    >Channels</div>
            </div>
        </div>

        <div v-if="pages.navTabRegistration == 'details'" class="f-body-contents f-scroll f-scroll-container f-mt-3">
            <div class="f-input-wrap f-border-bottom">
                <input type="text" class="f-input f-input-left" v-model="registration.business_name" placeholder="Business Name *" >
                <div class="f-action-lnk-<?=$unique_key?>" @click="registration.business_name = ''">clear</div>
            </div>
            <div class="f-input-wrap f-border-bottom">
                <input type="text" class="f-input f-input-left" v-model="registration.vat_number" placeholder="VAT Number" >
                <div class="f-action-lnk-<?=$unique_key?>" @click="registration.vat_number = ''">clear</div>
            </div>
            <div class="f-input-wrap f-border-bottom">
                <input type="text" class="f-input f-input-left" v-model="registration.registration_number" placeholder="Registration Number" >
                <div class="f-action-lnk-<?=$unique_key?>" @click="registration.registration_number = ''">clear</div>
            </div>
            <div class="f-input-wrap f-border-bottom f-mt-2">
                <div class="listing-listing-header-icon f-input-side f-b-0 contact_number_flag" :style="displayContactFlag(contact.flag)"></div>
                <input type="tel" class="f-input f-input-middle" v-model="registration.contact_number" 
                    @input="changeContactFlag($event.target.value, '.contact_number_flag')" placeholder="Contact Number *" >
                <div v-if="settings.mobile()" class="f-action-lnk-<?=$unique_key?>" @click="selectRegistrationContact('contact_number')">select</div>
                <div v-else class="f-action-lnk-<?=$unique_key?>" @click="registration.contact_number = ''">clear</div>
            </div>
            <div class="f-input-wrap f-border-bottom f-mt-2">
                <div class="listing-listing-header-icon f-input-side f-b-0 office_number_flag" :style="displayContactFlag(contact.flag)"></div>
                <input type="tel" class="f-input f-input-middle" v-model="registration.office_number" 
                    @input="changeContactFlag($event.target.value, '.office_number_flag')" placeholder="Office Number" >
                <div v-if="settings.mobile()" class="f-action-lnk-<?=$unique_key?>" @click="selectRegistrationContact('office_number')">select</div>
                <div v-else class="f-action-lnk-<?=$unique_key?>" @click="registration.office_number = ''">clear</div>
            </div>
            <div class="f-input-wrap f-border-bottom f-mt-2">
                <input type="text" class="f-input f-input-left" v-model="registration.email" placeholder="Business Email *" >
                <div class="f-action-lnk-<?=$unique_key?>" @click="registration.email = ''">clear</div>
            </div>
            <div class="fusion-descriptio f-mt-3">
                Person responsible for the business
            </div>
            <div class="f-input-wrap f-border-bottom">
                <input type="text" class="f-input f-input-left" v-model="registration.person_name" placeholder="First Name *" >
                <div class="f-action-lnk-<?=$unique_key?>" @click="registration.person_name = ''">clear</div>
            </div>
            <div class="f-input-wrap f-border-bottom f-mt-2">
                <input type="text" class="f-input f-input-left" v-model="registration.person_surname" placeholder="Last Name *" >
                <div class="f-action-lnk-<?=$unique_key?>" @click="registration.person_surname = ''">clear</div>
            </div>
        </div>

        <div v-if="pages.navTabRegistration == 'services'" class="f-body-contents f-scroll f-scroll-container f-mt-3">
            <div class="fusion-descriptio f-mt-3">
                Please describe the services your business suppliers.
            </div>
            <div class="f-input-wrap f-border-bottom f-mt-2">
                <textarea type="text" rows="10" class="f-input f-input" v-model="registration.description" placeholder="E.g. Building, Tiling, Etc *" >
                    {{registration.description}}
                </textarea>
            </div>
            <div class="fusion-descriptio f-mt-3" style="justify-content: right;">
                <div>Characters: {{ registration.description.length }}/300</div>
            </div>
        </div>

        <div v-if="pages.navTabRegistration == 'logo'" class="f-body-contents f-scroll f-scroll-container f-mt-3">
            <div class="fusion-descriptio f-mt-3">
                Click the image button to upload a picture of your business's logo.
            </div>

            <div v-if="isEmpty(getRegistrationLogo)" class="registration-image f-mt-3" @click="selectLogoFromFileRegistration()">
                <div class="f-upload-icon" style="
                    background-size: contain;
                    background-repeat: no-repeat;
                    background-position: center;
                "></div>
            </div>

            <div v-else class="registration-image f-mt-3"
                :style="displayListingLogo(getRegistrationLogo, '&w=360&h=360&q=100&zc=6')"
                @click="selectLogoFromFileRegistration()">
                <!-- <img id="businessLogoCroppieHolder" src="#" > -->
                <!-- <div :style="displayListingLogo(getRegistrationLogo, '&w=360&h=360&q=100&zc=6')"></div> -->
            </div>

            <!-- <div class="f-input-wrap">
                <div class="f-list-item-side"> 
                    <div class="f-action-btn f-act-bg-<?=$unique_key?> f-mt-1" id="businessLogoCroppieCapture">Capture</div>
                </div>
            </div> -->

            <input type="file" hidden class="input" name="registrationBusinessLogo" id="registrationBusinessLogo" accept="image/*" />
        </div>

        <div v-if="pages.navTabRegistration == 'channels'" class="f-body-contents f-scroll f-scroll-container f-mt-3">
            <div class="fusion-descriptio f-mt-3 f-mb-3">
                If you have a Google Business listing, enter the URL bellow or click "Other".
            </div>

            <label class="f-df f-fr f-mt-3 ">
                Google URL
                <div class="f-tab-btn f-act-bg-<?=$unique_key?> f-ml-2" 
                    @click="testRegistrationUrl('google_url')"
                    v-if="!isEmpty(registration.google_url)"
                    style="
                        margin-top: -4px;
                        padding: calc(calc(2/750) * var(--seg_width)) calc(calc(15/750) * var(--seg_width)) calc(calc(2/750) * var(--seg_width)) calc(calc(15/750) * var(--seg_width)) !important;
                    ">Test
                </div>
            </label>
            <div class="f-input-wrap f-border-bottom f-mt-2">
                <input type="text" class="f-input f-input-left" v-model="registration.google_url" placeholder="Google Business URL " >
                <div class="f-action-lnk-<?=$unique_key?>" 
                    v-if="!isEmpty(registration.google_url)"
                    @click="registration.google_url = ''">clear</div>
            </div>
            
            <!-- <div class="f-input-wrap">
                <div class="f-list-item-left f-fc"></div>
                <div class="f-list-item-side f-ml-3" style="justify-content: right;"> 
                    <div v-if="!registration.show_other" class="f-action-btn f-act-bg-<?=$unique_key?> f-mt-1" @click="toggleOtherChannel()">Other</div>
                    <div v-else class="f-action-btn f-act-bg-<?=$unique_key?> f-mt-1" @click="toggleOtherChannel()">Hide</div>
                </div>
            </div> -->
            
            <!-- <div v-if="registration.show_other"> -->

                <label class="f-mt-2 f-df f-fr">
                    Website URL
                    <div class="f-tab-btn f-act-bg-<?=$unique_key?> f-ml-2" 
                        @click="testRegistrationUrl('website_url')"
                        v-if="!isEmpty(registration.website_url)"
                        style="
                            margin-top: -4px;
                            padding: calc(calc(2/750) * var(--seg_width)) calc(calc(15/750) * var(--seg_width)) calc(calc(2/750) * var(--seg_width)) calc(calc(15/750) * var(--seg_width)) !important;
                        ">Test
                    </div>
                </label>
                <div class="f-input-wrap f-border-bottom f-mt-3">
                    <input type="text" class="f-input f-input-left" v-model="registration.website_url" placeholder="Website URL" >
                    <div class="f-action-lnk-<?=$unique_key?>" 
                        v-if="!isEmpty(registration.website_url)"
                        @click="registration.website_url = ''">clear</div>
                </div>

                <label class="f-mt-2 f-df f-fr">
                    Facebook URL
                    <div class="f-tab-btn f-act-bg-<?=$unique_key?> f-ml-2" 
                        @click="testRegistrationUrl('facebook_url')"
                        v-if="!isEmpty(registration.facebook_url)"
                        style="
                            margin-top: -4px;
                            padding: calc(calc(2/750) * var(--seg_width)) calc(calc(15/750) * var(--seg_width)) calc(calc(2/750) * var(--seg_width)) calc(calc(15/750) * var(--seg_width)) !important;
                        ">Test
                    </div>
                </label>
                <div class="f-input-wrap f-border-bottom f-mt-2">
                    <input type="text" class="f-input f-input-left" v-model="registration.facebook_url" placeholder="Facebook URL" >
                    <div class="f-action-lnk-<?=$unique_key?>" 
                        v-if="!isEmpty(registration.facebook_url)"
                        @click="registration.facebook_url = ''">clear</div>
                </div>

                <label class="f-mt-2 f-df f-fr">
                    X URL
                    <div class="f-tab-btn f-act-bg-<?=$unique_key?> f-ml-2" 
                        @click="testRegistrationUrl('x_url')"
                        v-if="!isEmpty(registration.x_url)"
                        style="
                            margin-top: -4px;
                            padding: calc(calc(2/750) * var(--seg_width)) calc(calc(15/750) * var(--seg_width)) calc(calc(2/750) * var(--seg_width)) calc(calc(15/750) * var(--seg_width)) !important;
                        ">Test
                    </div>
                </label>
                <div class="f-input-wrap f-border-bottom f-mt-2">
                    <input type="text" class="f-input f-input-left" v-model="registration.x_url" placeholder="X URL" >
                    <div class="f-action-lnk-<?=$unique_key?>" 
                        v-if="!isEmpty(registration.x_url)"
                        @click="registration.x_url = ''">clear</div>
                </div>

                <label class="f-mt-2 f-df f-fr">
                    Instagram URL
                    <div class="f-tab-btn f-act-bg-<?=$unique_key?> f-ml-2" 
                        @click="testRegistrationUrl('instagram_url')"
                        v-if="!isEmpty(registration.instagram_url)"
                        style="
                            margin-top: -4px;
                            padding: calc(calc(2/750) * var(--seg_width)) calc(calc(15/750) * var(--seg_width)) calc(calc(2/750) * var(--seg_width)) calc(calc(15/750) * var(--seg_width)) !important;
                        ">Test
                    </div>
                </label>
                <div class="f-input-wrap f-border-bottom f-mt-2">
                    <input type="text" class="f-input f-input-left" v-model="registration.instagram_url" placeholder="Instagram URL" >
                    <div class="f-action-lnk-<?=$unique_key?>" 
                        v-if="!isEmpty(registration.instagram_url)"
                        @click="registration.instagram_url = ''">clear</div>
                </div>
            <!-- </div> -->
            
        </div>
    </div>

    <!-- <div class="f-footer">
        <div class="f-buttons-<?=$unique_key?>">
            <div class="f-button" @click="closeBusinessDetailsPage()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div class="f-button" @click="showPreviewListingPage()">
                <div class="f-button-seg-icon f-next-icon"></div>
                <div class="f-button-text">Preview Listings</div>
            </div>
        </div>
    </div> -->
    <div class="f-footer">
        <div class="f-buttons-<?=$unique_key?>">
            <div v-if="pages.navTabRegistration == 'details'" class="f-button-seg2" @click="closeBusinessDetailsPage()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div v-else class="f-button-seg2" @click="backTab('registration')">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div v-if="pages.navTabRegistration == 'channels'" class="f-button-seg2" @click="showPreviewListingPage()">
                <div class="f-button-seg-icon f-next-icon"></div>
                <div class="f-button-text">Preview Listing</div>
            </div>
            <div v-else class="f-button-seg2" @click="nextTab('registration')">
                <div class="f-button-seg-icon f-next-icon"></div>
                <div class="f-button-text">Next</div>
            </div>
        </div>
    </div>

</div>


<div class="page f-page" :style="{ display: (pages.registration.croppie) ? 'flex': 'none' }" >

    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$unique_key?> f-mt-3">
            <div class="f-header-text-full f-ml-3">
                Business Listing Logo <div style="float: right;">3 of 4</div>
            </div> 
        </div>
    </div>

    <div class="f-body">
        <div class="f-body-contents f-scroll f-scroll-container f-mt-3">

            <!-- <div class="registration-image f-mt-3" :style="displayListingLogo(getRegistrationLogo, '&w=360&h=360&q=100&zc=6')">
            </div> -->

            <div class="f-input-wrap">
                <img src="#" id="registration-croppie-image" class="registration-croppie-image">
            </div>
        </div>
    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$unique_key?>">
            <div class="f-button registration-croppie-done" >
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Done</div>
            </div>
        </div>
    </div>

</div>


<div class="page f-page" :style="{ display: (pages.registration.preview) ? 'flex': 'none' }" >

    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$unique_key?>">
            <div class="f-list-item f-fc f-mb-3 f-bb-0">
                <div class="f-list-item-wrap f-fr f-ml-3" style="justify-content: center">

                    <div v-if="isEmpty(registration.logo)" class="f-list-item-main-right-byron-no-iamge" 
                        >{{registrationNoLogo(registration)}}</div>
                    <div v-else class="f-list-item-main-right-byron" 
                        :style="businessLogo(registration.logo, '&w=360&q=100&zc=6')"
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
                            {{ registration.business_name }}
                        </div>
                    </div>
                    <div class="listing-listing-header-nav-holder">
                        <div class="listing-listing-header-nav-btn-comment"></div>
                        <div class="listing-listing-header-nav-btn-stars">
                            <div class="listing-listing-header-nav-btn">
                                <div class="listing-listing-header-nav-btn-txt f-color-grey">-</div>
                                <div class="listing-listing-header-nav-btn-star-icon f-grey-star-icon"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="listing-listing-body f-mt-2 f-ml-3">
                    <div class="listing-listing-body-txt" v-html="registration.description"></div>
                </div>
                <div class="listing-listing-footer f-ml-3"></div>
            </div>
        </div>

        <div class="f-link-btn-wrap">
            <div class="f-link-btn-byron f-ml-3" @click="openDialerApp(registration.contact_number)">
                <div class="f-link-btn-icon f-contact-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Call</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
            <div class="f-link-btn-byron f-ml-3" @click="openWhatsAppApp(registration.contact_number)">
                <div class="f-link-btn-icon f-whatsapp-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">WhatsApp</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
            <div class="f-link-btn-byron f-ml-3" @click="openEmailApp(registration.email)">
                <div class="f-link-btn-icon f-invite-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Email</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
            <div class="f-link-btn-byron f-ml-3" @click="">
                <div class="f-link-btn-icon f-add-fav-heart-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Add</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
            <div v-if="!isEmpty(registration.google_url)" class="f-link-btn-byron f-ml-3" @click="openUrl(registration.google_url)">
                <div class="f-link-btn-icon f-google-listing-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Google</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
            <div v-if="!isEmpty(registration.website_url)" class="f-link-btn-byron f-ml-3" @click="openUrl(registration.website_url)">
                <div class="f-link-btn-icon f-website-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Website</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
            <div v-if="!isEmpty(registration.facebook_url)" class="f-link-btn-byron f-ml-3" @click="openUrl(registration.facebook_url)">
                <div class="f-link-btn-icon f-facebook-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Facebook</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
            <div v-if="!isEmpty(registration.x_url)" class="f-link-btn-byron f-ml-3" @click="openUrl(registration.x_url)">
                <div class="f-link-btn-icon f-website-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">X</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
            <div v-if="!isEmpty(registration.instagram_url)" class="f-link-btn-byron f-ml-3" @click="openUrl(registration.instagram_url)">
                <div class="f-link-btn-icon f-instagram-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Instagram</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
        </div>
    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$unique_key?>">
            <div class="f-button" @click="closePreviewListingPage()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div class="f-button" @click="saveBusinessDetails()">
                <div class="f-button-seg-icon f-pay-icon"></div>
                <div class="f-button-text">Submit Business</div>
            </div>
            <div class="f-button" @click="cancelListing()">
                <div class="f-button-seg-icon f-delete-icon"></div>
                <div class="f-button-text">Cancel Listings</div>
            </div>
        </div>
    </div>

</div>


<div class="page f-page" :style="{ display: (pages.registration.payment) ? 'flex': 'none' }" >
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$unique_key?> f-mt-3">
            <div class="f-header-text-full f-ml-3">
                List Your Business <div style="float: right;">4 of 4</div>
            </div> 
        </div>
        <div class="fusion-description f-mt-2">
            Choose your plan:
        </div>
    </div>

    <div class="f-body">
        <div class="f-body-contents-full f-mb-3 f-scroll main-contents-<?=$unique_key?>">

        <div class="f-list-item f-fr f-mt-2 f-bb-0 f-mb-3">
            <div class="f-list-item-wrap f-df f-fc f-ml-3" style="
                    display: block;
                    width: calc(calc(335/750) * var(--seg_width)) !important; 
                    height: calc(calc(200/750) * var(--seg_width)) !important; 
                "
                @click="openPayFastRegistrationComponent('99')">
                    <div class="f-action-btn f-act-bg-<?=$unique_key?> f-mt-1 f-df f-fc" style="
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
                    <div class="f-action-btn f-act-bg-<?=$unique_key?> f-mt-1 f-df f-fc" style="
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
                    <div class="f-action-btn f-act-bg-<?=$unique_key?> f-mt-1 f-df f-fc" style="
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
                        <div class="f-action-btn f-act-bg-<?=$unique_key?> f-act-btn" style="
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
                    <div class="f-action-btn f-act-bg-<?=$unique_key?> f-mt-1 f-df f-fc" style="
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
                        <div class="f-action-btn f-act-bg-<?=$unique_key?> f-act-btn" style="
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

            <!-- <div class="f-list-item f-fr f-mt-2 f-bb-0 f-mb-3">
                <div class="f-list-item-wrap f-df f-fc f-ml-3" style="
                    display: block;
                    width: calc(calc(335/750) * var(--seg_width)) !important; 
                    height: calc(calc(200/750) * var(--seg_width)) !important; 
                "
                @click="openPayFastRegistrationComponent('99')">
                    <div class="f-action-btn f-act-bg-<?=$unique_key?> f-mt-1 f-df f-fc" style="
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
                <div class="f-list-item-wrap f-df f-fc f-ml-2" style="
                    display: block;
                    width: calc(calc(335/750) * var(--seg_width)) !important; 
                    height: calc(calc(200/750) * var(--seg_width)) !important; 
                "
                @click="openPayFastRegistrationComponent('990')">
                    <div class="f-action-btn f-act-bg-<?=$unique_key?> f-mt-1 f-df f-fc" style="
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
                        <div class="f-action-btn f-act-bg-<?=$unique_key?> f-act-btn" style="
                            z-index: 10000;
                            position: fixed;
                            font-size: calc(calc(20/750) * var(--seg_width)) !important; 
                            height: calc(calc(60/750) * var(--seg_width)) !important; 
                            width: calc(calc(220/750) * var(--seg_width)) !important;
                            margin-top: calc(calc(-30/750) * var(--seg_width)) !important;
                            right: calc(calc(90/750) * var(--seg_width)) !important;">
                            2 Months Free
                        </div>
                    </div>
                </div>
            </div> -->
            
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
                        If you choose to "Pay Later", your listing will remain hidden in the business directory until payment is made.
                    </div>
                </div>
            </div>

            <div class="f-list-item f-fc f-mt-2 f-bb-0">
                <?php if($env == "sandbox") { ?>
                    <div class="f-list-item-wrap f-ml-3 f-mt-3">
                        <div class="f-action-btn f-act-bg-<?=$unique_key?> f-mt-1" 
                            style="width: calc(calc(690/750) * var(--seg_width)) !important;"
                            @click="openPayFastRegistrationComponent('5')">Test Payment R5</div>
                    </div>
                <?php } ?>
            </div>

        </div>
    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$unique_key?>">
            <div class="f-button-seg2" @click="closeRegistrationPaymentPage()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div class="f-button-seg2 f-act-btn" @click="payLater()">
                <div class="f-button-seg-icon f-pay-icon"></div>
                <div class="f-button-text">Pay Later</div>
            </div>
        </div>
    </div>

</div>






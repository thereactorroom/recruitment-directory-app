
<div class="page f-page" :style="{ display: pages.addFreeListing ? 'flex': 'none' }">
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$uniqueKey?> f-mt-3">
            <div class="f-header-text-full f-ml-3">
                Physio Directory
            </div>
        </div>
        <div class="fusion-description f-mt-2">
            Enter the personal details for the directory listings.
        </div>
    </div>

    <div class="f-body">
        <div class="f-body-contents f-scroll main-contents-<?=$uniqueKey?> f-mb-3" 
            style="height: calc(var(--s_height) - calc(calc(400/750) * var(--seg_width))) !important;">

            <div class="f-input-wrap f-mb-3 f-border-bottom">
                <div class="listing-listing-header-icon f-input-side f-b-0 contact_number_flag" :style="displayContactFlag(contact.flag)"></div>

                <input type="tel" class="f-input f-input-middle" v-model="newListing.contact_number" 
                    @input="changeContactFlag($event.target.value, '.contact_number_flag')" placeholder="0721234567 *" >

                <div v-if="isMobile" class="f-action-lnk-<?=$uniqueKey?> f-action-lnk-<?=$uniqueKey?>" @click="selectContactNumber('decodeNewListingContact')">select</div>
                <div v-else class="f-action-lnk-<?=$uniqueKey?> f-action-lnk-<?=$uniqueKey?>" @click="newListing.contact_number = ''">clear</div>
            </div>

            <div class="f-input-wrap f-border-bottom">
                <input type="text" class="f-input f-input-left" v-model="newListing.name" placeholder="Name of Person or Business  *" >
                <div class="f-action-lnk-<?=$uniqueKey?> f-action-lnk-<?=$uniqueKey?>" @click="newListing.name = ''">clear</div>
            </div>

            <div class="fusion-description f-mt-3 f-ml-0 f-mb-0">List Skills:</div>
            <div class="f-input-wrap f-mb-3 f-border-bottom">
                <input type="text" class="f-input f-input-left" v-model="newListing.description" placeholder="E.g. Physio, Needling, Etc" >
                <div class="f-action-lnk-<?=$uniqueKey?> f-action-lnk-<?=$uniqueKey?>" @click="newListing.description = ''">clear</div>
            </div>

            <div class="fusion-description f-mt-3 f-ml-0 f-mb-0">Enter Work Locations:</div>
            <div class="f-input-wrap f-mb-3 f-border-bottom">
                <input type="text" class="f-input f-input-left" v-model="newListing.location" placeholder="City, Suburbs">
                <div class="f-action-lnk-<?=$uniqueKey?> f-action-lnk-<?=$uniqueKey?>" @click="newListing.location = ''">clear</div>
            </div>

            <div class="fusion-description f-mt-3 f-ml-0 f-mb-0">Enter your comment below.</div>
            <div class="f-input-wrap">
                <textarea class="f-input" rows="3" v-model="newListing.comment"></textarea>
            </div>

            <div class="f-input-wrap">
                <div class="f-icon-wrap f-mt-2">
                    <div class="f-icon-icon f-grey-star-icon"
                        id="add_rec_star_1_<?=$uniqueKey?>"
                        @click="selectRatingStars(1,'add')">
                    </div>
                </div>
                <div class="f-icon-wrap f-mt-2 f-ml-1">
                    <div class="f-icon-icon f-grey-star-icon"
                        id="add_rec_star_2_<?=$uniqueKey?>"
                        @click="selectRatingStars(2,'add')">
                    </div>
                </div>
                <div class="f-icon-wrap f-mt-2 f-ml-1">
                    <div class="f-icon-icon f-grey-star-icon"
                        id="add_rec_star_3_<?=$uniqueKey?>"
                        @click="selectRatingStars(3,'add')">
                    </div>
                </div>
                <div class="f-icon-wrap f-mt-2 f-ml-1">
                    <div class="f-icon-icon f-grey-star-icon"
                        id="add_rec_star_4_<?=$uniqueKey?>"
                        @click="selectRatingStars(4,'add')">
                    </div>
                </div>
                <div class="f-icon-wrap f-mt-2 f-ml-1">
                    <div class="f-icon-icon f-grey-star-icon"
                        id="add_rec_star_5_<?=$uniqueKey?>"
                        @click="selectRatingStars(5,'add')">
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$uniqueKey?>">
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
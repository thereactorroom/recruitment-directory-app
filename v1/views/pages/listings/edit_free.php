
<div class="page f-page" :style="{ display: pages.editFreeListing ? 'flex': 'none' }">
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$uniqueKey?> f-mt-3">
            <div class="f-header-text-full f-ml-3">
                {{ listing.name }}
            </div>
        </div>
    </div>

    <div class="f-body">
        <div class="f-body-contents f-scroll main-contents-<?=$uniqueKey?>">

            <div class="f-input-wrap f-mb-3 f-border-bottom">
                <div class="listing-listing-header-icon f-input-side f-b-0 contact_number_flag" :style="displayContactFlag(contact.flag)"></div>

                <input type="tel" class="f-input f-input-middle" v-model="listing.contact_number" 
                    @input="changeContactFlag($event.target.value, '.contact_number_flag')" placeholder="0721234567 *" >

                <div v-if="isMobile" class="f-action-lnk-<?=$uniqueKey?> f-action-lnk-<?=$uniqueKey?>" 
                    @click="selectContactNumber('decodeEditListingContact')">select</div>
                <div v-else class="f-action-lnk-<?=$uniqueKey?> f-action-lnk-<?=$uniqueKey?>" @click="listing.contact_number = ''">clear</div>
            </div>

            <div class="f-input-wrap f-border-bottom">
                <input type="text" class="f-input f-input-left" v-model="listing.name" placeholder="Name of Person or Business  *" >
                <div class="f-action-lnk-<?=$uniqueKey?> f-action-lnk-<?=$uniqueKey?>" @click="listing.name = ''">clear</div>
            </div>

            <div class="fusion-description f-mt-3 f-ml-0 f-mb-0">List Skills:</div>
            <div class="f-input-wrap f-mb-3 f-border-bottom">
                <input type="text" class="f-input f-input-left" v-model="listing.description" placeholder="E.g. Physio, Needling, Etc" >
                <div class="f-action-lnk-<?=$uniqueKey?> f-action-lnk-<?=$uniqueKey?>" @click="listing.description = ''">clear</div>
            </div>

            <div class="fusion-description f-mt-3 f-ml-0 f-mb-0">Enter Work Locations:</div>
            <div class="f-input-wrap f-mb-3 f-border-bottom">
                <input type="text" class="f-input f-input-left" v-model="listing.location" placeholder="City, Suburbs">
                <div class="f-action-lnk-<?=$uniqueKey?> f-action-lnk-<?=$uniqueKey?>" @click="listing.location = ''">clear</div>
            </div>
        </div>
    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$uniqueKey?>">
            <div class="f-button-seg3" @click="closeEditFreeListing()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div class="f-button-seg3 f-act-btn" @click="updateListing()">
                <div class="f-button-seg-icon f-add-icon"></div>
                <div class="f-button-text">Save</div>
            </div>
            <div class="f-button-seg3" @click="deleteListing()">
                <div class="f-button-seg-icon f-delete-icon"></div>
                <div class="f-button-text">Delete</div>
            </div>
        </div>
    </div>

</div>
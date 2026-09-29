

<div class="page f-page" :style="{ display: pages.listingSelectGender ? 'flex': 'none' }">
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$uniqueKey?> f-fc f-mt-3">
            <div class="f-header-text-full f-ml-3">
                Select Gender
            </div>
        </div>
        <!-- <div class="fusion-description f-mt-2">
            Select Gender
        </div> -->
    </div>

    <div class="f-body">
        <div class="f-body-contents f-mb-3">
            <div class="f-input-wrap f-border-bottom">
                <div class="f-input f-input-left">
                    <div class="f-list-item-name f-ml-3 f-mt-3">Male</div>
                </div>
                <div class="f-list-item-side f-mb-2"> 
                    <div class="f-action-btn f-act-bg-<?=$uniqueKey?> f-mt-1" @click="selectListingGender('Male')">Select</div>
                </div>
            </div>
            <div class="f-input-wrap f-border-bottom">
                <div class="f-input f-input-left">
                    <div class="f-list-item-name f-ml-3 f-mt-3">Female</div>
                </div>
                <div class="f-list-item-side f-mb-2"> 
                    <div class="f-action-btn f-act-bg-<?=$uniqueKey?> f-mt-1" @click="selectListingGender('Female')">Select</div>
                </div>
            </div>
        </div>
    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$uniqueKey?>">
            <div class="f-button" @click="closeSelectListingGender()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
        </div>
    </div>

</div>

<div class="page f-page" :style="{ display: (pages.editVoucher) ? 'flex': 'none' }" >
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$uniqueKey?> f-mt-3">
            <div class="f-header-text-full f-ml-3">
                Edit Voucher
            </div>
        </div>
        <div class="fusion-description f-mt-2">
            Enter the details below to update vooucher
        </div>
    </div>

    <div class="f-body">
        <div class="f-body-contents f-scroll main-contents-<?=$uniqueKey?>">
            <div class="fusion-description f-mt-3 f-ml-0 f-mb-0">How many vouchers you want to create.</div>
            <div class="f-input-wrap f-border-bottom">
                <input v-if="isMobile" type="tel" class="f-input f-input-left f-b-0" v-model="newVoucher.capacity" placeholder="Enter Voucher Capacity  *" >
                <input v-else type="text" class="f-input f-input-left f-b-0" v-model="newVoucher.capacity" placeholder="Enter Voucher Capacity  *" >
                <div class="f-action-lnk-<?=$uniqueKey?>" @click="newVoucher.capacity = 1">clear</div>
            </div>
            <div class="fusion-description f-mt-3 f-ml-0 f-mb-0">Enter campaign start date *</div>
            <div class="f-input-wrap f-border-bottom">
                <input type="text" class="f-input f-input-left datepicker" 
                    id="editStartDateValue<?=$uniqueKey?>"
                    v-model="newVoucher.start_date" 
                    placeholder="Select start date" 
                    style="background-color: #ffffff;">
            </div>
            <div class="fusion-description f-mt-3 f-ml-0 f-mb-0">Enter campaign end date *</div>
            <div class="f-input-wrap f-border-bottom">
                <input type="text" class="f-input f-input-left datepicker" 
                    id="editEndDateValue<?=$uniqueKey?>"
                    v-model="newVoucher.end_date" 
                    placeholder="Select end date" 
                    style="background-color: #ffffff;">

            </div>
        </div>
    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$uniqueKey?>">
            <div class="f-button-seg2" @click="closeEditVoucherPage()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div class="f-button-seg2 f-act-btn" @click="updateVoucher()">
                <div class="f-button-seg-icon f-add-icon"></div>
                <div class="f-button-text">Save</div>
            </div>
        </div>
    </div>
</div>
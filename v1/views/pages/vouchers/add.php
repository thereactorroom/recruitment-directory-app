

<div class="page f-page" :style="{ display: (pages.addVoucher) ? 'flex': 'none' }" >
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$uniqueKey?> f-mt-3">
            <div class="f-header-text-full f-ml-3">
                New Voucher
            </div>
        </div>
        <div class="fusion-description f-mt-2">
            Enter the details bellow to create a Voucher.
        </div>
    </div>

    <div class="f-body">

        <div class="f-body-contents f-scroll main-contents-<?=$uniqueKey?>">
            <div class="fusion-description f-mt-3 f-ml-0 f-mb-0">Enter voucher code.</div>
            <div class="f-input-wrap f-border-bottom">
                <input type="text" class="f-input f-input-left" v-model="newVoucher.code" placeholder="Enter Voucher Code" >
                <div v-show="!isEmpty(newVoucher.code)" class="f-action-lnk-<?=$uniqueKey?>" @click="newVoucher.code = ''">clear</div>
            </div>

            <div class="fusion-description f-mt-3 f-ml-0 f-mb-0">How many vouchers you want to create.</div>
            <div class="f-input-wrap f-border-bottom">
                <input v-if="isMobile" type="tel" class="f-input f-input-left f-b-0" v-model="newVoucher.capacity" placeholder="Enter Voucher Capacity *" >
                <input v-else type="text" class="f-input f-input-left f-b-0" v-model="newVoucher.capacity" placeholder="Enter Voucher Capacity *" >
                <div v-show="!isEmpty(newVoucher.capacity)" class="f-action-lnk-<?=$uniqueKey?>" @click="newVoucher.capacity = ''">clear</div>
            </div>

            <div class="fusion-description f-mt-3 f-ml-0 f-mb-0">Enter months for the Voucher period*</div>
            <div class="f-input-wrap f-border-bottom">
                <input v-if="isMobile" type="tel" class="f-input f-input-left f-b-0" v-model="newVoucher.period_length" placeholder="E.g) 3, = 3 months " >
                <input v-else type="text" class="f-input f-input-left f-b-0" v-model="newVoucher.period_length" placeholder="E.g) 3, = 3 months *" >
                <div v-show="!isEmpty(newVoucher.period_length)" class="f-action-lnk-<?=$uniqueKey?>" @click="newVoucher.period_length = ''">clear</div>
            </div>

            <div class="fusion-description f-mt-3 f-ml-0 f-mb-0">Enter campaign start date *</div>
            <div class="f-input-wrap f-border-bottom">
                <input type="text" class="f-input f-input-left datepicker" 
                    id="addStartDateValue<?=$uniqueKey?>"
                    v-model="newVoucher.start_date" 
                    placeholder="Select start date" 
                    style="background-color: #ffffff;">

                <div v-show="!isEmpty(newVoucher.start_date)" class="f-action-lnk-<?=$uniqueKey?>" @click="newVoucher.start_date = ''">clear</div>
            </div>

            <div class="fusion-description f-mt-3 f-ml-0 f-mb-0">Enter campaign end date *</div>
            <div class="f-input-wrap f-border-bottom">
                <input type="text" class="f-input f-input-left datepicker" 
                    id="addEndDateValue<?=$uniqueKey?>"
                    v-model="newVoucher.end_date" 
                    placeholder="Select date end date" 
                    style="background-color: #ffffff;">

                <div v-show="!isEmpty(newVoucher.end_date)" class="f-action-lnk-<?=$uniqueKey?>" @click="newVoucher.end_date = ''">clear</div>
            </div>
        </div>

    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$uniqueKey?>">
            <div class="f-button-seg2" @click="closeAddVoucherPage()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div class="f-button-seg2 f-act-btn" @click="addVoucher()">
                <div class="f-button-seg-icon f-add-icon"></div>
                <div class="f-button-text">Add Voucher</div>
            </div>
        </div>
    </div>
</div>


<div class="f-overlay" :style="{ display: (pages.voucherPayment) ? 'flex': 'none' }" 
    @click.self="closeVoucherPaymentPage()">
    <div class="f-overlay-content">

        <div class="f-overlay-header f-fc">
            <div class="fusion-heading f-heading-<?=$uniqueKey?> f-mt-3 f-mb-3">
                Pay with Voucher
            </div>
        </div>

        <div class="f-overlay-body">
            <div class="f-body-contents f-scroll f-scroll-container f-mt-3 f-mb-3">
                <div class="fusion-descriptio">
                    Enter a voucher code below to claim free listing.
                </div>

                <div class="f-input-wrap f-border-bottom f-mt-2">
                    <input type="text" class="f-input f-input-left" v-model="newVoucher.searchCode" placeholder="Enter voucher code claim">                
                    <div class="f-action-lnk-<?=$uniqueKey?>" @click="newVoucher.searchCode = ''">clear</div>
                </div>
                <div class="f-input-wrap f-mt-2">
                    <div v-if="newListing.id > 0" class="f-action-btn f-act-bg-<?=$uniqueKey?> f-mt-1 f-act-btn"  
                        style="width: calc(calc(690/750) * var(--seg_width)) !important;"
                        @click="payWithVoucher()">Claim Voucher
                    </div>
                    <div v-else class="f-action-btn f-act-bg-<?=$uniqueKey?> f-mt-1 f-act-btn" 
                        style="width: calc(calc(690/750) * var(--seg_width)) !important;"
                        @click="claimVoucher()">Claim Voucher
                    </div>
                </div>
            </div>
        </div>

        <div class="f-overlay-footer">
            <div class="f-buttons-<?=$uniqueKey?>">
                <div class="f-button" @click="closeVoucherPaymentPage()">
                    <div class="f-button-seg-icon f-back-icon"></div>
                    <div class="f-button-text">Back</div>
                </div>
            </div>
        </div>

    </div>
</div>


<div class="page f-page" :style="{ display: pages.listVouchers ? 'flex': 'none' }" >
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$uniqueKey?> f-fc f-mt-3">
            <div class="f-header-text-full f-ml-3">
                Vouchers
            </div>
        </div>
        <div class="fusion-description f-mt-2">
            Click to view voucher information.
        </div>
    </div>

    <div class="f-body">

        <div class="f-body-contents-full f-scroll f-mb-3"
            style="height: calc(var(--s_height) - calc(calc(320/750) * var(--seg_width))) !important;">
            
            <div v-for="(voucher, index) in getVouchersList" :key="index" class="f-list-item f-fc f-mb-2">
                <div class="f-list-item-wrap f-fr f-ml-3 f-mb-3" @click="showViewVoucherPage(voucher)">
                    <div class="f-list-item-left-3x f-fc">
                        <div class="f-list-item-name f-mt-3">{{ voucher.code }}</div>
                        <div class="f-list-item-description f-mt-1">
                            {{ voucher.description }} &nbsp;&nbsp;
                            <b>Dates: &nbsp;</b> {{ voucher.start_date }} to {{ voucher.end_date }}
                        </div>
                        <div class="f-list-item-description f-mt-1">
                            <b>Capacity:&nbsp;</b> {{ voucher.capacity }} &nbsp;&nbsp;
                            <b>Available:&nbsp;</b> {{ voucher.available }} &nbsp;&nbsp;
                            <b>Used:&nbsp;</b> {{ voucher.used }} &nbsp;&nbsp;
                            <b>Status:&nbsp;</b> <span :class="
                                {'f-c-green' : voucher.is_active, 'f-c-red' : !voucher.is_active}">{{ voucher.status }}</span>
                        </div>
                    </div>
                    <div class="f-list-item-side">
                        <div class="f-icon f-grey-arrow-icon f-mt-3 f-ml-3"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$uniqueKey?>">
            <div class="f-button-seg2" @click="closeListVouchersPage()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Close</div>
            </div>
            <div class="f-button-seg2 f-act-btn" @click="showAddVoucherPage()">
                <div class="f-button-seg-icon f-add-icon"></div>
                <div class="f-button-text">New Voucher</div>
            </div>
        </div>
    </div>

</div>

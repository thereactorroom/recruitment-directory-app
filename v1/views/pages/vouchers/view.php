
<div class="page f-page" :style="{ display: (pages.viewVoucher) ? 'flex': 'none' }" >
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$uniqueKey?> f-fc f-mt-3">
            <div class="f-header-text-full f-ml-3">
                View Voucher
            </div>
        </div>
    </div>

    <div class="f-body">
        <div class="f-body-contents f-scroll main-contents-<?=$uniqueKey?> f-mt-3">
            <div class="f-list-item-wrap f-fr f-ml-0 f-mt-0 fusion-description">
                <div class="f-list-item-half"><b>Voucher Code:</b></div><div class="f-list-item-half">{{ getVoucher.code }}</div>
            </div>
            <div class="f-list-item-wrap f-fr f-ml-0 f-mt-0 fusion-description">
                <div class="f-list-item-half">Capacity:</div><div class="f-list-item-half">{{ getVoucher.capacity }}</div>
            </div>
            <div class="f-list-item-wrap f-fr f-ml-0 f-mt-0 fusion-description">
                <div class="f-list-item-half">Usage:</div><div class="f-list-item-half">{{ getVoucher.used }}</div>
            </div>

            <div class="f-list-item-wrap f-fr f-ml-0 fusion-description">
                <div class="f-list-item-half"><b>Voucher type</b></div>
            </div>
            <div class="f-list-item-wrap f-fr f-ml-0 f-mt-0 fusion-description">
                <div class="f-list-item-half">Free Period:</div><div class="f-list-item-half">{{ getVoucher.period_type }}</div>
            </div>
            <div class="f-list-item-wrap f-fr f-ml-0 f-mt-0 fusion-description">
                <div class="f-list-item-half">Description:</div><div class="f-list-item-half">{{ getVoucher.description }}</div>
            </div>

            <div class="f-list-item-wrap f-fr f-ml-0 fusion-description">
                <div class="f-list-item-half"><b>Duration</b></div>
            </div>
            <div class="f-list-item-wrap f-fr f-ml-0 f-mt-0 fusion-description">
                <div class="f-list-item-half">Campaign start Date:</div><div class="f-list-item-half">{{ getVoucher.start_date }}</div>
            </div>
            <div class="f-list-item-wrap f-fr f-ml-0 f-mt-0 fusion-description">
                <div class="f-list-item-half">Campaign end Date:</div><div class="f-list-item-half">{{ getVoucher.end_date }}</div>
            </div>

            <div class="f-list-item-wrap f-fr f-ml-0 fusion-description">
                <div class="f-list-item-half">Status:</div>
                <div class="f-list-item-half">
                    <span :class="{
                        'f-c-green': getVoucher.is_active, 
                        'f-c-red': !getVoucher.is_active
                    }">{{ getVoucher.status }}</span>
                </div>
            </div>
            <div v-if="!getVoucher.is_active" class="f-list-item-wrap f-fr f-ml-0 f-mt-0 fusion-description">
                <div class="f-list-item-half">Closed Date:</div><div class="f-list-item-half">{{ getVoucher.end_date }}</div>
            </div>
        </div>
    </div>

    <div class="f-footer">
        <div v-if="getVoucher.is_active" class="f-buttons-<?=$uniqueKey?>">
            <div class="f-button-seg4" @click="closeViewVoucherPage()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div class="f-button-seg4" @click="showListVoucherClaimsPage()">
                <div class="f-button-seg-icon f-add-icon"></div>
                <div class="f-button-text">Claims</div>
            </div>
            <div class="f-button-seg4 f-act-btn" @click="showEditVoucherPage()">
                <div class="f-button-seg-icon f-add-icon"></div>
                <div class="f-button-text">Edit</div>
            </div>
            <div class="f-button-seg4" @click="closeVoucher()">
                <div class="f-button-seg-icon f-delete-icon"></div>
                <div class="f-button-text">Close</div>
            </div>
        </div>
        <div v-else class="f-buttons-<?=$uniqueKey?>">
            <div class="f-button-seg2" @click="closeViewVoucherPage()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div class="f-button-seg2 f-act-btn" @click="showListVoucherClaimsPage()">
                <div class="f-button-seg-icon f-add-icon"></div>
                <div class="f-button-text">Claims</div>
            </div>
        </div>
    </div>
</div>

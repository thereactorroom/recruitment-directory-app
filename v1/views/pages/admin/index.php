
<div class="page f-page" :style="{ display: pages.adminMenu ? 'flex': 'none' }" >
    <div class="f-header f-mt-3">
        <!-- <div class="f-heading-<?=$uniqueKey?> f-fc f-mt-3">
            <div class="f-header-text-full f-ml-3">
                Admin Management
            </div>
            <div class="community-management-description f-ml-3">Management</div>
        </div> -->
        <div class="f-heading-<?=$uniqueKey?> f-fr f-mt-3">
            <div class="f-header-text f-ml-3">
                Admin Console
            </div>
            <div class="f-header-icons f-mt-">
                <div class="f-action-btn f-act-bg-<?=$uniqueKey?> f-act-btn"
                    style="
                        font-size: calc(calc(28/750) * var(--seg_width)) !important;
                        height: calc(calc(60/750) * var(--seg_width)) !important;
                        margin-top: calc(calc(-7/750) * var(--seg_width)) !important;
                    "
                    @click="showModuleConfigPage()">Config</div>
            </div>
        </div>

    </div>
    <div class="f-border-line"></div>
    <div class="f-body">
        <div class="f-link-btn-wrap">
            <div class="f-link-btn-byron f-ml-3" @click="showListMembers()">
                <div class="f-link-btn-icon f-member-blacklist-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Member</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2">Muting</div>
            </div>
            <div class="f-link-btn-byron f-ml-3" @click="showProposition()">
                <div class="f-link-btn-icon f-usp-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Listing</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2">Proposition</div>
            </div>
            <div class="f-link-btn-byron f-ml-3" @click="showManageListings()">
                <div class="listing-info-badge">{{ adminDashboardStats.all }}</div>
                <div class="f-link-btn-icon f-approvals-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Manage</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2">Listings</div>
            </div>
            <div class="f-link-btn-byron f-ml-3" @click="showAdminFreeListings()">
                <div class="listing-info-badge">{{ adminDashboardStats.free }}</div>
                <div class="f-link-btn-icon f-listing-listing-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Free</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2">Listings</div>
            </div>
            <div class="f-link-btn-byron f-ml-3" @click="showAdminApprovals()">
                <div class="listing-info-badge">{{ adminDashboardStats.approvals }}</div>
                <div class="f-link-btn-icon f-approvals-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Approve</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2">Listings</div>
            </div>
            <div class="f-link-btn-byron f-ml-3" @click="showDowngradedListings()">
                <div class="listing-info-badge">{{ adminDashboardStats.downgraded }}</div>
                <div class="f-link-btn-icon f-approvals-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Downgraded</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2">Listings</div>
            </div>
            <div class="f-link-btn-byron f-ml-3" @click="showPaymentLogsPage()">
                <div class="f-link-btn-icon f-payment-log-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Payment</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2">Log</div>
            </div>
            <div class="f-link-btn-byron f-ml-3" @click="showListVouchersPage()">
                <div class="business-info-badge">{{ adminDashboardStats.vouchers }}</div>
                <div class="f-link-btn-icon f-usp-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Voucher</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2">System</div>
            </div>
        </div>
    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$uniqueKey?>">
            <div class="f-button" @click="closeAdminMenu()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
        </div>
    </div>

</div>




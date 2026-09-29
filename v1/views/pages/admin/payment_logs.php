<div class="page f-page" :style="{ display: pages.paymentLogs ? 'flex': 'none' }" >
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$uniqueKey?> f-fc f-mt-3">
            <div class="f-header-text-full f-ml-3">
                Monthly Payment Logs
            </div>
        </div>
        <div class="fusion-description f-mt-2">
            Click + sign to Download monthly payment logs.
        </div>
    </div>

    <div class="f-body">

        <div class="f-body-contents-full f-scroll f-mb-3"
            style="height: calc(var(--s_height) - calc(calc(320/750) * var(--seg_width))) !important;">
            <div v-for="(logItem, index) in availablePaymentLogs" :key="index" class="f-list-item f-fc f-mb-2">

                <div class="f-list-item-wrap f-fr f-ml-3 f-mb-3" @click="downloadMonthlyLog(logItem)">
                    <div class="f-list-item-image"
                        style="
                            background-color: #0072FF;
                            color: white;
                            text-align: center;
                            display: block;
                            line-height: calc(calc(90/750) * var(--seg_width));
                        ">LOG</div>
                    <div class="f-list-item-middle-3x f-fc" >
                        <div class="f-list-item-name f-ml-3 f-mt-3">{{ logItem.month_text }} {{ logItem.year }}</div>
                    </div>

                    <div class="f-list-item-side" >
                        <div class="f-icon f-assign-icon f-mt-1 f-ml-3"></div>
                    </div>
                </div>

            </div>
        </div>
        
    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$uniqueKey?>">
            <div class="f-button" @click="closePaymentLogsPage()">
                <div class="f-button-seg-icon"
                    style="background-image: url('/modules/members-management-v2/images/back_icon.svg')"></div>
                <div class="f-button-text">Close</div>
            </div>
        </div>
    </div>

</div>
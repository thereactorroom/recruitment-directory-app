<div class="page f-page" :style="{ display: pages.listVoucherClaims ? 'flex': 'none' }" >
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$uniqueKey?> f-fc f-mt-3">
            <div class="f-header-text-full f-ml-3">
                Voucher {{ getVoucher.code }} <br/>Claims ({{getVoucherClaimsList.length}})
            </div>
        </div>
        <div class="fusion-description f-mt-2">
            Click on a business listing to view more info.
        </div>
    </div>

    <div class="f-body">

        <div class="f-body-contents-search-<?=$uniqueKey?> f-fr">
            <div class="f-input-wrap-byron f-mt-3 f-ml-3 f-mb-3 f-border-bottom">
                <input type="text" 
                    change="seachBusinessByName"
                    v-model="filters.vouchers" 
                    class="f-input f-input-left-search f-pl-2"
                    placeholder="Search listing by keyword..." >
                <div class="f-action-lnk-<?=$uniqueKey?> f-action-lnk-search" @click="filters.vouchers = ''">clear</div>
            </div>
        </div>

        <div class="f-body-contents-full f-mb-3 f-scroll main-contents-<?=$uniqueKey?>"
            style="height: calc(var(--s_height) - calc(calc(435/750) * var(--seg_width))) !important;">

            <div v-if="getVoucherClaimsList.length == 0 && loading.vouchers" 
                class="f-list-item" style="border-bottom: 0px;">
                <div class="f-list-item-wrap f-mt-3 f-ml-3">
                    <div class="loader-<?=$uniqueKey?>"></div> 
                    <div class="loader-text">Loading ...</div>
                </div>
            </div>

            <div v-else-if="getVoucherClaimsList.length == 0" 
                class="f-list-item" style="border-bottom: 0px;">
                <div class="f-list-item-wrap f-mt-3 f-ml-3">
                    There are no listings that claim this voucher &#128515;
                </div>
            </div>

            <div v-else v-for="(listing, index) in getVoucherClaimsList" :key="index">
                <div class="f-list-item f-fc" @click="showViewVoucherClaimPage(listing)">
                    <listing-paid-card :listing="listing" :urls="urls" :favorites="getFavoritesList"></listing-paid-card>
                </div>
            </div>

            <!-- <div v-else v-for="(business, index) in getVoucherClaimsList" :key="index" 
                class="business-listing f-fc" v-show="searchApprovalBusinessByName(business)"
                @click="showViewVoucherClaimPage(business)" >

                <div class="business-listing-header f-mt-3 f-ml-3">

                    <div v-if="business.recommended" class="business-listing-header-icon-recommended"
                        :style="businessLogo('<?=$envHost?><?=$community_icon?>', '&w=360&h=360&q=100&zc=6')"></div>
                    <div v-else-if="isEmpty(business.logo)" class="business-listing-header-icon-no-iamge">{{businessNoLogo(business)}}</div>
                    <div v-else class="business-listing-header-icon" 
                        :style="businessLogo(business.logo, '&w=360&q=100&zc=6')"></div>
                    
                    <div v-if="business.recommended" class="business-listing-header-txt-holder f-fc">
                        <div class="business-listing-header-txt f-ml-2"
                            :class="{'business-listing-header-txt-mt': business.business_name.length <= 27}"
                            style="font-size: calc(calc(30/750) * var(--seg_width));">
                            {{ business.display_name }}
                        </div>
                    </div>
                    <div v-else class="business-listing-header-txt-holder f-fc">
                        <div class="business-listing-header-txt f-ml-2"
                            :class="{'business-listing-header-txt-mt': business.business_name.length <= 27}"
                            style="font-size: calc(calc(30/750) * var(--seg_width));">
                            {{ business.display_name }}
                        </div>
                    </div>

                    <div class="business-listing-header-nav-holder"
                        style="width: calc(calc(80/750) * var(--seg_width)) !important;">

                        <div class="business-listing-header-txt-icon f-verified-icon f-mt-1 f-mr-1" v-if="business.paid"
                            style="background-size: calc(calc(40/750) * var(--seg_width));"></div>
                        <div class="business-listing-header-txt-icon f-fav-heart-icon f-mt-1 f-mr-1" v-if="isFavorite(business)" 
                            style="background-size: calc(calc(35/750) * var(--seg_width));"></div>

                    </div>

                    <div class="business-listing-header-nav-holder f-fc"
                        style="width: calc(calc(120/750) * var(--seg_width)) !important;">

                        <div class="f-mt-1" style="
                                float: right;
                                display: flex;
                                justify-content: right;
                                width: calc(calc(120/750) * var(--seg_width)) !important;
                                height: calc(calc(55/750) * var(--seg_width));
                                line-height: calc(calc(24/750) * var(--seg_width));
                                font-size: calc(calc(24/750) * var(--seg_width));
                            ">
                            <div v-if="business.stars_avg == 0" 
                                class="business-listing-header-txt-icon f-grey-star-icon f-mr-1" 
                                style="
                                    margin-top: calc(calc(-10/750) * var(--seg_width)) !important;
                                    background-size: calc(calc(40/750) * var(--seg_width));
                                "></div>
                            <div v-else class="business-listing-header-txt-icon f-gold-star-icon f-mr-1" 
                                style="
                                    margin-top: calc(calc(-10/750) * var(--seg_width)) !important;
                                    background-size: calc(calc(40/750) * var(--seg_width));
                                "></div>
                            <div style="
                                    display: flex; 
                                    width: max-content;
                                ">{{ business.stars_avg }}</div>
                        </div>

                        <div style="
                                float: right;
                                display: flex;
                                justify-content: right;
                                width: calc(calc(120/750) * var(--seg_width)) !important;
                                height: calc(calc(55/750) * var(--seg_width));
                                line-height: calc(calc(30/750) * var(--seg_width));
                                font-size: calc(calc(20/750) * var(--seg_width));
                            ">
                            <div style="
                                    display: flex; 
                                    width: max-content;
                                ">{{ business.comments }} Ratings</div>
                        </div>

                    </div>

                </div>

                <div class="business-listing-body f-mt-2 f-ml-3 f-fc">
                    <div class="business-listing-body-txt">
                        Status: {{ business.status }} <br/>
                    </div>
                    <div class="business-listing-body-txt f-fr" style="justify-content: space-between;">
                        <div>Subscription: {{ business.subscription_status }}</div>
                        <div>{{ business.claim_date }}</div>
                    </div>
                </div>

                <div class="business-listing-footer f-ml-3"></div>
            </div> -->

        </div>
    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$uniqueKey?>">
            <div class="f-button-seg" @click="closeListVoucherClaimsPage()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
        </div>
    </div>
</div>

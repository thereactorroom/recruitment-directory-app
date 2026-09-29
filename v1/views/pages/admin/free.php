
<div class="page f-page" :style="{ display: pages.adminFreeListings ? 'flex': 'none' }" >
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$uniqueKey?> f-fc f-mt-3">
            <div class="f-header-text-full f-ml-3">
                Free Listings ({{ getFreeListingsList.length }})
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
                    v-model="filters.free" 
                    class="f-input f-input-left-search f-pl-2"
                    placeholder="Search listing by keyword..." >
                <div class="f-action-lnk-<?=$uniqueKey?> f-action-lnk-search" @click="filters.free = ''">clear</div>
            </div>
        </div>

        <div class="f-body-contents-full f-mb-3 f-scroll main-contents-<?=$uniqueKey?>">

            <div v-if="getFreeListingsList.length == 0 && loading.free" 
                class="f-list-item" style="border-bottom: 0px;">
                <div class="f-list-item-wrap f-mt-3 f-ml-3">
                    <div class="loader-<?=$uniqueKey?>"></div> 
                    <div class="loader-text">Loading ...</div>
                </div>
            </div>

            <div v-else-if="getFreeListingsList.length == 0" 
                class="f-list-item" style="border-bottom: 0px;">
                <div class="f-list-item-wrap f-mt-3 f-ml-3">
                    There are no traders yet. &#128515;
                </div>
            </div>

            <div v-else v-for="(listing, index) in getFreeListingsList" :key="index"
                v-show="searchAdminFreeListingsByName(listing)">
                <div class="f-list-item f-fc listing-listing-recommended" @click="showAdminFreeListingCallLog(listing)">
                    <listing-free-card :listing="listing" :favorites="getFavoritesList"></listing-free-card>
                </div>
            </div>

        </div>
    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$uniqueKey?>">
            <div class="f-button" @click="closeAdminFreeListings()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
        </div>
    </div>

</div>


<div class="page f-page" :style="{ display: pages.adminFreeListingCallLog ? 'flex': 'none' }" >
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$uniqueKey?> f-fc f-mt-3">
            <div class="f-header-text-full f-ml-3">
                {{listing.display_name}} Call Log ({{getFreeListingCallLog.length}})
            </div>
        </div>
        <div class="fusion-description f-mt-2">
            Click on a call log to add a comment.
        </div>
    </div>

    <div class="f-body">

        <div class="f-body-contents-full f-mb-3 f-scroll main-contents-<?=$uniqueKey?>">

            <div v-for="(log_item, index) in getFreeListingCallLog" :key="index" 
                class="listing-listing f-fc"
                @click="showAdminFreeListingCallLogComment(log_item)">
                <div class="listing-listing-header f-mt-3 f-ml-3">
                    <div class="listing-listing-header-txt"
                        style="
                            font-size: calc(calc(30/750) * var(--seg_width));
                            width: calc(calc(690/750) * var(--seg_width));
                        ">
                        {{ formatDateTime(log_item.added) }} 
                    </div>
                </div>
                <div class="listing-listing-body f-mt-1 f-ml-3">
                    <div class="listing-listing-body-txt">{{ log_item.name }} {{ log_item.surname }}</div>
                </div>
                <div class="listing-listing-body f-mt-1 f-ml-3">

                    <div v-if="log_item.comment == ''" class="f-input-wrap">
                        <div class="f-list-item-side" 
                            style="width: calc(calc(300/750) * var(--seg_width)) !important; justify-content: left;"> 
                            <div class="f-action-btn f-act-bg-<?=$uniqueKey?> f-mt-1"
                                @click="showAdminFreeListingCallLogComment(log_item)"
                                style="
                                    background-color: rgb(9, 141, 183) !important;
                                    border-radius: calc(calc(55/750) * var(--seg_width));
                                    padding-right: calc(calc(30/750) * var(--seg_width));
                                    padding-left: calc(calc(30/750) * var(--seg_width));
                                    color: white;
                                    width: fit-content;
                                    height: calc(calc(55/750) * var(--seg_width));
                                    line-height: calc(calc(55/750) * var(--seg_width));
                                    font-size: calc(calc(28/750) * var(--seg_width));
                                    margin-top: calc(calc(0/750) * var(--seg_width)) !important;
                                "
                            >Add Comment</div>
                        </div>
                    </div>

                    <div v-else class="listing-listing-body-txt">{{ log_item.comment }}</div>

                </div>
                <div class="listing-listing-footer f-ml-3"></div>
            </div>

        </div>
    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$uniqueKey?>">
            <div class="f-button-seg3" @click="closeAdminFreeListingCallLog()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div class="f-button-seg3" @click="addFreeListingCallLog()">
                <div class="f-button-seg-icon f-make-call-icon"></div>
                <div class="f-button-text">Call</div>
            </div>
            <div class="f-button-seg3" @click="scrollToTop()">
                <div class="f-button-seg-icon f-to-top-icon"></div>
                <div class="f-button-text">To Top</div>
            </div>
        </div>
    </div>

</div>


<div class="f-overlay" :style="{ display: (pages.adminFreeListingCallLogComment) ? 'flex': 'none' }" 
    @click.self="closeAdminFreeListingCallLogComment()">
    <div class="f-overlay-content">
        <div class="f-overlay-header">
            <div class="fusion-heading f-heading-<?=$uniqueKey?> f-mt-3 f-mb-3">
                <div class="f-header-text-full">
                    Add Comment
                </div>
            </div>
        </div>

        <div class="f-overlay-body">
            <div class="f-overlay-body-contents f-scroll f-mt-3">

                <div class="fusion-description f-mt-3 f-ml-0 f-mb-0">Enter your comment below.</div>
                <div class="f-input-wrap">
                    <textarea class="f-input" rows="3" v-model="callLogItemComment"></textarea>
                </div>

                <div class="f-input-wrap">
                    <div class="f-list-item-left f-fc" style="width: calc(calc(390/750) * var(--seg_width)) !important;"></div>
                    <div class="f-list-item-side" 
                        @click="addFreeListingCallLogComment()"
                        style="width: calc(calc(300/750) * var(--seg_width)) !important; justify-content: right;"> 
                        <div class="f-action-btn f-act-bg-<?=$uniqueKey?> f-mt-1">Post Comment</div>
                    </div>
                </div>

                <div class="f-input-wrap f-mb-3"></div>

            </div>
        </div>

        <div class="f-overlay-footer">
            <div class="f-buttons-<?=$uniqueKey?>">
                <div class="f-button" @click="closeAdminFreeListingCallLogComment()">
                    <div class="f-button-seg-icon"
                        style="background-image: url('/modules/members-management-v2/images/back_icon.svg')"></div>
                    <div class="f-button-text">Back</div>
                </div>
            </div>
        </div>

    </div>
</div>





<div class="page f-page" :style="{ display: pages.recommendations ? 'flex': 'none' }" >
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$unique_key?> f-fc f-mt-3">
            <div class="f-header-text-full f-ml-3">
                Community Listings ({{recommendations.list.length}})
            </div>
        </div>
        <div class="fusion-description f-mt-2">
            Click on a business listing to view more info.
        </div>
    </div>

    <div class="f-body">

        <div class="f-body-contents-search-<?=$unique_key?> f-fr">
            <div class="f-input-wrap-byron f-mt-3 f-ml-3 f-mb-3 f-border-bottom">
                <input type="text" class="f-input f-input-left-search f-pl-2"
                        v-model="recommendations.mainFilter" placeholder="Search listing by keyword." >
                <div class="f-action-lnk-<?=$unique_key?> f-action-lnk-search" @click="traders.mainFilter = ''">clear</div>
            </div>
            <div v-if="pages.sort" class="f-icon-icon f-search-left-icon f-sort-dec-icon f-mt-3" @click="showSort()"
                style="
                    margin-top: calc(calc(36/750) * var(--seg_width)) !important;
                    background-position: right;
                "></div>
            <div v-else class="f-icon-icon f-search-left-icon f-sort-asc-icon f-mt-3" @click="showSort()"
                style="
                    margin-top: calc(calc(36/750) * var(--seg_width)) !important;
                    background-position: right;
                "></div>
        </div>

        <div class="f-body-contents-search-<?=$unique_key?> f-fr f-mt-1"
            :style="{ display: (pages.sort) ? 'flex': 'none' }">

            <div v-if="traders.sort.dateAscending" class="f-button-seg3 date-sort sort-item-background" @click="applySortRecommendations('date')">
                <div class="f-button-seg-icon f-sort-date-jan-dec-icon"></div>
                <div class="f-button-text">Date</div>
            </div>
            <div v-else class="f-button-seg3 date-sort sort-item-background" @click="applySortRecommendations('date')">
                <div class="f-button-seg-icon f-sort-date-dec-jan-icon"></div>
                <div class="f-button-text">Date</div>
            </div>

            <div v-if="traders.sort.nameAscending" class="f-button-seg3 name-sort" @click="applySortRecommendations('name')">
                <div class="f-button-seg-icon f-sort-az-icon"></div>
                <div class="f-button-text">Name</div>
            </div>
            <div v-else class="f-button-seg3 name-sort" @click="applySortRecommendations('name')">
                <div class="f-button-seg-icon f-sort-za-icon"></div>
                <div class="f-button-text">Name</div>
            </div>

            <div v-if="traders.sort.ratingAscending" class="f-button-seg3 rating-sort" @click="applySortRecommendations('rating')">
                <div class="f-button-seg-icon f-star-rating-dec-icon"></div>
                <div class="f-button-text">Rating</div>
            </div>
            <div v-else class="f-button-seg3 rating-sort" @click="applySortRecommendations('rating')">
                <div class="f-button-seg-icon f-star-rating-asc-icon "></div>
                <div class="f-button-text">Rating</div>
            </div>
        </div>

        <div class="f-body-contents-full f-mb-3 f-scroll main-contents-<?=$unique_key?>">

            <div v-if="getRecommendationsList.length == 0" 
                class="f-list-item" style="border-bottom: 0px;">
                <div class="f-list-item-wrap f-mt-3 f-ml-3">
                    There are no traders yet. &#128515;
                </div>
            </div>

            <div v-else v-for="(trader, index) in getRecommendationsList" :key="index" 
                class="listing-listing listing-listing-recommended f-fc" 
                :class="{'listing-listing-recommended-green' : trader.last_called != ''}"
                v-show="searchRecommendationsByName(trader)">

                <div class="listing-listing-header listing-listing-recommended f-mt-3 f-ml-3"
                    :class="{'listing-listing-recommended-green' : trader.last_called != ''}">

                    <div class="listing-listing-header-icon-recommended"
                        :style="traderLogo('<?=$envHost?><?=$community_icon?>', '&w=360&h=360&q=100&zc=6')"
                        @click="showRecommendationsCallLog(trader)"></div>
                    
                    <div class="listing-listing-header-txt-holder f-fc" @click="showRecommendationsCallLog(trader)">
                        <div class="listing-listing-header-txt f-ml-2"
                            :class="{'listing-listing-header-txt-mt': trader.trader_name.length <= 27}"
                            style="font-size: calc(calc(30/750) * var(--seg_width));" v-html="recommendationTraderName(trader)"></div>
                    </div>

                    <div class="listing-listing-header-nav-holder listing-listing-recommended"
                        :class="{'listing-listing-recommended-green' : trader.last_called != ''}">
                        <div class="listing-listing-header-nav-btn-comment">
                            <!-- <div class="listing-listing-header-txt-icon-heart f-fav-heart-icon f-mt-1 f-mr-1" v-if="isFavorite(trader)" 
                                style="background-size: calc(calc(35/750) * var(--seg_width));"></div>
                            <div class="listing-listing-header-txt-icon f-verified-icon f-mt-1 f-mr-1" v-if="trader.paid == 'Y'"
                                style="background-size: calc(calc(35/750) * var(--seg_width));"></div> -->
                        </div>
                        <div class="listing-listing-header-nav-btn-stars" @click="showComments(trader)">
                            <div class="listing-listing-header-nav-btn" v-if="trader.stars_avg == 0">
                                <div class="listing-listing-header-nav-btn-txt f-color-grey">-</div>
                                <div class="listing-listing-header-nav-btn-star-icon f-grey-star-icon"></div>
                            </div>
                            <div class="listing-listing-header-nav-btn" v-else>
                                <div class="listing-listing-header-nav-btn-txt">{{trader.stars_avg}}</div>
                                <div class="listing-listing-header-nav-btn-star-icon f-gold-star-icon"></div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="listing-listing-body f-fc f-mt-2 f-ml-3 listing-listing-recommended"
                    :class="{'listing-listing-recommended-green' : trader.last_called != ''}"
                     @click="showRecommendationsCallLog(trader)">
                    <div class="listing-listing-body-txt">{{ trader.description }}</div>
                    <div v-if="trader.last_called != ''" class="listing-listing-body-txt f-mt-2"
                        style="justify-content: right">Last Called: {{ formatDateTime(trader.last_called) }}</div>
                </div>

                <div class="listing-listing-footer f-ml-3"></div>
            </div>

        </div>
    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$unique_key?>">
            <div class="f-button" @click="closeRecommendations()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
        </div>
    </div>

</div>


<div class="page f-page" :style="{ display: pages.recommendations_call_log ? 'flex': 'none' }" >
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$unique_key?> f-fc f-mt-3">
            <div class="f-header-text-full f-ml-3">
                {{traders.trader.display_name}} Call Log ({{getRecommendationCallLogList.length}})
            </div>
        </div>
        <div class="fusion-description f-mt-2">
            Click on a call log to add a comment.
        </div>
    </div>

    <div class="f-body">

        <div class="f-body-contents-full f-mb-3 f-scroll main-contents-<?=$unique_key?>">

            <div v-for="(log_item, index) in getRecommendationCallLogList" :key="index" 
                class="listing-listing f-fc"
                @click="showCallLogComment(log_item)">
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
                            <div class="f-action-btn f-act-bg-<?=$unique_key?> f-mt-1"
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
        <div class="f-buttons-<?=$unique_key?>">
            <div class="f-button-seg3" @click="closeRecommendationsCallLog()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div class="f-button-seg3" @click="addRecommendationCallLog()">
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


<div class="f-overlay" :style="{ display: (pages.call_log_comment) ? 'flex': 'none' }" 
    @click.self="closeCallLogComment()">
    <div class="f-overlay-content">
        <div class="f-overlay-header">
            <div class="fusion-heading f-heading-<?=$unique_key?> f-mt-3 f-mb-3">
                <div class="f-header-text-full">
                    Add Comment
                </div>
            </div>
        </div>

        <div class="f-overlay-body">
            <div class="f-overlay-body-contents f-scroll f-mt-3">

                <div class="fusion-description f-mt-3 f-ml-0 f-mb-0">Enter your comment below.</div>
                <div class="f-input-wrap">
                    <textarea class="f-input" rows="3" v-model="recommendations.callLogComment"></textarea>
                </div>

                <div class="f-input-wrap">
                    <div class="f-list-item-left f-fc" style="width: calc(calc(390/750) * var(--seg_width)) !important;"></div>
                    <div class="f-list-item-side" 
                        @click="addCallLogComment()"
                        style="width: calc(calc(300/750) * var(--seg_width)) !important; justify-content: right;"> 
                        <div class="f-action-btn f-act-bg-<?=$unique_key?> f-mt-1">Post Comment</div>
                    </div>
                </div>

                <div class="f-input-wrap f-mb-3"></div>

            </div>
        </div>

        <div class="f-overlay-footer">
            <div class="f-buttons-<?=$unique_key?>">
                <div class="f-button" @click="closeCallLogComment()">
                    <div class="f-button-seg-icon"
                        style="background-image: url('/modules/members-management-v2/images/back_icon.svg')"></div>
                    <div class="f-button-text">Back</div>
                </div>
            </div>
        </div>

    </div>
</div>




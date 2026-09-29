

<div class="page f-page" :style="{ display: pages.main ? 'flex': 'none' }" >
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$uniqueKey?> f-fr f-mt-3">
            <div class="f-header-text f-ml-3"
                @click="showAdminMenu()">
                {{ moduleConfig.app_title }}
            </div>

            <div class="f-header-icons">
                <div
                    class="f-action-btn f-act-bg-<?=$uniqueKey?> f-act-btn"
                    @click="showViewListings()">
                    Paid Listing
                </div>
            </div>
        </div>
        <div class="community-management-description f-ml-3" v-if="permissions.admin" @click="showAdminMenu()">Tap here to manage listings.</div>
        <div class="fusion-description f-mt-2">
            Click on a listing to view more info.
        </div>
    </div>

    <div class="f-body">

        <div class="f-body-contents-search-<?=$uniqueKey?> f-fr">
            <div class="f-input-wrap-byron f-mt-3 f-ml-3 f-mb-3 f-border-bottom">
                <input type="text" 
                    v-model="filters.main" 
                    class="f-input f-input-left-search f-pl-2"
                    placeholder="Search listing by keyword..." >
                <div class="f-action-lnk-<?=$uniqueKey?> f-action-lnk-search" @click="filters.main = ''">clear</div>
            </div>
        </div>

        <div class="f-body-contents-full f-mb-3 f-scroll main-contents-<?=$uniqueKey?>" 
            style="height: calc(var(--s_height) - calc(calc(510/750) * var(--seg_width))) !important;">
            <!-- 536 -->

            <div v-if="getListingsList.length == 0 && loading.businesses" 
                class="f-list-item" style="border-bottom: 0px;">
                <div class="f-list-item-wrap f-mt-3 f-ml-3">
                    <div class="loader-<?=$uniqueKey?>"></div> 
                    <div class="loader-text">Loading ...</div>
                </div>
            </div>

            <div v-else-if="getListingsList.length == 0" 
                class="f-list-item" style="border-bottom: 0px;">
                <div class="f-list-item-wrap f-mt-3 f-ml-3">
                    No Results found. &#128515;
                </div>
            </div>

            <div v-else v-for="(listing, index) in getListingsList" :key="index">

                <div v-if="listing.free == 0" class="f-list-item f-fc" @click="showListing(listing)">
                    <listing-paid-card :listing="listing" :urls="urls" :favorites="getFavoritesList"></listing-paid-card>
                </div>

                <div v-else class="f-list-item f-fc listing-listing-recommended" @click="showListing(listing)">
                    <listing-free-card :listing="listing" :favorites="getFavoritesList"></listing-free-card>
                </div>
                
            </div>

        </div>
    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$uniqueKey?>">
            <div class="f-button-seg4" @click="exit()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Close</div>
            </div>
            <div v-if="!screen.mainTopBottom" class="f-button-seg4" @click="toggleMainTopBottom()">
                <div class="f-button-seg-icon f-to-top-icon"></div>
                <div class="f-button-text">To Top</div>
            </div>
            <div v-else class="f-button-seg4" @click="toggleMainTopBottom()">
                <div class="f-button-seg-icon f-to-bottom-icon"></div>
                <div class="f-button-text">To Bottom</div>
            </div>
            <div class="f-button-seg4 f-act-btn" @click="showAddFreeListing()">
                <div class="f-button-seg-icon f-add-icon"></div>
                <div class="f-button-text">New Listing</div>
            </div>
            <div class="f-button-seg4" @click="showMainFilter()">
                <div class="f-button-seg-icon f-fav-heart-icon" v-if="getFilterCondition == 'faves'"></div>
                <div class="f-button-seg-icon f-verified-icon" v-else-if="getFilterCondition == 'paid'"></div>
                <div class="f-button-seg-icon f-gold-star-icon" v-else-if="getFilterCondition == 'rated'"></div>
                <div class="f-button-seg-icon f-grey-star-icon" v-else-if="getFilterCondition == 'not_rated'"></div>
                <div class="f-button-seg-icon f-filter-icon" v-else></div>
                <div class="f-button-text">Filter</div>
            </div>
        </div>
    </div>

</div>


<div class="f-overlay" v-show="pages.mainFilter" @click.self="closeMainFilter()">

    <div class="f-overlay-content">
        <div class="f-overlay-footer">
            <div class="f-buttons-<?=$uniqueKey?>">

                <div class="f-button-seg2" @click="applyMainFilter('faves')">
                    <div class="f-button-seg-icon f-fav-heart-icon"></div>
                    <div class="f-button-text">Faves</div>
                </div>

                <div class="f-button-seg2 " @click="applyMainFilter('paid')">
                    <div class="f-button-seg-icon f-verified-icon"></div>
                    <div class="f-button-text">Paid</div>
                </div>

                <div class="f-button-seg2" @click="applyMainFilter('rated')">
                    <div class="f-button-seg-icon f-gold-star-icon"></div>
                    <div class="f-button-text">Rated</div>
                </div>

                <div class="f-button-seg2" @click="applyMainFilter('not_rated')">
                    <div class="f-button-seg-icon f-grey-star-icon"></div>
                    <div class="f-button-text">Not Rated</div>
                </div>

                <div class="f-button-seg2" @click="resetMainFilter()">
                    <div class="f-button-seg-icon f-clear-icon" v-if="getFilterCondition == 'faves'"></div>
                    <div class="f-button-seg-icon f-clear-icon" v-else-if="getFilterCondition == 'paid'"></div>
                    <div class="f-button-seg-icon f-clear-icon" v-else-if="getFilterCondition == 'rated'"></div>
                    <div class="f-button-seg-icon f-clear-icon" v-else-if="getFilterCondition == 'not_rated'"></div>
                    <div class="f-button-seg-icon f-clear-icon" v-else></div>
                    <div class="f-button-text">Clear</div>
                </div>

            </div>
        </div>
    </div>

</div>
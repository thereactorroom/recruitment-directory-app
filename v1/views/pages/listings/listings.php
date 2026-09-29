

<div class="page f-page" :style="{ display: pages.viewListings ? 'flex': 'none' }">
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$uniqueKey?> f-fc f-mt-3">
            <div class="f-header-text-full f-ml-3">
                Current Listings ({{getMyListingsList.length}})
            </div>
        </div>
        <div class="fusion-description f-mt-2">
            Your existing listings are shown below. Click to view or edit, or select New Listing to create one.
        </div>
    </div>

    <div class="f-body">

        <div class="f-body-contents-search-<?=$uniqueKey?> f-fr">
            <div class="f-input-wrap-byron f-mt-3 f-ml-3 f-mb-3 f-border-bottom">
                <input type="text" 
                    change="seachListingsByName"
                    v-model="filters.listings" 
                    class="f-input f-input-left-search f-pl-2"
                    placeholder="Search listing by keyword..." >
                <div class="f-action-lnk-<?=$uniqueKey?> f-action-lnk-search" @click="filters.listings = ''">clear</div>
            </div>
        </div>

        <div class="f-body-contents-full f-mb-3 f-scroll main-contents-<?=$uniqueKey?>"
            style="height: calc(var(--s_height) - calc(calc(520/750) * var(--seg_width))) !important;">

            <div v-if="getMyListingsList.length == 0 && loading.myListings" 
                class="f-list-item" style="border-bottom: 0px;">
                <div class="f-list-item-wrap f-mt-3 f-ml-3">
                    <div class="loader-<?=$uniqueKey?>"></div> 
                    <div class="loader-text">Loading ...</div>
                </div>
            </div>

            <div v-else-if="getMyListingsList.length == 0" 
                class="f-list-item" style="border-bottom: 0px;">
                <div class="f-list-item-wrap f-mt-3 f-ml-3 f-fc">
                    <p><b>You currently have no listings. To Create a paid Listing, select "New Listing" in the bottom Menu. &#128515;</b></p>
                    <p>
                        By paid listing allows the Physio Net community, to view your detailed information, CV and your skill sets.
                    </p>
                    <p>The members will see:</p>
                    <ul>
                        <li>Know the services you provide.</li>
                        <li>Call you or WhatsApp you.</li>
                        <li>Connect to your Social media channels.</li>
                        <li>Rate and Review you.</li>
                    </ul>
                </div>
            </div>

            <div v-else v-for="(listing, index) in getMyListingsList" :key="index">
                <div class="f-list-item f-fc" @click="showListing(listing)">
                    <listing-paid-card :listing="listing" :urls="urls" :stats="true" :favorites="getFavoritesList"></listing-paid-card>
                </div>
            </div>

        </div>
    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$uniqueKey?>">
            <div class="f-button-seg2" @click="closeViewListings()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div class="f-button-seg2 f-act-btn" @click="showAddPaidListing()">
                <div class="f-button-seg-icon f-add-icon"></div>
                <div class="f-button-text">New Listing</div>
            </div>
        </div>
    </div>

</div>



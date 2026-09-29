<div class="page f-page" :style="{ display: pages.viewFreeListing ? 'flex': 'none' }" >

    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$uniqueKey?> f-mt-3">
            <div class="f-header-text f-ml-3">
                {{listing.name}}
            </div>
            <!-- <div class="f-df f-fr f-pl-2 f-pr-2 f-mt-1"></div> -->
            <div class="f-header-icons f-df f-fr">
                <!-- <div class="listing-listing-header-txt-icon f-fav-heart-icon f-mt-1 f-mr-1" v-if="isFavorite(listing)"  -->
                <div class="listing-listing-header-txt-icon f-fav-heart-icon f-mt-1 f-mr-1"
                    style="background-size: calc(calc(35/750) * var(--seg_width));"></div>

                <div class="listing-listing-header-txt-icon f-color-grey f-mt-1 f-mr1" v-if="listing.stars_avg == 0"
                    style="
                        line-height: calc(calc(30/750) * var(--seg_width));
                        font-size: calc(calc(30/750) * var(--seg_width));
                        padding-right: calc(calc(8/750) * var(--seg_width));
                    ">-</div>
                <div class="listing-listing-header-txt-icon f-grey-star-icon f-mr-1" v-if="listing.stars_avg == 0"
                    style="
                        float: left;
                        background-repeat: no-repeat;
                        width: calc(calc(40/750) * var(--seg_width));
                        height: calc(calc(50/750) * var(--seg_width));
                        margin-top: calc(calc(-4/750) * var(--seg_width));
                        background-size: calc(calc(45/750) * var(--seg_width));
                    "></div>

                <div class="listing-listing-header-txt-icon f-color-grey f-mt-1 f-mr1" v-if="listing.stars_avg > 0"
                    style="
                        line-height: calc(calc(30/750) * var(--seg_width));
                        font-size: calc(calc(30/750) * var(--seg_width));
                        padding-right: calc(calc(8/750) * var(--seg_width));
                    ">{{listing.stars_avg}}</div>
                <div class="listing-listing-header-txt-icon f-gold-star-icon f-mr-1" v-if="listing.stars_avg > 0"
                    style="
                        float: left;
                        background-repeat: no-repeat;
                        width: calc(calc(40/750) * var(--seg_width));
                        height: calc(calc(50/750) * var(--seg_width));
                        margin-top: calc(calc(-4/750) * var(--seg_width));
                        background-size: calc(calc(45/750) * var(--seg_width));
                    "></div>
            </div> 
        </div>
    </div>

    <div class="f-body">
        <div class="f-link-btn-wrap f-scroll">
            <div class="f-link-btn-byron f-ml-3" @click="openDialerApp(listing.contact_number)">
                <div class="f-link-btn-icon f-contact-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Call</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
            <div class="f-link-btn-byron f-ml-3" v-if="listing.whatsapp" 
                @click="openWhatsAppApp(listing.whatsapp)">
                <div class="f-link-btn-icon f-whatsapp-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">WhatsApp</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
            <div v-if="permissions.admin" class="f-link-btn-byron f-ml-3" @click="showEditFreeListing(listing)">
                <div class="f-link-btn-icon f-edit-listing-icon f-mt-2 f-ml-2"></div>
                <div class="f-link-btn-text f-mt-2 f-ml-2">Edit</div>
                <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
            </div>
        </div>

        <div class="f-body-contents-search-<?=$uniqueKey?> f-mt-3">
            <div class="f-input-wrap f-mt-3 f-ml-3 f-mb-3 f-border-bottom">
                <input type="text" class="f-input f-input-left f-pl-2"
                    v-model="comments.filter" placeholder="Search comments by name." >
                <div class="f-action-lnk-<?=$uniqueKey?> f-action-lnk-search" @click="comments.filter = ''">clear</div>
            </div>
        </div>

        <div class="f-body-contents-full f-scroll main-contents-<?=$uniqueKey?>">

            <!-- v-show="searchComments(comment)" -->
            <div v-for="(comment, index) in getCommentsList" :key="index" 
                class="listing-listing f-fc" v-show="searchComments(comment)"
                @click="showCommentContact(comment)">

                <div class="listing-listing-body f-fc f-mt-3 f-ml-3" >   
                    <div class="listing-listing-body-txt" v-html="displayCommentContent(comment)"></div>
                    <div class="listing-listing-body-rating-txt f-df" v-html="displayCommentRatings(comment)"></div>
                </div>

                <div class="listing-listing-header f-mt-3 f-ml-3">
                    <div v-if="isEmpty(comment.picture)" class="listing-listing-header-icon-no-iamge f-cycle-radius f-b-0">
                        {{memberNoPicture(comment)}}
                    </div>
                    <div v-else class="listing-listing-header-icon f-cycle-radius f-b-0" :style="memberPhoto(comment.picture, '&w=360&h=360&q=100&zc=6')"></div>
                    <div class="listing-listing-header-txt-holder f-fc" >
                        <div class="listing-listing-header-txt f-ml-2">{{ comment.name }} {{ comment.surname }}</div>
                        <div class="listing-listing-header-txt-icons f-ml-2">
                            {{ getRelativeTimestamp(comment.updated) }}
                        </div>
                    </div>
                </div>
                <div class="listing-listing-footer f-ml-3"></div>
            </div>

        </div>

    </div>

    <div class="f-footer" >
        <div class="f-buttons-<?=$uniqueKey?>">
            <div class="f-button-seg4" @click="closeViewFreeListing()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div v-if="comments.alreadyCommented" class="f-button-seg4 f-act-btn" @click="showEditComment()">
                <div class="f-button-seg-icon f-edit-icon"></div>
                <div class="f-button-text">Edit Rating</div>
            </div>
            <div v-else class="f-button-seg4 f-act-btn" @click="showAddComment()">
                <div class="f-button-seg-icon f-new-comment-icon"></div>
                <div class="f-button-text">New Rating</div>
            </div>
            <div v-if="comments.ascending" class="f-button-seg4" @click="sortCommentsByStars()">
                <div class="f-button-seg-icon f-star-rating-small-large-icon"></div>
                <div class="f-button-text">Ascending</div>
            </div>
            <div v-else class="f-button-seg4" @click="sortCommentsByStars()">
                <div class="f-button-seg-icon f-star-rating-large-small-icon"></div>
                <div class="f-button-text">Descending</div>
            </div>
            <div class="f-button-seg4" @click="showShareListing()">
                <div class="f-button-seg-icon f-share-icon"></div>
                <div class="f-button-text">Share</div>
            </div>
            <div class="f-button-seg4" @click="scrollToTop()">
                <div class="f-button-seg-icon f-to-top-icon"></div>
                <div class="f-button-text">To Top</div>
            </div>
        </div>
    </div>

</div>


<div class="f-overlay" :style="{ display: (pages.commentContact) ? 'flex': 'none' }" 
    @click.self="closeCommentContact()">
    <div class="f-overlay-content">

        <div class="f-overlay-header f-fc">
            <div class="fusion-heading f-heading-<?=$uniqueKey?> f-mt-3 f-mb-3">
                Contact {{ comments.comment.name }} {{ comments.comment.surname }}
            </div>
        </div>

        <div class="f-overlay-body">
            <div class="f-link-btn-wrap">
                <div class="f-link-btn-byron f-ml-3" @click="openDialerApp(comments.comment.mobile)">
                    <div class="f-link-btn-icon f-contact-icon f-mt-2 f-ml-2"></div>
                    <div class="f-link-btn-text f-mt-2 f-ml-2">Call</div>
                    <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
                </div>
                <div class="f-link-btn-byron f-ml-3" @click="openWhatsAppApp(comments.comment.mobile)">
                    <div class="f-link-btn-icon f-whatsapp-icon f-mt-2 f-ml-2"></div>
                    <div class="f-link-btn-text f-mt-2 f-ml-2">WhatsApp</div>
                    <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
                </div>
                <div v-if="permissions.delete" class="f-link-btn-byron f-ml-3" @click="deleteComment()">
                    <div class="f-link-btn-icon f-delete-icon f-mt-2 f-ml-2" style="background-color: #000;"></div>
                    <div class="f-link-btn-text f-mt-2 f-ml-2">Remove</div>
                    <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
                </div>
            </div>
        </div>

        <div class="f-overlay-footer">
            <div class="f-buttons-<?=$uniqueKey?>">
                <div class="f-button" @click="closeCommentContact()">
                    <div class="f-button-seg-icon f-back-icon"></div>
                    <div class="f-button-text">Back</div>
                </div>
            </div>
        </div>

    </div>
</div>



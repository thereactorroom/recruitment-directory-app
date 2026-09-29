
<div class="page f-page" :style="{ display: (pages.listComments) ? 'flex': 'none' }" >
    
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$uniqueKey?>">
            <div class="f-header-text-full f-ml-3">
                {{ listing.name }}
            </div>
        </div>
    </div>

    <div class="f-body">
        
        <div class="f-body-contents-search-<?=$uniqueKey?> f-mt-3">
            <div class="f-input-wrap f-mt-3 f-ml-3 f-mb-3 f-border-bottom">
                <input type="text" class="f-input f-input-left f-pl-2"
                    v-model="comments.mainFilter" placeholder="Search comments by name." >
                <div class="f-action-lnk-<?=$uniqueKey?> f-action-lnk-search" @click="comments.mainFilter = ''">clear</div>
            </div>
        </div>

        <div class="f-body-contents-full f-scroll main-contents-<?=$uniqueKey?>">

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

    <div class="f-footer">
        <div class="f-buttons-<?=$uniqueKey?>">
            <div class="f-button-seg4" @click="closeComments()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div v-if="getAlreadyCommented" class="f-button-seg4 f-act-btn" @click="showEditComment()">
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
            <div class="f-button-seg4" @click="scrollToTop()">
                <div class="f-button-seg-icon f-to-top-icon"></div>
                <div class="f-button-text">To Top</div>
            </div>
        </div>
    </div>

</div>
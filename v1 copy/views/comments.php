
<div class="page f-page" :style="{ display: (pages.comments) ? 'flex': 'none' }" >
    
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$unique_key?>">
            <div class="f-header-text-full f-ml-3">
                {{businesses.business.business_name}}
            </div>
        </div>
    </div>

    <div class="f-body">
        
        <div class="f-body-contents-search-<?=$unique_key?> f-mt-3">
            <div class="f-input-wrap f-mt-3 f-ml-3 f-mb-3 f-border-bottom">
                <input type="text" class="f-input f-input-left f-pl-2"
                    v-model="comments.mainFilter" placeholder="Search comments by name." >
                <div class="f-action-lnk-<?=$unique_key?> f-action-lnk-search" @click="comments.mainFilter = ''">clear</div>
            </div>
        </div>

        <div class="f-body-contents-full f-scroll main-contents-<?=$unique_key?>">

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
        <div class="f-buttons-<?=$unique_key?>">
            <div class="f-button-seg4" @click="closeComments()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div v-if="getAlreadyCommented" class="f-button-seg4 f-act-btn" @click="showEditComment(getMyCommentFromComments())">
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


<div class="f-overlay" :style="{ display: (pages.addComment) ? 'flex': 'none' }" 
    @click.self="closeAddComment()">
    <div class="f-overlay-content">

        <div class="f-overlay-header f-fc">
            <div class="fusion-heading f-heading-<?=$unique_key?> f-mt-3 f-mb-3">
                Add Rating
            </div>
        </div>

        <div class="f-overlay-body">
            <div class="f-overlay-body-contents f-mt-3">
                <div class="fusion-description f-mt-0 f-ml-0 f-mb-0">Enter your comment below.</div>
                <div class="f-input-wrap">
                    <textarea class="f-input" rows="3" v-model="comments.content.comment"></textarea>
                </div>
                <div class="f-input-wrap">
                    <div class="f-icon-wrap f-mt-2">
                        <div class="f-icon-icon f-grey-star-icon"
                            id="add_star_1"
                            @click="selectStars(1)">
                        </div>
                    </div>
                    <div class="f-icon-wrap f-mt-2 f-ml-1">
                        <div class="f-icon-icon f-grey-star-icon"
                            id="add_star_2"
                            @click="selectStars(2)">
                        </div>
                    </div>
                    <div class="f-icon-wrap f-mt-2 f-ml-1">
                        <div class="f-icon-icon f-grey-star-icon"
                            id="add_star_3"
                            @click="selectStars(3)">
                        </div>
                    </div>
                    <div class="f-icon-wrap f-mt-2 f-ml-1">
                        <div class="f-icon-icon f-grey-star-icon"
                            id="add_star_4"
                            @click="selectStars(4)">
                        </div>
                    </div>
                    <div class="f-icon-wrap f-mt-2 f-ml-1">
                        <div class="f-icon-icon f-grey-star-icon"
                            id="add_star_5"
                            @click="selectStars(5)">
                        </div>
                    </div>
                </div>
                <div class="f-input-wrap f-mb-3">
                    <div class="f-list-item-left f-fc"></div>
                    <div class="f-list-item-side"> 
                        <div class="f-action-btn f-act-bg-<?=$unique_key?> f-mt-1" 
                            @click="addComment()">Post</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="f-overlay-footer">
            <div class="f-buttons-<?=$unique_key?>">
                <div class="f-button" @click="closeAddComment()">
                    <div class="f-button-seg-icon f-back-icon"></div>
                    <div class="f-button-text">Back</div>
                </div>
            </div>
        </div>

    </div>
</div>


<div class="f-overlay" :style="{ display: (pages.editComment) ? 'flex': 'none' }" 
    @click.self="closeEditComment()">
    <div class="f-overlay-content">

        <div class="f-overlay-header f-fc">
            <div class="fusion-heading f-heading-<?=$unique_key?> f-mt-3 f-mb-3">
                Edit Rating
            </div>
        </div>

        <div class="f-overlay-body">
            <div class="f-overlay-body-contents f-mt-3">
                <div class="fusion-description f-mt-0 f-ml-0 f-mb-0">Enter your comment below.</div>
                <div class="f-input-wrap">
                    <textarea class="f-input" rows="3" v-model="comments.content.comment"></textarea>
                </div>
                <div class="f-input-wrap">
                    <div class="f-icon-wrap f-mt-2">
                        <div class="f-icon-icon f-grey-star-icon"
                            id="edit_star_1"
                            @click="selectStars(1)">
                        </div>
                    </div>
                    <div class="f-icon-wrap f-mt-2 f-ml-1">
                        <div class="f-icon-icon f-grey-star-icon"
                            id="edit_star_2"
                            @click="selectStars(2)">
                        </div>
                    </div>
                    <div class="f-icon-wrap f-mt-2 f-ml-1">
                        <div class="f-icon-icon f-grey-star-icon"
                            id="edit_star_3"
                            @click="selectStars(3)">
                        </div>
                    </div>
                    <div class="f-icon-wrap f-mt-2 f-ml-1">
                        <div class="f-icon-icon f-grey-star-icon"
                            id="edit_star_4"
                            @click="selectStars(4)">
                        </div>
                    </div>
                    <div class="f-icon-wrap f-mt-2 f-ml-1">
                        <div class="f-icon-icon f-grey-star-icon"
                            id="edit_star_5"
                            @click="selectStars(5)">
                        </div>
                    </div>
                </div>
                <div v-if="permissions.update"  class="f-input-wrap f-mb-3">
                    <div class="f-list-item-left f-fc"></div>
                    <div class="f-list-item-side"> 
                        <div class="f-action-btn f-act-bg-<?=$unique_key?> f-mt-1" 
                            @click="updateComment()">Save</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="f-overlay-footer">
            <div class="f-buttons-<?=$unique_key?>">

                <div class="f-button-seg2" @click="closeEditComment()">
                    <div class="f-button-seg-icon f-back-icon"></div>
                    <div class="f-button-text">Back</div>
                </div>

                <div v-if="permissions.delete && comments.comment.deleted == ''" 
                    class="f-button-seg2" @click="deleteComment()">
                    <div class="f-button-seg-icon"
                        style="background-image: url('/modules/module_dev/noticeboard/images/close_icon.svg')"></div>
                    <div class="f-button-text">Remove</div>
                </div>

                <!-- <div v-else-if="permissions.deleteContent" class="f-button-seg3" 
                    @click="recoverComment() && comments.comment.deleted != ''">
                    <div class="f-button-seg-icon"
                        style="background-image: url('/modules/module_dev/noticeboard/images/new_post_icon.svg')"></div>
                    <div class="f-button-text">Un Delete</div>
                </div> -->
                
            </div>
        </div>

    </div>
</div>


<div class="f-overlay" :style="{ display: (pages.commentContact) ? 'flex': 'none' }" 
    @click.self="closeCommentContact()">
    <div class="f-overlay-content">

        <div class="f-overlay-header f-fc">
            <div class="fusion-heading f-heading-<?=$unique_key?> f-mt-3 f-mb-3">
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
            <div class="f-buttons-<?=$unique_key?>">
                <div class="f-button" @click="closeCommentContact()">
                    <div class="f-button-seg-icon f-back-icon"></div>
                    <div class="f-button-text">Back</div>
                </div>
            </div>
        </div>

    </div>
</div>



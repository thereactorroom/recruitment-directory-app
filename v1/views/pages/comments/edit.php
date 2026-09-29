<div class="f-overlay" :style="{ display: (pages.editComment) ? 'flex': 'none' }" 
    @click.self="closeEditComment()">
    <div class="f-overlay-content">

        <div class="f-overlay-header f-fc">
            <div class="fusion-heading f-heading-<?=$uniqueKey?> f-mt-3 f-mb-3">
                Edit Rating
            </div>
        </div>

        <div class="f-overlay-body">
            <div class="f-overlay-body-contents f-mt-3">
                <div class="fusion-description f-mt-0 f-ml-0 f-mb-0">Enter your comment below.</div>
                <div class="f-input-wrap">
                    <textarea class="f-input" rows="3" v-model="getComment.comment"></textarea>
                </div>
                <div class="f-input-wrap">
                    <div class="f-icon-wrap f-mt-2">
                        <div class="f-icon-icon f-grey-star-icon"
                            id="edit_rec_star_1_<?=$uniqueKey?>"
                            @click="selectRatingStars(1, 'edit')">
                        </div>
                    </div>
                    <div class="f-icon-wrap f-mt-2 f-ml-1">
                        <div class="f-icon-icon f-grey-star-icon"
                            id="edit_rec_star_2_<?=$uniqueKey?>"
                            @click="selectRatingStars(2, 'edit')">
                        </div>
                    </div>
                    <div class="f-icon-wrap f-mt-2 f-ml-1">
                        <div class="f-icon-icon f-grey-star-icon"
                            id="edit_rec_star_3_<?=$uniqueKey?>"
                            @click="selectRatingStars(3, 'edit')">
                        </div>
                    </div>
                    <div class="f-icon-wrap f-mt-2 f-ml-1">
                        <div class="f-icon-icon f-grey-star-icon"
                            id="edit_rec_star_4_<?=$uniqueKey?>"
                            @click="selectRatingStars(4, 'edit')">
                        </div>
                    </div>
                    <div class="f-icon-wrap f-mt-2 f-ml-1">
                        <div class="f-icon-icon f-grey-star-icon"
                            id="edit_rec_star_5_<?=$uniqueKey?>"
                            @click="selectRatingStars(5, 'edit')">
                        </div>
                    </div>
                </div>
                <div v-if="permissions.update"  class="f-input-wrap f-mb-3">
                    <div class="f-list-item-left f-fc"></div>
                    <div class="f-list-item-side"> 
                        <div class="f-action-btn f-act-bg-<?=$uniqueKey?> f-mt-1" 
                            @click="updateComment()">Save</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="f-overlay-footer">
            <div class="f-buttons-<?=$uniqueKey?>">

                <div class="f-button-seg2" @click="closeEditComment()">
                    <div class="f-button-seg-icon f-back-icon"></div>
                    <div class="f-button-text">Back</div>
                </div>

                <div v-if="permissions.delete && getComment.deleted == ''" 
                    class="f-button-seg2" @click="deleteComment()">
                    <div class="f-button-seg-icon"
                        style="background-image: url('/modules/module_dev/noticeboard/images/close_icon.svg')"></div>
                    <div class="f-button-text">Remove</div>
                </div>
                
            </div>
        </div>

    </div>
</div>
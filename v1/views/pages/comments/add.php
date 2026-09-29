<div class="f-overlay" :style="{ display: (pages.addComment) ? 'flex': 'none' }" 
    @click.self="closeAddComment()">
    <div class="f-overlay-content">

        <div class="f-overlay-header f-fc">
            <div class="fusion-heading f-heading-<?=$uniqueKey?> f-mt-3 f-mb-3">
                Add Rating
            </div>
        </div>

        <div class="f-overlay-body">
            <div class="f-overlay-body-contents f-mt-3">
                <div class="fusion-description f-mt-0 f-ml-0 f-mb-0">Enter your comment below.</div>
                <div class="f-input-wrap">
                    <textarea class="f-input" rows="3" v-model="comments.create.comment"></textarea>
                </div>
                <div class="f-input-wrap">
                    <div class="f-icon-wrap f-mt-2">
                        <div class="f-icon-icon f-grey-star-icon"
                            id="create_rec_star_1_<?=$uniqueKey?>"
                            @click="selectRatingStars(1, 'create')">
                        </div>
                    </div>
                    <div class="f-icon-wrap f-mt-2 f-ml-1">
                        <div class="f-icon-icon f-grey-star-icon"
                            id="create_rec_star_2_<?=$uniqueKey?>"
                            @click="selectRatingStars(2, 'create')">
                        </div>
                    </div>
                    <div class="f-icon-wrap f-mt-2 f-ml-1">
                        <div class="f-icon-icon f-grey-star-icon"
                            id="create_rec_star_3_<?=$uniqueKey?>"
                            @click="selectRatingStars(3, 'create')">
                        </div>
                    </div>
                    <div class="f-icon-wrap f-mt-2 f-ml-1">
                        <div class="f-icon-icon f-grey-star-icon"
                            id="create_rec_star_4_<?=$uniqueKey?>"
                            @click="selectRatingStars(4, 'create')">
                        </div>
                    </div>
                    <div class="f-icon-wrap f-mt-2 f-ml-1">
                        <div class="f-icon-icon f-grey-star-icon"
                            id="create_rec_star_5_<?=$uniqueKey?>"
                            @click="selectRatingStars(5, 'create')">
                        </div>
                    </div>
                </div>
                <div class="f-input-wrap f-mb-3">
                    <div class="f-list-item-left f-fc"></div>
                    <div class="f-list-item-side"> 
                        <div class="f-action-btn f-act-bg-<?=$uniqueKey?> f-mt-1" 
                            @click="addComment()">Post</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="f-overlay-footer">
            <div class="f-buttons-<?=$uniqueKey?>">
                <div class="f-button" @click="closeAddComment()">
                    <div class="f-button-seg-icon f-back-icon"></div>
                    <div class="f-button-text">Back</div>
                </div>
            </div>
        </div>

    </div>
</div>
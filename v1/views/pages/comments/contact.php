
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
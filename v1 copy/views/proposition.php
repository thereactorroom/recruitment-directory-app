

<div class="page f-page" :style="{ display: (pages.proposition) ? 'flex': 'none' }" >
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$unique_key?> f-mt-3">
            <div v-if="proposition.title.length > 0" class="f-header-text-full f-ml-3">
                {{proposition.title}}
            </div>
            <div v-else class="f-header-text-full f-ml-3">
                Business Proposition
            </div>
        </div>
    </div>

    <div class="f-body">
        <div class="f-body-contents f-scroll main-contents-<?=$unique_key?>">

            <div v-html="proposition.proposition" class="quill-content-<?=$unique_key?>"></div>

        </div>
    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$unique_key?>">
            <div class="f-button-seg2" @click="closeProposition()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Close</div>
            </div>
            <div class="f-button-seg2" @click="showEditProposition()">
                <div class="f-button-seg-icon f-edit-icon"></div>
                <div class="f-button-text">Edit</div>
            </div>
        </div>
    </div>
</div>


<div class="page f-page" :style="{ display: (pages.editProposition) ? 'flex': 'none' }" >
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$unique_key?> f-mt-3">
            <div class="f-header-text-full f-ml-3">
                Edit Proposition
            </div>
        </div>
    </div>

    <div class="f-body">
        <div class="f-body-contents f-scroll main-contents-<?=$unique_key?>">

            <div class="f-input-wrap f-border-bottom">
                <input type="text" class="f-input f-input-left" v-model="proposition.title" placeholder="Title *" >
                <div class="f-action-lnk-<?=$unique_key?> f-action-lnk-<?=$unique_key?>" @click="proposition.title = ''">clear</div>
            </div>
            <div class="f-input-wrap f-border-bottom f-df f-fc" style="margin-bottom: 60px;">
                <wiziwig 
                    ref="quillEditor"
                    :options="{
                        container: {
                            'class': 'f-wisiwig-input'
                        }
                    }"
                    @init="initWiziwig($event)"
                    @text-change="onTextChange($event)" 
                    @image-change="onImageChange($event)"></wiziwig>
            </div>

        </div>
    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$unique_key?>">
            <div class="f-button-seg3" @click="closeEditProposition()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div class="f-button-seg3" @click="saveProposition()">
                <div class="f-button-seg-icon f-save-icon"></div>
                <div class="f-button-text">Save</div>
            </div>
            <div class="f-button-seg3" @click="deleteProposition()">
                <div class="f-button-seg-icon f-delete-icon"></div>
                <div class="f-button-text">Delete</div>
            </div>
        </div>
    </div>
</div>






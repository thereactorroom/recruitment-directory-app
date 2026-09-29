<div class="page f-page" :style="{ display: (pages.moduleConfig) ? 'flex': 'none' }" >
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$uniqueKey?> f-mt-3">
            <div class="f-header-text-full f-ml-3">
                Module Configurations
            </div>
        </div>
        <div class="fusion-description f-mt-2">
            Enter below module configuration
        </div>
    </div>

    <div class="f-body">

        <div class="f-body-contents f-scroll main-contents-<?=$uniqueKey?>">
            
            <div class="fusion-description f-mt-3 f-ml-0 f-mb-0">Enter App Title</div>
            <div class="f-input-wrap f-border-bottom">
                <input type="text" class="f-input f-input-left" v-model="moduleConfig.app_title" placeholder="Enter App Title" >
                <div v-show="!isEmpty(moduleConfig.app_title)" 
                    class="f-action-lnk-<?=$uniqueKey?>" 
                    @click="moduleConfig.app_title = ''">clear</div>
            </div>

            <div class="fusion-description f-mt-3 f-ml-0 f-mb-0">Enter Specials Url</div>
            <div class="f-input-wrap f-border-bottom">
                <input type="text" class="f-input f-input-left" v-model="moduleConfig.base44_specials_url" placeholder="Enter Specials Url" >
                <div v-show="!isEmpty(moduleConfig.base44_specials_url)" 
                    class="f-action-lnk-<?=$uniqueKey?>" 
                    @click="moduleConfig.base44_specials_url = ''">clear</div>
            </div>

        </div>

    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$uniqueKey?>">
            <div class="f-button-seg2" @click="closeModuleConfigPage()">
                <div class="f-button-seg-icon f-back-icon"></div>
                <div class="f-button-text">Back</div>
            </div>
            <div class="f-button-seg2 f-act-btn" @click="saveModuleConfigChanges()">
                <div class="f-button-seg-icon f-add-icon"></div>
                <div class="f-button-text">Save Changes</div>
            </div>
        </div>
    </div>
</div>

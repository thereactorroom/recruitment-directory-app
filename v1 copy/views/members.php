
<div class="page f-page" :style="{ display: pages.members ? 'flex': 'none' }" >
    <div class="f-header f-mt-3">
        <div class="f-heading-<?=$unique_key?> f-fc f-mt-3"
            @click="showBusinessDirectory()">
            <div class="f-header-text-full f-ml-3">
                {{getBlacklistedList.length}} Member<span v-if="getBlacklistedList.length > 1">s</span> Muted
            </div>
        </div>
        <div class="fusion-description f-mt-2">
            Click on member to Mute or Unmute them. Muting prevents Listings, Referrals, Rating and Liking.
        </div>
    </div>

    <div class="f-body">

        <div class="f-body-contents f-h-120">
            <div class="f-input-wrap f-mt-0">
                <input type="text" class="f-input f-border-bottom"
                    v-model="members.memberFilter" placeholder="Search member by name." >
            </div>
        </div>

        <div class="f-body-contents-full f-scroll f-mb-3"
            style="height: calc(var(--s_height) - calc(calc(520/750) * var(--seg_width))) !important;">

            <!--  if list.length == 0 && is loading traders -> then show this, else empty list -->
            <div v-if="loading.blacklisted" 
                class="f-list-item" style="border-bottom: 0px;">
                <div class="f-list-item-wrap f-mt-3 f-ml-3">
                    <div class="loader-<?=$unique_key?>"></div> 
                    <div class="loader-text">Loading ...</div>
                </div>
            </div>

            <div v-else v-for="(member, index) in members.list" :key="index" 
                class="f-list-item f-fc f-mb-2"
                v-show="searchMembers(member)">
                <div class="f-list-item-wrap f-fr f-ml-3 f-mb-3">

                    <div v-if="memberIsNoPicture(member)" class="f-list-item-image" style="
                            background-color: #0072FF;
                            color: white;
                            text-align: center;
                            display: block;
                            line-height: calc(calc(90/750) * var(--seg_width));
                        " 
                    >{{memberNoPicture(member)}}</div>
                    <div v-else class="f-list-item-image" :style="memberPhoto(member.picture, '&w=360&h=360&q=100&zc=6')"></div>
                    
                    <div class="f-list-item-middle-3x f-fc" 
                        @click="showMemberDetails(member)">
                        <div class="f-list-item-name f-ml-3 f-mt-3">{{ member.name }} {{ member.surname }}</div>
                    </div>
                    
                    <div v-if="isMemberBlacklisted(member)" class="f-list-item-side"
                        @click="blacklistMember(member, 'unblacklist')" >
                        <div class="f-icon f-unassign-icon f-mt-1 f-ml-3"></div>
                    </div>

                    <div v-else class="f-list-item-side"
                        @click="blacklistMember(member, 'blacklist')" >
                        <div class="f-icon f-assign-icon f-mt-1 f-ml-3"></div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <div class="f-footer">
        <div class="f-buttons-<?=$unique_key?>">
            <div class="f-button" @click="closeMembers()">
                <div class="f-button-seg-icon"
                    style="background-image: url('/modules/members-management-v2/images/back_icon.svg')"></div>
                <div class="f-button-text">Close</div>
            </div>
        </div>
    </div>

</div>


<div class="f-overlay" :style="{ display: (pages.blacklistDetails) ? 'flex': 'none' }" 
    @click.self="closeMemberDetails()">
    <div class="f-overlay-content">
        <div class="f-overlay-header">
            <div class="fusion-heading f-heading-<?=$unique_key?> f-mt-3 f-mb-3">
                <div class="f-header-text-full">
                    {{members.member.name}} {{members.member.surname}}
                </div>
            </div>
        </div>

        <div class="f-overlay-body">
            <div class="f-overlay-body-contents f-scroll f-mt-3">

                <div class="f-input-wrap f-mt-0">
                    <div class="f-link-btn" @click="openDialerApp(members.member.mobile)">
                        <div class="f-link-btn-icon f-contact-icon f-mt-2 f-ml-2"></div>
                        <div class="f-link-btn-text f-mt-2 f-ml-2">Call</div>
                        <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2">{{members.member.name}}</div>
                    </div>
                    <div class="f-link-btn f-ml-3" @click="openWhatsAppApp(members.member.mobile)">
                        <div class="f-link-btn-icon f-whatsapp-icon f-mt-2 f-ml-2"></div>
                        <div class="f-link-btn-text f-mt-2 f-ml-2">WhatsApp</div>
                        <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2">{{members.member.name}}</div>
                    </div>
                </div>

                <div class="f-input-wrap"></div>

            </div>
        </div>

        <div class="f-overlay-footer">
            <div class="f-buttons-<?=$unique_key?>">
                <div class="f-button" @click="closeMemberDetails()">
                    <div class="f-button-seg-icon"
                        style="background-image: url('/modules/members-management-v2/images/back_icon.svg')"></div>
                    <div class="f-button-text">Back</div>
                </div>
            </div>
        </div>

    </div>
</div>



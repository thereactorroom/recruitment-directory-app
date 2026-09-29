if(!Object.keys(Vue.options.components).includes('listing-buttons')) {
    Vue.component('listing-buttons', {
        template: `
            <div class="f-link-btn-wrap f-scroll">
                
                <div v-if="
                        listing.paid == 1 && 
                        listing.downgraded == 0
                    " class="f-link-btn-byron f-ml-3" 
                    @click="$emit('open-specials')">
                    <div class="f-link-btn-icon f-my-specials-icon f-mt-2 f-ml-2"></div>
                    <div class="f-link-btn-text f-mt-2 f-ml-2">My Specials</div>
                    <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
                </div>

                <div class="f-link-btn-byron f-ml-3" @click="$emit('call', listing)">
                    <div class="f-link-btn-icon f-contact-icon f-mt-2 f-ml-2"></div>
                    <div class="f-link-btn-text f-mt-2 f-ml-2">Call</div>
                    <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
                </div>

                <div class="f-link-btn-byron f-ml-3"@click="$emit('open-comments', listing)">
                    <div class="f-link-btn-icon f-mt-2 f-ml-2"
                        :class="{
                            'f-grey-star-icon': listing.stars_avg == 0,
                            'f-gold-star-icon': listing.stars_avg > 0
                        }">
                        <div class="f-link-btn-text f-mt-2 f-ml-2" 
                            style="
                                margin-left: calc(calc(100/750)* var(--seg_width)) !important;
                                position: absolute;
                                margin-top: calc(calc(35/750)* var(--seg_width)) !important;
                                font-size: calc(calc(35/750)* var(--seg_width)) !important;
                            ">
                            {{ listing.stars_avg }}
                        </div>
                    </div> 
                    <div class="f-link-btn-text f-mt-2 f-ml-2">{{ listing.comments }} Ratings</div> 
                    <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
                </div>
                
                <div v-if="!isEmpty(listing.whatsapp)" class="f-link-btn-byron f-ml-3" @click="$emit('whatsapp', listing)">
                    <div class="f-link-btn-icon f-whatsapp-icon f-mt-2 f-ml-2"></div>
                    <div class="f-link-btn-text f-mt-2 f-ml-2">WhatsApp</div>
                    <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
                </div>
                
                <div v-if="!isEmpty(listing.email)" class="f-link-btn-byron f-ml-3" 
                    @click="$emit('email', listing)">
                    <div class="f-link-btn-icon f-invite-icon f-mt-2 f-ml-2"></div>
                    <div class="f-link-btn-text f-mt-2 f-ml-2">Email</div>
                    <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
                </div>
                <div v-if="!isEmpty(listing.google_url)" class="f-link-btn-byron f-ml-3" 
                    @click="$emit('url', listing.google_url)">
                    <div class="f-link-btn-icon f-google-business-icon f-mt-2 f-ml-2"></div>
                    <div class="f-link-btn-text f-mt-2 f-ml-2">Google</div>
                    <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
                </div>

                <div v-if="!isEmpty(listing.website_url)" class="f-link-btn-byron f-ml-3" 
                    @click="$emit('url', listing.website_url)">
                    <div class="f-link-btn-icon f-website-icon f-mt-2 f-ml-2"></div>
                    <div class="f-link-btn-text f-mt-2 f-ml-2">Website</div>
                    <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
                </div>

                <div v-if="!isEmpty(listing.facebook_url)" class="f-link-btn-byron f-ml-3" 
                    @click="$emit('url', listing.facebook_url)">
                    <div class="f-link-btn-icon f-facebook-icon f-mt-2 f-ml-2"></div>
                    <div class="f-link-btn-text f-mt-2 f-ml-2">Facebook</div>
                    <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
                </div>

                <div v-if="!isEmpty(listing.x_url)" class="f-link-btn-byron f-ml-3" 
                    @click="$emit('url', listing.x_url)">
                    <div class="f-link-btn-icon f-x-icon f-mt-2 f-ml-2"></div>
                    <div class="f-link-btn-text f-mt-2 f-ml-2">X</div>
                    <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
                </div>

                <div v-if="!isEmpty(listing.instagram_url)" class="f-link-btn-byron f-ml-3" 
                    @click="$emit('url', listing.instagram_url)">
                    <div class="f-link-btn-icon f-instagram-icon f-mt-2 f-ml-2"></div>
                    <div class="f-link-btn-text f-mt-2 f-ml-2">Instagram</div>
                    <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
                </div>

                <div class="f-link-btn-byron f-ml-3" @click="$emit('open-cv', listing.cv_path)">
                    <div class="f-link-btn-icon f-member-blacklist-icon f-mt-2 f-ml-2"></div>
                    <div class="f-link-btn-text f-mt-2 f-ml-2">CV - PDF DOC</div>
                    <div class="f-link-btn-category f-mt-2 f-ml-2 f-mb-2"></div>
                </div>
                
            </div>
        `,
        props: ['listing', 'isCreator', 'isAdmin'],
        methods: {
            isEmpty: function(value){
                if (value == undefined) {
                    return false;
                }
                if (value.length == 0 || value == "") {
                    return true;
                }
                return false;
            },
        }
    });
}

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
        <title><?= $name ?> Module</title>
        <style><?= $cssContent ?></style>
    </head>
    <body>
        <div id="recruitment_directory<?=$uniqueKey?>">
            <!-- Main -->
            <?php require 'pages/main/index.php'; ?>

            <!-- Listings -->
            <?php require 'pages/listings/listings.php'; ?>
            <?php require 'pages/listings/add_free.php'; ?>
            <?php require 'pages/listings/view_free.php'; ?>
            <?php require 'pages/listings/edit_free.php'; ?>
            <?php require 'pages/listings/add_paid.php'; ?>
            <?php require 'pages/listings/view_paid.php'; ?>
            <?php require 'pages/listings/edit_paid.php'; ?>
            <?php require 'pages/listings/share.php'; ?>
            <?php require 'pages/listings/select_gender.php'; ?>

            <!-- Comments -->
            <?php require 'pages/comments/list.php'; ?>
            <?php require 'pages/comments/add.php'; ?>
            <?php require 'pages/comments/edit.php'; ?>
            <?php require 'pages/comments/contact.php'; ?>
            
            <!-- Admin -->
            <?php require 'pages/admin/index.php'; ?>
            <?php require 'pages/admin/members.php'; ?>
            <?php require 'pages/admin/proposition.php'; ?>
            <?php require 'pages/admin/manage.php'; ?>
            <?php require 'pages/admin/free.php'; ?>
            <?php require 'pages/admin/approvals.php'; ?>
            <?php require 'pages/admin/downgraded.php'; ?>
            <?php require 'pages/admin/payment_logs.php'; ?>
            <?php require 'pages/admin/module_config.php'; ?>

            <!-- Vouchers -->
            <?php require 'pages/vouchers/index.php'; ?>
            <?php require 'pages/vouchers/view.php'; ?>
            <?php require 'pages/vouchers/add.php'; ?>
            <?php require 'pages/vouchers/edit.php'; ?>
            <?php require 'pages/vouchers/claims.php'; ?>
            <?php require 'pages/vouchers/claim.php'; ?>
            <?php require 'pages/vouchers/pay.php'; ?>
        </div>

        <script>
            (function(fusion, $) {
                <?php 
                    echo file_get_contents("$modulePath/scripts/utils.js"); 
                    echo file_get_contents("$modulePath/scripts/setup.js");
                    echo file_get_contents("$modulePath/scripts/state.js"); 
                    echo file_get_contents("$modulePath/scripts/computed.js");
                    echo file_get_contents("$modulePath/scripts/wiziwig.js");
                    echo file_get_contents("$modulePath/scripts/methods/filters.js");
                    echo file_get_contents("$modulePath/scripts/methods/members.js");
                    echo file_get_contents("$modulePath/scripts/methods/listings.js");
                    echo file_get_contents("$modulePath/scripts/methods/comments.js");
                    echo file_get_contents("$modulePath/scripts/methods/admin.js");
                    echo file_get_contents("$modulePath/scripts/methods/favorites.js");
                    echo file_get_contents("$modulePath/scripts/methods/module_config.js");

                    echo file_get_contents("$modulePath/scripts/components/listings/paid_card.js");
                    echo file_get_contents("$modulePath/scripts/components/listings/buttons.js");
                    echo file_get_contents("$modulePath/scripts/components/listings/free_card.js");
                    echo file_get_contents("$modulePath/scripts/components/listings/header_card.js");
                    echo file_get_contents("$modulePath/scripts/components/listings/free_listing.js");
                    echo file_get_contents("$modulePath/scripts/components/listings/paid_listing.js");
                    echo file_get_contents("$modulePath/scripts/components/admin/proposition.js");
                    echo file_get_contents("$modulePath/scripts/components/admin/manage.js");
                    echo file_get_contents("$modulePath/scripts/components/admin/free.js");
                    echo file_get_contents("$modulePath/scripts/components/admin/approvals.js");
                    echo file_get_contents("$modulePath/scripts/components/admin/downgraded.js");
                    echo file_get_contents("$modulePath/scripts/components/admin/vouchers.js");
                ?>

                fusion["recruitment_directory<?=$uniqueKey?>"] = new Object();
                var app = new Vue({
                    el: "#recruitment_directory<?=$uniqueKey?>",
                    created: function() {
                        fusion["recruitment_directory<?=$uniqueKey?>"]["_this"] = this;
                    },
                    mounted: async function() {
                        _this = this;
                        console.log("Mounted", fusion);

                        this.settings.user = user;
                        this.settings.module = "<?=$name?>";
                        this.settings.name = "<?=$name?><?=$category?>";
                        this.settings.icon = "<?=$icon?>";
                        this.settings.community_id = "<?=$communityId?>";
                        this.settings.content_id = "<?=$contentId?>";
                        this.settings.unique_key = "<?=$uniqueKey?>";

                        await this.aGetAllOnMounted(true);

                        $(document).on('change', '.datepicker', function(){
                            _this.setDateModelOnChange();
                        });

                        $(document).on('change', '#selectNewListingCVFromFile', function(e){
                            e.preventDefault();
                            _this.handleUploadCVFromFile(e.target.files);
                        });
                        $(document).on('change', '#selectEditListingCVFromFile', function(e){
                            e.preventDefault();
                            _this.handleUploadCVFromFile(e.target.files);
                        });

                        $(document).on('change', '#selectNewListingLogoFromFile', function(e){
                            e.preventDefault();
                            _this.handleUploadLogoFromFile(e.target.files);
                        });
                        $(document).on('change', '#selectEditListingLogoFromFile', function(e){
                            e.preventDefault();
                            _this.handleUploadLogoFromFile(e.target.files);
                        });
                        
                    },
                    data: stateProperties,
                    computed: computedProperties,
                    methods: Object.assign({}, 
                        utilitiesMethods, 
                        setupMethods, 
                        filtersMethods, 
                        membersMethods,
                        adminMethods,
                        listingsMethods,
                        commentsMethods,
                        favoritesMethods,
                        moduleCondigMethods,
                        freeListingComponent,
                        paidListingComponent,
                        propositionComponent,
                        adminManageListingsComponent,
                        adminFreeComponent,
                        adminApprovalsComponent,
                        adminDowngradedComponent,
                        vouchersComponent
                    )
                });
                
                fusion["componentsInstance"]["recruitment_directory<?=$uniqueKey?>"] = fusion["recruitment_directory<?=$uniqueKey?>"];
                fusion["componentsInstance"]["recruitment_directory<?=$uniqueKey?>"]["refresh"] = function () {
                    if(fusion["recruitment_directory<?=$uniqueKey?>"]['_this'] != undefined) {
                        app.aGetAllOnMounted(false);
                    }
                }

            })(fusion, jQuery);
        </script>
    </body>
</html>

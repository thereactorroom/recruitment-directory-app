<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once('fmini/libs/Utils.php');
    require_once('fmini/libs/Curl.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/envconfig.php');
    
    $env = "production";
    if (strpos($_SERVER['DOCUMENT_ROOT'], "uat") != false) {
        $env = "sandbox";
    }
    $envHost = config['env']['online'][$env]['host'];
    $envPath = $_ENV['ROOT_PATH'];

    $get = (object) $_GET;
    $curl = new Curl();

    $name = $get->name ?? "Recruitment Directory";
    $community_id = $get->communityId ?? "";
    $content_id = $get->contentId ?? "";
    $component = $get->component ?? "false";
    $registration = $get->registration ?? "false";
    $unique_key = "{$community_id}{$content_id}";
    
    $community_icon = "";
    $community_name = "";
    $primary = "#ed494b";
    $secondary = "#9ccc65";
    
    $results = $curl->get("$envHost/api/?communityId=$community_id&action=getCommunity", true);
    if ((bool)$results->result) {
        $community = $results->community;
        $community_icon = $community->icon;
        $community_name = $community->name . " " . $community->category;
    }

    $results = $curl->get("$envHost/api/?communityId=$community_id&action=getCommunityConfig", true);
    if ((bool)$results->result) {
        $config = $results->config;
        if (isset($config->primaryColor)) {
            $primary = $config->primaryColor;
        }
        if (isset($config->secondaryColor)) {
            $secondary = $config->secondaryColor;
        }
    }
    $css_file = "$envPath/modules/module_dev/recruitment_directory/v1/main.css";
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title><?= $name ?> Module</title>
    <style><?php echo Utils::injectCSS($css_file, $primary, $secondary, $unique_key); ?></style>
    <script src="https://www.payfast.co.za/onsite/engine.js"></script> 
</head>
<body>
    <div id="recruitment_directory<?=$unique_key?>">
        <?php
            require_once "views/main.php"; 
            // require_once "views/listings_crud.php"; 
            // require_once "views/business.php"; 
            // require_once "views/referral.php"; 
            // require_once "views/manage_listings.php";
            // require_once "views/comments.php"; 
            // require_once "views/registration.php";
            // require_once "views/members.php"; 
            // require_once "views/proposition.php";
            // require_once "views/approvals.php";
            // require_once "views/admin.php";
        ?>
    </div>
    <script>
        (function(fusion, $) {
            <?php 
                echo file_get_contents("$envPath/modules/module_dev/recruitment_directory/v1/wiziwig.js");
            ?>
            <?php 
                echo file_get_contents("scripts/utils.js"); 
                echo file_get_contents("scripts/screen.js");
                echo file_get_contents("scripts/setup.js"); 
                echo file_get_contents("scripts/filters.js");
                echo file_get_contents("scripts/members.js");
                echo file_get_contents("scripts/listings.js");


                // echo file_get_contents("scripts/businesses.js"); 
                // echo file_get_contents("scripts/comments.js");
                // echo file_get_contents("scripts/favorites.js");
                // 
                // echo file_get_contents("scripts/referrals.js");
                // echo file_get_contents("scripts/registration.js");
                // echo file_get_contents("scripts/proposition.js");
                // echo file_get_contents("scripts/approvals.js");
                // echo file_get_contents("scripts/manage_listings.js");
                // echo file_get_contents("scripts/admin.js");
            ?>
            fusion["recruitment_directory<?=$unique_key?>"] = new Object();
            var app = new Vue({
                el: "#recruitment_directory<?=$unique_key?>",
                created: function() {
                    fusion["recruitment_directory<?=$unique_key?>"]["_this"] = this;
                },
                mounted: function() {
                    _this = this;

                    this.is_registration = "<?=$registration?>" == "true" ? true : false;
                    this.aGetAllOnMounted(true);

                    // handle for images
                    $(document).on('click touch' , '.quillcontent-<?=$unique_key?> img' , function(e) {
                        var img = e.target.currentSrc;
                        img = img.replace(host + "/scripts/mthumb/mthumb.php?src=", "");
                        img = img.replace("&w=360&h=360&q=100&zc=6", "");
                        img = img.replace("&w=360&q=100&zc=6", "");
                        processContent(
                            { contentType: "url", url: img }, 
                            {icon: "", content: "Image", primaryColor: "<?=$primary?>"}
                        );
                    });

                    // handle hyperlinks
                    $(document).on('click touch', '.quillcontent-<?=$unique_key?> a', function(e){
                        e.preventDefault();
                        var data = $(this).attr('href');
                        data = data.replaceAll("tel:", "");
                        data = data.replaceAll("tel", "");
                        data = data.replaceAll("mailto:", "");
                        data = data.replaceAll("mailto", "");
                        data = data.replaceAll("+27", "0");
                        data = data.replaceAll("27", "0");
                        data = data.replaceAll(" ", "");
                        console.log(data);
                        if(_this.isValidEmail(data)) {
                            processContent(
                                {contentType: "external", url: "mailto:" + data}
                            );
                        } else if(_this.isValidMobile(data)) {
                            _this.showCallWhatsAppPopup(data);
                        } else if (data.toLowerCase().endsWith(".pdf")) {
                            console.log("Valid PDF:", data);
                            fusion.openPDF(data);
                        } else if(_this.isValidUrl(data)) {
                            if (!data.includes("http")){
                                data = "https://" + data;
                            }
                            processContent(
                                {contentType: "url", url: data}
                            );
                        } else {
                            $(`.message-popup`).showMessage({
                                message: `Can't open link: ${data}`,
                                buttons: [
                                    { text: 'Ok', onTap: function() {
                                        $(`.message-popup`).hide()
                                    }}
                                ]
                            });
                        }
                    });

                    $(document).on('change', '#registrationBusinessLogo', function(e){
                        e.preventDefault();

                        let file = undefined;
                        const files = e.target.files;
                        for (let i = 0; i < files.length; i++) {
                            if (files[i].type.match(/^image\//)) {
                                file = files[i];
                                break;
                            }
                        }

                        var reader = new FileReader();
                        $(".registration-croppie-image").attr("src", "#");
                        reader.onloadend = function() {
                            _this.registration.logo = reader.result;
                        }
                        reader.readAsDataURL(file);
                    });

                    $(document).on('change', '#businessLogo', function(e){
                        e.preventDefault();

                        let file = undefined;
                        const files = e.target.files;
                        for (let i = 0; i < files.length; i++) {
                            if (files[i].type.match(/^image\//)) {
                                file = files[i];
                                break;
                            }
                        }

                        var reader = new FileReader();
                        reader.onloadend = function() {
                            console.log('RESULT: ', reader.result);
                            _this.businesses.business.logo = reader.result;
                        }
                        reader.readAsDataURL(file);
                    });

                    $(document).on('click touch', '.nav-button', function(e){
                        var action = $(this).attr('data-page');
                        if (action === "minimize") {
                            _this.exit();
                        }
                    });
                
                    // Create a broadcast channel
                    let channel = new BroadcastChannel("payfast_channel");
                    channel.onmessage = (event) => {
                        let message = event.data;
                        console.log('message from payfast: ', message);
                        if (message == 'success') {
                            console.log('doing work after being successful');
                            // _this.getMyBusinesses();
                            // _this.getAdminDashStats();
                            // _this.closeRegistrationPages();
                            // _this.closeBusinessPaymentPage();
                            // _this.closeBusinessPage();
                            _this.scrollToTop();
                        }
                    };
                },
                data: {
                    urls: {
                        root: `${host}/api/`,
                        main: `${host}/modules/module_dev/recruitment_directory/v1/`,
                        pic: `${host}/modules/member_profile/`,
                        thumb: `${host}/scripts/mthumb/mthumb.php?src=`,
                        member: `${host}/modules/members-management-v2/api`,
                        analytics: `${host}/modules/module_dev/module_analytics/`,
                    },
                    user: user,
                    community_id: "<?=$community_id?>",
                    content_id: "<?=$content_id?>",
                    unique_key: "<?=$unique_key?>",
                    unique_key_id: undefined,
                    unique_key_dev_id: undefined,
                    unique_key_area_id: undefined,
                    user_key: undefined,
                    member: undefined,
                    session: undefined,
                    is_registration: false,
                    admin_dash_stats: {},
                    settings: {
                        module_name: "<?=$name?>",
                        community_name: "<?=$community_name?>",
                        community_icon: "<?=$community_icon?>",
                        platform: fusion.app.toLowerCase(),
                        mobile: function() {
                            return this.platform == 'ios' || this.platform == 'android';
                        }
                    },
                    permissions: {
                        admin: false,
                        visitor: false,
                        add: false,
                        update: false,
                        delete: false,
                    },
                    loading: {
                        businesses: false,
                        favorites: false,
                        members: false,
                        blacklisted: false,
                        myListings: false
                    },
                    pages: {
                        depth: 0,
                        page: "",
                        main: true,
                        addFreeListing: false,
                        

                        comments: false,
                        addComment: false,
                        editComment: false,
                        commentContact: false,
                        businesses: false,
                        business: false,
                        editBusiness: false,
                        editBusinessListing: false,
                        editBusinessTab: "",
                        businessContact: false,
                        businessReferral: false,
                        businessPayment: false,
                        businessAnalytics: false,
                        contact: false,
                        directory: false,
                        members: false,
                        details: false,
                        filter: false,
                        sort: false,
                        manageFilter: false,
                        addReferral: false,
                        editReferral: false,
                        referralsAdmin: false,
                        referralsCallLog: false,
                        referralsCallLogComment: false,
                        referralCallWhatsApp: false,
                        registration: {
                            landing: false,
                            search: false,
                            results: false,
                            _2fa: false,
                            business: false,
                            croppie: false,
                            tab: "details",
                            preview: false,
                            payment: false
                        },
                        admin: false,
                        proposition: false,
                        editProposition: false,
                        approvals: false,
                        approvalBusiness: false,
                        approvalRejectComment: false,
                        myListings: false,
                        myListing: false,
                        payListing: false,
                        editMyListing: false,
                        payMyListing: false,
                        myListingsComments: false,
                        myListingsTab: "",
                        manageListings: false,
                        manageListing: false,
                        mergeListings: false,
                        listingCallLog: false,
                        listingCallLogComment: false,
                        listingCallWhatsApp: false,
                        selectMergeListing: false,
                        downgradedListings: false,
                        shareBusinessListing: false,
                        paymentLogs: false,
                        navTab: "details",
                        navTabIndex: 0,
                        navTabs: ["details", "services", "logo", "channels"],
                        navTabRegistration: "details",
                        engagementStats: false,
                    },
                    screen: {
                        mainTopBottom: true,
                        commentsFullScreen: false,
                    },
                    groups: {
                        list: [],
                    },
                    members: {
                        list: [],
                        member: {},
                        memberFilter: "",
                        blacklisted: [],
                    },

                    newListing: {
                        id: 0,
                        name: "",
                        code: "27",
                        contact_number: "",
                        description: "",
                        location: "",
                        comment: "",
                        stars: 0,
                        flag: "",
                        clear: function() {
                            this.id = 0;
                            this.name = "";
                            this.code = "27";
                            this.contact_number = "";
                            this.description = "";
                            this.location = "";
                            this.comment = "";
                            this.stars = 0;
                            this.flag = "";
                        }
                    },

                    paidListing: {
                        id: 0,
                        source: "",
                        business_name: "",
                        vat_number: "",
                        registration_number: "",
                        country_code: "+27",
                        contact_number: "",
                        office_number: "",
                        email: "",
                        maskMobile: "",
                        maskEmail: "",
                        description: "",
                        logo: "",
                        person_name: "",
                        person_surname: "",
                        whatsapp: "",
                        google_url: "",
                        websiote_url: "",
                        facebok_url: "",
                        x_url: "",
                        instagram_url: "",
                        show_other: false,
                        uuid: "",
                        search: {
                            name: "",
                            mobile: "",
                            results: [],
                            _2fa: "",
                            action: "name",
                            showSearchMobile: false
                        },
                        clear: function(){
                            this.id = 0;
                            this.source = "";
                            this.business_name = "";
                            this.contact_number = "";
                            this.office_number = "";
                            this.email = "";
                            this.description = "";
                            this.person_name = "";
                            this.person_surname = "";
                            this.uuid = "";
                        }
                    },

                    listings: {
                        all: [],
                        list: [],
                        business: {},
                        favorites: [],
                        categories: [],
                        comments: [],
                        myListings: [],
                        allAdmin: [],
                        allAdminAll: [],
                        mainFilter: "",
                        quoteComment: "",
                        sort: {
                            sort: false,
                            nameAscending: false,
                            dateAscending: false,
                            ratingAscending: false,
                        },
                        statuses: [],
                    },

                    comments: {
                        list: [],
                        action: "",
                        comment: {},
                        content: {
                            comment: "",
                            stars: 0,
                            clear: function() {
                                this.comment = "";
                                this.stars = 0;
                            }
                        },
                        mainFilter: "",
                        ascending: true,
                        alreadyCommented: false
                    },
                    filter: {
                        condition: "",
                        results: [],
                        filters: [],
                        ratings: false,
                        comments: false,
                        stars: [5,4,3,2,1],
                        filterResults: "",
                        zeroFilter: 0,
                    },
                    referrals: {
                        list: [],
                        all: [],
                        call_log: [],
                        call_log_item: {},
                        mainFilter: "",
                        callLogComment: ""
                    },
                    
                    proposition: {
                        id: 0,
                        title: "",
                        proposition: "",
                        editor: "",
                    },
                    quillEditor: undefined,
                    quillImages: [],
                    approvals: {
                        list: [],
                    },
                    myListings: {
                        list: [],
                        mainFilter: "",
                        logo: "",
                    },
                    stats: {
                        approvals: 0,
                        leads: 0,
                        listings: 0
                    },
                    contact: {
                        flag: "south-africa_flag-eps-round.svg"
                    },
                    mergeList: [],
                    mergeOption: "main",
                    mergeListing: undefined,
                    mergeMainListing: undefined,
                    mergeSecondListing: undefined,
                    mergeListingFilter: "",
                    shareContactNumber: "",
                    availablePaymentLogs: [],
                    paymentLogLink: undefined,
                },
                computed: {
                    getListingsList: function () {
                        var filter = this.listings.mainFilter.toLowerCase();
                        if (filter) {
                            return this.listings.list.filter(item => {
                                return item.name.toLowerCase().includes(filter) || item.description.toLowerCase().includes(filter);
                            });
                        } else {
                            return this.listings.list;
                        }
                    },
                    getMyListingsList: function() {
                        var filter = this.listings.mainFilter.toLowerCase();
                        if (filter) {
                            return this.listings.myListings.filter(item => {
                                return item.name.toLowerCase().includes(filter) || item.description.toLowerCase().includes(filter);
                            });
                        } else {
                            return this.listings.myListings;
                        }
                    },
                    getCurrentListing: function (){
                        return this.listings.listing;
                    },

                    getCommentsList: function(){
                        return this.comments.list;
                    },
                    getFavoritesList: function (){
                        return this.traders.favorites;
                    },
                    getBlacklistedList: function (){
                        return this.members.blacklisted;
                    },
                    getFilterCondition: function(){
                        return this.filter.condition;
                    },
                    getReferralsAllList: function(){
                        return this.referrals.all;
                    },
                    getReferralsCallLogList: function(){
                        return this.referrals.call_log;
                    },
                    getRegistrationSearchResultsList: function(){
                        return this.registration.search.results;
                    },
                    getRegistrationLogo: function() {
                        return this.registration.logo;
                    },
                    getApprovalsList: function() {
                        return this.approvals.list;
                    },
                    getMyListingsList: function() {
                        return this.myListings.list;
                    },
                    getAlreadyCommented: function() {
                        // return this.comments.alreadyCommented;
                        if (this.comments.list.length == 0) {
                            return false;
                        }
                        var found = false;
                        for(var i = 0; i < this.comments.list.length; i++) {
                            if (this.isContentCreator(this.comments.list[i])) {
                                found = true; 
                                break;
                            }
                        }
                        return found;
                    }
                    
                },
                methods: Object.assign({}, 
                    utils, 
                    screen,
                    setup, 
                    members,
                    filters,
                    listings, 
                    // comments_methods,
                    // favorites_methods, 
                    // members_methods, 
                    // filter_methods,
                    // referrals_methods,
                    // registration_methods,
                    // proposition_methods,
                    // approvals_methods,
                    // manage_listings_methods,
                    // // analytics_methods,
                    // admin_methods,
                )
            });

            fusion["componentsInstance"]["recruitment_directory<?=$unique_key?>"] = fusion["recruitment_directory<?=$unique_key?>"];
            fusion["componentsInstance"]["recruitment_directory<?=$unique_key?>"]["refresh"] = function () {
                if(fusion["recruitment_directory<?=$unique_key?>"]['_this'] != undefined) {
                    app.aGetAllOnMounted(false);
                }
            }

        })(fusion, $);
    </script>
</body>
</html>



var stateProperties = {

    quillEditor: undefined,
    quillImages: [],

    urls: {
        root: `${host}/api/`,
        main: `${host}/modules/module_dev/recruitment_directory/v1/`,
        pic: `${host}/modules/member_profile/`,
        thumb: `${host}/scripts/mthumb/mthumb.php?src=`,
        member: `${host}/modules/members-management-v2/api`,
        analytics: `${host}/modules/module_dev/module_analytics/`,
    },

    permissions: {
        admin: false,
        visitor: false,
        supplier: false,
        add: false,
        update: false,
        delete: false,
    },

    settings: {
        user: undefined,
        module: "",
        name: "",
        icon: "",
        community_id: "",
        content_id: "",
        unique_key: "",
        user_key_id: "",
        unique_key_id: "",
        env: config.env,
        platform: fusion.app.toLowerCase(),
        is_registration: false,
        
    },

    loading: {
        listings: false,
        members: false,
        blacklisted: false,
        admin: false,
        free: false,
        approvals: false,
        downgraded: false,
        merge: false,
        favorites: false,
        vouchers: false
    },

    screen: {
        top: 0
    },

    members: {
        list: [],
        member: {},
        blacklisted: []
    },

    groups: {
        list: [],
    },

    listing: {},
    listings: {
        all: [],
        list: [],
        mine: [],
        free: [],
        admin: [],
        call_log: [],
        approvals: [],
        downgraded: [],
        merge: [],
        favorites: []
    },

    filters: {
        main: "",
        all: "",
        listings: "",
        comments: "",
        condition: "",
        members: "",
        admin: "",
        free: "",
        approvals: "",
        downgraded: "",
        merge: "",
        favorites: "",
        vouchers: ""
    },

    sort: {
        sort: false,
        nameAscending: false,
        dateAscending: false,
        ratingAscending: false,
    },

    stats: {
        approvals: 0,
        leads: 0,
        listings: 0
    },

    contact: {
        flag: "south-africa_flag-eps-round.svg"
    },

    pages: {
        depth: 0,
        page: "",
        main: true,
        mainFilter: false,
        adminFilter: false,
        viewListings: false,
        addFreeListing: false,
        viewFreeListing: false,
        editFreeListing: false,
        addPaidListing: false,
        viewPaidListing: false,
        editPaidListing: false,
        listingPayment: false,
        listingSelectGender: false,
        addPaidListingSearch: false,
        addPaidListingSearchResults: false,
        addPaidListing2FA: false,
        addPaidListingDetails: false,
        navTabRegistration: false,
        previewAddListing: false,
        makeListingPayment: false,
        shareListing: false,
        
        listComments: false,
        addComment: false,
        editComment: false,
        commentContact: false,

        adminMenu: false,
        listMembers: false,
        viewMemberContact: false,
        proposition: false,
        editProposition: false,

        adminListings: false,
        adminListingItem: false,

        adminFreeListings: false,
        adminFreeListingCallLog: false,
        adminFreeListingCallLogComment: false,

        adminApprovals: false,
        adminApprovalsItem: false,
        adminApprovalRejectComment: false,

        adminDowngradedListings: false,
        adminDowngradedListingItem: false,

        adminMergeListings: false,
        adminSelectMergeListing: false,

        paymentLogs: false,

        listVouchers: false,
        viewVoucher: false,
        addVoucher: false,
        editVoucher: false,
        listVoucherClaims: false,
        voucherPayment: false,
        selectPeriodType: false,
        viewVoucherClaim: false,
        moduleConfig: false,

        upgradeOptions: false,
        upgradeListing: false,
    },

    newListing: {
        id: 0,

        name: "",
        contact_code: "",
        contact_number: "",
        office_number: "",
        email: "",
        dob: "",
        gender: "",

        description: "",
        location: "",
        cv_path: "",
        logo: "",
        
        comment: "",
        stars: 0,
        flag: "",

        google_url: "",
        website_url: "",
        facebook_url: "",
        x_url: "",
        instagram_url: "",
        show_other: false,
        source: "",
        
        maskMobile: "",
        search: {
            name: "",
            mobile: "",
            results: [],
            _2fa: "",
            action: "name",
            showSearchMobile: false
        },
        clear: function() {
            this.id = 0;
            this.name = "";
            this.contact_code = "";
            this.contact_number = "";
            this.description = "";
            this.location = "";
            this.comment = "";
            this.stars = 0;
            this.flag = "";
        }
    },

    listingFiles: {
        action: ""
    },

    detailsTabs: {
        index: 0 ,
        tab: "details",
        tabs: ["details", "services", "logo", "channels"],
    },

    comments: {
        list: [],
        action: "",
        filter: "",
        comment: {},
        ascending: true,
        alreadyCommented: false,
        create: {
            comment: "",
            stars: 0,
            clear: function() {
                this.comment = "";
                this.stars = 0;
            }
        },
    },

    adminDashboardStats: {},
    availablePaymentLogs: [],

    proposition: {
        id: 0,
        title: "",
        content: "",
        editor: "",
    },

    callLogItem: {},
    callLogItemComment: "",

    merge: {
        option: "main",
        main: undefined,
        second: undefined,
    },

    vouchers: {
        list: [],
        claims: [],
        voucher: {},
    },
    
    newVoucher: {
        id: 0,
        code: "",
        capacity: "",
        period_type: "Months",
        period_length: "",
        description: "",
        start_date: "",
        end_date: "",
        searchCode: "",
        codeFound: false,
        action: '',
        clear: function() {
            this.id = 0;
            this.code = "";
            this.capacity = "";
            this.period_type = "Months";
            this.period_length = "";
            this.description = "";
            this.start_date = "";
            this.end_date = "";
            this.searchCode = "";
            this.codeFound = false;
            this.action = '';
        }
    },

    moduleConfig: {
        app_title: '',
        base44_specials_url: ''
    },

    upgradeOptions: {
        type: "",
        start_date: "",
        period_length: "",
        amount: "",
        description: "Admin Allocation",
        clear: function() {
            this.type = "";
            this.start_date = "";
            this.period_length = "";
            this.amount = "";
            this.description = "Admin Allocation";
        }
    }

};

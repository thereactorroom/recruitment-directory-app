
var screen = Object.freeze({

    toggleCommentFullScreen: function(){
        this.screen.commentsFullScreen = !this.screen.commentsFullScreen;
    },

    toggleMainTopBottom: function () {
        this.screen.mainTopBottom = !this.screen.mainTopBottom;
        if (this.screen.mainTopBottom) {
            this.scrollToTop();
        } else {
            this.scrollToBottom();
        }
    }

    // toggleMainFullScreen: function () {
    //     this.screen.isFullScreen = !this.screen.isFullScreen;
    //     if (this.screen.isFullScreen) {
    //         if (s_orientation == "portrait") {
    //             $(".nav").hide();
    //         }
    //         this.screen.likeFullScreen = true;
    //         this.screen.commentFullScreen = true;
    //         $(`.main-page-${this.unique_key}`).addClass("page");
    //         $(`.main-contents-${this.unique_key}`).addClass("noticeboard-items-full-height");
    //         $(`.main-contents-${this.unique_key}`).removeClass("noticeboard-items-mini-height");
    //     } else {
    //         if (s_orientation == "portrait") {
    //             $(".nav").show();
    //         }
    //         this.screen.likeFullScreen = false;
    //         this.screen.commentFullScreen = false;
    //         $(`.main-page-${this.unique_key}`).removeClass("page");
    //         $(`.main-contents-${this.unique_key}`).removeClass("noticeboard-items-full-height");
    //         $(`.main-contents-${this.unique_key}`).addClass("noticeboard-items-mini-height");
    //     }
    // },
    // toggleLikeFullScreen: function(){
    //     this.screen.likeFullScreen = !this.screen.likeFullScreen;
    // },
    // updateScrollHeight: function(){
    //     if (this.isFullScreen) {
    //         $(".noticeboard-items").addClass("noticeboard-items-fullheight");
    //         $(".noticeboard-items").removeClass("noticeboard-items-miniheight");
    //     } else {
    //         $(".noticeboard-items").addClass("noticeboard-items-miniheight");
    //         $(".noticeboard-items").removeClass("noticeboard-items-fullheight");
    //     }
    // },
});

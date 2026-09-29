
var commentsMethods = Object.freeze({

    getComments: function (listing_id) {
        _this = this;
        $.get(this.urls.main + "comments/list", {
            user_key_id: this.settings.user_key_id,
            unique_key_id: this.settings.unique_key_id,
            listing_id: listing_id
        }, function (response) {
            if (response.status == true) {
                _this.comments.list = response.data;
                _this.comments.alreadyCommented = false;
                for (var i = 0; i < _this.comments.list.length; i++) {
                    if (_this.isContentCreator(_this.comments.list[i])) {
                        _this.comments.alreadyCommented = true;
                        _this.comments.comment = _this.comments.list[i];
                    }
                }
            }
        }, 'json')
        .fail(function(response) {
            _this.showMessage(response.error);
        });
    },

    aGetComments: async function (listing_id) {
        const response = await $.get(this.urls.main + "comments/list", {
            user_key_id: this.settings.user_key_id,
            unique_key_id: this.settings.unique_key_id,
            listing_id: listing_id
        }, 'json');
        var results = await response;
        if (results.status == true && results.data) {
            this.comments.list = results.data;
            for (var i = 0; i < _this.comments.list.length; i++) {
                this.comments.alreadyCommented = false;
                if (this.isContentCreator(this.comments.list[i])) {
                    this.comments.alreadyCommented = true;
                    this.comments.comment = this.comments.list[i];
                }
            }
        }
    },

    addComment: function () {
        _this = this;
        if (this.comments.create.stars == 0) {
            this.showMessage('Rating is required.', [{
                text: "Ok",
                onTap: function () { }
            }]);
        } else {
            _this.showLoader("Adding comment");
            $.post(this.urls.main + "comments/insert", {
                user_key_id: this.settings.user_key_id,
                unique_key_id: this.settings.unique_key_id,
                listing_id: this.listing.id,
                stars: this.comments.create.stars,
                comment: this.comments.create.comment
            }, async function (response) {
                _this.hideLoader();
                if (response.status == true) {
                    _this.listing = response.data;
                    _this.clearRatingStars(_this.comments.create.stars);
                    await _this.aGetListings();
                    _this.updateAllListings();
                    _this.getComments(_this.listing.id);
                    _this.closeAddComment();
                } else {
                    _this.showMessage(response.message);
                }
            }, 'json')
            .fail(function (response) {
                _this.showMessage(response.responseText);
            });
        }
    },

    updateComment: function () {
        _this = this;
        if (this.getComment.stars == 0) {
            this.showMessage('Ratings is required.', [{
                text: "Ok",
                onTap: function () { }
            }]);
        } else {
            this.showLoader("Updating comment");
            $.post(this.urls.main + "comments/update", {
                user_key_id: this.settings.user_key_id,
                unique_key_id: this.settings.unique_key_id,
                listing_id: this.listing.id,
                id: this.getComment.id,
                stars: this.getComment.stars,
                comment: this.getComment.comment
            }, async function (response) {
                _this.hideLoader();
                if (response.status == true) {
                    _this.listing = response.data;
                    _this.clearRatingStars(_this.getComment.stars);
                    await _this.aGetListings();
                    _this.updateAllListings();
                    _this.getComments(_this.listing.id);
                    _this.closeEditComment();
                } else {
                    _this.showMessage(response.message);
                }
            }, 'json')
            .fail(function (response) {
                _this.showMessage(response.responseText);
            });
        }
    },

    deleteComment: function () {
        _this = this;
        this.showMessage(
            `Would you like to remove this comment? <br/><br/> This action cannot be undone!`,
            [{
                text: "Yes",
                onTap: function () {
                    _this.showLoader("Removing comment");
                    $.post(_this.urls.main + "comments/delete", {
                        user_key_id: _this.settings.user_key_id,
                        unique_key_id: _this.settings.unique_key_id,
                        listing_id: _this.listing.id,
                        id: _this.comments.comment.id,
                    }, async function (response) {
                        _this.hideLoader();
                        if (response.status == true) {
                            _this.comments.content.clear();
                            await _this.aGetApprovedBusinesses();
                            _this.updateAllBusinesses();
                            _this.getComments(_this.listing.id);
                            _this.closeEditComment();
                            _this.closeCommentContact();
                        } else {
                            _this.showMessage(response.message);
                        }
                    }, 'json')
                    .fail(function (response) {
                        _this.showMessage(response.responseText);
                    });
                }
            }, {
                text: "No",
                onTap: function() {
                    _this.closeEditComment();
                }
            }]
        ); 
    },


    selectRatingStars: function (stars, action) {
        if (stars == 1) {
            if ($(`#${action}_rec_star_1_${this.settings.unique_key}`).hasClass('f-gold-star-icon')) {
                this.newListing.stars = 0;
                this.getComment.stars = 0;
                this.comments.create.stars = 0;
                $(`#${action}_rec_star_1_${this.settings.unique_key}`).addClass('f-grey-star-icon');
                $(`#${action}_rec_star_1_${this.settings.unique_key}`).removeClass('f-gold-star-icon');
            } else {
                this.newListing.stars = 1;
                this.getComment.stars = 1;
                this.comments.create.stars = 1;
                $(`#${action}_rec_star_1_${this.settings.unique_key}`).addClass('f-gold-star-icon');
                $(`#${action}_rec_star_1_${this.settings.unique_key}`).removeClass('f-grey-star-icon');
            }
        } else {
            this.newListing.stars = stars;
            this.getComment.stars = stars;
            this.comments.create.stars = stars;
            for (var i = 1; i <= stars; i++) {
                $(`#${action}_rec_star_${i}_${this.settings.unique_key}`).addClass('f-gold-star-icon');
                $(`#${action}_rec_star_${i}_${this.settings.unique_key}`).removeClass('f-grey-star-icon');
            }
        }
        for (var i = stars + 1; i <= 5; i++) {
            $(`#${action}_rec_star_${i}_${this.settings.unique_key}`).removeClass('f-gold-star-icon');
            $(`#${action}_rec_star_${i}_${this.settings.unique_key}`).addClass('f-grey-star-icon');
        }
    },

    clearRatingStars: function (stars, action) {
        for (var i = 1; i <= stars; i++) {
            $(`#${action}_rec_star_${i}_${this.settings.unique_key}`).removeClass('f-gold-star-icon');
            $(`#${action}_rec_star_${i}_${this.settings.unique_key}`).addClass('f-grey-star-icon');
        }
    },

    displayCommentContent: function (comment, location = "") {
        return comment.comment;
    },

    displayCommentRatings: function (comment) {
        var html = '';
        if (comment.stars == 0) {
            html = `<div class="f-icon-wrap f-mt-1">Ratings: Not Rated</div>`;
        } else {
            html = `<div class="f-icon-wrap f-mt-1">Ratings: </div>`;
            for (var i = 0; i < comment.stars; i++) {
                html += `<div class="f-icon-wrap f-ml-1">
                    <div class="f-icon-icon-display f-gold-star-icon"></div>
                </div>`;
            }
        }
        return html;
    },

    searchComments: function (comment) {
        var name = comment.name.toLowerCase();
        var surname = comment.surname.toLowerCase();
        var filter = this.comments.filter.toLowerCase();
        if (`${name}`.indexOf(filter) > -1 || `${surname}`.indexOf(filter) > -1) {
            return true;
        }
        return false;
    },

    sortCommentsByStars: function () {
        this.comments.ascending = !this.comments.ascending;
        if (this.comments.ascending) { // if ascending, sort descending
            this.comments.list.sort((a, b) => b.stars - a.stars);
        } else { // else sort ascending
            this.comments.list.sort((a, b) => a.stars - b.stars);
        }
    },

    hasCommented: function () {
        for (var i = 0; i < this.comments.list.length; i++){
            var comment = this.comments.list[i];
            if (comment.unique_key_id == this.settings.unique_key_id && comment.user_key_id == this.settings.user_key_id) {
                return true;
            }
        }
        return false;
    },

    showComments: function(listing){
        this.pages.depth++;
        this.listing = listing;
        this.getComments(listing.id);
        this.openPage('listComments');
    },
    closeComments: function(){
        this.pages.depth--;
        this.comments.list = [];
        this.comments.ascending = false;
        // this.comments.alreadyCommented = false;
        this.closePage('listComments');
    },

    showAddComment: function () {
        _this = this;
        if (this.permissions.visitor) {
            this.showMessage(`You are not authorised to post ratings.<br/><br/>To gain access, accept the T&C's in the main menu ≡ (Top Right).`, [{
                text: "Dismiss",
                onTap: function () { }
            }]);
        } else if (this.isMemberBlacklisted(this.member)) {
            this.showMessage('You are not allowed to rate, please contact admin.', [{
                text: "Dismiss",
                onTap: function () { }
            }]);
        // } else if (this.isContentCreator(this.listing.id)) {
        //     this.showMessage('You are not allowed to rate your own business.', [{
        //         text: "Dismiss",
        //         onTap: function () { }
        //     }]);
        } else if (this.hasCommented()) {
            this.showMessage('You have already rated.<br/>Edit to change your rating.', [{
                text: "Dismiss",
                onTap: function () { }
            }]);
        } else {
            this.comments.action = "add";
            this.openPage('addComment');
        }
    },
    closeAddComment: function () {
        this.clearRatingStars(this.comments.content.stars);
        this.comments.action = "";
        this.comments.content.clear();
        this.closePage('addComment');
    },

    showEditComment: function () {
        console.log(this.getComment.stars);
        _this = this;
        if (this.permissions.visitor) {
            this.showMessage(`
                You are not authorised to post ratings.<br/><br/>To gain access, accept the T&C's in the main menu ≡ (Top Right).`, [
                {
                    text: "Dismiss",
                    onTap: function () { }
                }
            ]);
        } else {
            if (this.isMemberBlacklisted(this.member)) {
                this.showMessage('You are not allowed to comment, please contact admin.', [{
                    text: "Ok",
                    onTap: function () { }
                }]);
            } else {
                var isAdmin = this.permissions.admin;
                var isCreator = this.isContentCreator(this.getComment);

                if (isAdmin && isCreator) {
                    console.log("admin & creator: can delete & update");
                    this.permissions.delete = true;
                    this.permissions.update = true;
                } else if (!isAdmin && isCreator) {
                    console.log("!admin & creator: can delete & update");
                    this.permissions.delete = true;
                    this.permissions.update = true;
                } else {
                    this.permissions.delete = false;
                    this.permissions.update = false;
                    return false;
                }

                // this.comments.comment = comment;
                // this.comments.content.comment = comment.comment;
                // this.comments.content.stars = comment.stars;

                this.comments.action = "edit";
                this.selectRatingStars(this.getComment.stars, 'edit');
                this.openPage('editComment');
            }
        }
    },
    closeEditComment: function () {
        this.comments.action = "";
        this.clearRatingStars(this.getComment.stars);
        this.closePage('editComment');
    },

    showCommentContact: function (comment) {
        var isAdmin = this.permissions.admin;
        var isCreator = this.isContentCreator(comment);
        
        if (isAdmin && isCreator) {
            //admin & creator: can delete & update
            this.permissions.delete = true;
        } else if (!isAdmin && isCreator) {
            //console.log("!admin & creator: can delete & update");
            this.permissions.delete = true;
        } else {
            // cannot delette
            this.permissions.delete = false;
        }
        // this.comments.comment = comment;
        this.openPage('commentContact');   
    },
    closeCommentContact: function () {
        // this.comments.comment = {}
        this.closePage('commentContact'); 
    },

    showAddCommentChain: function(type = "add") {
        this.showComments(this.listing);
        if (type == "add") {
            this.showAddComment();
        } else {
            this.showEditComment(this.getComment);
        }
    }


});



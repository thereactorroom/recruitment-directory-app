

var comments_methods = Object.freeze({

    getComments: function (business) {
        _this = this;
        $.get(this.urls.main + "comments/list", {
            user_key_id: business.user_key_id,
            unique_key_id: business.unique_key_id,
            business_id: business.id
        }, function (response) {
            if (response.status == true) {
                _this.comments.list = response.data;
                for (var i = 0; i < _this.comments.list.length; i++) {
                    if (_this.isContentCreator(_this.comments.list[i])) {
                        console.log('found my comment');
                        _this.comments.alreadyCommented = true;
                    }
                }
            }
        }, 'json')
        .fail(function(response) {
            _this.showMessage(response.error);
        });
    },

    getCommentsRecommendedTrader: function (trader) {
        _this = this;
        $.get(this.urls.main + "comments/list_recommended", {
            user_key_id: trader.user_key_id,
            unique_key_id: trader.unique_key_id,
            trader_id: trader.id,
        }, function (response) {
            if (response.status == true) {
                _this.comments.list = response.data;
            }
        }, 'json')
        .fail(function(response) {
            _this.showMessage(response.error);
        });
    },

    getMyCommentFromComments: function () {
        for(var i = 0; i < this.comments.list.length; i++) {
            if (this.isContentCreator(this.comments.list[i])) {
                return this.comments.list[i];
            }
        }
        return null;
    },

    addComment: function () {
        _this = this;
        if (this.comments.content.stars == 0) {
            this.showMessage('Rating is required.', [{
                text: "Ok",
                onTap: function () { }
            }]);
        } else {
            _this.showLoader("Adding comment");
            $.post(this.urls.main + "comments/insert", {
                user_key_id: this.user_key.id,
                unique_key_id: this.unique_key_id,
                business_id: this.businesses.business.id,
                stars: this.comments.content.stars,
                comment: this.comments.content.comment
            }, async function (response) {
                _this.hideLoader();
                if (response.status == true) {
                    _this.clearStars(_this.comments.content.stars);
                    _this.comments.content.clear();
                    await _this.aGetApprovedBusinesses();
                    _this.updateAllBusinesses();
                    _this.getComments(_this.businesses.business);
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
        if (this.comments.content.stars == 0) {
            this.showMessage('Ratings is required.', [{
                text: "Ok",
                onTap: function () { }
            }]);
        } else {
            this.showLoader("Updating comment");
            $.post(this.urls.main + "comments/update", {
                user_key_id: this.user_key.id,
                unique_key_id: this.unique_key_id,
                business_id: this.businesses.business.id,
                id: this.comments.comment.id,
                stars: this.comments.content.stars,
                comment: this.comments.content.comment
            }, async function (response) {
                _this.hideLoader();
                if (response.status == true) {
                    _this.clearStars(_this.comments.content.stars);
                    _this.comments.content.clear();
                    await _this.aGetApprovedBusinesses();
                    _this.updateAllBusinesses();
                    _this.getComments(_this.businesses.business);
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
                        user_key_id: _this.user_key.id,
                        unique_key_id: _this.unique_key_id,
                        business_id: _this.businesses.business.id,
                        id: _this.comments.comment.id,
                    }, async function (response) {
                        _this.hideLoader();
                        if (response.status == true) {
                            _this.comments.content.clear();
                            await _this.aGetApprovedBusinesses();
                            _this.updateAllBusinesses();
                            _this.getComments(_this.businesses.business);
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

    selectStars: function (stars) {
        console.log('stars:', stars);
        var action = this.comments.action;
        if (stars == 1) {
            if ($(`#${action}_star_1`).hasClass('f-gold-star-icon')) {
                this.comments.content.stars = 0;
                $(`#${action}_star_1`).addClass('f-grey-star-icon');
                $(`#${action}_star_1`).removeClass('f-gold-star-icon');
            } else {
                this.comments.content.stars = 1;
                $(`#${action}_star_1`).addClass('f-gold-star-icon');
                $(`#${action}_star_1`).removeClass('f-grey-star-icon');
            }
        } else {
            this.comments.content.stars = stars;
            for (var i = 1; i <= stars; i++) {
                $(`#${action}_star_${i}`).addClass('f-gold-star-icon');
                $(`#${action}_star_${i}`).removeClass('f-grey-star-icon');
            }
        }
        for (var i = stars + 1; i <= 5; i++) {
            $(`#${action}_star_${i}`).removeClass('f-gold-star-icon');
            $(`#${action}_star_${i}`).addClass('f-grey-star-icon');
        }
    },

    clearStars: function (stars) {
        var action = this.comments.action;
        for (var i = 1; i <= stars; i++) {
            $(`#${action}_star_${i}`).removeClass('f-gold-star-icon');
            $(`#${action}_star_${i}`).addClass('f-grey-star-icon');
        }
    },

    displayCommentContent: function (comment, location = "") {
        // if (this.isContentCreator(comment)) {
        //     if (this.isEmpty(comment.comment)) {
        //         return `<span style="color: #9d9fa2; font-style: italic;">No Comment</span> <span class="listing-listing-body-action-txt text-edit-${this.unique_key}">edit</span>`;
        //     } else {
        //         return `${comment.comment} <span class="listing-listing-body-action-txt text-edit-${this.unique_key}">edit</span>`;
        //     }
        // }
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
        var filter = this.comments.mainFilter.toLowerCase();
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
            if (comment.unique_key_id == this.unique_key_id && comment.user_key_id == this.user_key.id) {
                return true;
            }
        }
        return false;
    },

    showComments: function(business){
        this.pages.depth++;
        this.businesses.business = business;
        this.getComments(business);
        this.openPage('comments');
    },
    closeComments: function(){
        this.pages.depth--;
        this.comments.list = [];
        this.comments.ascending = false;
        this.comments.alreadyCommented = false;
        this.closePage('comments');
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
        // } else if (this.isContentCreator(this.businesses.business)) {
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
        this.clearStars(this.comments.content.stars);
        this.comments.action = "";
        this.comments.content.clear();
        this.closePage('addComment');
    },

    showEditComment: function (comment) {
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
            if (this.isMemberBlacklisted(_this.member)) {
                this.showMessage('You are not allowed to comment, please contact admin.', [{
                    text: "Ok",
                    onTap: function () { }
                }]);
            } else {
                var isAdmin = this.permissions.admin;
                var isCreator = this.isContentCreator(comment);

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

                this.comments.action = "edit";
                this.comments.comment = comment;
                this.comments.content.comment = comment.comment;
                this.comments.content.stars = comment.stars;
                this.selectStars(comment.stars);
                
                this.openPage('editComment');
            }
        }
    },
    closeEditComment: function () {
        this.clearStars(this.comments.comment.stars);
        this.comments.action = "";
        this.comments.comment = {};
        this.comments.content.clear();
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
        this.comments.comment = comment;
        this.openPage('commentContact');   
    },
    closeCommentContact: function () {
        this.comments.comment = {}
        this.closePage('commentContact'); 
    },

    showAddCommentChain: function(type = "add") {
        this.showComments(this.businesses.business);
        if (type == "add") {
            this.showAddComment();
        } else {
            this.showEditComment(this.getMyCommentFromComments());
        }
    }
    
});





var membersMethods = Object.freeze({

    /**
     * getMember: return a member
     */
    getMember: function () {
        _this = this;
        $.get(this.urls.member, {
            action: "getMember",
            userId: this.settings.user.userId,
            communityId: this.settings.community_id,
        }, function (response) {
            if (response.result == true) {
                _this.member = response.member;
                if (!_this.isEmpty(_this.member.picture)) {
                    _this.member.picture = _this.urls.pic + _this.member.picture;
                }
                if (_this.member.userId == "-1") {
                    _this.permissions.guest = true;
                }
                for (var i = 0; i < _this.member.groups.length; i++) {
                    if (_this.member.groups[i].name == "Admin") {
                        _this.permissions.admin = true;
                    }
                    if (_this.member.groups[i].name == "Visitor") {
                        _this.permissions.visitor = true;
                    }
                    if (_this.member.groups[i].name == "Guest") {
                        _this.permissions.guest = true;
                    }
                }
            }
        }, 'json')
        .fail(function (response) {
            _this.showMessage(response.error);
        });
    },

    /**
     * getMembers: return a list of members
     */
    getMembers: function () {
        _this = this;
        $.get(this.urls.member, {
            action: "getMembers",
            userId: this.settings.user.userId,
            communityId: this.settings.community_id,
        }, function (response) {
            if (response.result == true) {
                for (var i = 0; i < response.members.length; i++) {
                    var member = response.members[i];
                    if (!_this.isEmpty(member.picture)) {
                        member.picture = _this.urls.pic + member.picture;
                    }
                    _this.members.list.push(member);
                };
            }
        }, 'json')
        .fail(function (response) {
            _this.showMessage(response.error);
        });
    },

    /**
     * getCommunityGroups: returns a list of community groups
     */
    getCommunityGroups: function () {
        _this = this;
        $.get(this.urls.member, {
            action: "getMemberGroups",
            communityId: this.settings.community_id,
        }, function (response) {
            if (response.result == true) {
                _this.groups.list = response.groups;
            }
        }, 'json')
        .fail(function (response) {
            _this.showMessage(response.error);
        });
    },

    /**
     * getBlacklisted: return a list of blacklisted members
     */
    getBlacklisted: function () {
        _this = this;
        this.loading.blacklisted = true;
        $.get(this.urls.main + "members/blacklisted", {
            user_key_id: this.settings.user_key_id,
            unique_key_id: this.settings.unique_key_id
        }, function (response) {
            _this.loading.blacklisted = false;
            if (response.status == true) {
                _this.members.blacklisted = response.data;
            }
        }, 'json')
        .fail(function(response) {
            _this.showMessage(response.error);
        });
    },

    /**
     * getGroupById: get a community group by id
     * @param {*} group_id 
     * @returns object - group
     */
    getGroupById: function (group_id) {
        return this.groups.list.filter(group => group.memberGroupId == group_id)[0];
    },

    /**
     * blacklistMember - Black list a member
     * @param {*} member 
     */
    blacklistMember: function (member, action) {
        _this = this;
        this.showLoader("Blacklisting favorites");
        $.post(this.urls.main + "members/" + action, {
            user_key_id: this.settings.user_key_id,
            unique_key_id: this.settings.unique_key_id,
            member_id: member.memberId,
        }, function (response) {
            _this.hideLoader();
            if (response.status == true) {
                _this.getBlacklisted();
            } else {
                _this.showMessage(response.message);
            }
        }, 'json')
        .fail(function (response) {
            _this.showMessage(response.responseText);
        });
    },
    
    /**
     * isMemberBlacklisted - check if a member is blacklisted
     * @param {*} member 
     */
    isMemberBlacklisted: function (member) {
        for (var i = 0; i < this.members.blacklisted.length; i++) {
            if (member.memberId == this.members.blacklisted[i].member_id) {
                return true;
            }
        }
        return false;
    },

    /**
     * searchMembers
     * Search if trader matched any of the given traders in the list
     * @param {*} trader 
     * @returns true ? false
     */
    searchMembers: function (member) {
        var str = member.name + member.surname;
        if (`${str.toLowerCase()}`.indexOf(this.filters.members.toLowerCase()) > -1) {
            return true;
        }
        return false;
    },

    /**
     * memberIsNoPicture
     * @param {*} member 
     * @returns 
     */
    memberIsNoPicture: function (member) {
        // console.log('member picture check:', member.picture);
        if (this.isEmpty(member.picture)) {
            return true;
        }
        return false;
    },

    /**
     * memberNoPicture - 
     * @param {*} member 
     * @returns 
     */
    memberNoPicture: function (member) {
        return member.name.substring(0, 1) + member.surname.substring(0, 1);
    },

    /**
     * showBusinessDirectory
     */
    // showBusinessDirectory: function () {
    //     if (this.permissions.admin) {
    //         this.pages.depth++;
    //         this.openPage('directory');
    //     }
    // },
    
    /**
     * closeBusinessDirectory
     */
    // closeBusinessDirectory: function () {
    //     this.pages.depth--;
    //     this.closePage('directory');
    // },

    /**
     * showMembers
     */
    showListMembers: function () {
        this.pages.depth++;
        this.getBlacklisted();
        this.openPage('listMembers');
    },
    
    /**
     * closeMembers
     */
    closeListMembers: function () {
        this.pages.depth--;
        this.closePage('listMembers');
    },

    /**
     * showViewMemberDetails
     * @param {*} member 
     */
    showViewMemberDetails: function (member) {
        this.pages.depth++;
        this.members.member = member;
        this.openPage('viewMemberContact');
    },

    /**
     * closeViewMemberDetails
     */
    closeViewMemberDetails: function () {
        this.pages.depth--;
        this.members.member = {};
        this.closePage('viewMemberContact');
    }


});


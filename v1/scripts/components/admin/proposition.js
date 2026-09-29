
var propositionComponent = Object.freeze({

    getProposition: function () {
        _this = this;
        this.loading.blacklisted = true;
        $.get(this.urls.main + "proposition/get", {
            user_key_id: this.settings.user_key_id,
            unique_key_id: this.settings.unique_key_id,
        }, function (response) {
            if (response.status == true && response.data) {
                _this.proposition.id = response.data.id;
                _this.proposition.title = response.data.title;
                _this.proposition.content = _this.replaceContentHost(response.data.content);
            } else {
                _this.proposition.id = 0;
                _this.proposition.title = "";
                _this.proposition.content = "";
            }
        }, 'json')
        .fail(function(response) {
            _this.showMessage(response.error);
        });
    },

    saveProposition: function() {
        _this = this;
        let error = false;
        let message = "";

        if (this.isEmpty(this.proposition.title)) {
            error = true;
            message = "Title";
        } else if (this.isEmpty(this.proposition.editor)) {
            error = true;
            message = "editor";
        }  
        
        if (error) {
            this.showMessage(`${message} is required.`, [{
                text: "Ok",
                onTap: function() {}
            }]);
        } else {
            this.showLoader("Saving Proposition");
            $.post(this.urls.main + "proposition/save", {
                user_key_id: this.settings.user_key_id,
                unique_key_id: this.settings.unique_key_id,
                id: this.proposition.id,
                title: this.proposition.title,
                content: this.proposition.editor,
                images: this.quillImages,
            }, function(response) {
                _this.hideLoader();
                if (response.status == true) {
                    _this.quillImages = [];
                    _this.getProposition();
                    _this.closeEditProposition();
                } else {
                    showMessage(response.message);
                }
            }, 'json')
            .fail(function(response) {
                showMessage(response.responseText);
            });
        }
    },

    deleteProposition: function() {
        _this = this;
        this.showLoader("Saving Proposition");
        $.post(this.urls.main + "proposition/delete", {
            id: this.proposition.id,
        }, function(response) {
            _this.hideLoader();
            if (response.status == true) {
                _this.getProposition();
                _this.closeEditProposition();
            } else {
                showMessage(response.message);
            }
        }, 'json')
        .fail(function(response) {
            showMessage(response.responseText);
        });
    },


    /**
     * 
     */

    showProposition: function() {
        this.pages.depth++;
        this.openPage('proposition');
    },
    closeProposition: function() {
        this.pages.depth--;
        this.closePage('proposition');
    },

    showEditProposition: function() {
        this.pages.depth++;
        this.openPage('editProposition');
    },
    closeEditProposition: function() {
        this.pages.depth--;
        this.closePage('editProposition');
    },

    initWiziwig: function(event) {
        if (this.quillEditor != undefined) {
            this.quillEditor = undefined;
        }
        this.quillEditor = event.editor;
    },
    onTextChange: function(event) {
        this.proposition.editor = event.contents.replaceAll("<h1><br></h1>", "");
    },
    onImageChange: function(event) {
        _this = this;
        let fileInput = event;
        if (fileInput.files != null && fileInput.files[0] != null) {
            for(let i = 0; i < fileInput.files.length; i++) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const range = _this.quillEditor.getSelection(true);
                    $.post(_this.urls.main + "images/save", {
                        image: e.target.result,
                    }, function(response) {
                        if (response.status == true) {
                            _this.quillImages.push(response.data);
                            _this.quillEditor.insertEmbed(range.index, 'image', response.data.url);
                            _this.quillEditor.setSelection(range.index + 1, 'image', Quill.sources.SILENT);
                            fileInput.value = "";
                        }
                    }, 'json');
                };
                reader.readAsDataURL(fileInput.files[i]);
            }
        }
    }

});
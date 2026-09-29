

if(!Object.keys(Vue.options.components).includes('wiziwig')) {
    Vue.component('wiziwig', {
        template: `<div :style="[style.container, $props?.options?.container?.style]" :class="[$props?.options?.container?.class]" ref="editor"></div>`,
        props: ['options'],
        mounted: function() {
            var _this = this;
            
            this.editor = new Quill(this.$refs.editor, {
                theme: 'snow', 
                placeholder: 'Type in your description',
                modules: {
                    toolbar: Object.assign([], [
                        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered' }, { 'list': 'bullet' }, { 'list': 'check' }, { indent: "-1" }, { indent: "+1" },],
                        [{ 'align': [] }],
                        ['link', 'image']
                        // ['link', 'image', 'video']
                    ], (_this.options.toolbar) ? _this.options.toolbar : []),
                    keyboard: Object.assign({}, {
                        enter: {
                            key: 13,
                            handler: function(range, ctx) {
                                this.editor.insertText(range.index, '\n');
                            }
                        }
                    }, (_this.options.keyboard) ? _this.options.keyboard : {}),
                },
            });
            
            this.editor.on('text-change', function () {
                _this.$emit('text-change', { contents: _this.editor.root.innerHTML });
            });

            this.editor.getModule('toolbar').addHandler('image', function() {
                var input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/png, image/webp, image/gif, image/jpeg');
                input.classList.add('ql-image');
                input.click();

                // Listen upload local image and save to server
                input.onchange = function() {
                    _this.$emit('image-change', input);
                };
            });

            // this.editor.getModule('toolbar').addHandler('link', function (link) {
            //     console.log('link handler');
            //     console.log(link);
            //     if (link) {
            //         var html = `
            //         <div class="ql-tooltip ql-editing ql-flip" style="left: -17.2px; top: -23.5px;" data-mode="link">
            //             <a class="ql-preview" target="_blank" href="about:blank"></a>
            //             <input type="text" data-formula="e=mc^2" data-link="https://quilljs.com" data-video="Embed URL" placeholder="https://quilljs.com">
            //             <a class="ql-action"></a><a class="ql-remove"></a
            //         </div>`;
            //         const href = prompt('Enter the URL');
            //         this.quill.format('link', href);
            //     } else {
            //         this.quill.format('link', false);
            //     }
            // });

            this.handlerMethods = (this.$props.options.methods !== undefined) ? this.$props.options.methods : true;
        
            if(this.handlerMethods) {
                this.handler()
            }
            
            this.$emit('init', this);
        },
        data: function() {
            return {
                editor: undefined,
                handlerMethods: true,
                style: {
                    container: {
                        // 'display': 'flex',
                        'width': 'calc(calc(690 / 750)* var(--seg_width))',
                        'height': '100%',
                        'margin-left': 'calc(calc(30 / 750)* var(--seg_width))',
                        'margin-right': 'calc(calc(30 / 750)* var(--seg_width))',
                    },
                }
            }
        },
        methods: {
            handler: function () {
                // 
                // $(`${this.$refs.editor} img`)
                // console.log("on handle image");
            }
        }
    });
}
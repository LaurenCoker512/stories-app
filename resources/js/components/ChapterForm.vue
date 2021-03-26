<template>
    <div role="form">
        <div class="form-group">
            <label for="name">Chapter Name (optional)</label>
            <input 
                type="text" 
                class="form-control" 
                :class="{ 'is-invalid': errors.name }"
                id="name" 
                name="name"
                placeholder="Chapter Name" 
                v-model="fields.name">
                <div class="invalid-feedback" v-if="errors.name">{{ errors.name[0] }}</div>
        </div>

        <!-- Rich Text Editor -->
        <div class="editor">
            <editor-menu-bar :editor="editor" v-slot="{ commands, isActive }">
                <div class="menubar">

                <button
                    class="menubar__button"
                    :class="{ 'is-active': isActive.bold() }"
                    @click="commands.bold"
                >
                    <img src="/img/bold.svg" alt="bold" width="15" height="15">
                </button>

                <button
                    class="menubar__button"
                    :class="{ 'is-active': isActive.italic() }"
                    @click="commands.italic"
                >
                    <img src="/img/italic.svg" alt="italic" width="15" height="15">
                </button>

                <button
                    class="menubar__button"
                    :class="{ 'is-active': isActive.strike() }"
                    @click="commands.strike"
                >
                    <img src="/img/strike.svg" alt="strike" width="15" height="15">
                </button>

                <button
                    class="menubar__button"
                    :class="{ 'is-active': isActive.underline() }"
                    @click="commands.underline"
                >
                    <img src="/img/underline.svg" alt="underline" width="15" height="15">
                </button>

                <button
                    class="menubar__button"
                    :class="{ 'is-active': isActive.code() }"
                    @click="commands.code"
                >
                    <img src="/img/code.svg" alt="code" width="15" height="15">
                </button>

                <button
                    class="menubar__button"
                    :class="{ 'is-active': isActive.paragraph() }"
                    @click="commands.paragraph"
                >
                    <img src="/img/paragraph.svg" alt="paragraph" width="15" height="15">
                </button>

                <button
                    class="menubar__button"
                    :class="{ 'is-active': isActive.heading({ level: 1 }) }"
                    @click="commands.heading({ level: 1 })"
                >
                    H1
                </button>

                <button
                    class="menubar__button"
                    :class="{ 'is-active': isActive.heading({ level: 2 }) }"
                    @click="commands.heading({ level: 2 })"
                >
                    H2
                </button>

                <button
                    class="menubar__button"
                    :class="{ 'is-active': isActive.heading({ level: 3 }) }"
                    @click="commands.heading({ level: 3 })"
                >
                    H3
                </button>

                <button
                    class="menubar__button"
                    :class="{ 'is-active': isActive.bullet_list() }"
                    @click="commands.bullet_list"
                >
                    <img src="/img/ul.svg" alt="ul" width="15" height="15">
                </button>

                <button
                    class="menubar__button"
                    :class="{ 'is-active': isActive.ordered_list() }"
                    @click="commands.ordered_list"
                >
                    <img src="/img/ol.svg" alt="ol" width="15" height="15">
                </button>

                <button
                    class="menubar__button"
                    :class="{ 'is-active': isActive.blockquote() }"
                    @click="commands.blockquote"
                >
                    <img src="/img/quote.svg" alt="quote" width="15" height="15">
                </button>

                <button
                    class="menubar__button"
                    :class="{ 'is-active': isActive.code_block() }"
                    @click="commands.code_block"
                >
                    <img src="/img/code.svg" alt="code" width="15" height="15">
                </button>

                <button
                    class="menubar__button"
                    @click="commands.horizontal_rule"
                >
                    <img src="/img/hr.svg" alt="hr" width="15" height="15">
                </button>

                <button
                    class="menubar__button"
                    @click="commands.undo"
                >
                    <img src="/img/undo.svg" alt="undo" width="15" height="15">
                </button>

                <button
                    class="menubar__button"
                    @click="commands.redo"
                >
                    <img src="/img/redo.svg" alt="redo" width="15" height="15">
                </button>

                </div>
            </editor-menu-bar>

            <editor-content class="editor__content" :editor="editor" />

        </div>

        <div class="invalid-feedback mb-3" v-if="errors.body">{{ errors.body[0] }}</div>

        <button @click="submit" class="btn btn-dark mb-2">
            <span v-if="method === 'update'">Update Chapter</span>
            <span v-else>Create Chapter</span>
        </button>
    </div>
</template>

<script>
    import { Editor, EditorContent, EditorMenuBar } from 'tiptap';
    import {
        Blockquote,
        CodeBlock,
        HardBreak,
        Heading,
        HorizontalRule,
        OrderedList,
        BulletList,
        ListItem,
        TodoItem,
        TodoList,
        Bold,
        Code,
        Italic,
        Link,
        Strike,
        Underline,
        History,
    } from 'tiptap-extensions'
    import VueSanitize from 'vue-sanitize';

    export default {
        components: {
            EditorContent,
            EditorMenuBar
        },
        props: [
            'storyId',
            'chapterId',
            'method',
            'name',
            'body',
        ],

        data() {
            return {
                fields: {
                    name: '',
                    body: ''
                },
                errors: {},
                editor: new Editor({
                    extensions: [
                        new Blockquote(),
                        new BulletList(),
                        new CodeBlock(),
                        new HardBreak(),
                        new Heading({ levels: [1, 2, 3] }),
                        new HorizontalRule(),
                        new ListItem(),
                        new OrderedList(),
                        new TodoItem(),
                        new TodoList(),
                        new Link(),
                        new Bold(),
                        new Code(),
                        new Italic(),
                        new Strike(),
                        new Underline(),
                        new History(),
                    ],
                    content: this.body ? this.$sanitize(this.body) : '<p>Click here to write your chapter!</p>',
                    onUpdate: ({ getJSON, getHTML }) => {
                        this.fields.body = getHTML();
                    }
                })
            }
        },
        mounted() {
            if (this.method === 'update') {
                this.fields.name = this.name;
                this.fields.body = this.body;
            }   
        },

        methods: {

            submit() {
                this.errors = {};
                let url = this.method === 'update' ? `/stories/${this.storyId}/chapters/${this.chapterId}` : `/stories/${this.storyId}/chapters`;

                if (this.method === 'store') {
                    window.axios.post(url, this.fields).then(res => {
                        window.location.href = res.data.redirect;
                    }).catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors || {};
                        }
                    });
                } else {
                    window.axios.patch(url, this.fields).then(res => {
                        window.location.href = res.data.redirect;
                    }).catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors || {};
                        }
                    });
                }
            },

            beforeDestroy() {
                this.editor.destroy()
            },
        }
    }
</script>

<style lang="scss" scoped>
    .invalid-feedback {
        display: block;
    }

    .button {
        font-weight: bold;
        display: inline-flex;
        background: transparent;
        border: 0;
        color: #000;
        padding: 0.2rem 0.5rem;
        margin-right: 0.2rem;
        border-radius: 3px;
        cursor: pointer;
        background-color: rgba(0, 0, 0, 0.1);

        &:hover {
            background-color: rgba(0, 0, 0, 0.15);
        }
    }

    .message {
        background-color: rgba(0, 0, 0, 0.05);
        color: rgba(0, 0, 0, 0.7);
        padding: 1rem;
        border-radius: 6px;
        margin-bottom: 1.5rem;
        font-style: italic;
    }

    .editor {
        position: relative;
        width: 100%;
        padding: 1rem;
        margin: 3rem auto 1rem auto;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        background-color: #fff;

        &__content {
            overflow-wrap: break-word;
            word-wrap: break-word;
            word-break: break-word;

            * {
            caret-color: currentColor;
            }

            pre {
            padding: 0.7rem 1rem;
            border-radius: 5px;
            background: #000;
            color: #fff;
            font-size: 0.8rem;
            overflow-x: auto;

            code {
                display: block;
            }
            }

            p code {
            padding: 0.2rem 0.4rem;
            border-radius: 5px;
            font-size: 0.8rem;
            font-weight: bold;
            background: rgba(0, 0, 0, 0.1);
            color: rgba(0, 0, 0, 0.8);
            }

            ul,
            ol {
            padding-left: 1rem;
            }

            li > p,
            li > ol,
            li > ul {
            margin: 0;
            }

            a {
            color: inherit;
            }

            blockquote {
            border-left: 3px solid rgba(0, 0, 0, 0.1);
            color: rgba(0, 0, 0, 0.8);
            padding-left: 0.8rem;
            font-style: italic;

            p {
                margin: 0;
            }
            }

            img {
            max-width: 100%;
            border-radius: 3px;
            }

            table {
            border-collapse: collapse;
            table-layout: fixed;
            width: 100%;
            margin: 0;
            overflow: hidden;

            td, th {
                min-width: 1em;
                border: 2px solid #dddddd;
                padding: 3px 5px;
                vertical-align: top;
                box-sizing: border-box;
                position: relative;
                > * {
                margin-bottom: 0;
                }
            }

            th {
                font-weight: bold;
                text-align: left;
            }

            .selectedCell:after {
                z-index: 2;
                position: absolute;
                content: "";
                left: 0; right: 0; top: 0; bottom: 0;
                background: rgba(200, 200, 255, 0.4);
                pointer-events: none;
            }

            .column-resize-handle {
                position: absolute;
                right: -2px; top: 0; bottom: 0;
                width: 4px;
                z-index: 20;
                background-color: #adf;
                pointer-events: none;
            }
            }

            .tableWrapper {
            margin: 1em 0;
            overflow-x: auto;
            }

            .resize-cursor {
            cursor: ew-resize;
            cursor: col-resize;
            }

        }
    }

    .menubar {
        margin-bottom: 1rem;
        border-bottom: 1px solid #ced4da;
        transition: visibility 0.2s 0.4s, opacity 0.2s 0.4s;

        &.is-hidden {
            visibility: hidden;
            opacity: 0;
        }

        &.is-focused {
            visibility: visible;
            opacity: 1;
            transition: visibility 0.2s, opacity 0.2s;
        }

        &__button {
            font-weight: bold;
            display: inline-flex;
            background: transparent;
            border: 0;
            color: #000;
            padding: 0.2rem 0.5rem;
            margin-right: 0.2rem;
            border-radius: 3px;
            cursor: pointer;

            &:hover {
            background-color: rgba(0, 0, 0, 0.05);
            }

            &.is-active {
            background-color: rgba(0, 0, 0, 0.1);
            }
        }

        span#{&}__button {
            font-size: 13.3333px;
        }
    }

    
</style>

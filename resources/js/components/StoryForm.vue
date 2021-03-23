<template>
    <div role="form">
        <div class="form-group">
            <label for="title">Title</label>
            <input 
                type="text" 
                class="form-control" 
                :class="{ 'is-invalid': errors.title }"
                id="title" 
                name="title"
                placeholder="Story Title" 
                v-model="fields.title">
                <div class="invalid-feedback" v-if="errors.title">{{ errors.title[0] }}</div>
        </div>
        <div class="form-group">
            <label for="description">Description/Summary</label>
            <textarea 
                class="form-control" 
                :class="{ 'is-invalid': errors.description }"
                id="description" 
                name="description"
                v-model="fields.description"
                rows="3">
            </textarea>
            <div class="invalid-feedback" v-if="errors.description">{{ errors.description[0] }}</div>
        </div>
        <div class="form-group">
            <label for="type">Story Type</label>
            <select class="form-control" id="type" name="type" v-model="fields.type">
                <option value="fiction">Fiction</option>
                <option value="nonfiction">Nonfiction</option>
                <option value="poetry">Poetry</option>
            </select>
        </div>

        <!-- Tags Multiselector -->
        <div class="form-group">
            <label class="typo__label" for="tags">Tags</label>
            <multiselect 
                v-model="fields.tags" 
                :height="300"
                id="tags" 
                label="name" 
                track-by="name" 
                tag-placeholder="Add this as new tag" 
                placeholder="Search or add a tag" 
                open-direction="bottom" 
                :options="tagOptions" 
                :multiple="true" 
                :searchable="true" 
                :loading="isLoading" 
                :internal-search="false" 
                :clear-on-select="false" 
                :close-on-select="false" 
                :preserve-search="true"
                :preselect-first="false"
                :options-limit="300" 
                :limit="3" 
                :max-height="600" 
                :show-no-results="false" 
                :hide-selected="true" 
                :taggable="true"
                @tag="addTag"
                @search-change="searchTag">
                <!-- <template slot="tag" slot-scope="{ option, remove }"><span class="custom__tag"><span>{{ option.name }}</span><span class="custom__remove" @click="remove(option)">❌</span></span></template>
                <template slot="clear" slot-scope="props"> -->
                <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="fields.tags.length &amp;&amp; !isOpen">{{ fields.tags.length }} options selected</span></template>
                <!-- <div class="multiselect__clear" v-if="selectedCountries.length" @mousedown.prevent.stop="clearAll(props.search)"></div> -->
                <span slot="noResult">Oops! No tags found. Consider changing the search query.</span>
            </multiselect>
        </div>

        <!-- Rich Text Editor -->
        <div class="editor" v-if="method === 'store'">
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

        <div class="invalid-feedback mb-3" v-if="errors.first_chapter">{{ errors.first_chapter[0] }}</div>

        <button @click="submit" class="btn btn-dark mb-2">
            <span v-if="method === 'update'">Update Story</span>
            <span v-else>Create Story</span>
        </button>
    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect';
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

    export default {
        components: {
            Multiselect,
            EditorContent,
            EditorMenuBar
        },
        props: [
            'id',
            'method',
            'title',
            'description',
            'type',
            'tags'
        ],

        data() {
            return {
                fields: {
                    title: '',
                    description: '',
                    type: 'fiction',
                    tags: []
                },
                firstChapter: '',
                errors: {},
                tagOptions: [],
                isLoading: false,
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
                    content: '<p>Click here to write your story!</p>',
                    onUpdate: ({ getJSON, getHTML }) => {
                        this.firstChapter = getHTML();
                    }
                })
            }
        },
        mounted() {
            if (this.method === 'update') {
                this.fields.title = this.title;
                this.fields.description = this.description;
                this.fields.type = this.type;
                this.fields.tags = this.tags;
            }   
        },

        methods: {
            addTag (newTag) {
                window.axios.post('/tags', {name: newTag}).then(res => {
                    this.tagOptions.push(res.data);
                    this.fields.tags.push(res.data);
                });
            },

            searchTag(tag) {
                this.isLoading = true;
                window.axios.get('/tags/search?query=' + encodeURIComponent(tag)).then(res => {
                    this.tagOptions = res.data;
                    this.isLoading = false;
                });
            },

            submit() {
                this.errors = {};
                let url = this.method === 'update' ? `/stories/${this.id}` : '/stories';

                if (this.method === 'store') {
                    this.fields.first_chapter = this.firstChapter;

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

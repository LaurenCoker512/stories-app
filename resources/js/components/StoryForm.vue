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
                v-model="fields.title"
                v-on:keyup="revalidate">
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
                rows="3"
                v-on:keyup="revalidate">
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
        <rich-text-editor 
            :original-body="firstChapter" 
            v-if="method === 'store'" 
            v-on:update="updateBody"
        ></rich-text-editor>

        <div class="invalid-feedback mb-3" v-if="errors.first_chapter">{{ errors.first_chapter[0] }}</div>

        <button @click="submit" class="btn btn-dark mb-2">
            <span v-if="method === 'update'">Update Story</span>
            <span v-else>Create Story</span>
        </button>
    </div>
</template>

<script>
import Multiselect from 'vue-multiselect';
import RichTextEditor from './RichTextEditor.vue';

    export default {
        components: {
            Multiselect,
            RichTextEditor
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
                errors: {
                    title: null,
                    description: null,
                    firstChapter: null
                },
                firstChapter: '<p>Click here to write your chapter!</p>',
                tagOptions: [],
                isLoading: false,
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
            updateBody(e) {
                this.firstChapter = e;
                this.revalidate();
            },

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

            revalidate() {
                if (this.fields.title.length >= 2 && this.fields.title.length <= 255) {
                    this.errors.title = null;
                }
                if (this.fields.description.length >= 2 && this.fields.description.length <= 1000) {
                    this.errors.description = null;
                }
                if (this.fields.first_chapter.length >= 10) {
                    this.errors.firstChapter = null;
                }
            },

            submit() {
                this.errors = {};
                let url = this.method === 'update' ? `/stories/${this.id}` : '/stories';

                if (this.method === 'store') {
                    this.fields.first_chapter = this.firstChapter;

                    if (this.fields.title.length < 2 || this.fields.title.length > 255) {
                        this.errors.title = ['Stories must have a title between 2 and 255 characters.'];
                    }
                    if (this.fields.description.length < 2 || this.fields.description.length > 1000) {
                        this.errors.description = ['Stories must have a description between 2 and 1000 characters.'];
                    }
                    if (this.fields.first_chapter.length < 10) {
                        this.errors.firstChapter = ['The first chapter must be at least 10 characters.'];
                    }

                    if (this.errors.title || this.errors.description || this.errors.firstChapter) {
                        return;
                    }

                    window.axios.post(url, this.fields).then(res => {
                        window.location.href = res.data.redirect;
                    }).catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors || {};
                        }
                    });
                } else {
                    if (this.fields.title.length < 2 || this.fields.title.length > 255) {
                        this.errors.title = ['Stories must have a title between 2 and 255 characters.'];
                    }
                    if (this.fields.description.length < 2 || this.fields.description.length > 1000) {
                        this.errors.description = ['Stories must have a description between 2 and 1000 characters.'];
                    }

                    if (this.errors.title || this.errors.description || this.errors.firstChapter) {
                        return;
                    }

                    window.axios.patch(url, this.fields).then(res => {
                        window.location.href = res.data.redirect;
                    }).catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data.errors || {};
                        }
                    });
                }
            }
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


    
</style>

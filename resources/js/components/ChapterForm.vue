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
        <rich-text-editor :original-body="fields.body" v-on:update="updateBody"></rich-text-editor>

        <div class="invalid-feedback mb-3" v-if="errors.body">{{ errors.body[0] }}</div>

        <button @click="submit" class="btn btn-dark mb-2">
            <span v-if="method === 'update'">Update Chapter</span>
            <span v-else>Create Chapter</span>
        </button>
    </div>
</template>

<script>

import RichTextEditor from './RichTextEditor.vue';

    export default {
        components: {
            RichTextEditor
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
                errors: {
                    body: null
                },
            }
        },
        mounted() {
            if (this.method === 'update') {
                this.fields.name = this.name;
                this.fields.body = this.body;
            }   
        },

        methods: {

            updateBody(e) {
                this.fields.body = e;
                this.revalidate();
            },

            revalidate() {
                if (this.fields.body.length >= 10) {
                    this.errors.body = null;
                }
            },

            submit() {
                this.errors = {};
                let url = this.method === 'update' ? `/stories/${this.storyId}/chapters/${this.chapterId}` : `/stories/${this.storyId}/chapters`;

                if (this.method === 'store') {
                    if (this.fields.body.length < 10) {
                        this.errors.body = ['The chapter must be at least 10 characters.'];
                    }

                    if (this.errors.body) {
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
                    if (this.fields.body.length < 10) {
                        this.errors.body = ['The chapter must be at least 10 characters.'];
                    }

                    if (this.errors.body) {
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

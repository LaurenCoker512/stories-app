<template>
  <div>
    <h3 v-if="comments.length > 0">Comments</h3>
            <div class="card mt-4" v-for="comment in comments" v-bind:key="comment.id">
                <div class="card-body">
                    <div 
                        class="img-circular-small d-inline-block" 
                        :style="`background-image: url(${comment.avatar});`">
                    </div> &nbsp;
                    <h5 class="card-title d-inline-block" style="transform: translateY(-12px);">{{ comment.author ? comment.author.name : 'Guest' }}</h5>
                    <p>{{ comment.posted_time }}</p>
                    <p class="card-text">{{ comment.body }}</p>
                    <button 
                        type="button" 
                        class="btn btn-dark" 
                        data-toggle="modal" 
                        :data-target="`#comment-${comment.id}`"
                        v-if="comment.can_update"
                    >Edit Comment</button>
                    <input 
                        class="btn btn-dark" 
                        type="submit" 
                        value="Delete Comment" 
                        @click="deleteComment(comment.path)"
                        v-if="comment.can_update">
                </div>
            </div>
            <infinite-loading @distance="1" @infinite="loadComments">
                <div slot="no-more"><br/>No more comments.</div>
            </infinite-loading>
            <br/>
            <!-- Edit comment modal -->
            <div 
                class="modal fade" 
                :id="`comment-${commentToUpdate.id}`" 
                tabindex="-1" 
                role="dialog" 
                aria-labelledby="exampleModalLabel" 
                aria-hidden="true"
                v-if="commentToUpdate.can_update"
            >
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div 
                            role="form"
                            class="mb-4"
                        >
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Edit Comment</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="body" class="sr-only">Comment</label>
                                    <textarea 
                                        class="form-control" 
                                        id="body" 
                                        name="body"
                                        rows="4" 
                                        required
                                        v-model="commentToUpdate.body"
                                    ></textarea>
                                </div>
                                
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-dark" @click="updateComment(commentToUpdate.id)">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</template>

<script>

    export default {
        props: [
          'story',
          'chapterNum'
        ],

        data() {
            return {
                comments: [],
                page: 1,
                commentToUpdate: {
                    id: '',
                    body: '',
                    can_update: true
                }
            }
        },
        mounted() {
            
        },

        methods: {
            loadComments($state) {
                window.axios.get(`/stories/${this.story.id}/chapters/${this.chapterNum}/comments?page=${this.page}`).then(res => {
                    if (res.data.comments.data.length) {
                        this.comments = [
                            ...this.comments,
                            ...res.data.comments.data
                        ];
                        this.page = this.page + 1;
                        $state.loaded();
                    } else {
                        $state.complete();
                    }
                });
            },

            updateComment(id) {
                let url = `/stories/${this.story.id}/chapters/${this.chapterNum}/comments/${id}`;

                window.axios.patch(url, {body: this.commentToUpdate.body}).then(res => {
                    window.location.href = res.data.redirect;
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors || {};
                    }
                });
            },

            deleteComment(url) {
                if (confirm('Are you sure you want to delete this comment?')) {
                    window.axios.delete(url).then(res => {

                    });
                }
            }
        }
    }
</script>

<template>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="width: 100%;">
        <a class="navbar-brand" href="/stories" style="font-family: 'Dancing Script', cursive; font-size: 1.5rem;">Stories</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <div class="form-inline my-2 my-lg-0">
            <input 
                class="form-control mr-sm-2" 
                type="search" 
                id="search"
                name="search"
                placeholder="Search" 
                aria-label="Search"
                v-model="searchInput"
                @keyup.enter="search">
            <button 
                class="btn btn-outline-light my-2 my-sm-0" 
                @click="search"
            >Search</button>
            </div>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item" v-if="guest">
                    <a class="nav-link" href="/login">Login</a>
                </li>
                <li class="nav-item" v-if="guest">
                    <a class="nav-link" href="/register">Create Account</a>
                </li>
                <div v-if="!guest"
                    class="img-circular-small" 
                    :style="`background-image: url(${authAvatar});`">
                </div>
                <li class="nav-item dropdown" v-if="!guest">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Welcome, {{ authName }}!
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" :href="`/dashboard/${authId}`">
                            Dashboard
                        </a>
                        <a class="dropdown-item" href="/logout" @click.prevent="logout">
                            Logout
                        </a>
                    </div>
                </li>
                <li class="nav-item dropdown" v-if="!guest">
                    <a 
                        id="navbarDropdown" 
                        class="nav-link dropdown-toggle" 
                        style="position: relative;"
                        role="button" 
                        data-toggle="dropdown" 
                        aria-haspopup="true" 
                        aria-expanded="false"
                    >
                        Notifications &nbsp; <img src="/img/bell.png" alt="notification bell." height="20" width="20">
                        <div class="notifications-count">{{ notifications.length }}</div>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" v-if="notifications.length > 0">
                        <a class="dropdown-item" href="#" v-for="notification in notifications" :key="notification.id">
                            <span v-if="notification.type === 'App\\Notifications\\StoryCreatedNotification'">
                                <a 
                                    :href="`/stories/${notification.data.story_id}/chapters/1`"
                                >{{ notification.data.author_name }} has posted a new story!</a>
                            </span>
                            <span v-if="notification.type === 'App\\Notifications\\StoryUpdatedNotification'">
                                <a 
                                    :href="`/stories/${notification.data.story_id}/chapters/${notification.data.chapter_num}`"
                                >{{ notification.data.story_title }} has been updated!</a>
                            </span>
                        </a>
                    </div>
                </li>
            </ul>
            
        </div>
    </nav>
</template>

<script>

    export default {
        props: [
            'guest',
            'authName',
            'authId',
            'authAvatar'
        ],

        data() {
            return {
                notifications: [],
                searchInput: ''
            }
        },
        mounted() {
            if (!this.guest) {
                // Send a get request to /notifications  
                axios.get('/notifications').then(res => {
                    console.log(res);
                    this.notifications = res.data;
                });
            }
        },

        methods: {
            // When a user opens the notifications dropdown, mark all notifications as read
            logout() {
                axios.post('/logout').then(res => {
                    window.location = '/stories';
                });
            },

            search() {
                window.location = `/search?query=${encodeURIComponent(this.searchInput)}`;
            },

            markAsRead() {
                axios.post('/notifications').then(res => {});
            }
        }
    }
</script>

<styles lang="scss" scoped>
    .notifications-count {
        z-index: 3;
        position: absolute;
        top: 6px;
        right: 18px;
        width: 16px;
        height: 16px;
        color: #fff;
        font-size: 12px;
        font-weight: bold;
        text-align: center;
        line-height: 1;
        padding: 3px;
        background-color: #da3225;
        border-radius: 60%;
        transition: 0.3s cubic-bezier(0, 0.24, 0.86, 1.08) all;
    }
</styles>

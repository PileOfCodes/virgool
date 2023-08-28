<template>
<div class="flex flex-col mx-20 mt-12">
    <input type="text" v-model="search" @keyup="searchItems" placeholder="جستجو.." autofocus>
    <div class="mt-16 flex">
        <div  @click="changeToPost" class="cursor-pointer"> پست ها </div>
        <div  @click="changeToUsers" class="mr-8 cursor-pointer"> کاربران </div>
        <div  @click="changeToCategories" class="mr-8 cursor-pointer"> دسته بندی ها </div>
    </div>
    <div class="mt-4">
        <div class="" v-if="'posts' == activeMode && search != ''">
            <SearchedPosts v-for="post in items.data" 
            :key="post.slug" 
            :data="post"
            :postDate="postDate"
            :category="category" >
            </SearchedPosts>
            <div class="float-right mt-8 mr-20">
                <button @click="fetchNextPosts" 
                v-if="!! items.next_page_url"
                class="py-4 px-6 shadow-md bg-sky-600 rounded-md"
                >صفحه بعد
                </button>
            </div>
        </div>
        <div v-if="'users' == activeMode && search != ''">
            <div class="shadow-md shadow-pink-200 p-2 rounded-md" v-for="user in items.data" :key="user.id"> 
                <div class="flex items-center mb-4">
                        <div class="flex basis-full">
                        <router-link class="flex items-center" :to="{name: 'user-posts', params:{username: user.username} }">
                                <img :src="user.profile_src" alt="" class="rounded-full w-12 h-12">
                                <span class="mr-4">{{user.name}}</span>
                        </router-link>
                        </div>
                    <div class="flex items-center">
                        <button @click="followUser(user)" v-if="$store.state.user.isLoggedIn && user.id != $store.state.user.user.id" :class="[user.is_followed ? 'text-black bg-sky-500' : 'text-white bg-slate-700']" class="flex flex-row mr-4 w-32 text-sm justify-center font-bold items-center py-2 rounded-full">
                            <span>{{user.is_followed ? 'دنبال میکنید' : 'دنبال کنید '}}</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="float-right mt-8 mr-20">
                <button @click="fetchNextPosts" 
                v-if="!! items.next_page_url"
                class="py-4 px-6 shadow-md bg-sky-600 rounded-md"
                >صفحه بعد
                </button>
            </div>
        </div>
        <div class="" v-if="'categories' == activeMode && search != ''">
            <div v-for="category in items.data" :key="category.slug">
                <router-link :to="{name: 'post-category', params:{slug: category.slug} }">
                    <span class="float-right shadow-md shadow-pink-200 py-2 px-4 mr-2 rounded-md">
                        {{category.title}}
                    </span>
                </router-link>
            </div>
            <div class="float-right mt-8 mr-20">
                <button @click="fetchNextPosts" 
                v-if="!! items.next_page_url"
                class="py-4 px-6 shadow-md bg-sky-600 rounded-md"
                >صفحه بعد
                </button>
            </div>
        </div>
        <div class="" v-if="'no-content' == activeMode">شما هیچ متنی را جستجو نکردید</div>
        <div class="" v-if="textErr">{{ textErr}}</div>
    </div>
</div>
</template>

<script>
import {debounce} from 'lodash'
import SearchedPosts from '../../components/NewestText.vue'
import axios from 'axios'
export default {
    name:"Index",
    components: {
        SearchedPosts: SearchedPosts
    },
    data: () => ({
        search: '',
        activeMode: null,
        items: {},
        category: {},
        postDate: null,
        textErr: null
    }),
    methods: {  
        debounce: debounce(function() {
        axios.get(`/api/search/${this.activeMode}?q=${this.search}`)
        .then(({data}) => {
            if(this.activeMode == 'posts')
            {
                this.items = data.posts
                this.postDate = data.postDate
                this.category = data.category
            } else if(this.activeMode == 'users')
            {
                this.items = data.users
            } else if(this.activeMode == 'categories')
            {
                this.items = data.categories
            }
        })
        }, 500),   
        searchItems() {
            this.debounce()
        },
        followUser(user) {
        axios.post(`/api/follow/${user.username}`)
        .then(() => {
            user.is_followed = !user.is_followed;
        })
        },
        fetchNextPosts() {
            axios.get(this.items.next_page_url)
            .then(({data}) => {
                if(this.activeMode == 'posts')
                {
                    this.items.data.push(...data.posts.data)
                    this.items.next_page_url = data.posts.next_page_url
                }
                if(this.activeMode == 'users')
                {
                    this.items.data.push(...data.users.data)
                    this.items.next_page_url = data.users.next_page_url
                }
                if(this.activeMode == 'categories')
                {
                    this.items.data.push(...data.categories.data)
                    this.items.next_page_url = data.categories.next_page_url
                }
            })
        },
        changeToPost() {
            this.$router.push(
                {name: 'search',
                params:{type: 'posts'},
                query:{q: this.search}
            }); 
        },
        changeToUsers() {
            this.$router.push(
                {name: 'search',
                params:{type: 'users'},
                query:{q: this.search}
            })
        },
        changeToCategories() {
            this.$router.push(
                {name: 'search',
                params:{type: 'categories'},
                query:{q: this.search}
            });
        }
    },
    created() {
        this.search = this.$route.query.q
        this.activeMode = this.$route.params.type
        axios.get(`/api/search/${this.activeMode}?q=${this.search}`)
        .then(({data}) => {
            if(this.activeMode == 'posts')
            {
                this.items = data.posts
                this.postDate = data.postDate
                this.category = data.category
            } else if(this.activeMode == 'users')
            {
                this.items = data.users
            } else if(this.activeMode == 'categories')
            {
                this.items = data.categories
            }
        })
    },
    mounted() {
        this.activeMode = this.$route.params.type
        if(this.activeMode != 'posts' && this.activeMode != 'users' && this.activeMode != 'categories')
        {
            this.$router.push({name: 'not-found'})
        }
        if(this.search == '')
        {
            this.activeMode = 'no-content'
        }
    }
}
</script>

<style>

</style>
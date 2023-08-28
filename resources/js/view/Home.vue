<template>
    <div class=" flex flex-wrap justify-center">
        <div class="flex mt-4" v-for="(post, index) in fposts" :key="index">
            <FrontPosts :fpost="post" :index="index" ></FrontPosts>
        </div>
    </div>
    <div class="flex flex-row mx-2 md:mx-12 lg:mx-24">
        <div class="flex flex-col">
            <div class="text-sky-600 font-bold">جدیدترین نوشته ها</div>
            <HomePosts v-for="(post,index) in AllPosts.data" 
            :key="index" 
            :category="category" 
            :postDate="postDate" 
            :data="post">
            </HomePosts>
        </div>
        <div class="flex-col justify-start mr-8 sidebar__welcome hidden lg:flex ">
            <div class="font-bold">به وبلاگ خوش آمدید </div>
            <div class="my-6 font-bold text-sm text-slate-600">
                زمان آن رسیده که شیوه نوشتن و خواندن مطالبتان را تغییر دهید.
                اگر برای آغاز دوران جدید آماده هستید به وبلاگ خوش آمدید.
            </div>
            <router-link :to="{name: 'register'}" v-if="!$store.state.user.user">
                <div class="bg-sky-600 p-3 w-48 text-white rounded-lg flex">
                    <span>میخواهم ثبت نام کنم </span>
                    <i class="material-icons mr-1">&#xe5cb;</i>
                </div>
            </router-link>
          <div class="flex flex-col justify-start my-8 sticky top-0">
            <div v-if="followingPostErr !== 'Unauthenticated.' ">
                <div class="font-bold">جدیدترین نوشته های دوستانتان</div>
                <FollowingPosts v-for="(post,index) in followingPosts" :followingPost="post" :key="index"></FollowingPosts>
            </div>
            <div class="font-bold">محبوب ترین های وبلاگ</div>
            <MostFavorite v-for="(post,index) in MostFPosts" :key="index" :post="post"></MostFavorite>
          </div>
        </div>
    </div>
    <div class="float-right mt-6 mb-12 mr-44">
        <button @click="fetchNextPosts" 
        v-if="!! AllPosts.next_page_url"
        class="py-4 px-6 shadow-md bg-sky-600 rounded-md"
        > پست های جدیدتر
        </button>
    </div>
</template>

<script>

import FrontPosts from '../components/FrontPosts.vue';
import HomePosts from '../components/NewestText.vue';
import MostFavorite from '../components/MostFavorite.vue';
import FollowingPosts from '../components/FollowingPosts.vue';

export default {
    name: "Home",
    data: () => ({
        fposts: [],
        AllPosts: {},
        followingPosts: {},
        followingPostErr: null,
        category: {},
        MostFPosts: {},
        postDate: null
    }),
    components: {
        FrontPosts,
        HomePosts : HomePosts,
        MostFavorite : MostFavorite,
        FollowingPosts : FollowingPosts
    },
    methods: {
        fetchNextPosts() {
        axios.get(this.AllPosts.next_page_url)
        .then(({data}) => {
            this.AllPosts.data.push(...data.posts.data)
            this.AllPosts.next_page_url = data.posts.next_page_url
        })
      }
    },
    created() {
        axios.get(`/api/home`)
        .then(({data}) => {
            this.AllPosts = data.posts
            this.category = data.category
            this.postDate = data.postDate
        }),

        axios.get(`/api/following-posts`)
        .then(({data}) => {
            this.followingPosts = data.posts
        }).catch(({response}) => {
            this.followingPostErr = response.data.message
        }),

        axios.get(`/api/trending-posts`)
        .then(({data}) => {
            this.MostFPosts = data.posts
        }),

        axios.get(`/api/feature-posts`)
        .then(({data}) => {
            this.fposts = data.data
        })
    }
}
</script>

<style>
.sidebar__welcome {
    width: 280px;
}
</style>

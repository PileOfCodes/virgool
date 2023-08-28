<template>
    <div class="mx-48 my-28">
        <div class="flex">
            <span class="text-4xl font-bold basis-3/4">نوشته های شما</span>
            <router-link :to="{name: 'create-post'}" class="border-sky-600 text-sm rounded-md py-3 px-5 mr-20 text-sky-600 border-2">نوشتن پست جدید</router-link>
        </div>
        <div class="flex mt-10 text-slate-500">
            <span :class="[showMyPosts == true ?'text-slate-900 border-b-2 border-slate-900 ' : ''] + 'cursor-pointer pb-2 mr-10'" @click="showMyPosts = true">
                پیش نویس ها<template v-if="drafts_count"> ({{ drafts_count }}) </template></span>
            <span :class="[showMyPosts == false ?'text-slate-900 border-b-2 border-slate-900 ' : ''] + 'cursor-pointer pb-2 mr-10'" @click="showMyPosts = false">مطالب مننتشر شده 
                <template v-if="posts_count"> ({{ posts_count }}) </template></span>
        </div>
        <hr class="text-slate-300 w-full h-4">
        <div :class="[showMyPosts ? '' : 'hidden']">
            <div class="flex flex-col mt-8" v-for="(draft, index) in drafts" :key="index">
                <router-link :to="{name: 'update-draft', params: {link: draft.link} }" class="font-bold text-2xl">{{ draft.title || 'بدون عنوان' }}</router-link>
                <div class="flex justify-start items-center mt-4">
                    <span class="text-slate-600 text-sm basis-full">آخرین ویرایش:   {{ draft.updated_at }} </span>
                    <router-link :to="{name: 'update-draft', params: {link: draft.link} }" class="material-icons cursor-pointer mr-2">
                        &#xe3c9;
                    </router-link>
                    <span class="material-icons cursor-pointer mr-8" @click="deleteModal(index, draft.link)" >&#xe872;</span>
                </div>
                <hr class="text-slate-300 w-full h-4 mt-6">
            </div>
        </div>
        <div :class="[!showMyPosts ? '' : 'hidden']">
            <div class="flex flex-col mt-8" v-for="(post, index) in posts" :key="index">
                <router-link :to="{name: 'post-show', params: {slug: post.slug} }" class="font-bold text-2xl">{{ post.title }}</router-link>
                <div class="flex justify-start items-center mt-4">
                    <span class="text-slate-600 text-sm basis-full">آخرین ویرایش:   {{ post.updated_at }} </span>
                    <router-link :to="{name: 'edit-post', params: {slug: post.slug} }" class="material-icons cursor-pointer mr-2">
                        &#xe3c9;
                    </router-link>
                    <span class="material-icons cursor-pointer mr-8" @click="deletePostModal(index, post.slug)">&#xe872;</span>
                </div>
                <hr class="text-slate-300 w-full h-4 mt-6">
            </div>
        </div>
    </div>
    <div :class="[ showDeleteModal ? 'fixed top-0 left-0 bottom-0 opacity-90 bg-black w-full h-full z-10' : 'hidden']">
        <div class="deleteModalCard text-black">
            <p class="text-center mt-6">آیا از حذف این نوشته مطمین هستید؟</p>
            <div class="absolute bottom-6 right-1/2 translate-x-1/2">
                <button class="py-1 px-4" @click="deleteDraftItem">بله</button>
                <button class="py-1 px-4 mr-4" @click="cancelDeleteModal">بستن</button>
            </div>
        </div>
    </div>
    <div :class="[ showPostDeleteModal ? 'fixed top-0 left-0 bottom-0 opacity-90 bg-black w-full h-full z-10' : 'hidden']">
        <div class="deleteModalCard text-black">
            <p class="text-center mt-6">آیا از حذف این نوشته مطمین هستید؟</p>
            <div class="absolute bottom-6 right-1/2 translate-x-1/2">
                <button class="py-1 px-4" @click="deletePostItem">بله</button>
                <button class="py-1 px-4 mr-4" @click="cancelDeletePostModal">بستن</button>
            </div>
        </div>
    </div>
</template>

<script>
import store from '../../store/index.js';
import { mapActions, mapState } from 'vuex';
    export default {
        name: "MyPosts",
        data: () => ({
            showMyPosts: true,
            showDeleteModal: false,
            deletableDraft: {},
            showPostDeleteModal: false,
            deletablePost: {}
        }),
        computed: {
            ...mapState({drafts: state => state.draft.drafts}),
            ...mapState({drafts_count: state => state.draft.drafts_count}),
            ...mapState({posts_count: state => state.post.posts_count}),
            ...mapState({posts: state => state.post.posts})
        },
        methods: {
            ...mapActions(['fetchAllDrafts','fetchAllPosts']),
            deleteModal(index, link) {
                this.showDeleteModal = true;
                this.deletableDraft = {index, link}
              
            },
            cancelDeleteModal() {
                this.showDeleteModal = false;
                this.deletableDraft = {}
              
            },
            deleteDraftItem() {
                store.dispatch('deleteDrafts', this.deletableDraft)
                this.showDeleteModal = false;
                this.deletableDraft = {};
            },
            deletePostModal(index, slug) {
                this.showPostDeleteModal = true;
                this.deletablePost = {index, slug};
            },
            cancelDeletePostModal() {
                this.showPostDeleteModal = false;
                this.deletablePost = {}
            },
            deletePostItem() {
                store.dispatch('deletePost', this.deletablePost)
                this.showPostDeleteModal = false;
                this.deletablePost = {};
            }
        },
        created() {
            this.showMyPosts = true;
            this.fetchAllDrafts();
            this.fetchAllPosts();
        }
    }
    </script>
    
<style scoped>
    .deleteModalCard {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: white;
        width: 400px;
        height: 140px;
        z-index: 100;
        border-radius: 7px;
    }
</style>
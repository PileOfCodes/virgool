<template>
    <div class="flex flex-col justify-start mx-20">
        <div class="p-5 flex flex-col justify-start mt-8 shadow-lg shadow-slate-600 rounded-md">
            <router-link :to="{name: 'user-posts', params:{username: post.user.username} }">
            <div class="flex justify-start">  
                <img class="w-12 h-12" :src="'/profiles/'+ post.user.profile">
                <div class="flex flex-col mr-4 basis-3/5 sm:basis-3/4 lg:basis-3/4">
                    <div class="font-bold">{{ post.user.name}}</div>
                    <div class="text-slate-400 mt-2 text-sm">{{postDate}} - خواندن {{post.min_read}} دقیقه</div>
                </div>
                <div class="float-left">
                    <div class=" font-bold">{{ category.title}}</div>
                </div>
            </div>
            </router-link>
            <div class=" my-8 text-3xl font-extrabold">{{ post.title}}</div>
            <router-link :to="{name: 'post-show', params:{slug: post.slug} }">
                <img class="freshTextImage " :src="'/posts/' + post.image">
            </router-link>
            <div class="text-slate-500 my-4 text-sm sm:text-lg">{{ post.description}}</div>
            <div class="flex flex-row justify-start" v-if="$store.state.user.isLoggedIn">
                <div class="flex basis-full">
                    <span class="material-icons cursor-pointer" @click="likePost">
                        {{post.is_liked ? '	&#xe87d;' : '&#xe87e;'}}
                    </span>
                    <span class="mr-3">{{post.likes_count}}</span>
                </div>
                <div class="material-icons mr-12 cursor-pointer" @click="bookmarkPost(post)" v-if="$store.state.user.isLoggedIn">
                    {{post.is_bookmarked ? '&#xe866;' : '&#xe867;'}}
                </div>
            </div>
            <div class="flex flex-row justify-start" v-else>
                <div class="flex basis-full">
                    <span class="material-icons">
                        {{post.is_liked ? '	&#xe87d;' : '&#xe87e;'}}
                    </span>
                    <span class="mr-3">{{post.likes_count}}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "NewestText",
    props: ['data','postDate','category'],
    data: () => ({
        post: null
    }),
    methods: {
        likePost() {
            this.post.is_liked = !this.post.is_liked
            const reqtype = this.post.is_liked ? 'post' : 'delete';
            axios[reqtype](`/api/likes/${this.post.slug}`)
            .then(() => {
                this.post.is_liked ? this.post.likes_count++ : this.post.likes_count--;
            })
        },
        bookmarkPost(postModel) {
            postModel.is_bookmarked = !postModel.is_bookmarked
            const reqtype = postModel.is_bookmarked ? 'post' : 'delete';
            axios[reqtype](`/api/bookmarks/${postModel.slug}`)
        },
    },
    created() {
        this.post = this.data
    }
}
</script>

<style scoped>
.freshTextImage {
    width: 720px;
    height: 340px;
}
@media screen and (max-width: 500px) {
    .freshTextImage {
        width: 420px;
        height: 250px;
    }
}
</style>

<template>
    <div class="flex flex-col justify-center items-start mx-72 mt-24" v-if="post.user">
        <div class="flex flex-row justify-center items-center">
            <img class="w-24 h-24 rounded-full" :src=" post.user.profile != null ? post.user.profile_src : '/images/person-1.jpg' ">
            <div class="flex flex-col ">
                <div class="flex flex-row items-center ">
                    <div class="pr-4 font-bold">{{ post.user.name }}</div>
                    <button @click="followUser" v-if="$store.state.user.isLoggedIn && post.user.id != $store.state.user.user.id"
                    :class="[post.user.is_followed ? 'text-black bg-sky-500' : 'text-white bg-slate-700']" 
                    class="flex flex-row mr-4 w-32 text-sm justify-center font-bold items-center py-2 rounded-full">
                        <span>{{post.user.is_followed ? 'دنبال میکنید' : 'دنبال کنید '}}</span>
                    </button>
                </div>
                <div class="text-xs text-gray-500 pr-4 mt-2">{{ post.user.bio || 'بایو' }}</div>
                <div class="text-xs pr-4 font-bold mt-2">  {{ create_date }} / خواندن {{ post.min_read }} دقیقه</div>
            </div>
        </div>
        <div class="my-6">{{ post.title }}</div>
        <img :src="'/posts/' + post.image" alt="">
        <div class="my-20 leading-10" style="text-align: justify" v-html="post.content"></div>
        <div class="flex flex-row">
            <div v-for="(category, index) in post.categories" :key="index">
                <router-link :to="{name: 'post-category', params:{slug: category.slug} }">
                    <span class="bg-gray-300 text-slate-900 p-2 text-sm font-bold ml-3 rounded-sm">{{ category.title }}</span>
                </router-link>
            </div>
        </div>
        <div class="flex flex-row justify-center items-center my-16 pb-16 border-slate-400 border-b-2 border-dotted ">
            <div class="flex flex-row items-center justify-center" v-if="$store.state.user.isLoggedIn">
                <span class="material-icons cursor-pointer" @click="likePost">
                    {{post.is_liked ? '	&#xe87d;' : '&#xe87e;'}}
                </span>
                <span class="mr-3">{{post.likes_count}}</span>
            </div>
            <div class="flex flex-row items-center justify-center" v-else>
                <span class="material-icons">
                    {{post.is_liked ? '	&#xe87d;' : '&#xe87e;'}}
                </span>
                <span class="mr-3">{{post.likes_count}}</span>
            </div>
            <div class="flex flex-row items-center justify-center">
                <span class="material-icons mr-12">&#xe0cb;</span>
                <span class="mr-3">{{post.comments_count}}</span>
            </div>
            <div class="material-icons mr-12 cursor-pointer" @click="bookmarkPost(post)" v-if="$store.state.user.isLoggedIn">
                {{post.is_bookmarked ? '&#xe866;' : '&#xe867;'}}
            </div>
            <div class="flex justify-center items-center mr-56">
                <i style="font-size:24px" class="fa cursor-pointer text-slate-500 duration-300 hover:text-black">&#xf2c6;</i>
                <i style="font-size:24px" class="fa cursor-pointer mr-12 text-slate-500 hover:text-black">&#xf099;</i>
                <button class="relative mr-4 border-2 border-slate-900 rounded-xl py-4 text-center w-52 cursor-pointer overflow-hidden" @click="doCopy" :disabled="short_link == 'کپی شد!!!'">
                    <span class="absolute bottom-1 left-8">{{ short_link }}</span>
                    <i class="material-icons bottom-1 absolute left-2">&#xe14d;</i>
                </button>
            </div>
        </div>
    </div>
    <div class="flex flex-col px-32 pt-8 pb-48">
        <span class="text-slate-700 text-xs font-bold" >شاید از این نوشته ها هم خوشتان بیاید</span>
        <div class="flex flex-row pt-8 ">
            <div class="flex flex-col pb-2 w-80 bg-white rounded-md mx-1"  v-for="(related_post, index) in related_posts" :key="index">
                <router-link :to="{name:'post-show', params:{slug: related_post.slug} }">
                    <img :src="'/posts/'+ related_post.image" class="w-80 h-36 rounded-t-md"  alt="">
                </router-link>
                <span class="text-slate-600 text-xs p-4">مطلبی دیگر در همین موضوع</span>
                <router-link class="pr-4 pb-4" :to="{name:'post-show', params:{slug: related_post.slug} }">{{ related_post.title }}</router-link>
                <div class="flex p-4">
                <router-link class="flex" :to="{name:'user-posts', params:{username: related_post.user.username} }">
                    <img :src="related_post.user.profile_src != null ? related_post.user.profile_src : '/images/person-1.jpg' " class="w-12 h-12 rounded-full" alt="">
                    <div class="flex flex-col pr-4">
                        <span class="text-blue-600 font-bold text-sm mb-2" style="font-family: IRSThin;">{{ related_post.user.name }}</span>
                        <span class="text-slate-600 text-xs ">خواندن {{ related_post.min_read }} دقیقه</span>
                    </div>
                </router-link>
                    <div class="flex flex-col">
                        <div class="material-icons mr-28 cursor-pointer" style="font-size: 30px;" v-if="$store.state.user.isLoggedIn" @click="bookmarkPost(related_post)">
                            {{related_post.is_bookmarked ? '&#xe866;' : '&#xe867;'}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <template v-if="$store.state.user.isLoggedIn">
            <div class="flex-col mr-28 mt-12">
                <div class="text-slate-800 text-sm">دیدگاه ها</div>
                <textarea v-model="comment.content" class="makeItFit bg-white resize-none mt-2 h-52 p-2 outline outline-2 outline-slate-600 rounded-md" placeholder="نظر خود را درباره این پست بنویسید"></textarea>
            </div>
            <div class="mt-2">
                <button @click="saveComment" class="bg-green-500 rounded-lg py-2 px-4 ml-44 float-left">ارسال دیدگاه</button>
                <div class="w-72 text-center mr-52 py-2 px-12 rounded-md bg-red-600 text-white" v-if="errorShow">
                    {{ errorMessage }}
                </div>
            </div>
        </template>
        <template v-else>
            <span class="mt-12 w-96 text-center py-2 px-4 rounded-md bg-red-600 text-white">برای ارسال دیدگاه باید ابتدا وارد سایت شوید</span>
        </template>
    <PostComment v-for="comment in post.parent_comments"
    :key="comment.id"
    :data="comment"

    ></PostComment>
    </div>
</template>

<script>
import PostComment from './PostComment.vue';
export default {
    name: "PostShow",
    components: {
        PostComment
    },
    data: () => ({
        post: {},
        related_posts: {},
        create_date: null,
        parentComments: null,
        short_link: null,
        makeCopy: null,
        comment: {
            content: null,
            post_id: null
        },
        errorMessage: null,
        errorShow: false,
        showBookmark: false
    }),
    methods: {
        followUser() {
            axios.post(`/api/follow/${this.post.user.username}`)
            .then(() => {
                this.post.user.is_followed = !this.post.user.is_followed;
            })
        },
        bookmarkPost(postModel) {
            postModel.is_bookmarked = !postModel.is_bookmarked
            const reqtype = postModel.is_bookmarked ? 'post' : 'delete';
            axios[reqtype](`/api/bookmarks/${postModel.slug}`)
        },
        likePost() {
            this.post.is_liked = !this.post.is_liked
            const reqtype = this.post.is_liked ? 'post' : 'delete';
            axios[reqtype](`/api/likes/${this.post.slug}`)
            .then(() => {
                this.post.is_liked ? this.post.likes_count++ : this.post.likes_count--;
            })
        },
        doCopy() {
        this.$copyText(this.short_link)
        const link = this.short_link;
        this.short_link = "کپی شد!!!";
        setTimeout(() => {
            this.short_link = link
        }, 1500);
      },
      saveComment() {
        axios.post(`/api/comments/${this.post.slug}`, this.comment)
        .then(({data}) => {
            this.comment.content = null
        })
        .catch(({response}) => {
            this.errorShow = true
            this.errorMessage = response.data.errors.content[0];
            setTimeout(() => {
                this.errorShow = false
            }, 3000);
        })
      }
    },
    created() {
        axios.get(`/api/post/${this.$route.params.slug}`)
        .then(({data}) => {
            this.post = data.post
            this.related_posts = data.related_posts
            this.create_date = data.dateTime
            this.parentComments = data.post.parent_comments
            this.comment.post_id = data.post.id
            this.short_link = 'http://127.0.0.1:8000/link/'+ data.post.short_link
            document.title = data.post.title
            Echo.channel(`comment_${data.post.id}`)
            .listen('.comment.deleted', ({comment}) => {
            const findComment = this.parentComments.find(c => c.id == comment.id)
            let index = this.parentComments.indexOf(findComment);
            this.parentComments.splice(index,1)
            this.post.comments_count--
            })
            Echo.channel(`comment_${data.post.id}`)
            .listen('CommentCreatedEvent', ({comment}) => {
                this.post.parent_comments.push(comment)
                this.post.comments_count++
                })
            })
        .catch(({response}) => {
            if(response.status == 404)
            {
                this.$router.push({name: 'not-found'})
            }
            if(response.status == 403)
            {
                this.$router.push({name: 'access-denied'})
            }
        });
    }
}
</script>

<style>
.makeItFit {
    width: 820px;
}
.putMargin {
    margin-left: 540px;
}
</style>

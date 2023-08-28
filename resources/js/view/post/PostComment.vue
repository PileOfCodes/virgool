<template>  
    <div class="flex flex-col mt-12 mr-28 w-3/4 bg-white rounded-lg">
        <div class="flex items-center my-2 mx-4 mt-2">
            <div class="flex basis-5/6">
                <img :src="data.user.profile != null ? data.user.profile_src : '/images/person-1.jpg'" alt="" class="w-16 h-16 rounded-full">
                <div class="flex flex-col justify-center items-center mr-2">
                    <span class="">{{ data.user.name }}</span>
                    <span class="text-sm text-gray-600">{{ data.show_readable_comment_date }}</span>
                </div>
            </div>
            <div class="flex mr-8" v-if="$store.state.user.isLoggedIn">
                <span class="cursor-pointer" @click="deleteComment" v-if="$store.state.user.user.id == data.user_id "><i class="material-icons">&#xe872;</i></span>
                <span class="cursor-pointer mr-4" @click="editComment" v-if="$store.state.user.user.id == data.user_id "><i class="material-icons">&#xe3c9;</i></span>
                <span class="cursor-pointer mr-4" @click="showReply = true"><i class="material-icons">&#xe15e;</i></span>
            </div>
        </div>
        <div class="mx-4 py-2">{{ updateContent }}</div>
    </div>
    <template v-if="showReply || showUpdate">
        <div class="flex-col mr-28 mt-20">
            <div class="text-slate-800 text-sm">پاسخ</div>
            <textarea v-model="reply.content" class="makeItFit bg-white resize-none mt-2 h-52 p-2 outline outline-2 outline-slate-600 rounded-md" placeholder="پاسخ خود را درباره این پست بنویسید"></textarea>
        </div>
        <div class="mt-2">
            <button @click="saveComment" class="bg-green-500 rounded-lg py-2 px-4 ml-44 float-left">ارسال پاسخ</button>
            <button @click="cancelReply" class="bg-gray-500 text-white rounded-lg py-2 px-4 ml-4 float-left">لغو</button>
            <div class="w-72 text-center mr-52 py-2 px-12 rounded-md bg-red-600 text-white" v-if="errorShow">
                {{ errorMessage }}
            </div>
        </div>
    </template>
    <div :class="takeMargin">
        <PostComment 
        v-for="comment in data.replies" 
        :key="comment.id" 
        :data="comment"
        :class="takeMargin"
        >
        </PostComment>
    </div>
</template>

<script>
export default {
    name: 'PostComment', 
    data: () => ({
        takeMargin: "mr-2",
        showReply: false,
        showUpdate: false,
        updateContent: null,
        pushComments: null,
        errorShow: false,
        errorMessage: null,
        reply: {
            content: null,
            comment_id: null
        }
    }),
    methods: {
        saveReply() {
            axios.post(`/api/replies/${this.$route.params.slug}`, this.reply)
            .then(({data}) => {
                this.reply.content = null,
                this.showReply = false,
                console.log(data)
            })
            .catch(({response}) => {
                this.errorShow = true
                if(response.data.errors.comment_id)
                {
                    this.errorMessage = "عملیات معتبر نیست"
                    setTimeout(() => {
                        this.reply.content = null,
                        this.showReply = false
                    }, 2000);
                }
                if(response.data.errors.content)
                {
                    this.errorMessage = response.data.errors.content[0];
                }
                setTimeout(() => {
                    this.errorShow = false
                }, 3000);
            })
        },
        cancelReply() {
            this.reply.content = null,
            this.showReply = false
            this.showUpdate = false
        },
        deleteComment() {
            axios.delete(`/api/comments/${this.reply.comment_id}`)
        },
        editComment() {
            this.showUpdate = true
            this.reply.content = this.updateContent
        },
        saveComment() {
            return this.showReply ? this.saveReply() : this.updateComment();
        },
        updateComment() {
            axios.patch(`/api/comments/${this.reply.comment_id}`, this.reply)
            .then(({data}) => {
                this.reply.content = null,
                this.showUpdate = false,
                this.updateContent = data.data.content
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
    updated() {
        this.reply.comment_id = this.data.id
    },
    mounted() {
        this.updateContent = this.data.content
    },
    created() {
        Echo.channel(`reply_${this.data.id}`)
        .listen('.reply.created', ({reply}) => {
        this.data.replies.push(reply)
        this.$emit('comment_created')
        })

        Echo.channel(`reply_${this.data.id}`)
        .listen('.comment.deleted', (event) => {
        const findComment = this.data.replies.find(c => c.id == event.comment.id)
        let index = this.data.replies.indexOf(findComment);
        this.data.replies.splice(index,1)
        })
    },
    props: ['data']

}
</script>

<style>
</style>
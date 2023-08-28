<template> 
    <router-link :to="{name: 'post-show', params:{slug: post.slug} }">
        <div class="flex flex-row my-4">
            <img class="w-12 h-12 rounded-full" :src="post.user.profile_src">
            <div class="flex flex-col mr-4">
                <div class="font-bold text-xs makeItEllipsis">{{post.title}}</div>
                <span class="font-bold text-sm text-slate-600 mt-2">{{post.user.name}}</span>
            </div>
        </div>
    </router-link>
</template>

<script>
export default {
    name: "FollowingPosts",
    props: ['followingPost'],
    data: () => ({
        post: {}
    }),
    created() {
        this.post = this.followingPost
    }
}
</script>

<style scoped>
.makeItEllipsis{
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    width:220px;
}
</style>

<template>
        <div v-if="index == 0 || index == 1" :class="index % 2 == 0 ? 'card__min mb-4 relative ml-4' : 'card__max mb-4 relative' ">
            <router-link :to="{name: 'post-show', params:{slug: fpost.post.slug} }">
                <img :src="'/posts/'+fpost.post.image" alt="image" class="hover:brightness-50 duration-100 brightness-75">
                <span class="absolute bottom-28 right-8 text-white text-xl ">{{ fpost.post.title }}</span> 
            </router-link>
            <router-link :to="{name: 'user-posts', params:{username: fpost.post.user.username} }">
                <div class="absolute bottom-8 right-8 flex justify-start">
                    <img :src="fpost.post.user.profile_src" class="profile__image">
                    <div class="flex flex-col text-white mr-4">
                        <span>{{ fpost.post.user.name }}</span>
                        <span>{{fpost.create_date}}</span>
                    </div>
                </div>
            </router-link>
        </div>
        <div v-if="index ==3 || index == 2" :class="index % 2 == 0 ? 'card__max mb-4 relative' : 'card__min mb-4 relative mr-4' ">
            <router-link :to="{name: 'post-show', params:{slug: fpost.post.slug} }">
                <img :src="'/posts/'+fpost.post.image" alt="image" class="hover:brightness-50 duration-100 brightness-75">
                <span class="absolute bottom-28 right-8 text-white text-xl ">{{ fpost.post.title }}</span> 
            </router-link>
            <router-link :to="{name: 'user-posts', params:{username: fpost.post.user.username} }">
                <div class="absolute bottom-8 right-8 flex justify-start">
                    <img :src="fpost.post.user.profile_src" class="profile__image">
                    <div class="flex flex-col text-white mr-4">
                        <span>{{ fpost.post.user.name }}</span>
                        <span>{{fpost.create_date}}</span>
                    </div>
                </div>
            </router-link>
        </div>
</template>

<script>
export default {
    name: "FrontPosts",
    props: {
        fpost: {
            type: Object,
            required: true
        },
        index: {
            type: Number
        }
    }
}
</script>

<style>
.card__max {
    width: 680px;
    mx-height: 260px;
}
.card__max img{
    width: 680px;
    height: 260px;
}
.card__min {
    width: 450px;
    mx-height: 260px;
}
.card__min img{
    width: 450px;
    height: 260px;
}
.profile__image {
    width: 50px !important;
    height: 50px !important;
    border-radius: 50% !important;
}
@media screen and (max-width:700px) {
    .card__max {
        width: 450px;
        mx-height: 260px;
    }
    .card__max img{
        width: 450px;
        height: 260px;
    }
}
@media screen and (min-width:700px) and (max-width: 1000px) {
    .card__max {
        width: 680px;
        mx-height: 260px;
    }
    .card__max img{
        width: 680px;
        height: 260px;
    }
    .card__min {
        display: flex;
        flex-direction: column;
        width: 680px;
        mx-height: 260px;
    }
    .card__min img{
        width: 680px;
        height: 260px;
    }
}
</style>

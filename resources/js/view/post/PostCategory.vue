<template>
  <div class="mx-48 my-12">
    <div class="flex flex-col">
        <div class="flex justify-between">
            <span>نوشته های مربوط به دسته بندی : {{category.slug}}</span>
            <span class="ml-20">تعداد نوشته های مربوطه : {{ category.posts_count}}</span>
        </div>
        <CategorizedPosts v-for="(post,index) in posts.data" :key="index" :category="category" :postDate="postDate" :data="post"></CategorizedPosts>
        <div class="float-right mt-8 mr-20">
            <button @click="fetchNextPosts" 
            v-if="!! posts.next_page_url"
            class="py-4 px-6 shadow-md bg-sky-600 rounded-md"
            >صفحه بعد
            </button>
        </div>
    </div>
    <div v-if="category.posts_count == null" class="bg-red-500 p-2 text-white mt-12 w-72">پستی برای این دسته بندی وجود ندارد !</div>
  </div>
</template>

<script>
import CategorizedPosts from '../../components/NewestText.vue'
export default {
    name: 'PostCategory',
    components: {
        CategorizedPosts : CategorizedPosts
    },
    data: () => ({
        postDate: null,
        posts: {},
        category: {}
    }),
    methods: {
        fetchNextPosts()
        {
            axios.get(this.posts.next_page_url)
            .then(({data}) => {
                this.posts.data.push(...data.posts.data)
                this.posts.next_page_url = data.posts.next_page_url
            })
        }
    },
    created() {
        axios.get(`/api/posts/category/${this.$route.params.slug}`)
        .then(({data}) => {
            this.posts = data.posts,
            this.postDate = data.postDate,
            this.category = data.category,

            document.title = data.category.title + '-وبلاگ شخصی'
        })
    }
}
</script>

<style>

</style>
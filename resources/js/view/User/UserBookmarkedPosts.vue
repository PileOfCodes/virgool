<template>
  <div class="mx-48 my-12">
    <div class="flex flex-col">
        <div class="flex justify-between">
            <span>نوشته های ذخیره شده من </span>
            <span class="ml-20">تعداد پست های ذخیره شده : {{ user.bookmarks_count}}</span>
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
    <div v-if="user.posts_count == null" class="bg-red-500 p-2 text-white mt-12 w-72">پستی برای این دسته بندی وجود ندارد !</div>
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
        user: {},
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
      axios.get(`/api/bookmarked-posts`)
      .then(({data}) => {
        this.posts = data.posts
        this.user = data.user
        this.category = data.category
        this.postDate = data.postDate
        document.title =  'نوشته های ذخیره شده من '
      })
    }
}
</script>

<style>

</style>
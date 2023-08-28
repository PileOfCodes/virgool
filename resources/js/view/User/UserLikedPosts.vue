<template>
  <div class="mx-48 my-12">
    <div class="flex flex-col">
      <div class="flex justify-between items-center">
        <div class="ml-20">  نوشته هایی که شما لایک کردید </div>
        <div class="ml-20"> تعداد لایک های مربوطه : {{user.likes_count}}</div>
      </div>
      <UserAllPosts v-for="(post,index) in posts.data" :key="index" :category="category" :postDate="postDate" :data="post"></UserAllPosts>
      <div class="float-right mt-8 mr-20">
        <button @click="fetchNextPosts" 
          v-if="!! posts.next_page_url"
          class="py-4 px-6 shadow-md bg-sky-600 rounded-md"
          >صفحه بعد
        </button>
      </div>
    </div>
    <div v-if="user.posts_count == null" class="bg-red-500 p-2 text-white mt-12 w-72">این کاربر هیچ پستی ندارد !</div>
  </div>
</template>

<script>
import UserAllPosts from '../../components/NewestText.vue'
import axios from 'axios'
export default {
    name:"UserPosts",
    components: {
      UserAllPosts : UserAllPosts
    },
    data: () => ({
      posts: {},
      user: {},
      category: {},
      postDate: null
    }),
    methods: {
      followUser() {
        axios.post(`/api/follow/${this.user.username}`)
        .then(() => {
            this.user.is_followed = !this.user.is_followed;
        })
      },
      fetchNextPosts() {
        axios.get(this.posts.next_page_url)
        .then(({data}) => {
            this.posts.data.push(...data.posts.data)
            this.posts.next_page_url = data.posts.next_page_url
        })
      }
    },
    created() {
      axios.get(`/api/liked-posts`)
      .then(({data}) => {
        console.log(data)
        this.posts = data.posts
        this.user = data.user
        this.category = data.category
        this.postDate = data.postDate
        document.title =  'نوشته های مورد پسند من '
      })
    }
}
</script>

<style>

</style>
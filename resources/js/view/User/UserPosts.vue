<template>
  <div class="mx-48 my-12">
    <div class="flex flex-col">
      <div class="flex justify-between items-center">
        <div class="flex flex-row items-center mr-20">
          <img class="w-24 h-24 rounded-full" :src=" user.profile != null ? user.profile_src : '/images/person-1.jpg' ">
          <span class="pr-4 font-bold">{{ user.name }}</span>
          <button @click="followUser" v-if="$store.state.user.isLoggedIn && user.id != $store.state.user.user.id" :class="[user.is_followed ? 'text-black bg-sky-500' : 'text-white bg-slate-700']" class="flex flex-row mr-4 w-32 text-sm justify-center font-bold items-center py-2 rounded-full">
            <span>{{user.is_followed ? 'دنبال میکنید' : 'دنبال کنید '}}</span>
          </button>
        </div>
        <div class="ml-20">تعداد نوشته های مربوطه : {{ user.posts_count}}</div>
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
      axios.get(`/api/user-posts/${this.$route.params.username}`)
      .then(({data}) => {
        this.posts = data.posts
        this.user = data.user
        this.category = data.category
        this.postDate = data.postDate
        document.title =  data.user.username + ' پست های کاربر '
      })
    }
}
</script>

<style>

</style>
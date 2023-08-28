<template>
  <p class="">کاربران</p>
  <BreadCrumbs class="mt-2" :crumbs="crumbs"></BreadCrumbs>
  <div class="flex items-center mt-6">
    <input type="text" v-model="search"
     placeholder="جستجو پست ها..." @keyup="searchItems"
    class="border-b-2 w-full border-sky-500" autofocus>
  </div>
  <div class="mt-8">
    <table>
      <tr class="text-xs md:text-sm">
        <!-- <th v-for="(header, index) in headers" :key="index">
          <td>{{header.text}}</td>
        </th> -->
        <th>تصویر پست</th>
        <th>عنوان</th>
        <th>توضیحات</th>
        <th>کاربر نویسنده</th>
        <th>تعداد کامنت ها</th>
        <th>تعداد لایک ها</th>
        <th>عملیات</th>
      </tr>
       <tr class="text-xs" v-for="(post, index) in posts.data" :key="index">
        <td><img :src="'/posts/'+post.image " alt="" class="w-12 h-12 rounded-full"></td>
        <td>{{ post.title }}</td>
        <td><div class="makeItEllipsis">{{ post.description }}</div></td>
        <td>{{ post.user.name }}</td>
        <td>{{ post.comments_count }}</td>
        <td>{{ post.likes_count }}</td>
        <td>
        <button @click="deletePost(post)">
          <i class="material-icons text-red-600">&#xe872;</i>
        </button>
        <router-link :to="{name: 'post-show', params:{slug: post.slug} }">
          <i class="material-icons text-red-600 mr-4">&#xe8f4;</i>
        </router-link>
        </td>
      </tr>
     </table> 
    <div class="flex justify-center mt-4">
        <button class="px-3 py-2 text-xs font-bold rounded-md mx-2 bg-white shadow-lg"  @click="prevPage" v-if="!! prevPageUrl"> &lt; قبلی</button>
        <button class="px-3 py-2 text-xs font-bold rounded-md mx-2 bg-white shadow-lg" 
        @click="nextPage" v-if="!! nextPageUrl">بعدی &gt;</button>
    </div>
  </div> 
</template>

<script>
import axios from 'axios';
import BreadCrumbs from '../BreadCrumbs.vue';
import {debounce} from 'lodash';
export default {
    name: "PostIndex",
    data: () => ({
      page: 0,
      prevPageUrl: null,
      nextPageUrl: null,
      search: null,
      crumbs: [
        {
        title: ' داشبورد',
        to: {name: 'admin-dashboard'},
        divider: ' / '
      },
        {
        title: '  پست ها ',
        to: '',
        divider: ''
      }
    ],
    posts: [],
    headers: []
    }),
    methods: {
      deletePost(post) {
        let findPost = this.posts.data.find(p => p.id == post.id)
        let index = this.posts.data.indexOf(findPost);
        this.posts.data.splice(index,1)
        axios.delete(`/api/admin/posts/${post.id}`)
      },
      nextPage() {
        axios.get(this.nextPageUrl)
        .then(({data}) => {
            this.posts.data.push(...data.posts.data)
            this.prevPageUrl = data.posts.prev_page_url
            this.posts.data = data.posts.data
            this.nextPageUrl = data.posts.next_page_url
        })
      },
      prevPage() {
        axios.get(this.prevPageUrl)
        .then(({data}) => {
            this.posts.data.push(...data.posts.data)
            this.nextPageUrl = data.posts.next_page_url
            this.posts.data = data.posts.data
            this.prevPageUrl = data.posts.prev_page_url
        })
      },
      searchItems() {
        this.debounce()
      },
      debounce: debounce(function() {
        axios.get(`/api/admin/posts?search=${this.search}`)
        .then(({data}) => {
        this.posts = data.posts
        this.headers = data.headers
        this.nextPageUrl = data.posts.next_page_url
        this.prevPageUrl = data.posts.prev_page_url
      })
      }, 500)
    },
    created() {
      axios.get(`/api/admin/posts`)
      .then(({data}) => {
        this.posts = data.posts
        this.headers = data.headers
        this.nextPageUrl = data.posts.next_page_url
        this.prevPageUrl = data.posts.prev_page_url
      })
    },
    components: {
      BreadCrumbs,
    }
}
</script>

<style scoped>
  table {
    border-collapse: collapse;
  }
  td {
    font-weight: 700;
    text-align: right;
  }
  tr {
    border-bottom: 1px solid rgba(0,0,0,0.2);
  }
  th, td {
    padding: 10px 5px;
  }
  @media screen and (min-width: 550px) and (max-width: 650px)
  {
    th, td{
    padding: 15px 10px;
  }
  }

  @media screen and (min-width: 650px) and (max-width: 750px)
  {
    th {
    padding: 15px;
  }
  }

  @media screen and (min-width: 750px) and (max-width: 900px)
  {
    th, td {
    padding: 10px 20px;
  }
  }

  @media screen and (min-width: 900px)
  {
    th {
    padding: 10px 27px;
  }
  td {
    padding: 10px 30px
  }
  }
  .makeItEllipsis{
    overflow:hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    width:160px ;
}
</style>
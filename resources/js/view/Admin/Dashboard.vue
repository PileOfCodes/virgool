<template>
  <p class="">داشبورد</p>
  <BreadCrumbs class="mt-2" :crumbs="crumbs"></BreadCrumbs>
  <div class="mt-8">
    <div class="flex justify-between">
      <div class="flex justify-evenly text-white text-xl p-4 rounded-md users_count">
        <span class="">تعداد کاربران</span>
        <span class="mr-20">{{users_count}}</span>
      </div>
      <div class="flex text-white text-xl p-4 rounded-md posts_count mr-4">
        <span class="">تعداد پست ها</span>
        <span class="mr-20">{{ posts_count}}</span>
      </div>
      <div class="flex text-white text-xl p-4 rounded-md categories_count mr-4">
        <span class="">تعداد دسته بندی ها</span>
        <span class="mr-20">{{categories_count}}</span>
      </div>
      <div class="flex text-white text-xl p-4 rounded-md comments_count mr-4">
        <span class="">تعداد دیدگاه ها</span>
        <span class="mr-20">{{comments_count}}</span>
      </div>
    </div>
    <p class="mt-8">پست های ویژه صفحه اصلی</p>
    <input type="text" v-model="search" placeholder="جستجو در پست ها"
     class="mt-4 border-2 border-slate-400 w-96 showFeatured px-4 py-2 rounded-md" 
     @click="showOptions = ! showOptions" >
    <template v-if="showOptions == true">
      <div class="border-2 border-slate-400 rounded-md mt-2 w-96">
        <ul v-for="(item, index) in items" :key="index">
          <li class="hover:bg-sky-500 p-2 cursor-pointer" @click="putInSearch(item.slug)">{{item.title}}</li>
        </ul>
      </div>
    </template>
  </div>
  <div class="mt-8">
    <table>
      <tr class="text-xs md:text-sm">
        <th> تصویر پست</th>
        <th> عنوان</th>
        <th> نویسنده</th>
        <th> عملیات</th>
      </tr>
      <tr class="text-xs" v-for="(fpost, index) in posts" :key="index">
        <td><img :src="'/posts/'+fpost.post.image " alt="" class="w-12 h-12 "></td>
        <td>{{ fpost.post.title }}</td>
        <td>{{ fpost.post.user.name }}</td>
        <td>
          <button @click="deletePost(fpost.post.slug)">
            <i class="material-icons text-red-600">&#xe872;</i>
          </button>
        </td>
      </tr>
    </table>
    <div class="flex justify-center mt-4">
        <button class="px-3 py-2 text-xs font-bold rounded-md mx-2 bg-white shadow-lg" @click="prevPage" v-if="!! prevPageUrl"> &lt; قبلی</button>
        <button class="px-3 py-2 text-xs font-bold rounded-md mx-2 bg-white shadow-lg" @click="nextPage" v-if="!! nextPageUrl">بعدی &gt;</button>
    </div>
  </div>
</template>

<script>
  import BreadCrumbs from './BreadCrumbs.vue';
  import {debounce} from 'lodash';
import axios from 'axios';
export default {
    name: "Dashboard",
    data: () => ({
      users_count: null,
      comments_count: null,
      categories_count: null,
      posts_count: null,
      showOptions: false,
      prevPageUrl: null,
      nextPageUrl: null,
      search: null,
      items: [{slug: null, title: null}],
      posts: [],
      crumbs: [
        {
        title: ' داشبورد',
        to: {name: 'admin-dashboard'},
        divider: ' / '
      },
        {
        title: ' صفحه اصلی ',
        to: '',
        divider: ''
      }
    ],
    }),
    methods: {
      putInSearch(slug)
      {
        this.search = slug
        axios.post(`/api/admin/feature-post/${slug}`)
        .then(() => {
          this.fetchFeaturePost()
        })
      },
      fetchFeaturePost()
      {
        axios.get(`/api/admin/feature-post`)
        .then(({data}) => {
          this.posts = data.posts
        })
        .catch(({response}) => {
          if (response.status === 403) {
            this.$router.push({ name: 'access-denied' })
          }
        })
      },
      deletePost(slug)
      {
         if(confirm("آیتم حذف شود؟"))
         {
          axios.delete(`/api/admin/feature-post/${slug}`)
          .then(() => {
            this.fetchFeaturePost()
          })
         }
      },
      debounce: debounce(function(value) {
        if(value == "")
        {
           this.showOptions = false
          return;
        } 
        axios.get(`/api/admin/feature-posts-search?q=${value}`)
        .then(({data}) => {
          this.items = data.data
        })
      },1000)
    },
    watch: {
      search(value)
      {
        this.showOptions = true
        this.debounce(value)
      }
    },
    created() {
      this.fetchFeaturePost(),
      axios.get(`/api/admin/dashboard`)
      .then(({data}) => {
        this.users_count = data.data.users_count
        this.posts_count = data.data.posts_count
        this.categories_count = data.data.categories_count
        this.comments_count = data.data.comments_count
      })
      .catch(({response}) => {
        if (response.status === 403) {
        this.$router.push({ name: 'access-denied' })
        }
      })
    },
    mounted() {
      document.addEventListener('click', (event) => {
        if(! event.target.closest('.showFeatured'))
        {
          this.showOptions = false
        }
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
  .users_count {
    background: #000428;  
    background: -webkit-linear-gradient(to right, #004e92, #000428);  
    background: linear-gradient(to right, #004e92, #000428); 
  }
  .posts_count {
    background: #11998e;  
    background: -webkit-linear-gradient(to right, #38ef7d, #11998e);  
    background: linear-gradient(to right, #38ef7d, #11998e); 
  }
  .categories_count {
    background: #ff00cc;  
    background: -webkit-linear-gradient(to right, #333399, #ff00cc);  
    background: linear-gradient(to right, #333399, #ff00cc); 
  }
  .comments_count {
    background: #ee0979;  
    background: -webkit-linear-gradient(to right, #ff6a00, #ee0979);  
    background: linear-gradient(to right, #ff6a00, #ee0979); 
  }
</style>
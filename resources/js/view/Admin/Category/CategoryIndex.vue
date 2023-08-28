<template>
  <p class="">دسته بندی ها</p>
  <BreadCrumbs class="mt-2" :crumbs="crumbs"></BreadCrumbs>
  <div class="flex items-center mt-6">
    <input type="text" v-model="search"
     placeholder="جستجو دسته بندی..." @keyup="searchItems"
    class="border-b-2 w-full border-sky-500" autofocus>
    <router-link :to="{name: 'admin-category-create'}" title="ساخت دسته بندی">
      <i class="material-icons py-2 px-4 bg-slate-200 mr-4 rounded-md">&#xe145;</i>
    </router-link>
  </div>
  <div class="mt-8">
    <table>
      <tr class="text-xs md:text-sm">
        <!-- <th v-for="(header, index) in headers" :key="index">
          <td>{{header.text}}</td>
        </th> -->
        <th>عنوان</th>
        <th>تعداد پست ها</th>
        <th>تاریخ ساخت</th>
        <th>عملیات</th>
      </tr>
       <tr class="text-xs" v-for="(category, index) in categories.data" :key="index">
        <td>{{ category.title }}</td>
        <td>{{ category.posts_count }}</td>
        <td>{{ category.show_category_date }}</td>
        <td>
        <button @click="deleteCategory(category)">
          <i class="material-icons text-red-600">&#xe872;</i>
        </button>
        <router-link :to="{name: 'post-category', params:{slug: category.slug} }">
          <i class="material-icons text-red-600 mr-4">&#xe8f4;</i>
        </router-link>
        <router-link :to="{name: 'admin-category-edit', params:{id: category.id} }">
          <i class="material-icons text-red-600 mr-4">&#xe254;</i>
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
    name: "UserIndex",
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
        title: '  دسته بندی ها ',
        to: '',
        divider: ''
      }
    ],
    categories: [],
    headers: []
    }),
    methods: {
        deleteCategory(category) {
            if(confirm("آیتم حذف شود؟"))
            {
                let findCategory = this.categories.data.find(c => c.id == category.id)
                let index = this.categories.data.indexOf(findCategory);
                this.categories.data.splice(index,1)
                axios.delete(`/api/admin/categories/${category.id}`)
            }
        },
      nextPage() {
        axios.get(this.nextPageUrl)
        .then(({data}) => {
            this.categories.data.push(...data.categories.data)
            this.prevPageUrl = data.categories.prev_page_url
            this.categories.data = data.categories.data
            this.nextPageUrl = data.categories.next_page_url
        })
      },
      prevPage() {
        axios.get(this.prevPageUrl)
        .then(({data}) => {
            this.categories.data.push(...data.categories.data)
            this.nextPageUrl = data.categories.next_page_url
            this.categories.data = data.categories.data
            this.prevPageUrl = data.categories.prev_page_url
        })
      },
      searchItems() {
        this.debounce()
      },
      debounce: debounce(function() {
        axios.get(`/api/admin/categories?search=${this.search}`)
        .then(({data}) => {
        this.categories = data.categories
        this.headers = data.headers
        this.nextPageUrl = data.categories.next_page_url
        this.prevPageUrl = data.categories.prev_page_url
      })
      }, 500)
    },
    created() {
      axios.get(`/api/admin/categories`)
      .then(({data}) => {
        this.categories = data.categories
        this.headers = data.headers
        this.nextPageUrl = data.categories.next_page_url
        this.prevPageUrl = data.categories.prev_page_url
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
</style>
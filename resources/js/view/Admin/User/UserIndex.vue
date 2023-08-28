<template>
  <p class="">کاربران</p>
  <BreadCrumbs class="mt-2" :crumbs="crumbs"></BreadCrumbs>
  <div class="flex items-center mt-6">
    <input type="text" v-model="search"
     placeholder="جستجو کاربران..." @keyup="searchItems"
    class="border-b-2 w-full border-sky-500" autofocus>
    <router-link :to="{name: 'admin-user-create'}" title="ساخت کاربر">
      <i class="material-icons py-2 px-4 bg-slate-200 mr-4 rounded-md">&#xe7fe;</i>
    </router-link>
  </div>
  <div class="mt-8">
    <table>
      <tr>
        <!-- <th v-for="(header, index) in headers" :key="index">
          <td class="text-xs">{{header.text}}</td>
        </th> -->
        <th class="text-xs">تصویر</th>
        <th class="text-xs">نام</th>
        <th class="text-xs">ایمیل</th>
        <th class="text-xs">نام کاربری</th>
        <th class="text-xs">تعداد پست ها</th>
        <th class="text-xs">تاریخ ساخت</th>
        <th class="text-xs">عملیات</th>
      </tr>
       <tr class="text-xs" v-for="(user, index) in users.data" :key="index">
        <td><img :src="user.profile ? user.profile_src : '/images/person-1.jpg' " alt="" class="w-12 h-12 rounded-full"></td>
        <td>{{ user.name }}</td>
        <td>{{ user.email }}</td>
        <td>{{ user.username }}</td>
        <td>{{ user.posts_count }}</td>
        <td>{{ user.show_user_date }}</td>
        <td>
        <button @click="deleteUser(user)">
          <i class="material-icons text-red-600">&#xe872;</i>
        </button>
        <router-link :to="{name: 'user-posts', params:{username: user.username} }">
          <i class="material-icons text-red-600 mr-4">&#xe8f4;</i>
        </router-link>
        <router-link :to="{name: 'admin-user-edit', params:{id: user.id} }">
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
        title: '  کاربران ',
        to: '',
        divider: ''
      }
    ],
    users: [],
    headers: []
    }),
    methods: {
      deleteUser(user) {
        let findUser = this.users.data.find(u => u.id == user.id)
        let index = this.users.data.indexOf(findUser);
        this.users.data.splice(index,1)
        axios.delete(`/api/admin/users/${user.id}`)
        // .then(() => {

        // })
      },
      nextPage() {
        axios.get(this.nextPageUrl)
        .then(({data}) => {
            this.users.data.push(...data.users.data)
            this.prevPageUrl = data.users.prev_page_url
            this.users.data = data.users.data
            this.nextPageUrl = data.users.next_page_url
        })
      },
      prevPage() {
        axios.get(this.prevPageUrl)
        .then(({data}) => {
            this.users.data.push(...data.users.data)
            this.nextPageUrl = data.users.next_page_url
            this.users.data = data.users.data
            this.prevPageUrl = data.users.prev_page_url
        })
      },
      searchItems() {
        this.debounce()
      },
      debounce: debounce(function() {
        axios.get(`/api/admin/users?search=${this.search}`)
        .then(({data}) => {
        this.users = data.users
        this.headers = data.headers
        this.nextPageUrl = data.users.next_page_url
        this.prevPageUrl = data.users.prev_page_url
      })
      }, 500)
    },
    created() {
      axios.get(`/api/admin/users`)
      .then(({data}) => {
        this.users = data.users
        this.headers = data.headers
        this.nextPageUrl = data.users.next_page_url
        this.prevPageUrl = data.users.prev_page_url
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
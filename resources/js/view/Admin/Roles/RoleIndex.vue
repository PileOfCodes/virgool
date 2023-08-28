<template>
  <p class="">نقش ها</p>
  <BreadCrumbs class="mt-2" :crumbs="crumbs"></BreadCrumbs>
  <div class="flex items-center mt-6">
    <input type="text" v-model="search"
     placeholder="جستجو نقش ها..." @keyup="searchItems"
    class="border-b-2 w-full border-sky-500" autofocus>
    <router-link :to="{name: 'admin-role-create'}" title="ساخت نقش کاربر">
      <i class="material-icons py-2 px-4 bg-slate-200 mr-4 rounded-md">&#xe8d3;</i>
    </router-link>
  </div>
  <div class="mt-8">
    <table>
      <tr class="text-xs md:text-sm">
        <th>تصویر پست</th>
        <th>عنوان</th>
        <th>عملیات</th>
      </tr>
       <tr class="text-xs" v-for="(role, index) in roles.data" :key="index">
        <td>{{ role.name }}</td>
        <td>{{ role.show_role_date }}</td>
        <td>
          <router-link :to="{name: 'admin-role-edit', params:{id: role.id} }">
            <v-icon icon="mdi-account-edit" style="font-size: 28px" color="error" />
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
    name: "RoleIndex",
    data: () => ({
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
        title: '  نقش ها ',
        to: '',
        divider: ''
      }
    ],
    roles: [],
    headers: []
    }),
    methods: {
      nextPage() {
        axios.get(this.nextPageUrl)
        .then(({data}) => {
            this.roles.data.push(...data.roles.data)
            this.prevPageUrl = data.roles.prev_page_url
            this.roles.data = data.roles.data
            this.nextPageUrl = data.roles.next_page_url
        })
      },
      prevPage() {
        axios.get(this.prevPageUrl)
        .then(({data}) => {
            this.roles.data.push(...data.roles.data)
            this.nextPageUrl = data.roles.next_page_url
            this.roles.data = data.roles.data
            this.prevPageUrl = data.roles.prev_page_url
        })
      },
      searchItems() {
        this.debounce()
      },
      debounce: debounce(function() {
        axios.get(`/api/admin/roles?search=${this.search}`)
        .then(({data}) => {
        this.roles = data.roles
        this.headers = data.headers
        this.nextPageUrl = data.roles.next_page_url
        this.prevPageUrl = data.roles.prev_page_url
      })
      }, 500)
    },
    created() {
      axios.get(`/api/admin/roles`)
      .then(({data}) => {
        this.roles = data.roles
        this.nextPageUrl = data.roles.next_page_url
        this.prevPageUrl = data.roles.prev_page_url
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
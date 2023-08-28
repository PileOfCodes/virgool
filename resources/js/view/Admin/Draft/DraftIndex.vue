<template>
  <p class="">پیش نویس ها</p>
  <BreadCrumbs class="mt-2" :crumbs="crumbs"></BreadCrumbs>
  <div class="flex items-center mt-6">
    <input type="text" v-model="search"
     placeholder="جستجو  پیش نویس..." @keyup="searchItems"
    class="border-b-2 w-full border-sky-500" autofocus>
  </div>
  <div class="mt-8">
    <table>
      <tr class="text-xs md:text-sm">
        <th v-for="(header, index) in headers" :key="index">
          <td>{{header.text}}</td>
        </th>
        <th>عملیات</th>
      </tr>
       <tr class="text-xs" v-for="(draft, index) in drafts.data" :key="index">
        <td>{{ draft.title }}</td>
        <td>{{ draft.user.name }}</td>
        <td><div class="makeItEllipsis">{{ draft.show_draft_date }}</div></td>
        <td>
        <button @click="deletePost(draft)">
          <i class="material-icons text-red-600">&#xe872;</i>
        </button>
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
    name: "DraftIndex",
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
        title: '  پیش نویس ها ',
        to: '',
        divider: ''
      }
    ],
    drafts: [],
    headers: []
    }),
    methods: {
      deletePost(draft) {
        if(confirm("آیتم حذف شود؟"))
        {
            let findDraft = this.drafts.data.find(d => d.id == draft.id)
            let index = this.drafts.data.indexOf(findDraft);
            this.drafts.data.splice(index,1)
            axios.delete(`/api/admin/drafts/${draft.id}`)
        }
      },
      nextPage() {
        axios.get(this.nextPageUrl)
        .then(({data}) => {
            this.drafts.data.push(...data.drafts.data)
            this.prevPageUrl = data.drafts.prev_page_url
            this.drafts.data = data.drafts.data
            this.nextPageUrl = data.drafts.next_page_url
        })
      },
      prevPage() {
        axios.get(this.prevPageUrl)
        .then(({data}) => {
            this.drafts.data.push(...data.drafts.data)
            this.nextPageUrl = data.drafts.next_page_url
            this.drafts.data = data.drafts.data
            this.prevPageUrl = data.drafts.prev_page_url
        })
      },
      searchItems() {
        this.debounce()
      },
      debounce: debounce(function() {
        axios.get(`/api/admin/drafts?search=${this.search}`)
        .then(({data}) => {
        this.drafts = data.drafts
        this.headers = data.headers
        this.nextPageUrl = data.drafts.next_page_url
        this.prevPageUrl = data.drafts.prev_page_url
      })
      }, 500)
    },
    created() {
      axios.get(`/api/admin/drafts`)
      .then(({data}) => {
        this.drafts = data.drafts
        this.headers = data.headers
        this.nextPageUrl = data.drafts.next_page_url
        this.prevPageUrl = data.drafts.prev_page_url
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
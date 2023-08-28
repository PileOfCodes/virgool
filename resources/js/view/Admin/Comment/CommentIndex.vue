<template>
  <p class="">دسته بندی ها</p>
  <BreadCrumbs class="mt-2" :crumbs="crumbs"></BreadCrumbs>
  <div class="flex items-center mt-6">
    <input type="text" v-model="search"
     placeholder="جستجو کامنت..." @keyup="searchItems"
    class="border-b-2 w-full border-sky-500" autofocus>
  </div>
  <div class="mt-8">
    <table>
      <tr class="text-xs md:text-sm">
        <!-- <th v-for="(header, index) in headers" :key="index">
          <td>{{header.text}}</td>
        </th> -->
        <th>محتوا</th>
        <th> عنوان پست</th>
        <th> پاسخ</th>
        <th> کاربر نویسنده</th>
        <th>تاریخ ساخت</th>
        <th>عملیات</th>
      </tr>
       <tr class="text-xs" v-for="(comment, index) in comments.data" :key="index">
        <td><div class="makeItEllipsis">{{ comment.content }}</div></td>
        <td>{{ comment.post.title }}</td>
        <td>{{ comment.parent?.content }}</td>
        <td>{{ comment.user.name }}</td>
        <td>{{ comment.show_comment_date }}</td>
        <td>
            <button @click="deleteComment(comment)">
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
    name: "CommentIndex",
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
        title: ' کامنت ها ',
        to: '',
        divider: ''
      }
    ],
    comments: [],
    headers: []
    }),
    methods: {
        deleteComment(comment) {
            if(confirm("آیتم حذف شود؟"))
            {
                let findCommnet = this.comments.data.find(c => c.id == comment.id)
                let index = this.comments.data.indexOf(findCommnet);
                this.comments.data.splice(index,1)
                axios.delete(`/api/admin/comments/${comment.id}`)
            }
        },
      nextPage() {
        axios.get(this.nextPageUrl)
        .then(({data}) => {
            this.comments.data.push(...data.comments.data)
            this.prevPageUrl = data.comments.prev_page_url
            this.comments.data = data.comments.data
            this.nextPageUrl = data.comments.next_page_url
        })
      },
      prevPage() {
        axios.get(this.prevPageUrl)
        .then(({data}) => {
            this.comments.data.push(...data.comments.data)
            this.nextPageUrl = data.comments.next_page_url
            this.comments.data = data.comments.data
            this.prevPageUrl = data.comments.prev_page_url
        })
      },
      searchItems() {
        this.debounce()
      },
      debounce: debounce(function() {
        axios.get(`/api/admin/comments?search=${this.search}`)
        .then(({data}) => {
        this.comments = data.comments
        this.headers = data.headers
        this.nextPageUrl = data.comments.next_page_url
        this.prevPageUrl = data.comments.prev_page_url
      })
      }, 500)
    },
    created() {
      axios.get(`/api/admin/comments`)
      .then(({data}) => {
        this.comments = data.comments
        this.headers = data.headers
        this.nextPageUrl = data.comments.next_page_url
        this.prevPageUrl = data.comments.prev_page_url
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
    width:100px ;
}
</style>
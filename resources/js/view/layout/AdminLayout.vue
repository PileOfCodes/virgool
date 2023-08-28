<template>
  <div class="flex">
    <div class="shadow-lg h-screen duration-500 flex flex-col items-center" :class="[AdminSidebar ? 'w-60': 'w-14']">
      <div class="flex mt-4">
        <img :src="user.profile_src ? user.profile_src : '/images/person-1.jpg'" :class="[AdminSidebar ? 'w-12 h-12 rounded-full' : ''] + 'w-12 h-12 rounded-full mb-2'" alt="">
        <div :class="[AdminSidebar ? 'flex flex-col items-center justify-center pr-2' : 'hidden']">
          <span class="text-xs font-bold">{{user.name}}</span>
          <div class="flex justify-evenly items-center">
          <template v-for="(role,index) in user.roles" :key="index">
              <span class="text-slate-500 p-1 font-bold text-xs" style="font-family: 'IRSThin'">{{role}}</span>
              <span v-if="! index + 1 == user.roles.length" class="text-xl">|</span>
          </template>
          </div>
        </div>
      </div>
      <ul class="border-t-2 w-full flex flex-col pt-2">
        <template v-for="(item, index) in adminItems" :key="index">
          <router-link :to="{name: item.to}" v-if="canShowLink(item.roles)" class="mr-2">
            <li :class="[AdminSidebar ? item.class : 'material-icons text-3xl']">{{ AdminSidebar ? item.title :  item.icon }}</li>
          </router-link>
        </template>
      </ul>
    </div>
    <button  class="material-icons bg-white shadow-lg rounded-full w-10 h-10 duration-500
    relative left-5 top-72  font-bold text-2xl hover:bg-slate-800 hover:text-white" 
    @click="showAdminSidebar">
      {{ AdminSidebar ? '	&#xe5cc;' : '&#xe5cb;' }}
    </button>
    <div class="flex flex-col mt-14">
      <router-view></router-view>
    </div>
  </div>
  <div v-if="showTwoItem == false" class="absolute left-8 top-8 flex flex-col" @mouseover="showTwoItem = true">حالت شب / خروج</div>
  <ul v-if="showTwoItem == true" class="absolute left-8 top-8 flex flex-col" @mouseleave="showTwoItem = false">
    <li class="hover:bg-sky-600 p-2 cursor-pointer" @click="toggleDarkMode">حالت شب</li>
    <li><div @click="logout" class="hover:bg-sky-600 p-2 cursor-pointer" >خروج</div></li>
  </ul>
</template>

<script>
import {useTheme} from 'vuetify'
import {mapState} from 'vuex'
import store from '../../store/index.js'
export default {
    name: "AdminLayout",
    data: () => ({
      showTwoItem: false,
      theme: useTheme(),
      AdminSidebar: false,
      adminItems: [
        {
          title: ' داشبورد ',
          icon: 'dashboard',
          class: 'text-sm font-bold pb-6',
          to: 'admin-dashboard',
          roles: []
        },
        {
          title: 'مدیریت دسته بندی ها ',
          icon: 'library_books',
          class: 'text-sm font-bold pb-6',
          to: 'admin-category-index',
          roles: ['admin','author']
        },
        {
          title: 'مدیریت پست ها ',
          icon: '	satellite',
          class: 'text-sm font-bold pb-6',
          to: 'admin-post-index',
          roles: ['admin','author']
        },
        {
          title: 'مدیریت کاربران ',
          icon: 'person',
          class: 'text-sm font-bold pb-6',
          to: 'admin-user-index',
          roles: ['admin']
        },
        {
          title: 'مدیریت پیش نویس ها ',
          icon: 'edit',
          class: 'text-sm font-bold pb-6',
          to: 'admin-draft-index',
          roles: ['admin','author']
        },
        {
          title: 'مدیریت دیدگاه ها ',
          icon: 'comment',
          class: 'text-sm font-bold pb-6',
          to: 'admin-comment-index',
          roles: ['admin','author']
        },
        {
          title: 'مدیریت نقش ها ',
          icon: 'supervisor_account',
          class: 'text-sm font-bold pb-6',
          to: 'admin-role-index',
          roles: ['admin']
        },
      ]
    }),
    methods: {
      showAdminSidebar() {
        this.AdminSidebar = ! this.AdminSidebar;
      },
      canShowLink(roles)
      {
        if(roles.length === 0 || ! roles) return true;
        return roles.some(role => this.user.roles.includes(role));
      },
      logout() {
        store.dispatch('logout')
        .then(() => {
          this.$router.push({name: 'home'})
        })
      },
      toggleDarkMode()
      {
        this.theme.global.name = this.theme.global.current.dark ? 'light' : 'dark';
        this.theme.global.name === 'dark' ? localStorage.setItem('isDark', 1) : localStorage.removeItem('isDark')
      }
    },
    computed: {
      ...mapState({user: state => state.user.user})
    }

}
</script>

<style>

</style>

<template>
  <div class="w-full h-32 lg:h-56 shadow-xl">
    <IsVerified></IsVerified>
      <div :class="[ search ? 'absolute top-0 right-0 w-full bg-white h-20 ' +
       'flex flex-row justify-start items-center lg:h-28 z-10' : 'hidden']">
          <input type="text" v-model="form" @keyup.enter="pushToSearch" class="mr-20 basis-4/6 border-b-2" placeholder="جستجو..." >
          <i class="material-icons cursor-pointer text-gray-500 mr-12" @click="showSearchModal">&#10006;</i>
      </div>
    <div class="flex justify-start items-center w-full pt-8 lg:pt-12">
      <div class="relative left-20 md:max-w-3xl lg:hidden cursor-pointer">
          <span class="bg-slate-600  hamburger" @click="showDrawer"></span>
      </div>
      <div class="flex items-center text-sky-600 mr-20 basis-2/6 sm:basis-3/6 lg:basis-4/6">
        <router-link :to="{name: 'home'}" class="text-4xl font-black">وبلاگ</router-link>
      </div>
      <div class="flex flex-row justify-center items-center">
        <v-icon icon="mdi-magnify" class="text-gray-500 ml-8 cursor-pointer z-0" @click="showSearchModal"  />
        <v-icon icon="mdi-bell" class="text-gray-500 ml-8 cursor-pointer" @click="showNotificationDrawer"/>
        <template v-if="! auth">
          <router-link :to="{name:'login'}" class="text-sky-600 text-sm">ورود</router-link>
          <span class="px-4 text-gray-500">/</span>
          <router-link :to="{name: 'register'}" class="text-sky-600 text-sm ">ثبت نام</router-link>
        </template>
        <template v-else class="flex flex-col">
          <img v-if="profile != '/profiles/'" :src="profileAvatar" alt=""
          class="rounded-full cursor-pointer w-12 h-12 showProfile_card"
          @click="showUpCard = true">
          <img v-else :src="'/images/person-1.jpg'" alt="" 
          class="rounded-full cursor-pointer w-12 h-12 showProfile_card" @click="showUpCard = true">
          <div :class="[ showUpCard ? 'relative' : 'hidden']">
            <ul class="text-right profile_card text-sm bg-white w-52  p-4 cursor-pointer rounded-md z-10 text-left shadow-lg ">
              <li @click="goToProfile" class="hover:bg-sky-600 pb-2 flex flex-col">
                <span>{{ name }}</span>
                <span>پروفایل من</span>
              </li>
              <li class="hover:bg-sky-600 pb-2 flex flex-col"><router-link :to="{name: 'my-posts'}">نوشته های من</router-link></li>
              <li class="hover:bg-sky-600 pb-2 flex flex-col"><router-link :to="{name: 'bookmarked-posts'}">نوشته های ذخیره شده من</router-link></li>
              <li class="hover:bg-sky-600 pb-2 flex flex-col"><router-link :to="{name: 'liked-posts'}">نوشته های مورد پسند من</router-link></li>
              <li class="hover:bg-sky-600 pb-2 flex flex-col" @click="toggleDarkMode">حالت شب</li>
              <li class="hover:bg-sky-600" @click="logout">خروج از حساب کاربری</li>
            </ul>
          </div>
        </template>
      </div>
    </div>
    <FrontNavbar :categories="categories"></FrontNavbar>
  </div>
</template>

<script>
  import FrontNavbar from './FrontNavbar.vue';
  import IsVerified from './IsVerified.vue';
  import store from '../store/index.js';
  import { mapState } from 'vuex';
  import IsVerified1 from './IsVerified.vue';
  import { useTheme } from 'vuetify'
export default {
    name: "Container",
    components: {
    FrontNavbar,
    IsVerified,
    IsVerified1
},
    data: () => ({
        search : false,
        theme: useTheme(),
        showUpCard: false,
        form: null,
        profileAvatar: null
    }),
    props:['categories'],
    methods: {
      toggleDarkMode()
      {
        this.theme.global.name = this.theme.global.current.dark ? 'light' : 'dark'
        this.theme.global.name === 'dark' ? localStorage.setItem('isDark', 1) : localStorage.removeItem('isDark')
      },
      pushToSearch() {
        this.$router.push({name:'search',
         params:{type: 'posts'},
         query: {q: this.form}
         } )
      },
      showDrawer()
        {
            this.$emit("showDrawer");
        },
      showNotificationDrawer() {
        this.$emit("showNotificationDrawer");
      },
      showSearchModal()
        {
            this.search = ! this.search;
        },
      logout() {
        store.dispatch('logout')
        .then(() => {
          this.$router.push({name:'home'})
        })
      },
      goToProfile() {
        this.$router.push({name: 'profile'})
      }
    },
    mounted() {
      this.profileAvatar = this.profile
      document.addEventListener('click', (event) => {
        if(! event.target.closest('.showProfile_card'))
        {
          this.showUpCard = false
        }
      })
    },
    computed: {
      ...mapState({
        name: state => state.user.user.name,
        auth: state => state.user.isLoggedIn,
        profile: state => state.user.user ? state.user.user.profile_src : ''
      })
    }
}
</script>

<style>
  .hamburger {
    width: 32px;
    height: 5px;
    position: absolute;
    right: 100px;
    border-radius: 4px;
  }
  .hamburger::after {
    content: '';
    width: 32px;
    height: 5px;
    position: absolute;
    border-radius: 4px;
    top: 8px;
    background-color: inherit;
  }
  .hamburger::before {
    content: '';
    width: 32px;
    height: 5px;
    position: absolute;
    border-radius: 4px;
    bottom: 8px;
    background-color: inherit;
  }
  .profile_card {
    position: absolute;
    top: 20px;
    left: -150px;
  }
</style>

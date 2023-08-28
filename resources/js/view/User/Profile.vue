<template>
  <div class="flex flex-col w-full my-12" v-if="user">
    <div class="flex items-first my-12 mx-8 lg:mx-48">
        <img :src="[user.profile != null ? user.profile_src : '/images/person-1.jpg']" @click="$refs.profile.click()" class="cursor-pointer w-20 h-20 rounded-full" alt="">
        <input type="file" ref="profile" class="hidden" @change="uploadProfile">
        <div class="flex flex-col mr-4">
          <div class="flex">
            <input autofocus v-model="user.name" class="bg-transparent focus:border-b-2 border-sky-600 w-80" type="text" @blur="validateName" placeholder="نام">
            <span class="mr-8 text-red-500 text-sm py-2 font-bold" v-if="nameError && user.name == ''">{{ nameError }}</span>
          </div>
            <textarea v-model="user.bio" class="bg-transparent mt-8 focus:border-b-2 border-sky-600" cols="40" rows="5" placeholder="بایوگرافی"></textarea>
        </div>
    </div>
    <div class="mt-12 mx-8 lg:mx-48">
        <h2>تنظیمات حساب کاربری</h2>
        <div class="flex flex-col justify-start">
            <div class="mt-6">
                <span class="text-slate-600 text-sm">نام کاربری</span>
                <template v-if="editing.username" class="flex flex-col">
                  <input autofocus class="sm:w-2/4 w-1/4 moveDirection float-left border-b-2 border-blue-500" 
                  type="text" v-model="user.username" @blur="validateUsername">
                  <span class="text-red-500 mr-4 text-sm py-2 font-bold" v-if="usernameError">{{ usernameError }}</span>
                </template>
                <template v-else>
                  <span class="float-left p-2 border-2 border-green-500">{{ envKey + user.username }}</span>
                  <span class="mr-4 text-red-500 text-sm py-2 font-bold" v-if="usernameError">{{ usernameError }}</span>
                  <span class="mr-4 text-red-500 text-sm py-2 font-bold" v-if="errors['username'] ">{{ errors['username'] }}</span>
                </template>
            </div>
            <div class="flex mt-4">
                <button class="ml-4 border-2 border-sky-600 px-2 py-1 rounded-md hover:bg-sky-600 hover:text-white" 
                @click="changeEdit('username')">{{ editing.username ? 'ثبت' : 'ویرایش' }}</button>
                <button class="border-2 border-sky-600 px-2 py-1 rounded-md hover:bg-sky-600 hover:text-white" 
                v-show="editing.username == true" @click="cancel('username')">منصرف شدن</button>
            </div>
            <div class="mt-6">
                <span class="text-slate-600"> تغییر رمز عبور</span>
                <input autofocus class="sm:w-2/4 w-1/4 float-left moveDirection border-b-2 border-blue-500" 
                  type="text" v-model="user.password" placeholder="رمز عبور" @blur="validatePassword">
                <span class="mr-8 text-red-500 text-sm py-2 font-bold" v-if="passwordError && user.password != ''">{{ passwordError }}</span>
            </div>
            <div class="mt-6">
                <span class="text-slate-600"> ایمیل</span>
                <template v-if="editing.email">
                  <input autofocus class="sm:w-2/4 w-1/4 moveDirection float-left border-b-2 border-blue-500" 
                  type="text" v-model="user.email" @blur="validateEmail">
                  <span class="mr-8 text-red-500 text-sm py-2 font-bold" v-if="emailError">{{ emailError }}</span>
                </template>
                <template v-else>
                  <span class="float-left p-2 border-2 border-green-500">{{ user.email }}</span>
                  <span class="mr-8 text-red-500 text-sm py-2 font-bold" v-if="emailError">{{ emailError }}</span>
                  <span class="mr-8 text-red-500 text-sm py-2 font-bold" v-if="errors['email']">{{ errors['email'] }}</span>
                </template>
            </div>
            <div class="mt-4">
              <button class="ml-4 border-2 border-sky-600 px-2 py-1 rounded-md hover:bg-sky-600 hover:text-white" 
                @click="changeEdit('email')">{{ editing.email ? 'ثبت' : 'ویرایش' }}</button>
                <button class="border-2 border-sky-600 px-2 py-1 rounded-md hover:bg-sky-600 hover:text-white" 
                v-show="editing.email == true" @click="cancel('email')">منصرف شدن</button>
            </div>
            <div class="mt-12">
                <span class=" ">ارسال ایمیل هنگام فالو کردن</span>
              <label class="switch float-left">
                <input type="checkbox" @click="checkFollow" v-if="user.email_on_follow == true" checked >
                <input type="checkbox" @click="checkFollow" v-else-if="user.email_on_follow == false" >
                <span class="slider round"></span>
              </label>
            </div>
            <div class="mt-4">
                <span class=" ">ارسال ایمیل هنگام لایک کردن</span>
              <label class="switch float-left">
                <input type="checkbox" @click="checkLike" v-if="user.email_on_like == true" checked >
                <input type="checkbox" @click="checkLike" v-else-if="user.email_on_like == false" >
                <span class="slider round"></span>
              </label>
            </div>
            <div class="mt-4">
                <span class=" ">ارسال ایمیل هنگام پاسخ دادن</span>
                <label class="switch float-left">
                  <input type="checkbox" @click="checkReply" v-if="user.email_on_reply == true" checked >
                  <input type="checkbox" @click="checkReply" v-else-if="user.email_on_reply == false" >
                  <span class="slider round"></span>
              </label>
            </div>
            <div class="mt-8">
                <button @click="update" class="flex items-center float-left bg-sky-600 text-white p-2 mr-2 rounded-md">
                  <span  class="flex" v-if="loading">
                        <span class="spinner1"></span>
                        <span class="spinner2"></span>
                        <span class="spinner3"></span>
                  </span>
                  <span class="text-sm flex justify-center items-center" v-else>
                        <span>ذخیره پروفایل</span>
                  </span>
                </button>
            </div>
        </div>
    </div>
  </div>
</template>

<script>
import store from '../../store/index.js'
export default {
    name: 'Profile',
    data: () => ({
      user: null,
      envKey: import.meta.env.VITE_APP_URL,
      editing: {
        username: false,
        email: false
      },
      old: {
        username: null,
        email: null
      },
      file: "",
      emailError: "",
      nameError: "",
      regexError: "",
      usernameError: "",
      passwordError: "",
      serverEmailError: "",
      errors: {
        email: null,
        username: null
      },
      loading: false
    }),

    watch: {
     
        'user.username'(value) {
          this.validateUsername(value)
          this.checkRegex(value)
          this.errors.username = ""
        },
    },

    methods: {
      checkReply() {
        this.user.email_on_reply = !this.user.email_on_reply
      },
      checkLike() {
        this.user.email_on_like = !this.user.email_on_like
      },
      checkFollow() {
        this.user.email_on_follow = !this.user.email_on_follow
      },
      cancel(data) {
        this.user[data]  = this.old[data]
        this.editing[data] = false
      },
      changeEdit(data) {
        this.old[data]  = this.user[data]
        this.editing[data] = !this.editing[data]
      },
      uploadProfile(event)
      {
        this.file = event.target.files[0];
        this.user.profile_name = event.target.files[0].name;
        const reader = new FileReader();
        reader.readAsDataURL(this.file)
        reader.onload = (image) => {
          this.user.profile_src = this.user.profile = image.target.result          
        }
      },
      checkRegex(value) {
        if(/^[A-Za-z][A-Za-z0-9_]{6,29}$/.test(value))
        {
          this.usernameError = "";
        }else{
          this.usernameError = "نام کاربری یک فرمت معتبر نیست";
        }
      },
      validateUsername(value) {
        if(value === "")
        {
            this.usernameError = "نام کاربری الزامی است";
        }else {
          this.usernameError = '';
        }
      },
      validateName() {
            if(this.user.name === "")
            {
                this.nameError = "نام الزامی است";
            }
      },
      validateEmail() {
        if (/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(this.user.email)) {
          this.emailError = '';
        } 
        else {
          this.emailError = 'ایمیل معتبر نیست';
        }
        if(this.user.email.length >= 255)
          {
              this.emailError = " طول ایمیل نباید بیشتر از 255 کاراکتر باشد"
          }
        if(this.user.email === "")
          {
              this.emailError = " فیلد ایمیل الزامی است";
          }
      },
      validatePassword() {
            if(this.user.password.length < 8)
            {
                this.passwordError = " رمز عبور باید لااقل 8 کاراکتر باشد";
            } else {
                this.passwordError = "";
            }
            if(/^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{6,16}$/.test(value))
            {
                this.passwordError = ""
            }else {
                this.passwordError = 'رمز عبور معتبر نیست';
            }
      },
      update() {
        this.loading = true;
        if(this.emailError == '' || this.nameError == '' || this.usernameError == '' || this.passwordError == '')
        {
          axios.patch('/api/profile', this.user)
          .then(({data}) => {
            store.dispatch('changeName', data.name)
          })
          .catch(({response}) => {
            for(const index in response.data.errors)
            {
              this.errors[index] = response.data.errors[index][0]
            }
          })
          .finally(() => this.loading = false)
        }
      }
    },

    created() {
      axios.get('/api/me')
      .then(({data}) => {
        this.user = data
        this.old.username = data.username
        this.old.email = data.email
      })
    }

}
</script>

<style>
.switch {
  position: relative;
  display: inline-block;
  width: 56px;
  height: 27px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 23px;
  width: 23px;
  left: 4px;
  bottom: 2px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}

.moveDirection {
  direction: ltr !important;
}

.spinner1 {
        width: 8px;
        height: 8px;
        margin-left: 2px;
        border-radius: 50%;
        background-color: #fff;
        animation-name: spin1;
        animation-duration: 1s;
        animation-iteration-count: infinite;
        animation-direction:alternate;
    }
    .spinner2 {
        width: 8px;
        height: 8px;
        margin-left: 2px;
        border-radius: 50%;
        background-color: #fff;
        animation-name: spin2;
        animation-delay: 1s;
        animation-duration: 1s;
        animation-iteration-count: infinite;
        animation-direction:alternate;
    }
    .spinner3 {
        width: 8px;
        height: 8px;
        margin-left: 2px;
        border-radius: 50%;
        background-color: #fff;
        animation-name: spin3;
        animation-delay: 2s;
        animation-duration: 1s;
        animation-iteration-count: infinite;
        animation-direction:alternate;
    }
    @keyframes spin1 {
        0% {opacity: 0}
        25% {opacity: 0.2}
        50% {opacity: 0.5}
        75% {opacity: 0.75}
        100% {opacity: 1}
    }
    @keyframes spin2 {
        0% {opacity: 0}
        25% {opacity: 0.2}
        50% {opacity: 0.5}
        75% {opacity: 0.75}
        100% {opacity: 1}
    }
    @keyframes spin3 {
        0% {opacity: 0}
        25% {opacity: 0.2}
        50% {opacity: 0.5}
        75% {opacity: 0.75}
        100% {opacity: 1}
    }
</style>
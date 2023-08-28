<template>
<p class="">ویرایش کاربر</p>
  <BreadCrumbs class="mt-2" :crumbs="crumbs"></BreadCrumbs>
  <div class="flex flex-col mt-6">
    <div>
        <input type="text" v-model="form.name" placeholder="نام" style="border: 1px solid grey" class=" w-96 p-2">
        <span class="text-red-500 mr-4">{{user_name}}</span>
    </div>
    <div class="mt-2">
        <input type="text" v-model="form.username" placeholder=" نام کاربری" style="border: 1px solid grey" class=" w-96 p-2">
        <span class="text-red-500 mr-4">{{user_username || usernameErr}}</span>
    </div>
    <div class="mt-2">
        <input type="email" v-model="form.email" placeholder="ایمیل" style="border: 1px solid grey" class=" w-96 p-2">
        <span class="text-red-500 mr-4">{{user_email || emailErr}}</span>
    </div>
    <div class="mt-2">
        <input type="text" v-model="form.password" placeholder="رمز عبور" style="border: 1px solid grey" class=" w-96 p-2">
        <span class="text-red-500 mr-4">{{user_password}}</span>
    </div>
    <p class="text-slate-500 mt-2">نقش های کاربری</p>
    <div class="flex">
        <v-checkbox v-model="form.roles" v-for="role in roles"
        :key="role.name" 
        :value="role.id"
        :label="role.name">
        </v-checkbox>
    </div>
    <button @click="updateUser" class="flex flex-row justify-center items-center text-white bg-sky-600 p-2 w-32 mt-2 rounded-md ">
        <span  class="flex" v-if="loading">
            <span class="spinner1"></span>
            <span class="spinner2"></span>
            <span class="spinner3"></span>
        </span>
        <span class="text-sm flex justify-center items-center" v-else>
            <span class="text-sm">ویرایش</span>
            <i class="material-icons mr-2">&#xe5cb;</i>
        </span>
    </button>
  </div>
</template>

<script>
import BreadCrumbs from '../BreadCrumbs.vue';
export default {
    name: "UserEdit",
    components: {
        BreadCrumbs
    },
    watch: {
        'form.name'(value)
        {
            this.validateName(value)
        },
        'form.username'(value)
        {
            this.validateUsername(value)
        },
        'form.email'(value)
        {
            this.validateEmail(value)
        },
        'form.password'(value)
        {
            this.validatePassword(value)
        },
    },
    data: () => ({
        form: {
            name: '',
            username: '',
            email: '',
            password: '',
            roles: []
        },
        roles: [],
        loading: false,
        usernameErr: null,
        emailErr: null,
        user_name: null,
        user_username: null,
        user_email: null,
        user_password: null,
        crumbs: [
            {
                title: ' داشبورد',
                to: {name: 'admin-dashboard'},
                divider: ' / '
            },
            {
                title: ' مدیریت کاربران ',
                to: {name: 'admin-user-index'},
                divider: '/'
            },
            {
                title: ' ویرایش کاربر ',
                to: '',
                divider: ''
            }
        ],
    }),
    methods: {
        validateUsername(value) {
            if(this.usernameErr != "")
            {
                this.usernameErr = ""
            }
            if(value !== "")
            {
                this.user_username = ""
            }else 
            {   
                this.user_username = " فیلد الزامی است";
            }
            if(/^[A-Za-z][A-Za-z0-9_]{7,29}$/.test(value))
            {
                this.user_username = "";
            }else{
                this.user_username = "نام کاربری یک فرمت معتبر نیست";
            }
        },
        validateName(value) {
            if(value !== "")
            {
                this.user_name = ""
            }else 
            {   
                this.user_name = " فیلد الزامی است";
            }
        },
        validateEmail(value) {
            if(this.emailErr =! "")
            {
                this.emailErr = ""
            }
            if(value !== "")
            {
                this.user_email = ""
            }else 
            {   
                this.user_email = " فیلد الزامی است";
            }
            if (/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(value)) {
                this.emailError = '';
            } 
            else {
                this.user_email = 'ایمیل معتبر نیست';
            }
        },
        validatePassword(value) {
            if(value !== "")
            {
                this.user_password = ""
            }
        },
        updateUser()
        {
            if(this.user_name == "" && this.user_username == "" && this.user_email == "" )
            {
            this.loading = true
            axios.patch(`/api/admin/users/${this.$route.params.id}`, this.form)
            .then(() => {
                this.$router.push({name: 'admin-user-index'})
            })
            .catch(({response}) => {
                if(response.data.errors.username)
                {
                    this.usernameErr = response.data.errors.username[0]
                }
                if(response.data.errors.email)
                {
                    this.emailErr = response.data.errors.email[0]
                }
            })
            .finally(() => this.loading = false)
            }
            if(this.form.name == ""){
                this.user_name =  " فیلد الزامی است"
            }
            if(this.form.username == ""){
                this.user_username =  " فیلد الزامی است"
            }
            if(this.form.email == ""){
                this.user_email =  " فیلد الزامی است"
            }
        }
    },
    created() {
        axios.get(`/api/admin/users/${this.$route.params.id}`)
        .then(({data}) => {
            this.form.name = data.user.name
            this.form.username = data.user.username
            this.form.email = data.user.email
            this.form.roles = data.user.all_roles_id
        }),
        axios.get(`/api/admin/all-roles`)
        .then(({data}) => {
            this.roles = data
        })
    }
}
</script>

<style>
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
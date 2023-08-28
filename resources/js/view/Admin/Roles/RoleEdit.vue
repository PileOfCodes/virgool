<template>
<p class="">نقش ها</p>
  <BreadCrumbs class="mt-2" :crumbs="crumbs"></BreadCrumbs>
  <div class="flex flex-col mt-6">
    <div>
        <input type="text" v-model="role.name" placeholder=" نام نقش" class="border-2 w-96 p-2">
        <span class="text-red-500 mr-4">{{role_name || roleErr}}</span>
    </div>
    <button @click="updateRole" class="flex flex-row justify-center items-center text-white bg-sky-600 p-2 w-32 mt-2 rounded-md ">
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
    name: "RoleEdit",
    components: {
        BreadCrumbs
    },
    watch: {
        'role.name'(value)
        {
            this.validateRolename(value)
        }
    },
    data: () => ({
        role: {
            name: ''
        },
        loading: false,
        roleErr: null,
        role_name: null,
        crumbs: [
            {
                title: ' داشبورد',
                to: {name: 'admin-dashboard'},
                divider: ' / '
            },
            {
                title: ' مدیریت نقش ها ',
                to: {name: 'admin-role-index'},
                divider: '/'
            },
            {
                title: ' ویرایش نقش کاربر ',
                to: '',
                divider: ''
            }
        ],
    }),
    methods: {
        validateRolename(value) {
            if(this.roleErr != "")
            {
                this.roleErr = ""
            }
            if(value !== "")
            {
                this.role_name = ""
            }else 
            {   
                this.role_name = " فیلد الزامی است";
            }
        },
        updateRole()
        {
            if(this.role_name == "")
            {
                this.loading = true
                axios.patch(`/api/admin/roles/${this.$route.params.id}`, this.role)
                .then(() => {
                    this.$router.push({name: 'admin-role-index'})
                })
                .catch(({response}) => {
                    if(response.data.errors.name)
                    {
                        this.roleErr = response.data.errors.name[0]
                    }
                })
                .finally(() => this.loading = false)
            }
            if(this.role.name == ""){
                this.role_name =  " فیلد الزامی است"
            }
        }
    },
    created() {
        axios.get(`/api/admin/roles/${this.$route.params.id}`)
        .then(({data}) => {
            this.role.name = data.role.name
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
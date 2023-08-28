<template>
<p class="">دسته بندی ها</p>
  <BreadCrumbs class="mt-2" :crumbs="crumbs"></BreadCrumbs>
  <div class="flex flex-col mt-6">
    <div>
        <input type="text" v-model="form.title" placeholder="عنوان دسته بندی" class="border-2 w-96 p-2">
        <span class="text-red-500 mr-4">{{category_title || categoryErr}}</span>
    </div>

    <button @click="saveCategory" class="flex flex-row justify-center items-center text-white bg-sky-600 p-2 w-32 mt-2 rounded-md ">
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
import axios from 'axios';
import BreadCrumbs from '../BreadCrumbs.vue';
export default {
    name: "CategoryEdit",
    components: {
        BreadCrumbs
    },
    watch: {
        'form.title'(value)
        {
            this.validateTitle(value)
        },
    },
    data: () => ({
        form: {
            title: '',
        },
        loading: false,
        category_title: null,
        categoryErr: null,
        crumbs: [
            {
                title: ' داشبورد',
                to: {name: 'admin-dashboard'},
                divider: ' / '
            },
            {
                title: ' مدیریت دسته بندی ها ',
                to: {name: 'admin-category-index'},
                divider: '/'
            },
            {
                title: ' ساخت دسته بندی ',
                to: '',
                divider: ''
            }
        ],
    }),
    methods: {
        validateTitle(value) {
            if(this.categoryErr != null)
            {
                this.categoryErr = ""
            }
            if(value == "")
            {
                this.category_title =  " فیلد الزامی است"
            }else {
                this.category_title =  ""
            }
        },

        saveCategory()
        {
            if(this.category_title == "")
            {
                this.loading = true
                axios.patch(`/api/admin/categories/${this.$route.params.id}`, this.form)
                .then(() => {
                    this.$router.push({name: 'admin-category-index'})
                })
                .catch(({response}) => {
                    if(response.data.errors.title)
                    {
                        this.categoryErr = response.data.errors.title[0]
                    }
                })
                .finally(() => this.loading = false)
            }
            if(this.form.title == ""){
                this.category_title =  " فیلد الزامی است"
            }
        }
    },
    created() {
        axios.get(`/api/admin/categories/${this.$route.params.id}`)
        .then(({data}) => {
            this.form.title = data.category.title
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
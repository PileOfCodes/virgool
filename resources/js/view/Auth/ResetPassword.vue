<template>
    <div class="flex flex-col md:flex-row">
        <div class="flex flex-col from-sky-700 bg-gradient-to-br to-slate-400 md:basis-3/6 md:w-full md:h-screen lg:basis-4/6">
            <div class="flex flex-col justify-center items-center mt-48">
                <div class="text-5xl text-white font-bold">وبلاگ</div>
                <div class="mt-10 p-4 font-bold text-xl text-white">اینجا هر کسی میتونه بنویسه!</div>
                <div class="text-md font-bold text-center mx-6 md:mx-12">
                    همین حالا حساب کاربری خودت را بساز و دوران جدید وبلاگ نویسی خودت را شروع کن.
                </div>
            </div>
            <div class="flex flex-col items-center justify-center lg:flex-row lg:justify-start text-white">
                <div class="text-sm flex justify-center mt-12 lg:mt-48">
                    <i>&#169;</i>
                    <span>تمام حقوق برای وبلاگ محفوظ است.</span>
                </div>
                <div class="flex flex-row text-xs my-6 lg:my-0 lg:mr-80 lg:mt-48 md:mt-20 ">
                    <span>صفحه اصلی</span>
                    <span class="mr-8">تماس با ما </span>
                </div>
            </div>
        </div>
    <div class="flex flex-col md:h-screen md:basis-1/3 md:pt-24">
        <div class="text-sky-600 font-bold p-5">رمز عبور جدید خود را وارد کنید</div>
        <input v-model="form.email" @blur="checkEmail" :class="[emailError ? 'border-red-500 border-2' : '']" class="mr-8 my-2 border p-2 w-96 rounded-md" placeholder="ایمیل">
        <span class="mr-8 text-red-500 text-sm py-2 font-bold" v-if="emailError ">{{ emailError }}</span>
        <input v-model="form.password" @blur="checkPassword" :class="[passwordError ? 'border-red-500 border-2' : '']" class="mr-8 my-2 border p-2 w-96 rounded-md" placeholder="رمز عبور" >
        <span class="mr-8 text-red-500 text-sm py-2 font-bold" v-if="passwordError ">{{ passwordError }}</span>
        <input v-model="form.password_confirmation" @blur="checkConPassword" :class="[conPasswordError ? 'border-red-500 border-2' : '']" class="mr-8 my-2 border p-2 w-96 rounded-md" placeholder="تایید رمز عبور">
        <span class="mr-8 text-red-500 text-sm py-2 font-bold" v-if="conPasswordError ">{{ conPasswordError }}</span>
        <template v-if="serverError">
            <span class="mr-8 text-red-500 text-sm py-2 font-bold" v-for="err in serverError" :key="err">
                {{ err[0] }}
            </span>
        </template>
    <div class="flex justify-end ml-8 lg:ml-8 md:ml-0">
        <button @click="changePassword" class="flex flex-row justify-center items-center text-white my-4 bg-sky-600 p-2 h-10 w-48 rounded-xl">
            <span  class="flex" v-if="loading">
                <span class="spinner1"></span>
                <span class="spinner2"></span>
                <span class="spinner3"></span>
            </span>
            <span class="text-sm flex justify-center items-center" v-else>
                <span>تغییر رمز عبور</span>
                <i class="material-icons mr-2">&#xe5cb;</i>
            </span>
        </button>
    </div>
</div>

</div>
</template>

<script>
import store from '../../store/index.js'

export default {
name: 'ResetPassword',
data: () => ({
    loading: false,
    form: {
        email: "",
        password: "",
        password_confirmation: "",
        token: "",
    },
    emailError: "",
    passwordError: "",
    conPasswordError: "",
    serverError: []
}),
created() {
    this.form.token = this.$route.params.token
},
methods: {
    checkEmail() {
        if (/.+@+./.test(this.form.email)) {
            this.emailError = '';
        } else {
            this.emailError = 'ایمیل معتبر نیست';
        }
        if(this.form.email.length >= 255)
        {
            this.emailError = " طول ایمیل نباید بیشتر از 255 کاراکتر باشد"
        }
        if(this.form.email === "")
        {
            this.emailError = " فیلد ایمیل الزامی است";
        }
    },
    checkPassword() {
            if(this.form.password.length < 8)
            {
                this.passwordError = " رمز عبور باید لااقل 8 کاراکتر باشد";
            } else {
                this.passwordError = "";
            }
            if(this.form.password === "")
            {
                this.passwordError = " فیلد پسورد الزامی است";
            }
    },
    checkConPassword() {
            if(this.form.password_confirmation.length < 8)
            {
                this.conPasswordError = " رمز عبور باید لااقل 8 کاراکتر باشد";
            } else {
                this.conPasswordError = "";
            }
            if(this.form.password_confirmation === "")
            {
                this.conPasswordError = " فیلد پسورد الزامی است";
            }
    },
    changePassword() {
        this.loading = true;
        if(this.form.email == "")
        {
            this.emailError = " فیلد ایمیل الزامی است";
        }
        if(this.form.password == "")
        {
            this.passwordError = " فیلد پسورد الزامی است";
        }
        if(this.form.password_confirmation == "")
        {
            this.conPasswordError = " فیلد پسورد الزامی است";
        }
        store.dispatch('resetPassword', this.form)
        .then(() => {
            this.$router.push({name:'home'})
        })
        .catch((error) => {
            this.serverError = error.response.data.errors
        })
        .finally(() => this.loading = false)
    }
}
}
</script>

<style>

</style>
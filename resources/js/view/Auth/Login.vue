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
            <div class="flex flex-col items-center justify-center lg:flex-row lg:justify-start text-white ">
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
            <div class="text-sky-600 font-bold p-5">ورود به حساب کاربری</div>
            <input v-model="form.email" @blur="checkEmail" :class="[emailError ? 'border-red-500 border-2' : '']" class="mr-8 border p-2 w-96 xl:w-96 md:w-80 rounded-md" placeholder="نام کاربری یا ایمیل">
            <span class="mr-8 text-red-500 text-sm py-2 font-bold" v-if="emailError">{{ emailError }}</span>
            <input v-model="form.password" @blur="checkPassword" type="password" :class="[passError ? 'border-red-500 border-2' : '']"
            class="mr-8 border p-2 w-96 md:w-80 xl:w-96 mt-2 rounded-md" placeholder="رمز عبور">
            <span class="mr-8 text-red-500 text-sm py-2 font-bold" v-if="passError">{{ passError }}</span>
        <div class="flex flex-col justify-center items-center md:flex-row mt-4">
            <router-link :to="{name: 'reset-email-password'}" class="text-sm my-6 hover:text-sky-500 md:mx-4">رمز خود را فراموش کرده ام</router-link>
            <button @click="login" class="flex flex-row justify-center items-center text-white bg-sky-600 p-2 w-42 rounded-xl xl:mr-16">
            <span  class="flex" v-if="loading">
                <span class="spinner1"></span>
                <span class="spinner2"></span>
                <span class="spinner3"></span>
            </span>
            <span class="text-sm flex justify-center items-center" v-else>
                <span class="text-sm">ورود به حساب کاربری</span>
                <i class="material-icons mr-2">&#xe5cb;</i>
            </span>
            </button>
        </div>
            <div class="flex flex-col justify-center items-center mt-24 text-sm">
                <span class="mb-12 text-slate-500 font-bold"> ورود با اکانت <i class="text-red-600">گوگل</i></span>
                <span class="p-2">
                    ثبت نام در وبلاگ به منزله موافقت با <i class="border-b-2 border-black">قوانین</i> است</span>
                <router-link class="pb-12 md:pb-4" :to="{ name: 'register'}">عضو نیستید؟ ثبت نام کنید</router-link>
            </div>
        </div>

    </div>

</template>

<script>
    import store from '../../store/index.js'
export default {
    name: "Login",
    data: () => ({
        form: {
            email: null,
            password: null,
            remember: true
        },
        emailError: "",
        passError: "",
        loading: false
    }),
    methods: {
        checkEmail() {
            if(this.form.email === "")
            {
                this.emailError = "این فیلد ضروری است"
            }
        },
        checkPassword() {
            if(this.form.password === "")
            {
                this.passError = "فیلد رمز عبور ضروری است"
            }
        },
        login() {
            this.loading = true
            store.dispatch('login', this.form)
            .then(() => {
                this.$router.push('/')
            })
            .catch((error) => {
                this.emailError = error.response.data.errors.email[0];
                this.passError = error.response.data.errors.password[0];
            })
            .finally(() => this.loading = false)
        }
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

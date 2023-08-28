<template>
    <div class="flex flex-col md:flex-row">
        <div class="flex flex-col from-sky-700 bg-gradient-to-br to-slate-400 md:basis-3/6 md:w-full md:h-screen lg:basis-4/6">
            <div class="flex flex-col justify-center items-center mt-48">
                <div class=" text-5xl text-white font-bold">وبلاگ</div>
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
                <div class="flex flex-row text-xs my-6 lg:my-0 lg:mr-60 xl:mr-80 lg:mt-48 md:mt-20 ">
                    <span>صفحه اصلی</span>
                    <span class="mr-5">تماس با ما </span>
                </div>
            </div>
        </div>
        <div class="flex flex-col md:h-screen md:basis-1/3 md:pt-24">
            <template v-if="sended">
                <div class="text-sky-600 font-bold p-5">ایمیل خود را برای بازیابی رمز عبور وارد کنید</div>
                <input v-model="form.email" @blur="checkEmail" :class="[emailError ? 'border-red-500 border-2' : '']" class="mr-8 border p-2 w-96 rounded-md" placeholder=" ایمیل">
                <span class="mr-8 text-red-500 text-sm py-2 font-bold" v-if="emailError">{{ emailError }}</span>
                <div class="flex justify-end items-center ml-9 mt-2 mb-8">
                    <button @click="sendResetEmail" class="flex flex-row justify-center items-center text-white mt-4 bg-sky-600 p-2 h-10 w-48 rounded-xl">
                        <span  class="flex" v-if="loading">
                            <span class="spinner1"></span>
                            <span class="spinner2"></span>
                            <span class="spinner3"></span>
                        </span>
                        <span class="text-sm flex justify-center items-center" v-else>
                            <span>  بازیابی رمز عبور</span>
                            <i class="material-icons mr-2">&#xe5cb;</i>
                        </span>
                    </button>
                </div>
            </template>
            <template v-else>
                <p class="text-sky-500 pr-10">ایمیل به آدرس نوشته شده ارسال شد!</p>
            </template>
        </div>
    </div>
</template>

<script>
import store from '../../store/index.js'
export default {
    name: 'ResetEmailPassword',
    data: () => ({
        loading: false,
        sended: true,
        emailError: "",
        form: {
            email: ""
        },
    }),
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
        sendResetEmail() {
            if(this.form.email == "")
            {
                this.emailError = " فیلد ایمیل الزامی است"
            }else if(this.form.email == "" && this.emailError != ""){
                return this.emailError
            } else if(this.form.email != "" && this.emailError == ""){
                this.loading = true;
                store.dispatch('sendEmail', this.form)
                .then(() => this.sended = false)
                .catch((error) => {
                    this.emailError = error.response.data.errors.email[0]
                })
                .finally(() => this.loading = false)
            
            }
        }
    }
}
</script>

<style>

</style>
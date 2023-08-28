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
                <div class="flex flex-row text-xs my-6 lg:my-0 lg:mr-64 lg:mt-48 md:mt-20 ">
                    <span>صفحه اصلی</span>
                    <span class="mr-8">تماس با ما </span>
                </div>
            </div>
        </div>
        <div class="flex flex-col items-center justify-center md:h-screen md:basis-2/3">
            <div class="text-sky-600 font-bold text-sm sm:text-xl">
                به وبلاگ خوش آمدید، اینجا شروع دوران جدید نوشتن شما است.
            </div>
            <div class="text-gray-500 text-sm mt-4">
                برای اتمام مراحل ثبت نام با مراجعه به ایمیل خود بر روی لینک فعالسازی کلیک کنید.
            </div>
            <div class="w-full flex items-center justify-end ml-12">
                <router-link :to="{name: 'home'}" class="flex flex-row justify-center items-center text-white mt-4 mb-8 bg-sky-600 p-2 h-10 w-48 rounded-xl">
                    <span  class="flex" v-if="loading">
                        <span class="spinner1"></span>
                        <span class="spinner2"></span>
                        <span class="spinner3"></span>
                    </span>
                    <span class="text-sm flex justify-center items-center" v-else>
                        <span>ورود به صفحه اصلی</span>
                        <i class="material-icons mr-2">&#xe5cb;</i>
                    </span>
                </router-link>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    name: 'Verify',
    data: () => ({
        loading: false,
    })
}
</script>

<style>

</style>
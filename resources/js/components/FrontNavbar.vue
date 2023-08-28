<template>
    <div class="flex justify-center items-center w-full bg-sky-600 py-4 mt-8 lg:block hidden">
        <router-link :to="{name:'create-post' }" class="text-white text-sm pl-6 mr-20">
                ایجاد پست جدید
        </router-link>
        <span v-for="(category,index) in categories" :key="index">
            <router-link :to="{name:'post-category', params:{slug: category.slug} }" class="text-slate-200 text-sm pl-6 mr-12 hover:text-white">
                {{ " " + category.title + " " }}
            </router-link>
        </span>
    </div>
</template>

<script>
export default {
    name: 'FrontNavbar',
    data: () => ({

    }),
    props:['categories']
}
</script>

<style>

</style>

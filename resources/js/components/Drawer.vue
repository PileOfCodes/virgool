<template>
<div class="absolute top-0 bottom-0 w-full bg-slate-900 bg-opacity-40 z-10">
<div class="sidebar absolute top-0 bottom-0 right-0 bg-white w-80 ">
    <span class="absolute left-4 top-2 cursor-pointer text-3xl hover:text-slate-500 duration-300"
          @click="$emit('closeSidebar')">&#10006;
    </span>
    <router-link :to="{name: 'create-post' }" class="pt-4 mt-5 mr-4">ایجاد پست جدید</router-link>
    <span v-for="(category, index) in categories" :key="index" class="flex flex-col">
       <router-link :to="{name: 'post-category', params:{slug: category.slug} }" class="pt-4 mt-5 mr-4">{{ category.title }}</router-link>
    </span>
</div>
</div>
</template>

<script>
export default {
    name: "Drawer",
    props: ['categories']
}
</script>

<style>
</style>

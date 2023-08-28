<template>
<div class="fixed top-0 bottom-0 w-full bg-slate-900 bg-opacity-40 z-10">
<div class="sidebar absolute top-0 bottom-0 left-0 bg-white w-80 ">
    <span class="absolute left-4 top-2 cursor-pointer text-3xl hover:text-slate-500 duration-300"
          @click="$emit('closeNotificationSidebar')">&#10006;</span>
    <span v-for="notification in notifications" :key="notification.id" class="flex flex-col text-red-500">
        <a :href="notification.data.link" @click.prevent="readNotification(notification)" 
        class="pt-4 mt-5 mr-4" :class="{'text-sky-500' : !! notification.read_at}">
        {{ notification.data.text}}
        </a>
    </span>
</div>
</div>
</template>

<script>
export default {
    name:"UserNotifications",
    props: ['notifications'],
    methods: {
        readNotification(notification)
        {
            axios.patch(`/api/notifications/${notification.id}`)
            .then(() => {
                this.$router.push(notification.data.link)
            })
        }
    }
}
</script>

<style>

</style>


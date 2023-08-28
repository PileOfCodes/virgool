<template>
    <Container @showDrawer="showDrawer" @showNotificationDrawer="showNotificationDrawer" :categories="$store.state.category.categories"></Container>
    <Drawer @closeSidebar="showSidebar = false" :class="[showSidebar == true ? '' : 'hidden']"
            :categories="$store.state.category.categories">
    </Drawer>
    <NotificationDrawer @closeNotificationSidebar="showNotificationSidebar = false"
    :class="[showNotificationSidebar == true ? '' : 'hidden']" :notifications="$store.state.notification.notifications">
    </NotificationDrawer>
    <router-view></router-view>
</template>

<script>
import Container from '/resources/js/components/Container.vue';
import NotificationDrawer from '../User/UserNotifications.vue'
import Drawer from '/resources/js/components/Drawer.vue';
export default {
    name: "Header",
    components: {
        Container,
        Drawer,
        NotificationDrawer
    },
    data: () => ({
        showSidebar: false,
        showNotificationSidebar: false
    }),
    methods: {
        showDrawer() {
            this.showSidebar = !this.showSidebar;
        },
        showNotificationDrawer() {
            this.showNotificationSidebar = !this.showNotificationSidebar;
        },
    },
    created() {
        this.$store.dispatch('getNotifications')
        this.$store.dispatch('getNavbarCategories')
    }
}
</script>

<style scoped>

</style>

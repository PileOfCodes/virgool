import { createRouter, createWebHistory } from 'vue-router';
import routes from './routes.js';
import store from './store/index.js';


const router = createRouter({
    history: createWebHistory(),
    routes
});

router.beforeEach((to, from, next) => {
    if((to.meta.guest || to.matched.some(parent => parent.meta.guest)) && store.state.user.isLoggedIn)
    {
        return next({name: 'home'})
    }
    if((to.meta.auth || to.matched.some(parent => parent.meta.auth)) && !store.state.user.isLoggedIn)
    {
        return next({name: 'login'})
    }
    if((to.meta.verified || to.matched.some(parent => parent.meta.verified)) && store.state.user.user.isVerified != 2)
    {
        return next({name: 'home'})
    }
    document.title = to.meta.title
    next()
}) 


export default router;

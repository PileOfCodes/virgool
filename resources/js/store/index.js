import {createStore} from 'vuex'
import user from './modules/user.js'
import draft from './modules/draft.js'
import post from './modules/post.js'
import category from './modules/category.js'
import notification from './modules/notification.js'


const store = createStore({
    modules: {
        user,
        draft,
        post,
        category,
        notification
    }
})


export default store
import axios from 'axios'


export default {
    state: {
        categories: []
    },
    mutations: {
        SET_CATEGORIES(state, categories)
        {
            state.categories = categories
        }
    },
    actions: {
        async getNavbarCategories({commit, state}) {
            if(state.categories.length == 0)
            {
                let {data} = await axios.get(`/api/navbar-categories`)
                commit('SET_CATEGORIES', data.data)
                return;
            }
        }
    }
}


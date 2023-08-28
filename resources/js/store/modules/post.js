import axios from 'axios'


export default {
    state: {
        post: null,
        posts: null,
        posts_count: null
    },
    mutations: {
        SET_POST(state, data) {
            state.post = data
        },
        SET_ALL_POSTS(state, data) {
            state.posts = data
        },
        SET_POSTS_COUNT(state, data) {
            state.posts_count = data
        },
        DELETE_POST(state, index) {
            state.posts.splice(index, 1)
            state.posts_count--
        }
    },
    actions: {
        async savePost({commit}, form) {
            let response = await axios.post('/api/posts/', form)
            commit('SET_POST', response.data)
            return response;
        },

        async editPost({commit}, form) {
            let response = await axios.patch(`/api/posts/${form.slug}`, form)
            commit('SET_POST', response.data)
            return response;
        },
        
        async updatePost({commit}, {title, content, slug})
        {
            await axios.get('/sanctum/csrf-cookie');
            const data = { title,content};
            commit('SET_POST', data);
            return axios.patch(`/api/posts/${slug}/edit`, data)
        },

        async fetchAllPosts({commit}) {
            let {data} = await axios.get(`/api/posts/all-posts`)
            commit('SET_ALL_POSTS', data.data)
            commit('SET_POSTS_COUNT', data.posts_count)
        },

        async deletePost({ commit }, {index,slug}) {
            await axios.delete(`/api/posts/${slug}`)
            commit('DELETE_POST', index)
        }
    }
}


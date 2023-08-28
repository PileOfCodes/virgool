import axios from 'axios'


export default {
    state: {
        draft: null,
        drafts: null,
        drafts_count: null,
        posts_count: null
    },
    mutations: {
        SET_DRAFT(state, data) {
            state.draft = data
        },
        DELETE_DRAFT(state, index) {
            state.drafts.splice(index, 1)
            state.drafts_count--
        },
        SET_ALL_DRAFTS(state, data) {
            state.drafts = data
        },
        SET_DRAFTS_COUNT(state, data) {
            state.drafts_count = data
        }
    },
    actions: {
        async saveDraft({commit}, {requestType,requestUri,title,content}) 
        {
            await axios.get('/sanctum/csrf-cookie');
            const data = { title,content};
            commit('SET_DRAFT', data);
            return axios[requestType](requestUri, data)
        },
        async getDraft({commit, state}, link) {
            if(state.draft == null)
            {
                let response = await axios.get(`/api/drafts/${link}`)
                const data = {
                    title : response.data.title,
                    content: response.data.content
                }
                commit('SET_DRAFT', data)
            }
            return state.draft;
        },
        async fetchAllDrafts({ commit }) {
            let {data} = await axios.get('/api/posts/all-drafts')
            commit('SET_ALL_DRAFTS', data.data)
            commit('SET_DRAFTS_COUNT', data.drafts_count)
        },
        async deleteDrafts({ commit }, {index, link}) {
            await axios.delete(`/api/drafts/${link}`)
            commit('DELETE_DRAFT', index)
        }
    }
}


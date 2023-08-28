import axios from 'axios'


export default {
state: {
        user: window.user,
        isLoggedIn: !! window.user,
        profile_src: window.user
    },
mutations: {
        LOGOUT(state) {
            state.isLoggedIn = false,
            state.user = null,
            state.profile_src= null
        },
        LOGIN(state, payload) {
            state.isLoggedIn = true,
            state.user = {
                name: payload.name,
                profile_src: payload.profile_src,
                isVerified: payload.email_verified_at == null ? 1 : 2
            }
        },
        
        REGISTER(state, payload) {
            state.isLoggedIn = true,
            state.user = {
                name: payload.name,
                isVerified: 1,
                profile_src: payload.profile_src
            }
        },

        RESET_PASSWORD(state, payload) {
            state.isLoggedIn = true,
            state.user = {
                name: payload.name,
            }
        },

        SET_NAME(state, name) {
            state.user.name = name
        }
    },
    actions: {
        logout({commit}) {
            return axios.post('/logout')
            .then(() => {
                commit('LOGOUT')
            })
        },
        login({commit}, form) {
            return axios.post('login', form)
            .then(({data}) => {
                commit('LOGIN', data.user)
            })
        },
        register({commit}, form) {
            return axios.post('register', form)
            .then(({data}) => {
                commit('REGISTER', data.data)
            })
        },
        sendEmail(context, form) {
            return axios.post('/api/password/email', form)
        },
        
        resetPassword({commit}, form) {
            return axios.post('/api/password/reset', form)
            .then(async () => {
                let response = await axios.get('/api/me')
                commit('RESET_PASSWORD', response.data)
            })
        },

        changeName({commit} , name) {
            commit('SET_NAME', name);
        }
    }
}

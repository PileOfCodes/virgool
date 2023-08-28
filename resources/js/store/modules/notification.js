import axios from 'axios'


export default {
    state: {
        notifications: []
    },
    mutations: {
        SET_NOTIFICATION(state, notifications)
        {
            state.notifications = notifications
        }
    },
    actions: {
        async getNotifications({commit, state}) {
            if(state.notifications.length == 0)
            {
                let {data} = await axios.get(`/api/notifications`)
                commit('SET_NOTIFICATION', data.data)
                return;
            }
        }
    }
}


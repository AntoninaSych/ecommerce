import {createStore} from 'vuex'

const store = createStore({
    state: {
        user: {
            token: '12345',
            data: {}
        }
    },
    getters: {},
    actions: {},
    mutations: {}
})
export default store
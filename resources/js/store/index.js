import { createStore } from 'vuex'

// Create a new store instance.
export const store = createStore({
  state () {
    return {
      auth_user: null,
    }
  },
  mutations: {
    setAuthUser (state, auth_user) {
      state.auth_user = auth_user
    }
  },
  actions: {
    async fetchAuthUser({ commit }) {
        try {
          const data = await axios.get('/api/auth_user')
            commit('setAuthUser', data.data)
          }
          catch (error) {
              alert(error)
              console.log(error)
          }
      }
  },
})

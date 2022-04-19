import { createStore } from 'vuex'

import VueGates from 'vue-gates';

// Create a new store instance.
export const store = createStore({
  state () {
    return {
      auth_user: null,
      roles: [],
      permissions: [],
    }
  },
  mutations: {
    setAuthUser (state, auth_user) {
      state.auth_user = auth_user
    },
    setAuthUserRole (state, roles) {
      state.roles = roles

      this.$gates.setRoles(this.$store.state.roles);
    },
    setAuthUserPermission (state, permissions) {
      state.permission = permissions
      this.$gates.setPermissions(permissions);
    }
  },
  actions: {
    async fetchAuthUser({ commit }) {
        try {
          const data = await axios.get('/api/auth_user')
          console.log(data.data)
            commit('setAuthUser', data.data)
          }
          catch (error) {
              alert(error)
          }
      },
    async fetchAuthUserRoles({ commit }) {
        try {
          const data = await axios.get('/api/auth_roles')
            commit('setAuthUserRole', data.data)
          }
          catch (error) {
      }
    },
    async fetchAuthUserPermissions({ commit }) {
        try {
          const data = await axios.get('/api/auth_permissions')
            commit('setAuthUserPermission', data.data)
          }
          catch (error) {
      }
    }
  },
})



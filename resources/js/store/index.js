import { createStore } from 'vuex'
import createPersistedState from "vuex-persistedstate";

// Create a new store instance.
export const store = createStore({
  plugins: [createPersistedState()],
  state () {

    return {
      auth_user: null,
      auth_roles: [],
      auth_permissions: [],
      roles: [],
      leave_types: [],
      groups: [],
    }
  },
  mutations: {
    setAuthUser (state, auth_user) {
      state.auth_user = auth_user
    },
    setAuthUserRole (state, roles) {
      state.roles = roles
    },
    setAuthUserPermission (state, permissions) {
      state.permissions = permissions
    },
    setPillars (state, value) {
      state.pillars = value
    },
    setRoles (state, value) {
      state.roles = value
    },
    setStaffTypes (state, value) {
        state.staffTypes = value
    },
    setContractTypes (state, value) {
      state.contractTypes = value
    },
    setStaffCategories (state, value) {
      state.staffCategories = value
    },
    setDesignations (state, value) {
      state.designations = value
    },
    setCourseCategories (state, value) {
      state.courseCategories = value
    },
    setSupervisors (state, value) {
      state.supervisors = value
    },
    setLeaveType(state, value) {
        state.leave_types = value
    },
    setGroup(state, value) {
        state.groups = value
    },
  },
  actions: {
    async fetchAuthUser({ commit }) {
        try {
          const data = await axios.get('/api/auth-user')
          console.log(data.data)
            commit('setAuthUser', data.data)
          }
          catch (error) {
              alert(error)
          }
      },
    async fetchAuthUserRoles({ commit }) {
        try {
          const data = await axios.get('/api/auth-roles')
            commit('setAuthUserRole', data.data)
        this.$gates.setRoles(data.data);
          }
          catch (error) {
      }
    },
    async fetchAuthUserPermissions({ commit }) {
        try {
          const data = await axios.get('/api/auth-permissions')
            commit('setAuthUserPermission', data.data)
            this.$gates.setPermissions(data.data);
          }
          catch (error) {
      }
    },
    async fetchOptions({ commit }) {
      try {
        const data = await axios.get('/api/choice-role')
        commit('setRoles', data.data)

        const group_choice = await axios.get('/api/choice-group')
          commit('setGroup', group_choice.data)

        const leave_choice = await axios.get('/api/choice-leave-type')
          commit('setLeaveType', leave_choice.data)
        }
        catch (error) {
    }
  }
  },
})



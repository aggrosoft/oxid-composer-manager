import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

const api = axios.create()

Vue.use(Vuex)

var tokenResolve = null
const tokenPromise = new Promise((resolve) => { tokenResolve = resolve })

export default new Vuex.Store({
  getters: {
    token: () => { return tokenPromise },
    packages: state => state.packages && state.packages.installed ? state.packages.installed : []
  },
  state: {
    packages: {},
    token: undefined
  },
  mutations: {
    setPackages (state, packages) {
      state.packages = packages
    },
    setToken (state, token) {
      const curToken = state.token
      state.token = token
      if (curToken !== token) tokenResolve(token)
    }
  },
  actions: {
    setToken ({ commit }, token) {
      commit('setToken', token)
    },
    async initPackages ({commit, state, getters}) {
      if (!state.packages.length) {
        return getters.token.then(token => {
          return api.get('/admin/index.php?cl=composerman&fnc=getpackages&stoken='+token).then(r => {
            commit('setPackages', r.data)
          })
        })
      }
    }
  },
  modules: {
  }
})

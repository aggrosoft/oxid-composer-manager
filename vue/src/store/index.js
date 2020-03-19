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
    loading: state => state.loading,
    packages: state => state.packages && state.packages.installed ? state.packages.installed : []
  },
  state: {
    packages: {},
    token: undefined,
    loading: true
  },
  mutations: {
    setPackages (state, packages) {
      state.packages = packages
    },
    setLoading (state, loading) {
      state.loading = loading
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
    async initPackages ({dispatch, state}) {
      if (!state.packages.length) {
        return dispatch('loadPackages')
      }
    },
    async loadPackages ({commit, getters}) {
      commit('setLoading', true)
      return getters.token.then(token => {
        return api.get('/admin/index.php?cl=composerman&fnc=getpackages&stoken='+token).then(r => {
          commit('setPackages', r.data)
          commit('setLoading', false)
        })
      })
    },
    async updatePackage ({commit, dispatch, getters}, pkg) {
      commit('setLoading', true)
      return getters.token.then(token => {
        return api.get('/admin/index.php?cl=composerman&fnc=updatepackage&stoken='+token+'&package='+pkg).then(() => {
          return dispatch('loadPackages')
        })
      })
    },
    async runCommand ({commit, dispatch, getters}, cmd) {
      commit('setLoading', true)
      return getters.token.then(token => {
        const data = new FormData();
        data.set('stoken', token)
        data.set('cl', 'composerman')
        data.set('fnc', 'runcommand')
        data.set('cmd', cmd)
        return api.post('/admin/index.php', data).then(r => {
          dispatch('loadPackages')
          return r.data
        })
      })
    }
  },
  modules: {
  }
})

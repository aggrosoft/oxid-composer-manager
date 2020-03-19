import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'
import packages from '@/store/mock/packages'
const api = axios.create()

Vue.use(Vuex)

var tokenResolve = null
const tokenPromise = new Promise((resolve) => { tokenResolve = resolve })

export default new Vuex.Store({
  getters: {
    token: () => { return tokenPromise },
    loading: state => state.loading,
    packages: state => state.packages && state.packages.installed ? state.packages.installed : [],
    composerJson: state => state.composerJson
  },
  state: {
    packages: process.env.NODE_ENV === 'production' ? {} : packages,
    token: undefined,
    loading: true,
    composerJson: ''
  },
  mutations: {
    setPackages (state, packages) {
      state.packages = packages
    },
    setLoading (state, loading) {
      state.loading = loading
    },
    setComposerJson (state, composerJson) {
      state.composerJson = composerJson
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
    async initPackages ({dispatch, getters, commit}) {
      if (!getters.packages.length) {
        return dispatch('loadPackages')
      } else {
        commit('setLoading', false)
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
    async removePackage ({commit, dispatch, getters}, pkg) {
      commit('setLoading', true)
      return getters.token.then(token => {
        return api.get('/admin/index.php?cl=composerman&fnc=removepackage&stoken='+token+'&package='+pkg).then(() => {
          return dispatch('loadPackages')
        })
      })
    },
    async getComposerJson ({commit, getters}) {
      commit('setLoading', true)
      return getters.token.then(token => {
        return api.get('/admin/index.php?cl=composerman&fnc=getcomposerjson&stoken='+token).then(r => {
          commit('setComposerJson', r.data)
          return r.data
        })
      })
    },
    async saveComposerJson () {

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

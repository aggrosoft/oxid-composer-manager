import Vue from 'vue'
import Vuex from 'vuex'
import api from '@/store/api'
import packages from '@/store/mock/packages'
import composerJson from '@/store/mock/composerJson'

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
    composerJson: process.env.NODE_ENV === 'production' ? undefined : composerJson,
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
    async addPackage ({commit, dispatch, getters}, pkg) {
      commit('setLoading', true)
      return getters.token.then(token => {
        return api.get('/admin/index.php?cl=composerman&fnc=addpackage&stoken='+token+'&package='+pkg).then(r => {
          dispatch('loadPackages')
          return r.data
        })
      })
    },
    async updatePackage ({commit, dispatch, getters}, pkg) {
      commit('setLoading', true)
      return getters.token.then(token => {
        return api.get('/admin/index.php?cl=composerman&fnc=updatepackage&stoken='+token+'&package='+pkg).then(r => {
          dispatch('loadPackages')
          return r.data
        })
      })
    },
    async removePackage ({commit, dispatch, getters}, pkg) {
      commit('setLoading', true)
      return getters.token.then(token => {
        return api.get('/admin/index.php?cl=composerman&fnc=removepackage&stoken='+token+'&package='+pkg).then(r => {
          dispatch('loadPackages')
          return r.data
        })
      })
    },
    async getComposerJson ({commit, getters}) {
      if (getters.composerJson) {
        return getters.composerJson
      }else{
        commit('setLoading', true)
        return getters.token.then(token => {
          return api.get('/admin/index.php?cl=composerman&fnc=getcomposerjson&stoken='+token).then(r => {
            commit('setComposerJson', r.data)
            return r.data
          })
        })
      }
    },
    async setComposerJson ({commit}, composerJson) {
      commit('setComposerJson', composerJson)
    },
    async saveComposerJson ({commit, getters}, contents) {
      commit('setLoading', true)
      return getters.token.then(token => {
        const data = new FormData();
        data.set('stoken', token)
        data.set('cl', 'composerman')
        data.set('fnc', 'savecomposerjson')
        data.set('contents', contents)
        return api.post('/admin/index.php', data).then(r => {
          return r.data
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

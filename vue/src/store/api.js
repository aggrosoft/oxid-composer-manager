import axios from 'axios'

const api = axios.create()

const wait = function(ms, value) {
  return new Promise(resolve => setTimeout(resolve, ms, value));
}

export default {
  get(url) {
    if (process.env.NODE_ENV === 'production') {
      return api.get(url)
    }else{
      return wait(2500, {data: 'mocked result data'})
    }
  },
  post(url, data){
    if (process.env.NODE_ENV === 'production') {
      return api.post(url, data)
    }else{
      return wait(2500, {data: 'mocked result data'})
    }
  }
}
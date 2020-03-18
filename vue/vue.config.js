module.exports = {
  "transpileDependencies": [
    "vuetify"
  ],
  outputDir: '../copy_this/modules/composerman/out/vue/',
  indexPath: process.env.NODE_ENV === 'production'
    ? '../../application/views/admin/tpl/composerman.tpl'
    : '/',
  publicPath: process.env.NODE_ENV === 'production'
    ? '/modules/composerman/out/vue/'
    : '/'
}
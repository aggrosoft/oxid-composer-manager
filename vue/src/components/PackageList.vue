<template>
  <div>
    <v-data-table
      :items="packages"
      :headers="headers"
      />
  </div>
</template>

<script>
  import {mapActions, mapGetters} from "vuex";

  export default {
    name: "PackageList",
    mounted: function() {
      console.log('init!')
      this.initPackages()
    },
    data: () => ({
      headers: [
        {
          text: 'Titel',
          value: 'name'
        },
        {
          text: 'Version',
          value: 'version'
        },
        {
          text: 'Beschreibung',
          value: 'description'
        },
        { text: 'Aktionen', value: 'action', sortable: false },
      ],
    }),
    computed: {
      ...mapGetters(['packages'])
    },
    methods: {
      ...mapActions(['initPackages'])
    }
  }
</script>

<style scoped>

</style>
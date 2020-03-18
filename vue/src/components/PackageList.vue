<template>
  <div>
    <v-data-table
      :items="packages"
      :headers="headers"
      :loading="!packages || !packages.length"
      >
      <template v-slot:item.version="{item}">
        <v-chip>
          {{item.version}}
        </v-chip>
      </template>
      <template v-slot:item.latest="{item}">
        <v-chip
          :color="versionColor(item)"
        >
          {{item.latest}}
        </v-chip>
      </template>
      <template v-slot:item.latest-status="{item}">
        <span v-html="statusText(item)" />
      </template>
      <template v-slot:item.action="{item}">
        <v-btn color="primary" text icon class="mr-lg-2" small @click.stop="clickUpdatePackage(item)" v-on="on" v-if="isUpdatable(item)">
          <v-icon>
            mdi-refresh
          </v-icon>
          Aktualisieren
        </v-btn>
      </template>
    </v-data-table>
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
          text: 'Neueste Version',
          value: 'latest'
        },
        {
          text: 'Status',
          value: 'latest-status'
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
      versionColor: function(item) {
        if (item['latest-status'] === 'semver-safe-update'){
          return 'green'
        }else if(item['latest-status'] === 'update-possible'){
          return 'orange'
        }else{
          return undefined
        }
      },
      statusText: function(item) {
        if (item['latest-status'] === 'semver-safe-update'){
          return 'Sicheres Update'
        }else if(item['latest-status'] === 'update-possible'){
          return 'Update möglich'
        }else if(item['latest-status'] === 'up-to-date'){
          return 'Aktuell'
        }else{
          return item['latest-status']
        }
      },
      isUpdatable: function(item) {
        return item['latest-status'] !== 'up-to-date'
      },
      clickUpdatePackage: function(item) {
        this.updatePackage(item.name)
      },
      ...mapActions(['initPackages', 'updatePackage'])
    }
  }
</script>

<style scoped>

</style>
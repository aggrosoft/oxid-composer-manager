<template>
  <v-card>
    <v-card-title>
      <v-text-field
          v-model="search"
          append-icon="mdi-magnify"
          label="Installierte Pakete durchsuchen"
          single-line
          hide-details
      ></v-text-field>
    </v-card-title>
    <v-data-table
      :items="packages"
      :headers="headers"
      :loading="loading"
      :search="search"
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
        <v-btn fab :color="isUpdatable(item) ? 'primary' : ''" text icon class="mr-lg-2" small @click.stop="clickUpdatePackage(item)">
          <v-icon>
            mdi-refresh
          </v-icon>
        </v-btn>
        <v-btn fab color="red darken-1" text icon small @click.stop="clickDeletePackage(item)">
          <v-icon>
            mdi-delete
          </v-icon>
        </v-btn>
      </template>
    </v-data-table>
    <ConfirmDialog ref="confirm" />
  </v-card>
</template>

<script>
  import {mapActions, mapGetters} from "vuex";
  import ConfirmDialog from "./ConfirmDialog";

  export default {
    name: "PackageList",
    components: {ConfirmDialog},
    mounted: function() {
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
      search: ''
    }),
    computed: {
      ...mapGetters(['packages', 'loading'])
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
      clickUpdatePackage: async function(item) {
        if (await this.$refs.confirm.open('Paket aktualisieren?','Wollen Sie das gewählte Paket "'+item.name+'" wirklich aktualisieren?')) {
          console.log('update')
          this.updatePackage(item.name)
        }
      },
      clickDeletePackage: async function(item) {
        if (await this.$refs.confirm.open('Paket entfernen?','Wollen Sie das gewählte Paket "'+item.name+'" wirklich löschen? Dieser Vorgang kann das System unbrauchbar machen, es werden nur Pakete vom Typ "oxideshop-module" gelöscht.')){
          console.log('delete')
          this.removePackage(item.name)
        }
      },
      ...mapActions(['initPackages', 'updatePackage', 'removePackage'])
    }
  }
</script>

<style scoped>

</style>
<template>
  <v-card>
    <v-form>
      <v-container fluid>
        <v-row align="center">
          <v-col
              cols="4"
          >
            <v-select
                label="Suchen nach Pakettyp"
                :items="packageTypes"
                v-model="packageType"
                prepend-icon="mdi-briefcase-search"
                return-object
                hide-details
            />
          </v-col>
          <v-col
              cols="5"
          >
            <v-autocomplete
                v-model="selectedPackage"
                :items="items"
                :loading="isLoading"
                :search-input.sync="search"
                hide-no-data
                hide-selected
                item-text="name"
                item-value="name"
                label="Paket wählen"
                placeholder="Geben Sie den Paketnamen ein um zu Suchen"
                prepend-icon="mdi-database-search"
                :return-object="false"
                clearable
                hide-details
            >
              <template v-slot:item="data">
                <template v-if="typeof data.item !== 'object'">
                  <v-list-item-content v-text="data.item"></v-list-item-content>
                </template>
                <template v-else>
                  <v-list-item-content>
                    <v-list-item-title v-html="data.item.name"></v-list-item-title>
                    <v-list-item-subtitle v-html="data.item.description"></v-list-item-subtitle>
                  </v-list-item-content>
                </template>
              </template>
            </v-autocomplete>
          </v-col>
          <v-col cols="3">
            <v-btn
              color="primary"
              @click="clickAddPackage"
              :disabled="!selectedPackage"
              small
              >
              <v-icon>mdi-plus</v-icon>
              Hinzufügen
            </v-btn>
          </v-col>
        </v-row>
      </v-container>
    </v-form>
    <ProgressDialog v-model="progress" :message="progressMessage" />
  </v-card>
</template>

<script>
  import axios from 'axios'
  import {mapActions} from "vuex";
  import ProgressDialog from "./ProgressDialog";

  const api = axios.create()

  export default {
    name: "PackageForm",
    components: {ProgressDialog},
    data: () => ({
      entries: [],
      isLoading: false,
      selectedPackage: null,
      search: null,
      packageType: undefined,
      progress: false,
      progressMessage: undefined,
      packageTypes: [
        {text: 'Module', value: 'oxideshop-module'},
        {text: 'Themes', value: 'oxideshop-theme'},
        {text: 'Alle Pakettypen', value: ''},
      ]
    }),

    computed: {
      items () {
        return this.search ? this.entries.concat([{name: this.search}]) : this.entries
          /*.map(entry => {
          const Description = entry.Description.length > this.descriptionLimit
            ? entry.Description.slice(0, this.descriptionLimit) + '...'
            : entry.Description

          return Object.assign({}, entry, { Description })
        })*/
      },
    },

    watch: {
      search (val) {
        this.timeout && clearTimeout(this.timeout)

        if (val) {
          this.isLoading = true
          this.timeout = setTimeout(() => {
            this.apiQuery()
          }, 400)
        }
      }
    },

    methods: {
      apiQuery () {
        // Lazily load input items
        api.get('https://packagist.org/search.json',{
          params: {
            q: this.search,
            type: this.packageType ? this.packageType.value : ''
          }
        })
          .then(res => {
            const { total, results } = res.data
            this.count = total
            this.entries = results
          })
          .catch(err => {
            console.log(err)
          })
          .finally(() => (this.isLoading = false))
      },
      async clickAddPackage () {
        if (this.selectedPackage) {
          this.progressMessage = undefined
          this.progress = true
          try{
            const result = await this.addPackage(this.selectedPackage)
            this.progressMessage = result
          }catch(err){
            this.progressMessage = err.response
            return
          }

        }
      },
      ...mapActions(['addPackage'])
    }

  }
</script>

<style scoped>

</style>
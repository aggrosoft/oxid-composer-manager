<template>
  <v-form>
    <v-container>
      <v-row>
        <v-col
            cols="6"
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
        <v-col
            cols="4"
        >
          <v-select
            label="Suchen nach"
            :items="packageTypes"
            v-model="packageType"
            return-object
          />
        </v-col>
        <v-col cols="2">
          <v-btn
            color="primary"
            @click="clickAddPackage"
            >
            Paket hinzufügen
          </v-btn>
        </v-col>
      </v-row>
    </v-container>
  </v-form>
</template>

<script>
  import axios from 'axios'
  import {mapActions} from "vuex";

  const api = axios.create()

  export default {
    name: "PackageForm",
    data: () => ({
      entries: [],
      isLoading: false,
      selectedPackage: null,
      search: null,
      packageType: undefined,
      packageTypes: [
        {text: 'Module', value: 'oxideshop-module'},
        {text: 'Themes', value: 'oxideshop-theme'},
        {text: 'Alles', value: ''},
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
      clickAddPackage () {
        this.selectedPackage && this.addPackage(this.selectedPackage.name)
      },
      ...mapActions(['addPackage'])
    }

  }
</script>

<style scoped>

</style>
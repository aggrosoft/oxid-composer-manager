<template>
  <v-form>
    <v-container>
      <v-row>
        <v-col
            cols="10"
        >
          <v-autocomplete
              v-model="model"
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
          ></v-autocomplete>
        </v-col>
        <v-col cols="2">
          <v-btn
            color="primary"
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

  const api = axios.create()

  export default {
    name: "PackageForm",
    data: () => ({
      entries: [],
      isLoading: false,
      model: null,
      search: null,
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
            q: this.search
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
    }

  }
</script>

<style scoped>

</style>
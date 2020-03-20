<template>
  <v-dialog
      :value="value"
      @input="onInput"
      width="1000"
      scrollable
  >
    <template v-slot:activator="{ on }">
      <v-btn
          color="deep-orange"
          class="mr-2"
          fab
          v-on="on"
      >
        <v-icon>mdi-source-repository</v-icon>
      </v-btn>
    </template>

    <v-card :loading="loading">
      <v-card-title
          class="headline"
          primary-title
      >
        Paketquellen bearbeiten
      </v-card-title>

      <v-card-text>
        <v-data-table
          :items="repositories"
          :headers="headers"

        >
          <template v-slot:item.action="{item}">
            <v-btn fab color="red darken-1" text icon small @click.stop="clickRemoveRepository(item)">
              <v-icon>
                mdi-delete
              </v-icon>
            </v-btn>
          </template>
        </v-data-table>

        <v-divider/>

        <v-form>
          <v-container fluid>
            <v-subheader>Paketquelle hinzufügen</v-subheader>
            <v-row align="center">
              <v-col
                  cols="4"
              >
                <v-select
                    label="Typ"
                    :items="repositoryTypes"
                    v-model="repositoryType"
                    prepend-icon="mdi-briefcase-search"
                    :return-object="false"
                    hide-details
                />
              </v-col>
              <v-col
                  cols="5"
              >
                <v-text-field
                  v-model="repositoryUrl"
                  label="URL"
                  hide-details
                  />
              </v-col>
              <v-col cols="3">
                <v-btn
                    color="primary"
                    class="mt-3"
                    @click="addRepository"
                    :disabled="!repositoryUrl"
                    small
                >
                  <v-icon>mdi-plus</v-icon>
                  Hinzufügen
                </v-btn>
              </v-col>
            </v-row>
          </v-container>
        </v-form>
      </v-card-text>

      <v-divider></v-divider>

      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn
            color="primary"
            text
            :disabled="!valid"
            @click="clickSaveComposerJson"
        >
          Speichern
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
  import {mapActions, mapGetters} from "vuex";

  export default {
    name: "ComposerRepositoriesDialog",
    props: ['value'],
    data: () => ({
      headers: [
        {text: 'Typ', value: 'type'},
        {text: 'URL', value: 'url'},
        { text: 'Aktionen', value: 'action', sortable: false },
      ],
      valid: true,
      loading: false,
      modifiedComposerJson: undefined,
      repositoryUrl: '',
      repositoryType: 'composer',
      repositoryTypes: [
        {value: 'composer', text: 'Composer'},
        {value: 'path', text: 'Pfad'},
        {value: 'git', text: 'GIT'},
      ]
    }),
    mounted: async function(){
      this.loading = true
      await this.getComposerJson()
      this.modifiedComposerJson = this.composerJson
      this.loading = false
    },
    computed: {
      repositories: function () {
        return this.modifiedComposerJson ? Object.entries(this.modifiedComposerJson.repositories).map(v => {
          return Object.assign({}, v[1], {key: v[0]})
        }) : []
      },
      ...mapGetters(['composerJson'])
    },
    methods: {
      onInput: function(val) {
        this.$emit('input', val)
      },
      clickSaveComposerJson: async function() {
        this.loading = true
        await this.saveComposerJson(JSON.stringify(this.modifiedComposerJson, null, 2))
        this.loading = false
        this.onInput(false)
        // this.output = false
        // this.output = await this.runCommand(this.cmd)
      },
      clickRemoveRepository: function (item) {
        let repositories = Object.assign({}, this.modifiedComposerJson.repositories)
        delete repositories[item.key]
        let modifiedComposerJson = Object.assign({}, this.modifiedComposerJson)
        modifiedComposerJson.repositories = repositories
        this.modifiedComposerJson = modifiedComposerJson
      },
      addRepository: function () {
        let repositories = Object.assign({}, this.modifiedComposerJson.repositories)
        const key = this.repositoryUrl.replace(/[\W_]+/g,"")
        repositories[key] = {
          type: this.repositoryType,
          url: this.repositoryUrl
        }
        let modifiedComposerJson = Object.assign({}, this.modifiedComposerJson)
        modifiedComposerJson.repositories = repositories
        this.modifiedComposerJson = modifiedComposerJson
        this.repositoryUrl = undefined
      },
      ...mapActions(['getComposerJson','setComposerJson', 'saveComposerJson'])
    },
    watch: {
      composerJson: function(val) {
        this.modifiedComposerJson = val
      }
    }
  }
</script>

<style scoped>

</style>
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
        <v-icon>mdi-file-cog</v-icon>
      </v-btn>
    </template>

    <v-card :loading="loading">
      <v-card-title
          class="headline"
          primary-title
      >
        Composer Datei bearbeiten
      </v-card-title>

      <v-card-text>
        <VuePrismEditor language="json" v-model="contents" />
      </v-card-text>

      <v-divider></v-divider>

      <v-card-actions>
        <v-alert
            v-if="!valid"
            border="right"
            colored-border
            type="error"
            elevation="2"
            dense
        >
          Die Datei enthält ungültiges JSON und kann nicht gespeichert werden
        </v-alert>
        <v-spacer></v-spacer>
        <v-btn
            color="warning"
            text
            @click="resetComposerJson"
        >
          Reset
        </v-btn>
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
  import "prismjs";
  import "prismjs/themes/prism.css";
  import VuePrismEditor from "vue-prism-editor";
  import "vue-prism-editor/dist/VuePrismEditor.css";
  import 'prismjs/components/prism-json';

  export default {
    name: "ComposerJsonDialog",
    props: ['value'],
    components: {VuePrismEditor},
    data: () => ({
      contents: '',
      valid: true,
      lastValidJson: '',
      loading: false
    }),
    mounted: async function(){
      this.loading = true
      this.lastValidJson = await this.getComposerJson()
      this.resetComposerJson()
      this.loading = false
    },
    computed: {
      ...mapGetters(['composerJson'])
    },
    methods: {
      onInput: function(val) {
        this.$emit('input', val)
      },
      clickSaveComposerJson: async function() {
        this.loading = true
        await this.saveComposerJson(this.contents)
        this.loading = false
        this.onInput(false)
        // this.output = false
        // this.output = await this.runCommand(this.cmd)
      },
      resetComposerJson: function () {
        this.contents = JSON.stringify(this.lastValidJson || this.composerJson, null, 2)
      },
      ...mapActions(['getComposerJson','setComposerJson', 'saveComposerJson'])
    },
    watch: {
      composerJson: function (val) {
        this.contents = JSON.stringify(val, null, 2)
      },
      contents: function (val) {
        try{
          const json = JSON.parse(val)
          this.setComposerJson(json)
          this.valid = true
          // eslint-disable-next-line no-empty
        }catch(e){this.valid = false}
      }
    }
  }
</script>

<style scoped>

</style>
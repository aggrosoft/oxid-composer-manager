<template>
  <v-dialog
      :value="value"
      @input="onInput"
      width="1000"
  >
    <template v-slot:activator="{ on }">
      <v-btn
          color="deep-orange"
          fab
          v-on="on"
      >
        <v-icon>mdi-file-cog</v-icon>
      </v-btn>
    </template>

    <v-card>
      <v-card-title
          class="headline"
          primary-title
      >
        Composer Datei bearbeiten
      </v-card-title>

      <v-card-text>
        <VuePrismEditor language="json" v-model="contents"/>
      </v-card-text>

      <v-divider></v-divider>

      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn
            color="primary"
            text
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
      cmd: '',
      output: false
    }),
    mounted: function(){
      this.getComposerJson()
    },
    computed: {
      contents: function () {
        return JSON.stringify(this.composerJson, null, 2)
      },
      ...mapGetters(['composerJson'])
    },
    methods: {
      onInput: function(val) {
        this.$emit('input', val)
      },
      clickSaveComposerJson: async function() {
        // this.output = false
        // this.output = await this.runCommand(this.cmd)
      },
      ...mapActions(['getComposerJson', 'saveComposerJson'])
    }
  }
</script>

<style scoped>

</style>
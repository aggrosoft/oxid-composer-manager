<template>
  <v-dialog
      :value="value"
      @input="onInput"
      width="1000"
  >
    <template v-slot:activator="{ on }">
      <v-btn
          color="deep-orange"
          class="mr-2"
          fab
          v-on="on"
      >
        <v-icon>mdi-console</v-icon>
      </v-btn>
    </template>

    <v-card>
      <v-card-title
          class="headline"
          primary-title
      >
        Composer Kommando ausführen
      </v-card-title>

      <v-card-text>
        Geben Sie ein beliebiges Composer Kommando ein, weitere Informationen finden Sie in der <a href='https://getcomposer.org/doc/03-cli.md' target='_blank'>Dokumentation</a>

        <v-text-field
            class="mt-3"
            label="Kommando"
            placeholder="z.B. composer update"
            v-model="cmd"
            outlined
            >
        </v-text-field>

        <textarea
          readonly
          v-if="output"
          v-model="output"
          class="pa-3 blue-grey darken-4 white--text"
          style="width:100%"
          rows="5"
          />

      </v-card-text>

      <v-divider></v-divider>

      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn
            color="primary"
            text
            @click="submitCommand"
        >
          Ausführen
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
  import {mapActions} from "vuex";

  export default {
    name: "CommandDialog",
    props: ['value'],
    data: () => ({
      cmd: '',
      output: false
    }),
    methods: {
      onInput: function(val) {
        this.$emit('input', val)
      },
      submitCommand: async function() {
        this.output = false
        this.output = await this.runCommand(this.cmd)
      },
      ...mapActions(['runCommand'])
    }
  }
</script>

<style scoped>

</style>
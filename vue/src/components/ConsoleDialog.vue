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
        <v-icon>mdi-apple-keyboard-command</v-icon>
      </v-btn>
    </template>

    <v-card>
      <v-card-title
          class="headline"
          primary-title
      >
        Oxid Console Kommando ausführen
      </v-card-title>

      <v-card-text>
        Wählen Sie das Kommando das ausgeführt werden soll

        <v-select
          v-model="cmd"
          :items="commands"
          item-text="title"
          :hint="`${selectedCommandString}`"
          item-value="command"
          persistent-hint
          return-object
        >
        </v-select>

        <v-text-field
            v-if="cmd && cmd.needsModulePath"
            class="mt-5"
            label="Modulpfad"
            placeholder="z.B. source/modules/meinmodul/"
            v-model="module"
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
    name: "ConsoleDialog",
    props: ['value'],
    data: () => ({
      cmd: undefined,
      module: undefined,
      commands: [
        {
          command: 'oe:module:install-configuration',
          title: 'Modulkonfiguration installieren',
          needsModulePath: true
        },
        {
          command: 'oe:module:apply-configuration',
          title: 'Modulkonfiguration anwenden',
          needsModulePath: true
        },
        {
          command: 'oe:module:reset-configurations',
          title: 'Modulkonfiguration zurücksetzen',
          needsModulePath: true
        },
        {
          command: 'oe:oxideshop-update-component:delete-module-data-from-database',
          title: 'Moduldaten aus der Datenbank löschen',
          needsModulePath: false
        },
        {
          command: 'oe:oxideshop-update-component:install-all-modules',
          title: 'Alle Module installieren',
          needsModulePath: false
        },
        {
          command: 'oe:oxideshop-update-component:transfer-module-data',
          title: 'Moduldaten übertragen',
          needsModulePath: false
        }
      ],
      output: false
    }),
    computed: {
      selectedCommandString: function () {
        return this.cmd ? this.cmd.command : ''
      },
      fullCommand: function () {
        return this.cmd && this.cmd.needsModulePath ? this.selectedCommandString + ' ' + this.module : this.selectedCommandString;
      }
    },
    methods: {
      onInput: function(val) {
        this.$emit('input', val)
      },
      submitCommand: async function() {
        this.output = false
        this.output = await this.runConsole(this.fullCommand)
      },
      ...mapActions(['runConsole'])
    }
  }
</script>

<style scoped>

</style>
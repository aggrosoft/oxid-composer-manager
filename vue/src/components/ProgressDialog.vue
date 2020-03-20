<template>
  <v-dialog
      v-model="dialog"
      @input="onInput"
      :persistent="!message"
      width="500"
  >
    <v-card
        color="primary"
        dark
    >
      <v-card-title v-if="!message">{{title}}</v-card-title>
      <v-card-title v-else>Befehlsausgabe</v-card-title>

      <v-card-text>

        <v-progress-linear
            indeterminate
            color="white"
            class="mb-0"
            v-if="!message"
        ></v-progress-linear>
        <textarea
            readonly
            v-if="message"
            v-model="message"
            class="mt-6 pa-3 blue-grey darken-4 white--text"
            style="width:100%"
            rows="5"
        />
      </v-card-text>
    </v-card>
  </v-dialog>
</template>

<script>
  export default {
    name: "ProgressDialog",
    data: function () {
      return  {
        dialog: false
      }
    },
    props: {
      value: Boolean,
      message: String,
      title: {
        type: String,
        default: 'Bitte warten ...'
      }
    },
    watch: {
      value: function(val){
        this.dialog = val
      },
      dialog: function(val){
        this.$emit('change', val)
      }
    },
    methods: {
      onInput: function(val) {
        this.$emit('input', val)
      }
    }
  }
</script>

<style scoped>

</style>
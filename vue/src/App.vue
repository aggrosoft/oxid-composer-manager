<template>
  <v-app id="inspire">
    <v-app-bar
        app
        color="indigo"
        dark
    >
      <v-toolbar-title>Paketverwaltung</v-toolbar-title>
    </v-app-bar>

    <v-content>
      <v-container
          class="fill-height"
          fluid
      >
        <v-row
            align="center"
            justify="center"
        >
          <v-col class="text-center">
            <PackageList />
          </v-col>
        </v-row>
      </v-container>
    </v-content>
    <v-footer
        color="indigo"
        app
    >
      <span class="white--text">&copy; 2020 <a href="https://www.aggrosoft.de">Aggrosoft GmbH</a></span>
    </v-footer>
  </v-app>
</template>

<script>
  import PackageList from "./components/PackageList";

  export default {
    components: {PackageList},
  }
</script>
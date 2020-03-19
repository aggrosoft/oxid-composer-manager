<template>
  <v-app id="inspire">
    <v-app-bar
        app
        color="indigo"
        dark
    >
      <v-toolbar-title>Paketverwaltung</v-toolbar-title>
      <v-spacer />
      <CommandDialog />
    </v-app-bar>

    <v-content>
      <v-container
          class="fill-height"
      >
        <v-row
        >
          <v-col  cols="12" class="text-center">
            <PackageList />
          </v-col>
        </v-row>
        <v-row
        >
          <v-col cols="12">
            <PackageForm />
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
  import PackageForm from "./components/PackageForm";
  import CommandDialog from "./components/CommandDialog";

  export default {
    components: {CommandDialog, PackageForm, PackageList},
  }
</script>
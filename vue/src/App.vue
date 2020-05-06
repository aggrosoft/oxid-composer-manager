<template>
  <v-app id="inspire">
    <v-app-bar
        app
        color="secondary"
        dark
    >
      <v-toolbar-title>Paketverwaltung</v-toolbar-title>
      <v-spacer />
      <ComposerJsonDialog />
      <ComposerRepositoriesDialog />
      <CommandDialog />
      <ConsoleDialog />
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
        color="secondary"
        app
    >
      <span class="white--text">&copy; 2020 <a href="https://www.aggrosoft.de" class="white--text">Aggrosoft GmbH</a></span>
    </v-footer>
  </v-app>
</template>

<script>
  import PackageList from "./components/PackageList";
  import PackageForm from "./components/PackageForm";
  import CommandDialog from "./components/CommandDialog";
  import ComposerJsonDialog from "./components/ComposerJsonDialog";
  import ComposerRepositoriesDialog from "./components/ComposerRepositoriesDialog";
  import ConsoleDialog from "./components/ConsoleDialog";

  export default {
    components: {ConsoleDialog, ComposerRepositoriesDialog, ComposerJsonDialog, CommandDialog, PackageForm, PackageList},
  }
</script>
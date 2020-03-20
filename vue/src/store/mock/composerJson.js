export default {
  "name": "oxid-esales/oxideshop-project",
  "type": "project",
  "description": "This file should be used as an OXID eShop project root composer.json file. Entries provided here intended to be examples and could be changed to your specific needs.",
  "license": [
    "GPL-3.0-only"
  ],
  "minimum-stability": "stable",
  "require": {
    "oxid-esales/oxideshop-metapackage-ce": "v6.1.5",
    "shirtnetwork/designer-oxid": "^1.1.5",
    "aggrosoft/oxid-force-full-categories": "^1.0",
    "aggrosoft/oxid-basket": "^1.0"
  },
  "repositories": {
    "0": {
      "type": "composer",
      "url": "https://packages.aggrosoft.de"
    },
    "bla": {
      "type": "composer",
      "url": "https://packages.example.org"
    },
    "netensio/net_cookie_manager": {
      "type": "path",
      "url": "extensions/net_cookie_manager"
    }
  },
  "require-dev": {
    "oxid-esales/testing-library": "^v5.0.4",
    "oxid-esales/coding-standards": "^v3.0.5",
    "incenteev/composer-parameter-handler": "~v2.0",
    "oxid-esales/oxideshop-ide-helper": "^v3.1.2",
    "oxid-esales/azure-theme": "^v1.4.2"
  },
  "autoload-dev": {
    "psr-4": {
      "OxidEsales\\EshopCommunity\\Tests\\": "./vendor/oxid-esales/oxideshop-ce/tests"
    }
  },
  "scripts": {
    "post-install-cmd": [
      "Incenteev\\ParameterHandler\\ScriptHandler::buildParameters",
      "@oe:ide-helper:generate"
    ],
    "post-update-cmd": [
      "Incenteev\\ParameterHandler\\ScriptHandler::buildParameters",
      "@oe:ide-helper:generate"
    ],
    "oe:ide-helper:generate": [
      "if [ -f ./vendor/bin/oe-eshop-ide_helper ]; then oe-eshop-ide_helper; fi"
    ]
  },
  "config": {
    "preferred-install": {
      "*": "dist"
    }
  },
  "extra": {
    "incenteev-parameters": {
      "file": "test_config.yml",
      "dist-file": "vendor/oxid-esales/testing-library/test_config.yml.dist",
      "parameter-key": "mandatory_parameters",
      "env-map": {
        "shop_path": "SHOP_PATH",
        "shop_tests_path": "SHOP_TESTS_PATH",
        "partial_module_paths": "PARTIAL_MODULE_PATHS"
      }
    }
  }
}
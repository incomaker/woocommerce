# Incomaker Woo Commerce plugin

Plugin for WordPress downloadable from `https://www.incomaker.com/sites/default/files/plugin/woocommerce/incomaker.zip`.

Installation instructions are [here](https://support.incomaker.com/en/hc/2628921009/7/woocommerce).

Provides feeds on URL `/wp-json/incomaker/v210/feed/?type=...` and calls Incomaker API on certain actions.

## Run and Debug in Docker

Start Docker container:

    sudo docker compose up

First you will have to activate WordPress installation by completing the wizard.

Next you will have to install and activate Woo Commerce plugin. (via web interface)

Run `composer` to create and update `vendor` folder.

Finally, activate Incomaker plugin which is already listed in available plugins.

Now you have fresh WordPress installation with WooCommerce and Incomaker, happy debugging.

Fresh WordPress installation is now running on `http://localhost:8081`. Admin console is on `http://localhost:8081/wp-admin` 
and e-shop on `http://localhost:8081/shop` 

### Data Feeds

Products data feed example: 
`http://localhost:8081/wp-json/incomaker/v210/feed/?type=product&key=312e57de-f7e9-4d9a-a3a5-c5a5b455a741`

You will have to change permalinks settings (http://localhost:8081/wp-admin/options-permalink.php) to make pretty feed URLs work!

Without pretty links:
`http://localhost:8081/?rest_route=/incomaker/v210/feed/&type=product&key=312e57de-f7e9-4d9a-a3a5-c5a5b455a741`

### Wordpress v. 5.7

Description above is only valid for WordPress v. 6.2 and newer. To use version 5.7 you will have to download, install and activate Woo Commerce plugin manually.
(older versions can be downloaded here: https://wordpress.org/plugins/woocommerce/advanced/)

Put the plugin folder to `wordpress-5.7/woocommerce`.

Start Docker container:

    cd wordpress-5.7
    sudo docker compose up

Notice embedded config file `wp-config.php` - settings were added to prevent it from auto-updating to newest version.

Run `composer` to create and update `vendor` folder.

Activate Incomaker plugin which is already listed in available plugins.

## Build and distribute

### For direct download

Wordpress plugins are distributed as `zip` archives downloaded from `www.incomaker.com`.

Archives are placed in folder `/var/www/incomaker.com/https_www/public_html/sites/default/files/plugin/woocommerce` on `www` server.

There is separate archive for each version and also archive called `incomaker.zip` with the latest version. 

### For Wordpress Marketplace

We have account on `wordpress.org` registered with username `incomakermarketing` and email `support@incomaker.com`.

Currently, we are waiting for plugin approval. Status of review process can be checked [here](https://wordpress.org/plugins/developers/add/).

# Incomaker Woo Commerce plugin

## Run and Debug in Docker

### Wordpress v. 6.2 and newer

Start Docker container:

    sudo docker compose up

First you will have to activate WordPress installation by completing the wizard.

Next you will have to install and activate Woo Commerce plugin. (via web interface)

Run `composer` to create and update `vendor` folder.

Finally activate Incomaker plugin which is already listed in available plugins.

Now you have fresh WordPress installation with WooCommerce and Incomaker, happy debugging.

Fresh WordPress installation is now running on `http://localhost`. Admin console is on `http://localhost/wp-admin` 
and e-shop on `http://localhost/shop` 

### Wordpress v. 5.7

You will have to download, install and activate Woo Commerce plugin manually.
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

We have account on `wordpress.org` registered with email `incomakermarketing@gmail.com`.

Currently we are waiting for plugin approval.

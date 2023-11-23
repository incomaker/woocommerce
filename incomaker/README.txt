== Incomaker ===

Contributors: incomaker
Tags: marketing, emailing, popups, marketing automation
Requires PHP: 5.6.0
Stable tag: 2.1.4
Requires at least: 4.7
Tested up to: 6.3.2
License: GPL v3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Intelligent multichannel marketing automation. Emailing, SMS, on-site widgets, and social media.

== Description ==

Incomaker is a marketing automation solution with artificial intelligence. It collects data from e-shops and, by using it, it automates retention marketing channels.

In order to use this plugin, you must have an active Incomaker account. After linking the plugin with your account, various data from your e-commerce site will be sent to Incomaker for use in your campaigns and data analysis.

To register an account, visit https://my.incomaker.com.

Before you start using our services, please read our Terms & Conditions (https://www.incomaker.com/en/terms-and-conditions) and Privacy Policy (https://www.incomaker.com/en/privacy).

== FAQ ==

= Can I use the plugin without an Incomaker account? =

Not really. While you can still install the plugin, without linking it to your account, no data will be sent to Incomaker and you will not be able to use any of the services we offer.

= Do I have to pay before I start using Incomaker? =

No, you only need to create an account to get started. Next to paid services, we also offer free services, trial periods and flexible pricing models.

= Will the plugin start automatically sending data to Incomaker after installation? =

No, you will have to link the plugin to your account first. So installation alone will not trigger any data transfer.

= Can I stop sending data to Incomaker anytime? =

Yes, you can stop sending data anytime by simply uninstalling the plugin or breaking the link to your account.

== Extensions ==

There are following defined filters and hooks that you can use to modify output.

incomaker_output_xml_feed_filter
Arguments:
    string: XML feed
    string : type of the feed - "contact", "product", "order", "category"
Returns:
    string: XML feed for output / false for error

You can use this filter to modify XML output of the plugin.

incomaker_modify_xml_contact_item
Arguments:
    ContactExport : controller
    SimpleXMLElement : child element of the item
    WC_Customer : object of the item

You can use this action to adjust contact item on output.

incomaker_modify_xml_product_item
Arguments:
    ProductExport : controller
    SimpleXMLElement : child element of the item
    WC_Product : object of the item

You can use this action to adjust product item on output.

incomaker_modify_xml_order_item
Arguments:
    OrderExport : controller
    SimpleXMLElement : child element of the item
    WC_Order : object of the item

You can use this action to adjust order item on output.

incomaker_modify_xml_category_item
Arguments:
    CategoryExport : controller
    SimpleXMLElement : child element of the item
    WP_Term : object of the item

You can use this action to adjust category item on output.

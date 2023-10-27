== Incomaker ===

Contributors: incomaker
Tags: marketing, emailing, popups, marketing automation
Requires PHP: 5.6.0
Stable tag: 2.1.2
Requires at least: 4.7
Tested up to: 6.3.2
License: GPL v3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Intelligent multichannel marketing automation. Emailing, SMS, on-site widgets, and social media.

== Description ==

Incomaker is a marketing automation solution with artificial intelligence. It collects data from e-shops, and using it; it automates retention marketing channels.
Content Generators

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

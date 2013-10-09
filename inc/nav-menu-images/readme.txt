=== Nav Menu Images ===
Contributors: dimadin
Donate link: http://blog.milandinic.com/donate/
Tags: nav menu, menu, media, image
Requires at least: 3.1
Tested up to: 3.6
Stable tag: 3.1

Display image as a menu item content.

== Description ==

[Plugin homepage](http://blog.milandinic.com/wordpress/plugins/nav-menu-images/) | [Plugin author](http://blog.milandinic.com/) | [Donate](http://blog.milandinic.com/donate/)

This plugin enables you to upload images for nav menu items on a menu edit screen. By default, those images will be displayed instead of text for respective menu items. Note that after upload, you should set an image as 'featured' to be able to display it.

You can also set images that will be displayed only when you hover menu item, or when menu item is of currently displayed page. [Read detailed instructions about using plugin](http://blog.milandinic.com/wordpress/plugins/nav-menu-images/#how-to-use).

Developers can use many available filters to make their own way of displaying images, or even create a child class on top of base one. See source code for more ideas.

Although this plugin displays uploaded images out of the box, it will probably not give best possible look, so it's recommended to create custom CSS styles for affected elements.

Nav Menu Images code is partly based on a code from now defunct plugin Custom Menu Images by [Anabelle Handdoek
](http://huellaspyp.com/)/[∞manos s.a.s](http://8manos.com/) and a code from plugin [Metronet Profile Picture](http://wordpress.org/extend/plugins/metronet-profile-picture/) by [Ronald Huereca](http://www.ronalfy.com/)/[Metronet Norge AS](http://www.metronet.no/).

== Installation ==

Either install the plugin via the WordPress admin panel, or ...

1. Upload `nav-menu-images` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

There are no configuration options in this plugin.

== Screenshots ==

1. Link to upload form on nav menu item's edit screen
2. Setting featured image from media modal screen
3. Uploaded image on nav menu item's edit screen
4. Setting image used on hover from media modal screen

== Changelog ==

= 3.1 =
* Released on 12th August 2013
* Don't cache media frame and instead load new each time
* Don't overwrite `wp.media.view.settings` but extend it, and only with post data
* Force listing of images uploading only to current post 

= 3.0 =
* Released on 26th March 2013
* Add support for active and hover menu item images

= 2.0 =
* Released on 11th February 2013
* Use new media views for WordPress 3.5+
* Add more hooks for developers

= 1.0 =
* Released on 22nd October 2012
* Initial release
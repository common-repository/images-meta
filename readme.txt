=== Images Meta ===

Contributors: sfraser657
Donate link: http://scuba-net.org/blog/plugin/images-meta/
Tags: images, metabox, image management, thumbnail, gallery, attachment, quick edit
Requires at least: 3.0.0
Tested up to: 3.1
Stable tag: 1.0

Adds 'Images' metaboxes to the wordpress post editor for better image management.

== Description ==
This is a simple metabox to make it easier to manage images in wordpress. It allows you to enter a title, caption & menu-order for an image as well as set thumbnail without the complexity of the standard wordpress image system.  It has been a long-standing complaint of mine that there is no simple, straightforward way to quick-edit info for images attached to wordpress posts.  Now there is :) ( See Screenshot 1 )

It also includes a ‘detach’ & ‘delete’ function, and adds in ‘Detach’ & ‘Retach’ options in the Media Library view.
( See Screenshot 3)

To provide a means of adding new images, I’ve included the experimental “All Images” metabox.  This will display a list of all currently unattached images and allow you to edit caption & description and attach or delete. (See Screenshot 2)

Designed to work by default with my colorbox wordpress plugin (which is just a very basic adaptation of the jquery plugin).  That should be available soon from my web site.  

This DOES NOT provide a means to upload new files.  You’ll have to stick with current wordpress methods to do that, for now.  I would like to add on a flash uploader in a future version.

Please contact me if you find this useful, have any suggestions, or when you find bugs!  If you like this plugin, consider buying me a coffee :) 
== Installation ==

1. Install via wordpress back-end or upload
2. Activate
3. Use.

Optional: Place a variation of this code in your theme's functions.php file (or wherever) to configure this plugin

global $_images_post_types, $_all_images;
$_images_post_types = array( 'page', 'post' ); // Posts types to use for images meta plugin
$_all_images = 0; // Show 'All Images' metabox when using images meta plugin

== Screenshots ==

1. Images metabox lets you edit title, caption and menu order for all attached images, as well as detach or delete an image.
2. The "All Images" metabox lets you manage unattached images too.  Change title & captions, attach, or delete images directly from the post editor.
3. Also adds 'detach' and 're-attach' functions to the Media Library columns
4. Click the thumbnails for a close-up, if used in combination with colorbox 
	eg. $('a[rel=colorbox]').colorbox() );

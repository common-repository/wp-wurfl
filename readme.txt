=== WP-Wurfled ===
Contributors: mch
Tags: wml,xhtmlmp, mobile, wurfl, post, theme
Requires at least: 2.7
Tested up to: 2.9
Stable tag: 0.0.2

WML and XHTML-MP extension for Wordpress, allowing mobile devices to access your blog.The device recognition is performed by WURFL.  

== Description ==
WML and XHTML-MP extension for Wordpress, allowing mobile devices to access your blog.  
Device is recognised by WURFL and based on the capability, proper theme is choosed.
Themes are based on original Wordpress PDA & iPhone plugin, by Imthiaz Rafiq (http://imthi.com/) 


== Installation ==

1. Upload, unzip to wp-content/plugins/wp-wurfled.
2. Download Wurfl Device definition, place the XML file to wp-wurfled/wurfl/data/wurfl.xml
3. Download Wurfl web browser patch and place the XML file to wp-wurfled/wurfl/data/web_browsers_patch.xml
4. Activate WP-Wurfled , Go to Options > WP-Wurfled
5. Allow PHP to write to the wp-content/plugins/wp-wurfled/wurfl/data/
6. !!!Generate WURFL Cache - under Settings - WP-Wurfled - button Regenerate Cache!!!
* instead of steps 2,3 you may download both files packed from http://www.demoru.com/wp-content/uploads/wurfl_data.zip

== Frequently Asked Questions ==

The posts are converted for WML format. Still may contain bugs.

Translated into English and Czech.

Known bugs:
* WML deck size problems.
* no Image resize
* WML doesn't supports comments

== Screenshots ==
None yet

== Changelog ==
= 0.0.2 =
* Readme.txt fixes, process clarification
* added ini_set('memory_limit') to 64 MB for cache generation

= 0.0.1 =
* WML Parser implementation
* WML and XHTML-MP themes
* Handset/Manufacturer database

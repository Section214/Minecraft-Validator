=== Minecraft Validator ===
Contributors: Ghost1227
Donate link: http://pledgie.com/campaigns/16297
Tags: registration, register, minecraft
Requires at least: 3.1
Tested up to: 4.0.0
Stable tag: 2.0.1

Authenticate new users against the minecraft.net valid user database.

== Description ==

Minecraft Validator is a simple plugin which authenticates new users against the minecraft.net valid user database. If the name is not verified, they are not permitted to register.

Right now, Minecraft Validator is *very* simplistic. However, I'd like to see it become much more. In fact, I'd like to see it grow into a Minecraft 'suite' for WordPress. The only problem is, I'm honestly not sure what all it should do. Any suggestions from my users would be wonderful.

== Installation ==

1. Unzip the downloaded 'minecraft-validator.zip' file
2. Upload the 'minecraft-validator' folder to '/wp-content/plugins' directory of your WordPress installation
3. Activate the plugin via the WordPress Plugins page

== Frequently Asked Questions ==

None yet

== Screenshots ==

1. Demo of the login/register screen

== Changelog ==
= Version 2.0.1 =
* Fix Mojang API

= Version 2.0.0 =
* Complete codebase overhaul
* Added Multisite support

= Version 1.4 =
* Deprecated CURL in favor of wp_remote_get()

= Version 1.3 =
* Major code optimization
* Fixed login page rewrite issue
* Deprecated URL File-Access in favor of CURL

= Version 1.2 =
* Fixed Minecraft check bug
* Added handler for minecraft.net being down
* Started the basics for options
* Added donation link

= Version 1.1 =
* I can't follow directions...

= Version 1.0 =
* Initial release

== Upgrade Notice ==

= Version 1.2 =
* Critical update! Fixed a bug which prevented Minecraft Validator from properly authenticating against the Minecraft database.

== Thanks ==

Huge thanks to sterlo from #wordpress for all the help sorting out my jQuery issue.

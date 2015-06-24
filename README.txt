Make your own movie/tv-show streaming website!

Designed for NAS hardware. All PHP, no MySQL needed.

Created by NeWaGe
http://newagesoldier.com


FUNCTIONS.INC.PHP FILE
===========================
- handles settings from settings.ini.php file and distributes to all class files
- processes required class files


INDEX.PHP FILE
===========================
- processes all page requests including post and get
- processes custom template requests from settings
- starts sessions for all pages


TEMPLATE REPLACEMENTS
===========================
- replacement function in includes/class/template.class.php
- strings located in includes/class/template.class.php
- import function handles all template replacements including languages
- language and template replacements can be handled in both the template.class.php and custom html template files


FEATURES
[X] make user login/register/profile database system
[_] user modification options in admin panel like ban by IP, delete account, change pass etc.
[_] restrict videos to account ratings (parental controls)
[X] create custom templates system
[X] create language system
[_] create payment renewal system or at least monthly expiration system option
[_] more video streaming options (flash, windows media, HTML5, etc.)


CHANGELOG
============================================================================


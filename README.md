# Libr8
 an easy to use Free Open Source Content Management System.

### Features
* Blog, Portfolio's, Bookings, Events, News, Testimonials, Inventory, Services, Gallery, Proofs
* Integration of External Services, such as Google Analytics, Pinterest Verification, Bing Verification, and so on.
* Messaging - Whenever a message is created via the Contact Us page, it is stored in the Messages system as well as emailed.
* Orders - Create Quotes, Invoices, and recurring Orders. Client viewing of Orders.
* Media - Upload and manage various types of files for addition into content.
* Accounts - Create Accounts for co-workers with Account Types for Administrators, Editors (especially good for SEO and Copywriters), Client's, and Visitors.
* Client Proofs and Commenting
* Easy Theme Selector

Please submit issue's here at GitHub, this is so we can track, and update issue's more efficiently.

You can now get themes from our Themes GitHub Repository @ <https://github.com/StudioJunkyard/Libr8-themes>

### Dependencies
* Bootstrap
* jQuery+Bootstrap's Jquery Modules <http://jquery.com/> (Depends on Theme)
* PHP/PDO (Tested with MySQL)
* mod_rewrite

#### PHP Addons used:
* PHPMailer <https://github.com/PHPMailer/PHPMailer>
* TCPDF <http://www.tcpdf.org/>
* Zebra_Image <https://github.com/stefangabos/Zebra_Image>

jQuery Addons depend on Theme:

### Tested on:
* Ubuntu 14.04 + Apache v2.4.7 + PHP v5.5.9-1 + MySQL v5.5.37
* Linux Mint Debian Edition
* Debian 7 + nGinx + PHP 5.4 + MySQL
* Windows 7 + WAMP + PHP 5.5 + MySQL

### Setup:
Libr8 uses PHP's PDO for Database integration. So all you need to do, is use a Database Engine that's compatible with PDO. Then import the "libr8.sql" file found in the root folder, then edit the "config.ini" file in the "includes/" folder.
Remember to set the appropriate file and folder permissions for security purposes, and make sure "media" uses 0755 so files can be uploaded when editing or creating content.

### NOTE:
* This is currently a work in progress, and being converted to an MVC System. If you download and use Libr8 in a Production Environment, you do so at your own risk.
* We openly welcome help with MVC Conversion, and bug squashing.

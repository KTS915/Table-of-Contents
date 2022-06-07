=== Table of Contents ===
Description: Provides an accessible table of contents for posts
Author: Tim Kaye
Author URI: https://timkaye.org
Contributors: KTS915, xxsimoxx
Plugin URI: https://timkaye.org
Tags: table of contents, classicpress
Tested up to: 4.9.99
Requires PHP: 7.0
Requires at least: 4.9.15
Version: 0.5.2
Download link: https://github.com/KTS915/Table-of-Contents/releases/download/v0.5.2/kts-table-of-contents.zip
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

== Description ==
Once activated, the plugin automatically inserts a table of contents (ToC) on every post just before the post content. The ToC will include every `h2`, `h3`, and `h4` heading. The plugin uses the `details` and `summary` HTML tags to ensure that the ToC is both fully accessible and collapsible without requiring any JavaScript.

== Shortcode ==
The plugin also provides an optional [kts_toc] shortcode that is designed to be placed inside a custom HTML widget, which can then be named manually (e.g. Table of Contents). In this case, the `details` and `summary` HTML tags are replaced by `nav` tags to maintain accessibility, and the ToC will not be collapsible. This widget relies on some JavaScript, but it is so tiny (less than 150 bytes) that it will have no discernible effect on your page loading time.

== Configuration ==
The plugin adds a metabox, headed Table of Contents, on the post edit screen. Checking the box marked "Do not display" and then hitting Update will ensure that no ToC appears on that page.

It is also possible to hide the ToC using CSS, like this: `#toc-container { display: none; }`

Similarly, if you wish to use the shortcode but also wish to hide the ToC it generates at certain viewport widths, you can add this line to an appropriate section within your CSS file or Customizer CSS: `#toc-nav-container { display: none; }`

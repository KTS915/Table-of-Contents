=== Table of Contents ===
Contributors: KTS915
Tags: table of contents, classicpress
Requires at least: CP 1.4.1
Tested up to: CP 1.4.1
Requires PHP: 7.0
Stable tag: 0.1.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Provides an accessible table of contents for posts.

== Description and Usage ==
Once activated, the plugin automatically inserts a table of contents (ToC) on every post just before the post content. The ToC will include every h2, h3, and h4 heading. The plugin uses the <details> and <summary> HTML tags to ensure that the ToC is both fully accessible and collapsible without requiring any JavaScript.

The plugin also provides an optional [kts_toc] shortcode that is designed to be placed inside a custom HTML widget, which can then be named manually (e.g. Table of Contents). In this case, the <details> and <summary> HTML tags are replaced by <nav> tags to maintain accessibility, and the TOC will not be collapsible. This widget relies on some JavaScript, but it is so tiny (less than 150 bytes) that it will have no discernible effect on your page loading time.

There are no configuration options in this initial version. Such options may be added in future if user demand warrants.

If, however, you wish to hide the automatic ToC, you can add this line to an appropriate section within your CSS file or Customizer CSS: #toc-container { display: none; }

Similarly, if you wish to use the shortcode but also wish to hide the ToC it generates at certain viewport widths, you can add this line to an appropriate section within your CSS file or Customizer CSS: #toc-nav-container { display: none; }

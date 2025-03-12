# KTS-Display-Widgets
Author:            Tim Kaye

Version:           0.7.0

Requires CP:       2.1

Requires at least: 6.2.3

Requires PHP:      7.4

## Description
Provides an accessible, automatically-generated table of contents for all posts, with options to disable per post, set the label for the ToC, and to have the ToC be initially open or closed. There is also a shortcode that can be added to a widget.

## Description
Once activated, the plugin automatically inserts a table of contents (ToC) on every post just before the post content. The ToC will include every `h2`, `h3`, and `h4` heading. The plugin uses the `details` and `summary` HTML tags to ensure that the ToC is both fully accessible and collapsible without requiring any JavaScript.

## Configuration
The plugin has a Settings page, where you can set the title of the ToC. The default is "Table of Contents" (without the quotes).

The settings page also allows you to decide whether you want the ToC to initially be open or closed. The default is closed.

The plugin also adds a metabox, headed Table of Contents, on each post's edit screen. Checking the box marked "Do not display" and then hitting Update will ensure that no ToC appears on that page.

It is also possible to hide the ToC using CSS, like this: `#toc-container { display: none; }`

## Shortcode
The plugin also provides an optional [kts_toc] shortcode that is designed to be placed inside a custom HTML widget, which can then be named manually (e.g. Table of Contents). In this case, the `details` and `summary` HTML tags are replaced by `nav` tags to maintain accessibility, and the ToC will not be collapsible. This widget relies on some JavaScript, but it is so tiny (less than 150 bytes) that it will have no discernible effect on your page loading time.

If you wish to use the shortcode but also wish to hide the ToC it generates at certain viewport widths, you can add this line to an appropriate section within your CSS file or Customizer CSS: `#toc-nav-container { display: none; }`

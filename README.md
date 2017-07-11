# Castlegate IT WP Conditional Downloads

Castlegate IT WP Conditional Downloads can be used to restrict access to any files uploaded to the WordPress media gallery. By default, the plugin provides a simple "restrict access to this file" checkbox for each file, which restricts access to registered users. The checkbox is added with [Advanced Custom Fields](https://www.advancedcustomfields.com/).

## Custom restrictions

You can edit the file attachment restrictions via the `cgit_condo_attachment_restricted` filter:

~~~ php
add_filter('cgit_condo_attachment_restricted', function ($restricted, $attachment) {
    return $restricted;
}, 10, 2);
~~~

This function should return a boolean value that will be used to determine whether access to the file should be restricted. The attachment itself is provided as a `WP_Post` instance in the second function parameter.

You can also edit whether the current user is allowed to access the file or not via the `cgit_condo_user_permitted`:

~~~ php
add_filter('cgit_condo_user_permitted', function ($permitted, $attachment, $user) {
    return $permitted;
}, 10, 3);
~~~

This should return a boolean that will be used to determine whether the current user is permitted to download the file. The attachment and user are provided as `WP_Post` and `WP_User` instances respectively.

## Custom messages

The title, message, and HTTP response code of the "access denied" page can be edited via the `cgit_condo_forbidden_title`, `cgit_condo_forbidden_message`, and `cgit_condo_forbidden_code` filters respectively.

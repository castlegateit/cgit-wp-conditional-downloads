<?php

namespace Cgit\Condo;

class Loader
{
    /**
     * Unique identifier used as the GET key
     *
     * @var string
     */
    private $name;

    /**
     * Requested file path relative to ABSPATH
     *
     * @var string
     */
    private $request;

    /**
     * Attachment
     *
     * @var WP_Post
     */
    private $attachment;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct($name)
    {
        require_once ABSPATH . 'wp-includes/pluggable.php';

        $this->name = $name;
        $this->request = isset($_GET[$name]) ? $_GET[$name] : '';

        // Try to find the requested file in the database
        $this->getAttachment();

        // Do something with the request. If the requested file does not exist,
        // send a 404. If the requested file is restricted, check the
        // restrictions. If it does exist and we are allowed to send it to the
        // user, send it.
        $this->processRequest();
    }

    /**
     * Process a request for a file
     *
     * Checks that the file exists and that the user is allowed to download it
     * before sending it.
     *
     * @return void
     */
    private function processRequest()
    {
        // If no file has been requested or the file does not exist in the
        // database, carry on like nothing happened.
        if (!$this->request || !$this->attachment) {
            return;
        }

        // If we are viewing the file from the admin interface or if the current
        // user is permitted to view the file, send the file.
        if (is_admin() || $this->userPermitted()) {
            return $this->send();
        }

        // Send a mild rebuke
        $this->nope();
    }

    /**
     * Get the requested attachment from the database
     *
     * @return void
     */
    private function getAttachment()
    {
        global $wpdb;

        $table = $wpdb->posts;
        $query = "SELECT ID FROM $table WHERE guid LIKE '%%%s'";
        $post_id = $wpdb->get_var($wpdb->prepare($query, $this->request));

        // If there is no post ID for the requested file and so the file is not
        // an attachment, check whether it is a resized version of an existing
        // attachment by searching for it within the postmeta table.
        if (is_null($post_id)) {
            $name = basename($this->request);
            $table = $wpdb->postmeta;
            $query = "SELECT post_id FROM $table
                WHERE meta_key = '_wp_attachment_metadata'
                AND meta_value LIKE '%%%s%%'";
            $post_id = $wpdb->get_var($wpdb->prepare($query, $name));
        }

        $this->attachment = get_post($post_id);
    }

    /**
     * Send the requested file
     *
     * @return void
     */
    private function send()
    {
        $path = ABSPATH . $this->request;
        $name = basename($this->attachment->guid);
        $type = $this->attachment->post_mime_type;
        $size = filesize($path);

        header('Content-Disposition: inline; filename=' . $name);
        header('Content-Type: ' . $type);
        header('Content-Length: ' . $size);

        $file = fopen($path, 'r');

        fpassthru($file);
        fclose($file);

        exit;
    }

    /**
     * Don't send the requested file
     *
     * @return void
     */
    private function nope()
    {
        wp_die('You do not have permission to view this file.',
            'Access denied', ['response' => 403]);
    }

    /**
     * Is this a restricted attachment?
     *
     * @return boolean
     */
    private function attachmentRestricted()
    {
        $post_id = $this->attachment->ID;
        $name = $this->name . '_restricted';
        $restricted = intval(get_post_meta($post_id, $name, true)) == 1;

        // Allow restrictions to be modified via filters
        $restricted = apply_filters('cgit_condo_attachment_restricted',
            $restricted, $this->attachment);

        return $restricted;
    }

    /**
     * Are we permitted to send the attachment to this user?
     *
     * @return boolean
     */
    private function userPermitted()
    {
        $user = wp_get_current_user();
        $permitted = false;

        if (is_user_logged_in() || !$this->attachmentRestricted()) {
            $permitted = true;
        }

        // Allow permissions to be modified via filters
        $permitted = apply_filters('cgit_condo_user_permitted', $permitted,
            $this->attachment, $user);

        return $permitted;
    }
}

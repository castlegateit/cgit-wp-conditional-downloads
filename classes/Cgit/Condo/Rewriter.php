<?php

namespace Cgit\Condo;

class Rewriter
{
    /**
     * Unique identifier
     *
     * @var string
     */
    private $name;

    /**
     * Configuration file to edit
     *
     * @var string
     */
    private $config;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct($name)
    {
        $this->name = $name;
        $this->config = ABSPATH . '.htaccess';
    }

    /**
     * Enable media request rewrites
     *
     * @return void
     */
    public function enable()
    {
        $this->write([
            'RewriteCond %{REQUEST_FILENAME} -f',
            'RewriteCond %{REQUEST_URI} ^/wp-content/uploads/.*$ [NC]',
            'RewriteRule ^(.*)$ ?' . $this->name . '=$1',
        ]);
    }

    /**
     * Disable media request rewrites
     *
     * @return void
     */
    public function disable()
    {
        $this->write([]);
    }

    /**
     * Write lines to configuration file
     *
     * @param mixed $lines
     * @return void
     */
    private function write($lines)
    {
        require_once ABSPATH . 'wp-admin/includes/misc.php';

        if (!is_array($lines)) {
            $lines = explode("\n", $lines);
        }

        insert_with_markers($this->config, $this->name, $lines);
    }
}

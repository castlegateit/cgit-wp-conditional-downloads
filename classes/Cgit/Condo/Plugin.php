<?php

namespace Cgit\Condo;

class Plugin
{
    /**
     * Unique identifier
     *
     * @var string
     */
    private $name;

    /**
     * Rewriter instance
     *
     * @var Rewriter
     */
    private $rewriter;

    /**
     * File loader instance
     *
     * @var Loader
     */
    private $loader;

    /**
     * Custom field maker instance
     *
     * @var CustomFieldMaker
     */
    private $customFieldMaker;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $plugin = CGIT_CONDO_PLUGIN;
        $this->name = pathinfo($plugin, PATHINFO_FILENAME);

        $this->rewriter = new Rewriter($this->name);
        $this->customFieldMaker = new CustomFieldMaker($this->name);
        $this->loader = new Loader($this->name);

        register_activation_hook($plugin, [$this, 'activate']);
        register_deactivation_hook($plugin, [$this, 'deactivate']);
    }

    /**
     * Activation
     *
     * @return void
     */
    public function activate()
    {
        $this->rewriter->enable();
    }

    /**
     * Deactivation
     *
     * @return void
     */
    public function deactivate()
    {
        $this->rewriter->disable();
    }
}

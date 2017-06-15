<?php

namespace Cgit\Condo;

class CustomFieldMaker
{
    /**
     * Custom field prefix
     *
     * @var string
     */
    private $prefix;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct($prefix)
    {
        $this->prefix = $prefix;

        add_action('acf/init', [$this, 'register']);
    }

    /**
     * Register ACF custom fields
     *
     * @return void
     */
    public function register()
    {
        acf_add_local_field_group([
            'key' => $this->prefix,
            'title' => 'Access control',
            'location' => [
                [
                    [
                        'param' => 'attachment',
                        'operator' => '==',
                        'value' => 'all',
                    ],
                ],
            ],
            'fields' => [
                [
                    'key' => $this->prefix . '_restricted',
                    'name' => $this->prefix . '_restricted',
                    'label' => 'Restrict access to this file?',
                    'type' => 'true_false',
                ],
            ],
        ]);
    }
}

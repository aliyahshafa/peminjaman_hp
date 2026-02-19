<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Filters extends BaseConfig
{
    /**
     * Alias filter
     */
    public array $aliases = [
        // Filter bawaan CI
        'csrf'     => \CodeIgniter\Filters\CSRF::class,
        'toolbar'  => \CodeIgniter\Filters\DebugToolbar::class,
        'honeypot' => \CodeIgniter\Filters\Honeypot::class,

        // Filter login (punya kamu)
        'auth'     => \App\Filters\AuthFilter::class,
        'role'     => \App\Filters\RoleFilter::class,
    ];

    /**
     * Filter global
     */
    public array $globals = [
        'before' => [],
        'after'  => [
            'toolbar',
        ],
    ];

    /**
     * Filter berdasarkan HTTP method
     */
    public array $methods = [];

    /**
     * Filter berdasarkan route
     */
    public array $filters = [];
}
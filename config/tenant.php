<?php

// config/tenant.php
return [
    'use_subdomain' => false,
    'domain' => '',
    'storage' => [
        'prefix' => 'tenant_', // tenant klasör prefix'i
        'root' => 'tenants',   // ana klasör
    ],
];

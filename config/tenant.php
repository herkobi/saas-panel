<?php

// config/tenant.php
return [
    'multi_user' => false,
    'use_subdomain' => false,
    'domain' => '',
    'storage' => [
        'prefix' => 'tenant_', // tenant klasör prefix'i
        'root' => 'tenants',   // ana klasör
    ],
];

<?php

return [
    'role_structure' => [
        'admin' => [
            'ticket' => 'c,r,u,d',
            'user' => 'c,r,u,d',
        ],
        'lawyer' => [
            'ticket' => 'c,r,u,d',
            'user' => 'c,r,u,d',
        ],
        'staff' => [
            'ticket' => 'c,r,u,d',
            'user' => 'c,r,u,d',
        ],
        'billing' => [
            'ticket' => 'c,r,u,d',
            'user' => 'c,r,u,d',
        ],
        'logistics' => [
            'ticket' => 'c,r,u,d',
            'user' => 'c,r,u,d',
        ],
        'client' => [
            'ticket' => 'c,r,u,d',
            'user' => 'c,r,u,d',
        ],
    ],
    'permission_structure' => [
        'cru_user' => [
            'profile' => 'c,r,u'
        ],
    ],
    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete',
        'as' => 'assign',
        'ras' => 'reassign'
    ]
];

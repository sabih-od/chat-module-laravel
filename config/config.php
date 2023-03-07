<?php

return [
    'channels_table' => 'channels',
    'users_table' => 'users',

    'prefix' => 'chat',
    'middleware' => ['web', 'auth'],
];
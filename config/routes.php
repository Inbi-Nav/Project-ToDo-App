<?php

return [
    // User CRUD
    '/users'        => 'user#index',
    '/users/show'   => 'user#show',
    '/users/create' => 'user#create',
    '/users/update' => 'user#update',
    '/users/delete' => 'user#delete',

    // Task CRUD
    '/tasks'        => 'task#index',
    '/tasks/show'   => 'task#show',
    '/tasks/create' => 'task#create',
    '/tasks/update' => 'task#update',
    '/tasks/delete' => 'task#delete',

    // Category CRUD
    '/categories'        => 'category#index',
    '/categories/show'   => 'category#show',
    '/categories/create' => 'category#create',
    '/categories/update' => 'category#update',
    '/categories/delete' => 'category#delete',


    // Views
    '/register' => 'user#register',
    '/login'    => 'user#login',
    '/dashboard' => 'user#dashboard',
    '/logout'   => 'user#logout',
];
?>
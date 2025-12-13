<?php

return [
    // User CRUD
    '/users'        => 'user#index',
    '/users/show'   => 'user#show',
    '/users/create' => 'user#create',
    '/users/update' => 'user#update',
    '/users/delete' => 'user#delete',
    '/register' => 'user#register',
     '/login'    => 'user#login',
    '/logout'   => 'user#logout',



    // Task CRUD
    '/tasks'        => 'task#index',
    '/tasks/show'   => 'task#show',
    '/tasks/create' => 'task#create',
    '/tasks/update' => 'task#update',
    '/tasks/delete' => 'task#delete',

];
?>
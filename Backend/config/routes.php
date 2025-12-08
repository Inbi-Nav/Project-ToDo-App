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


];


?>
<?php

require_once __DIR__ . '/../models/User.php';

class UserController extends ApplicationController
{
    // GET /users
    public function indexAction()
    {
        // 1. Obtener todos los usuarios usando el Modelo
        $users = User::all();
        
        $this->data['users'] = $users;
        $this->data['title'] = 'Lista de Usuarios';
        
        // 3. Renderizar la vista
        $this->render('users/index', $this->data);
    }
    
    // GET /login
    public function loginAction()
    {
    }

    // GET /register
    public function createAction()
    {
    }

    // POST /register
    public function storeAction()
    {
    }

    // POST /login
    public function authenticateAction()
    {
    }

    // GET /logout
    public function logoutAction()
    {
    }

    // GET /users/:id
    public function showAction($id)
    {
    }

    // GET /users/:id/edit
    public function editAction($id)
    {
    }

    // POST /users/:id/update
    public function updateAction($id)
    {
    }

    // POST /users/:id/delete
    public function deleteAction($id)
    {
    }
}

<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;


class UserApiController extends ResourceController
{

    // public function __construct()
    // {
    //     $this->model = new UserModel();
    // }


    protected $modelName = 'App\Models\UserModel';
    protected $format    = 'json';


    // GET: /users - List all users
    public function index()
    {
        $users = $this->model->findAll();

        return $this->respond($users);
    }


    public function show($id = null)
    {
        $user = $this->model->find($id);
        if (!$user) {
            return $this->failNotFound('User not found');
        }
        return $this->respond($user);
    }

     // POST: /users - Create a new user
     public function create()
     {
         $data = [
             'name'  => $this->request->getPost('name'),
             'email' => $this->request->getPost('email'),
         ];
 
         if ($this->model->insert($data)) {
             $response = [
                 'status'   => 201,
                 'messages' => 'User created successfully',
                 'data'     => $data,
             ];
             return $this->respondCreated($response);
         }
         
         return $this->failValidationErrors($this->model->errors());
     }


     // PUT/PATCH: /users/{id} - Update a user
    public function update($id = null)
    {
        $data = $this->request->getRawInput(); // use for PUT method, POST for PATCH
        
        if (!$this->model->find($id)) {
            return $this->failNotFound('User not found');
        }

        if ($this->model->update($id, $data)) {
            $response = [
                'status'   => 200,
                'messages' => 'User updated successfully',
                'data'     => $data,
            ];
            return $this->respond($response);
        }

        return $this->failValidationErrors($this->model->errors());
    }

}

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

    // Add CORS headers to each response


    protected $modelName = 'App\Models\UserModel';
    protected $format    = 'json';


    private function setCorsHeaders()
    {
        header('Access-Control-Allow-Origin: *');  // Allow all origins. You can specify a specific domain if needed.
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        header('Access-Control-Allow-Credentials: true'); // If you're dealing with cookies or authentication
    }

    // Handle preflight OPTIONS requests
    public function options()
    {
        $this->setCorsHeaders();
        exit(0); // Terminate the script for OPTIONS requests
    }

    // GET: /users - List all users
    public function index()
    {


        $this->setCorsHeaders();
    
        
        $users = $this->model->findAll();


        return $this->respond($users);
    }


    public function show($id = null)
    {

        $this->setCorsHeaders();

        $user = $this->model->find($id);
        if (!$user) {
            return $this->failNotFound('User not found');
        }
        return $this->respond($user);
    }

     // POST: /users - Create a new user
     public function create()
     {
         // Set CORS headers
         $this->setCorsHeaders();
     
         // Get the incoming POST data as JSON
         $jsonData = $this->request->getJSON(true); // `true` converts it into an associative array
     
         // Log the incoming JSON data for debugging purposes
         log_message('info', 'Incoming JSON request data: ' . json_encode($jsonData));
     
         // Check if the data is empty or incomplete
         if (empty($jsonData['name']) || empty($jsonData['email'])) {
             return $this->response->setJSON(['error' => 'Name and email fields are required'], 400);
         }
     
         // Proceed to insert data into the database if validation passes
         $model = new \App\Models\UserModel();
         $model->insert($jsonData);
     
         return $this->response->setJSON(['messages' => 'User created successfully','data' => $jsonData], 200);
     }
     
     


     // PUT/PATCH: /users/{id} - Update a user
    public function update($id = null)
    {

        
        $this->setCorsHeaders();

        $data = $this->request->getJSON(true); // use for PUT method, POST for PATCH
        
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



    public function delete($id = null)
    {
        // Set CORS headers
        $this->setCorsHeaders();

        if (!$this->model->find($id)) {
            return $this->failNotFound('User not found');
        }

        if ($this->model->delete($id)) {
            $response = [
                'status'   => 200,
                'messages' => 'User deleted successfully',
                'data'     => null,
            ];
            return $this->respond($response);
        }

        return $this->failServerError('Failed to delete user');


    }

}

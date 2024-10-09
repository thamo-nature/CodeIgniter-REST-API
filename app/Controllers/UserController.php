<?php

namespace App\Controllers;


use App\Models\UserModel;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use CodeIgniter\Database\Exceptions\DatabaseException;

class UserController extends BaseController
{

    public $db;

    public function __construct()
    {
        // Load the database
        $this->db = \Config\Database::connect();
    }


   public function index()
    {
        // Fetch the UserModel to interact with the user table
        $user = new UserModel();

        // Start the transaction
        $this->db->transStart();
        
        try {
            // Fetch all users
            $users = $user->get();
            
            // $builder = $this->db->table('users');

            // $builder->get();

            // $builder->where('id', '1');


            // Option 2: Get the compiled select query (this only generates the query, without executing it)
            //$compiledQuery = $builder->getCompiledSelect();
        
            // Display the compiled query
            //echo $compiledQuery;

            //die();

            // Complete the transaction
            $this->db->transComplete();
            
            // Check if the transaction was successful
            if ($this->db->transStatus() === FALSE) {
                // Transaction failed, so handle the rollback manually
                $this->db->transRollback();
                throw new DatabaseException("Transaction failed. Changes rolled back.");
            }

            // Return the view with users data
            return view('users/user_view', ['users' => $users]);

        } catch (DatabaseException $e) {
            // Handle the exception if any error occurs
            return $this->response->setStatusCode(500)->setBody($e->getMessage());
        }
    }


    public function create()
    {
        return view('users/create_user');
    }


    public function store()
    {
        // Create an instance of the UserModel
        $model = model(UserModel::class);
    
        // Fetch POST data
        $data = [
            'name'  => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
        ];
    
        // Debugging: Print the data to confirm it is being retrieved correctly
        echo '<pre>';
        print_r($data); // Check what data is being retrieved from the form
        echo '</pre>';
    
        // Attempt to save the data to the database
        try {
            if ($model->save($data) === false) {
                // If saving fails, get the error messages
                echo '<pre>';
                print_r($model->errors()); // Check for validation errors or database issues
                echo '</pre>';
                return;
            }
        } catch (\Exception $e) {
            echo 'Error: ' . $e->getMessage(); // Print the exception message
            return;
        }
    
        // Optionally, you can return a redirect or a response
        return redirect()->to('users/');
    }
    
    
    
    



    public function edit($id = null)
    {
        $model = new UserModel();
        $data['user'] = $model->find($id);

        return view('users/edit_user', $data);
    }

    public function update($id = null)
    {
        $model = new UserModel();
    
        // Fetch the user based on the ID
        $user = $model->find($id);
    
        // Debugging: Check if user is found
        if (!$user) {
            echo 'User not found.';
            return;
        }
    
        echo '<pre>';
        print_r($user); // Check the current user data
        echo '</pre>';
    
        // Create an array with updated data from the request
        $data = (object) [
            'name'  => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
        ];
    
        // Debugging: Check the data to be updated
        echo '<pre>';
        print_r($data); // Check what data is being sent for update
        echo '</pre>';
    
        // Attempt to update the user data in the database
        try {
            if ($model->update($id, $data) === false) {
                // If saving fails, get the error messages
                echo '<pre>';
                print_r($model->errors()); // Check for validation errors or database issues
                echo '</pre>';
                return;
            }
        } catch (\Exception $e) {
            echo 'Error: ' . $e->getMessage(); // Print the exception message
            return;
        }
    
        // Optionally redirect after successful update
        return redirect()->to('/users');
    }
    


    public function delete($id = null)
    {
        $model = new UserModel();
        $model->delete($id);

        return redirect()->to('/users');
    }


}

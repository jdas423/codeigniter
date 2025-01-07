<?php
namespace App\Controllers;

use App\Models\CandidateModel;
use App\Models\UserModel;
use App\Models\LoginlogModel;

// use Ramsey\Uuid\Guid\Guid;
// use Ramsey\Uuid\Guid\GuidInterface;
// use Ramsey\Uuid\Rfc4122\FieldsInterface;
// use Ramsey\Uuid\Guid\GuidFactoryInterface;

class Auth extends BaseController{
     private $data=[];
     private $userId="";
    private $candidateId="";
     public function createAcc() {
       
        $this->data=$this->request->getPost(['name','email','role_id','role_text','password','mobile','source']);
       
        if(!$this->validateData($this->data,[
           'name'=>'required|alpha_space',
           'email'=>'required|valid_email',
           'role_id'=>'required|integer',
           'role_text'=>'required|alpha_space',
           'password'=>'required|min_length[6]',
           'mobile'=>'required|numeric|min_length[10]|max_length[10]',
           'source'=>'required|alpha_space'
        ])){
            return $this->response
            ->setStatusCode(400)
            ->setJSON([ 
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $this->validator->getErrors()
            ]);
        }
        $resp=$this->createUser();
        if(!$resp['res']){
            return $this->response
            ->setStatusCode(400)
            ->setJSON([ 
                'status' => 'error',
                'message' => 'database error',
                'errors'=>$resp['errors']
            ]);
        };
        
        $resp=$this->createCandidate();
        if(!$resp['res']){
            return $this->response
            ->setStatusCode(400)
            ->setJSON([ 
                'status' => 'error',
                'message' => 'database error',
                'errors'=>$resp['errors']
            ]);
        };
        
        return $this->response
        ->setStatusCode(200)
        ->setJSON([ 
            'status' => 'success',
            'message' => 'Candidate successfully registered!!!',
            "userId"=>$this->userId,
            "candidateId"=>$this->candidateId
        ]);
     }
     
     private function createUser(){
        $this->data["email"]=strtolower($this->data["email"]);
        $this->data["username"]=$this->data["email"];

        $model=model(UserModel::class);

        $user = $model->where('email', $this->data['email'])
        ->orWhere('username', $this->data['username'])
        ->first();

        if ($user) {
            return ['res'=>false,'errors'=>'User with the given email already exists'];
        } 

        $datetime=new \DateTime();
       
        $userData=[
            "email"=>$this->data['email'],
            "username"=>$this->data['username'],
            "name"=>$this->data['name'],
            "password"=>base64_encode($this->data['password']),
            "mobile"=>$this->data['mobile'],
            "role_id"=>$this->data['role_id'],
            "role_text"=>$this->data['role_text'],
            "created_by" => null,
            "company" => null,
            "department" => null,
            "designation" => null,
            "gender" => null,
            "zone" => null,
            "state" => null,
            "city" => null,
            "pincode" => null,
            "address" => null,
            "client_id" => null,
            "client_name" => null,
            "partner_id" => null,
            "partner_name" => null,
            "failed_login_count" => null,
            "last_login" => null,
            "last_login_ip" => null,
            "auth_key" => $datetime->format('Y-m-d H:i:s'),
            "auth_key_generated_time"=>$datetime->format('Y-m-d H:i:s'),
            "otp" => null,
            "otp_time" => null,
            "last_login_ip_verified" => null
        ];
        if ($model->save($userData)) {
            $this->userId=$model->getInsertID();
            return ['res'=>true];
        } else {
            $errorMessages = $model->errors();
            return ['res'=>false,'errors'=>$errorMessages];
        }
     }

     private function createCandidate():Array{
        $model=model(CandidateModel::class);
        
        $candidateData = [
            "name" => $this->data["name"],
            "mobile" => $this->data["mobile"],
            "email" => $this->data["email"],
            "dob" => null,
            "gender" => null,
            "indent_id" => null,
            "fathername" => null,
            "skills" => null,
            "address" => null,
            "state" => null,
            "city" => null,
            "totworkexp" => null,
            "highest_qualification_category" => null,
            "education" => null,
            "highest_qualification" => null,
            "highest_qualification_passed_on" => null,
            "highest_qualification_percentage" => null,
            "experience" => null,
            "user_id" => $this->userId,
            "resume_path" => null,
            "unique_id" => null,
            "auth" => 'self',
            "candidate_summary" => null,
            "strengths" => null,
            "upload_location" => null,
            "answers" => null,
            "eandi_id" => null,
            "selfsource"=>$this->data["source"]
        ];
        
        if ($model->save($candidateData)) {
            $this->candidateId=$model->getInsertID();
            return ['res'=>true];
        } else {
            $errorMessages = $model->errors();
            return ['res'=>false,'errors'=>$errorMessages];
        }

     }
     private function displayData():string{
        return view('displayData',['data'=>$this->data]);
     }

     public function loginUser(){
        $loginId="";
        $username=strtolower($this->request->getGet('username'));
        $password=$this->request->getGet('password');

        $model=model(UserModel::class);
        
        $user = $model->groupStart()
        ->where('email', $username)
        ->orWhere('username', $username)
        ->groupEnd()
        ->where('role_id!=',value: 9)
        ->first();

        if(!$user){
            return $this->response
            ->setStatusCode(400)
            ->setJSON([ 
                'status' => 'error',
                'message' => 'no user exists with the given email or username'
            ]);
        }

        if(base64_decode($user['password'])!==$password){
            return $this->response
            ->setStatusCode(400)
            ->setJSON([ 
                'status' => 'error',
                'message' => 'wrong password'
            ]);
        }

        if(!$user["verified"]){
            return $this->response
            ->setStatusCode(400)
            ->setJSON([ 
                'status' => 'error',
                'message' => 'user is not verified'
            ]);
        }


        $client_ip = $this->request->getIPAddress();
        $user_agent = $this->request->getUserAgent();
        $datetime=new \DateTime();
        $updateData=[
            "last_login"=>$datetime->format('Y-m-d H:i:s'),
            "last_login_ip"=>$client_ip
        ];
    
        if ($model->update($user["id"], $updateData)) {} else {
            $errorMessages = $model->errors();
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'status' => 'error',
                    'message' => 'Update failed',
                    'errors' => $errorMessages
                ]);
        }

        $login_data=[
            "email"=>$user["email"],
            "role_id"=>$user["role_id"],
            "role_name"=>$user["role_text"],
            "ip_address"=>$client_ip,
            "user_agent"=>$user_agent,
            "request"=>"login"
        ];

        $model=model(LoginlogModel::class);
        if ($model->insert($login_data)) {
            $loginId=$model->getInsertID();
        } else {
            $errorMessages = $model->errors();
            return $this->response
            ->setStatusCode(400)
            ->setJSON([ 
                'status' => 'error',
                'message' => 'database failure',
                "errors"=>$errorMessages
            ]);
        }


        return $this->response
        ->setStatusCode(200)
        ->setJSON([ 
            'status' => 'success',
            'message' => 'login successful!!!',
            'data'=>[
                "id"=>$user["id"],
                "auth_key"=>$user["auth_key"],
                "usertype"=>$user["role_text"],
                "name"=>$user["name"],
                "role_id"=>$user["role_id"],
                'loginId'=>$loginId
            ]
        ]);



     }
}
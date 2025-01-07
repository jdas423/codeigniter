<?php

namespace App\Models;

use CodeIgniter\Model;

class LoginlogModel extends Model
{
    protected $table='login_log';
    protected $primaryKey = 'id';
    
    protected $allowedFields = [
       "email","role_id","role_name","ip_address","user_agent","request"
    ];
    
}
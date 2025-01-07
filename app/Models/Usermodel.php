<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table='user';
    protected $primaryKey = 'id';
    
    protected $allowedFields = [
        'name',
        'username',
        'email',
        'role_id',
        'role_text',
        'created_by',
        'password',
        'mobile',
        'company',
        'department',
        'designation',
        'gender',
        'zone',
        'state',
        'city',
        'pincode',
        'address',
        'client_id',
        'client_name',
        'partner_id',
        'partner_name',
        'image',
        'failed_login_count',
        'last_login',
        'last_login_ip',
        'auth_key',
        'auth_key_generated_time',
        'otp',
        'otp_time',
        'last_login_ip_verified'
    ];
    
}
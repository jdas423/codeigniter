<?php

namespace App\Models;
use CodeIgniter\Model;

class CandidateModel extends Model{
    protected $table='candidate';

    protected $primaryKey = 'id';
    
    protected $allowedFields = [ 
    'name',
    'mobile',
    'email',
    'dob',
    'gender',
    'indent_id',
    'fathername',
    'skills',
    'address',
    'state',
    'city',
    'totworkexp',
    'highest_qualification_category',
    'education',
    'highest_qualification',
    'highest_qualification_passed_on',
    'highest_qualification_percentage',
    'experience',
    'user_id',
    'resume_path',
    'unique_id',
    'auth',
    'candidate_summary',
    'strengths',
    'upload_location',
    'answers',
    'eandi_id',
     'selfsource'];
}
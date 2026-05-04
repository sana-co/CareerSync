<?php
class Candidate
{
    use Model;
    protected $table = 'candidate';
    protected $allowedColumns = [
        'user_id',
        'firstName',
        'lastName',
        'DOB',
        'address',
        'contactNo',
        'candidate_photo_path',
    ];
}

<?php
class Counselor
{
    use Model;
    protected $table = 'counselor';
    protected $allowedColumns = [
        'user_id',
        'firstName',
        'lastName',
        'contactNo',
        'counselor_photo_path',
        'certificate_path',
    ];
}

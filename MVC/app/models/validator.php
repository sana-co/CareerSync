<?php
class Validator
{
    use Model;
    protected $table = 'validator';
    protected $allowedColumns = [
        'user_id',
        'firstName',
        'lastName',
        'contactNo',
        'validator_photo_path',
    ];

    public function getCompanyDetails()
    {
        $query = "SELECT c.*, u.email FROM company c 
        JOIN users u ON c.user_id = u.user_id
        WHERE u.status = 'pending' 
        AND c.payment_status = 'inactive' 
        AND c.validator_approval = 'pending'";

        return $this->query($query);
    }
}

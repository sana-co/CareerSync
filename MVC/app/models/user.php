<?php
class User
{
    use Model;
    protected $table = 'users';
    protected $allowedColumns = [
        'email',
        'password',
        'role',
        'status',
        'email_verified',
        'verification_token',
        'token_expires_at',
    ];
}
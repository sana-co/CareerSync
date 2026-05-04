<?php
class InterviewSlot
{
    use Model;
    protected $table = 'interview_slots';
    protected $allowedColumns = [
        'interview_id', 
        'slot_datetime'
    ];

    public function __construct()
    {
        $this->order_column = 'slot_id';//overriding the default order_column
    }
}

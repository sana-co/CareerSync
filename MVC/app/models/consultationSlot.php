<?php
class ConsultationSlot
{
    use Model;
    protected $table = 'consultation_slots';

    protected $allowedColumns = [
        'meeting_id',
        'slot_datetime'
    ];

    public function __construct()
    {
        $this->order_column = "slot_id";//overriding the default order_column
    }
}

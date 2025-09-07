<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments'; // Table name

    protected $fillable = ['enrollment_id', 'user_id', 'batch_id', 'payment_id', 'amount', 'status', 'created_at', 'updated_at','payment_method',
        'emi_installments',
        'emi_amount',
        'emi_schedule'];

        protected $casts = [
            'emi_schedule' => 'array', // Cast to array for JSON field
        ];
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
}
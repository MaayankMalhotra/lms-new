<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    protected $table = 'contact_us';

    protected $fillable = [
        'user_type',
        'contact_number',
        'full_name',
        'email',
        'graduation_year',
        'department',
        'company_name',
        'message',
    ];
}

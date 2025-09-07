<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadsStudent extends Model
{
    protected $table = 'leads_student'; // map to your table name

    protected $fillable = [
        'name','email','phone',
        'page','utm_source','utm_medium','utm_campaign','utm_term','utm_content',
        'ip_address','user_agent',
    ];
}

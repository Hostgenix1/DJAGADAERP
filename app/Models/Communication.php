<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Contact;
use App\Models\User;

class Communication extends Model
{
    protected $table = 'communications';

    protected $fillable = [
        'communicable_type', 'communicable_id', 'type', 'direction', 'subject', 'body', 'contact_id', 'user_id', 'occurred_at'
    ];

    protected $casts = [
        'occurred_at' => 'datetime',
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}

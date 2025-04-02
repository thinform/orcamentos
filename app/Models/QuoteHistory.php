<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuoteHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'quote_id',
        'user_id',
        'action',
        'old_status',
        'new_status',
        'changes',
        'comment'
    ];

    protected $casts = [
        'changes' => 'array'
    ];

    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;

// BENAR
#[Fillable(['parent_id', 'user_id', 'admin_id', 'sender_role', 'message', 'attachment', 'is_read', 'read_by_user_at', 'intent', 'confidence'])]
class ChatMessage extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'read_by_user_at' => 'datetime',
        ];
    }

    public function parent()
    {
        return $this->belongsTo(ChatMessage::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(ChatMessage::class, 'parent_id');
    }

    /** Pelanggan pemilik percakapan */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** Admin pengirim pesan (null jika pesan dari pelanggan) */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
<?php

namespace ChatModule\Models;

use ChatModule\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ChatMessage extends Model implements HasMedia
{
    use HasFactory, Uuids, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'channel_id',
        'sender_id',
        'content',
    ];

    protected $hidden = [
        'media',
    ];

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function userDelete()
    {
        return $this->morphOne(UserDelete::class, 'deleteable');
    }
}
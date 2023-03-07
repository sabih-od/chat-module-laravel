<?php

namespace ChatModule\Models;

use ChatModule\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class Channel extends Model
{
    use HasFactory, Uuids, SoftDeletes, HasJsonRelationships;

    protected $fillable = [
        'creator_id',
        'participants'
    ];

    protected $casts = [
        'participants' => 'json'
    ];

    protected $hidden = [
        'deleted_at',
        'updated_at'
    ];

    /*protected $appends = [
        'i_am_participant'
    ];*/

    public function messages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function users()
    {
        return $this->belongsToJson(User::class, 'participants');
    }

    public function group()
    {
        return $this->hasOne(Group::class);
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

    public function userDelete()
    {
        return $this->morphMany(UserDelete::class, 'deleteable');
    }

    public function videoStreams()
    {
        return $this->hasMany(VideoStream::class);
    }

    /*public function getIAmParticipantAttribute()
    {
        return Auth::check() && in_array(Auth::id(), $this->participants ?? []);
    }*/
}
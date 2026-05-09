<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoteVersion extends Model
{
    /** @use HasFactory<\Database\Factories\NoteVersionFactory> */
    use HasFactory;

    protected $fillable = [
        'note_id',
        'old_content',
        'updated_by'
    ];

    public function note()
    {
        return $this->belongsTo(Note::class);
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}

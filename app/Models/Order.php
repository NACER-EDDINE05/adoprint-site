<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'status', 'total', 'meta'];

    protected $casts = [
        'meta' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Fichiers d'impression joints à la commande (table media).
     */
    public function media()
    {
        return $this->morphMany(Media::class, 'model');
    }

    public function getMediaFilesAttribute()
    {
        return $this->media()->orderBy('created_at')->get();
    }

    /**
     * Référence humaine de la commande, ex. « CMD-00042 ».
     */
    public function getReferenceAttribute(): string
    {
        return 'CMD-'.str_pad((string) $this->id, 5, '0', STR_PAD_LEFT);
    }
}

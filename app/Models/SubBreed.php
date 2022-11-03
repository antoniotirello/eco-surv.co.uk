<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $name
 * @property numeric $breed_id
 */
class SubBreed extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function breed(): BelongsTo
    {
        return $this->belongsTo(Breed::class);

    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $name
 */
class Breed extends Model
{
    use HasFactory;

    //public string $name;

    protected $fillable = ['name'];
    protected $table = 'breeds';

    public function subBreed(): HasMany
    {
        return $this->hasMany(SubBreed::class);
    }
}

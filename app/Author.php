<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Author
 *
 * @property int $id
 * @property string $name
 * @property-read Collection|Book[] books
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Author extends Model
{
    protected $fillable = ['name'];

    public function books()
    {
        return $this->belongsToMany(Book::class);
    }
}

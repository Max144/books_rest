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
 * @property-read Collection|Author[] $authors
 * @property-read Collection|Category[] $categories
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Book extends Model
{
    protected $fillable = ['name'];
    protected $with = ['authors', 'categories'];

    public function authors()
    {
        return $this->belongsToMany(Author::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}

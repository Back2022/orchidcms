<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Category extends Model
{
    use HasFactory;
    use AsSource, Filterable, Attachable;
    
    protected $fillable=['type',  'created_at','updated_at'];
     
    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'type',
        'updated_at',
        'created_at',
    ];
}

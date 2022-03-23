<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;
use App\Models\Category;

class Post extends Model
{
    use HasFactory;
    use AsSource, Filterable, Attachable;
    
    protected $fillable=['title', 'category_id','body', 'created_at','updated_at'];
    
        /**
     * Name of columns to which http sorting can be applied
     *
     * @var array
     */
    protected $allowedSorts = [
        'title',
        'category_id',
        'created_at',
        'updated_at'
    ];
    
    /**
 * Name of columns to which http filter can be applied
 *
 * @var array
 */
protected $allowedFilters = [
    'title',
];
    public function category()
    {
        return $this->belongsTo(Category::class);  //category_id bi trebao automatski prepoznati
    }
}

// Provjera relacija uz pomoć tinkera 
/*
 
 $ php artisan tinker
Psy Shell v0.11.2 (PHP 8.1.1 — cli) by Justin Hileman

>>> $post1= new Post;
[!] Aliasing 'Post' to 'App\Models\Post' for this Tinker session.
=> App\Models\Post {#3653}
 
>>> $post1->findorfail(1);
=> App\Models\Post {#3975
     id: 1,
     category_id: 2,
     title: "Naslov",
     created_at: "2022-03-22 18:03:14",
     updated_at: "2022-03-22 18:03:48",
   }

>>> $post1->findorfail(1)->category()->get();
=> Illuminate\Database\Eloquent\Collection {#4587
     all: [
       App\Models\Category {#4591
         id: 2,
         type: "Zabava",
         created_at: "2022-03-22 17:54:16",
         updated_at: "2022-03-22 17:54:16",
       },
     ],
   }

>>> $post1->findorfail(1)->category()->get()->first()->type;
=> "Zabava"

 */
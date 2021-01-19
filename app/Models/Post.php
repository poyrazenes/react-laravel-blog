<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Base
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'user_id', 'title',
        'slug', 'content', 'image', 'published'
    ];

    protected $appends = ['image_url', 'date_formatted', 'excerpt'];

    /**
     * return the image url to be displayed on react templates
     */
    public function getImageUrlAttribute()
    {
        return $this->image != "" ? url("uploads/" . $this->image) : "";
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->with(['user', 'post']);
    }

    /**
     * approved comments to be displayed on react website
     */
    public function approvedComments()
    {
        return $this->hasMany(Comment::class)->with(['user', 'post'])
            ->where('approved', 1);
    }

    public function tags()
    {
        return $this->belongsToMany(
            Tag::class,
            'post_tag',
            'post_id',
            'tag_id'
        );
    }

    public function getDateFormattedAttribute()
    {
        return Carbon::parse($this->created_at)
            ->format('F d, Y');
    }

    public function getExcerptAttribute()
    {
        return substr(strip_tags($this->content), 0, 100);
    }

    public function getPrevAttribute()
    {
        return Post::where('published', true)
            ->where('id', '<', $this->id)
            ->orderBy('id', 'desc')->first();
    }

    public function getNextAttribute()
    {
        return Post::where('published', true)
            ->where('id', '>', $this->id)->first();
    }
}

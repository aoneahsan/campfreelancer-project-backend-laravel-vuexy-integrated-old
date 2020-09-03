<?php

namespace App\Model\Gig;

use App\Model\MainModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class GigCategory extends MainModel
{

    use SoftDeletes;

    // protected $fillable = [
    //     'parent_id', 'title', 'description', 'sort_order', 'is_visible', 'is_parent'
    // ];
    protected $guarded = [];

    public function icon_file_url()
    {
        if (!!$this->icon_file_path) {
            return Storage::url($this->icon_file_path);
        } else {
            return 'custom-assets/img/defaultPlaceholder.png';
        }
    }

    public function image_file_url()
    {
        if (!!$this->image_file_path) {
            return Storage::url($this->image_file_path);
        } else {
            return 'custom-assets/img/defaultPlaceholder.png';
        }
    }

    public function banner_file_url()
    {
        if (!!$this->banner_file_path) {
            return Storage::url($this->banner_file_path);
        } else {
            return 'custom-assets/img/defaultPlaceholder.png';
        }
    }

    public function video_file_url()
    {
        if (!!$this->video_file_path) {
            return Storage::url($this->video_file_path);
        } else {
            return false;
        }
    }

    public function subcategories()
    {
        return $this->hasMany('App\Model\Gig\GigCategory', 'parent_id', 'id');
    }
}

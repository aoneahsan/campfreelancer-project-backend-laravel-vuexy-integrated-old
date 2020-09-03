<?php

namespace App\Model\Gig;

use App\Model\MainModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class GigGallery extends MainModel
{
    use SoftDeletes;
    
    // protected $fillable = [
    //     'gig_id', 'file_type', 'file_name'
    // ];
    protected $guarded = [];

    public function file_url()
    {
        if (!!$this->file_name) {
            return Storage::url($this->file_name);
        } else {
            return 'custom-assets/img/defaultPlaceholder.png';
        }
    }
}

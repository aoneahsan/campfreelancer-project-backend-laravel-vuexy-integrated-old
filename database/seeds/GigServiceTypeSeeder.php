<?php

use App\Model\Gig\GigServiceType;
use Illuminate\Database\Seeder;

use Illuminate\Support\Str;

class GigServiceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $service_type = new GigServiceType();
    	$service_type->category_id = 5;
    	$service_type->title = "WordPress Website Development";
    	$service_type->slug = Str::slug($service_type->title);
    	$service_type->description = "Develop Complete Website in WordPress.";
        $service_type->save();

        $service_type2 = new GigServiceType();
    	$service_type2->category_id = 7;
    	$service_type2->title = "Html Website";
    	$service_type2->slug = Str::slug($service_type2->title);
    	$service_type2->description = "Develop Complete Website in HTML.";
        $service_type2->save();
    }
}

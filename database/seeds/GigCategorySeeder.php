<?php

use App\Model\Gig\GigCategory;
use Illuminate\Database\Seeder;

use Illuminate\Support\Str;

class GigCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $parent_category = new GigCategory();
    	$parent_category->title = "Programming";
    	$parent_category->slug = Str::slug($parent_category->title);
    	$parent_category->description = "Programming Category.";
    	$parent_category->is_parent = true;
    	$parent_category->is_popular = true;
    	$parent_category->header_menu_item = true;
    	$parent_category->freelancers_increment = 20000;
        $parent_category->save();

        $parent_category2 = new GigCategory();
    	$parent_category2->title = "Designing";
    	$parent_category2->slug = Str::slug($parent_category2->title);
    	$parent_category2->description = "Programming Category.";
    	$parent_category2->is_parent = true;
    	$parent_category2->is_popular = true;
    	$parent_category2->header_menu_item = true;
    	$parent_category2->freelancers_increment = 20000;
        $parent_category2->save();

        $parent_category3 = new GigCategory();
		$parent_category3->title = "Content Writing";
		$parent_category3->slug = Str::slug($parent_category3->title);
    	$parent_category3->description = "Programming Category.";
    	$parent_category3->is_parent = true;
    	$parent_category3->is_popular = true;
    	$parent_category3->header_menu_item = true;
    	$parent_category3->freelancers_increment = 20000;
        $parent_category3->save();

        $parent_category4 = new GigCategory();
    	$parent_category4->title = "Game Development";
		$parent_category4->slug = Str::slug($parent_category4->title);
		$parent_category4->description = "Programming Category.";
    	$parent_category4->is_parent = true;
    	$parent_category4->is_popular = true;
    	$parent_category4->header_menu_item = true;
    	$parent_category4->freelancers_increment = 20000;
        $parent_category4->save();

        $child_category = new GigCategory();
    	$child_category->parent_id = 1;
    	$child_category->title = "WordPress";
		$child_category->slug = Str::slug($child_category->title);
    	$child_category->description = "WordPress Programming Category.";
    	$child_category->is_parent = false;
        $child_category->save();

        $child_category2 = new GigCategory();
    	$child_category2->parent_id = 3;
    	$child_category2->title = "Blog Post";
		$child_category2->slug = Str::slug($child_category2->title);
    	$child_category2->description = "WordPress Programming Category.";
    	$child_category2->is_parent = false;
        $child_category2->save();

        $child_category3 = new GigCategory();
    	$child_category3->parent_id = 1;
    	$child_category3->title = "HTML";
		$child_category3->slug = Str::slug($child_category3->title);
    	$child_category3->description = "WordPress Programming Category.";
    	$child_category3->is_parent = false;
		$child_category3->save();
		
		$child_category4 = new GigCategory();
    	$child_category4->parent_id = 2;
    	$child_category4->title = "Logo Designing";
		$child_category4->slug = Str::slug($child_category4->title);
    	$child_category4->description = "WordPress Programming Category.";
    	$child_category4->is_parent = false;
        $child_category4->save();
    }
}

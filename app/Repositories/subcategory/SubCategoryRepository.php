<?php

namespace App\Repositories\subcategory;

use App\Models\Category;
use App\Models\SubCategory;
use Intervention\Image\Facades\Image;


class SubCategoryRepository 
{
    public function StoreSubCategory($data)
    {
        // $image = $data->file('subcategory_image');
        // $name_genrate = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
        // Image::make($image)->resize(120,120)->save('upload/subcategory/'.$name_genrate);
        // $save_url = 'upload/subcategory/'.$name_genrate;

        SubCategory::insert([
            'category_id' => $data->category_id,
            'subcategory_name' => $data->subcategory_name,
            'subcategory_slug' => strtolower(str_replace('','-',$data->subcategory_name)),
            // 'subcategory_image' => $save_url,
        ]);
        $notification = [
            'message' => 'SubCategory Inserted Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->route('all.subcategory')->with($notification);
    }
    public function UpdateSubCategory($data)
    {
        $subcategory_name_id = $data->id;
        SubCategory::findOrFail($subcategory_name_id)->update([
            'category_id' => $data->category_id,
            'subcategory_name' => $data->subcategory_name,
            'subcategory_slug' => strtolower(str_replace('','-',$data->subcategory_name)),
        ]);
        $notification = [
            'message' => 'SubCategory Upadated Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->route('all.subcategory')->with($notification);
    

        
    }
    public function DeleteSubCategory($id)
    {
        SubCategory::findOrFail($id)->delete();
        $notification = [
            'message' => 'SubCategory Deleted Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($notification);
    }
}
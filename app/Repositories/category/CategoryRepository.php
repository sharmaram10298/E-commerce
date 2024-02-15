<?php

namespace App\Repositories\category;

use App\Models\Category;
use Intervention\Image\Facades\Image;


class CategoryRepository 
{
    public function StoreCategory($data)
    {
        $image = $data->file('category_image');
        $name_genrate = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
        Image::make($image)->resize(120,120)->save('upload/category/'.$name_genrate);
        $save_url = 'upload/category/'.$name_genrate;

        Category::insert([
            'category_name' => $data->category_name,
            'category_slug' => strtolower(str_replace('','-',$data->category_name)),
            'category_image' => $save_url,
        ]);
        $notification = [
            'message' => 'Category Inserted Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->route('all.category')->with($notification);
    }
    public function UpdateCategory($data)
    {
        $category_name_id = $data->id;
        $category_old_image_id = $data->old_image;

        if ($data->file('category_image')) {
            $image = $data->file('category_image');
            $name_genrate = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            Image::make($image)->resize(300,300)->save('upload/category/'.$name_genrate);
            $save_url = 'upload/category/'.$name_genrate;

            if (file_exists($category_old_image_id)) {
                unlink($category_old_image_id);
             }
     
            Category::findOrFail($category_name_id)->update([
                'category_name' => $data->category_name,
                'category_slug' => strtolower(str_replace('','-',$data->category_name)),
                'category_image' => $save_url,
            ]);
            $notification = [
                'message' => 'Category Upadated Successfully',
                'alert-type' => 'success'
            ];
            return redirect()->route('all.category')->with($notification);
        } else {
            Category::findOrFail($category_name_id)->update([
                'category_name' => $data->category_name,
                'category_slug' => strtolower(str_replace('','-',$data->category_name)),
                
            ]);
            $notification = [
                'message' => 'Category  Upadated  without image Successfully',
                'alert-type' => 'success'
            ];
            return redirect()->route('all.category')->with($notification);
        }

        
    }
    public function DeleteCategory($id)
    {
        $category_id = Category::findOrFail($id);
        $img = $category_id->category_image;
        unlink($img);
        Category::findOrFail($id)->delete();
        $notification = [
            'message' => 'Category Deleted Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($notification);
    }
}
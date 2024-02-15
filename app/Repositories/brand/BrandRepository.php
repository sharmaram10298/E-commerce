<?php

namespace App\Repositories\brand;

use App\Models\Brand;
use Intervention\Image\Facades\Image;

class BrandRepository
{
    public function AllBrand()
    {
        $brand = Brand::latest()->get();
        return $brand;
    }
    public function StoreBrand($data)
    {
        $image = $data->file('brand_image');
        $name_genrate = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
        Image::make($image)->resize(300,300)->save('upload/brand/'.$name_genrate);
        $save_url = 'upload/brand/'.$name_genrate;

        Brand::insert([
            'brand_name' => $data->brand_name,
            'brand_slug' => strtolower(str_replace('','-',$data->brand_name)),
            'brand_image' => $save_url,
        ]);
        $notification = [
            'message' => 'Brand Inserted Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->route('all.brand')->with($notification);
    }
    public function UpdateBrand($data)
    {
        $brand_name_id = $data->id;
        $brand_old_image_id = $data->old_image;

        if ($data->file('brand_image')) {
            $image = $data->file('brand_image');
            $name_genrate = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            Image::make($image)->resize(300,300)->save('upload/brand/'.$name_genrate);
            $save_url = 'upload/brand/'.$name_genrate;

            if (file_exists($brand_old_image_id)) {
                unlink($brand_old_image_id);
             }
     
            Brand::findOrFail($brand_name_id)->update([
                'brand_name' => $data->brand_name,
                'brand_slug' => strtolower(str_replace('','-',$data->brand_name)),
                'brand_image' => $save_url,
            ]);
            $notification = [
                'message' => 'Brand Upadated Successfully',
                'alert-type' => 'success'
            ];
            return redirect()->route('all.brand')->with($notification);
        } else {
            Brand::findOrFail($brand_name_id)->update([
                'brand_name' => $data->brand_name,
                'brand_slug' => strtolower(str_replace('','-',$data->brand_name)),
                
            ]);
            $notification = [
                'message' => 'Brand  Upadated  without image Successfully',
                'alert-type' => 'success'
            ];
            return redirect()->route('all.brand')->with($notification);
        }

        
    }
    public function DeleteBrand($id)
    {
        $brand_id = Brand::findOrFail($id);
        $img = $brand_id->brand_image;
        unlink($img);
        Brand::findOrFail($id)->delete();
        $notification = [
            'message' => 'Brand Deleted Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($notification);
    }


}


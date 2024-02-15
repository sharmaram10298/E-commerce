<?php

namespace App\Repositories\product;

use App\Models\User;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\MultiImg;
use App\Models\SubCategory;
use Illuminate\Support\Carbon;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Request;

class ProductRepository
{
    public function AddProduct()
    {
        $activeVendor = User::where('status','active')->where('role','vendor')->latest()->get();
        $brands = Brand::latest()->get();
        $categories = Category::latest()->get();
        return view('backend.product.product_add',compact('brands','categories','activeVendor'));
    }

    public function StoreProduct($data){


        $image = $data->file('product_thumbnail');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(800,800)->save('upload/products/thambnail/'.$name_gen);
        $save_url = 'upload/products/thambnail/'.$name_gen;

        $product_id = Product::insertGetId([

            'brand_id' => $data->brand_id,
            'category_id' => $data->category_id,
            'subcategory_id' => $data->subcategory_id,
            'product_name' => $data->product_name,
            'product_slug' => strtolower(str_replace(' ','-',$data->product_name)),

            'product_code' => $data->product_code,
            'product_qty' => $data->product_qty,
            'product_tags' => $data->product_tags,
            'product_size' => $data->product_size,
            'product_color' => $data->product_color,

            'selling_price' => $data->selling_price,
            'discount_price' => $data->discount_price,
            'short_descp' => $data->short_descp,
            'long_descp' => $data->long_descp, 

            'hot_deals' => $data->hot_deals,
            'featured' => $data->featured,
            'special_offer' => $data->special_offer,
            'special_deals' => $data->special_deals, 

            'product_thumbnail' => $save_url,
            'vendor_id' => $data->vendor_id,
            'status' => 1,
            'created_at' => Carbon::now(), 

        ]);

        /// Multiple Image Upload From her //////
        $images = $data->file('multi_img');
        foreach($images as $img){
            $make_name = hexdec(uniqid()).'.'.$img->getClientOriginalExtension();
        Image::make($img)->resize(800,800)->save('upload/products/multi-image/'.$make_name);
        $uploadPath = 'upload/products/multi-image/'.$make_name;



        MultiImg::insert([

            'product_id' => $product_id,
            'photo_name' => $uploadPath,
            'created_at' => Carbon::now(), 

        ]); 
        } // end foreach

        /// End Multiple Image Upload From her //////

        $notification = array(
            'message' => 'Product Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.product')->with($notification); 
    } 

    public function EditProduct($id){
        // dd($id);
        
       $multiImgs = MultiImg::where('product_id',$id)->get();
        $activeVendor = User::where('status','active')->where('role','vendor')->latest()->get();
         $brands = Brand::latest()->get();
         $categories = Category::latest()->get();
         $subcategory = SubCategory::latest()->get();
         $products = Product::findOrFail($id);
         return view('backend.product.product_edit',compact('brands','categories','activeVendor','products','subcategory','multiImgs'));
     }

     public function UpdateProdeuct($data)
     {
        $product_id = $data->id;
         Product::findOrFail($product_id)->update([

            'brand_id' => $data->brand_id,
            'category_id' => $data->category_id,
            'subcategory_id' => $data->subcategory_id,
            'product_name' => $data->product_name,
            'product_slug' => strtolower(str_replace(' ','-',$data->product_name)),

            'product_code' => $data->product_code,
            'product_qty' => $data->product_qty,
            'product_tags' => $data->product_tags,
            'product_size' => $data->product_size,
            'product_color' => $data->product_color,

            'selling_price' => $data->selling_price,
            'discount_price' => $data->discount_price,
            'short_descp' => $data->short_descp,
            'long_descp' => $data->long_descp, 

            'hot_deals' => $data->hot_deals,
            'featured' => $data->featured,
            'special_offer' => $data->special_offer,
            'special_deals' => $data->special_deals, 
            'vendor_id' => $data->vendor_id,
            'status' => 1,
            'created_at' => Carbon::now(), 

        ]);
        $notification = array(
            'message' => 'Product Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.product')->with($notification); 
     }

    public function UpdateProdeuctThambnail($data)
     {
        $pro_id = $data->id;
        $oldImg = $data->old_img;
        $image = $data->file('product_thumbnail');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(800,800)->save('upload/products/thambnail/'.$name_gen);
        $save_url = 'upload/products/thambnail/'.$name_gen;

         if (file_exists($oldImg)) {
           unlink($oldImg);
        }

        Product::findOrFail($pro_id)->update([

            'product_thumbnail' => $save_url,
            'updated_at' => Carbon::now(),
        ]);

       $notification = array(
            'message' => 'Product Image Thumbnail Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification); 

     }

    public function UpdateProductMultiimage($data){

        $imgs = $data->multi_img;

        foreach($imgs as $id => $img ){
            $imgDel = MultiImg::findOrFail($id);
            unlink($imgDel->photo_name);

        $make_name = hexdec(uniqid()).'.'.$img->getClientOriginalExtension();
        Image::make($img)->resize(800,800)->save('upload/products/multi-image/'.$make_name);
        $uploadPath = 'upload/products/multi-image/'.$make_name;

        MultiImg::where('id',$id)->update([
            'photo_name' => $uploadPath,
            'updated_at' => Carbon::now(),

        ]); 
        } // end foreach

         $notification = array(
            'message' => 'Product Multi Image Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification); 

    }// End Method 
}
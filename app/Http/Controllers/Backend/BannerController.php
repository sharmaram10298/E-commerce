<?php

namespace App\Http\Controllers\Backend;

use App\Models\Banner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;

class BannerController extends Controller
{
    public function AllBanner()
    {
        $banner = Banner::latest()->get();
        return view('backend.banner.banner_all', compact('banner'));
    }
    public function AddBanner()
    {
        return view('backend.banner.banner_add');
    }

    // cat


  

    public function StoreBanner(Request $request)
    {
        $image = $request->file('banner_image');
        $name_genrate = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
        Image::make($image)->resize(768,450)->save('upload/banner/'.$name_genrate);
        $save_url = 'upload/banner/'.$name_genrate;

        Banner::insert([
            'banner_title' => $request->banner_title,
            'banner_url' => $request->banner_url,
            'banner_image' => $save_url,
        ]);
        $notification = [
            'message' => 'Banner Inserted Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->route('all.banner')->with($notification);
    }

    public function EditBanner($id)
    {
        $data = Banner::findOrFail($id);
        return view('backend.banner.banner_edit', compact('data'));
    }
    public function UpdateBanner(Request $request)
    {
        $banner_id = $request->id;
        $banner_old_image_id = $request->old_image;

        if ($request->file('banner_image')) {
            $image = $request->file('banner_image');
            $name_genrate = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            Image::make($image)->resize(2376,807)->save('upload/banner/'.$name_genrate);
            $save_url = 'upload/banner/'.$name_genrate;

            if (file_exists($banner_old_image_id)) {
                unlink($banner_old_image_id);
             }
     
            Banner::findOrFail($banner_id)->update([
                'banner_title' => $request->banner_title,
                'banner_url' => $request->banner_url,
                'banner_image' => $save_url,
            ]);
            $notification = [
                'message' => 'Banner Upadated Successfully',
                'alert-type' => 'success'
            ];
            return redirect()->route('all.banner')->with($notification);
        } else {
            Banner::findOrFail($banner_id)->update([
                'slider_title' => $request->slider_title,
                'short_title' => $request->short_title,
                
            ]);
            $notification = [
                'message' => 'Banner  Upadated  without image Successfully',
                'alert-type' => 'success'
            ];
            return redirect()->route('all.banner')->with($notification);
        }

        
    }
    public function DeleteBanner($id)
    {
        $banner_id = Banner::findOrFail($id);
        $img = $banner_id->banner_image;
        unlink($img);
        Banner::findOrFail($id)->delete();
        $notification = [
            'message' => 'Banner Deleted Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($notification);
    }
}

<?php

namespace App\Http\Controllers\Backend;

use App\Models\Slider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;

class SliderController extends Controller
{
    public function AllSlider()
    {
        $slider = Slider::latest()->get();
        return view('backend.slider.slider_all', compact('slider'));
    }
    public function AddSlider()
    {
        return view('backend.slider.slider_add');
    }

    // cat


  

    public function StoreSlider(Request $request)
    {
        $image = $request->file('slider_image');
        $name_genrate = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
        Image::make($image)->resize(2376,807)->save('upload/slider/'.$name_genrate);
        $save_url = 'upload/slider/'.$name_genrate;

        Slider::insert([
            'slider_title' => $request->slider_title,
            'short_title' => $request->short_title,
            'slider_image' => $save_url,
        ]);
        $notification = [
            'message' => 'Slider Inserted Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->route('all.slider')->with($notification);
    }

    public function EditSlider($id)
    {
        $data = Slider::findOrFail($id);
        return view('backend.slider.slider_edit', compact('data'));
    }
    public function UpdateSlider(Request $request)
    {
        $slider_id = $request->id;
        $slider_old_image_id = $request->old_image;

        if ($request->file('slider_image')) {
            $image = $request->file('slider_image');
            $name_genrate = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            Image::make($image)->resize(2376,807)->save('upload/slider/'.$name_genrate);
            $save_url = 'upload/slider/'.$name_genrate;

            if (file_exists($slider_old_image_id)) {
                unlink($slider_old_image_id);
             }
     
            Slider::findOrFail($slider_id)->update([
                'slider_title' => $request->slider_title,
                'short_title' => $request->short_title,
                'slider_image' => $save_url,
            ]);
            $notification = [
                'message' => 'Slider Upadated Successfully',
                'alert-type' => 'success'
            ];
            return redirect()->route('all.slider')->with($notification);
        } else {
            Slider::findOrFail($slider_id)->update([
                'slider_title' => $request->slider_title,
                'short_title' => $request->short_title,
                
            ]);
            $notification = [
                'message' => 'Slider  Upadated  without image Successfully',
                'alert-type' => 'success'
            ];
            return redirect()->route('all.slider')->with($notification);
        }

        
    }
    public function DeleteSlider($id)
    {
        $slider_id = Slider::findOrFail($id);
        $img = $slider_id->slider_image;
        unlink($img);
        Slider::findOrFail($id)->delete();
        $notification = [
            'message' => 'Slider Deleted Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($notification);
    }
}

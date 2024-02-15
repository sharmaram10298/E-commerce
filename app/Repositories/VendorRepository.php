<?php

namespace App\Repositories;

use Rules\Password;
use App\Models\User;
use App\Helper\ImageResponseHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Request;



class VendorRepository
{
    protected $imageResponseHelper;
    public function __construct(ImageResponseHelper $imageResponseHelper)
    {
        $this->imageResponseHelper = $imageResponseHelper;
    }
    

    public function Vendorlogout($data)
    {
        Auth::guard('web')->logout();

        $data->session()->invalidate();

        $data->session()->regenerateToken();

        return redirect()->route('vendor.login');
    }
    public function VendorLogin()
    {
        return view('vendor.vendor_login');
    }
    
    public function VendorProfile()
    {
        $id = Auth::user()->id;
        $vendorData = User::find($id);
        return $vendorData;
    }

    public function VendorProfileStore($request)
    
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        $data->name = $request->name;
        $data->username = $request->username;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;
        $data->vendor_join = $request->vendor_join; 
        $data->vendor_short_info = $request->vendor_short_info;
        $userRole = Auth::user()->role;
        if ($request->hasFile('photo')) {
            if ($data->photo && file_exists(public_path('upload/vendor_images/' . $data->photo))) {
                unlink(public_path('upload/vendor_images/' . $data->photo));
            }
            $imageFileName = $this->imageResponseHelper->ImageFile($request,$userRole);

            $data->photo = $imageFileName;
        }
    
        $data->save();
        $notification = [
            'message' => 'Vendor Profile Updated Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($notification);

    }

    public function VendorUpdatePassword($data){
        // Validation 
        $data->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed', 
        ]);

        // Match The Old Password
        if (!Hash::check($data->old_password, auth::user()->password)) {
            return back()->with("error", "Old Password Doesn't Match!!");
        }

        // Update The new password 
        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($data->new_password)

        ]);
        return back()->with("status", " Password Changed Successfully");

    } // End Mehtod 



    public function VendorRegister($data) {

        $data->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed'],
        ]);

        $user = User::insert([ 
            'name' => $data->name,
            'username' => $data->username,
            'email' => $data->email,
            'phone' => $data->phone,
            'vendor_join' => $data->vendor_join,
            'password' => Hash::make($data->password),
            'role' => 'vendor',
            'status' => 'inactive',
        ]);

          $notification = array(
            'message' => 'Vendor Registered Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('vendor.login')->with($notification);

    }

    

}
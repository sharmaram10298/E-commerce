<?php

namespace App\Repositories;

use App\Helper\ImageResponseHelper;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;



class AdminRepository
{
    protected $imageResponseHelper;
    public function __construct(ImageResponseHelper $imageResponseHelper)
    {
        $this->imageResponseHelper = $imageResponseHelper;
    }
    

    public function Adminlogout($data)
    {
        Auth::guard('web')->logout();

        $data->session()->invalidate();

        $data->session()->regenerateToken();
        $notification = [
            'message' => "Loutout successfully",
            'alert-type' => 'success'
        ];
        return redirect()->route('admin.login')->with($notification);
    }
    public function AdminLogin()
    {
        return view('admin.admin_login');
    }
    public function AdminProfile()
    {
        $id = Auth::user()->id;
        $adminData = User::find($id);
        return $adminData;
    }

    public function AdminProfileStore($request)
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        $data->name = $request->name;
        $data->username = $request->username;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;
        $userRole = Auth::user()->role;
        if ($request->hasFile('photo')) {
            if ($data->photo && file_exists(public_path('upload/admin_images/' . $data->photo))) {
                unlink(public_path('upload/admin_images/' . $data->photo));
            }
            $imageFileName = $this->imageResponseHelper->ImageFile($request,$userRole);

            $data->photo = $imageFileName;
        }
    
        $data->save();
        $notification = [
            'message' => 'Admin Profile Updated Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($notification);

    }

    public function AdminUpdatePassword($data){
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


   
    public function ActiveVendor()
    {
        $activevendor = User::where('status', 'active')->where('role', 'vendor')->latest()->get();
        return $activevendor;
    }
    public function ActiveVendorDetails($id){

        $inactiveVendorDetails = User::findOrFail($id);
        return $inactiveVendorDetails;

    }
    public function InactiveVendorApprove($data)
    {
        $vendor_id = $data->id;
        $user = User::findOrFail($vendor_id)->update([
            'status' => 'inactive',
        ]);
        $notification = [
            'message' => 'vendor Inactive  Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->route('inactive.vendor')->with($notification);
      
    }

    public function InactiveVendor()
    {
        $inactivevendor = User::where('status', 'inactive')->where('role', 'vendor')->latest()->get();
        return $inactivevendor;
    }
    
    public function InactiveVendorDetails($id){

        $inactiveVendorDetails = User::findOrFail($id);
        return $inactiveVendorDetails;

    }

    public function ActiveVendorApprove($data)
    {
        $vendor_id = $data->id;
        $user = User::findOrFail($vendor_id)->update([
            'status' => 'active',
        ]);
        $notification = [
            'message' => 'vendor Active  Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->route('active.vendor')->with($notification);
      
    }
    

}
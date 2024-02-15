<?php

namespace App\Repositories;

use App\Helper\ImageResponseHelper;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;



class UserRepository
{
    protected $imageResponseHelper;
    public function __construct(ImageResponseHelper $imageResponseHelper)
    {
        $this->imageResponseHelper = $imageResponseHelper;
    }
    

    public function Userlogout($data)
    {
        Auth::guard('web')->logout();

        $data->session()->invalidate();

        $data->session()->regenerateToken();
        $notification = [
            'message' => 'Logout Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->route('login')->with($notification);
    }
    // public function AdminLogin()
    // {
    //     return view('admin.admin_login');
    // }
    // public function AdminProfile()
    // {
    //     $id = Auth::user()->id;
    //     $adminData = User::find($id);
    //     return $adminData;
    // }

    public function UserProfileStore($request)
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
            if ($data->photo && file_exists(public_path('upload/user_images/' . $data->photo))) {
                unlink(public_path('upload/user_images/' . $data->photo));
            }
            $imageFileName = $this->imageResponseHelper->ImageFile($request,$userRole);

            $data->photo = $imageFileName;
        }
    
        $data->save();
        $notification = [
            'message' => 'User Profile Updated Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($notification);

    }

    public function UserChangePassword($data){
        // Validation 
        $data->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed', 
        ]);

        // Match The Old Password
        if (!Hash::check($data->old_password, auth::user()->password)) {
            $notification = [
                'message' => "Old Password Doesn't Match!!",
                'alert-type' => 'success'
            ];
            return back()->with($notification);
        }

        // Update The new password 
        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($data->new_password)

        ]);
        $notification = [
            'message' => 'Password Changed Successfully',
            'alert-type' => 'success'
        ];
        return back()->with($notification);

    } // End Mehtod 

    

}
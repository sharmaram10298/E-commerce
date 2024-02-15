<?php

namespace App\Http\Controllers;

use App\Repositories\AdminRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery\Generator\StringManipulation\Pass\Pass;

class AdminController extends Controller
{
    protected $adminRepository;
    public function __construct(AdminRepository $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }
    public function AdminDashboard()
    {
      
        return view('admin.index');
        
    }


    public function Adminlogout(Request $request)
    {
        return $this->adminRepository->Adminlogout($request);
    }

    public function AdminLogin()
    {
        
        return $this->adminRepository->AdminLogin();
    }

    public function AdminProfile()
    {
         $data = $this->adminRepository->AdminProfile();
         return view('admin.admin_profile_view', compact('data'));
    }
    public function AdminProfileStore(Request $request)
    {
        $this->adminRepository->AdminProfileStore($request);
        return redirect()->back();
    }

    public function AdminChangePassword()
    {
        return view('admin.admin_change_password');
    }
    public function AdminUpdatePassword(Request $request)
    {
        $this->adminRepository->AdminUpdatePassword($request);
        return redirect()->back();
    }

    public function InactiveVendor(Request $request)
    {
        $inactivevendor =   $this->adminRepository->InactiveVendor($request);
        return view('backend.vendor.inactive_vendor', compact('inactivevendor'));
    }

    public function InactiveVendorDetails($id)
    {
        $inactivevendordetails = $this->adminRepository->InactiveVendorDetails($id);
        return view('backend.vendor.inactive_vendor_details',compact('inactivevendordetails'));
    }
    public function ActiveVendorApprove(Request $request)
    {
        return $this->adminRepository->ActiveVendorApprove($request);
            
    }

    public function InactiveVendorApprove(Request $request)
    {
        return $this->adminRepository->InactiveVendorApprove($request);
            
    }
   

    public function ActiveVendor()
    {
        $activevendor = $this->adminRepository->ActiveVendor();
        return view('backend.vendor.active_vendor', compact('activevendor'));
    }
    public function ActiveVendorDetails($id)
    {
        $activevendordetails = $this->adminRepository->ActiveVendorDetails($id);
        return view('backend.vendor.active_vendor_details',compact('activevendordetails'));
    }

}

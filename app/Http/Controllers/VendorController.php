<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\VendorRepository;

class VendorController extends Controller
{
    protected $vendorRepository;
    public function __construct(VendorRepository $vendorRepository)
    {
        $this->vendorRepository = $vendorRepository;
    }
    public function VendorDashboard()
    {
        return view('vendor.index');
    }
    
    public function Vendorlogout(Request $request)
    {
        return $this->vendorRepository->Vendorlogout($request);
    }

    public function VendorLogin()
    {
        return $this->vendorRepository->VendorLogin();
    }
    public function VendorProfile()
    {
         $data = $this->vendorRepository->VendorProfile();
         return view('vendor.vendor_profile_view', compact('data'));
    }
    public function VendorProfileStore(Request $request)
    {
        $this->vendorRepository->VendorProfileStore($request);
        return redirect()->back();
    }

    public function VendorChangePassword()
    {
        return view('vendor.vendor_change_password');
    }
    public function VendorUpdatePassword(Request $request)
    {
        $this->vendorRepository->VendorUpdatePassword($request);
        return redirect()->back();
    }
   

    public function BecomeVendor()
    {
        return view('auth.become_vendor');
    }

    public function VendorRegister(Request $request)
    {
        return $this->vendorRepository->VendorRegister($request);
       
    }
}

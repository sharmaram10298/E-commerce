<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Repositories\brand\BrandRepository;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    protected  $brandRepository;
    public function __construct(BrandRepository $brandRepository)
    {
        $this->brandRepository = $brandRepository;
    }

    public function AllBrand()
    {
        $data = $this->brandRepository->AllBrand();
        return view('backend.brand.brand_all', compact('data'));
    }
    public function AddBrand()
    {
        return view('backend.brand.brand_add');
    }
    public function StoreBrand(Request $request)
    {
        $data = $this->brandRepository->StoreBrand($request);
        return $data;
    }
    public function EditBrand($id)
    {
        $editdata = Brand::findOrFail($id);
        return view('backend.brand.brand_edit', compact('editdata'));
    }
    public function UpdateBrand(Request $request)
    {
        $data = $this->brandRepository->UpdateBrand($request);
        return $data;
    }
    public function DeleteBrand($id)
    {
        return $this->brandRepository->DeleteBrand($id);
    }
}

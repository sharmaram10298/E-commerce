<?php

namespace App\Http\Controllers\Backend;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\subcategory\SubCategoryRepository;

class SubCategoryController extends Controller
{
    protected  $subcategoryRepository;
    public function __construct(SubCategoryRepository $subcategoryRepository)
    {
        $this->subcategoryRepository = $subcategoryRepository;
    }
    public function StoreSubCategory(Request $request)
    {
        $data = $this->subcategoryRepository->StoreSubCategory($request);
        return $data;
    }
    public function EditSubCategory($id)
    {
        $categories = Category::orderBy('category_name', 'ASC')->get();
        $editdata = SubCategory::findOrFail($id);
        return view('backend.subcategory.subcategory_edit', compact('editdata','categories'));
    }
    public function UpdateSubCategory(Request $request)
    {
        $data = $this->subcategoryRepository->UpdateSubCategory($request);
        return $data;
    }
    public function DeleteSubCategory($id)
    {
        return $this->subcategoryRepository->DeleteSubCategory($id);
    }
    public function AllSubCategory()
    {
        $subcategories = SubCategory::latest()->get();
        return view('backend.subcategory.subcategory_all', compact('subcategories'));
    }
    public function AddSubCategory()
    {
      $subcategories = Category::orderBy('category_name', 'ASC')->get();
        return view('backend.subcategory.subcategory_add',compact('subcategories'));
    }

    public function GetSubCategory($category_id){
        $subcat = SubCategory::where('category_id',$category_id)->orderBy('subcategory_name','ASC')->get();
            return json_encode($subcat);

    }


}

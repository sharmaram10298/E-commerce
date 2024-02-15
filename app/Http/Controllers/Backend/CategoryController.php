<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Repositories\category\CategoryRepository;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected  $categoryRepository;
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }
    public function StoreCategory(Request $request)
    {
        $data = $this->categoryRepository->StoreCategory($request);
        return $data;
    }
    public function EditCategory($id)
    {
        $editdata = Category::findOrFail($id);
        return view('backend.category.category_edit', compact('editdata'));
    }
    public function UpdateCategory(Request $request)
    {
        $data = $this->categoryRepository->UpdateCategory($request);
        return $data;
    }
    public function DeleteCategory($id)
    {
        return $this->categoryRepository->DeleteCategory($id);
    }
    public function AllCategory()
    {
        $categories = Category::latest()->get();
        return view('backend.category.category_all', compact('categories'));
    }
    public function AddCategory()
    {
       
        return view('backend.category.category_add');
    }




}

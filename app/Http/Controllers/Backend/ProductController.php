<?php

namespace App\Http\Controllers\Backend;

use App\Models\Product;
use App\Models\MultiImg;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\product\ProductRepository;

class ProductController extends Controller
{
  protected  $productRepository;
  public function __construct(ProductRepository $productRepository)
  {
      $this->productRepository = $productRepository;
  }
  public function AllProduct()
  {
    $product = Product:: latest()->get();
    return view('backend.product.product_all',compact('product'));
  }
  public function AddProduct()
  { 
    return $this->productRepository->AddProduct();
  }

  public function StoreProduct(Request $request)
  {
      $data = $this->productRepository->StoreProduct($request);
      return $data;
  }
  
  public function EditProduct($id)
  {

    return $this->productRepository->EditProduct($id);
  }

  public function UpdateProdeuct(Request $request)
    {
    return $this->productRepository->UpdateProdeuct($request);
    }
  public function UpdateProdeuctThambnail(Request $request)
    {
      return $this->productRepository->UpdateProdeuctThambnail($request);
    }
  public function UpdateProductMultiimage(Request $request)
    {
      return $this->productRepository->UpdateProductMultiimage($request);
    }

  public function MulitImageDelelte($id){
    $oldImg = MultiImg::findOrFail($id);
    unlink($oldImg->photo_name);

    MultiImg::findOrFail($id)->delete();

    $notification = array(
        'message' => 'Product Multi Image Deleted Successfully',
        'alert-type' => 'success'
    );

    return redirect()->back()->with($notification);
  }
  
  public function ProductInactive($id)
  {
    Product::findOrFail($id)->update(['status' => 0]);
    $notification = array(
      'message' => 'Product Inactive',
      'alert-type' => 'success'
    );
    return redirect()->back()->with($notification);

  }
  public function ProductActive($id){

    Product::findOrFail($id)->update(['status' => 1]);
    $notification = array(
        'message' => 'Product Active',
        'alert-type' => 'success'
    );

    return redirect()->back()->with($notification);

  }

  public function ProductDelelte($id){
    $product = Product::findOrFail($id);
    unlink($product->product_thumbnail);
    Product::findOrFail($id)->delete();

    $image = MultiImg::where('product_id', $id)->get();
    foreach ($image as $img){
      unlink($img->photo_name);
      MultiImg::where('product_id', $id)->delete();
    }

    $notification = array(
        'message' => 'Product  Deleted Successfully',
        'alert-type' => 'success'
    );

    return redirect()->back()->with($notification);
  }


}



<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\SuperCategory;
use App\Models\SuperSubCategory;
use App\Models\Category;
use App\Models\SubCategory;

class ProductController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function productList()
    {
		$productList = Product::where('deleted_at','')->get();
		return view('admin.product.productList',compact('productList'));
    }
    public function addProduct()
    {
    	$superCategoryList = SuperCategory::where('deleted_at','')->where('supercategory_status','1')->get();
    	$superSubCategoryList = SuperSubCategory::where('deleted_at','')->where('supersub_status','1')->get();
    	$categoryList = Category::where('deleted_at','')->where('status','1')->get();
    	$subCategoryList = SubCategory::where('deleted_at','')->where('status','1')->get();
    	return view('admin.product.addProduct',compact('superCategoryList','superSubCategoryList','categoryList','subCategoryList'));
    }
    public function saveProduct(Request $request)
    {
    	$request->validate([
    		'product_name' => 'required',
    		'super_cat_id' => 'required',
    		'super_sub_cat_id' => 'required',
    		'category_id' => 'required',
    		'sub_category_id' => 'required',
    		'brand_name' => 'required',
    		'price' => 'required',
    		'quantity' => 'required',
    		'color_id' => 'required',
    		'size_id' => 'required',
    		'description' => 'required',
    	]);
    	$colorId = implode(',',$request->color_id);
    	$sizeId = implode(',',$request->size_id);
    	$product = new Product;
    	$product->super_cat_id = $request->super_cat_id;
    	$product->super_sub_cat_id = $request->super_sub_cat_id;
    	$product->category_id = $request->category_id;
    	$product->sub_category_id = $request->sub_category_id;
    	$product->product_name = $request->product_name;
    	$product->brand_name = $request->brand_name;
    	$product->price = $request->price;
    	$product->description = $request->description;
    	$product->quantity = $request->quantity;
    	$product->color_id = $colorId;
    	$product->size_id = $sizeId;
    	$product->product_status = $request->product_status;
    	$product->deleted_at = '';
    	$product->product_image = '';
    	if ($request->file('product_image'))
    	{
    		$file = $request->file('product_image');
    		$image = date('y-m-d').'_'.time().$file->getClientOriginalName();
    		$file->move('uploads/product/',$image);
    		$product->product_image = $image;
    	}
    	$product->save();

    	$count = count((array)$request->file('product_multiple_image'));
    	if ($count > 0)
    	{
    		$files = $request->file('product_multiple_image');
    		foreach($files as $file)
    		{
    			$image = date('y-m-d').'_'.time().$file->getClientOriginalName();
    			$file->move('uploads/product/',$image);
    			$productImage = new ProductImage;
    			$productImage->product_id = $product->id;
    			$productImage->product_image = $image;
    			$productImage->deleted_at = '';
    			$productImage->save();
    		}
    	}
    	return redirect()->route('productList')->with('success','Product Created SuccessFully.');
    }
    public function editProduct($id)
    {
    	$productData = Product::where('deleted_at','')->findOrFail($id);
    	$superCategoryList = SuperCategory::where('deleted_at','')->where('supercategory_status','1')->get();
    	$superSubCategoryList = SuperSubCategory::where('deleted_at','')->where('supersub_status','1')->get();
    	$categoryList = Category::where('deleted_at','')->where('status','1')->get();
    	$subCategoryList = SubCategory::where('deleted_at','')->where('status','1')->get();

    	return view('admin.product.editProduct',compact('productData','superCategoryList','superSubCategoryList','categoryList','subCategoryList'));
    }
    public function updateProduct(Request $request,$id)
    {
    	$colorId = implode(',',$request->color_id);
    	$sizeId = implode(',',$request->size_id);
    	$product = Product::where('deleted_at','')->findOrFail($id);
    	$product->super_cat_id = $request->super_cat_id;
    	$product->super_sub_cat_id = $request->super_sub_cat_id;
    	$product->category_id = $request->category_id;
    	$product->sub_category_id = $request->sub_category_id;
    	$product->brand_name = $request->brand_name;
    	$product->price = $request->price;
    	$product->description = $request->description;
    	$product->quantity = $request->quantity;
    	$product->color_id = $colorId;
    	$product->size_id = $sizeId;
    	$product->product_status = $request->product_status;
    	if ($request->file('product_image'))
    	{
    		$file = $request->file('product_image');
    		$image = date('y-m-d').'_'.time().$file->getClientOriginalName();
    		$file->move('uploads/product/',$image);
    		$product->product_image = $image;
    	}
    	$product->save();

    	// $count = count($request->file('prodcut_multiple_image'));
    	// if ($count > 0)
    	// {
    	// 	$files = $request->file('prodcut_multiple_image');
    	// 	foreach($files as $file)
    	// 	{
    	// 		$image = date('y-m-d').'_'.time().$file->getClientOriginalName();
    	// 		$file->move('uploads/product/',$image);
    	// 		$productImage = new ProductImage;
    	// 		$productImage->product_id = $product->id;
    	// 		$productImage->product_image = $image;
    	// 		$productImage->deleted_at = '';
    	// 		$productImage->save();
    	// 	}
    	// }
    	return redirect()->route('productList')->with('success','Product Updated SuccessFully.');
    }
    public function deleteProduct($id)
    {
    	$product = Product::where('deleted_at','')->findOrFail($id);
    	$product->deleted_at = config('constant.CURRENT_DATETIME');
    	$product->save();

    	$productImage = ProductImage::where('deleted_at','')->where('product_id',$id)->update(['deleted_at' => config('constant.CURRENT_DATETIME')]);
    	return redirect()->back()->with('success','Product Deleted Successfully.');
    }
}

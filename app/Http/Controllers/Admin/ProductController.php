<?php

namespace App\Http\Controllers\Admin;

use App\Models\Color;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\SubCategory;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Models\SuperCategory;
use App\Models\SuperSubCategory;
use App\Models\Size;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $productList = Product::where('deleted_at','')->orderBy('id','DESC')->get();
        return view('admin.product.index',compact('productList'));
    }

    public function create()
    {
        $superCategoryList = SuperCategory::where('deleted_at','')->where('supercategory_status','1')->get();
        $superSubCategoryList = SuperSubCategory::where('deleted_at','')->where('supersub_status','1')->get();
        $categoryList = Category::where('deleted_at','')->where('status','1')->get();
        $subCategoryList = SubCategory::where('deleted_at','')->where('status','1')->get();
        $colorList = Color::where('deleted_at','')->where('status','1')->get();
        $brandList = Brand::where('deleted_at','')->where('status','1')->get();
        $sizeList = Size::where('deleted_at','')->where('status','1')->get();
        return view('admin.product.create',compact('superCategoryList','superSubCategoryList','categoryList','subCategoryList','colorList','brandList','sizeList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|max:255',
            'super_cat_id' => 'required',
            'super_sub_cat_id' => 'required',   
            'category_id' => 'required',
            'sub_category_id' => 'required',
            'brand_id' => 'required',
            'price' => 'required|numeric|gt:0',
            'discount' => 'nullable|numeric|between:1,100',
            'quantity' => 'required|gt:0',
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
        $product->brand_id = $request->brand_id;
        $product->price = $request->price;
        $product->description = $request->description??'';
        $product->quantity = $request->quantity;
        $product->used_quantity = '';
        $product->color_id = $colorId;
        $product->size_id = $sizeId;
        $product->product_status = $request->product_status;
        $product->deleted_at = '';
        $product->product_image = '';
        $product->discount = '';
        if ($request->discount)
        {
            $product->discount = $request->discount;
        }
        
        $product->fit = $request->fit??'';
        $product->pattern = $request->pattern??'';
        $product->wash = $request->wash??'';
        $product->color = $request->color??'';
        $product->neck_collar = $request->neck_collar??'';
        $product->model_fit = $request->model_fit??'';
        $product->sleeve = $request->sleeve??'';
        $product->fabric = $request->fabric??'';
        $product->productid = $request->productid??'';
        $product->sold_by = $request->sold_by??'';
        
        if ($request->file('product_image'))
        {
            $file = $request->file('product_image');
            $image = time() . "." . $file->getClientOriginalExtension();
            $file->move('uploads/product/',$image);
            $product->product_image = $image;
        }

        $product->save();
        $colorArr = $request->color_id;
        $sizeArr = $request->size_id;
        $count = count((array)$request->file('product_multiple_image'));
        if ($count > 0)
        {
            $files = $request->file('product_multiple_image');
            foreach($files as $key => $file)
            {
                // dd($colorArr[$key]);
                $image = date('ymd').time()."." .$file->getClientOriginalExtension();
                $file->move('uploads/product/',$image);
                $productImage = new ProductImage;
                $productImage->product_id = $product->id;
                $productImage->product_image = $image;
                $productImage->deleted_at = '';
                $productImage->color_id = $colorArr[$key] ?? '';
                $productImage->size_id = $sizeArr[$key] ?? '';
                $productImage->save();
            }
        }

        return redirect()->route('admin.product.index')->with('success','Product Created Successfully.');
    }

    public function edit($id)
    {
        $productData = Product::where('deleted_at','')->findOrFail($id);
        $superCategoryList = SuperCategory::where('deleted_at','')->where('supercategory_status','1')->get();
        $superSubCategoryList = SuperSubCategory::where('deleted_at','')->where('supersub_status','1')->get();
        $categoryList = Category::where('deleted_at','')->where('status','1')->get();
        $subCategoryList = SubCategory::where('deleted_at','')->where('status','1')->get();
        $colorList = Color::where('deleted_at','')->where('status','1')->get();
        $brandList = Brand::where('deleted_at','')->where('status','1')->get();
        $sizeList = Size::where('deleted_at','')->where('status','1')->get();
        return view('admin.product.edit',compact('productData','superCategoryList','superSubCategoryList','categoryList','subCategoryList','colorList','brandList','sizeList'));
    }

    public function update(Request $request,$id)
    {
        $request->validate([
            'product_name' => 'required|max:255',
            'super_cat_id' => 'required',
            'super_sub_cat_id' => 'required',   
            'category_id' => 'required',
            'sub_category_id' => 'required',
            'brand_id' => 'required',
            'price' => 'required|numeric|gt:0',
            'discount' => 'nullable|numeric|between:1,100',
            'quantity' => 'required|gt:0',
            'color_id' => 'required',
            'size_id' => 'required',
            'description' => 'required',
        ]);

        $product = Product::where('deleted_at','')->findOrFail($id);

        $colorId = implode(',',$request->color_id);
        $sizeId = implode(',',$request->size_id);
        $product->super_cat_id = $request->super_cat_id;
        $product->product_name = $request->product_name;
        $product->super_sub_cat_id = $request->super_sub_cat_id;
        $product->category_id = $request->category_id;
        $product->sub_category_id = $request->sub_category_id;
        $product->brand_id = $request->brand_id;
        $product->price = $request->price;
        $product->description = $request->description??'';
        $product->quantity = $request->quantity;
        $product->color_id = $colorId;
        $product->size_id = $sizeId;
        $product->product_status = $request->product_status;
        
        $product->fit = $request->fit??'';
        $product->pattern = $request->pattern??'';
        $product->wash = $request->wash??'';
        $product->color = $request->color??'';
        $product->neck_collar = $request->neck_collar??'';
        $product->model_fit = $request->model_fit??'';
        $product->sleeve = $request->sleeve??'';
        $product->fabric = $request->fabric??'';
        $product->productid = $request->productid??'';
        $product->sold_by = $request->sold_by??'';

        $product->discount = '';
        if ($request->discount)
        {
            $product->discount = $request->discount;
        }
        
        if ($request->file('product_image'))
        {
            $file = $request->file('product_image');
            $image = time()."." . $file->getClientOriginalExtension();
            $file->move('uploads/product/',$image);
            $product->product_image = $image;
        }

        $product->save();

        return redirect()->route('admin.product.index')->with('success','Product Updated SuccessFully.');
    }

    public function destroy(Request $request,$id)
    {
        $product = Product::where('deleted_at','')->findOrFail($id);
        $product->deleted_at = Carbon::now();
        $product->save();
        $productImage = ProductImage::where('deleted_at','')->where('product_id',$id)->update(['deleted_at' => Carbon::now()]);
        $request->session()->flash('success', 'Product deleted successfully.');
    }
    public function otherImages($id)
    {
        $productData = Product::where('deleted_at','')->findOrFail($id);
        $productImages = ProductImage::where('deleted_at','')->where('product_id',$id)->get();
        return view('admin.product.product_images',compact('productImages','productData'));
    }
    public function createImage($id)
    {
        $productData = Product::where('deleted_at','')->findOrFail($id);
        $colorList = Color::where('deleted_at','')->where('status','1')->get();
        return view('admin.product.create_image',compact('productData','colorList'));
    }
    public function storeImage(Request $request,$id)
    {
        $request->validate([
            'product_image'=>'required',
            'color_id' => 'required',
        ]);
        $productData = Product::where('deleted_at','')->findOrFail($id);
        if ($request->file('product_image'))
        {
            $productImage = new ProductImage;
            $file = $request->file('product_image');
            $image = time() . "." . $file->getClientOriginalExtension();
            $file->move('uploads/product/',$image);

            $productImage->product_id = $id;
            $productImage->product_image = $image;
            $productImage->deleted_at = '';
            $productImage->color_id = $request->color_id;
            $productImage->size_id = '';
            $productImage->save();
        }
        return redirect()->route('admin.product.image.index',$id)->with('success','image inserted');
    }
    public function editImage($id)
    {
        $productImageData = ProductImage::where('deleted_at','')->findOrFail($id);
        $colorList = Color::where('deleted_at','')->where('status','1')->get();
        return view('admin.product.edit_image',compact('productImageData','colorList'));
    }
    public function updateImage(Request $request,$id)
    {

        $productImageData = ProductImage::where('deleted_at','')->findOrFail($id);
        $productId = $productImageData->product_id;
        if ($request->file('product_image'))
        {
            $request->validate([
                'color_id' => 'required',
            ]);

            $file = $request->file('product_image');
            $image = time() . "." . $file->getClientOriginalExtension();
            $file->move('uploads/product/',$image);
            $productImageData->product_image = $image;
            $productImageData->color_id = $request->color_id;
            $productImageData->save();
        }
        return redirect()->route('admin.product.image.index',$productId)->with('success','image updated');
    }
    public function deleteImage(Request $request,$id)
    {
        $productImageData = ProductImage::where('deleted_at','')->findOrFail($id);
        $productImageData->deleted_at = Carbon::now();
        $productImageData->save();
        // return redirect()->back()->with('success','image deleted');
        $request->session()->flash('success', 'Product deleted successfully.');
    }
    public function getProduct(Request $request)
    {
        $categoryId = $request->category_id;
        $subCategoryId = $request->sub_category_id;
        $brandId = $request->brand_id;
        $colorId = $request->color_id;
        $productList = Product::select('*')->where('deleted_at', '')->where('product_status', '1')->whereRaw('used_quantity < quantity');

        if (!empty($categoryId))
        {
            $productList->where('category_id',$categoryId);
        }
        if (!empty($subCategoryId))
        {
            $productList->where('sub_category_id',$subCategoryId);
        }
        if (!empty($brandId))
        {
            $productList->where('brand_id',$brandId);
        }
        if (!empty($colorId))
        {
            $productList->whereRaw("find_in_set('" . $colorId . "',color_id)");
        }
        $productList = $productList->get();
        $option = '';
        if (count($productList) > 0)
        {
            $option .= "<option value='-1'>SELECT ALL</option>";
            foreach($productList as $product)
            {
                $option .= "<option value=".$product->id.">".$product->product_name."</option>";
            }
        }
        echo $option;exit;
    }
}

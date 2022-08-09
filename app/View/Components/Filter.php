<?php

namespace App\View\Components;

use App\Models\Size;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\View\Component;

class Filter extends Component
{
    public $categoryId;
    public $subCategoryId;


    public function __construct($categoryId=0, $subCategoryId)
    {
        $this->categoryId = $categoryId;
        $this->subCategoryId = $subCategoryId;
    }

    public function render()
    {
        $colorList = Color::where('deleted_at', '')->where('status', '1');
        $brandList = Brand::where('deleted_at', '')->where('status', '1');
        $colorIds = '';
        $brandIds = '';
        if ($this->subCategoryId)
        {
            $selectProduct = Product::where('deleted_at','')->select('color_id','brand_id')->where('product_status','1')->where('sub_category_id',$this->subCategoryId)->get();
            if (count($selectProduct) > 0)
            {
                foreach($selectProduct as $product)
                {
                    $colorIds .= $product->color_id . ',';
                    $brandIds .= $product->brand_id . ',';
                }
            }
        }
        if ($colorIds != '')
        {
            $colorIds = explode(',',$colorIds);
            $colorList->whereIn('id',$colorIds);
        }
        if ($brandIds != '')
        {
            $brandIds = explode(',',$brandIds);
            $brandList->whereIn('id',$brandIds);
        }
        $colorList = $colorList->get();
        $brandList = $brandList->get();
        if ($this->categoryId!=0) {

            $subCategoryList = SubCategory::where('deleted_at', '')->where('status', '1')->where('category_id', $this->categoryId)->get();
        } else {
            $subCategoryList = SubCategory::where('deleted_at', '')->where('status', '1')->get();
        }

        $minPrice = Product::selectRaw('MIN(CAST(price as DECIMAL(10))) as minPrice')->where('sub_category_id',$this->subCategoryId)->where('deleted_at', '')->where('product_status', '1')->whereRaw('quantity > 0')->first();
        $maxPrice = Product::selectRaw('MAX(CAST(price as DECIMAL(10))) as maxPrice')->where('sub_category_id',$this->subCategoryId)->where('deleted_at', '')->where('product_status', '1')->whereRaw('quantity > 0')->first();
        $priceRange  = array(
            "minPrice" => $minPrice->minPrice,
            "maxPrice" => $maxPrice->maxPrice,
        );
        $data['colorList'] = $colorList;
        $data['brandList'] = $brandList;
        $data['subCategoryList'] = $subCategoryList;
        $data['priceRange'] = $priceRange;

        return view('components.filter', compact('data'));
    }
}

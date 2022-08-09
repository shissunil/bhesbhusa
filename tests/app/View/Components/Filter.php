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
        $colorList = Color::where('deleted_at', '')->where('status', '1')->get();
        $brandList = Brand::where('deleted_at', '')->where('status', '1')->get();
        if ($this->categoryId!=0) {
            $subCategoryList = SubCategory::where('deleted_at', '')->where('status', '1')->where('category_id', $this->categoryId)->get();
        } else {
            $subCategoryList = SubCategory::where('deleted_at', '')->where('status', '1')->get();
        }

        $minPrice = Product::selectRaw('MIN(CAST(price as DECIMAL(10))) as minPrice')->where('deleted_at', '')->where('product_status', '1')->first();
        $maxPrice = Product::selectRaw('MAX(CAST(price as DECIMAL(10))) as maxPrice')->where('deleted_at', '')->where('product_status', '1')->first();
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

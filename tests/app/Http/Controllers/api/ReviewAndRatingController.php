<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\api\ApiBaseController as ApiBaseController;


use Illuminate\Http\Request;
use App\Models\ReviewAndRating;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\ReviewReply;
use stdClass;

class ReviewAndRatingController extends ApiBaseController
{
    public function saveReviewAndRating(Request $request)
    {
        try
        {
            $userData = $request->user();
            $userId = $userData->id;

            $orderItemId = $request->order_item_id;
            // $productId = $request->product_id;
            $rate = $request->rate;
            $message = $request->message;
            // $reviewAndRatingData = auth('sanctum')->user();
            // $userId = $reviewAndRatingData->id;
            if (isset($orderItemId) && isset($rate))
            {
                $chekOrderItemData = OrderItem::where('deleted_at','')->find($orderItemId);
                if ($chekOrderItemData)
                {
                    $orderId = $chekOrderItemData->order_id;
                    $productId = $chekOrderItemData->product_id;
                    $chekOrderData = Order::where('deleted_at','')->where('user_id',$userId)->find($orderId);
                    if ($chekOrderData)
                    {
                        $chekAlreadyInserted = ReviewAndRating::where('deleted_at','')->where('user_id',$userId)->where('order_id',$orderId)->where('order_item_id',$orderItemId)->first();
                        if ($chekAlreadyInserted)
                        {
                            $chekAlreadyInserted = new stdClass;
                            return $this->sendResponse(201,$chekAlreadyInserted,'review already added');
                        }
                        else
                        {
                            $reviewAndRating = new ReviewAndRating;
                            $reviewAndRating->user_id = $userId;
                            $reviewAndRating->order_id = $orderId;
                            $reviewAndRating->order_item_id = $orderItemId;
                            $reviewAndRating->product_id = $productId;
                            $reviewAndRating->rate = $rate;
                            $reviewAndRating->message = '';
                            if ($message)
                            {
                                $reviewAndRating->message = $message;
                            }
                            $reviewAndRating->deleted_at = '';
                            $reviewAndRating->save();
                            
                            $updateProduct = Product::find($productId);
                            if($updateProduct){
                                $rating = ReviewAndRating::where('deleted_at','')->where('product_id',$productId)->sum('rate');
                                $count = ReviewAndRating::where('deleted_at','')->where('product_id',$productId)->count();
                                if ($count != 0)
                                {
                                    $rating = $rating / $count;
                                }
                                $rating = sprintf("%.1f",$rating);
                                
                                $updateProduct->rating = $rating;
                                $updateProduct->save();
                            }
                            return $this->sendResponse(200, $reviewAndRating, "Review Submited Successfully...!");
                        }
                    }
                    else
                    {
                        $chekOrderData = new stdClass;
                        return $this->sendResponse(201,$chekOrderData,'Order item data not found');
                    }
                }
                else
                {
                    $chekOrderItemData = new stdClass;
                    return $this->sendResponse(201,$chekOrderItemData,'Order item data not found');
                }
            }
            else
            {
                $data = new stdClass;
                return $this->sendResponse(201,$data,'Fill all the required field');
            } 
        }
        catch(\Exception $e)
        {
            return $this->sendError(201, $e->getMessage());
        }
    }
    public function productReview(Request $request)
    {
        try
        {
            $pageNumber = isset($request->page_number) ? $request->page_number : 1;
            $pageSize = isset($request->page_size) ? $request->page_size : 10;
            $productId = $request->product_id;
            if (isset($productId))
            {
                $reviewCollection = ReviewAndRating::where('deleted_at','')->where('product_id',$productId)->paginate($pageSize,'*','page',$pageNumber);
                // $$reviewCollection = $reviewCollection;
                $reviewData = $reviewCollection->items();

                $totalPages = $reviewCollection->lastPage();
                $totalCount = $reviewCollection->total();
                $pageNumber = $reviewCollection->currentPage();
                $nextPage = $reviewCollection->nextPageUrl()?true:false;
                $prevPage = $reviewCollection->previousPageUrl()?true:false;
                // $reviewDataArr = $reviewData->toArray();
                $reviewArray = array();
                if (!empty($reviewData))
                {
                    foreach($reviewData as $value)
                    {
                        $userData = User::find($value->user_id);
                        $value->customer_name = $userData->first_name. ' '. $userData->last_name;
                        $value->review_date = date('d M, Y',strtotime($value->created_at));

                        $reviewReply = ReviewReply::where('deleted_at','')->where('review_id',$value->id)->get();
                        $value->review_reply = $reviewReply;
                    }
                    $reviewDetail = ReviewAndRating::where('deleted_at','')->where('product_id',$productId)->paginate($pageSize,'*','page',$pageNumber);
                    $count = $reviewDetail->count();
                    $sumOfRate = $reviewDetail->sum('rate');
                    $avarageRate = $reviewDetail;

                    $oneStar = $reviewDetail->where('rate','<=','1')->count();
                    $twoStar = $reviewDetail->where('rate','<=','2')->count();
                    $threeStar = $reviewDetail->where('rate','<=','3')->count();
                    $fourStar = $reviewDetail->where('rate','<=','4')->count();
                    $fiveStar = $reviewDetail->where('rate','<=','5')->count();

                    $oneStar = 100 * $oneStar;
                    $twoStar = 100 * $twoStar;
                    $threeStar = 100 * $threeStar;
                    $fourStar = 100 * $fourStar;
                    $fiveStar = 100 * $fiveStar;
                    if ($count != 0)
                    {
                        $avarageRate = $sumOfRate / $count;

                        $oneStar = $oneStar / $count;
                        $twoStar = $twoStar / $count;
                        $threeStar = $threeStar / $count;
                        $fourStar = $fourStar / $count;
                        $fiveStar = $fiveStar / $count;
                    }
                    // $value->review_reply = [];
                    $reviewArray = array(
                        'total_count' => (string)$count,
                        'avarage_rate' => (string)$avarageRate,
                        'one_star' => (int)$oneStar,
                        'two_star' => (int)$twoStar,
                        'three_star' => (int)$threeStar,
                        'four_star' => (int)$fourStar,
                        'five_star' => (int)$fiveStar,
                        'review_detail' => $reviewData
                    );
                    // $reviewArray->reviewDetail = $reviewData;
                    // return $this->sendResponse(200,$reviewArray,'product review detail');
                    return $this->paginatResponse(200, $reviewArray , $totalPages, $totalCount, $pageNumber, $nextPage, $prevPage, 'product review detail');
                }
                else
                {
                    // $reviewArray = array(
                    //     'total_count' => '0',
                    //     'avarage_rate' => '0',
                    //     'one_star' =>  '0 %',
                    //     'two_star' =>  '0 %',
                    //     'three_star' => '0 %',
                    //     'four_star' => '0 %',
                    //     'five_star' => '0 %',
                    //     'review_detail' => $reviewData
                    // );
                    $data = new stdClass;
                    return $this->sendResponse(201,$data,'review not found');
                }
            }
            else
            {
                $data = new stdClass;
                return $this->sendResponse(201,$data,'Please fill all the required field');
            }
        }
        catch(\Exception $e)
        {
            return $this->sendError(201, $e->getMessage());
        }
    }
}

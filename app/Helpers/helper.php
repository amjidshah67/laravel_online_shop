<?php

use App\Models\Category;
use App\Models\ProductImage;
use App\Models\Order;
use App\Models\Country;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderEmail;

    function getCategories(){
        return Category::orderBy('name','ASC')
                    ->with('sub_category')
                    ->orderBy('id','DESC')
                    ->where('status',1)
                    ->where('showHome','Yes')
                    ->get();
    }
    function getProductImage($productId)
    {
        return ProductImage::where('product_id',$productId)->first();
    }
    function orderEmail($orderId, $userType="customer")
    {
        $order = Order::where('id', $orderId)->with('items')->first();

        if($userType == 'customer'){
            $subject = 'Thanks For your order';
            $email = $order->email;
        }
            else{
                $subject = 'You have received an order';
                $email = env('ADMIN_EMAIL');
            }


        $mailData = [
            'subject' => $subject,
            'order' => $order,
            'userType' => $userType
        ];

        // Ensure the email address is valid
        $email = 'amjid1234@example.com'; // Change this to a valid email address

        Mail::to($email)->send(new OrderEmail($mailData));
    }

    function getCountryInfo($id){
        return Country::where('id',$id)->first();
    }

?>

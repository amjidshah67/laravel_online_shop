<?php

namespace App\Http\Controllers;

use App\Models\Product;
use http\Env\Response;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $product = Product::with('product_images')->find($request->id);
        if ($product == null){
                return response()->json([
                    'status' => false,
                    'message' => 'Product Not Found',
                ]);
        }
        if (Cart::count() > 0 ){
//                echo "product already is cart";
//            Product found in cart
//            check if this  product already in the cart
//            Return the message that product  already added in your cart
//            If product not found in the cart ,then add product in cart

            $cartContent = Cart::content();
            $productAlreadyExist = false;
            foreach ($cartContent as $item ){
                if ($item->id == $product->id){
                    $productAlreadyExist = true;
                }
                if ($productAlreadyExist == false){
                    Cart::add($product->id, $product->title, 1, $product->price ,
                        ['productImage' => (!empty($product->product_images)) ?
                            $product->product_images->first() : '' ]);
                    $status  = true;
                    $message =  $product->title.' added in your cart successfully.';
                    session()->flash('success',$message);

                }else{
                    $status  = false;
                    $message = $product->title.' already added in cart';
                }
            }
        }else{
            Cart::add($product->id, $product->title, 1, $product->price ,
                ['productImage' => (!empty($product->product_images)) ?
                    $product->product_images->first() : '' ]);
            $status  = true;
            $message =  $product->title.' added in your cart successfully.';
            session()->flash('success',$message);

        }
        return response()->json([
           'status' => $status,
            'message' => $message,
        ]);
    }
    public function cart()
    {
        $cartContent = Cart::content();
//        dd($cartContent);
        $data['cartContent'] = $cartContent;
        return view('front.cart', $data);
    }
    public function updateCart(Request $request)
    {
        $rowId = $request->rowId;
        $qty = $request->qty;
        $itemInfo = Cart::get($rowId);
        $product = Product::find($itemInfo->id);
//        check qty available in stock
        if ($product->track_qty == 'Yes'){
            if ($qty <= $product->qty){
                Cart::update($rowId,$qty);
                $message = 'cart updated successfully';
                $status = true;
                session()->flash('success',$message);
            }else{
                $message = 'Requested qty('.$qty.') no avialable in stock . ';
                $status = false;
                session()->flash('error',$message);
            }
        }else{
            Cart::update($rowId,$qty);
            $message = 'cart updated successfully';
            $status = true;
            session()->flash('success',$message);
        }
        return response()->json([
            'status' => $status,
            'message' => $message,
        ]);
    }
    public function deleteItem(Request $request)
    {
        $itemInfo = Cart::get($request->rowId);

        if ($itemInfo == null){
            $errorMessage = 'Item not found in cart';

            session()->flash('error',$errorMessage);

            return response()->json([
                'status' => false,
                'message' => $errorMessage,
            ]);
        }
        Cart::remove($request->rowId);

        $successMessage = 'Item remove from from cart successfully';

        session()->flash('success',$successMessage);

        return response()->json([
            'status' => true,
            'message' => $successMessage,
        ]);
    }
}

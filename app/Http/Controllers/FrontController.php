<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrontController extends Controller
{
    public function index(){
        $product = Product::where('is_featured','Yes')->orderBy('id','DESC')
            ->where('status',1)->take(8)->get();
        $data['featuredProduct'] = $product;

        $latestProduct = Product::orderBy('id','DESC')->where('status',1)
                    ->take(8)->get();
        $data['latestProduct'] = $latestProduct;

        return view('front.home',$data);
    }
    public function addToWishlist(Request $request){
        if (!Auth::check()) {
            session(['url.intended' => url()->previous()]);
            return response()->json([
                'status' => false,
                'message' => 'User is not logged in'
            ]);
        }

        $product = Product::where('id',$request->id)->first();

        if($product == null){
            return response()->json([
                'status' => true,
                'message' => '<div class="alert alert-danger">Product not found.</div>'
            ]);
        }

        Wishlist::updateOrCreate(
                [
                    'user_id' => Auth::user()->id,
                    'product_id' => $request->id,
                ],
        [

        ]
        );

        // $wishlist = new Wishlist;
        // $wishlist->user_id = Auth::user()->id;
        // $wishlist->product_id = $request->id;
        // $wishlist->save();

        return response()->json([
            'status' => true,
            'message' => '<div class="alert alert-success"><strong>"'.$product->title.'"</strong> added to your wishlist</div>'
        ]);
    }

}

<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Image;

class ProductImageController extends Controller
{
    public function update(Request $request)
    {
        // Check if the request has the 'image' key
        if (!$request->hasFile('image')) {
            return response()->json([
                'status' => false,
                'message' => 'No image provided.',
            ]);
        }

        $image = $request->file('image'); // Fix the typo here
        $ext = $image->getClientOriginalExtension();
        $sourcePath = $image->getPathName();

        $productImage = new ProductImage();
        $productImage->product_id = $request->product_id;
//        $productImage->image = null;
//        $productImage->save();

        $imageName = $request->product_id . '-' . $productImage->id . '-' . time() . '.' . $ext;
        $productImage->image = $imageName;
        $productImage->save();

        // Large Image
        $destpath = public_path() . '/uploads/product/large/' . $imageName;
        $image = Image::make($sourcePath);
        $image->resize(1400, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $image->save($destpath);

        // Small Image
        $destpath = public_path() . '/uploads/product/small/' . $imageName;
        $image = Image::make($sourcePath);
        $image->fit(300, 300);
        $image->save($destpath);

        return response()->json([
            'status' => true,
            'image_id' => $productImage->id,
            'imagePath' => asset('uploads/product/small/' . $productImage->image),
            'message' => 'Image saved successfully',
        ]);
    }
    public function destroy(Request $request){

        $productImage = ProductImage::find($request->id);

        if (empty($productImage)){
            return response()->json([
                'status' => false,
                'message' => 'image not Found',
            ]);
        }
//        Delete Images From Folder
        File::delete(public_path('uploads/product/large/'.$productImage->image));
        File::delete(public_path('uploads/product/small/'.$productImage->image));

        $productImage->delete();

        return response()->json([
            'status' => true,
            'message' => 'Record Deleted successfully',
        ]);

    }
}

<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use Illuminate\Support\Facades\File;
use Image;

class CategoryController extends Controller
{
    public function index(Request $request){
        $categories = Category::latest();
        if (!empty($request->get('keyword'))){
            $categories = $categories->where('name','like','%'.$request->get('keyword').'%');
        }

        $categories = $categories->paginate(10);
        return view('admin.category.list',compact('categories'));
    }
    public function create(){
        return view('admin.category.create');
    }
    public function store(Request $request){
            $validator = Validator::make($request->all(),[
                'name' => 'required',
                'slug' => 'required|unique:categories',
                ]);

            if ($validator->passes()){

                $category = new Category();
                $category->name = $request->name;
                $category->slug = $request->slug;
                $category->status = $request->status;
                $category->save();

                //Save Image Here
                if (!empty($request->image_id)){
                    $tempImage = TempImage::find($request ->image_id);
                    $extArray = explode('.',$tempImage->name);
                    $ext = last($extArray);

                    $newImageName = $category->id.'.'.$ext;
                    $sPath = public_path().'/temp/'.$tempImage->name;
                    $dPath = public_path().'/uploads/category/'.$newImageName;
                    file::copy($sPath,$dPath);

//                    Generate Image thumbal
                    $dPath = public_path().'/uploads/category/thumb/'.$newImageName;
                    $img = Image::make($sPath);
//                    $img->resize(300, 300);
                    $img->fit(300, 300, function ($constraint) {
                        $constraint->upsize();
                    });
                    $img->save($dPath);


                    $category->image = $newImageName;
                    $category->save();
                }

                $request->session()->flash('success','category added successfully');

                return response()->json([
                    'status' => true,
                    'message' =>'categories added successfully'
                ]);
            }else{
                return response()->json([
                    'status' => false,
                    'errors' =>$validator->errors()
                ]);
            }
    }
    public function edit($categoryId, Request $request){

        $category = Category::Find($categoryId);
    if (empty($category)){
        return redirect()->route('categories.index');
    }
        return view('admin.category.edit',compact('category'));
    }

    public function update($categoryId, Request $request)
    {
        $category = Category::find($categoryId);

        if (empty($category)) {
            $request->session()->flash('erorr','category not found');

            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Category not found'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,' . $category->id . ',id',
        ]);

        if ($validator->passes()) {
            // Save Image Here
            if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray);

                $newImageName = $category->id . '-' . time() . '.' . $ext;
                $sPath = public_path() . '/temp/' . $tempImage->name;
                $dPath = public_path() . '/uploads/category/' . $newImageName;
                File::copy($sPath, $dPath);

                // Generate Image thumbnail
                $dThumbPath = public_path() . '/uploads/category/thumb/' . $newImageName;
                $img = Image::make($sPath);
//                $img->resize(300, 300);
                $img->fit(300, 300, function ($constraint) {
                    $constraint->upsize();
                });
                $img->save($dThumbPath);

                // Delete old image
                $oldImage = $category->image;
                File::delete(public_path() . '/uploads/category/' . $oldImage);
                File::delete(public_path() . '/uploads/category/thumb/' . $oldImage);

                $category->image = $newImageName;
            }

            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->save();

            $request->session()->flash('success', 'Category updated successfully');

            return response()->json([
                'status' => true,
                'message' => 'Category Updated Successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function destroy($categoryId, Request $request){

        $category = Category::find($categoryId);

        if (empty($category)) {
            $request->session()->flash('error', 'Category not found');
            return response()->json([
                'status' => true,
                'message' =>'Category not found'
            ]);
        }
            File::delete(public_path() . '/uploads/category/' . $category->image);
            File::delete(public_path() . '/uploads/category/thumb/' . $category->image);

            $category->delete();
            $request->session()->flash('success', 'Category deleted successfully');

            return response()->json([
                'status' => true,
                'message' =>'categories deleted successfully'
            ]);
        }
}

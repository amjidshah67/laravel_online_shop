<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brands;
use Illuminate\Support\Facades\Validator;

class BrandsController extends Controller
{
    public function index(Request $request){
        $brands = Brands::latest('id');
        if($request->get('keyword')){
            $brands = $brands->where('name','like','%'.$request->keyword . '%');
        }
        $brands = $brands->paginate(10);
        return view('admin.brands.list' , compact('brands'));
    }
    public function create(){
        return view('admin.brands.create');
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:brands',
        ]);
        if ($validator->passes()){
            $brand = new Brands();
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();

            $request->session()->flash('success' , 'Brands Created Successfully.');

            return response()->json([
                'status' => true,
                'message' => 'Brands added successfully.'
            ]);

        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function edit($id , Request $request){

        $brand = Brands::find($id);
        if (empty($brand)){
            $request->session()->flash('error' , 'Record not found');
                return redirect()->route('brands.index');
        }
        $data['brand'] = $brand;
        return view('admin.brands.edit',$data);
    }
    public function update($id,Request $request){

        $brand = Brands::find($id);

        if (empty($brand)){
            $request->session()->flash('error' , 'Record not found');
            return response([
                'status' => false,
                'notfound' => true
            ]);
        }
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,' . $brand->id . ',id',
            'status' => 'required'
        ]);
        if ($validator->passes()){

            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();

            $request->session()->flash('success' , 'brand updated Successfully.');

            return response([
                'status' => true,
                'message' => 'brand update Successfully.'
            ]);
        } else{
            return response([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function destroy($id, Request $request){
        $brand = Brands::find($id);

        if (empty($brand)) {
            $request->session()->flash( 'error','Record not found' );
            return response([
                'status' => false,
                'notfound' => true
            ]);
        }
            $brand->delete();

            $request->session()->flash('success','Brands deleted Successfully');
            return response([
                'status' => true,
                'massage' => 'Brands deleted Successfully.'
            ]);
    }
}

<?php

namespace App\Http\Controllers\admin;

use App\Models\Brand;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BrandController extends Controller
{

    public function index( Request $request){
        $brands = Brand::paginate(10);

        if($request->get('keyword')){
            $brands = $brands->where('name','like','%'.$request->word.'%');
        }
        return view('admin.brands.list', compact('brands'));
    }
    public function create(){
        return view('admin.brands.create');
    }

    public function store (Request $request){
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'slug'=>'required|unique:brands',
            'status' => 'required'
        ]);

        if($validator->passes()){
            $brand = new Brand();
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();

            return response()->json([
                'status' => true,
                'message' => 'Brand added Successfully.'

            ]);

        }else{
            return response()->json([
                'status'=> false,
                'errors' =>$validator->errors()

            ]);

        }
    }

    // public function edit( Request $request, Brand $brand){

    //     $brands = Brand::find($brands);

    //     if(empty($brand)){
    //         $request->session()->flash('error','Record not found');

    //         return redirect ()->route('brand.index');
    //     }

    //     return view('admin.brands.edit');
    // }
    public function edit(Brand $brand)
{
    // Check if the brand exists
    if (!$brand) {
        session()->flash('error', 'Record not found');
        return redirect()->route('brand.index');
    }

    return view('admin.brands.edit', compact('brand'));
}


    public function update($id, Request $request ){

        $brands = Brand::find($id);

        if(empty($brands)){
            $request->session()->flash('error','Record not found');

            // return redirect ()->route('brands.index');
            return response()->json([
                'status' => false,
                'notFound' => true,
            ]);
        }
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'slug' => 'required|unique:categories,slug,'.'$brand->id.','id',
            'status' => 'required'
        ]);

        if($validator->passes()){
            // $brand = new Brand();
            $brands->name = $request->name;
            $brands->slug = $request->slug;
            $brands->status = $request->status;
            $brands->save();

            return response()->json([
                'status' => true,
                'message' => 'Brand updated Successfully.'

            ]);

        }else{
            return response()->json([
                'status'=> false,
                'errors' =>$validator->errors()

            ]);

        }

    }
}

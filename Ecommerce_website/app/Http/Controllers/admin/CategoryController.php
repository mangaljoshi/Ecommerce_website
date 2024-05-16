<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
class CategoryController extends Controller
{
    public function index() {
        $categories = Category :: latest()->paginate(10);
        //    dd($categories);
        return view('admin.category.list',compact('categories'));

    }

    public function create() {
    //  echo "categories Create";
    return view('admin.category.create');

    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:categories'
        ]);

        if ($validator->passes()){

            $category = new Category();
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->save();

            $request-> $request->session()->flash('success', 'Category added successfuly');
             
            return response()->json([
                'status' => true,
                'message' =>  $validator->errors()
            ]);

        }else{
            return response()->json([
                'status' => false,
                'errors' =>  'Category added successfuly'
            ]);
        }

    }

    public function edit(){

    }

    public function update(){

    }

    public function destroy(){

    }
}

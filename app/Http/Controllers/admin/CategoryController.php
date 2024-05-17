<?php

namespace App\Http\Controllers\admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;


class CategoryController extends Controller
{
    public function index( Request $request) {

        $categories = Category :: latest();
        if(!empty($request->get('keyword'))){
            $categories = $categories->where('name','like', '%'.$request->get('keyword').'%');
        }
        $categories = Category::latest()->paginate(10);

        return view('admin.category.list',compact('categories'));

    }

    public function create() {
    //  echo "categories Create";

    return view('admin.category.create');

    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories',
            'status' => 'required|numeric',
            'image' => 'nullable',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->toArray(),
            ], 422);
        }
    
        $category = new Category();
        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->status = $request->status;
        $category->image = 'default_image.jpg';
        $category->save();
    
        $request->session()->flash('success', 'Category added successfully');
    
        return response()->json([
            'status' => true,
            'message' => 'Category added successfully',
        ]);
    }
    
    public function edit(){

    }

    public function update(){

    }

    public function destroy(){

    }
}

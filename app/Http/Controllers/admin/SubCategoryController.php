<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\SubCategory;



class SubCategoryController extends Controller
{

    public function index(Request $request){
        $sub_categories = subCategory ::select('sub_categories.*','categories.name as categoryName')->latest('sub_categories.id')->leftJoin('categories','categories.id','sub_categories.category_id');

        if(!empty($request->get('keyword'))){
            $sub_categories = $sub_categories->where('sub_categories.name','like', '%'.$request->get('keyword').'%');
            $sub_categories = $sub_categories->orwhere('categories.name','like', '%'.$request->get('keyword').'%');
        }
        $sub_categories = subCategory::latest()->paginate(10);

        return view('admin.sub_category.list',compact('sub_categories'));
        }
    public function create()
    {
        $sub_categories = Category::all(); // Assuming you're fetching categories from the database
        return view('admin.sub_category.create', ['categories' => $sub_categories]);
    }
    

    public function store( Request $request){
         $validator = Validator::make($request->all(),[

            'name' => 'required',
            'slug'  => 'required|unique:sub_categories',
            // 'category_id' =>'required',
            'status' => 'required',
         ]);

         if (Category::where('id', $request->category)->exists()) {
            // Create a new SubCategory instance
            $subCategory = new SubCategory();
        
            // Set the attributes
            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;
            $subCategory->status = $request->status;
            $subCategory->category_id = $request->category;

            $subCategory->save();

            $request->session()->flash('success', 'Sub Category Created Successfully.');
            return response([
                'status' => true,
                'message' => 'Sub Category Created Successfuly',
            ]);

         }else{
            return response([
                'status' => false,
                'errors' => $validator->errors()
            ]);
         }
    }

    public function edit($id, Request $request){
        $subCategory = SubCategory::find($id);
        if(empty($subCategory)){
            $request->session()->flash('error','Record not found');
            return redirect()->route('sub_categories.index');
        }
        $sub_categories = SubCategory::all(); 
        $data['categories']= $sub_categories; 
        return view('admin.sub_category.edit', ['categories' => $sub_categories]);

    }

    public function update($id, Request $request){

        $subCategory = SubCategory::find($id);

        if(empty($SubCategory)){
            $request->session()->flash('error','Record not found');
            return response([
                'status' => false,
                'notFound'=> true,
            ]);
            
        }


        $validator = Validator::make($request->all(),[

            'name' => 'required',
            'slug' => 'required|unique:sub_categories,slug,'.$subCategory->id.',id',
            // 'category_id' =>'required',
            'status' => 'required',
         ]);

         if ($validator->passes()){
            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;
            $subCategory->status = $request->status;
            $subCategory->category_id = $request->category;
            $subCategory->save();
            
            return redirect()->route('sub_categories.index');

            $request->session()->flash('success', 'Category updated successfully');
            return response([
                'status' => true,
                'message' => 'Sub Category Update Successfuly',
            ]);

         }else{
            return response([
                'status' => false,
                'errors' => $validator->errors()
            ]);

            
         }

    }

    public function destroy($id, Request $request){

        $SubCategory = SubCategory::find($id);

        if(empty($SubCategory)){
            $request->session()->flash('error','Record not found');
            return response([
                'status' => false,
                'notFound'=> true,
            ]);
            return redirect()->route('sub_categories.index');
        }

        $SubCategory->delete();

        $request->session()->flash('success', 'Category deleted successfully');
            return response([
                'status' => true,
                'message' => 'Sub Category Update Successfuly',
            ]);
    }
}

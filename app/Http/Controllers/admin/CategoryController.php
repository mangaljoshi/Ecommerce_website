<?php

namespace App\Http\Controllers\admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\TempImage;
use illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;




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
        $category->save();

        // Save Image Here

        if(!empty($request->image_id)){
            $tempImage = TempImage::find($request->image_id);

            $extArray = explode('.', $tempImage->name);
            $ext =last($extArray);
            $newImageName = $category->id.'.'.$ext;
            $spath = public_path()."temp/".$tempImage->name;
            $dpath  = public_path().'/uploads/category/'.$newImageName;
            File::copy($spath,$dpath);

            //Generate Image Thumbnail
            $dpath  = public_path().'/uploads/category/thumb/'.$newImageName;
            $img = Image::make($spath);
            $img->fit(450, 600, function ($constraint) {
                $constraint->upsize();
            });
            $img->save($dpath);


            $category->image = $newImageName;
        $category->save();
        }
    
        $request->session()->flash('success', 'Category added successfully');
        $spath = public_path()."temp/".$tempImage->name;
        $dpath  = public_path().'/uploads/category/'.$newImageName;
        return response()->json([
            'status' => true,
            'message' => 'Category added successfully',
        ]);
    }
    
    public function edit($categoryId, Request $request){
        //   echo $categoryId;
        $category =Category::find($categoryId);
        if(empty($category)){
            return redirect()->route('categories.index');
        }
        return view('admin.category.edit',compact('category'));
    }

    public function update($categoryId, Request $request){
        // Find the category by its ID
        $category = Category::find($categoryId);
        
        // Check if the category exists
        if(!$category){
            $request->session()->flash('error','Category not found');
            return response()->json([
                'status' => false,
                'notfound' => true,
                'message' => 'Category Not Found',
            ]);
        }
    
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,'.$categoryId.',id',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', 
            'status' => 'required|in:0,1',
        ]);
        
        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->toArray(),
            ], 422);
        }
    
        // Update category data
        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->status = $request->status;
    
        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $imagePath = public_path('uploads/category/');
            
            // Delete old image if exists
            if ($category->image && File::exists($imagePath . $category->image)) {
                File::delete($imagePath . $category->image);
            }
    
            // Resize and save image
            $image->move($imagePath, $imageName);
            $category->image = $imageName;
        }
    
        // Save the updated category
        $category->save();
    
        // Flash success message
        $request->session()->flash('success', 'Category updated successfully');
    
        // Return success response
        return response()->json([
            'status' => true,
            'message' => 'Category updated successfully',
        ]);
    }

    public function destroy($categoryId, Request $request){
        // Find the category by its ID
        $category = Category::find($categoryId);
        
        // Check if the category exists
        if(empty(!$category)){
            // return redirect()->route('categories.index');
            $request->session()->flash('error','Category not found');
            return response()->json([
                'status' => true,
                'message' => 'Category not found'
            ]);

        }
    
        // Delete the category
        $category->delete();
    
        // Flash success message
        $request->session()->flash('success', 'Category deleted successfully');
    
        // Return JSON response
        return response()->json([
            'status' => true,
            'message' => 'Category deleted successfully'
        ]);
    }
    
}

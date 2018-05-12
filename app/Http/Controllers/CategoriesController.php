<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Category;

class CategoriesController extends Controller
{

    /**
     * render the category create view
     */
    public function index()
    {
        return view('categories.index');
    }
    /**
     * Create a new category
     * @param Request $request
     * @return redirect route
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2|max:255',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('/category/create')
                        ->withErrors($validator)
                        ->withInput();
        }
        //save category -> add user and department it is created under
        $category = new Category($request->all());
        $department = \Auth::User()->department_id;
        $category->department()->associate($department);
        $category->user()->associate(\Auth::User());
        //check if that department already has that category
        if(Category::where('name', $request['name'])
                ->where('department_id', $department)->count() > 0){
            session()->flash("message", 
                "Category already exists with that name for this department");
            return back()->withInput();
        }
        if($category->save()){
            session()->flash("message", "Category created successfully.");
            return redirect(route('create_task'));
        }else{
            session()->flash("message", "Category not created successfully.");
            return back()->withInput();
        }

    }
}

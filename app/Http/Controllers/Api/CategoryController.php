<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\CategoryService;
use Illuminate\Http\Request;

/**
 * @group Category management
 *
 * APIs for managing Categories
 */

class CategoryController extends Controller
{

    /**
     * @var CategoryService
     */
    private $CategoryService;

    public function __construct(CategoryService $CategoryService){
        $this->CategoryService = $CategoryService;
    }
    /**
     * Display a listing of the resource.
     *
     * @authenticated
     *
     * @queryParam name string name of the Category .No-example
     * @queryParam page int page of the answer collection.No-example
     * @queryParam limit int per page number.No-example
     * @queryParam sortBy string sortBy order.
     * @return \Illuminate\Http\Jsonresponse
     */
    public function index(Request $request)
    {
        //
        $categories =  $this->CategoryService->showAllCategories($request->withRelations,auth()->user()->id,$request->name,$request->page,$request->limit,$request->sortBy);
        if ($request->page){
            $categories= [
                'data'=>$categories->items(),
                'page'=>$request->page,
                'limit'=>$request->limit?$request->limit:15,
                'total'=>$categories->total(),

            ];
        }
        return response()->json($categories,200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @authenticated
     *
     * @param  \App\Http\Requests\StoreCategoryRequest  $request
     * @return \Illuminate\Http\Jsonresponse
     */
    public function store(StoreCategoryRequest $request)
    {
        //
        $input = $request->all();
        $input['user_id']=auth()->user()->id;
        $category = $this->CategoryService->storeCategory($input);
        return response()->json($category,200);
    }

    /**
     * Display the specified resource.
     *
     * @authenticated
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Jsonresponse
     */
    public function show(Request $request,Category $category)
    {
        //
        $category = $this->CategoryService->getCategory($category->id,$request->withRelations);

        return response()->json($category,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @authenticated
     *
     * @param  \App\Http\Requests\UpdateCategoryRequest  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Jsonresponse
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        //
        $input = $request->all();
        $this->CategoryService->updateCategory($input,$category);
        return response()->json('success',200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @authenticated
     *
     * @param  \App\Models\Category  $Category
     * @return \Illuminate\Http\Jsonresponse
     */
    public function destroy(Category $category)
    {
        //
        $this->CategoryService->destroyCategory($category);
        return response()->json('success',200);
    }
}

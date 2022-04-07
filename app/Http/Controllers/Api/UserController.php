<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

/**
 * @group User management
 *
 * APIs for managing Categories
 */

class UserController extends Controller
{

    /**
     * @var UserService
     */
    private $UserService;

    public function __construct(UserService $UserService){
        $this->UserService = $UserService;
    }
    /**
     * Display a listing of the resource.
     *
     * @authenticated
     *
     * @queryParam name string name of the User .No-example
     * @queryParam page int page of the answer collection.No-example
     * @queryParam limit int per page number.No-example
     * @queryParam sortBy string sortBy order.
     * @return \Illuminate\Http\Jsonresponse
     */
    public function index(Request $request)
    {
        //
        $difficulties =  $this->UserService->showAllUsers($request->withRelations,$request->name,$request->page,$request->limit,$request->sortBy);
        if ($request->page){
            $difficulties= [
                'data'=>$difficulties->items(),
                'page'=>$request->page,
                'limit'=>$request->limit?$request->limit:15,
                'total'=>$difficulties->total(),

            ];
        }
        return response()->json($difficulties,200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @authenticated
     *
     * @param  \App\Http\Requests\StoreUserRequest  $request
     * @return \Illuminate\Http\Jsonresponse
     */
    public function store(StoreUserRequest $request)
    {
        //
        $input = $request->all();
        $input['user_id']=auth()->user()->id;
        $User = $this->UserService->store($input);
        return response()->json($User,200);
    }

    /**
     * Display the specified resource.
     *
     * @authenticated
     *
     * @param  \App\Models\User  $User
     * @return \Illuminate\Http\Jsonresponse
     */
    public function show(Request $request,User $User)
    {
        //
        $User = $this->UserService->getUser($User->id,$request->withRelations);

        return response()->json($User,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @authenticated
     *
     * @param  \App\Http\Requests\UpdateUserRequest  $request
     * @param  \App\Models\User  $User
     * @return \Illuminate\Http\Jsonresponse
     */
    public function update(UpdateUserRequest $request, User $User)
    {
        //
        $input = $request->all();
        $this->UserService->update($input,$User);
        return response()->json('success',200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @authenticated
     *
     * @param  \App\Models\User  $User
     * @return \Illuminate\Http\Jsonresponse
     */
    public function destroy(User $User)
    {
        //
        $this->UserService->destroy($User);
        return response()->json('success',200);
    }
}

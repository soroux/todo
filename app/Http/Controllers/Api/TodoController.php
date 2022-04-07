<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use App\Http\Requests\StoreTodoRequest;
use App\Http\Requests\UpdateTodoRequest;
use App\Services\TodoService;
use Illuminate\Http\Request;

/**
 * @group Todo management
 *
 * APIs for managing Todo
 */

class TodoController extends Controller
{
    /**
     * @var TodoService
     */
    private $TodoService;

    public function __construct(TodoService $TodoService){
        $this->TodoService = $TodoService;
    }
    /**
     * Display a listing of the resource.
     *
     * @authenticated
     *
     * @queryParam name string name of the Todo .No-example
     * @queryParam page int page of the answer collection.No-example
     * @queryParam limit int per page number.No-example
     * @queryParam sortBy string sortBy order.
     * @return \Illuminate\Http\Jsonresponse
     */
    public function index(Request $request)
    {
        //
        $todos =  $this->TodoService->showAllTodos($request->withRelations,auth()->user()->id,$request->title,$request->category_id,$request->page,$request->limit,$request->sortBy);
        if ($request->page){
            $todos= [
                'data'=>$todos->items(),
                'page'=>$request->page,
                'limit'=>$request->limit?$request->limit:15,
                'total'=>$todos->total(),

            ];
        }
        return response()->json($todos,200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @authenticated
     *
     * @param  \App\Http\Requests\StoreTodoRequest  $request
     * @return \Illuminate\Http\Jsonresponse
     */
    public function store(StoreTodoRequest $request)
    {
        //
        $input = $request->all();
        $input['user_id']=auth()->user()->id;
        $Todo = $this->TodoService->storeTodo($input);
        return response()->json($Todo,200);
    }

    /**
     * Display the specified resource.
     *
     * @authenticated
     *
     * @param  \App\Models\Todo  $Todo
     * @return \Illuminate\Http\Jsonresponse
     */
    public function show(Request $request,Todo $todo)
    {
        //
        $todo = $this->TodoService->getTodo($todo->id,$request->withRelations);

        return response()->json($todo,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @authenticated
     *
     * @param  \App\Http\Requests\UpdateTodoRequest  $request
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Jsonresponse
     */
    public function update(UpdateTodoRequest $request, Todo $todo)
    {
        //
        $input = $request->all();
        $this->TodoService->updateTodo($input,$todo);
        return response()->json('success',200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @authenticated
     *
     * @param  \App\Models\Todo  $Todo
     * @return \Illuminate\Http\Jsonresponse
     */
    public function destroy(Todo $todo)
    {
        //
        $this->TodoService->destroyTodo($todo);
        return response()->json('success',200);
    }
}

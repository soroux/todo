<?php

namespace App\Services;

use App\Models\Todo;

class TodoService
{

    public function showAllTodos($withRelations,$user,$title,$category,$page,$limit=15,$sortBy){
        $query = Todo::query();
        if ($title){
            $query = $query->where('content','LIKE',"%$title%");
        }
        if ($user){
            $query = $query->where('user_id',$user);
        }
        if ($category){
            $query = $query->where('category_id',$category);
        }
        switch ($sortBy) {
            case 'date':
                $query= $query->orderBy("created_at", "desc");
                break;
            case 'content':
                $query=  $query->orderBy("title", "desc");
                break;
            default:
                $query=  $query->orderBy("created_at", "asc");
                break;
        }

        if ($withRelations){
            $query = $query->with($withRelations);
        }

        if ($page){
            return   $query->paginate($limit);
        }
       return $query->get();
    }

    public function storeTodo(array $item){
        return Todo::create($item);
    }
    public function getTodo($id,$withRelations=null){
        $query = Todo::query();
        $query->where('id',$id);
        if ($withRelations){
            $query = $query->with($withRelations);
        }
        return $query->firstOrFail();
    }
    public function updateTodo(array $item,$Todo){
        return $Todo->update($item);
    }
    public function destroyTodo($Todo){
        return $Todo->delete($Todo);

    }
}

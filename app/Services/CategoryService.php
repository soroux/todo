<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{

    public function showAllCategories($withRelations,$user,$name,$page,$limit=15,$sortBy){
        $query = Category::query();
        if ($name){
            $query = $query->where('name','LIKE',"%$name%");
        }
        if ($user){
            $query = $query->where('user_id',$user);
        }
        switch ($sortBy) {
            case 'date':
                $query= $query->orderBy("created_at", "desc");
                break;
            case 'name':
                $query=  $query->orderBy("name", "desc");
                break;
            case 'price':
                $query=  $query->orderBy("price", "desc");

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

    public function storeCategory(array $item){
        return Category::create($item);
    }
    public function getCategory($id,$withRelations=null){
        $query = Category::query();
        $query->where('id',$id);
        if ($withRelations){
            $query = $query->with($withRelations);
        }
        return $query->firstOrFail();
    }
    public function updateCategory(array $item,$Category){
        return $Category->update($item);
    }
    public function destroyCategory($Category){
        return $Category->delete($Category);

    }
}

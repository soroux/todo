<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserService
{

    public function showAllUsers($withRelations,$name,$page,$limit=15,$sortBy){
        $query = User::query();
        if ($name){
            $query = $query->where('name','LIKE',"%$name%");
        }
        switch ($sortBy) {
            case 'date':
                $query= $query->orderBy("created_at", "desc");
                break;
            case 'name':
                $query=  $query->orderBy("name", "desc");
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

    public function store($items)
    {
        if(isset($items['profile_pic']))
            $items['profile_pic'] = "/storage/images/" . Storage::disk('images')->putFile('user/profile', $items['profile_pic']);

        $items['password'] = Hash::make($items['password']);

        return User::create($items);
    }
    public function getUser($id,$withRelations=null){
        $query = User::query();
        $query->where('id',$id);
        if ($withRelations){
            $query = $query->with($withRelations);
        }
        return $query->firstOrFail();
    }
    public function update($user,$items)
    {
        if(isset($items['profile_pic']))
        {
            if (!empty($user->profile_pic) && (file_exists(public_path($user->profile_pic))))
                unlink(public_path($user->profile_pic));

            $items['profile_pic'] = "/storage/images/" . Storage::disk('images')->putFile('user/profile', $items['profile_pic']);
        }

        if (isset($items['password']))
            $items['password'] = Hash::make($items['password']);


        return $user->update($items);
    }

    public function destroy($user)
    {
        return $user->delete($user);
    }
}

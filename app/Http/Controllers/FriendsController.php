<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Friends;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreFriendsRequest;
use App\Http\Requests\UpdateFriendsRequest;
use App\Models\Inbox;

class FriendsController extends Controller
{
      public function add()
    { 
      try {
            if(!auth()->check()){
                return response()->json(['message' => 'Unauthorized ⚠️'], 401);
            }  
                $validator = Validator::make(request()->all(), [
                'user_id' => 'required|integer',
                'friend_id' => 'required|integer' 
            ]); 
      
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            } 

             if(request()->rater_id===request()->user_id){
            return response()->json(['message' => "Please you cannot inbox your self ⚠️"],401); 
                
            } 
             $Inbox = Friends::create(array_merge(
                    $validator->validated(),
                  [
                    "status"=>"delivered",
                    "message"=>"I am free 👍"
                  ]    
                ));
            return response()->json(['message' => 'Friend successfully created 👍','Friend'=>$Inbox],200); 
         
        } catch (\Throwable $th) {
            // throw $th;
              return response()->json([
           'message' => 'This error is from the backend, please contact the backend developer'],500);
        }
    }
    public function myFriends($user_id){
       try {
            if(!auth()->check()){
                return response()->json(['message' => 'Unauthorized ⚠️'], 401);
            }
            
            $Inbox = Friends::where('user_id',$user_id)
            ->orWhere('user_id',$user_id)
            ->latest()
            ->with('users_friend.profile','users_friend.profileImage')->get(); 
             return response()->json(['message' => 'Inbox successfully Loaded 👍','friends'=>$Inbox],200);
     
      } catch (\Throwable $th) {
        //   throw $th;
          return response()->json([
           'message' => 'This error is from the backend, please contact the backend developer'],500);
      }
 }
}
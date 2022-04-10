<?php

namespace App\Http\Controllers\Api;

use App\GroupData;
use App\Groups;
use App\Http\Controllers\Controller;
use App\Http\Resources\GroupResource;
use App\Menus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
    protected $groups;
    protected $group_data;
    public function __construct()
    {
        $this->middleware('auth:api')->except('index','store', 'show', 'update', 'destroy');
        $this->groups = new Groups;
        $this->group_data = new GroupData;
    }

    public function index(){
        $group = $this->groups->paginate(10);
        return $this->sendResponse($group, "Success View Group");
    }

    public function store(Request $request){
        // dd($request->all());
        $validator = Validator::make($request->all(),[
            'name' => 'required|string',
        ]);
        if($validator->fails()){
            return $this->sendError("Validasi", $validator->messages()->toArray(), 500);
        }
        $group = Groups::where('name',$request->name)->first();
        if($group){
            return $this->sendError("Group ".$request->name." Already Exist");
        }
        $this->groups->name = $request->name;
        $this->groups->save();
        $groupId = $this->groups->id;
        $menu = Menus::orderBy('sort','asc')->get();
        foreach($menu as $item){
            if(!empty($request->post('menu-'.$item->id ) ) ){
                $this->group_data->group_id = $groupId;
                $this->group_data->menu_id = $item->id;
                $this->group_data->save();
            }
        }
        $responseMessage = "Create Successful";
        $data = new GroupResource($this->groups);
        return $this->sendResponse($data, $responseMessage);
    }
    public function show($id)
    {
        $group = Groups::find($id);
        if(is_null(($group))){
            return $this->sendError("Group Not Found");
        }
        return $this->sendResponse($group, "Group Found");
    }
    public function update(Request $request, $id){
        // dd($request->all());
        $validator = Validator::make($request->all(),[
            'name' => 'required|string',
        ]);
        if($validator->fails()){
            return $this->sendError("Validasi", $validator->messages()->toArray(), 500);
        }
        $group_cek = Groups::where('name',$request->name)->whereNotIn('id', [$id])->first();
        if($group_cek){
            return $this->sendError("Group ".$request->name." Already Exist");
        }
        $group = Groups::find($id);
        if(is_null(($group))){
            return $this->sendError("Group Not Found");
        }else{
            $group->name = $request->name;
            $group->save();
            $readyData = GroupData::where('group_id', $id)->count();
            if($readyData){
                GroupData::where('group_id', $id)->delete();
            }
            $menu = Menus::orderBy('sort','asc')->get();
            foreach($menu as $item){
                if(!empty($request->post('menu-'.$item->id ) ) ){
                    $this->group_data->group_id = $id;
                    $this->group_data->menu_id = $item->id;
                    $this->group_data->save();
                }
            }
            $responseMessage = "Update Successful";
            $data = new GroupResource($request);
            return $this->sendResponse($data, $responseMessage);
        }
    }

    public function destroy($id){
        $group = Groups::find($id);
        if(is_null(($group))){
            return $this->sendError("Group Not Found");
        }else{
            $group->delete();
            $responseMessage = "Delete Successful";
            return $this->sendResponse("", $responseMessage);
        }
    }
}

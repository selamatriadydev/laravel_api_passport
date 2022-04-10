<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Menus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MenusController extends Controller
{
    protected $menus;
    public function __construct()
    {
        $this->middleware("auth;api", ["except"=> ["list_all","index", "store", "show", "update", "publish"]]);
        $this->menus = new Menus;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list_all()
    {
        $all =  Menus::with('sub')->published()->parrent()->get();
        return $this->sendResponse($all, "Success View Menus");
    }
    public function index()
    {
        $menu = Menus::paginate(10);
        return $this->sendResponse($menu, "Success View Menus");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string',
            'link' => 'required|string',
            'route' => 'required|string',
        ]);
        if($validator->fails()){
            return $this->sendError("Validasi", $validator->messages()->toArray(), 500);
        }
        $menu = menus::where('name',$request->name)->first();
        if($menu){
            return $this->sendError("Menus ".$request->name." Already Exist");
        }
        $this->menus->name = $request->name;
        $this->menus->link = $request->link;
        $this->menus->route = $request->route;
        $this->menus->parrent = $request->parrent ? $request->parrent : 0;
        $this->menus->sort = $request->sort ? $request->sort : 0;
        $this->menus->is_published = $request->is_published ? $request->is_published : false;
        $this->menus->save();
        $responseMessage = "Create Successful";
        $data = $this->menus;
        return $this->sendResponse($data, $responseMessage);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $show = Menus::find($id);
        if(is_null($show)){
            return $this->sendError("Menus Not Found");
        }
        return $this->sendResponse($show, "menus Found");

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string',
            'link' => 'required|string',
            'route' => 'required|string',
        ]);
        if($validator->fails()){
            return $this->sendError("Validasi", $validator->messages()->toArray(), 500);
        }
        $menu = menus::where('name',$request->name)->whereNotIn('id', [$id])->first();
        if($menu){
            return $this->sendError("Menus ".$request->name." Already Exist");
        }
        $show = Menus::find($id);
        if(is_null($show)){
            return $this->sendError("Menus Not Found");
        }
        $show->name = $request->name;
        $show->link = $request->link;
        $show->route = $request->route;
        $show->parrent = $request->parrent ? $request->parrent : 0;
        $show->sort = $request->sort ? $request->sort : 0;
        $show->is_published = $request->is_published ? $request->is_published : false;
        $show->save();
        $responseMessage = "Update Successful";
        return $this->sendResponse($request, $responseMessage);
    }
    public function publish($id){
        $publish = Menus::find($id);
        if(is_null($publish)){
            return $this->sendError("Menus Not Found");
        }
        $publish->is_published = ! $publish->is_published; 
        $publish->save();
        return $this->sendResponse($publish, "Publish Success");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

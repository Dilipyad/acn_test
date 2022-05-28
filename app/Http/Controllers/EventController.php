<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $getdata= Event::All();
        $result = [
            'status'=> true,
            'data'=>$getdata,
            'message'=>'Get all data',
        ];
        return $result;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) 
    { 

        try {
           
            $validator = Validator::make($request->all(), [ 
                'name' => 'required', 
               
            ]);
            $slug=Str::slug($request->name);
            if ($validator->fails()) { 
                return response()->json(['error'=>$validator->errors()], 401);            
            }
            $user = Event::where('slug', '=', $slug)->first();
            if ($user === null) {
            } else{
                return response()->json(['error'=>"name alredy exit"], 401);            
            }
            $input['name'] = $request->name; 
            $input['slug'] =$slug;
            $user = Event::create($input); 
            $returnData = Event::where('id',$user->id)->first();
            return response()->json(
                ['status'=>true, 
                'user' => $returnData,
                'message'=>'creted successful',
               ]); 

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getdata= Event::where('id',$id)->get();
        $result = [
            'status'=> true,
            'data'=>$getdata,
            'message'=>'Get all data',
        ];
        return $result;
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
    public function update(Request $request)
    {
        try {
           
            $validator = Validator::make($request->all(), [ 
                'id' => 'required', 
                'name'=>'required', 
            ]);
            
            $id=($request->id);
            if ($validator->fails()) { 
                return response()->json(['error'=>$validator->errors()], 401);            
            }
            $user = Event::where('id', '=', $id)->first();
            if ($user) {
            } else{
                return response()->json(['error'=>"record not  exit"], 401);            
            }
            $getData = Event::updateOrCreate(['id' => $request->id],[
                "name" => $request->name,
                "slug" => Str::slug($request->name)
            ]);
           
            return response()->json(
                ['status'=>true, 
                'user' => $getData,
                'message'=>'update successful',
               ]); 

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $post = Event::find($id)->delete();
        return response()->json(
            ['status'=>true, 
            'message'=>'Deleted Record !!',
           ]); 
    }
}

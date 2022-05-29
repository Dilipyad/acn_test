<?php

namespace App\Http\Controllers;
use App\Models\Event;
use Illuminate\Http\Request;
use DataTables;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function getEvent(Request $request)
    {
       
        if ($request->ajax()) {
            $data = Event::latest()->get();
      
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
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

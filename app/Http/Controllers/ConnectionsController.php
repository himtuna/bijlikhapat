<?php

namespace App\Http\Controllers;

use Auth;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use App\Http\Requests;

use App\Connection;


class ConnectionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        //start fresh with create a new connection
        if(Connection::all()->isempty()) {
            return view('connections.create')->with('start','newaccount');
        } else {
            $user_id = Auth::user()->id;
            
            // $connections = Connection::where('user_id',$user_id)
            // ->with(array('billcurrent' => function($query) {
            //         $query->with('readings');
            // }))
            // ->get();
            $connections = Connection::where('user_id',$user_id)->with('billcurrent')->get();
            // var_dump($connections); exit();
            
            return view('connections.index',compact('connections'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('connections.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        
        $rules = array(
            'name' => 'required',
            'type' => 'required',
            'power_distributor' => 'required',
            // 'user_id' => 'required'
        );    
        
        $messages = array(
            'required' => 'The :attribute is required.',
            // 'same'  => 'The :others must match.'
        );
        
        // $validator = Validator::make(Input::all(), $rules);
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {


        // get the error messages from the validator
        $messages = $validator->messages();

        // redirect our user back to the form with the errors from the validator
        return Redirect::to('connections.create')
            ->withErrors($validator)
            ->withInput();

        } else {


        
            $connection = new Connection($request->all());
            $user_id = Auth::user()->id;
            $connection->user_id = $user_id;
            $slug = 'c' . uniqid();
            $connection->slug = $slug;
            $connection->save();

            // return redirect()->route('connections.index')->with('status','Yippi! Successfly  created your new connection.');
            return view('bills.start', compact('connection'))->with('status','Yippi! Successfly created your new connection.')->with('start','newaccount');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        //
        
        $connection = Connection::where('slug',$slug)->with(array('billcurrent'=>function($query){
            $query->take(1);
        }))->first();
        
        return view('connections.show',compact('connection'));


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
        //
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

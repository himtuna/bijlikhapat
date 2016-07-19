<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Reading;

use App\Bill;

class ReadingsController extends Controller
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
        //
        $readings = Reading::all();
        return view('readings.index',compact('readings'));
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
        //
        // Add more logic  to it (Bill current, paid, etc.)
        // $bill=Bill::where('status','Current')->orderBy('start_date','desc')->first();

        $bill = Bill::where('slug',$request->bill)->first();

        // Enter Logic here if all readings are deleted by user, then there wont be any lastreading.       
        $reading = new Reading;
        $reading->bill_id = $bill->id;

        if($bill->type == "Bill") {

        } 
        elseif($bill->type == "Reading Cycle") {
            
            // if there is no meter reading then save as it is.
            if($bill->readings->isempty()) {
                $reading->current_reading = $request->current_reading;
                $reading->current_consumption = 0;
                $reading->current_energy_charges = 0;
                $reading->save();

                return redirect()->back()->withInput($request->except('_token'))->with('status', 'Reading Saved Successfully');

             } 
             // Reading exists
             else{
                // Get last reading
                $lastreading= $bill->readings()->latest()->first();

                // Check if entered reading is less than last reading. 
                if($request->current_reading <= $lastreading->current_reading) {
                    return redirect()->back()->withInput($request->except('_token'))->with('status', 'Please enter reading more than the previous reading.');
                }
                else {

                    $reading->current_reading = $request->current_reading;
                    $reading->current_consumption = $request->current_reading - $lastreading->current_reading;
                    $reading->current_energy_charges = 0;
                    $reading->save();
                    return redirect()->back()->with('status-success', 'Reading Saved Successfully');

                 }   

             } // Else Reading Cycle : Last Reading
            
        } // Else Reading Cycle Ends 



        


        // if(!$bill->readings->isempty()) {

        //     $lastreading= $bill->readings()->latest()->first();

        //     if($request->current_reading <= $lastreading->current_reading) {
        //         return redirect()->back()->with('status', 'Please enter reading more than the previous reading.');
        //      }
        // }
        // elseif($request->current_reading <= $bill->start_reading) {
        //     return redirect()->back()->with('status', 'Please enter reading more than bill start reading.');
        // }
        //     $reading->current_reading = $request->current_reading;
        //     $reading->current_energy_charges = $this->energycharges($bill->start_reading, $request->current_reading);
        //     $reading->save();
        //     return redirect()->back();
        
        // Working code below
        /**
        $reading = new Reading;
        $reading->bill_id = $bill->id;
        
        if($request->current_reading > $lastreading->current_reading) {
            $reading->current_reading =$request->current_reading;
            $reading->current_energy_charges = $this->energycharges($bill->start_reading, $request->current_reading);
            $reading->save();
            return redirect()->route('bills.index');
        }
        else {
            return redirect()->back()->with('status', 'Please enter reading more than the previous reading.');
        }**/
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

    public static function energycharges($start,$end)
    {   
        $consumption = $end - $start;
        $charges = 0;

        if($consumption - 200 < 0) {
            $charges+= $consumption * 4;            
        } 
        elseif($consumption - 400 < 0) {
            $charges+=200 * 4;
            $charges+= ($consumption-200) * 5.95; 
        }
        elseif($consumption - 800 < 0) {
            $charges+=200 * 4; //till 200 units
            $charges+=200 *5.95; // from 201 - 400 units
            $charges+= ($consumption-400) * 7.30; // from 401 till 800 units
        }
        elseif($consumption - 1200 < 0) {
            $charges+=200 * 4; //till 200 units
            $charges+=200 *5.95; // from 201 - 400 units
            $charges+=400 *7.30; // from 401 - 800 units
            $charges+= ($consumption-800) * 8.10; // from 801 till 1200 units
        }
        elseif($consumption - 1200 > 0) {
            $charges+=200 * 4; //till 200 units
            $charges+=200 *5.95; // from 201 - 400 units
            $charges+=400 *7.30; // from 401 - 800 units
            $charges+=400 *8.10; // from 801 - 1200 units
            $charges+= ($consumption-1200) * 8.75; // 1200 units and above
        }      


        return $charges;
    }

}

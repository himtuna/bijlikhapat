<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Bill;

use App\Reading;

use App\Connection;

use Carbon\Carbon;

class BillsController extends Controller
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

        // if(count(Bill::all()) == 0) {
        if(Bill::all()->isempty()) {
            return view('bills.start');
        }
        elseif (Bill::where('status','Current')->get()->isempty()){
            // if there is no active bill
            // get meter reading from last bill.
            return view('bills.start');

        }
        else  {
            // $bill = Bill::orderby('start_date','desc')->first()->with('readings');
            // $bill = Bill::with('readings')->first();

            $bill = Bill::where('status','Current')->with(['readings' => function ($query) {
                $query->orderBy('created_at', 'desc');

            }])->first();
            // var_dump($bills); exit();
            // $bills = Bill::all();
            return view('bills.index', compact('bill'));
        }
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Connection $connnection)
    {
        //
        if(isset($connection)){
            return view('bills.create',compact('connection'));
        }
        else {
            
            return view('bills.start');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     public function store(Request $request)
    {   
        $this->validate($request, [
            'current_reading' => 'required',

        ]);    

        $connection = Connection::where('slug',$request->connection)->first();

        if($request->start == 'newaccount' || count(Bill::where('connection_id',$connection->id)->get()) == 0) {

            // create new bill
            $bill = new Bill;
            $bill->connection_id = $connection->id;
            $bill->status = "Current";
            $bslug = 'b' . uniqid();
            $bill->slug = $bslug;
            $bill->type = "Reading Cycle";

            $bill->save();


            $reading = new Reading;
            $reading->current_reading = $request->current_reading;
            $reading->bill_id = $bill->id;
            $reading->current_energy_charges = 0;
            $rslug = 'r' . uniqid();
            $reading->slug = $rslug;
            $reading->save(); 

            return redirect()->route('connections.index')->with('status','Successfully Created your new connection and added new meter reading');
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
        $bill = Bill::findorfail($id);
        $lastreading = $bill->readings()->latest()->first();



        $this->validate($request, [
            'end_reading' => 'required',
            'end_date' => 'date|before:tomorrow',
            //end_date should be before tomorrow, and less than last reading

        ]); 

        
        if($request->status == "Previous") {
            // var_dump(strtotime(Carbon::parse($lastreading->created_at)->toDateString())); 
            // var_dump($request->end_date);
            // var_dump(strtotime($request->end_date));
            // exit();
            // check this function
            if(strtotime($request->end_date) < strtotime(Carbon::parse($lastreading->created_at)->toDateString())  )
            {
                return redirect()->back()->with('status', 'End date cannot be less than last recorded reading');
            } 

            if($request->end_reading < $lastreading->current_reading) {
                return redirect()->back()->with('status', 'End reading should be greater than last recorded reading');
            }
            
            // Saving all values
            $bill->end_reading = $request->end_reading;

            $bill->consumption = $request->end_reading - $bill->start_reading;
            $bill->energy_charges = $this->energycharges($request->start_reading,$bill->end_reading);
            $bill->status = $request->status;

            if($request->end_date == NUll) {
                $bill->end_date = Carbon::now()->toDateString();
            } else {
                $bill->end_date = $request->end_date;
            }

            // var_dump($request->next_cycle); exit();
            $bill->update();

            if($request->next_cycle == 'on') {
                $newbill = new Bill;
                $newbill->start_reading = $bill->end_reading + 1;
                $end_date = strtotime($bill->end_date);
                $end_date = strtotime("+1 day", $end_date);
                $newbill->start_date = date('Y-m-d',$end_date);
                // var_dump(date('Y-m-d',$end_date)); exit();
                $newbill->status = "Current";
                $newbill->save();

                // Rethink on create this zero mandatory record.
                // $newreading = new Reading;
                // $newreading->current_energy_charges = 0;
                // $newreading->current_reading = $newbill->start_reading;
                // $newreading->bill_id = $newbill->id;
                // $newreading->save();

                return redirect()->route('bills.index')->with('status', 'New Billing cycle created, you may start adding new reading.');
            }

            return redirect()->route('bills.index');

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
        //  
        $bill = Bill::findorfail($id);
        $bill->destroy();

        Session::flash('message', 'Successfully deleted the bill!');
        return redirect()->route('bills.index');
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

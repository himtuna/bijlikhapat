@extends('layouts.app')
@section('title', 'Bills')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">Readings</div>

                <div class="panel-body">
                    <table class="table table-striped table-hover ">
                    <thead>
                        <th>#</th>
                        <th>Units Consumed</th>
                        <th>Duration</th>
                        <th>Reading Date</th>
                        <th>Reading</th>
                        <th>Current Charges</th>
                        <!-- <th><i class="fa fa-cog"></i></th> -->
                    </thead>
                    <tbody>
                    @if($bill->readings->isempty()) {
                    	<alert class="alert-info"><strong>No readings! Go ahead and create new readings.</strong></alert>

                    } 
                    @else
                        @foreach($bill->readings as $index => $reading)
                        <tr>
                            
                            <!-- <td><a href="{{ route('bills.destroy', $bill->id )}}" data-method="delete" rel="nofollow" data-confirm="Are you sure you want to delete this?">Delete this entry</a></td> -->
                            
                                <td>{{count($bill->readings) - $index }}</td>
                                <td><strong>{{$reading->current_reading - $bill->start_reading}}</strong> units consumed</td>
                                <td>
                                    <strong>{{$reading->created_at->diffForHumans(Carbon\Carbon::parse($bill->start_date))}}</strong> start date as on
                                </td>
                                
                                <td>{{$reading->created_at}}</td>
                                <td>with meter reading {{$reading->current_reading}} </td>
                                
                                
                                
                                <td> and energy charges <strong>Rs.&nbsp;{{$reading->current_energy_charges}}</strong></td>
                                <!-- <td></td> -->
                            
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                    </table>
                </div>
            </div>           
             
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-body">
                <legend>Current Bill Details</legend>
                    @if(!$bill->readings->isempty())
                    <h4>Energy Charges: Rs. {{$bill->readings[0]->current_energy_charges}}</h4>@endif
                    <h4>Start Date from: {{$bill->start_date}} <small><em>{{Carbon\Carbon::parse($bill->start_date)->diffForHumans()}} </em></small></h4>
                    @if(isset($bill->end_date))
                    <h>End Date: {{$bill->end_date}} </h4>
                    @endif
                    
                    <h6>Reading Starts: {{$bill->start_reading}} units </h6>

                    <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">End Billing Cycle</button>                   


                </div>
                <div id="myModal" class="modal fade" role="dialog">
                  <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <legend>End current billing cycle</legend>
                      </div>
                      <div class="modal-body">
                        <p>Please enter the end date of billing cycle with final meter reading.</p>
                        <form action="{{url('bills/'.$bill->id)}}" method="post">
                        {{ method_field('PATCH') }}
                            <div class="form-group">
                              <label for="end_reading" class="col-md-2 control-label">End Reading</label>

                              <div class="col-md-10">
                                <input type="number" class="form-control" id="end_reading" name="end_reading" placeholder="End Reading">
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="end_date" class="col-md-2 control-label">End date</label>

                              <div class="col-md-10">
                                <input type="date" class="form-control" id="end_date" name="end_date" placeholder="End Date">
                              </div>
                            </div>
                            <div class="form-group">
                            	<div class="togglebutton" margin-left="10px">
						          <label>
						            <input type="checkbox" checked name="next_cycle"> would you like to continue the next billing cycle
						          </label>
						        </div>
                            </div>
                            <input type="hidden" value="Previous" name="status">
                            {{csrf_field()}}
                        <div class="form-group">
                          <div class="col-md-10 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">End Billing Cycle</button>
                          </div>
                        </div>
                        </form>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      </div>
                    </div>

                  </div>
                </div>        
                </div>
                <div class="well bs-component">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session('status'))
                        <div class="alert alert-danger">
                            {{ session('status') }}
                        </div>
                    @endif
                 <form class="form-horizontal" action="{{url('readings')}}" method="post">
                    <fieldset>
                    <legend>Enter new reading</legend>
                        
                        <div class="form-group">
                          <label for="current_reading" class="col-md-2 control-label">Enter new reading</label>

                          <div class="col-md-10">
                            <input type="number" class="form-control" id="current_reading" name="current_reading" placeholder="Current Reading">
                          </div>
                        </div>
                        {{csrf_field()}}
                        <div class="form-group">
                          <div class="col-md-10 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">Enter Reading</button>
                          </div>
                        </div>
                    </fieldset>
                </form>   
                </div>
                
        </div>
    </div>
</div>
@endsection

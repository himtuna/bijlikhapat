@extends('layouts.app')
@section('title')
{{$connection->name}} Readings
@stop
@section('stylesheets')
<link rel="stylesheet" href="{{asset('css/morris.css')}}">
@stop
@section('content')


<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-primary">
                <div class="panel-heading">Current Readings for meter connection at <strong>{{$connection->name}}</strong></div>

                <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="{{$connection->billcurrent->slug}}-data">
                    @if($connection->billcurrent->type == "Bill")
                    <thead>
                        <th>#</th>
                        <th>Units Consumed</th>
                        <th>Duration</th>
                        <th>Reading Date</th>
                        <th>Reading</th>
                        <th>Current Charges</th>
                        <!-- <th><i class="fa fa-cog"></i></th> -->
                    </thead>
                    @elseif($connection->billcurrent->type == "Reading Cycle")
                    <thead>
                        <th>#</th>
                        <th>Units Consumed</th>
                        <th>Duration</th>
                        <th>Reading Date</th>
                        <th>Reading</th>                        
                    </thead>
                    @endif
                    <tbody>
                    @if($connection->billcurrent->type == "Bill")                    
                    @elseif($connection->billcurrent->type == "Reading Cycle")
                    <?php $count = count($connection->billcurrent->readings)?>
                        @foreach($connection->billcurrent->readings as $index => $reading)
                        <tr @if($index == 0 )  class="tr-head" @endif>
                            <td>{{$count - $index}}</td>
                            @if($index != count($connection->billcurrent->readings)-1) 
                                <td>
                                {{$connection->billcurrent->readings[$index]->current_reading - $connection->billcurrent->readings[$index + 1]->current_reading}} units</td>
                                <td>
                                <?php 

                                $duration = $connection->billcurrent->readings[$index+1]->created_at->diffForHumans(Carbon\Carbon::parse($connection->billcurrent->readings[$index]->created_at));
                                $durationtext = explode('before', $duration);
                                print_r($durationtext[0]);
                                ?>
                                </td>
                            @else
                            <td>--</td><td>--</td>@endif
                            <td>{{$reading->created_at}}</td>
                            <td>{{$reading->current_reading}}</td>

                        </tr>
                        @endforeach
                    @endif


                    
                    </tbody>
                    </table>
                    <div class="text-center">
                    <input type="button" id="seeMoreRecords" value="More records" class="btn btn-default">
                    <input type="button" id="seeLessRecords" value="Less" class="btn btn-default">
                    </div>
                    </div><!-- Table Responsive -->
                </div>
            </div>   
            <div class="well">
                <div id="{{$connection->billcurrent->slug}}-chart" style="height: auto;width:auto;overflow-y: hidden;"></div>
            </div>        
             
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-body">
                <legend>{{$connection->name}}</legend>
                <!-- Different for billing cycle -->
                <h4>{{$connection->billcurrent->readings()->latest()->first()->current_reading - $connection->billcurrent->readings[count($connection->billcurrent->readings)-1]->current_reading}} units consumed in
                <?php 

                $duration = $connection->billcurrent->readings[$index]->created_at->diffForHumans(Carbon\Carbon::parse($connection->billcurrent->readings[0]->created_at));
                    $durationtext = explode('before', $duration);
                print_r($durationtext[0]);
                ?>
                </h4>
                <hr>
                <h6>Power Distributor: {{$connection->power_distributor}}</h6>
                <h6>Connection Type: {{$connection->type}}</h6>
                Readings recorded: {{count($connection->billcurrent->readings)}} <br>
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

                    @if (session('status-success'))
                        <div class="alert alert-success">
                            {{ session('status-success') }}
                        </div>
                    @endif

                 <form class="form-horizontal" action="{{url('readings')}}" method="post">
                    <fieldset>
                    <legend>Enter new reading</legend>
                        
                        <div class="form-group">
                          <label for="current_reading" class="col-md-2 control-label">Enter new reading</label>

                          <div class="col-md-10">
                            <input type="number" class="form-control" id="current_reading" name="current_reading" placeholder="Current Reading" value="{{old('current_reading')}}">
                          </div>
                        </div>
                        {{csrf_field()}}
                        <input type="hidden" name="bill" value="{{$connection->billcurrent->slug}}">
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


@section('scripts')
<!-- <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script> -->
<script src="{{asset('js/raphael-min.js')}}"></script>
<script src="{{asset('js/morris.min.js')}}"></script>
<!-- <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script> -->
<script>
    new Morris.Line({
  // ID of the element in which to draw the chart.
  element: '{{$connection->billcurrent->slug}}-chart',
  // Chart data records -- each entry in this array corresponds to a point on
  // the chart.
  data: [<?php $data = $connection->billcurrent->readings;                                     
                        foreach($data as $d) {
                            unset($d['id']);
                            unset($d['bill_id']);
                            unset($d['slug']);
                            unset($d['current_reading']);
                            unset($d['current_energy_charges']);
                            unset($d['updated_at']);
                            // if($d['current_consumption'] == NULL ) $d['current_consumption'] = 0;
                            echo $d . ',';                    
                        }?>],

  // The name of the data record attribute that contains x-values.
  xkey: 'created_at',
  // A list of names of data record attributes that contain y-values.
  ykeys: ['current_consumption'],
  // Labels for the ykeys -- will be displayed when you hover over the
  // chart.

  labels: ['Units consumed']

});
</script>
<script>
var trs = $("#{{$connection->billcurrent->slug}}-data tr");
var btnMore = $("#seeMoreRecords");
var btnLess = $("#seeLessRecords");
var trsLength = trs.length;
var currentIndex = 5;

trs.hide();
trs.slice(0, 5).show(); 
checkButton();

btnMore.click(function (e) { 
    e.preventDefault();
    $("#{{$connection->billcurrent->slug}}-data tr").slice(currentIndex, currentIndex + 5).show();
    currentIndex += 5;
    checkButton();
});

btnLess.click(function (e) { 
    e.preventDefault();
    $("#{{$connection->billcurrent->slug}}-data tr").slice(currentIndex - 5, currentIndex).hide();          
    currentIndex -= 5;
    checkButton();
});

function checkButton() {
    var currentLength = $("#{{$connection->billcurrent->slug}}-data tr:visible").length;

    if (currentLength >= trsLength) {
        btnMore.hide();            
    } else {
        btnMore.show();   
    }

    if (trsLength > 5 && currentLength > 5) {
        btnLess.show();
    } else {
        btnLess.hide();
    }

}
</script>
@stop
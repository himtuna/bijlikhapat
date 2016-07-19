@extends('layouts.app')
@section('title', 'Connections')

@section('content')
<!-- Show All Active Connection Summary -->
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
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
                        <div class="alert alert-warning">
                            {{ session('status') }}
                        </div>
                    @endif

                                  
        @foreach($connections as $connection)
            <div class="well">
                <legend><a href="{{url('connections/'.$connection->slug)}}" style="color:inherit;text-decoration:none">{{$connection->name}}  </a></legend>
                

                <?php $readings = count($connection->billcurrent->readings); ?>

                    @if($readings == 0)
                    <div class="alert alert-info"><h4>No Reading!</h4> You can create a new reading.</div>
                    @elseif($readings ==  1) 
                        @foreach($connection->billcurrent->readings as $index => $reading)
                            <h1>{{$reading->current_reading}} units</h1> meter reading recorded on <strong> {{$reading->created_at}}</strong>
                        @endforeach
                    @elseif($readings>1)
                        <h2>{{$connection->billcurrent->readings[0]->current_reading - $connection->billcurrent->readings[1]->current_reading}} units  consumed in 
                        <?php 

                        $duration = $connection->billcurrent->readings[1]->created_at->diffForHumans(Carbon\Carbon::parse($connection->billcurrent->readings[0]->created_at));
                        $durationtext = explode('before', $duration);
                        print_r($durationtext[0]);
                        ?>
                      </h2>
                    @endif
                    Readings taken in the current cycle: <strong>{{$readings}}</strong> <br>
                    Total Meter Cycles Recorded: <strong>{{count($connection->bills)}}</strong> <br>
                    
                
            </div>
        @endforeach

        </div>
        
    </div>
</div>
@stop

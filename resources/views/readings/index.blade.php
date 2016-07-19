@extends('layouts.app')
@section('title', 'Readings')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 pull-right">
                <div class="well bs-component">
                 <form class="form-horizontal" action="{{url('readings')}}" method="post">
                    <fieldset>
                    <legend>Enter new reading</legend>
                        
                        <div class="form-group">
                          <label for="meter_reading" class="col-md-2 control-label">Reading</label>

                          <div class="col-md-10">
                            <input type="number" class="form-control" id="meter_reading" name="meter_reading" placeholder="Last bill reading">
                          </div>
                        </div>
                        {{csrf_field()}}
                        <div class="form-group">
                          <div class="col-md-10 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">Enter</button>
                          </div>
                        </div>
                    </fieldset>
                </form>   
                </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Readings</div>

                <div class="panel-body">
                    <table class="table table-striped table-hover ">
                    <thead>
                        <th>#</th>
                        <th>Reading date</th>
                        <th>Meter reading</th>
                        <th>Energy Charges</th>
                    </thead>
                    <tbody>
                        @foreach($readings as $reading)
                        <tr>
                            <td>{{$reading->id}}</td>
                            <td>{{$reading->created_at}}</td>
                            <td>{{$reading->meter_reading}}</td>
                            <td>Rs. </td>                           
                        </tr>
                        @endforeach
                    </tbody>
                    </table>
                </div>
            </div>           
             
        </div>
        
    </div>
</div>
@endsection

@extends('layouts.app')
@section('title', 'Bills')

@section('content')
<div class="container">
    <div class="row">
        
        <div class="col-md-6 col-md-offset-3">
                <div class="well bs-component">
                 <form class="form-horizontal" action="{{url('bills')}}" method="post">
                    <fieldset>
                    <legend>Create a new billing cycle</legend>
                    @if(isset($start) && $start == 'newaccount')
                    <div class="progress progress-striped">
                      <div class="progress-bar progress-bar-info" style="width: 66%"></div>
                    </div>
                  @endif
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
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                        <div class="form-group">
                          <label for="current_reading" class="col-md-2 control-label">Current Reading</label>

                          <div class="col-md-10">
                            <input type="number" class="form-control" id="current_reading" name="current_reading" placeholder="Current Reading">
                          </div>
                        </div>
                        <hr>
                        <h4>Billing Cycle Detail</h4>
                        <p class="help-block">Please fill the billing cycle detail. If you leave it blank then current meter reading and current date will be used to start the billing cycle.</p>
                        <div class="form-group">
                          <label for="start_date" class="col-md-2 control-label">Start Date</label>

                          <div class="col-md-10">
                            <input type="date" class="form-control" id="start_date" name="start_date" placeholder="Start Date">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="start_reading" class="col-md-2 control-label">Bill Start Reading</label>

                          <div class="col-md-10">
                            <input type="number" class="form-control" id="start_reading" name="start_reading" placeholder="Bill Start Reading" value="">
                          </div>                         

                        </div>
                        <input type="hidden" value="start" name="start">
                        {{csrf_field()}}
                        <div class="form-group">
                          <div class="col-md-10 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">Create</button>
                          </div>
                        </div>
                    </fieldset>
                </form>   
                </div>
        </div>
    </div>
</div>
@endsection

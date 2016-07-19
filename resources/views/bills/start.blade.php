@extends('layouts.app')
@section('title', 'Bills')

@section('content')
<div class="container">
    <div class="row">
        
        <div class="col-md-6 col-md-offset-3">
                <div class="well bs-component">
                 <form class="form-horizontal" action="{{url('bills')}}" method="post">
                    <fieldset>
                    

                    <legend>Now let's add first meter reading</legend>
                    <p>Step 2. Please add your current meter reading.</p>
                    
                    <div class="progress progress-striped">
                      <div class="progress-bar progress-bar-info" style="width: 66%"></div>
                    </div>
                  
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
                                                         
                        <input type="hidden" value="newaccount" name="start">
                        <input type="hidden" value="{{$connection->slug}}" name="connection">
                        {{csrf_field()}}
                        <div class="form-group">
                          <div class="col-md-10 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">Record reading</button>
                          </div>
                        </div>
                    </fieldset>
                </form>   
                </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')
@section('title', 'Create a new Connection')

@section('content')
<!-- Show All Active Connection Summary -->
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
        	<div class="well bs-component">
	            <form class="form-horizontal" action="{{url('connections')}}" method="post">
	            @if(isset($start) && $start == 'newaccount')
                    <legend>Let's begin by creating a new Connection</legend>
                    <p>Step 1. Please add your meter connection details.</p>
                @else
                    <legend>Create a new Connection</legend>
                    <p>Step 1. Please add your new meter connection details.</p>
                @endif
	            
                <div class="progress progress-striped">
                    <div class="progress-bar progress-bar-info" style="width: 33%"></div>
                </div>
	            	@if ($errors->has())
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
                    <div class="form-group @if ($errors->has('name')) has-error @endif label-floating" style="margin:0 5px 5px">
                      <label for="name" class="col-md control-label">Connection Name</label>

                       <input type="text" class="form-control" id="name" name="name" placseholder="" required1="required1" value="{{ old('name') }}">
                            @if ($errors->has('name'))<p class="help-block">{{ $errors->first('name') }} You can enter Home, Office or Factory.</p> @else
                            <p class="help-block">Home, Office or Factory</p> @endif
                      
                    </div>
                    <div class="form-group @if($errors->has('type')) has-error @endif">
				      <label class="col-md-2 control-label">Connection Type</label>
				      <div class="col-md-10">
				        <div class="radio radio-primary">
				          <label>
				            <input type="radio" name="type" value="Household" @if(old('type') == "Household") checked="checked" @endif>
				            Household
				          </label>
				        </div>
				        <div class="radio radio-primary">
				          <label>
				            <input type="radio" name="type" value="Commercial" @if(old('type') == "Commercial") checked="checked" @endif>
				            Commercial
				          </label>
                          @if($errors->has('type')) <p class="help-block">{{$errors->first('type')}}</p> @endif
				        </div>
				      </div>
				    </div>
                    <div class="form-group @if ($errors->has('power_distributor')) has-error @endif">
                    	<label for="type" class="col-md-2 control-label">Power Distributor</label>
                    	<div class="col-md-10">
	                    	<select name="power_distributor" id="power_distributor" class="form-control" required1="required1">
	                    		<option value selected disabled>--Power Distributor--</option>
	                    		<option value="Tata Power Delhi Limited" @if(old('power_distributor') == "Tata Power Delhi Limited") selected="selected" @endif>Tata Power Delhi Limited</option>
	                    		<option value="BSES Yamuna" @if(old('power_distributor') == "BSES Yamuna") selected="selected" @endif>BSES Yamuna</option>
	                    		<option value="BSES Rajdhani" @if(old('power_distributor') == "BSES Rajdhani") selected="selected" @endif>BSES Rajdhani</option>
	                    	</select>
                            @if ($errors->has('power_distributor'))<p class="help-block">{{ $errors->first('power_distributor') }} </p> @endif
                    	</div>
                    </div>
                    <!-- Should we display user id to outside world -->
                    <!-- <input type="hidden" value="{{Auth::user()->id}}" name="user_id"> -->
                    
                    {{csrf_field()}}
                    <div class="form-group">
                      <div class="col-md-10 col-md-offset-4">
                        <button type="submit" class="btn btn-primary">Create</button>
                      </div>
                    </div>
	            </form>
            </div>
        </div>
    </div>
</div>
@stop

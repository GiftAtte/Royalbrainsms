

@extends('layouts.master2')


@section('content')

<div  style="bg:grey">
	        
                <div class=" row">
                   
                <center style="bg:white">
                    <img src="{{ asset( 'img/logo.jpg')}}" height="200px" width="200px" class="img-thumbnail img-rounded"  alt="image">
                    <h5 class="text-danger">                       {{ trans('app.school_address') }}          </h5>
                    
                    <h5>                       {{ trans('app.school_town') }}         </h5>
                   
                </center>
                </div>
              
                <div class="col-xs-2">
                   
                </div>
              </div>
        
<p>Dear  {{ $data['name'] }}</p>
<h2>{{$data['subject']}}</h2>
<p> {!! $data['message'] !!}

   
    </div>

<!-- /.row -->
    
@endsection
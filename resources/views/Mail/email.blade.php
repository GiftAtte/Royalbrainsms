

@extends('layouts.master2')


@section('content')

         <div  class="card">

                <div class=" card-body">

                <center style="background:white">
                   <img src="{{ asset('/img/schools/'.$data['school']->logo) }}" class="img-thumbnail pr-2 " alt="logo" width="30" height="30">
                    <h5 class="text-primary">                {{$data['school']->name }}      </h5>

                    <h5>                {{ $data['school']->contact_address}},&nbsp; {{$data['school']->state}}        </h5>

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

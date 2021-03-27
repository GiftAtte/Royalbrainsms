@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="container">
            <div class="card card-navy">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

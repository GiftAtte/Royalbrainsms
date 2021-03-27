@extends('admin.default')

@section('page-header')
 Lecture Notes <small>{{ trans('app.manage') }}</small>
@stop

@section('content')

	<section class="filter-area">
		<div class="row">
			<div class="col-sm-10">
				<ul class="list-inline">
					<li><a class="btn btn-info" href="{{ route(ADMIN . '.lecture.create') }}">{{ trans('app.add_button') }}</a></li>
				</ul>
			</div>
		</div>
	</section>

	<div class="row">
	  <div class="col-xs-12">
	    <div class="box box-info">
	      <div class="box-header">
	        <h3 class="box-title"> Lecture list</h3>
	      </div>
	      <!-- /.box-header -->
	      <div class="box-body table-responsive no-padding">
	        <table class="table data-tables" cellspacing="0" width="100%">
                <thead>
                    <tr>
                       <th>Suject</th>
                        <th>Topic</th>
                        <th>Class</th>
                        <th>Date</th>
                       
                        
                        <th class="actions">Actions</th>
                    </tr>
                </thead>
             
                <tfoot>
                    <tr>
                        
                    </tr>
                </tfoot>
             
                <tbody>
                    @foreach ($items as $item)
                   
						<tr>
                        <td>@if( $item->subjects){{ $item->subjects->Subject}}@endif</td>
                         <td>{{ $item->topic}}</td>
                            
                           
                             <td>@if($item->student_classes){{ $item->student_classes->ClassName}}@endif</td>
                            
                                <td>{{ $item->created_at}}</td>
                                   <td>
                                <ul class="list-inline">
                                    
                                    <li>{{ link_to_asset($item->note_path, 'download',['class'=>'btn btn-info btn-xs fa fa-download']) }}</li>
                                    <li>
                                        {!! Form::open([
                                            'class'=>'delete',
                                            'url'  => route(ADMIN . '.lecture.destroy', $item->id), 
                                            'method' => 'DELETE',
                                            ]) 
                                        !!}

                                            <button class="btn btn-danger btn-xs" title="{{ trans('app.delete_title') }}"><i class="fa fa-trash"></i></button>
                                            
                                        {!! Form::close() !!}
                                    </li>
                                </ul>
                            </td>
						</tr>
                    @endforeach
                  
                </tbody>
            </table>
	      </div>
	      <!-- /.box-body -->
	    </div>
	    <!-- /.box -->
	  </div>
	</div>
	
@stop
@section('js')

	<script>
		$(function () {
			$('input').iCheck({
				checkboxClass: 'icheckbox_square-blue',
				radioClass: 'iradio_square-blue',
				increaseArea: '15%' // optional
			});
        });







    </script>
        @stop
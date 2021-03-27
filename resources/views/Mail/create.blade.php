@extends('admin.default')

@section('page-header')
 Send Mail<small>new</small>
@stop

@section('content')

    {!! Form::open([
            'action' => ['SendEmailController@Send'],
            'files'=>true
           
        ])
    !!}

    @include('admin.Mail.form')
    

  	

  {!! Form::close() !!}

@stop


@section('js')

<script>

var student = "<tr><td class='text-uppercase'>"+  "<label class='radio-inline'>"+
                        "<input type='radio' name='type' value='parents' class='radio icheck ' id='parents'> Parents"+
                    "</label>"+  "</td>"+
                    
                    " <td><label class='radio-inline'>"+
                        "<input type='radio' name='type' value='employees' class='radio  minimal-green' id='staff'> STAFF"+
                    "</label></td>"+
                    " <td><label class='radio-inline'>"+
                        "<input type='radio' name='type' value='students' class='radio  minimal-green' id='student'> STUDENTS"+
                    "</label></td>"+
                    "<td><button class='btn btn-primary bt-xs' id='custom' type='button'><i class='fa fa-plus-square'>Add emailaddress </i></button>"+
                    
                "</td>"
                
             +"</tr>"
	           
                $('#attendance').append(student);




 $(document).ready(function(){
      $("#custom").click(function() {
         
            $("#phoneDiv").show()
        }
)
      //select2
        
    //Add text editor
    $("#compose-textarea").wysihtml5();
  
  });
  	$('input').iCheck({
				checkboxClass: 'icheckbox_square-blue',
				radioClass: 'iradio_square-green',
				increaseArea: '15%' // optional
            });
            $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass   : 'iradio_square-red'
    })
  	
</script>
<script src="/plugins/iCheck/icheck.min.js"></script>
@stop

<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <link rel="shortcut icon" type="image/x-icon" href="img/logo.png" />

  <title>RBSMS</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">

  <!-- Theme style -->

  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition ">
<div class="wrapper" >



  <!-- Main Sidebar Container -->

  <!-- Content Wrapper. Contains page content -->

  <div  >
    <!-- Content Header (Page header) -->

    <!-- /.content-header -->

    <!-- Main content -->

    <div class="content py-5" id="app" >
      @if(session()->has('success'))
      @php
          $message=session()->get('success');
      @endphp
    <div class="alert alert-success">
        {{ $message }}
    </div>
@endif

@if(session()->has('errors'))
          @php
          $message=session()->get('errors');
      @endphp
    <div class="alert alert-danger">
        {{$message}}
    </div>
@endif


      <div class="container-fluid">
          <div class="container" id="print">

<div class="content col-12">
    <div class="card card-navy card-outline">
    <div class="card-header">
    <div class="ribbon-wrapper">
        <div class="ribbon bg-primary">
          Result
          </div>
        </div>
    <h5 class="header text-danger text-uppercase">Result Class List for {{ $report->title }} </h5>
<a href="{{ route('export_master',['report_id'=>$report->id]) }}" class="btn btn-primary">
    Cummulative CSV</a>

    <a href="{{ route('export_master_term',['report_id'=>$report->id]) }}" class="btn btn-success">
    This Term CVS</a>
</div>
    <div class="card-body table-responsive col-12">
    <table class="table table-hover  display" id="data_tb" width="100%">
    <thead>
    <tr>
    <th>S/N</th><th>Student Names</th> <th>Arms</th><th>Action</th>
    </tr>
    </thead>
    <tbody>
        @foreach ($students as  $student)


    <tr >
    <td>{{$student->id}}</td>
    <td>{{$student->surname}}&nbsp;{{$student->first_name}}&nbsp;{{$student->middle_name}}</td>
    <td>{{$student->arm?$student->arm->name:''}}</td>
     <td class="row">
    {{-- <router-link v-if="$gate.isTutorOrAdmin()"     :to="`/result/${report}/${student.id}`"  tag="a" exact class="pr-2">view report card</router-link>
    <router-link v-if="$gate.isTutorOrAdmin()"     :to="`/result/${report}/${student.id}/annual`"  tag="a" exact class="pr-2">view Annual Report</router-link>

    <router-link :to="`/transcript/${student.id}`"  tag="a" exact>view transcript</router-link> --}}
<a href="{{ route('pdfdownload',['id'=>$student->id,'report_id'=>$report->id]) }}">Download PDF</a>
<a class="pl-2" href="{{ route('pdfdownload',['id'=>$student->id,'report_id'=>$report->id,'email'=>1]) }}">Send Mail</a>
                        </td>
    </tr>
    @endforeach
    </tbody>
    </table>
    </div>
    <div class="card-footer">

                  </div>
    </div>
    </div>

          </div>


        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


  <!-- Main Footer -->

<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
@auth
<script>
    //window.user = @json(auth()->user())
</script>
@endauth
<!-- jQuery -->

<!-- AdminLTE App -->
<script src="{{ asset('js/app.js') }}">




</script>

<script>
    var app=new Vue({
   // el:'#app2',

    data(){
            return{
                students:{},
                report:''

            }
        },

        mounted(){

            // $('#data_tb').DataTable();
        axios.get(`/api/results/${206}`)
            .then(result => {
                this.students=result.data;

              console.log(result.data);
            }).catch((err) => {

            });


        },
            methods:{
 getResults(page = 1) {
                        axios.get(`/api/results/${this.$route.params.id}?page=${page}`)
                            .then(response => {
                                this.students = response.data;
                            });
                },


                printResult(){
                  window.print();
                }},
                created(){

 Fire.$on('searching',() => {
                let query = this.$parent.search;
                axios.get(`/api/findStudent/${this.report}?q=` + query)
                .then((data) => {
                    this.students = data.data
                })
                .catch(() => {

                })
            })



 //setTimeout(function(){$('#data_tb').DataTable();}, 0);
             }

})

</script>

</body>
</html>

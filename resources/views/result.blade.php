
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

  <title>NNSS</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- Font Awesome Icons -->


  <!-- Theme style -->

  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
<style>


body {
  margin: 0;

}
  .flex-container{
    display: flex;
    flex-direction:row ;






}

.text-primary{
    color: rgb(23, 23, 107)
}
.text-danger{
    color: rgb(197, 49, 5)
}
.text-primary{
    color: rgb(23, 23, 107)
}

table, td, th {
  border: 0.5px solid black;
}

table {
  width: 100%;
  border-collapse: collapse;
}

.col-sm-2{
    flex: 2;
    flex-direction: row;

    padding: 12.5%;

}
.col-sm-8{
    flex: 8;
    width: 80%;

}
.col-sm-12{

    width: 100%;
    float: left;

}
.col-sm-3{
    float: left;
  width: 25%;
}
.col-sm-4{
    float: left;
  width: 25%;

    padding: 2px;
}
.col-sm-10{
    flex: 75;

    padding: 2px;
}
</style>

</head>
<body>





    <div class="flex-container" >



                <div class="  col-sm-12">
                <div class="col-sm-2"><img src="img/school/{{ $school->logo }}" class="img-thumbnail  result-logo" alt="logo" width="100" height="100"></div>
                <div class=" col-sm-8">
                <h3 class="text-primary text-uppercase">{{$school->name}},
                </h3>
                <h5>{{$school->contact_address}},&nbsp; {{$school->state}}</h5>
                <h5>P:&nbsp; {{$school->phone}}. &nbsp; E: {{$school->email}}</h5>
                <h5 class="text-danger">URL:&nbsp; {{$school->website}}.</h5>

                </div>
                <div class="col-sm-2">
                <h5 class="pt-2 text-danger" style="align-self: flex-end">Results Sheet</h5>
                </div>
                </div>
                <div>

                <div class="col-sm-12 ">

                <div class="row col-sm-10">
                <div class="col-sm-6 py-2">
                <h5><b>Name:</b>&nbsp; {{$summary->student->surname}}&nbsp; {{$summary->student->first_name}} &nbsp; {{$summary->student->middle_name?$summary->student->middle_name:''}}</h5>
                <h5><b>class:&nbsp;</b> {{$report->levels->level_name}}&nbsp;{{$arm->name}}</h5>
                <h5><b>Gender:&nbsp;</b> {{$summary->student->gender?$summary->student->gender:'-----------'}} </h5>
                <h5 ><b class="text-primary">Position In Class:</b>&nbsp; {{$summary->arm_position?$summary->arm_position:'-----------'}} Out Of {{$summary->total_students}}</h5>
                <hr>
                <!-- <h5 ><b>Portal ID:</b>&nbsp; </h5> -->
                </div>
                <div class="col-sm-6">
                <h4 class="text-primary text-uppercase  "><b>&nbsp;</b> {{$report->header?$report->header:''}}</h4>
                <hr class="text-danger mb-1">
                <h5 ><b>Term:&nbsp;</b> {{$report->terms->name}}</h5>
                <h5><b>Session:&nbsp;</b> {{$report->sessions->name}} </h5>
                <h5 ><b>Next Term Begins:&nbsp;</b> {{$report->term_start?$report->term_start:'---------'}}</h5>

                </div>

                </div>

                </div>
                <div style="width: 100%">
                <table >
                <thead>
                <tr>
                <td>S/N</td>
                <td>Subject</td>
                <td>Class <br>Work[2%]</td>
                <td>Assignment<br>[2%]</td>
                <td>Class<br>Test[5%]</td>
                <td>Notes<br>[1%]</td>
                <td>Weighted<br>Score[5%]</td>
                <td>Mid-Term<br>[25%]</td>
                <td>Exams<br>[70%]</td>
                <td>Total<br>[100%]</td>
                <td > Average <br>Score</td>
                <td >Position</td>
                <td>Teacher's<br>Comment</td>
                <!-- <th>Narration</th> -->
                <!-- <th class="text-center " v-show="isAHScore"><div><span>HIGHEST<br> SCORE</span></div></th>
                <th class="text-center" v-show="isASLScore"><div><span>LOWEST <br> SCORE</span></div></th> -->


                </tr>
                </thead>

                <tbody class="text-bold text-uppercase">

                    @foreach ($scores as $score)
                <tr  class="text-bold text-uppercase">
                <td>{{1}}</td>
                <td>{{$score->subjects?$score->subjects->name:''}}</td>
                <td>{{$score->test1}}</td>
                <td>{{$score->test2}}</td>
                <td>{{$score->test3}}</td>
                <td>{{$score->note}}</td>
                <td>{{$score->weighted_score}}</td>
                <td>{{$score->mid_term}}</td>
                <td>{{$score->exams}}</td>
                <td>{{$score->total}}</td>
                <td>{{$score->arm_avg_score?$score->arm_avg_score:'-'}}</td>
                <td>{{$score->arm_subj_position?$score->arm_subj_position:'-'}}</td>
                <td>{{$score->grade?$score->grade:'-'}}</td>




                </tr>
                @endforeach

                </tbody>


                </table>
                <div class="text-center text-bold text-primary container"> RESULTS SUMMARY</div>
                <hr class="text-bold">
                <div class="row col-md-12 py-3  bg-primary">
                 <div class="col-md-3"><b>Total score:</b>&nbsp;&nbsp;{{$summary->total_scores}}</div>
                 <div class="col-md-3"><b>Average score:</b>&nbsp;&nbsp;{{$summary->average_scores	}}</div>
                 <div class="col-md-3"><b>Cummulative Avg score:</b>&nbsp;&nbsp;{{$summary->cummulative_average}}</div>
                 <div class="col-md-3"><b>Grade:</b>&nbsp;&nbsp;{{$summary->grade}}</div>

                  </div>
                  <hr  class="text-bold">
                </div>

                <div class="col-md-12 row py-2" >
                <div class="col-md-3">
                    <table class="table table-bordered table-sm  table-striped" >
                <tr>
                 <th colspan="2"  class="text-uppercase text-center text-primary text-bold">PART B-AFFECTIVE</th>
                 </tr>
                 <tbody> @foreach ($LDomain as $ldomain)
                     <tr>
                         @if ( $ldomain->ldomain==='Skill')


                     <td >{{$ldomain->ldomain->domain}}</td>
                     <td >{{$ldomain->grade}}</td>
                     @endif
                     </tr>@endforeach
                 </tbody>
                    </table>
                </div>
                <div class="col-md-3">
                {{-- <table class="table table-bordered table-sm " >
                <tr >
                 <th colspan="2"  class="text-uppercase text-center text-primary text-bold">PART C-PSYCHOMOTOR</th>
                 </tr>

                 <tbody>
                     <tr v-for="ldomain in LDomain" :key="ldomain.id">
                     <td v-if="ldomain.ldomain.type==='Skill'">{{$ldomain->ldomain->domain}}</td>
                     <td v-if="ldomain.ldomain.type==='Skill'">{{$ldomain->grade}}</td>
                     </tr>
                 </tbody>
                    </table> --}}
                </div>

                <div class="  col-md-3  " >
                {{-- <table class=" table table-bordered table-sm  text-uppercase myTable table-striped" width="100%">
                <tr>
                <th colspan="3" class="text-center text-primary" >Grading Key</th>
                </tr>

                <tbody>
                <tr v-for="grade in grades" :Key="grade.id">
                <td>{{grade.lower_bound}} - {{grade.upper_bound}}</td>
                <td>{{grade.grade}}</td>
                <!-- <td>{{grade.narration}}</td> -->
                </tr>
                </tbody>
                </table> --}}
                </div>
                <div class="  col-md-3  " >
                <table class=" table table-bordered table-sm  text-uppercase myTable table-striped" width="100%">
                <tr>
                <th colspan="2" class="text-center text-primary" >Grading Key</th>

                </tr>
                <tr><th>GRADE</th><th>DEGREE</th></tr>
                <tbody>
                <tr>
                <td>5</td><td>Excellent </td>
                </tr><tr>
                <td>4</td><td>High </td>
                </tr><tr>
                <td>3</td><td>Acceptable </td>
                </tr>
                <tr>
                <td>2</td><td>Minimal </td>
                </tr><tr>
                <td>1</td><td>No Regard</td>
                </tr>
                </tbody>
                </table>
                </div>
                </div>

                <div class="  card-body row ">
                <div class=" row col-6"><span><b>Principal's Comment:&nbsp;</b>{{$principal_comment?$principal_comment:$summary->narration}}</span></div>
                <div  class=" row col-6 "><span><b>Form Tutor's Comment:&nbsp;</b >{{$staff_comment?$staff_comment:''}}</span></div>

                </div>
                <center>
                <div class=" text-center"><span><b>Authorized Signature:&nbsp;</b ><img :src="`/img/signatures/{{$signature->photo}}`" class="ml-2 img-result " width="60" height="50" onerror="this.style.display='none'"></span></div>
                </center>
                </div>

          </div>


        <!-- /.row -->
    <!-- /.container-fluid -->

    <!-- /.content -->

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


</body>
</html>

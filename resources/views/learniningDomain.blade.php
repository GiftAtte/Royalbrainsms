<table class="table" style="width: 100%; align-content:top;">
                 <tr>
                 <td style="width: 30%">
                   <table class="table table-bordered" style="width: 100%;font-size:xx-small">
                      <tr>
                 <td colspan="2" style="vertical-align: center;background-color:rgb(15, 15, 112); color:white"> AFFECTIVE </td>

                </tr>
                <tr><td>Domain</td><td>Grade</td>
                </tr>
                     @foreach ($LDomain as $ldomain)
                    @if($ldomain->ldomain->type==="Behavioural")
                    <tr class="text-capitalized">
                     <td >{{$ldomain->ldomain->domain}}</td>
                     <td >{{$ldomain->grade}}</td>
                     @endif
                     </tr>
                     @endforeach
                   </table>
                   </td>
                   <td style="width: 30%">
                   <table class="table-bordered myTable " style="width: 100%; font-size:xx-small">

                     <tr>
                <td colspan="2" style="vertical-align: center;background-color:rgb(15, 15, 112); color:white">PSYCHOMOTOR</td>

                </tr>
                <tr><td>Domain</td><td>Grade</td></tr>

                     @foreach ($LDomain as $ldomain)


                    @if( $ldomain->ldomain->type==="Skill")
                     <tr>
                     <td >{{$ldomain->ldomain->domain}}</td>
                     <td >{{$ldomain->grade}}</td>
                     @endif
                     </tr>
                     @endforeach
                   </table>
                 </td>



                 <td style="width: 38%">
               <table class=" table-bordered " style="width: 100% ; font-size:xx-small">
                <tr>
                <td colspan="3" style="vertical-align: center;background-color:rgb(15, 15, 112); color:white">GRADING KEYS</td>

                </tr>
                <tr><td>SCORES</td><td>GRADE</td><td>DEGREE</td></tr>
               @foreach ( $gradings as $grade )
               <tr class="text-capitalized">
          <td>{{$grade->lower_bound}} - {{$grade->upper_bound}}</td>
          <td>{{$grade->grade}}</td><td class="text-capitalized">{{$grade->narration}}</td>
                  </tr>
               @endforeach


                </table>

                </td>

                </tr>
                    </table>

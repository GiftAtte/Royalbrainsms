               <table class="table">
                 <tr>
                 <td>

                   <table class="table table-bordered myTable" style="font-size:xx-small">
                      <tr>
                 <th colspan="2" style="vertical-align: center;background-color:rgb(15, 15, 112); color:white"> AFFECTIVE </th>

                </tr>
                <tr><th>Domain</th><th>Grade</th>
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
                   <td>
                   <table class=" table table-bordered myTable " style="width: 100%; font-size:xx-small">

                     <tr>
                <th colspan="2" style="vertical-align: center;background-color:rgb(15, 15, 112); color:white">PSYCHOMOTOR</th>

                </tr>
                <tr><th>Domain</th><th>Grade</th></tr>

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
                </tr>
                    </table>

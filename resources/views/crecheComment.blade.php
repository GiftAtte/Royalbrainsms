
</style>

<table class="table bordered table-striped " style=" width:100%; font-size:xx-small">

    <tr style=" background-color: #0c018b; color:white">
         <th style="width:100%"><b class="float-left">OTHER SKILLS &nbsp;</b>/ &nbsp;<b style="right: 0; left-5px">RATINGS</b></th>
    </tr>
<tr><td>
<table class="table table-bordered">
 @foreach ($crecheComment as $cscore)
 @php
    $count=1;
@endphp

   <tr style=" background-color: #f2f2f2;">
    <th style="width: 5%">***</th>
    <th class="text-center" style="width: 65%;" >
    {{$cscore['domain']}}</th>
    <th>-----</th>
</tr>
@foreach($cscore['subdomains'] as $sub)
  <tr>
 <td style="width: 5%">{{ $count}}</td>
 <td style="width: 70%">{{$sub->subdomains->name }}</td><td>{{ $sub->ratings->rate }}</td>
   </tr>
   @php
    $count=$count+1;
   @endphp
@endforeach
@endforeach
</table>
</td></tr>
</table>

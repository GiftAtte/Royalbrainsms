
<table class="table ">
    <tbody>
    <tr style="vertical-align: top;">

       <td style="width: :60%">

          <table class="table" style="border: none">
            <tr>
                <td style="width: inherit">
                 @include('diamonScores')
                </td>
            </tr>
                <tr>
                <td>
                  @include('diamondSummary')

                </td>
            </tr>
            <tr>

                <td><h6 style="margin-top: 10px" class="text-danger">GRADING KEYS</h6>
                    <hr>
                     <p>
                    @foreach ( $gradings as $grade )

          |{{$grade->lower_bound}} - {{$grade->upper_bound}} : {{$grade->grade}} : {{$grade->narration}}|

               @endforeach
                 </p>
             @if ($report->isLearningDomain)
                <div>
            <h6 class="text-bold text-danger">AFFECTIVE DOMAIN KEYS</h6>
            <hr />
            <p class="text-bold fs-4">
              |Excellent - 5 | Good - 4 | Average - 3 | Below Average - 2 | Poor
              - 1 |
            </p>
          </div>
             @endif
                </td>
            </tr>


        </table>
       </td>
   <td style="width: 35%; vertical-align:top; position:fixed">
        @include('crecheComment')
       </td>
      </tr>
</tbody>
</table>

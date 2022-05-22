
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
                      @include('grading')
                </td>
            </tr>
            <tr>
                <td>
                    @include('attendance')
                </td>
            </tr>
        <tr>
                <td>
                  @include('diamondSummary')

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

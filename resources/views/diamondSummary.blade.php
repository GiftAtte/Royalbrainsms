<style>
    .summary{
      background-color:rgb(111, 1, 89);
      color: white;

    }
</style>

<table class="table myTable table-bordered summary
pt-2" style="max-width: 60%">

          <tr>
              <th colspan="3">

      <div class=" pt-2" style="width:100%;  height:10px; border-radius:25px; background-color:rgb(61, 19, 57)">
<h6 class="text-white text-center">RESULTS SAMMARY</h6>
    </div>
    </th>
          </tr>

                  <tr style="text-transform: uppercase;background-color:rgb(61, 19, 57);
                   color:white">
                      <th>
                          Total Scores
                      </th>
                      <th>
                          Average Score
                      </th>
                      <th>
                         Final Grade
                      </th>
                  </tr>
                   <tr class="text-center" style="background-color:rgba(241, 238, 238, 0.921);">
                     <td>{{$summary->total_scores}}</td>
                      <td>{{$summary->average_scores}}</td>
                      <td>{{$summary->grade}}</td>
                   </tr>
              </table>

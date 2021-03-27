<template>
    <div>
    <div class="col-md-4 ">
 <table class="table table-bordered table-sm  " width="100%" >
 <tr >
 <th colspan="2"  class="text-uppercase text-center text-bold">summary</th>
 </tr>

 <tbody>
 
 <tr>
 <td>Total score</td><td>{{summary.total_scores}}</td>
 </tr>
 <tr>
 <td>Average score</td><td>{{summary.average_scores	}}</td>
 </tr>
 <tr>
 <td>Cummulative Avg score</td><td>{{summary.cummulative_average	}}</td>
 </tr>
 <tr>
 <td>Grade</td><td>{{summary.grade}}</td>
 </tr>
 <tr>
 <td>Narration<td>{{summary.narration}}</td>
 </tr>
 <tr>
 <td>Students in class</td><td>{{summary.total_students}}</td>
 </tr>
 <tr>
 <td>Progress status</td><td>{{comment.comment?comment.comment:''}}</td>
 </tr>
 <tr>
 <td>Remarks</td><td>{{comment.comment?comment.comment:''}}</td>
 </tr>
 
 </tbody>
 </table>
</div>
<div class="col  col-md-4  ">
<table class=" table table-bordered table-sm  text-uppercase myTable " width="100%">
<tr>
<th colspan="3" class="text-center" >Gradding Key</th>
</tr>

<tbody>
<tr>
<td>75 - 100</td><td>A1</td><td>Excellent</td>
</tr>
<tr>
<td>70 - 74.99 </td><td>B2</td><td>Very Good</td>
</tr>
<tr>
<td>65-69.99</td><td>B3</td><td>Good</td>
</tr>
<tr>
<td>60-64.99</td><td>C4</td><td>Credit</td>
</tr>
<tr>
<td>55-59.99</td><td>C5</td><td>Credit</td>
</tr>
<tr>
<td>50-54.99</td><td>C6</td><td>Credit</td>
</tr>
<tr>
<td>45-49.99</td><td>D7</td><td>Fair</td>
</tr>
<tr>
<td>40-44.99</td><td>E8</td><td>Poor</td>
<tr>
<td>55-59.99</td><td>F9</td><td>Fail</td>
</tr>
</tr>
</tbody>
</table>
</div>
<div class=" col col-md-4 ">
<table class="table table-bordered table-sm  " width="100%" >
 <tr >
 <th colspan="2"  class="text-uppercase text-center text-bold">Short Keys</th>
 </tr>

 <tbody>
 
 <tr>
 <td>CA</td><td>Contineous Assesment</td>
 </tr>
 <tr>
 <td>CS Score</td>Class Subject</td>
 </tr>
 <tr>
 <td>SH Score</td><td>Subject Highest Score</td>
 </tr>
 <tr>
 <td>SL Score</td><td>Subject Lowest Score</td>
 </tr>
 <tr>
 <td>CSA Score<td>Class Subject Avg. Score</td>
 </tr>
 
 <tr>
 <td>AS Position</td><td>Arm Subject</td>
 </tr>
 <tr>
 <td>Remarks</td><td>{{comment.comment?comment.comment:''}}</td>
 </tr>

 </tbody>
 </table>
</div>

    </div>
</template>

<script lang="ts">
    
    export default {
        
    }
</script>


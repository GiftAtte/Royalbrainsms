<template>
  <table class="table table-bordered table-sm font-weight-bold  table-striped text-center" >
 <tr >
 <th colspan="8"  class="text-uppercase text-center text-primary text-bold">Results summary</th>
 </tr>

 <tbody>
     <tr style="vertical-align: center;background-color:rgb(15, 15, 112); color:bisque">
         <th v-if="report.isArmPosition">Student <br/>Position</th>
         <th>Total score</th>
         <th>Average <br/>score</th>
         <th v-if="report.isCummulative">
             Cummulative Avg score
         </th>
         <th>Grade</th>
         <th>Narration</th>
         <th>Students In Class</th>
         <th v-if="report.isProgress_status">Progress status</th>

     </tr>

<tr>

<td v-if="report.isArmPosition" >{{summary.arm_position}}</td>

<td>{{summary.total_scores}}</td>

<td>{{summary.average_scores}}</td>


 <td v-if="report.isCummulative">{{summary.cummulative_average	}}</td>


<td>{{summary.grade}}</td>


 <td>{{summary.narration}}</td>

 <td>{{summary.total_students}}</td>

<td v-if="report.isProgress_status"><b v-if="report">{{summary.progress_status?summary.progress_status:'---------------'}}</b></td>
</tr>

 </tbody>
 </table>
</template>

<script>
export default {
 props:[
      'report',
     'summary',
     'attendance'

 ]
}
</script>

<style>

</style>

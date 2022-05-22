<template>
<div class="col-md-4" v-if="report.isAttendance && attendance">
<table class=" table table-bordered table-sm font-weight-bold  table-striped" width="100%">
<tr>
<th colspan="3" class="text-center text-primary text-uppercase table-sm" >Grading Key</th>
</tr>

<tbody>
<tr v-for="grade in grades" :Key="grade.id">
<td>{{grade.lower_bound}} - {{grade.upper_bound}}</td><td>{{grade.grade}}</td><td>{{grade.narration}}</td>
</tr>
</tbody>
</table>
</div>

<div class="col-md-12" v-else>
<table class=" table table-bordered table-sm font-weight-bold  table-striped" width="100%">
<tr>
<th colspan="3" class="text-center text-primary text-uppercase table-sm" >Grading Key</th>
</tr>

<tbody>
<tr v-for="grade in grades" :Key="grade.id">
<td>{{grade.lower_bound}} - {{grade.upper_bound}}</td><td>{{grade.grade}}</td><td>{{grade.narration}}</td>
</tr>
</tbody>
</table>

</div>

</template>

<script>
export default {
props:['grades','report','attendance']
}
</script>


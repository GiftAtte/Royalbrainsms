<template>
  <table class="table table-bordered" style="width: 100%">
    <thead>
        <tr>
            <th colspan="14">
        <div class="text-center text-bold text-primary">
         DROPPED SUBJECTS
         </div>
         </th>
        </tr>
    </thead>
    <tbody>
<tr>
    <th colspan="2">S/N</th>
    <th colspan="4">Subject</th>
    <th colspan="4">Total Scores</th>
    <th colspan="4">Average Score</th>
</tr>

<tr v-for="(score, index) in scores" :key="index">
     <td colspan="2">{{ index+1 }}</td>
    <td colspan="4">{{ score.subject }}</td>
     <td colspan="4">{{ score.grandTotal}}</td>
      <td colspan="4">{{ score.average }}</td>
</tr>

    </tbody>

</table>
</template>

<script>
export default {
  props:['scores']
}
</script>

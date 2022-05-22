<template>
<div class="col-md-8">

  <table class="table table-bordered" >
            <thead>
          <tr style="vertical-align: center;background-color:rgb(15, 15, 112); color:bisque">
              <th colspan="3" class="text-center">
ATTENDANCE
              </th>

          </tr>


      </thead>
      <tbody>
          <tr>
              <th>Total School Days </th>
              <th>Days Present</th>
              <th>Days Absent </th>
          </tr>
          <tr>
              <td>{{ attendance.school_days}}</td>
              <td>{{ attendance.days_absent}}</td>
              <td>{{ attendance.days_present}}</td>
          </tr>
      </tbody>
  </table>
  </div>
</template>

<script>
export default {
props:['attendance']
}
</script>

<style>

</style>

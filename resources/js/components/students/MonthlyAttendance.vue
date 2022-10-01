<template>
  <div class="table-responsive container">
    <table class="table table-sm table-bordered table-striped">
      <tr>
        <th class="text-center">STUDENTS</th>
        <th
          class="vertical-header text-uppercase"
          v-for="(day, index) in daysOfMonth"
          :key="index"
        >
          <span>{{ day.dayOfWeek }}</span>
        </th>
      </tr>
      <tr>
        <td class="action text-uppercase text-bold">
          {{ month }}
        </td>
        <td class="text-bold" v-for="(day, index) in daysOfMonth" :key="index">
          {{ day.dayOfMonth }}
        </td>
      </tr>
      <tr v-for="student in students" :key="student.id">
        <td>
          <span class="mx-1 text-bold">{{ student.name }}</span>
        </td>
        <td v-for="(day, index) in student.attendance" :key="index">
          <i v-if="day.isPresent" class="fa fa-check text-success"></i>
          <i v-else class="fa fa-times text-danger"></i>
        </td>
      </tr>
    </table>
  </div>
</template>
<script>
import moment from "moment";
export default {
  props: {
    students: Array,
    daysOfMonth: Array,
    month: String,
  },
  data() {
    return {};
  },
  methods: {},
  created() {
    //  console.log("this.students", this.daysOfMonth, this.month);
  },
};
</script>
<style scoped>
table {
  border-collapse: collapse;
  width: 100%;
  font-size: 14px;
}
.table,
.table-sm {
  border-collapse: collapse;
  width: 100%;
  font-size: 14px !important;
}
.table-bordered th {
  font-weight: medium;
  border: 0.25px solid rgb(97, 95, 95) !important;
  padding: 2.5px;
}
.table-bordered td {
  border: 0.25px solid rgb(161, 158, 158) !important;
  padding: 1.5px;
  text-transform: capitalize;
  font-weight: medium;
  font-size: 14px !important;
}

.vertical-header span {
  writing-mode: vertical-lr;
  transform: rotate(-180deg);
  text-align: end;
  justify-items: baseline;
  max-height: 150px;
}
.vertical-header {
  justify-items: self-start;
}
</style>

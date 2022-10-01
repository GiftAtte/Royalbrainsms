<template>
  <div class="col-md-12">
    <div class="card">
      <div class="card-body">
        <div class="col-md-12 row">
          <div class="col-md-6">ATTENDANCE VIEW</div>
          <div class="col-md-6">
            <button @click="showModal" class="btn btn-lg float-right">
              <i class="fa fa-list mx-1"></i> GENERATE REPORT
            </button>
            <button class="btn float-right mx-2" @click="printReport">
              <i class="fa fa-print"></i>
            </button>
          </div>
        </div>
        <div class="row" id="section-to-print">
          <monthly-attendance
            v-if="monthlyAttendance.length"
            :students="monthlyAttendance"
            :daysOfMonth="daysOfMonth"
            :month="month"
          />
          <div class="table-responsive" v-else>
            <table class="table table-bordered">
              <tr class="text-center">
                <th>PRESENT</th>
                <th>ABSENT</th>
                <th colspan="2">CHART</th>
              </tr>
              <tr class="text-center">
                <td>
                  <h3 h3 class="mx-auto text-success my-auto">
                    {{ numPresent ? numPresent : 0 }}
                  </h3>
                </td>
                <td>
                  <h3 h3 class="mx-auto text-danger my-auto">
                    {{ numAbsent ? numAbsent : 0 }}
                  </h3>
                </td>
                <td colspan="2" style="width: 20%">
                  <piechart
                    v-if="numPresent > 0"
                    style="height: 100px"
                    :numPresent="numPresent"
                    :numAbsent="numAbsent"
                  />
                  <h6 class="text-danger" v-else>Attendance not entered</h6>
                </td>
              </tr>

              <tr>
                <th>S/N</th>
                <th>Names</th>
                <th>Symbol</th>
                <th>Status</th>
              </tr>
              <tr
                v-for="(student, index) in attendanceList"
                :key="student.student_id"
              >
                <td colspan="0.5">{{ index + 1 }}</td>
                <td>{{ student.name }}</td>
                <td v-if="student.isPresent">
                  <i class="fa fa-check text-success"></i>
                </td>
                <td v-else>
                  <i class="fa fa-times text-danger"></i>
                </td>
                <td>
                  <span class="text-success" v-if="student.isPresent"
                    >Present</span
                  >
                  <span class="text-dander" v-else>Absent</span>
                </td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
    <app-modal
      :createAction="getMonthlyAttendance"
      modalTitle="Generate Monthly Report"
      sumitButtonText="Generate"
    >
      <input-field
        label="Attendance Date"
        v-model="form.attendance_date"
        id="attendance_date"
        :form="form"
        field="attendance_date"
        placeholder=" "
        type="date"
      />
    </app-modal>
  </div>
</template>

<script>
import moment from "moment";
import AppModal from "../utils/AppModal.vue";
import MonthlyAttendance from "./MonthlyAttendance.vue";
import Piechart from "./Piechart.vue";
export default {
  components: { Piechart, MonthlyAttendance, AppModal },
  props: ["numAbsent", "numPresent", "attendanceList", "form"],
  data() {
    return {
      attendance: [],
      monthlyAttendance: [],
      daysOfMonth: [],
      month: "",
    };
  },
  methods: {
    showModal() {
      $("#appModal").modal("show");
    },
    printReport() {
      window.print();
    },
    monthSplites() {
      this.daysOfMonth = [];

      if (this.monthlyAttendance && this.monthlyAttendance.length) {
        let date = this.form.attendance_date;
        let attendanceMonth = new Date(date).getMonth() + 1;

        let attendanceYear = new Date(date).getFullYear();
        console.log("YYY:", attendanceYear);
        this.daysOfMonth = this.getDaysInMonth(attendanceMonth, attendanceYear);

        this.month = this.toMonthName(
          new Date(this.form.attendance_date).getMonth()
        );
      }
    },

    getDaysInMonth(month, year) {
      let date = new Date(year, month, 1);
      let days = [];
      while (date.getMonth() === month) {
        days.push({
          day: moment(new Date(date)).format("dddd"),
          date: new Date(date).getDate(),
          month: moment(new Date(month).getMonth()).format("MMMM"),
        });
        date.setDate(date.getDate() + 1);
      }
      console.log("days:", days);
      return days;
    },
    getMonthlyAttendance() {
      this.form.year = new Date(this.form.attendance_date).getFullYear();
      this.form.month = new Date(this.form.attendance_date).getMonth() + 1;
      this.monthlyAttendance = [];
      this.daysOfMonth = [];
      this.month = "";

      this.form
        .post("/api/attendanceByMonth")
        .then((res) => {
          this.monthlyAttendance = res.data.attendance;
          this.daysOfMonth = res.data.daysOfMonth;
          this.month = this.toMonthName(res.data.month);
          $("#appModal").modal("hide");
        })
        .catch((err) => console.log(err));
      //  setTimeout(() => this.monthSplites(), 1000);
    },

    toMonthName(monthNumber) {
      const date = new Date();
      date.setMonth(monthNumber - 1);

      return date.toLocaleString("en-US", {
        month: "long",
      });
    },
  },
};
</script>

<template>
  <div class="container">
    <div class="card">
      <div class="card-header bg-primary">
        <h5>ATTENDANCE UPDATE</h5>
      </div>

      <div class="card-body">
        <div class="row">
          <div class="table-responsive">
            <table class="table table-bordered">
              <tr class="text-center">
                <th>S/N</th>
                <th>STUDENTS</th>
                <th>
                  Mark All&nbsp;<input
                    type="checkbox"
                    @click="selectAll"
                    v-model="allSelected"
                    :checked="isSelectAll"
                  />
                </th>
              </tr>
              <tr
                v-for="(student, index) in attendance"
                :key="student.student_id"
              >
                <td>{{ index + 1 }}</td>
                <td>
                  {{ student.name }}
                  <input
                    type="hidden"
                    :id="`student_id${index}`"
                    :value="student.student_id"
                  />
                  <!-- <input type="hidden" :id="`arm_id${index}`" :value="score.arm_id"> -->
                </td>
                <td>
                  <input
                    class="checkbox-success"
                    :id="`student${student.student_id}`"
                    type="checkbox"
                    @click="select(student.student_id)"
                    :checked="isChecked"
                  />
                </td>
              </tr>
            </table>
          </div>
        </div>
      </div>
      <div class="card-footer">
        <button class="btn btn-primary float-right" @click="updateAttendance">
          UPDATE
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import Piechart from "./Piechart.vue";
export default {
  components: { Piechart },
  props: ["form", "attendance"],
  data() {
    return {
      isSelectAll: false,
      selected: [],
      allSelected: false,
      studentIds: [],
      isChecked: false,
      isStudentId: false,
    };
  },
  methods: {
    selectAll() {
      this.studentIds = [];
      if (this.isChecked) {
        this.isChecked = false;

        return this.checkStudentId();
      }
      const students = this.attendance;
      this.isChecked = true;
      if (!this.allSelected) {
        for (let index = 0; index < students.length; index++) {
          this.studentIds.push(students[index].student_id);
          this.checkStudentId();
          this.allSelected = true;
        }
        //  console.log(this.studentIds)
        this.checkStudentId();
      }
    },
    select(id) {
      if (this.allSelected) {
        const index = this.studentIds.indexOf(id);
        if (index > -1) {
          this.studentIds.splice(index, 1);
          this.checkStudentId();
        } else {
          this.studentIds.push(id);
          this.checkStudentId();
        }
      } else {
        const index = this.studentIds.indexOf(id);
        if (index > -1) {
          this.studentIds.splice(index, 1);
          this.checkStudentId();
        } else {
          this.studentIds.push(id);
          this.checkStudentId();
        }
      }

      this.checkStudentId();
      console.log(this.studentIds);
    },
    checkStudentId() {
      let studentLength = this.studentIds.length;
      if (this.studentIds.length > 0) {
        this.isStudentId = true;
      } else {
        this.isStudentId = false;
      }

      if (this.studentIds.length === this.attendance.length) {
        this.isSelectAll = true;
      } else {
        this.isSelectAll = false;
      }
    },

    updateAttendance() {
      this.form.students = this.studentIds;
      this.$Progress.start();
      this.form.post("/api/addAttendance").then((res) => {
        swal.fire("Updated!", "Attendance added successfully", "success");
        this.$Progress.finish();
        // Fire.$emit("AfterCreate");
      });
    },
  },

  mounted() {
    // this.attendance.forEach((student) => {
    //     if (student.isPresent) {
    //         this.select(student.student_id);
    //     }
    // });
  },
};
</script>

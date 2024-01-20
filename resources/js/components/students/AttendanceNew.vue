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

    <div class="row">
      <div class="col-md-6">
        <StreamBarcodeReader @decode="onDecode" @loaded="onLoaded"></StreamBarcodeReader>
      </div>
      <div class="col-md-6">
          <h1>Student Details</h1>
    <ul v-show="barcodeCaptured">
  <li>Name:{{ studentDetails.name }}   <span class="float-right"><img
                                                class="profile-user-img img-fluid img-rounded"
                                                width="50"
                                                height="50"
                                                :src="`/img/profile/${studentDetails.img}`"
                                                alt="User profile picture"
                                                 onerror="this.src='/img/profile.png'"
                                            />
                                        </span></li>
  <li>Gender:{{ studentDetails.gender }}</li>
  <li>Level:{{ studentDetails.level }}</li>
  <li>Student ID:Stud-{{ studentDetails.id }}</li>
  <i><h3>Attendance</h3>
    <hr>
  </i>
 
  <li>Status:
    <p>Date:{{ new Date()|myDateTime }} <span class="float-right"><i class="fa fa-check text-success"></i></span></p>
  <h3 class="text-center text-danger">Please take off your card</h3>
  </li>
    </ul>
    <h3 v-show="!barcodeCaptured" class=" my-5 text-center text-success">Scanner ready !</h3>
      </div>
    </div>
    <!-- <ImageBarcodeReader @decode="onDecode" @error="onError"></ImageBarcodeReader> -->
  </div>
</template>

<script>
import Piechart from "./Piechart.vue";
export default {
  components: { Piechart  },
  
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

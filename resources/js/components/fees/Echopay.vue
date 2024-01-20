<template>
  <div>
    <div class="content-header">
      <div class="container-fluid navy">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">E-Wallet</h1>
          </div>
          <!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">E-Wallet</li>
            </ol>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </div>

    <div class="content col-md-12" v-if="$gate.isTutorOrAdmin()">

      <div class="card card-navy card-outline">
           <Loading
      :active.sync="isLoading"
      :can-cancel="false"
      :on-cancel="onCancel"
      color="green"
      :is-full-page="fullPage"
    >
    </Loading>
        <div class="card-header">
          <div class="row">
            <div class="col-md-2">
              <select
                name="class_id"
                id="level"
                :class="{ 'is-invalid': form.errors.has('class_id') }"
                class="form-control"
                v-model="level_id"
                @change="checkArm"
              >
                <option value selected>Select Class/Level</option>
                <option
                  v-for="level in levels"
                  :key="level.id"
                  :value="level.id"
                >
                  {{ level.level_name }}
                </option>
              </select>
              &nbsp; &nbsp;
            </div>

            <div v-show="hasArm" class="col-md-2">
              <select
                name="arm_id"
                id="arm_id"
                :class="{ 'is-invalid': form.errors.has('arm_id') }"
                class="form-control"
                v-model="arm_id"
                @change="loadStudents"
              >
                <option value selected>Select Class Arm</option>
                <option v-for="arm in arms" :key="arm.id" :value="arm.id">
                  {{ arm.name }}
                </option>
              </select>
              <has-error :form="form" field="arm_id"></has-error>
            </div>
            <div v-show="hasArm">
              <export-excel class="btn btn-primary" :data="student_login">
                Download Account Numbers
                <i class="fa fa-download"></i>
              </export-excel>
            </div>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive">
          <div class="mb-2"> <button v-show="$gate.isAdmin()"
          class="btn btn-md btn-success float-right"
          @click="enrollForAccountNumber"
        >
          <i class="fas fa-user-plus fa-fw"></i> Generate New Account Number
        </button></div>
          <table class="table table-hover">
            <tbody>
              <tr>
                <th>
                  Select All&nbsp;<input
                    type="checkbox"
                    @click="selectAll"
                    v-model="allSelected"
                    :checked="isSelectAll"
                  />
                </th>
                <th>S/ID</th>
                <th colspan="2">Account Names</th>

                <th>Levels</th>
                <th>Arm</th>
                <th>Account Number</th>

                <th>Enrolement Status</th>
              </tr>

              <tr v-for="student in students.data" :key="student.id">
                <td width="20">
                  <input
                    :id="`student${student.id}`"
                    type="checkbox"
                    @click="select(student.id)"
                    :checked="isChecked"
                  />
                </td>
                <td>{{ student.id }}</td>

                <td colspan="2">
                  <router-link
                    :to="`student_profile/${student.id}`"
                    tag="a"
                    exact
                  >
                    {{ student.surname }}, &nbsp;{{
                      student.first_name
                    }}
                    &nbsp;{{ student.middle_name ? student.middle_name : "" }}
                  </router-link>
                </td>
                <td>{{ student.levels ? student.levels.level_name : "" }}</td>
                <td>{{ student.arm ? student.arm.name : "" }}</td>

                <td>
                  {{
                    student.accountNumber
                      ? student.accountNumber
                      : "Not enrolled"
                  }}
                </td>
                <td>
                  <button
                    class="btn btn-success btn-sm"
                    @click="student.accountNumber?'': generatetAccountById(student.id)"
                     :disabled="student.accountNumber"
                  >
                    Generate Account
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
          <pagination
            :data="students"
            @pagination-change-page="getResults"
          ></pagination>
        </div>
      </div>
      <!-- /.card-body -->
      <div class="card-footer">
       
      </div>
    </div>
    <!-- /.card -->

    <div v-if="!$gate.isAdminOrTutor()">
      <not-found></not-found>
    </div>
  </div>
</template>

<script>
import Loading from "vue-loading-overlay";
export default {
  props: ["post-route"],
  components:Loading,
  computed: {
    school() {
      return this.$store.state.school;
    },
  },
  data() {
    return {
         isLoading:true,
        fullPage: true,
      editmode: false,
      students: [],
      levels: {},
      accountDetails: "",
      isSelectAll: false,
      arms: {},
      hasArm: false,
      selected: [],
      generatedAccounts: [],
      noAccountNumber:false,
      allSelected: false,
      level_id: "",
      arm_id: "",
      studentIds: [],
      isChecked: false,
      isStudentId: false,
      student_login: "",
      importFile: "api/importUser",
      form: new Form({
        id: "",
        arm_id: "",
        surname: "",
        first_name: "",
        middle_name: "",
        dob: "",
        phone: "",
        gender: "",
        dob: "",
        class_id: "",
        school_id: window.user.school_id,
      }),
    };
  },
  mounted() {
      if(this.$gate.isParent()){
          this.$router.push('/parents/wallet')
      }
    axios.get("api/arms").then((res) => {
      this.arms = res.data;
      // this.id=id;
    });
  },
  methods: {
    getResults(page = 1) {
      axios.get("api/student?page=" + page).then((response) => {
        this.students = response.data;
       
      });
    },
    updateStudent() {
      this.$Progress.start();
      // console.log('Editing data');
      this.form
        .put("/api/student_update/" + this.form.id)
        .then(() => {
          // success
          $("#addNew").modal("hide");
          swal.fire("Updated!", "Information has been updated.", "success");
          this.$Progress.finish();
          Fire.$emit("AfterCreate");
        })
        .catch(() => {
          this.$Progress.fail();
        });
    },
    editForm(student) {
      this.editmode = true;
      // Fire.$emit('AfterCreate');
      this.form.reset();
      $("#addNew").modal("show");
      this.form.fill(student);
    },

    loadLevels() {
      if (this.$gate.isAdminOrTutor()) {
        axios.get("api/get_levels").then(({ data }) => {
          this.levels = data;
          // console.log(this.levels);
        });
      }
    },
    loadStudents() {
      if (this.$gate.isAdminOrTutorOrParent()) {
        axios
          .get("/api/students/" + this.level_id + "/" + this.arm_id)
          .then(({ data }) => (this.students = data));
      }
      this.downloadLogin();
    },

    createStudent() {
      this.$Progress.start();

      this.form
        .post("api/student")
        .then((res) => {
          Fire.$emit("AfterCreate");
          $("#addNew").modal("hide");

          toast.fire({
            type: "success",
            title: "Student Created in successfully",
          });
          //console.log(res)
          this.$Progress.finish();
        })
        .catch((err) => {
          toast.fire({
            type: "fail",
            title: err,
          });
          this.$Progress.fail();
        });
    },

    checkArm() {
      var e = document.querySelector("#level");
      var id = e.options[e.selectedIndex].value;
      console.log(id);
      axios
        .get(`api/check_arm/${id}`)
        .then((res) => {
          if (res.data > 0) {
            this.hasArm = true;
            //  this.downloadLogin();
          } else {
            this.hasArm = false;
          }
        })
        .catch((err) => {
          console.log(err);
        });
    },

    selectAll() {
      this.studentIds = [];
      if (this.isChecked) {
        this.isChecked = false;

        return this.checkStudentId();
      }
      const students = this.students.data;
      this.isChecked = true;
      if (!this.allSelected) {
        for (let index = 0; index < students.length; index++) {
          this.studentIds.push(students[index].id);
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

      if (this.studentIds.length === this.students.length) {
        this.isSelectAll = true;
      } else {
        this.isSelectAll = false;
      }
    },

    setActivation(id) {
      axios.put("/api/account_enrollment/" + id).then((res) => {});
    },
    downloadLogin() {
      axios
        .get(`/api/login_export/${this.level_id}/${this.arm_id}/isAccount`)
        .then((res) => {
          this.student_login = res.data.student_login;
        });
    },

    enrollForAccountNumber() {


      axios.get("/api/ibtc-new-acount").then((res) => {
        Fire.$emit("AfterCreate");
        swal.fire("Updated!", "account numbers updated successfully.", "success");
      });
    },


generatetAccountById(id){

  axios.get(`/api/account-by-student/${id}`)
  .then((res) => {
    Fire.$emit("AfterCreate");
      });
},


    generateBulkAccount() {
      const formData = new FormData();
      formData.append("accountDetails", this.studentIds);
    this.$Progress.start()
      axios
        .post("api/generateBulkAccountNumbers", formData)
        .then((response) => {

             swal.fire("Updated!", "account numbers generated successfully.", "success");
              Fire.$emit("AfterCreate");
              this.$Progress.end()
        })
        .catch((err) => {
            this.resetLoading()
        });
    },
     setLoading(){
             this.isLoading=true
           },
           resetLoading(){
             this.isLoading=false
           }
  },
  created() {
    Fire.$on("searching", () => {
      let query = this.$parent.search;
      axios
        .get("api/findStudent?q=" + query)
        .then((data) => {
          this.students = data.data;
          this.noAccountNumber=this.students.filter(s=>s.accountNumber!==null).length>0
          console.log('no account',  this.noAccountNumber)
        })
        .catch(() => {});
    });
    this.loadStudents();
    this.loadLevels();

    Fire.$on("AfterCreate", () => {
      this.loadStudents();
    });
    //setInterval(() => this.loadUsers(), 3000);
  },
};
</script>

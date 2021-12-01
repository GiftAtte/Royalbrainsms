<template>
  <div>
    <div class="content-header">
      <div class="container-fluid navy">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h2 class="m-0 text-dark">Marks Update</h2>
          </div>
          <!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Score Card</li>
            </ol>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </div>
    <div class="content col-12">
      <div class="card">
        <div class="card-header row">
          <div class="col-md-6">
            <h4 v-show="!isSession" class="text-danger">
              <router-link :to="`/import_scores`" tag="a" exact
                >Import scores {{ "(cvs)" }} </router-link
              >&nbsp;/Select Report Set
            </h4>
            <h4 v-show="isSession" class="card-heading text-primary">
              Select Subject
            </h4>
            <button
              v-show="isSession"
              @click="resetSession"
              class="btn btn-success float-right"
            >
              <i class="fa fa-plus"></i>Load Session
            </button>
          </div>

          <div class="col-md-6">
            <input type="file" ref="file" @change="setFile" /><button
              @click="importExcel"
              class="btn btn-success pull-right m-2"
            >
              import(cvs)
            </button>
          </div>
        </div>
        <form @submit.prevent="createScore">
          <div class="card-body content">
            <loading
              :active.sync="isLoading"
              color="green"
              :is-full-page="true"
            >
            </loading>
            <div class="row">
              <div v-show="!isSession" class="form-group col-md-12">
                <select
                  name="report_id"
                  id="report_id"
                  :class="{ 'is-invalid': form.errors.has('report_id') }"
                  class="form-control"
                  v-model="form.report_id"
                  @change="loadArms"
                >
                  <option value selected>Select Report class set</option>
                  <option
                    v-for="report in reports"
                    :key="report.id"
                    :value="report.id"
                  >
                    {{ report.title }}
                  </option>
                </select>
                <has-error :form="form" field="subject"></has-error>
              </div>

              <div class="form-group col-md-12 col-sm-12" v-show="isArm">
                <select
                  name="arm_id"
                  id="arm_id"
                  :class="{ 'is-invalid': form.errors.has('report_id') }"
                  class="form-control"
                  v-model="form.arm_id"
                  @change="loadSubjects"
                >
                  <option value selected>Select Class/Level Arm</option>
                  <option
                    v-for="arm in arms"
                    :key="arm.arms.id"
                    :value="arm.arms.id"
                  >
                    {{ arm.arms.name }}
                  </option>
                </select>
                <has-error :form="form" field="subject"></has-error>
              </div>

              <div v-show="isSession" class="form-group col-md-12">
                <select
                  name="subject_id"
                  id="report_id"
                  :class="{ 'is-invalid': form.errors.has('subject_id') }"
                  class="form-control"
                  v-model="form.subject_id"
                  @change="loadStudents"
                >
                  <option value selected>Select Subject</option>
                  <option
                    v-for="subject in subjects"
                    :key="subject.id"
                    :value="subject.subjects ? subject.subjects.id : subject.id"
                  >
                    {{ subject.subjects ? subject.subjects.name : subject.id }}
                  </option>
                </select>
                <has-error :form="form" field="subject_id"></has-error>
              </div>
            </div>

            <div
              v-show="isSubject"
              class="card-body table-responsive col-md-12"
            >
              <div class="table-responsive">
                <!-- scondary Section -->
                <table
                  v-if="
                    report === 'primary-midterm' ||
                    report === 'primary-terminal'
                  "
                  class="table table-hover table-sm"
                >
                  <thead>
                    <tr>
                      <th>S/N</th>
                      <th>Name</th>
                      <th>First Test[10%]</th>
                      <th>Second Test[10%]</th>
                      <th>Exams/Mid-term[40%]</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(score, index) in Scores" :key="index">
                      <td>{{ index + 1 }}</td>
                      <td>
                        {{ score.name }}
                        <input
                          type="hidden"
                          :id="`student_id${index}`"
                          :value="score.student_id"
                        />
                        <input
                          type="hidden"
                          :id="`arm_id${index}`"
                          :value="score.arm_id"
                        />
                      </td>
                      <td>
                        <input
                          :id="`test1${index}`"
                          :value="score.test1"
                          type="number"
                          placeholder="Class Work"
                          max="100"
                          min="0"
                          step="0.01"
                        />
                      </td>
                      <td>
                        <input
                          :id="`test2${index}`"
                          :value="score.test2"
                          type="number"
                          placeholder="Assignment "
                          max="100"
                          min="0"
                          step="0.01"
                        />
                      </td>
                      <td>
                        <input
                          type="number"
                          :id="`midterm${index}`"
                          :value="score.total"
                          placeholder="Class Test "
                          max="100"
                          min="0"
                          step="0.01"
                          disabled
                        />
                      </td>
                      <!-- <input type="hidden" :id="`test3${index}`" :value="score.test3"  placeholder="Class Test " max="100" min="0" step="0.01">
<input :id="`note${index}`" :value="score.note" type="hidden" placeholder="Notes " max="100" min="0" step="0.01"></td> -->
                      <td>
                        <input
                          :id="`exams${index}`"
                          :value="score.exams"
                          type="number"
                          placeholder="Mid-Term Test/Exams"
                          max="100"
                          min="0"
                          step="0.01"
                        />
                      </td>
                    </tr>
                  </tbody>
                </table>
                <table
                  v-if="
                    report === 'default-result' || report === 'default-midterm'
                  "
                  class="table table-hover table-sm"
                >
                  <thead>
                    <tr>
                      <th>S/N</th>
                      <th>Name</th>
                      <th>CA1</th>
                      <th>CA2</th>
                      <th>CA3</th>
                      <th
                        v-show="
                          Scores[0] &&
                          Scores[0].total &&
                          report === 'default-result'
                        "
                      >
                        MID-TERM
                      </th>
                      <th v-show="report === 'default-midterm'">
                        MID-TERM EXAMS
                      </th>
                      <th v-show="report === 'default-result'">
                        TERMINAL EXAMS
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(score, index) in Scores" :key="index">
                      <td>{{ index + 1 }}</td>
                      <td>
                        {{ score.name }}
                        <input
                          type="hidden"
                          :id="`student_id${index}`"
                          :value="score.student_id"
                        />
                        <input
                          type="hidden"
                          :id="`arm_id${index}`"
                          :value="score.arm_id"
                        />
                      </td>
                      <td>
                        <input
                          :id="`test1${index}`"
                          :value="score.test1"
                          type="number"
                          placeholder="CA1"
                          max="100"
                          min="0"
                          step="0.01"
                        />
                      </td>
                      <td>
                        <input
                         :disabled="!isCa2"
                          :id="`test2${index}`"
                          :value="score.test2"
                          type="number"
                          placeholder="CA2"
                          max="100"
                          min="0"
                          step="0.01"
                        />
                      </td>
                      <td>
                        <input
                          :disabled="!isCa3"
                          type="number"
                          :id="`test3${index}`"
                          :value="score.test3"
                          placeholder="CA3 "
                          max="100"
                          min="0"
                          step="0.01"
                        />
                      </td>
                      <td style="display: none">
                        <input
                          :id="`note${index}`"
                          :value="score.note"
                          type="hidden"
                          placeholder="Notes "
                          max="100"
                          min="0"
                          step="0.01"
                        />
                      </td>
                      <td
                        v-show="
                          Scores[0] &&
                          Scores[0].total &&
                          report === 'default-result'
                        "
                      >
                        <input
                          type="number"
                          :id="`midterm${index}`"
                          :value="score.total"
                          placeholder="Exam  "
                          max="100"
                          min="0"
                          step="0.01"
                          disabled
                        />
                      </td>
                      <input
                        :id="`exams${index}`"
                        :value="score.exams"
                        type="number"
                        placeholder="Mid-Term Test/Exams"
                        max="100"
                        min="0"
                        step="0.01"
                      />
                    </tr>
                  </tbody>
                </table>

                <!-- primary Section -->
                <table v-else class="table table-hover table-sm">
                  <thead>
                    <tr>
                      <th>S/N</th>
                      <th>Name</th>
                      <th>Class work[2]</th>
                      <th>Assignment[2]</th>
                      <th>Class Test[5]</th>
                      <th>Notes[1]</th>
                      <th
                        v-show="
                          Scores[0] && Scores[0].total && report != 'mid_term'
                        "
                      >
                        midterm
                      </th>
                      <th>Exams[20/70]</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(score, index) in Scores" :key="index">
                      <td>{{ index + 1 }}</td>
                      <td>
                        {{ score.name }}
                        <input
                          type="hidden"
                          :id="`student_id${index}`"
                          :value="score.student_id"
                        />
                        <input
                          type="hidden"
                          :id="`arm_id${index}`"
                          :value="score.arm_id"
                        />
                      </td>
                      <td>
                        <input
                          :id="`test1${index}`"
                          :value="score.test1"
                          type="number"
                          placeholder="Class Work"
                          max="100"
                          min="0"
                          step="0.01"
                        />
                      </td>
                      <td>
                        <input
                          :id="`test2${index}`"
                          :value="score.test2"
                          type="number"
                          placeholder="Assignment "
                          max="100"
                          min="0"
                          step="0.01"
                        />
                      </td>
                      <td>
                        <input
                          :id="`test3${index}`"
                          :value="score.test3"
                          type="number"
                          placeholder="Class Test "
                          max="100"
                          min="0"
                          step="0.01"
                        />
                      </td>
                      <td>
                        <input
                          :id="`note${index}`"
                          :value="score.note"
                          type="number"
                          placeholder="Notes "
                          max="100"
                          min="0"
                          step="0.01"
                        />
                      </td>
                      <td v-if="score.total != null && report != 'mid_term'">
                        <input
                          :id="`midterm${index}`"
                          :value="score.total"
                          disabled
                          max="100"
                          min="0"
                          step="0.01"
                        />
                      </td>
                      <td>
                        <input
                          :id="`exams${index}`"
                          :value="score.exams"
                          type="number"
                          placeholder="Mid-Term Test/Exams"
                          max="100"
                          min="0"
                          step="0.01"
                        />
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="card-footer">
                <button
                  type="button"
                  @click="deleteScores"
                  class="btn btn-danger"
                >
                  Delete Scores
                </button>
                <button class="btn btn-success pl-2">submit</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { VueCsvImport } from "vue-csv-import";
import Loading from "vue-loading-overlay";

export default {
  components: { VueCsvImport, Loading },

  data() {
    return {
      file: "",
      report: "",
      isSession: false,
      isSubject: false,
      reports: {},
      subjects: {},
      isArm: false,
      Scores: [],
      report_id: "",
      id: "",
      note: "",
      isCa2:false,
        isCa3:false,
          isLoading: false,
      arms: {},
      report: {
        type: "mid_term",


      },


      form: new Form({
        report_id: "",
        name: [],
        student_id: [],
        subject_id: "",
        test1: [],
        test2: [],
        test3: [],
        exams: [],
        midterm: [],
        arm_id: "",
        number_of_students: 0,
      }),
    };
  },
  mounted() {
    axios
      .get("api/load_report")
      .then((result) => {
        //  console.log(result.data);
        this.reports = result.data;
      })
      .catch((err) => {});
    // this.loadSubjects();
  },
  methods: {
    resetSession() {
      // this.report_id=this.form.report_id
      this.isSession = false;
      this.form.reset();
      this.Scores = "";
    },
    loadStudents() {
      this.$Progress.start();
      this.checkReport(this.form.report_id);
      this.form
        .post(`api/load_students`)
        .then((result) => {
          this.Scores = result.data;

          console.log(this.report);
          this.$Progress.finish();
          this.isSubject = true;
        })
        .catch((err) => {
          this.$Progress.fail();
        });
    },

    deleteScores() {
      swal
        .fire({
          title: "Are you sure?",
          text: "You won't be able to revert this!",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes, delete it!",
        })
        .then((result) => {
          // Send request to the server
          if (result.value) {
            this.form
              .post("api/deleteScores")
              .then((res) => {
                swal.fire("Deleted!", res.data.Message, "success");
                this.loadStudents();
              })
              .catch(() => {
                swal.fire("Failed!", "There was something wronge.", "warning");
              });
          }
        });
    },

    checkReport(id) {
      axios.get("/api/checkreport/" + id).then((response) => {
        this.report = response.data.type;

        // console.log(this.report_id)
      });
    },
    loadSubjects() {
      //this.isSession=false;
      if (this.$gate.isAdminOrSubjectTutor()) {
        this.isSession = true;
        this.$Progress.start();
        axios
          .get("api/load_list/" + this.form.report_id + "/" + 1)
          .then((res) => {
            this.subjects = res.data;
            this.report_id = this.form.report_id;
            // console.log(this.report_id)
            this.$Progress.finish();



          });

      }


      console.log([this.isCa2,this.isCa3])
    },

    loadArms() {
      axios.get("/api/getArms/" + this.form.report_id).then((res) => {
        this.arms = res.data;
        this.isArm = true;

      this.reports.map(rep=>{
          if(rep.id===this.form.report_id){
              this.isCa2=rep.isCa2;
              this.isCa3=rep.isCa3;
              console.log(rep)

          }

      })

      });

    },

    setFile() {
      this.file = this.$refs.file.files[0];
      console.log(this.file);
    },

    importExcel() {
      this.isLoading = true;
      const formData = new FormData();
      formData.append("file", this.file);
      axios
        .post("/api/importExcel", formData)
        .then((res) => {
          swal.fire("success", "Uploaded Successfully.", "success");
          console.log(res.data);
          this.$Progress.finish();
          this.isLoading = false;
          Fire.$emit("AfterCreate");
        })
        .catch((err) => {
          this.$Progress.fail();
          swal.fire("error", "Errors uploadind." + err, "error");
          this.isLoading = false;
        });
    },

    createScore() {
      this.isLoading = true;
      this.form.student_id = [];
      this.form.test1 = [];
      this.form.test2 = [];
      this.form.test3 = [];
      this.form.exams = [];
      this.form.note = [];
      this.form.midterm = [];
      this.form.number_of_students = 0;
      var check = false;
      if (this.Scores[0].total != null) {
        check = true;
      }
      for (let index = 0; index < this.Scores.length; index++) {
        var student_id = document.querySelector(`#student_id${index}`).value;
        var test1 = document.querySelector(`#test1${index}`).value;
        var test2 = document.querySelector(`#test2${index}`).value;
        var exams = document.querySelector(`#exams${index}`).value;

        this.form.student_id.push(student_id);
        this.form.test1.push(test1);

        this.form.test2.push(test2);
        if (
          this.report != "primary-midterm" &&
          this.report != "primary-terminal"
        ) {
          var test3 = document.querySelector(`#test3${index}`).value;
          if (check) {
            var midterm = document.querySelector(`#midterm${index}`).value;
            this.form.midterm.push(midterm);
            console.log(check);
          }
          var note = document.querySelector(`#note${index}`).value;

          this.form.test3.push(test3);
          this.form.note.push(note);
        }
        this.form.exams.push(exams);

        this.form.number_of_students = ++this.form.number_of_students;
      }
      console.log("Running Herae");
      if (
        this.report === "primary-midterm" ||
        this.report === "primary-terminal"
      ) {
        //   console.log('Running Herae')
        this.form
          .post("/api/primary_scores")

          .then((res) => {
            toast.fire({
              type: "success",
              title: "marks added successfully",
            });
            this.isSubject = false;
            this.isLoading = false;
            // this.Scores=[]
          })
          .catch((err) => {
            toast.fire({
              type: "danger",
              title: "there was error uploading the file" + err,
            });
            this.isLoading = false;
          });
      } else {
        this.form
          .post("/api/scores")

          .then((res) => {
            toast.fire({
              type: "success",
              title: "marks added successfully",
            });
            this.isSubject = false;
            this.isLoading = false;
          })
          .catch((err) => {
            toast.fire({
              type: "danger",
              title: "there was error uploading the file" + err,
            });
            this.isLoading = false;
          });
      }
    },
  },
  created() {
    // this.loadSubjects();

    Fire.$on("AfterCreate", () => {
      // this.loadSubjects();
      // $this.form.reset();
    });
    //    setInterval(() => this.loadUsers(), 3000);
  },
};
</script>

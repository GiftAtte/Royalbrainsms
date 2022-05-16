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
                            <li class="breadcrumb-item">
                                <a href="#">Home</a>
                            </li>
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
                      
                    </div>

                    <div class="col-md-6">
                        <input
                            type="file"
                            ref="file"
                            @change="setFile"
                        /><button
                            @click="importExcel"
                            class="btn btn-success float-right m-2"
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
                            <div class="form-group col-md-12">
                                <select
                                    name="report_id"
                                    id="report_id"
                                    :class="{
                                        'is-invalid':
                                            form.errors.has('report_id'),
                                    }"
                                    class="form-control"
                                    v-model="form.report_id"
                                    @change="loadArms"
                                >
                                    <option value selected>
                                        Select Report class set
                                    </option>
                                    <option
                                        v-for="report in reports"
                                        :key="report.id"
                                        :value="report.id"
                                    >
                                        {{ report.title }}
                                    </option>
                                </select>
                                <has-error
                                    :form="form"
                                    field="subject"
                                ></has-error>
                            </div>

                            <div
                                class="form-group col-md-12 col-sm-12"
                                v-show="isArm"
                            >
                                <select
                                    name="arm_id"
                                    id="arm_id"
                                    :class="{
                                        'is-invalid':
                                            form.errors.has('report_id'),
                                    }"
                                    class="form-control"
                                    v-model="form.arm_id"
                                    @change="loadSubjects"
                                >
                                    <option value selected>
                                        Select Class/Level Arm
                                    </option>
                                    <option
                                        v-for="arm in arms"
                                        :key="arm.arms.id"
                                        :value="arm.arms.id"
                                    >
                                        {{ arm.arms.name }}
                                    </option>
                                </select>
                                <has-error
                                    :form="form"
                                    field="subject"
                                ></has-error>
                            </div>

                            <div
                                v-show="isSession"
                                class="form-group col-md-12"
                            >
                                <select
                                    name="subject_id"
                                    id="report_id"
                                    :class="{
                                        'is-invalid':
                                            form.errors.has('subject_id'),
                                    }"
                                    class="form-control"
                                    v-model="form.subject_id"
                                    @change="loadStudents"
                                >
                                    <option value selected>
                                        Select Subject
                                    </option>
                                    <option
                                        v-for="subject in subjects"
                                        :key="subject.id"
                                        :value="
                                            subject.subjects
                                                ? subject.subjects.id
                                                : subject.id
                                        "
                                    >
                                        {{
                                            subject.subjects
                                                ? subject.subjects.name
                                                : subject.id
                                        }}
                                    </option>
                                </select>
                                <has-error
                                    :form="form"
                                    field="subject_id"
                                ></has-error>
                            </div>
                        </div>

                        <div
                            v-show="isSubject"
                            class="card-body table-responsive col-md-12"
                        >
                            <div class="table-responsive">
                                <!-- scondary Section -->
                                <general-marks
                                    v-if="
                                        report.type === 'default-result' ||
                                        report.type === 'default-midterm'
                                    "
                                    :form="form"
                                    :report="report"
                                    :Scores="Scores"
                                />
                                <madonna-marks
                                    v-else
                                    :form="form"
                                    :report="report"
                                    :Scores="Scores"
                                />
                            </div>
                            <div class="card-footer">
                                <button
                                    type="button"
                                    @click="deleteScores"
                                    class="btn btn-danger"
                                >
                                    Delete Scores
                                </button>
                                <button class="btn btn-success pl-2">
                                    submit
                                </button>
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
import GeneralMarks from "../results/GeneralMarks.vue";
import MadonnaMarks from "../results/MadonnaMarks.vue";

export default {
    components: { VueCsvImport, Loading, GeneralMarks, MadonnaMarks },

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
            isCa2: false,
            isCa3: false,
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
            // this.checkReport(this.form.report_id);
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
            swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                // Send request to the server
                if (result.value) {
                    this.form
                        .post("api/deleteScores")
                        .then((res) => {
                            swal.fire("Deleted!", res.data.Message, "success");
                            this.loadStudents();
                        })
                        .catch(() => {
                            swal.fire(
                                "Failed!",
                                "There was something wronge.",
                                "warning"
                            );
                        });
                }
            });
        },

        // checkReport(id) {
        //   axios.get("/api/checkreport/" + id).then((response) => {
        //     this.report = response.data.type;

        //     // console.log(this.report_id)
        //   });
        // },
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

            // console.log([this.isCa2,this.isCa3])
        },

        loadArms() {
            axios.get("/api/getArms/" + this.form.report_id).then((res) => {
                this.arms = res.data;
                this.isArm = true;

                this.reports.map((rep) => {
                    if (rep.id === this.form.report_id) {
                        this.isCa2 = rep.isCa2;
                        this.isCa3 = rep.isCa3;
                        this.report = rep;
                        console.log(rep);
                    }
                });
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
                var student_id = document.querySelector(
                    `#student_id${index}`
                ).value;
                var test1 = document.querySelector(`#test1${index}`).value;
                var test2 = this.report.isCa2
                    ? document.querySelector(`#test2${index}`).value
                    : "";
                var exams = document.querySelector(`#exams${index}`).value;

                this.form.student_id.push(student_id);
                this.form.test1.push(test1);

                this.form.test2.push(test2);

                if (
                    this.report.type != "primary-midterm" &&
                    this.report.type != "primary-terminal"
                ) {
                    var test3 = this.report.isC3
                        ? document.querySelector(`#test3${index}`).value
                        : "";
                    if (check) {
                        var midterm = document.querySelector(
                            `#midterm${index}`
                        ).value;
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
                this.report.type === "primary-midterm" ||
                this.report.type === "primary-terminal"
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

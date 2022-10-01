<template>
    <div>
        <div class="content-header">
            <div class="container-fluid navy">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h2 class="m-0 text-dark">Weekly Mark Update</h2>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="#">Home</a>
                            </li>
                            <li class="breadcrumb-item active">
                                Weekly Activity
                            </li>
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
                <div class="card-header card-navy card-outline"></div>
                <form @submit.prevent="createScore">
                    <div class="card-body content">
                        <loading
                            :active.sync="isLoading"
                            color="green"
                            :is-full-page="true"
                        >
                        </loading>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select
                                        name="week_id"
                                        id="week_id"
                                        :class="{
                                            'is-invalid':
                                                form.errors.has('week_id'),
                                        }"
                                        class="form-control"
                                        v-model="form.week_id"
                                    >
                                        <option value selected>
                                            Select Week
                                        </option>
                                        <option
                                            v-for="option in weekOptions"
                                            :key="option.id"
                                            :value="option.id"
                                        >
                                            {{ option.week }}
                                        </option>
                                    </select>
                                    <has-error
                                        :form="form"
                                        field="week_id"
                                    ></has-error>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
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
                            </div>

                            <div class="col-md-3">
                                <div class="form-group" v-show="isArm">
                                    <select
                                        name="arm_id"
                                        id="arm_id"
                                        :class="{
                                            'is-invalid':
                                                form.errors.has('report_id'),
                                        }"
                                        class="form-control"
                                        v-model="form.arm_id"
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
                            </div>

                            <div class="col-md-3" v-show="isArm">
                                <div v-show="isSession" class="form-group">
                                    <select
                                        name="subject_id"
                                        id="subject_id"
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
                        </div>

                        <div
                            v-show="isSubject"
                            class="card-body table-responsive col-md-12"
                        >
                            <div class="table-responsive">
                                <!-- scondary Section -->

                                <table
                                    class="table table-hover table-sm"
                                    v-if="isSubject"
                                >
                                    <thead>
                                        <tr>
                                            <th>S/N</th>
                                            <th>Name</th>
                                            <th>CLASS WORK[10]</th>
                                            <th>TEST[10]</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="(score, index) in Scores"
                                            :key="index"
                                        >
                                            <td>{{ index + 1 }}</td>
                                            <td>
                                                {{ score.name }}
                                                <input
                                                    @input="update"
                                                    type="hidden"
                                                    :id="`student_id${index}`"
                                                    :value="score.student_id"
                                                />
                                                <input
                                                    class="form-control"
                                                    @input="update"
                                                    type="hidden"
                                                    :id="`arm_id${index}`"
                                                    :value="score.arm_id"
                                                />
                                            </td>
                                            <td>
                                                <input
                                                    class="form-control"
                                                    @input="update"
                                                    :id="`test1${index}`"
                                                    :value="score.class_work"
                                                    type="number"
                                                    placeholder="CA"
                                                    :max="10"
                                                    min="0"
                                                    step="0.01"
                                                />
                                            </td>
                                            <td>
                                                <input
                                                    class="form-control"
                                                    @input="update"
                                                    :id="`test2${index}`"
                                                    :value="score.test"
                                                    type="number"
                                                    placeholder="TEST"
                                                    :max="10"
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
import SelectInput from "../../utils/SelectInput.vue";
import { weekOptions } from "../../utils/weekOptions";

export default {
    components: { VueCsvImport, Loading, SelectInput },

    data() {
        return {
            weekOptions: weekOptions,
            file: "",
            report: "",
            isSession: false,
            isSubject: false,
            reports: [],
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
            report: "",

            form: new Form({
                report_id: "",
                subject_id: "",
                week_id: "",
                arm_id: "",
                scores: [],
            }),
        };
    },
    mounted() {
        axios
            .get("/api/load_report")
            .then((result) => {
                //  console.log(result.data);
                this.reports = result.data;
            })
            .catch((err) => {});
        // this.loadSubjects();
    },
    methods: {
        update(event) {
            this.$emit("input", event.target.value);
        },
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
                .post(`/api/loadWeeklyAssesment`)
                .then((result) => {
                    this.Scores = result.data;
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
                        .post("/api/deleteWeeklyActivity")
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

        loadSubjects() {
            //this.isSession=false;
            if (this.$gate.isAdminOrSubjectTutor()) {
                this.isSession = true;
                this.$Progress.start();
                axios
                    .get("/api/load_list/" + this.form.report_id + "/" + 1)
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
            this.subjects = [];
            this.Scores = [];
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
                    this.loadSubjects();
                });
            });
        },

        createScore() {
            this.isLoading = true;
            this.form.scores = [];
            var check = false;
            for (let index = 0; index < this.Scores.length; index++) {
                var student_id = document.querySelector(
                    `#student_id${index}`
                ).value;
                var class_work = document.querySelector(`#test1${index}`).value;
                var test = document.querySelector(`#test2${index}`).value;
                this.form.scores.push({
                    student_id,
                    test,
                    class_work,
                });
            }
            this.form
                .post("/api/weeklyScores")

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

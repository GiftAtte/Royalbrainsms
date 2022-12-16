<template>
    <div>
        <div class="content-header">
            <div class="container-fluid navy">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h2 class="m-0 text-dark">Attendance</h2>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="#">Home</a>
                            </li>
                            <li class="breadcrumb-item active">Attendance</li>
                        </ol>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <div class="content col-md-12">
            <div class="card">
                <div class="card-header">
                    <input
                        class="btn btn-primary float-right"
                        ref="file"
                        id="file"
                        type="file"
                        @change="importExcel"
                        placeholder="import excel"
                    />
                </div>
                <form @submit.prevent="createAttendance">
                    <div class="card-body content">
                        <loading
                            :active.sync="isLoading"
                            color="green"
                            :is-full-page="true"
                        >
                        </loading>
                        <div class="row">
                            <div
                                v-show="!isSession"
                                class="form-group col-md-12"
                            >
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

                            <div class="col-md-12 col-sm-12 row" v-show="isArm">
                                <div class="form-group col-md-6 col-sm-12">
                                    <label>Class Arm</label>
                                    <select
                                        name="arm_id"
                                        id="arm_id"
                                        :class="{
                                            'is-invalid':
                                                form.errors.has('report_id'),
                                        }"
                                        class="form-control"
                                        v-model="form.arm_id"
                                        @change="loadAttendance"
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
                                <div class="form-group col-md-6 col-sm-12">
                                    <label>No of school days</label>
                                    <input
                                        class="form-control"
                                        v-model="form.school_days"
                                    />
                                </div>
                            </div>
                        </div>

                        <div
                            v-show="isSubject"
                            class="card-body table-responsive col-md-12 py-3 table-striped"
                        >
                            <div class="table-responsive">
                                <!-- scondary Section -->
                                <table class="table table-hover table-sm">
                                    <thead>
                                        <tr>
                                            <th>S/N</th>
                                            <th>Students Name</th>
                                            <th>Days Present</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="(
                                                student, index
                                            ) in Attendance"
                                            :key="index"
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
                                                    name="days_present"
                                                    :id="`days_present${index}`"
                                                    class="form-control"
                                                    :value="
                                                        student.days_present
                                                            ? student.days_present
                                                            : ''
                                                    "
                                                />
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-success">submit</button>
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
            comments: "",
            report: "",
            isSession: false,
            isSubject: false,
            reports: {},
            Subdomains: {},
            domains: {},
            isArm: false,
            Attendance: "",
            report_id: "",
            id: "",
            note: "",
            arms: {},
            report: {
                type: "mid_term",
            },
            isLoading: false,
            form: new Form({
                report_id: "",
                name: [],
                arm_id: "",
                students: [],
                school_days: "",
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

            this.form
                .post(`/api/load_comments`)
                .then((result) => {
                    this.Scores = result.data;

                    console.log(this.Scores);
                    this.$Progress.finish();
                    this.isSubject = true;
                })
                .catch((err) => {
                    this.$Progress.fail();
                });
        },

        loadArms() {
            axios.get("/api/getArms/" + this.form.report_id).then((res) => {
                this.arms = res.data;
                this.isArm = true;
            });
        },

        setFile() {
            this.file = this.$refs.file.files[0];
            console.log(this.file);
        },

        importExcel() {
            this.isLoading = true;
            const file = this.$refs.file.files[0];
            const formData = new FormData();
            formData.append("file", file);
            axios
                .post("/api/attendance/importExcel", formData)
                .then((res) => {
                    toast.fire({
                        type: "success",
                        title: "Scores successfully uploaded",
                    });

                    this.$Progress.finish();
                    this.isLoading = false;
                    Fire.$emit("AfterCreate");
                })
                .catch((err) => {
                    this.$Progress.fail();
                    toast.fire({
                        type: "failure",
                        title: "there was error uploading the file" + err,
                    });
                    this.isLoading = false;
                });
        },

        createAttendance() {
            this.isLoading = true;
            this.form.student_id = [];

            this.form.number_of_students = 0;
            for (let index = 0; index < this.Attendance.length; index++) {
                var student_id = document.querySelector(
                    `#student_id${index}`
                ).value;
                var days_present = document.querySelector(
                    `#days_present${index}`
                ).value;

                this.form.students.push({ student_id, days_present });
                this.form.number_of_students = ++this.form.number_of_students;
            }
            //  console.log(this.form)
            this.form
                .post("/api/attendance")

                .then((res) => {
                    toast.fire({
                        type: "success",
                        title: "Attendance added successfully",
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
        loadAttendance() {
            axios
                .get(
                    `/api/attendance/${this.form.report_id}/${this.form.arm_id}`
                )
                .then((res) => {
                    this.Attendance = res.data;
                    this.isSubject = true;
                    this.form.school_days = res.data[0].school_days
                        ? res.data[0].school_days
                        : "";
                });
        },
    },
    created() {
        Fire.$on("AfterCreate", () => {
            // this.loadSubjects();
            // $this.form.reset();
        });
        //    setInterval(() => this.loadUsers(), 3000);
    },
};
</script>

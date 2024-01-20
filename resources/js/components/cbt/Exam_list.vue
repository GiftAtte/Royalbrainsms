<template>
    <div>
        <div class="content-header">
            <div class="container-fluid navy">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">
                            Examination Scheduling List
                        </h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="#">Home</a>
                            </li>
                            <li class="breadcrumb-item active">Exam List</li>
                        </ol>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>

        <div class="content" v-if="$gate.isAdminOrTutor()">
            <div class="col-12">
                <div class="card card-navy card-outline">
                    <div class="card-header">
                        <div class="row float-right">
                            <div class="card-tools">
                                <button
                                    class="btn btn-primary btn-sm float-right m-2"
                                    @click="newModal"
                                >
                                    <i class="fas fa-plus-circle mx-1"></i>Add
                                    New
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <th>E/ID</th>
                                    <th>Title</th>
                                    <th>Examiner</th>
                                    <th>Venue</th>
                                    <th>Exam Date</th>
                                    <th class="text-danger">Publish</th>
                                    <th>Action</th>
                                </tr>

                                <tr v-for="exam in exams.data" :key="exam.id">
                                    <td>{{ exam.id }}</td>
                                    <td>{{ exam.title | upText }}</td>
                                    <td>
                                        {{
                                            exam.examiner
                                                ? exam.examiner.surname
                                                : ""
                                        }}&nbsp;
                                        {{
                                            exam.examiner
                                                ? exam.examiner.first_name
                                                : ""
                                        }}
                                    </td>
                                    <td>{{ exam.venue ? exam.venue : "" }}</td>
                                    <td>{{ exam.start_date | myDate }}</td>
                                    <td>
                                        <toggle-button
                                            @change="publishExam(exam.id)"
                                            :label="true"
                                            :labels="{
                                                checked: 'ON',
                                                unchecked: 'OFF',
                                            }"
                                            :height="20"
                                            :font-size="14"
                                            :value="exam.isPublished"
                                            :color="'navy'"
                                            :name="'activated'"
                                            class="mx-2"
                                        />
                                    </td>
                                    <td class="action">
                                        <a href="#" @click="editModal(exam)">
                                            <i class="fa fa-edit blue"></i>
                                        </a>
                                        /
                                        <a
                                            href="#"
                                            @click="deleteExam(exam.id)"
                                            class="pl-2"
                                        >
                                            <i class="fa fa-trash red"></i>
                                        </a>
                                        <router-link
                                            :to="`/questions/${exam.id}`"
                                            tag="a"
                                            class="pl-2"
                                        >
                                            Add Questions
                                        </router-link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <pagination
                            :data="exams"
                            @pagination-change-page="getResults"
                        ></pagination>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>

        <div v-if="!$gate.isAdminOrTutor()">
            <not-found></not-found>
        </div>
        <!-- Arms Modal -->
        <div
            class="modal fade modal-primary"
            id="addNew"
            tabindex="-1"
            role="dialog"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5
                            class="modal-title"
                            v-show="!editmode"
                            id="addNewLabel"
                        >
                            Add New
                        </h5>
                        <h5
                            class="modal-title"
                            v-show="editmode"
                            id="addNewLabel"
                        >
                            Update exam's Info
                        </h5>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form
                        @submit.prevent="editmode ? updateExam() : createExam()"
                    >
                        <div class="modal-body">
                            <div class="p-1 row">
                                <div class="form-group col-md-6">
                                    <input
                                        type=""
                                        v-model="form.title"
                                        class="form-control"
                                        id="title"
                                        placeholder="title"
                                        :class="{
                                            'is-invalid':
                                                form.errors.has('title'),
                                        }"
                                    />
                                    <has-error
                                        :form="form"
                                        field="title"
                                    ></has-error>
                                </div>

                                <div class="form-group col-md-6">
                                    <select
                                        name="subject_id"
                                        id="subject_id"
                                        :class="{
                                            'is-invalid':
                                                form.errors.has('subject_id'),
                                        }"
                                        class="form-control"
                                        v-model="form.subject_id"
                                    >
                                        <option value selected>
                                            Select subject to Add to list
                                        </option>
                                        <option
                                            v-for="subject in subjects"
                                            :key="subject.id"
                                            :value="subject.id"
                                        >
                                            {{ subject.name }}
                                        </option>
                                    </select>
                                    <has-error
                                        :form="form"
                                        field="level_id"
                                    ></has-error>
                                </div>
                                <div class="form-group col-md-6">
                                    <select
                                        name="subject_id"
                                        id="subject_id"
                                        :class="{
                                            'is-invalid':
                                                form.errors.has('level_id'),
                                        }"
                                        class="form-control"
                                        v-model="form.level_id"
                                        @change="loadArms"
                                    >
                                        <option value selected>
                                            Select Level
                                        </option>
                                        <option
                                            v-for="level in levels"
                                            :key="level.id"
                                            :value="level.id"
                                        >
                                            {{ level.level_name }}
                                        </option>
                                    </select>

                                    <has-error
                                        :form="form"
                                        field="level_id"
                                    ></has-error>
                                </div>
                                <div class="form-group col-md-6">
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
                                        field="arm_id"
                                    ></has-error>
                                </div>

                                <div class="form-group col-md-6">
                                    <datetime
                                        input-class="form-control"
                                        placeholder="Exam/Test Start Date"
                                        type="datetime"
                                        :phrases="{
                                            ok: 'Continue',
                                            cancel: 'Exit',
                                        }"
                                        format="yyyy-MM-dd HH:mm:ss"
                                        use12-hour
                                        auto
                                        v-model="form.start_date"
                                        :class="{
                                            'is-invalid':
                                                form.errors.has('start_date'),
                                        }"
                                    >
                                    </datetime>
                                    <has-error
                                        :form="form"
                                        field="start_date"
                                    ></has-error>
                                </div>
                                <div class="form-group col-md-6">
                                    <datetime
                                        input-class="form-control"
                                        placeholder="Exam/Test End Date"
                                        type="datetime"
                                        format="yyyy-MM-dd HH:mm:ss"
                                        :phrases="{
                                            ok: 'Continue',
                                            cancel: 'Exit',
                                        }"
                                        use12-hour
                                        auto
                                        v-model="form.end_date"
                                        :class="{
                                            'is-invalid':
                                                form.errors.has('end_date'),
                                        }"
                                    >
                                    </datetime>
                                    <has-error
                                        :form="form"
                                        field="end_date"
                                    ></has-error>
                                </div>

                                <div class="form-group col-md-6">
                                    <input
                                        class="form-control"
                                        type="number"
                                        placeholder="Exam duration "
                                        v-model="form.duration"
                                        :class="{
                                            'is-invalid':
                                                form.errors.has('duration'),
                                        }"
                                    />
                                </div>

                                <div class="form-group col-md-6">
                                    <input
                                        type="text"
                                        v-model="form.venue"
                                        name="venue"
                                        class="form-control"
                                        id="title"
                                        placeholder="Exam venue"
                                        :class="{
                                            'is-invalid':
                                                form.errors.has('venue'),
                                        }"
                                    />
                                    <has-error
                                        :form="form"
                                        field="venue"
                                    ></has-error>
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Instruction</label>
                                    <textarea
                                        rows="3"
                                        class="form-control"
                                        v-model="form.comment"
                                        placeholder="Any Instruction"
                                    >
                                    </textarea>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button
                                type="button"
                                class="btn btn-danger"
                                data-dismiss="modal"
                            >
                                Close
                            </button>
                            <button
                                v-show="editmode"
                                type="submit"
                                class="btn btn-success"
                            >
                                Update
                            </button>
                            <button
                                v-show="!editmode"
                                type="submit"
                                class="btn btn-primary"
                            >
                                Create
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Datepicker from "vuejs-datepicker";
import moment from "moment";
import VueTimepicker from "vue2-timepicker/dist/VueTimepicker.umd.min.js";
import { mapState } from "vuex";
export default {
    components: {
        VueTimepicker,
        Datepicker,
    },
    data() {
        return {
            editmode: false,
            instruction: "",
            exams: {},
            subjects: {},
            levels: {},
            arms: {},
            form: new Form({
                id: "",
                title: "",
                subject_id: "",
                level_id: "",
                arm_id: "",
                venue: "",
                start_time: "",
                end_time: "",
                start_date: "",
                end_date: "",
                duration: "",
                comment: "",
            }),
        };
    },
    mounted() {
        axios.get("/api/get_levels").then((res) => {
            this.levels = res.data;
        });
    },
    methods: {
        customFormatter(date) {
            return moment(date).format("YYYY-MM-DD");
        },
        getResults(page = 1) {
            axios.get("/api/exams?page=" + page).then((response) => {
                this.exams = response.data;
            });
        },
        updateExam() {
            this.$Progress.start();
            // console.log('Editing data');
            this.form
                .put("api/exams")
                .then(() => {
                    // success
                    $("#addNew").modal("hide");
                    swal.fire(
                        "Updated!",
                        "Information has been updated.",
                        "success"
                    );
                    this.$Progress.finish();
                    Fire.$emit("AfterCreate");
                })
                .catch(() => {
                    this.$Progress.fail();
                });
        },

        editModal(exam) {
            this.editmode = true;
            this.form.reset();
            $("#addNew").modal("show");
            this.form.fill(exam);
        },
        newModal() {
            this.editmode = false;
            this.form.reset();
            $("#addNew").modal("show");
        },

        createExam() {
            this.$Progress.start();
            this.form
                .post("/api/exams")
                .then(() => {
                    Fire.$emit("AfterCreate");
                    $("#addNew").modal("hide");

                    toast.fire({
                        type: "success",
                        title: "Exam Created in successfully",
                    });
                    this.$Progress.finish();
                    $("#addNew").modal("hide");
                    Fire.$emit("AfterCreate");
                })
                .catch(() => {});
        },
        loadArms() {
            axios
                .get("/api/get_exam_arms/" + this.form.level_id)
                .then((res) => {
                    this.arms = res.data;
                });
        },
        deleteExam(id) {
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
                        .delete("api/exams/" + id)
                        .then(() => {
                            swal.fire(
                                "Deleted!",
                                "school has been deleted.",
                                "success"
                            );
                            Fire.$emit("AfterCreate");
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

        loadExams() {
            if (this.$gate.isAdminOrTutor()) {
                axios.get("api/exams").then((res) => (this.exams = res.data));
            }
        },
        loadSubjects() {
            if (this.$gate.isAdminOrTutor()) {
                axios.get("api/load_list").then((res) => {
                    this.subjects = res.data;
                });
            }
        },
        publishExam(id) {
            axios.put("/api/publishCBT/" + id).then((res) => {
                Fire.$emit("afterCreated");
            });
        },
    },
    created() {
        Fire.$on("searching", () => {
            let query = this.$parent.search;
            axios
                .get("api/findSchool?q=" + query)
                .then((data) => {
                    this.levels = data.data;
                })
                .catch(() => {});
        });
        this.loadExams();
        this.loadSubjects();
        Fire.$on("AfterCreate", () => {
            this.loadExams();
        });
        //    setInterval(() => this.loadUsers(), 3000);
    },
};
</script>

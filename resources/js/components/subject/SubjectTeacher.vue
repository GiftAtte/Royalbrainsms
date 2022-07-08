<template>
    <div>
        <div class="content-header">
            <div class="container-fluid navy">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h2 class="m-0 text-dark">Teacher's Subjects</h2>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="#">Home</a>
                            </li>
                            <li class="breadcrumb-item active">Subject List</li>
                        </ol>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>

        <div class="col-md-12" v-if="$gate.isAdminOrSubjectTutor()">
            <div class="col-md-12">
                <div class="card card-navy card-outline">
                    <div class="card-header">
                        <div class="row col-md-12">
                            <div
                                v-show="$gate.isAdmin()"
                                class="form-group col-md-6"
                            >
                                <select
                                    name="class_id"
                                    id="class_id"
                                    :class="{
                                        'is-invalid':
                                            form.errors.has('class_id'),
                                    }"
                                    class="form-control"
                                    v-model="form.staff_id"
                                    @change="loadSubjects_list"
                                >
                                    <option value selected>
                                        Select Class Teacher
                                    </option>
                                    <option
                                        v-for="staff in Staff"
                                        :key="staff.id"
                                        :value="staff.id"
                                    >
                                        {{ staff.name }}
                                    </option>
                                </select>
                                <has-error
                                    :form="form"
                                    field="staff_id"
                                ></has-error>
                            </div>

                            <div
                                v-show="$gate.isAdminOrTutor()"
                                class="form-group col-md-6"
                            >
                                <select
                                    name="level_id"
                                    id="level_id"
                                    :class="{
                                        'is-invalid':
                                            form.errors.has('level_id'),
                                    }"
                                    class="form-control"
                                    v-model="form.level_id"
                                    @change="loadSubjects"
                                >
                                    <option value selected>Select Level</option>
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
                                    field="level"
                                ></has-error>
                            </div>

                            <div class="form-group col-md-8">
                                <label>Subjects</label>
                                <multiselect
                                    v-model="form.subjects"
                                    :items="listOptions"
                                    item-key="id"
                                    item-label="label"
                                    title="Select Subjects"
                                    menu-position="float"
                                >
                                    <template v-slot:selected-items-footer>
                                        <button
                                            class="btn btn-success float-right m-2"
                                            @click="AddSubject"
                                        >
                                            Save
                                        </button>
                                    </template>
                                </multiselect>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <td>
                                        <input
                                            type="checkbox"
                                            @click="selectAll"
                                            v-model="allSelected"
                                            :checked="isSelectAll"
                                        />
                                    </td>
                                    <th>S/ID</th>
                                    <th>SUBJECT</th>
                                    <th>CODE</th>
                                    <th>LEVELS</th>
                                    <th>Modify</th>
                                </tr>

                                <tr
                                    v-for="subject in subjects_list"
                                    :key="subject.id"
                                >
                                    <td>
                                        <input
                                            :id="`subject${subject.id}`"
                                            type="checkbox"
                                            @click="select(subject.id)"
                                            :checked="isChecked"
                                        />
                                    </td>
                                    <td>{{ subject.subject_id }}</td>
                                    <td>
                                        {{
                                            subject.subjects
                                                ? subject.subjects.name
                                                : ""
                                        }}
                                    </td>

                                    <td>
                                        {{
                                            subject.subjects
                                                ? subject.subjects.code
                                                : ""
                                        }}
                                    </td>
                                    <td>
                                        {{
                                            subject.levels
                                                ? subject.levels.level_name
                                                : ""
                                        }}
                                    </td>

                                    <td>
                                        <a
                                            href="#"
                                            @click="
                                                deleteSubject_list(subject.id)
                                            "
                                            class="pl-2"
                                        >
                                            <i class="fa fa-trash red"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button
                            class="btn btn-danger"
                            @click="deleteSubject_list"
                            v-show="subjectIds.length"
                        >
                            Delete
                        </button>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>

        <div v-if="!$gate.isAdminOrTutor()">
            <not-found></not-found>
        </div>
    </div>
</template>

<script>
//import VueMultiselectComponent from "vue-multiselect-component";
export default {
    data() {
        return {
            editmode: false,
            addToList: false,
            subjects: [],
            levels: {},
            subjects_list: "",
            selected_file: "",
            counter: 0,
            Staff: "",
            listOptions: [],
            selected: [],
            subjectIds: [],
            allSelected: false,
            isChecked: false,
            isSubjectId: false,
            isSelectAll: false,
            deleteForm: new Form({
                subjectIds: [],
            }),

            form: new Form({
                id: "",
                level_id: "",
                subject_id: "",
                staff_id: "",
                subjects: [],
            }),
        };
    },

    methods: {
        AddSubject() {
            if (this.form.type === "") {
                alert("please select your subject tpye");
            } else {
                this.$Progress.start();
                //console.log(this.form.subjects)
                this.form
                    .post("api/teacher_subjects")
                    .then(() => {
                        toast.fire({
                            type: "success",
                            title: "subject added successfully",
                        });
                        this.$Progress.finish();

                        Fire.$emit("AfterCreate");
                        this.form.reset();
                    })
                    .catch(() => {});
            }
        },
        deleteSubject_list(id = null) {
            if (this.$gate.isAdmin()) {
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
                        if (id) this.subjectIds.push(id);
                        this.deleteForm.subjectIds = this.subjectIds;
                        this.deleteForm
                            .delete("api/teacher_subjects")
                            .then(() => {
                                swal.fire(
                                    "Deleted!",
                                    "subject has been removed.",
                                    "success"
                                );
                                Fire.$emit("AfterCreate");
                                this.isChecked = false;
                                this.isSelectAll = false;
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
            } else {
                swal.fire({
                    title: "Are you sure?",
                    text: "Please contact admin to remove this subject from your list!",
                    type: "info",
                });
            }
        },
        loadSubjects() {
            if (this.$gate.isAdminOrSubjectTutor()) {
                axios
                    .get("/api/load_subjects/" + this.form.level_id)
                    .then((res) => {
                        this.subjects = res.data;
                        console.log(res.data);
                        res.data.forEach((subject) => {
                            this.listOptions.push({
                                id: subject.subjects.id,
                                label: subject.subjects.name,
                                value: subject.subjects.id,
                            });
                        });
                    });
            }
        },
        loadSubjects_list() {
            if (this.$gate.isAdmin()) {
                // console.log(this.$gate.isAdmin());

                axios
                    .get(
                        `/api/teacher_subjects/${
                            this.form.staff_id ? this.form.staff_id : ""
                        }`
                    )
                    .then((res) => {
                        this.subjects_list = res.data;
                    });
            } else {
                axios
                    .get("/api/teacher_subjects")
                    .then((res) => {
                        this.subjects_list = res.data;
                        //console.log(res);
                    })
                    .catch((err) => {});
            }
        },

        selectAll() {
            this.subjectIds = [];
            if (this.isChecked) {
                this.isChecked = false;

                return this.checkSubjectId();
            }
            const subjects = this.subjects_list;
            this.isChecked = true;
            if (!this.allSelected) {
                for (let index = 0; index < subjects.length; index++) {
                    this.subjectIds.push(subjects[index].id);
                    this.checkSubjectId();
                    this.allSelected = true;
                }
                //  console.log(this.studentIds)
                this.checkSubjectId();
            }
        },
        select(id) {
            if (this.allSelected) {
                const index = this.subjectIds.indexOf(id);
                if (index > -1) {
                    this.subjectIds.splice(index, 1);
                    this.checkSubjectId();
                } else {
                    this.subjectIds.push(id);
                    this.checkSubjectId();
                }
            } else {
                const index = this.subjectIds.indexOf(id);
                if (index > -1) {
                    this.subjectIds.splice(index, 1);
                    this.checkSubjectId();
                } else {
                    this.subjectIds.push(id);
                    this.checkSubjectId();
                }
            }

            this.checkSubjectId();
            console.log(this.subjectIds);
        },
        checkSubjectId() {
            //let studentLength = this.studentIds.length;
            if (this.subjectIds.length > 0) {
                this.isSubjectId = true;
            } else {
                this.isSubjectId = false;
            }

            if (this.subjectIds.length === this.subjects_list.length) {
                this.isSelectAll = true;
            } else {
                this.isSelectAll = false;
            }
            console.log(this.subjectIds);
        },
    },

    created() {
        this.loadSubjects_list();

        Fire.$on("AfterCreate", () => {
            //  this.loadSubjects();
            this.loadSubjects_list();
        });
        //    setInterval(() => this.loadUsers(), 3000);
        axios.get("/api/get_levels").then((res) => {
            this.levels = res.data;
        });

        axios
            .get("/api/employees")
            .then((res) => {
                this.Staff = res.data;
            })
            .catch((err) => {});

        // this.loadLevels();
    },
};
</script>

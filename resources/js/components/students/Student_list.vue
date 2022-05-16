<template>
    <div>
        <div class="content-header">
            <div class="container-fluid navy">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Student</h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="#">Home</a>
                            </li>
                            <li class="breadcrumb-item active">Student List</li>
                        </ol>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>

        <div class="content col-md-12" v-if="$gate.isTutorOrAdmin()">
            <div class="card card-navy card-outline ">
                <div class="card-header">
                    <div class="row  ">
                        <div class="col-md-2">
                            <select
                                name="class_id"
                                id="level"
                                :class="{
                                    'is-invalid': form.errors.has('class_id')
                                }"
                                class="form-control"
                                v-model="level_id"
                                @change="checkArm"
                            >
                                <option value selected
                                    >Select Class/Level</option
                                >
                                <option
                                    v-for="level in levels"
                                    :key="level.id"
                                    :value="level.id"
                                    >{{ level.level_name }}</option
                                >
                            </select>
                            &nbsp; &nbsp;
                        </div>

                        <div v-show="hasArm" class=" col-md-2">
                            <select
                                name="arm_id"
                                id="arm_id"
                                :class="{
                                    'is-invalid': form.errors.has('arm_id')
                                }"
                                class="form-control"
                                v-model="arm_id"
                                @change="loadStudents"
                            >
                                <option value selected>Select Class Arm</option>
                                <option
                                    v-for="arm in arms"
                                    :key="arm.id"
                                    :value="arm.id"
                                    >{{ arm.name }}</option
                                >
                            </select>
                            <has-error :form="form" field="arm_id"></has-error>
                        </div>
                        <div v-show="hasArm">
                            <export-excel
                                class="btn btn-primary"
                                :data="student_login"
                            >
                                Download student Logins
                                <i class="fa fa-download"></i>
                            </export-excel>
                        </div>
                    </div>
                    <div class="row float-right">
                        <div class="card-title ">
                            <router-link to="/import_students" class="p-5">
                                Import student list (cvs)</router-link
                            >
                        </div>

                        <div class="card-tools">
                            <button class="btn btn-success " @click="newModal">
                                Add New <i class="fas fa-user-plus fa-fw"></i>
                            </button>
                            <button
                                v-show="$gate.isAdmin()"
                                class="btn btn-danger "
                                @click="updatePassword"
                            >
                                Update Password
                                <i class="fa fa-pencil fa-fw"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body  ">
                    <div class="table-responsive">
                    <table class="table table-hover ">
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
                                <th colspan="2">Student Names</th>

                                <th>Course</th>
                                <th>Arm</th>
                                <th>Gender</th>
                                <th>Photo</th>
                                <th>Modify</th>
                                <th>Status</th>
                            </tr>

                            <tr
                                v-for="student in students.data"
                                :key="student.id"
                            >
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
                                        :to="
                                            `student_profile/${student.id}/student`
                                        "
                                        tag="a"
                                        exact
                                    >
                                        {{ student.surname }}, &nbsp;{{
                                            student.first_name
                                        }}
                                        &nbsp;{{
                                            student.middle_name
                                                ? student.middle_name
                                                : ""
                                        }}
                                    </router-link>
                                </td>
                                <td>
                                    {{
                                        student.levels
                                            ? student.levels.level_name
                                            : ""
                                    }}
                                </td>
                                <td>
                                    {{ student.arm ? student.arm.name : "" }}
                                </td>
                                <td>
                                    {{ student.gender ? student.gender : "" }}
                                </td>
                                <td>
                                    <img
                                        :src="
                                            '/img/profile/stud' +
                                                student.id +
                                                '.png'
                                        "
                                        alt="no pics"
                                        width="35"
                                        height="35"
                                        class="  img-circle "
                                    />
                                </td>

                                <td>
                                    <a href="#" @click="editModal(student)">
                                        <i class="fa fa-edit blue"></i>
                                    </a>
                                    /
                                    <a
                                        href="#"
                                        @click="deleteStudent(student.id)"
                                    >
                                        <i class="fa fa-trash red"></i>
                                    </a>
                                </td>
                                <td>
                                    <toggle-button
                                        @change="setActivation(student.userId)"
                                        :label="true"
                                        :labels="{
                                            checked: 'ON',
                                            unchecked: 'OFF'
                                        }"
                                        :height="20"
                                        :font-size="14"
                                        :value="student.isActive"
                                        :color="'green'"
                                        :name="'activated'"
                                        class="pl-2"
                                    />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
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
                <button
                    v-show="isStudentId"
                    class="btn btn-md btn-danger"
                    @click="deleteStudent"
                >
                    <i class="fa fa-trash"></i> Delete
                </button>
            </div>
        </div>
        <!-- /.card -->

        <div v-if="!$gate.isAdminOrTutor()">
            <not-found></not-found>
        </div>

        <!-- Modal -->
        <div
            class="modal fade"
            id="addNew"
            tabindex="-1"
            role="dialog"
            aria-labelledby="addNewLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
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
                            Update student's Info
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
                        @submit.prevent="
                            editmode ? updateStudent() : createStudent()
                        "
                    >
                        <div class="modal-body">
                            <div class="form-group">
                                <input
                                    v-model="form.surname"
                                    type="text"
                                    name="surname"
                                    placeholder="Surname"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': form.errors.has('surname')
                                    }"
                                />
                                <has-error
                                    :form="form"
                                    field="surname"
                                ></has-error>
                            </div>
                            <div class="form-group">
                                <input
                                    v-model="form.first_name"
                                    type="text"
                                    name="first_name"
                                    placeholder=" First Name"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': form.errors.has(
                                            'first_name'
                                        )
                                    }"
                                />
                                <has-error
                                    :form="form"
                                    field="first_name"
                                ></has-error>
                            </div>

                            <div class="form-group">
                                <input
                                    v-model="form.middle_name"
                                    type="text"
                                    name="middle_name"
                                    placeholder="Middle Name (optional)"
                                    class="form-control"
                                />
                            </div>
                            <div class="form-group">
                                <input
                                    v-model="form.phone"
                                    type="text"
                                    name="phone"
                                    placeholder="Phone Number (optional)"
                                    class="form-control"
                                />
                            </div>

                            <div class="form-group">
                                <input
                                    v-model="form.dob"
                                    type="date"
                                    name="dob"
                                    placeholder="Dob"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': form.errors.has('dob')
                                    }"
                                />
                                <has-error :form="form" field="dob"></has-error>
                            </div>

                            <div class="form-group">
                                <select
                                    name="type"
                                    v-model="form.gender"
                                    id="class_id"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': form.errors.has('gender')
                                    }"
                                >
                                    <option value="">Select sex</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                                <has-error
                                    :form="form"
                                    field="gender"
                                ></has-error>
                            </div>

                            <div class="form-group">
                                <select
                                    name="class_id"
                                    id="level"
                                    :class="{
                                        'is-invalid': form.errors.has(
                                            'class_id'
                                        )
                                    }"
                                    class="form-control"
                                    v-model="form.class_id"
                                    @change="checkArm"
                                >
                                    <option value selected
                                        >Select Class/Level</option
                                    >
                                    <option
                                        v-for="level in levels"
                                        :key="level.id"
                                        :value="level.id"
                                        >{{ level.level_name }}</option
                                    >
                                </select>
                                <has-error
                                    :form="form"
                                    field="class_id"
                                ></has-error>
                            </div>

                            <div v-show="hasArm" class="form-group">
                                <select
                                    name="arm_id"
                                    id="arm_id"
                                    :class="{
                                        'is-invalid': form.errors.has('arm_id')
                                    }"
                                    class="form-control"
                                    v-model="form.arm_id"
                                >
                                    <option value selected
                                        >Select Class Arm</option
                                    >
                                    <option
                                        v-for="arm in arms"
                                        :key="arm.id"
                                        :value="arm.id"
                                        >{{ arm.name }}</option
                                    >
                                </select>
                                <has-error
                                    :form="form"
                                    field="arm_id"
                                ></has-error>    
                            </div>
                            <div class="form-group">
                                    <label>New Student</label>
                                    <input type="checkbox"
                                    v-model="form.is_new"
                                    >

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
export default {
    props: ["post-route"],
    computed: {
        school() {
            return this.$store.state.school;
        }
    },
    data() {
        return {
            editmode: false,
            students: {},
            levels: {},
            selected_file: "",
            isSelectAll: false,
            arms: {},
            hasArm: false,
            selected: [],
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
                is_new:0,
                school_id: window.user.school_id
            }),
            deleteForm: new Form({
                studentIds: []
            }),
            tableColumns: [
                {
                    label: "S/ID",
                    field: "id",
                    numeric: false,
                    html: false
                },
                {
                    label: "SURNAME",
                    field: "surname",
                    numeric: false,
                    html: false
                },
                {
                    label: "FIRST NAME",
                    field: "first_name",
                    numeric: false,
                    html: false
                },
                {
                    label: "MIDDLE NAME",
                    field: "middle_name",
                    numeric: false,
                    html: false
                },
                {
                    label: "PHONE",
                    field: "phone",
                    numeric: false,
                    html: false
                },
                {
                    label: "CLASS",
                    field: "levels.level_name",
                    numeric: false,
                    html: false
                },
                {
                    label: "ARM",
                    field: "arm.name",
                    numeric: false,
                    html: false
                },
                {
                    label: "GENDER",
                    field: "gender",
                    numeric: false,
                    html: false
                }
            ],
            tableRows: []
        };
    },
    mounted() {
        axios.get("api/arms").then(res => {
            this.arms = res.data;
            // this.id=id;
        });
    },
    methods: {
        getResults(page = 1) {
            axios.get("api/student?page=" + page).then(response => {
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
        editModal(student) {
            this.editmode = true;
            // Fire.$emit('AfterCreate');
            this.form.reset();
            $("#addNew").modal("show");
            this.form.fill(student);
        },
        newModal() {
            this.editmode = false;
            // Fire.$emit('AfterCreate');
            this.form.reset();
            $("#addNew").modal("show");
        },
        deleteStudent() {
            swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then(result => {
                // Send request to the server
                if (result.value) {
                    this.deleteForm.studentIds = this.studentIds;
                    this.deleteForm
                        .delete("api/students")
                        .then(() => {
                            swal.fire(
                                "Deleted!",
                                "student has been deleted.",
                                "success"
                            );
                            this.isStudentId = false;

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
        loadLevels() {
            if (this.$gate.isAdminOrTutor()) {
                axios.get("api/get_levels").then(({ data }) => {
                    this.levels = data;
                    // console.log(this.levels);
                });
            }
        },
        loadStudents() {
            if (this.$gate.isAdminOrTutor()) {
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
                .then(res => {
                    Fire.$emit("AfterCreate");
                    $("#addNew").modal("hide");

                    toast.fire({
                        type: "success",
                        title: "Student Created in successfully"
                    });
                    //console.log(res)
                    this.$Progress.finish();
                })
                .catch(err => {
                    toast.fire({
                        type: "fail",
                        title: err
                    });
                    this.$Progress.fail();
                });
        },
        selectFile() {
            this.selected_file = this.$refs.file.files[0];
            console.log(this.selected_file);
        },
        importStudents() {
            this.$Progress.start();
            const formData = new FormData();
            formData.append("selected_file", this.selected_file);
            //console.log(formData.values);
            axios
                .post("api/importStudents", formData)
                .then(res => {
                    toast.fire({
                        type: "success",
                        title: "Students successfully imported"
                    });
                    //  console.log(res.data)
                    this.$Progress.finish();
                    Fire.$emit("AfterCreate");
                })
                .catch(err => {
                    toast.fire({
                        type: "danger",
                        title: "there was error uploading the file" + err
                    });
                    console.log(err);
                });
        },
        checkArm() {
            var e = document.querySelector("#level");
            var id = e.options[e.selectedIndex].value;
            console.log(id);
            axios
                .get(`api/check_arm/${id}`)
                .then(res => {
                    if (res.data > 0) {
                        this.hasArm = true;
                        //  this.downloadLogin();
                    } else {
                        this.hasArm = false;
                    }
                })
                .catch(err => {
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
        updatePassword() {
            swal.fire({
                title: "Are you sure?",
                text: "You are generating new password for all the students !",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, update !"
            }).then(result => {
                // Send request to the server
                if (result.value) {
                    axios
                        .post("api/update_password")
                        .then(() => {
                            swal.fire(
                                "Updated!",
                                "passwords have been updated.",
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

        setActivation(id) {
            axios.put("/api/activateUser/" + id).then(res => {});
        },
        downloadLogin() {
            axios
                .get(`/api/login_export/${this.level_id}/${this.arm_id}`)
                .then(res => {
                    this.student_login = res.data.student_login;
                });
        }
    },
    created() {
        Fire.$on("searching", () => {
            let query = this.$parent.search;
            axios
                .get("api/findStudent?q=" + query)
                .then(data => {
                    this.students = data.data;
                    //  console.log(this.students)
                })
                .catch(() => {});
        });
        this.loadStudents();
        this.loadLevels();

        Fire.$on("AfterCreate", () => {
            this.loadStudents();
        });
        //setInterval(() => this.loadUsers(), 3000);
    }
};
</script>

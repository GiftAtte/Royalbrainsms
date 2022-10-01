<template>
    <div>
        <div class="content-header">
            <div class="container-fluid navy">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Classes/Levels</h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="#">Home</a>
                            </li>
                            <li class="breadcrumb-item active">class List</li>
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
                    <div class="col-12 table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>L/ID</th>
                                    <th>Level Names</th>
                                    <th>Class Teachers</th>
                                    <th>Has Arms</th>
                                    <th>Modify</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="level in levels.data"
                                    :key="level.id"
                                >
                                    <td>{{ level.id }}</td>
                                    <td>
                                        {{
                                            level.level_name
                                                ? level.level_name
                                                : ""
                                        }}
                                    </td>
                                    <td v-if="level.has_arm">
                                        <router-link
                                            :to="`levels/${level.id}`"
                                            tag="a"
                                            exact
                                            >View Arms/class
                                            teachers</router-link
                                        >
                                    </td>
                                    <td v-else-if="level.staff">
                                        {{ level.staff.surname }}&nbsp;{{
                                            level.staff.first_name
                                        }}
                                    </td>
                                    <td v-else>{{ "" }}</td>
                                    <td v-if="level.has_arm">yes</td>
                                    <td v-else>No</td>

                                    <td class="row">
                                        <a href="#" @click="addArm(level.id)">
                                            <i class="fa fa-plus">A</i>
                                        </a>
                                        /
                                        <a
                                            href="#"
                                            @click="editModal(level)"
                                            class="pl-2"
                                        >
                                            <i class="fa fa-edit blue"></i>
                                        </a>
                                        /
                                        <a
                                            href="#"
                                            @click="deleteLevel(level.id)"
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
                        <pagination
                            :data="levels"
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
            class="modal fade"
            id="arms_form"
            tabindex="-1"
            role="dialog"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            Add Arms
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
                    <form @submit.prevent="CreateArms()">
                        <div class="modal-body">
                            <div class="form-group">
                                <select
                                    name="arms_id"
                                    id="arms_id"
                                    :class="{
                                        'is-invalid': form.errors.has('arm_id'),
                                    }"
                                    class="form-control"
                                    v-model="Arm_form.arms_id"
                                >
                                    <option value selected>Select Arm</option>
                                    <option
                                        v-for="arm in arms"
                                        :key="arm.id"
                                        :value="arm.id"
                                    >
                                        {{ arm.name }}
                                    </option>
                                </select>
                                <has-error
                                    :form="Arm_form"
                                    field="arm_id"
                                ></has-error>
                            </div>

                            <div class="form-group">
                                <select
                                    name="staff_id"
                                    id="staff_id"
                                    :class="{
                                        'is-invalid':
                                            form.errors.has('staff_id'),
                                    }"
                                    class="form-control"
                                    v-model="Arm_form.staff_id"
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
                                    :form="Arm_form"
                                    field="staff_id"
                                ></has-error>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button
                                type="button"
                                class="btn btn-secondary"
                                data-dismiss="modal"
                            >
                                Close
                            </button>
                            <button class="btn btn-primary">
                                Save changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Level Modal -->
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
                            Update User's Info
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
                            editmode ? updateLevel() : createLevel()
                        "
                    >
                        <div class="modal-body">
                            <div class="form-group">
                                <input
                                    v-model="form.level_name"
                                    type="text"
                                    name="level_name"
                                    placeholder="Class/Level Name"
                                    class="form-control"
                                    :class="{
                                        'is-invalid':
                                            form.errors.has('level_name'),
                                    }"
                                />
                                <has-error
                                    :form="form"
                                    field="level_name"
                                ></has-error>
                            </div>

                            <div class="form-group">
                                <select
                                    name="class_id"
                                    id="class_id"
                                    :class="{
                                        'is-invalid':
                                            form.errors.has('class_id'),
                                    }"
                                    class="form-control"
                                    v-model="form.staff_id"
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
    data() {
        return {
            editmode: false,
            levels: {},
            Staff: {},
            arms: {},
            id: "",
            selected_file: "",
            importFile: "api/importUser",
            form: new Form({
                id: "",
                level_name: "",
                staff_id: "",
                arm: "",
                school_id: window.user.school_id,
            }),

            Arm_form: new Form({
                arms_id: "",
                staff_id: "",
                level_id: "",
                school_id: window.user.school_id,
            }),
        };
    },
    mounted() {
        axios
            .get("/api/employees")
            .then((res) => {
                this.Staff = res.data;
            })
            .catch((err) => {});

        this.loadLevels();
    },
    methods: {
        getResults(page = 1) {
            axios.get("/api/level?page=" + page).then((response) => {
                this.levels = response.data;
            });
        },
        updateLevel() {
            this.$Progress.start();
            // console.log('Editing data');
            this.form
                .put("/api/level/" + this.form.id)
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
        editModal(level) {
            this.editmode = true;
            this.form.reset();
            $("#addNew").modal("show");
            this.form.fill(level);
        },
        newModal() {
            this.editmode = false;
            this.form.reset();
            $("#addNew").modal("show");
        },
        addArm(id) {
            axios
                .get("api/arms")
                .then((res) => {
                    this.arms = res.data;
                    this.id = id;
                })
                .catch((err) => {});
            $("#arms_form").modal("show");
        },
        CreateArms() {
            this.$Progress.start();
            this.Arm_form.level_id = this.id;
            this.Arm_form.post("api/add_arms")
                .then(() => {
                    Fire.$emit("AfterCreate");
                    $("#addNew").modal("hide");

                    toast.fire({
                        type: "success",
                        title: "Student Created in successfully",
                    });
                    this.$Progress.finish();
                    $("#arms_form").modal("hide");
                    Fire.$emit("AfterCreate");
                })
                .catch(() => {});
        },
        deleteLevel(id) {
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
                        .delete("api/level/" + id)
                        .then(() => {
                            swal.fire(
                                "Deleted!",
                                "level has been deleted.",
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
        loadLevels() {
            if (this.$gate.isAdminOrTutor()) {
                axios.get("/api/level").then(({ data }) => {
                    console.log(data);
                    this.levels = data;
                });
            }
        },

        createLevel() {
            this.$Progress.start();

            this.form
                .post("/api/level")
                .then(() => {
                    Fire.$emit("AfterCreate");
                    $("#addNew").modal("hide");

                    toast.fire({
                        type: "success",
                        title: "Student Created in successfully",
                    });
                    this.$Progress.finish();
                })
                .catch(() => {});
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
                .then((res) => {
                    toast.fire({
                        type: "success",
                        title: "Students successfully imported",
                    });
                    console.log(res.data);
                    this.$Progress.finish();
                    Fire.$emit("AfterCreate");
                })
                .catch((err) => {
                    toast.fire({
                        type: "danger",
                        title: "there was error uploading the file" + err,
                    });
                    console.log(err);
                });
        },
    },
    created() {
        Fire.$on("searching", () => {
            let query = this.$parent.search;
            axios
                .get("/api/findStudent?q=" + query)
                .then((data) => {
                    this.levels = data.data;
                })
                .catch(() => {});
        });
        this.loadLevels();
        Fire.$on("AfterCreate", () => {
            this.loadLevels();
        });
        //    setInterval(() => this.loadUsers(), 3000);
    },
};
</script>

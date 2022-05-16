<template>
    <div>
        <div class="content-header">
            <div class="container-fluid navy">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Siblings</h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="#">Parent</a>
                            </li>
                            <li class="breadcrumb-item active">Siblings</li>
                        </ol>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>

        <div class="content" v-if="$gate.isAdminOrTutorOrParent()">
            <div class="col-12">
                <div class="card card-navy card-outline">
                    <div class="card-header">
                        <h3 class="text-primary text-uppercase">{{ parent.name }}</h3>
                        <div class="row float-right">
                            <div class="card-tools">
                                <button
                                    v-show="$gate.isAdmin()"
                                    class="btn btn-success btn-sm float-right m-2"
                                    @click="newModal"
                                >
                                    Add/Modify
                                    <i class="fas fa-user-plus fa-fw"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive">
                        <table class="table table-hover ">
                        <tbody>
                            <tr>

                                <th>S/ID</th>
                                <th colspan="2"> Names</th>

                                <th>Level</th>
                                <th>Arm</th>
                                <th>Gender</th>
                                <th>Photo</th>
                                <th v-show="$gate.isAdmin()">Remove</th>
                                <th v-show="$gate.isAdmin()">Status</th>
                            </tr>

                            <tr
                                v-for="student in students"
                                :key="student.id"
                            >

                                <td>{{ student.id }}</td>

                                <td colspan="2">
                                    <router-link
                                        :to="
                                            `/student_profile/${student.id}/student`
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

                                <td v-show="$gate.isAdmin()">


                                    <a
                                        href="#"
                                        @click=" deleteSibling(student.id)"
                                    >
                                        <i class="fa fa-times red"></i>
                                    </a>
                                </td>
                                <td v-show="$gate.isAdmin()">
                                    <toggle-button
                                        @change="setActivation(student.userId)"
                                        :label="true"
                                        :labels="{
                                            checked: 'ON',
                                            unchecked: 'OFF'
                                        }"
                                        :height="20"
                                        :font-size="14"
                                        :value="student.users.isActive"
                                        :color="'green'"
                                        :name="'activated'"
                                        class="pl-2"
                                    />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">

                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>

        <div v-if="!$gate.isAdmin">
            <not-found></not-found>
        </div>
        <!-- Arms Modal -->
        <loading
            :active.sync="isLoading"
            :can-cancel="false"
            :on-cancel="onCancel"
            color="blue"
            :is-full-page="fullPage"
        ></loading>

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
                            Update Report's Info
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
                            editmode ? updateParent() : CreateSiblings()
                        "
                    >
                        <div class="modal-body">
                            <input-field
                                v-model="form.siblingsStr"
                                type="text"
                                label="Sibling ID's"
                                :form="form"
                                field=""
                                placeholder="Add student ids separated with comma eg: 123,234,455 etc"
                            />

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
import Loading from "vue-loading-overlay";
export default {
    components: { Loading },
    data() {
        return {
            isLoading: false,
            fullPage: true,
            editmode: false,
            parents: '',
            parent:'',
            students:[],
            form: new Form({
                siblingsStr: "",
                parentId:this.$route.params.parentId


            })
        };
    },
    mounted() {

    },

    methods: {

        updateParent(id) {
            this.$Progress.start();

            this.form
                .put("/api/parents")
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
        editModal(parentInfo) {
            this.editmode = true;
            this.form.reset();
            $("#addNew").modal("show");
            this.form.fill(parentInfo);

        },
        newModal() {
            this.editmode = false;
            this.form.reset();
            if(this.students.length>0){
                console.log(this.students)
            let str=''
            this.students.forEach(student=>{
              str=str+`${student.id},`
            })
            this.form.siblingsStr=str
            }
            $("#addNew").modal("show");
        },

        CreateSiblings() {
            this.$Progress.start();
            this.form
                .post("/api/siblings")
                .then(() => {
                    Fire.$emit("AfterCreate");
                    $("#addNew").modal("hide");

                    toast.fire({
                        type: "success",
                        title: "Sibling(s) added successfully"
                    });
                    this.$Progress.finish();
                    $("#addNew").modal("hide");
                    Fire.$emit("AfterCreate");
                })
                .catch(() => {});
        },
        deleteSibling(id) {
            swal.fire({
                title: "Are you sure?",
                text: "You want to remove this child from the list",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then(result => {
                // Send request to the server
                if (result.value) {
                    this.form
                        .delete("/api/siblings/" + id)
                        .then(() => {
                            swal.fire(
                                "Deleted!",
                                "Sibling removed successfully",
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
        loadSiblings() {
            if (this.$gate.isAdminOrTutorOrParent()) {
                axios.get(`/api/siblings/${this.$route.params.parentId?this.$route.params.parentId:''}`).then(response => {
                    this.students = response.data.siblings;
                    this.parent=response.data.parent;
                  //  console.log(response.data.data)
                });
            }
        },

        setLoading() {
            this.isLoading = true;
        },
        resetLoading() {
            this.isLoading = false;
        }
    },
    created() {

        this. loadSiblings();



        Fire.$on("AfterCreate", () => {
            this.loadSiblings();
        });
        //    setInterval(() => this.loadUsers(), 3000);
    }
};
</script>

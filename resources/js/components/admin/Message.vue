<template>
    <div>
        <div class="content-header">
            <div class="container-fluid navy">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Manage SMS</h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="#">Home</a>
                            </li>
                            <li class="breadcrumb-item active">sms</li>
                        </ol>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>

        <div class="content" v-if="$gate.isAdmin()">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        <div class="row float-right">
                            <div class="card-tools">
                                <button
                                    class="btn btn-success btn-sm float-right m-2"
                                    @click="newModal"
                                >
                                    Add New
                                    <i class="fas fa-user-plus fa-fw"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <th>S/ID</th>
                                    <th>Email</th>
                                    <th>Key</th>

                                    <th>Date Created</th>
                                    <th>Modify</th>
                                </tr>

                                <tr v-for="pkey in payStack" :key="pkey.id">
                                    <td>{{ pkey.id }}</td>
                                    <td>{{ pkey.email }}</td>
                                    <td>{{ pkey.api_key }}</td>
                                    <td>{{ pkey.created_at | myDate }}</td>
                                    <td>
                                        <a href="#" @click="editModal(pkey)">
                                            <i class="fa fa-edit blue"></i>
                                        </a>
                                        /
                                        <a
                                            href="#"
                                            @click="deleteMessageApi(pkey.id)"
                                        >
                                            <i class="fa fa-trash red"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer"></div>
                </div>
                <!-- /.card -->
            </div>
        </div>

        <div v-if="!$gate.isAdmin()">
            <not-found></not-found>
        </div>
        <!-- Arms Modal -->
        <div
            class="modal fade"
            id="addNew"
            tabindex="-1"
            role="dialog"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog" role="document">
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
                            Update Key's Info
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
                            editmode ? updateMessageApi() : createMessageApi()
                        "
                    >
                        <div class="modal-body">
                            <div class="form-group">
                                <input
                                    type="email"
                                    name="email"
                                    placeholder="Ebulksms Email"
                                    id="paystack_key"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': form.errors.has('bank'),
                                    }"
                                    v-model="form.email"
                                />
                                <has-error
                                    :form="email"
                                    field="bank"
                                ></has-error>
                            </div>
                            <div class="form-group">
                                <input
                                    type="text"
                                    name="name"
                                    placeholder="Enter ebulksms key"
                                    id="api_key"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': form.errors.has('name'),
                                    }"
                                    v-model="form.api_key"
                                />
                                <has-error
                                    :form="form"
                                    field="api_key"
                                ></has-error>
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <select
                                    v-model="form.is_active"
                                    class="form-control"
                                    name="is_active"
                                >
                                    <option value="" selected>
                                        Select status
                                    </option>
                                    <option value="1">Set Active</option>
                                    <option value="0">Deactivate</option>
                                </select>
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

        <!-- Level Modal -->
    </div>
</template>

<script>
import { mapState } from "vuex";
export default {
    data() {
        return {
            editmode: false,
            payStack: "",
            smsApi: "",

            sessions: {},
            selected_file: "",
            activate: false,
            form: new Form({
                id: "",
                email: "",
                api_key: "",
                is_active: true,
                school_id: "",
            }),
        };
    },

    methods: {
        updateMessageApi() {
            if (this.$gate.isAdmin()) {
                this.$Progress.start();
                // console.log('Editing data');
                this.form
                    .put("/api/messageApi")
                    .then(() => {
                        // success
                        $("#addNew").modal("hide");
                        swal.fire(
                            "Updated!",
                            "Session has been updated.",
                            "success"
                        );
                        this.$Progress.finish();
                        Fire.$emit("AfterCreate");
                    })
                    .catch(() => {
                        this.$Progress.fail();
                    });
            }
        },

        editModal(paystck) {
            this.editmode = true;
            this.form.reset();
            $("#addNew").modal("show");
            this.form.fill(paystck);
        },
        newModal() {
            this.editmode = false;
            this.form.reset();
            $("#addNew").modal("show");
        },

        createMessageApi() {
            if (this.$gate.isAdmin()) {
                this.$Progress.start();

                this.form
                    .post("api/messageApi")
                    .then(() => {
                        Fire.$emit("AfterCreate");
                        $("#addNew").modal("hide");

                        toast.fire({
                            type: "success",
                            title: "Session Created in successfully",
                        });
                        this.$Progress.finish();
                        $("#addNew").modal("hide");
                        Fire.$emit("AfterCreate");
                    })
                    .catch(() => {});
            }
        },

        deleteMessageApi(id) {
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
                        this.form
                            .delete("/api/messageApi/" + id)
                            .then(() => {
                                swal.fire(
                                    "Deleted!",
                                    "paystack has been deleted.",
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
            }
        },
        loadMessageApi() {
            if (this.$gate.isAdmin()) {
                axios.get("/api/messageApi").then((res) => {
                    this.payStack = res.data.sms;
                    let smsApi = res.data.is_active;
                    this.form.email = smsApi.email;
                    this.form.api_key = smsApi.api_key;
                });
            }
        },
    },
    created() {
        this.loadMessageApi();

        Fire.$on("AfterCreate", () => {
            this.loadMessageApi();
        });
        //    setInterval(() => this.loadUsers(), 3000);
    },
};
</script>

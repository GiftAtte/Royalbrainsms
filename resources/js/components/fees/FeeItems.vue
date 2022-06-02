<template>
    <div>
        <div class="content-header">
            <div class="container-fluid navy">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Fee Items</h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="#">FeeItems</a>
                            </li>
                            <li class="breadcrumb-item active">Report Group</li>
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
                <div class="card card-navy card-outline">
                    <div class="card-header">
                      
                        
                        <div class="row float-right">
                            <div class="card-tools">
                                <button
                                    v-show="$gate.isAdmin()"
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
                        <table class="table table-hover" id="data_tb">
                            <tbody>
                                <tr>
                                    <th>S/N</th>
                                    <th>Fee Items</th>
                                    <th>Action</th>
                                </tr>

                                <tr
                                    v-for="(feeItem, index) in feeItems"
                                    :key="feeItem.id"
                                >
                                    <td>{{ index + 1 }}</td>
                                    <td>
                                        {{ feeItem.name }}
                                    </td>
                                    <td>
                                        <a
                                            href="#"
                                            @click="editModal(feeItem)"
                                            class="pl-2"
                                        >
                                            <i class="fa fa-edit blue"></i>
                                        </a>

                                        <a
                                            href="#"
                                            @click="deleteFeeItem(feeItem.id)"
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
                       
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>

        <div v-if="!$gate.isAdmin">
            <not-found></not-found>
        </div>
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
                            Update Info
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
                            editmode ? updateFeeItem() : CreateFeeItem()
                        "
                    >
                        <div class="modal-body">
                            <input-field
                                v-model="form.name"
                                type="text"
                                label="Fee Item"
                                :form="form"
                                field="name"
                                placeholder="Enter fullname"
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
            feeItems: [],
            form: new Form({
                id: "",
                name: "",
            }),
        };
    },
    mounted() {},

    methods: {
        getResults(page = 1) {
            axios.get("api/parents?page=" + page).then((response) => {
                this.parents = response.data;
            });
        },
        updateFeeItem(id) {
            this.$Progress.start();

            this.form
                .put("/api/feeItems")
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
            $("#addNew").modal("show");
        },

   
        CreateFeeItem() {
            this.$Progress.start();
            //console.log(this.form.name);
            this.form
                .post("api/feeItems")
                .then(() => {
                    Fire.$emit("AfterCreate");
                    $("#addNew").modal("hide");

                    toast.fire({
                        type: "success",
                        title: "Fee Item Created successfully",
                    });
                    this.$Progress.finish();
                    $("#addNew").modal("hide");
                    Fire.$emit("AfterCreate");
                })
                .catch(() => {});
        },
        deleteFeeItem(id) {
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
                        .delete("/api/feeItems/" + id)
                        .then(() => {
                            swal.fire(
                                "Deleted!",
                                "A parent has been deleted.",
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
        loadFeeItems() {
            if (this.$gate.isAdminOrTutorOrStudent()) {
                axios.get("/api/feeItems").then((response) => {
                    this.feeItems = response.data;
                    //  console.log(response.data.data)
                });
            }
        },

        
    },
    created() {
        // console.log(window.user)
        this.loadFeeItems();
        Fire.$on("AfterCreate", () => {
            this.loadFeeItems();
        });
        //    setInterval(() => this.loadUsers(), 3000);
    },
};
</script>

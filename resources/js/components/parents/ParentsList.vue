<template>
    <div>
        <div class="content-header">
            <div class="container-fluid navy">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Parents</h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="#">Parent</a>
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

        <div class="content" v-if="$gate.isAdminOrTutorOrStudent()">
            <div class="col-12">
                <div class="card card-navy card-outline">
                    <div class="card-header">
                        <input type="file" name="parentFile" ref="file" class="btn" @change="setFile">
                        <button class="btn btn-primary" @click="importParent">Import</button>
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
                        <table class="  table table-hover " id="data_tb">
                            <tbody>
                                <tr>
                                    <th>R/ID</th>
                                    <th>Name</th>

                                    <th>Phone Number</th>
                                    <th>Siblings</th>
                                    <th>Action</th>
                                </tr>

                                <tr
                                    v-for="parent in parents.data"
                                    :key="parent.id"
                                >
                                    <td>{{ parent.id }}</td>
                                    <td>

                                     <router-link :to=" `parent_profile/parent/${parent.id}`" tag="a" exact>
                                       {{ parent.name }}
                                    </router-link>
                                    </td>
                                    <td>{{ parent.phoneNumber }}</td>

                                    <td>

                                    <router-link  :to=" `siblings/${parent.id}`" tag="a" exact>
                                        Manage Siblings
                                    </router-link>

                                </td><td>
                                        <a
                                            href="#"
                                            @click="editModal(parent)"
                                            class="pl-2"
                                        >
                                            <i class="fa fa-edit blue"></i>
                                        </a>

                                        <a
                                            href="#"
                                            @click="deleteParent(parent.id)"
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
                            :data="parents"
                            @pagination-change-page="getResults"
                        ></pagination>
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
                            editmode ? updateParent() : CreateParent()
                        "
                    >
                        <div class="modal-body">
                            <input-field
                                v-model="form.name"
                                type="text"
                                label="Full Name"
                                :form="form"
                                field="name"
                                placeholder="Enter fullname"
                            />
                             <input-field
                                v-model="form.email"
                                type="text"
                                label="Email Address"
                                :form="form"
                                field="email"
                                placeholder="Enter your email"
                            />
                             <input-field
                                v-model="form.phoneNumber"
                                type="text"
                                label="Phone Numbers"
                                :form="form"
                                field="phoneNumber"
                                placeholder="Enter phone eg 0803333, 09013333"
                            />
                             <input-field
                                v-model="form.contactAddress"
                                type="text"
                                label="Contact Address"
                                :form="form"
                                field="contactAddress"
                                placeholder="Enter Contact Address"
                            />
                             <input-field
                                v-model="form.occupation"
                                type="text"
                                label="Sponsors's Occupation"
                                :form="form"
                                field="name"
                                placeholder="Sponsors's Occupation"
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
            file:'',
            parents: '',
            form: new Form({
                id: "",
                name: "",
                contactAddress: "",
                phoneNumber: "",
                email:'',
                occupation:''

            })
        };
    },
    mounted() {

    },

    methods: {
        getResults(page = 1) {
            axios.get("api/parents?page=" + page).then(response => {
                this.parents = response.data;
            });
        },
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
            $("#addNew").modal("show");
        },

    setFile() {
      this.file = this.$refs.file.files[0];
      console.log(this.file);
    },

    importParent() {
      this.isLoading = true;
      const formData = new FormData();
      formData.append("file", this.file);
      axios
        .post("/api/parents/import", formData)
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

        CreateParent() {
            this.$Progress.start();
            console.log(this.form.name);
            this.form
                .post("api/parents")
                .then(() => {
                    Fire.$emit("AfterCreate");
                    $("#addNew").modal("hide");

                    toast.fire({
                        type: "success",
                        title: "A parent Created successfully"
                    });
                    this.$Progress.finish();
                    $("#addNew").modal("hide");
                    Fire.$emit("AfterCreate");
                })
                .catch(() => {});
        },
        deleteParent(id) {
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
                    this.form
                        .delete("/api/parents/" + id)
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
        loadParent() {
            if (this.$gate.isAdminOrTutorOrStudent()) {
                axios.get("/api/parents").then(response => {
                    this.parents = response.data;
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
        // console.log(window.user)
        this. loadParent();
        Fire.$on("searching", () => {
            let query = this.$parent.search;
            axios
                .get("api/findStudent?q=" + query)
                .then(data => {
                    this.levels = data.data;
                })
                .catch(() => {});
        });


        Fire.$on("AfterCreate", () => {
            this.loadParent();
        });
        //    setInterval(() => this.loadUsers(), 3000);
    }
};
</script>

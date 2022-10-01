<template>
    <div>
        <div class="content-header">
            <div class="container-fluid navy">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">School List</h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="#">Home</a>
                            </li>
                            <li class="breadcrumb-item active">School List</li>
                        </ol>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>

        <app-modal
            id="admissionLink"
            modalTitle=" "
            modalSize="modal-lg"
            sumitButtonText="copy link"
            :createAction="copy"
        >
            <div class="col-md-12">
                <div class="form-group">
                    <label for="reglink">Admission Link</label>
                    <span
                        v-if="isCopied"
                        class="text-white bg-primary my-2 p-2 mx-2"
                        style="border-radius: 50px"
                        >Copied</span
                    >
                    <input
                        v-model="url"
                        type="text"
                        v-on:focus="$event.target.select()"
                        class="form-control"
                        ref="reglink"
                    />
                </div>
            </div>
        </app-modal>

        <div class="content" v-if="$gate.isAdmin()">
            <div class="col-12">
                <div class="card card-navy card-outline">
                    <div class="card-header">
                        <div class="row float-right">
                            <div class="card-tools">
                                <button
                                    class="btn btn-primary btn-sm float-right m-2"
                                    @click="newModal"
                                >
                                    <i class="fas fa-plus-circle"></i>
                                    Add New
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
                                    <th>Name</th>
                                    <th>Location</th>
                                    <th>State</th>
                                    <th>Admission Link</th>
                                    <th>Templates</th>
                                    <th>Modify</th>
                                </tr>

                                <tr
                                    v-for="school in schools.data"
                                    :key="school.id"
                                >
                                    <td>{{ school.id }}</td>
                                    <td>{{ school.name | upText }}</td>
                                    <td>{{ school.contact_address }}</td>
                                    <td>{{ school.state }}</td>
                                    <td>
                                        <button
                                            title="Admission Link"
                                            class="btn btn-flat"
                                            @click="getRegLink(school.id)"
                                        >
                                            <i class="fa fa-clone"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <router-link
                                            class="btn btn-flat"
                                            tag="button"
                                            :to="`/schoolTemplates/${school.id}`"
                                        >
                                            <i class="fa fa-share-square"></i>
                                        </router-link>
                                    </td>

                                    <td class="action">
                                        <a href="#" @click="editModal(school)">
                                            <i class="fa fa-edit blue"></i>
                                        </a>
                                        /
                                        <a
                                            href="#"
                                            @click="deleteSchool(school.id)"
                                            class="pl-2"
                                        >
                                            <i class="fa fa-trash red"></i>
                                        </a>

                                        <toggle-button
                                            @change="setSchool(school.id)"
                                            :label="true"
                                            :labels="{
                                                checked: 'ON',
                                                unchecked: 'OFF',
                                            }"
                                            :height="20"
                                            :font-size="14"
                                            :value="isActive(school.id)"
                                            :color="'navy'"
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
                        <pagination
                            :data="schools"
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
            id="addNew"
            tabindex="-1"
            role="dialog"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true"
        >
            <div
                class="modal-dialog modal-dialog-centered modal-lg"
                role="document"
            >
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
                            Update subject's Info
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

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input
                                        type="text"
                                        name="name"
                                        placeholder="Enter Name"
                                        id="name"
                                        class="form-control"
                                        :class="{
                                            'is-invalid':
                                                form.errors.has('name'),
                                        }"
                                        v-model="form.name"
                                    />
                                    <has-error
                                        :form="form"
                                        field="name"
                                    ></has-error>
                                </div>
                                <div class="form-group">
                                    <input
                                        type="text"
                                        name="name"
                                        placeholder="Enter Short Name (School Abbreviation)"
                                        id="name"
                                        class="form-control"
                                        :class="{
                                            'is-invalid':
                                                form.errors.has('short_name'),
                                        }"
                                        v-model="form.short_name"
                                    />
                                    <has-error
                                        :form="form"
                                        field="short_name"
                                    ></has-error>
                                </div>

                                <div class="form-group">
                                    <input
                                        type="text"
                                        name="contact_address"
                                        placeholder="School Address"
                                        id="contact_address"
                                        class="form-control"
                                        :class="{
                                            'is-invalid':
                                                form.errors.has(
                                                    'contact_address'
                                                ),
                                        }"
                                        v-model="form.contact_address"
                                    />
                                    <has-error
                                        :form="form"
                                        field="contact_address"
                                    ></has-error>
                                </div>
                                <div class="form-group">
                                    <input
                                        type="text"
                                        name="state"
                                        placeholder="Enter state"
                                        id="state"
                                        class="form-control"
                                        :class="{
                                            'is-invalid':
                                                form.errors.has('state'),
                                        }"
                                        v-model="form.state"
                                    />
                                    <has-error
                                        :form="form"
                                        field="state"
                                    ></has-error>
                                </div>
                                <div class="form-group">
                                    <input
                                        type="text"
                                        name="email"
                                        placeholder="email address of the school"
                                        id="state"
                                        class="form-control"
                                        :class="{
                                            'is-invalid':
                                                form.errors.has('state'),
                                        }"
                                        v-model="form.email"
                                    />
                                    <has-error
                                        :form="form"
                                        field="state"
                                    ></has-error>
                                </div>

                                <div class="form-group">
                                    <input
                                        type="text"
                                        name="phone"
                                        placeholder="Phone numbers"
                                        id="name"
                                        class="form-control"
                                        :class="{
                                            'is-invalid':
                                                form.errors.has('phone'),
                                        }"
                                        v-model="form.phone"
                                    />
                                    <has-error
                                        :form="form"
                                        field="phone"
                                    ></has-error>
                                </div>
                                <div class="form-group">
                                    <input
                                        type="text"
                                        name="website"
                                        placeholder="Enter Website"
                                        id="name"
                                        class="form-control"
                                        :class="{
                                            'is-invalid':
                                                form.errors.has('website'),
                                        }"
                                        v-model="form.website"
                                    />
                                    <has-error
                                        :form="form"
                                        field="website"
                                    ></has-error>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input
                                        type="text"
                                        name="country"
                                        placeholder="Counntry"
                                        id="country"
                                        class="form-control"
                                        :class="{
                                            'is-invalid':
                                                form.errors.has('country'),
                                        }"
                                        v-model="form.country"
                                    />
                                    <has-error
                                        :form="form"
                                        field="conuntry"
                                    ></has-error>
                                </div>

                                <div class="form-group">
                                    <input
                                        type="file"
                                        @change="updateProfile"
                                        name="logo"
                                        class="form-input"
                                    />
                                </div>

                                <div class="form-group">
                                    <label> PAYMENT GATE WAY</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        v-model="form.gateway_pk"
                                        placeholder="payment gateway public key"
                                    />
                                </div>
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
                            type="button"
                            class="btn btn-success"
                            @click="updateSchool"
                        >
                            Update
                        </button>
                        <button
                            v-show="!editmode"
                            type="button"
                            class="btn btn-primary"
                            @click="createSchool"
                        >
                            Create
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Level Modal -->
    </div>
</template>

<script>
import { mapState } from "vuex";
export default {
    computed: mapState(["school"]),

    data() {
        return {
            editmode: false,
            url: "",
            schools: {},
            selected_file: "",
            activate: false,
            templates: [],
            templateOptions: [],
            isCopied: false,
            form: new Form({
                id: "",
                name: "",
                state: "",
                contact_address: "",
                phone: "",
                website: "",
                logo: "",
                country: "",
                short_name: "",
                email: "",
                gateway_pk: "",
                result_templates: [
                    { id: 1, label: "default", value: "default" },
                ],
            }),
        };
    },

    methods: {
        getResults(page = 1) {
            axios.get("api/schools?page=" + page).then((response) => {
                this.subjects = response.data;
            });
        },
        loadTemplate() {
            axios.get("api/templates").then((res) => {
                res.data.forEach((template) => {
                    this.templateOptions.push({
                        id: template.id,
                        label: template.name,
                        value: template.name,
                    });
                });
            });
        },

        updateSchool() {
            this.$Progress.start();
            // console.log('Editing data');
            this.form
                .put("api/school/" + this.form.id)
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
        updateProfile(e) {
            let file = e.target.files[0];
            let reader = new FileReader();

            let limit = 1024 * 1024 * 2;
            if (file["size"] > limit) {
                swal.fire({
                    type: "error",
                    title: "Oops...",
                    text: "You are uploading a large file",
                });
                return false;
            }

            reader.onloadend = (file) => {
                this.form.logo = reader.result;
            };
            reader.readAsDataURL(file);
            console.log(this.form.logo);
        },

        editModal(school) {
            this.editmode = true;
            this.form.reset();
            $("#addNew").modal("show");
            this.form.fill(school);
        },
        newModal() {
            this.editmode = false;
            this.form.reset();
            $("#addNew").modal("show");
        },
        getRegLink(id) {
            axios.get("/api/getRegLink/" + id).then((res) => {
                this.url = res.data;
                $("#admissionLink").modal("show");
            });
        },
        createSchool() {
            this.$Progress.start();
            this.form
                .post("/api/school")
                .then(() => {
                    Fire.$emit("AfterCreate");
                    $("#addNew").modal("hide");

                    toast.fire({
                        type: "success",
                        title: "Student Created in successfully",
                    });
                    this.$Progress.finish();
                    $("#addNew").modal("hide");
                    Fire.$emit("AfterCreate");
                })
                .catch(() => {});
        },
        deleteSchool(id) {
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
                        .delete("api/school/" + id)
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
        copy() {
            this.$refs.reglink.focus();
            document.execCommand("copy");
            this.isCopied = true;
            setTimeout(() => {
                this.isCopied = false;
            }, 2000);
        },
        loadSchools() {
            if (this.$gate.isAdminOrTutor()) {
                axios
                    .get("api/school")
                    .then((res) => (this.schools = res.data));
            }
        },
        setSchool(id) {
            //     this.$Progress.start()
            // this.$store.dispatch('loadSchool',[id])

            axios.put("api/set_school/" + id).then(() => {
                toast.fire({
                    type: "success",
                    title: "School switched successfully",
                });
                this.$Progress.finish();
                this.$store.dispatch("loadSchool");
                localStorage.removeItem("products");
                window.location.replace("/dashboard");
            });
        },
        isActive(id) {
            if (id === window.user.school_id) {
                // console.log(id)
                return true;
            } else {
                //  console.log(id)
                return false;
            }
        },
    },
    created() {
        this.loadTemplate();
        this.loadSchools();

        Fire.$on("AfterCreate", () => {
            this.loadSchools();
        });
        //    setInterval(() => this.loadUsers(), 3000);
    },
};
</script>

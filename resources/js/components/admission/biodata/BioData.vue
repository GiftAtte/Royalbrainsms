<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Dashboard</h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="#">Home</a>
                            </li>
                            <li class="breadcrumb-item active">Profile</li>
                        </ol>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>

        <div class="content col-md-12 row">
            <div class="col-md-3" id="profile">
                <!-- Profile Image -->
                <div class="card card-navy card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img
                                class="profile-user-img img-fluid img-circle"
                                :src="`/img/profile/${form.users.photo}`"
                                alt="User profile picture"
                            />
                        </div>

                        <h3 class="profile-username text-center">
                            {{ form.surname }}&nbsp; {{ form.first_name }}
                        </h3>

                        <p class="text-muted text-center">CANDIDATE</p>

                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>Level</b>
                                <a class="float-right"
                                    >{{ form.levels.level_name }}
                                    {{ form.arm.name }}</a
                                >
                            </li>
                            <li class="list-group-item">
                                <b>Gender</b>
                                <a class="float-right">{{ form.gender }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Portal ID</b>
                                <a class="float-right">{{
                                    form.users.portal_id
                                }}</a>
                            </li>
                        </ul>

                        <a
                            href="#"
                            class="btn btn-primary btn-block text-uppercase"
                            ><b>{{ form.users.type }}</b></a
                        >
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
                <div class="card card-navy">
                    <div class="card-body box-profile">
                        <h3 class="profile-username text-center">E-WALLET</h3>

                        <p class="text-muted text-center">
                            Account Information
                        </p>

                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>Bank Name</b>
                                <a class="float-right">{{
                                    form.bankName ? form.bankName : ""
                                }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Account Number</b>
                                <a class="float-right">{{
                                    form.accountNumber
                                }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Account Balance</b>
                                <a class="float-right"
                                    >N{{
                                        Number(accountDetails.balance).toFixed(
                                            2
                                        )
                                    }}</a
                                >
                            </li>
                        </ul>
                        <h6 class="text-danger text-uppercase">
                            N/B: Transfer money into this account for settlement
                            of your fees
                        </h6>
                        <hr />

                        <router-link
                            :to="`/transactions/${form.accountNumber}`"
                            title="view transacions"
                            tag="a"
                            exact
                            class="btn btn-primary btn-block text-uppercase"
                        >
                            Transactions</router-link
                        >
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- About Me Box -->

                <!-- /.card -->
            </div>

            <!-- tab -->

            <div class="col-md-9">
                <div class="card card-navy card-outline">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item">
                                <a
                                    class="nav-link active show"
                                    href="#profile-update"
                                    data-toggle="tab"
                                    >Profile update</a
                                >
                            </li>
                            <li class="nav-item">
                                <a
                                    class="nav-link"
                                    href="#photo-card"
                                    data-toggle="tab"
                                    >Photo Card</a
                                >
                            </li>
                            <li class="nav-item">
                                <a
                                    class="nav-link"
                                    href="#fee-details"
                                    data-toggle="tab"
                                    >Fee Details</a
                                >
                            </li>
                            <li class="nav-item">
                                <a
                                    class="nav-link"
                                    href="#assignmentAndNote"
                                    data-toggle="tab"
                                    v-show="$gate.isParent()"
                                >
                                    Assigment/Notes</a
                                >
                            </li>
                            <li class="nav-item" v-show="$gate.isParent()">
                                <a
                                    class="nav-link"
                                    href="#examsAndRecords"
                                    data-toggle="tab"
                                    >Exams/Records</a
                                >
                            </li>
                            <li class="nav-item">
                                <a
                                    class="nav-link"
                                    href="#security"
                                    data-toggle="tab"
                                    >Security</a
                                >
                            </li>
                        </ul>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- Activity Tab -->
                            <div class="tab-pane" id="security">
                                <profile></profile>
                            </div>
                            <div class="tab-pane" id="examsAndRecords">
                                <h3 class="text-center">
                                    Student Exams And Records
                                </h3>
                                <student-resut-list></student-resut-list>
                            </div>

                            <div class="tab-pane" id="assignmentAndNote">
                                <h3 class="text-center">Assigment And Notes</h3>
                                <assignment></assignment>
                            </div>

                            <div class="container tab-pane" id="photo-card">
                                <photo-card
                                    :form="form"
                                    :school="school"
                                    :printResults="printResults"
                                />
                                <!-- /.card-body -->
                            </div>

                            <div class="tab-pane" id="fee-details">
                                <!-- <profile></profile> -->
                                <table class="table table-hover" id="data_tb">
                                    <tbody>
                                        <tr>
                                            <th>R/ID</th>
                                            <th>Fee Title</th>

                                            <th>
                                                <div>
                                                    <span>Class/Level </span>
                                                </div>
                                            </th>
                                            <th>Bank</th>
                                            <th>Discount</th>

                                            <th>Due Date</th>
                                            <th>Action</th>
                                        </tr>

                                        <tr
                                            v-for="report in reports.data"
                                            :key="report.id"
                                        >
                                            <td>{{ report.id }}</td>
                                            <td>{{ report.tittle }}</td>
                                            <td>
                                                {{
                                                    report.levels
                                                        ? report.levels
                                                              .level_name
                                                        : ""
                                                }}
                                            </td>
                                            <td>
                                                {{
                                                    report.paystacks
                                                        ? report.paystacks.bank
                                                        : ""
                                                }}
                                            </td>
                                            <td>{{ report.discount + "%" }}</td>
                                            <td>
                                                {{ report.due_date | myDate }}
                                            </td>
                                            <td v-show="$gate.isAdminOrTutor()">
                                                <router-link
                                                    :to="`/fee_description/${report.id}`"
                                                    title="view fee list"
                                                    tag="a"
                                                    exact
                                                    class="pl-2"
                                                >
                                                    Fee details</router-link
                                                >
                                            </td>

                                            <td
                                                class="row"
                                                v-show="
                                                    $gate.isStudentOrParent()
                                                "
                                            >
                                                <router-link
                                                    :to="`/fee_description/${report.id}/${$route.params.id}`"
                                                    title="view fee list"
                                                    tag="a"
                                                    exact
                                                    class="pl-2"
                                                >
                                                    Fee details</router-link
                                                >
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Setting Tab -->
                            <div
                                class="tab-pane active show"
                                id="profile-update"
                            >
                                <div class="card-bod">
                                    <div class="container">
                                        <div class="col-md-12">
                                            <h3>Personal Information Center</h3>
                                            <hr />
                                        </div>
                                        <div class="col-md-12 row">
                                            <div class="col-md-6">
                                                <input-field
                                                    label="Surname"
                                                    v-model="form.surname"
                                                    :form="form"
                                                    field="surname"
                                                />

                                                <input-field
                                                    label="Firstname"
                                                    v-model="form.first_name"
                                                    :form="form"
                                                    name="first_name"
                                                    id="firstname"
                                                    field="first_name"
                                                />
                                                <input-field
                                                    label="middle_name"
                                                    v-model="form.middle_name"
                                                    :form="form"
                                                    name="middle_name"
                                                    id="middlename"
                                                    field="middle_name"
                                                    placeholder="Enter middle name (optional)"
                                                />
                                                <select-box
                                                    label="Gender"
                                                    :form="form"
                                                    v-model="form.gender"
                                                    placeholder="Select gender"
                                                    field="type"
                                                    :options="gender"
                                                    id="gender"
                                                    name="gender"
                                                    optionLabel="name"
                                                    optionValue="id"
                                                />
                                                <input-field
                                                    label="DOB"
                                                    v-model="form.dob"
                                                    :form="form"
                                                    name="dob"
                                                    type="date"
                                                    id="firstname"
                                                    field="dob"
                                                    placeholder="dd/mm/yyyy"
                                                />
                                                <input-field
                                                    label="DOB"
                                                    v-model="form.blood_group"
                                                    :form="form"
                                                    name="blood_group"
                                                    type="text"
                                                    id="blood_group"
                                                    field="blood_group"
                                                    placeholder="Blood group (optional)"
                                                />
                                            </div>

                                            <div class="col-md-6">
                                                <input-field
                                                    label="Phone"
                                                    v-model="form.phone"
                                                    :form="form"
                                                    :field="phone"
                                                    id="phone"
                                                    name="phone"
                                                    placeholder="Phone Number"
                                                />

                                                <input-field
                                                    label="Nationality"
                                                    v-model="form.nationality"
                                                    :form="form"
                                                    name="nationality"
                                                    id="nationality"
                                                    :field="nationality"
                                                />
                                                <input-field
                                                    label="State"
                                                    v-model="form.state"
                                                    :form="form"
                                                    name="state"
                                                    id="state"
                                                    :field="state"
                                                    placeholder="Enter state of origin"
                                                />
                                                <input-field
                                                    label="LGA Of Origin"
                                                    v-model="form.lga"
                                                    :form="form"
                                                    name="lga"
                                                    type="text"
                                                    id="lga"
                                                    field="lga"
                                                    placeholder="LGA of Origin"
                                                />
                                                <input-field
                                                    label="Contact Address"
                                                    v-model="
                                                        form.contact_adress
                                                    "
                                                    :form="form"
                                                    name="contact_adress"
                                                    type="text"
                                                    id="contact_adress"
                                                    field="contact_adress"
                                                    placeholder="Contact Address"
                                                />
                                                <select-box
                                                    label="Level"
                                                    :form="form"
                                                    v-model="form.class_id"
                                                    placeholder="Select level"
                                                    :options="tbData"
                                                    id="level"
                                                    name="level"
                                                    optionLabel="name"
                                                    optionValue="id"
                                                />
                                            </div>
                                            {{ tbData }}
                                        </div>
                                        <button
                                            class="btn btn-primary"
                                            @click="updateInfo"
                                        >
                                            Update
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.nav-tabs-custom -->
            </div>
            <!-- end tabs -->
        </div>
    </div>
</template>

<script>
import { mapState } from "vuex";
import PhotoCard from "./PhotoCard.vue";
export default {
    components: { "photo-card": PhotoCard },
    computed: mapState(["school"]),
    data() {
        return {
            reports: "",
            id: this.$route.params.id,
            accountDetails: "",
            tbData: [],
            gender: [
                {
                    name: "Male",
                    id: "Male",
                },
                {
                    name: "Female",
                    id: "Female",
                },
            ],
            user: "",
            form: new Form({
                id: "",
                surname: "",
                first_name: "",
                middle_name: "",
                dob: "",
                phone: "",
                gender: "",
                class_id: "",
                contact_adress: "",
                state: "",
                nationality: "",
                religion: "",
                gender: "",
                lga: "",
                blood_group: "",
                roll_no: "",
                arm_id: "",
                home_town: "",
                mother_tongue: "",
                other_tongue: "",
                state_of_resident: "",
                lga_of_resident: "",
                bustop: "",
                former_school: "",
                fname: "",
                fphone: "",
                femail: "",
                foccupation: "",
                mname: "",
                mphone: "",
                memail: "",
                moccupation: "",
                course: "",
                genotype: "",
                height: "",
                disability: "",
                accountNumber: "",
                bankName: "",
                admission_date: "",
                admission_level: "",

                photo: "",
                levels: {
                    level_name: "",
                },
                arm: {
                    name: "",
                },
                users: {
                    portal_id: "",
                    email: "",
                    photo: "",
                    type: "",
                },
            }),
        };
    },

    mounted() {
        if (this.$gate.isCandidate()) {
            const user = window.user;
            this.form.first_name = user.name.split(" ")[1];
            this.form.surname = user.name.split(" ")[0];
        }
        //  this.loadLevel();
    },

    methods: {
        loadLevel() {
            axios
                .get("/api/admission/levels")
                .then((res) => {
                    this.tbData = res.data;
                    //  console.log(window.user);
                })
                .catch((err) => console.log(err));
        },
        getProfile() {
            axios
                .get(
                    `/api/personalInfo${
                        this.$route.params.id ? "/" + this.$route.params.id : ""
                    }`
                )
                .then(({ data }) => {
                    this.form.fill(data);
                });
        },

        getProfilePhoto() {
            let photo =
                this.form.photo.length > 200
                    ? this.form.photo
                    : "img/profile/" + this.form.photo;
            return photo;
        },
        printResults() {
            window.print();
        },
        updateInfo() {
            this.$Progress.start();
            if (this.id) {
                this.form.post("/api/admission/" + this.id).then((res) => {
                    // console.log(res.data)

                    //Fire.$emit('AfterCreate');
                    this.$Progress.finish();
                    return true;
                });
            } else {
                this.form.post("/api/admission").then((res) => {
                    swal.fire({
                        type: "success",
                        title: "Update",
                        text: "Info updated successfully ",
                    });
                    Fire.$emit("AfterCreate");
                    this.$Progress.finish();
                    return true;
                });
            }
        },
        getAccountBalance() {
            axios
                .get(`/api/getAccountBalance/${this.id ? this.id : ""}`)
                .then((res) => {
                    console.log("kkkkk", res.data);
                    this.accountDetails = res.data;
                });
        },
    },

    created() {
        this.getProfile();
        this.loadLevel();
        if (this.$route.params.id) {
            axios.get("/api/fee/" + this.id).then((response) => {
                this.reports = response.data;
                console.log(this.reports);
            });
        } else {
            axios.get("/api/fees").then((response) => {
                this.reports = response.data;
            });
        }
        Fire.$emit("afterCreated", () => {
            this.getProfile();
        });

        this.form.users = window.user;
    },
};
</script>

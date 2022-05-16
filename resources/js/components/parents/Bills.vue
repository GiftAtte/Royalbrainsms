<template>
    <div>
        <div class="content-header">
            <div class="container-fluid navy">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Parent</h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="#">Bill</a>
                            </li>
                            <li class="breadcrumb-item active">E-Bills</li>
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
                        <h3 class="text-primary text-uppercase">Bills</h3>
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
                        <table class="table table-hover table-striped">
                            <tbody>
                                <tr>
                                    <th>S/ID</th>
                                    <th colspan="2">Names</th>

                                    <th>Level</th>

                                    <th>Fee Type</th>
                                    <th>Fee Sum</th>
                                    <th>Amount Paid</th>
                                    <th>Balance</th>
                                    <th>DOP</th>
                                    <th>Status</th>
                                </tr>

                                <tr v-for="student in bills" :key="student.id">
                                    <td>{{ student.student_id }}</td>

                                    <td colspan="2">
                                        <router-link
                                            :to="
                                                `/student_profile/${student.id}/student`
                                            "
                                            tag="a"
                                            exact
                                        >
                                            {{ student.name }}
                                        </router-link>
                                    </td>
                                    <td>
                                        {{ student.level }}
                                    </td>
                                    <td>{{ student.title }}</td>
                                    <td>NGN {{ twoDp(student.feeSum) }}</td>

                                    <td>
                                        NGN
                                        {{
                                            student.paymentDetials.amount
                                                ? twoDp(
                                                      student.paymentDetials
                                                          .amount
                                                  )
                                                : "0.00"
                                        }}
                                    </td>

                                    <td>
                                        NGN
                                        {{
                                            twoDp(
                                                Number(student.feeSum) -
                                                    (student.paymentDetials
                                                        .amount
                                                        ? student.paymentDetials
                                                              .amount
                                                        : 0)
                                            )
                                        }}
                                    </td>
                                    <td>
                                        {{
                                            student.paymentDetials.created_at
                                                | myDate
                                        }}
                                    </td>
                                    <td
                                        v-if="
                                            checkStatus(
                                                student.feeSum,
                                                student.paymentDetials.amount
                                                    ? student.paymentDetials
                                                          .amount
                                                    : 0
                                            ) > 0
                                        "
                                    >
                                        <button class="btn btn-success" title="cleared">
                                            <i class="fa fa-check"></i>
                                        </button>
                                    </td>
                                    <td v-else >
                                        <button class="btn btn-danger" title="Not cleared">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot >
                                <tr>
                                <th colspan="3" class="text-primary"><h5>Total Payable Fees: NGN {{ twoDp(totalSumFee) }}</h5> </th>
                                 <th colspan="3" class="text-success"><h5>Total Amount Paid: NGN {{ twoDp(totalFeePaid)}} </h5></th>
                                <th colspan="3" class="text-danger"><h5>Total Balance: NGN {{ twoDp(totalBalance)}} </h5></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer"></div>
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
            parents: "",
          siblingsBill:'',
          totalSumFee:'',
         totalFeePaid:'',
        totalBalance:'',
            parent: "",
            bills: [],
            form: new Form({
                siblingsStr: "",
                parentId: this.$route.params.parentId
            })
        };
    },
    mounted() {},

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
            if (this.students.length > 0) {
                console.log(this.students);
                let str = "";
                this.students.forEach(student => {
                    str = str + `${student.id},`;
                });
                this.form.siblingsStr = str;
            }
            $("#addNew").modal("show");
        },
        checkStatus(feeSum, amount_paid) {
            let balance = Number(feeSum) - Number(amount_paid);
            if (balance <= 0) {
                return 1;
            } else {
                return 0;
            }
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
        loadBills() {
            this.$Progress.start();
            if (this.$gate.isAdminOrTutorOrParent()) {
                axios
                    .get(
                        `/api/parents/bills${
                            this.$route.params.parentId
                                ? "/" + this.$route.params.parentId
                                : ""
                        }`
                    )
                    .then(response => {
                        this.bills = response.data.siblingsBill;
                       this.totalSumFee = response.data.totalSumFee;
                       this.totalFeePaid = response.data.totalFeePaid;
                       this.totalBalance = response.data.totalBalance;
                      
                      
                      
                        this.$Progress.finish();
                    });
            }
        },

        setLoading() {
            this.isLoading = true;
        },
        resetLoading() {
            this.isLoading = false;
        },
        twoDp(number) {
            return Number(number).toFixed(2);
        }
    },
    created() {
        this.loadBills();

        Fire.$on("AfterCreate", () => {
            this.loadBills();
        });
        //    setInterval(() => this.loadUsers(), 3000);
    }
};
</script>

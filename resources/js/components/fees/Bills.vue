<template>
  <div>
    <div class="content-header">
      <div class="container-fluid navy">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Fee</h1>
          </div>
          <!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Fees Group</li>
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
            <div class="row float-right">
              <div class="card-tools">
                <button
                  v-show="$gate.isAdmin()"
                  class="btn btn-success btn-sm float-right m-2"
                  @click="newModal"
                >
                  Add New Fee <i class="fas fa-user-plus fa-fw"></i>
                </button>
              </div>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive">
            <table class="table table-hover" id="data_tb">
              <tbody>
                <tr>
                  <th>Bill/ID</th>
                  <th>Bill Title</th>
                  <th>
                      Amount
                  </th>
                  <th> Date created</th>
                  <th>Action</th>
                </tr>

                <tr v-for="bill in bills" :key="bill.id">
                  <td>{{ bill.id }}</td>
                  <td>{{ bill.title }}</td>
                  <td>{{ bill.amount }}</td>
                  <td>{{ bill.created_at | myDate }}</td>
                  <td colspan="2" v-show="$gate.isAdminOrTutor()">

                    <a
                      href="#"
                      @click="editModal(bill)"
                      v-show="$gate.isAdmin()"
                      class="pl-2"
                    >
                      <i class="fa fa-edit blue"></i>
                    </a>

                    <a
                      href="#"
                      @click="deleteBill(bill.id)"
                      v-show="$gate.isAdmin()"
                      class="pl-2"
                    >

                      <i class="fa fa-trash red"></i>
                    </a>

                    <a
                      href="#"
                      @click="billAccounts(bill)"
                      v-show="$gate.isAdmin()"
                      class=" btn btn-sm btn-success ml-4"
                    >


Bill Accounts
                    </a>
                  </td>

                  <td class="row" v-show="$gate.isStudent()">
                    <router-link
                      :to="`fee_description/${bill.feegroup_id}`"
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
          <!-- /.card-body -->
          <div class="card-footer">
            <pagination
              :data="reports"
              @pagination-change-page="getResults"
            ></pagination>
          </div>
        </div>
        <!-- /.card -->
      </div>
    </div>

    <div v-if="!$gate.isAdminOrTutorOrStudent()">
      <not-found></not-found>
    </div>
    <!-- Arms Modal -->
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
            <h5 class="modal-title" v-show="!editmode" id="addNewLabel">
              Add New
            </h5>
            <h5 class="modal-title" v-show="editmode" id="addNewLabel">
              Update Fee Info
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
          <form @submit.prevent="editmode ? updateReport() : CreateReport()">
            <div class="modal-body">
              <div class="form-group">
                <label>Title</label>
                <input
                  v-model="form.title"
                  type="text"
                  name="tittle"
                  placeholder=" JSS3-2020/2021 Second term Fee"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('title') }"
                />
                <has-error :form="form" field="title"></has-error>
              </div>

              <div class="form-group">
                <select
                  name="level_id"
                  id="level_id"
                  class="form-control"
                  v-model="form.feegroup_id"
                  @change="getAmount"
                >
                  <option value selected>Select Fee Group</option>
                  <option v-for="fee in reports.data" :key="fee.id" :value="fee.id">
                    {{ fee.tittle }}
                  </option>
                </select>
              </div>

              <div class="form-group">
                <input
                  v-model="form.amount"
                  type="number"
                  name="amount"
                  placeholder="amount"
                  class="form-control"
                  disabled
                  :class="{ 'is-invalid': form.errors.has('amount') }"
                />
                <has-error :form="form" field="amount"></has-error>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">
                Close
              </button>
              <button v-show="editmode" type="submit" class="btn btn-success">
                Update
              </button>
              <button v-show="!editmode" type="submit" class="btn btn-primary">
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
     import Loading from 'vue-loading-overlay';

export default {
    components:Loading,
  data() {
    return {
        isLoading: false,
      fullPage: true,
      editmode: false,
         bills:'',
      reports: {},

      form: new Form({
        id: "",
        title: "",
        feegroup_id: "",
        amount: "",
        neovastId:''
      }),
    };
  },
  mounted() {
    axios.get("/api/getAllBills").then((res) => {
      this.bills = res.data;
    });

  },

  methods: {

    updateReport(id) {
      this.$Progress.start();

      this.form
        .put("/api/fees/" + id)
        .then(() => {
          // success
          $("#addNew").modal("hide");
          swal.fire("Updated!", "Information has been updated.", "success");
          this.$Progress.finish();
          Fire.$emit("AfterCreate");
        })
        .catch(() => {
          this.$Progress.fail();
        });
    },
    editModal(group) {
      this.editmode = true;
      this.form.reset();
      $("#addNew").modal("show");
      this.form.fill(group);
    },
    newModal() {
      this.editmode = false;
      this.form.reset();
      $("#addNew").modal("show");
    },

    CreateReport() {
      this.$Progress.start();

      this.form
        .post("api/createBills")
        .then(() => {
          Fire.$emit("AfterCreate");
          $("#addNew").modal("hide");

          toast.fire({
            type: "success",
            title: "Bill created  successfully",
          });
          this.$Progress.finish();
          $("#addNew").modal("hide");
          Fire.$emit("AfterCreate");
        })
        .catch(() => {});
    },
    deleteBill(id) {
      swal
        .fire({
          title: "Are you sure?",
          text: "You won't be able to revert this!",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes, delete it!",
        })
        .then((result) => {
          // Send request to the server
          if (result.value) {
            axios
              .delete("api/bill/" + id)
              .then(() => {
                swal.fire("Deleted!", "Bill has been deleted.", "success");
                Fire.$emit("AfterCreate");
              })
              .catch(() => {
                swal.fire("Failed!", "There was something wronge.", "warning");
              });
          }
        });
    },
    loadBills() {
      if (this.$gate.isAdminOrTutorOrStudent()) {
        axios.get("/api/getAllBills").then((response) => {
          this.reports = response.data;
        });
      }
    },
      billAccounts(bill) {
           this.$Progress.start();
      if (this.$gate.isAdmin()) {
          this.form.fill(bill)
          this.form.post("/api/billAccounts")
          .then((res) => {
          //  console.log(res.data)
             swal.fire("success!", "Bill has been dispatche successfully.", "success");
            });
            this.$Progress.finish();
      }
    },




    getAmount() {
      axios.get("/api/getBillAmount/" + this.form.feegroup_id).then((res) => {
        this.form.amount = res.data;
      });
    },

    createDescription() {
      this.$Progress.start();

      this.DetailsForm.post("api/fee_description")
        .then((res) => {
          $("#detailsModal").modal("hide");

          toast.fire({
            type: "success",
            title: "Description created  successfully",
          });
          this.$Progress.finish();
          this.$router.push("/fee_description/" + res.data.feegroup_id);
        })
        .catch(() => {});
    },
  },
  created() {


    this.loadFees();
    Fire.$on("AfterCreate", () => {
      this.loadBills();
    });
    //    setInterval(() => this.loadUsers(), 3000);
  },
};
</script>

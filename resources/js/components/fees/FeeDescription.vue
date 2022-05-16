<template>
  <div>
    <div class="content-header">
      <div class="container-fluid navy">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Fee Description</h1>
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

    <div class="content" v-if="$gate.isAdminOrTutorOrStudentOrParent()">
      <div
        class="card-body pt-0"
        ref="generatePDF"
        :style="`background-image: linear-gradient(to bottom, rgba(255,255,255,0.98) 100%,rgba(255,255,255,0.98) 100%), url(/img/schools/${school.logo}) ;background-repeat: no-repeat; background-position: center;background-size: 80%;`"
      >
        <div class="card card-navy card-outline">
          <div class="card-header">
            <button
              class="btn btn-primary float-left pl-2"
              @click="printReciept"
            >
              <i class="fa fa-print"></i>Print Invoice
            </button>
            <div class="row float-right">
              <div class="card-tools">
                <button
                  v-show="$gate.isAdmin()"
                  class="btn btn-success btn-sm float-right m-2"
                  @click="newModal"
                >
                  Add New Detail <i class="fas fa-user-plus fa-fw"></i>
                </button>
              </div>
            </div>
          </div>
          <!-- /.card-header -->
          <div
            class="card-body table-responsive"
            id="print"
            ref="generatePDF"
            :style="`background-image: linear-gradient(to bottom, rgba(255,255,255,0.98) 100%,rgba(255,255,255,0.98) 100%), url(/img/schools/${school.logo}) ;background-repeat: no-repeat; background-position: center;background-size: 100%;`"
          >
            <div class="card-body row col-md-12 col-sm-12 pt-1 mt-0">
              <div class="col-md-2 col-sm-2">
                <img
                  :src="`/img/schools/${school.logo}`"
                  class="img-thumbnail result-logo"
                  alt="logo"
                  width="100"
                  height="100"
                />
              </div>
              <div class="col-md-8 col-sm-12">
                <h3 class="text-primary text-uppercase">{{ school.name }},</h3>
                <h5>{{ school.contact_address }},&nbsp; {{ school.state }}</h5>
                <h5>
                  P:&nbsp; {{ school.phone }}. &nbsp; E: {{ school.email }}
                </h5>
                <h5 class="text-red">URL:&nbsp; {{ school.website }}.</h5>
                <input type="hidden" :value="school.gateway_pk" />
              </div>
              <div class="col-md-2 col-sm-2">
                <h3 class="pt-2 text-danger pt-5">Fee Invoice</h3>
                <br />Status:<span
                  v-show="isPaid"
                  class="text-success text-uppercase"
                >
                  Paid</span
                ><span v-show="!isPaid" class="text-danger text-uppercase">
                  Un Paid</span
                >
              </div>

              <div class="row col-md-12" v-if="isStudent">
                <div class="col-md-6 py-2">
                  <h5>
                    <b>Name:</b>&nbsp;{{ student ? student.surname : "" }} ,
                    &nbsp;{{ student ? student.first_name : "" }}
                  </h5>
                  <h5>
                    <b>class:&nbsp; </b> &nbsp;
                    {{ levels ? levels.levels.level_name : "" }}
                  </h5>
                  <h5>
                    <b>Portal ID:</b>&nbsp;{{
                      student ? student.users.portal_id : ""
                    }}
                  </h5>
                </div>
                <div class="col-md-6">
                  <h4 class="text-primary text-uppercase">
                    <b
                      >&nbsp;{{
                        reports[0] ? reports[0].feegroup.tittle : ""
                      }}</b
                    >
                  </h4>
                  <hr class="text-danger mb-1" />
                  <h5>
                    <b>Term:&nbsp;</b
                    >{{ levels.terms ? levels.terms.name : "" }}
                  </h5>
                  <h5>
                    <b>Session:&nbsp;</b
                    >{{ levels ? levels.sessions.name : "" }}
                  </h5>
                </div>
              </div>
              <div class="container text-center">
                <h4
                  class="text-primary mt-5 float-left"
                  v-show="$gate.isAdmin()"
                >
                  &nbsp;{{ reports[0] ? reports[0].feegroup.tittle : "" }}
                </h4>
                <h4 class="float-right text-primary mt-5">
                  <span>Due Date: </span>&nbsp;
                  {{
                    (reports[0] ? reports[0].feegroup.due_date : "") | myDate
                  }}
                </h4>
              </div>
              <b
                ><br />
                <hr class="mt-0"
              /></b>
            </div>
            <div class="container">
              <center>
                <table class="table table-bordered pt-10" id="data_tb">
                  <tbody>
                    <tr>
                      <th>S/N</th>
                      <th>DESCRIPTION</th>

                      <th>AMOUNT</th>
                      <th class="action">Action</th>
                    </tr>

                    <tr v-for="report in reports" :key="report.id">
                      <td>{{ report.id }}</td>
                      <td>{{ report.description }}</td>
                      <td>{{ report.amount }}</td>
                      <td class="action" v-show="$gate.isAdminOrTutor()">
                        <a
                          href="#"
                          @click="editModal(report)"
                          v-show="$gate.isAdmin()"
                          class="pl-2 action"
                        >
                          <i class="fa fa-edit blue action"></i>
                        </a>

                        <a
                          href="#"
                          @click="deleteDescription(report.id)"
                          v-show="$gate.isAdmin()"
                          class="pl-2 action"
                        >
                          <i class="fa fa-trash red action"></i>
                        </a>
                      </td>
                    </tr>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th>Total</th>
                      <th>-------</th>
                      <th>N{{ total }}</th>
                    </tr>
                    <tr>
                      <th>Dicount</th>
                      <th>-------</th>
                      <th>
                        {{ levels ? levels.discount : "" }}%<b
                          v-if="levels.discount > 0"
                          >({{ (levels.discount / 100) * total }})</b
                        >
                      </th>
                    </tr>
                    <tr>
                      <th>
                        <b v-if="discounted_amount > 0">Partial Payment</b
                        ><b v-else>Full Payment</b>
                      </th>
                      <th>-------</th>
                      <th>N{{ amount_paid ? amount_paid.amount : "0.00" }}</th>
                    </tr>

                    <tr>
                      <th>Balance</th>
                      <th>-------</th>
                      <th>N{{ discounted_amount }}</th>
                      <th v-show="$gate.isStudentOrParent()">
                        Status:<span
                          v-show="isPaid"
                          class="text-success text-uppercase"
                        >
                          Paid</span
                        >
                        <span
                          v-show="!isPaid"
                          class="text-danger text-uppercase"
                        >
                          Unpaid</span
                        >
                        <br />
                        Transaction ID:
                        <span
                          v-show="isPaid"
                          class="text-danger text-uppercase"
                        >
                          {{
                            payment_details
                              ? payment_details.transaction_id
                              : ""
                          }}</span
                        >
                        <div class="action" v-show="!isPaid">
                          <paystack
                            class="btn btn-success"
                            v-show="!isPaid"
                            :amount="round(PayForm.amount * 100)"
                            :email="student ? student.users.email : email"
                            :paystackkey="
                              levels.paystacks ? levels.paystacks.paystack_key : ''
                            "
                            :reference="reference()"
                            :callback="callback"
                            :close="close"
                            :embed="false"
                          >
                            <i class="fas fa-money-bill-alt"></i>
                            Make Payment
                          </paystack>
                          <label class="pl-2"> Amount</label>
                          <input type="number" v-model="PayForm.amount" />
                        </div>
                      </th>
                    </tr>
                  </tfoot>
                </table>
              </center>
              <div class="container float-right"></div>
            </div>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
    </div>

    <div v-if="!$gate.isAdminOrTutorOrStudentOrParent()">
      <not-found></not-found>
    </div>
    <!-- Arms Modal -->

    <!-- DetailsModal -->

    <div
      class="modal fade"
      id="detailsModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="example">Add new desscription</h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form @submit.prevent="createDescription()">
            <div class="modal-body">
              <div class="form-group">
              <select class="form-control"
              v-model="DetailsForm.description"
              name="description"
              id="description"
              >
              <option value selected>Select Fee Item</option>
               <option 
               v-for="feeItem in feeItems" 
               :key="feeItem.id"
               :value="feeItem.name">
                {{ feeItem.name }}
               </option>
              </select>
              <has-error :form="DetailsForm" field="description"></has-error>
              </div>
              <div class="form-group">
                <input
                  class="form-control"
                  type="number"
                  placeholder="Enter Amount"
                  :class="{ 'is-invalid': DetailsForm.errors.has('amount') }"
                  v-model="DetailsForm.amount"
                />
                <has-error :form="DetailsForm" field="amount"></has-error>
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
              <button class="btn btn-primary">Save</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";
import paystack from "vue-paystack";
export default {
  components: {
    paystack,
  },
  computed: mapState(["school"]),

  data() {
    return {
      editmode: false,
      levels: "",
      isStudent: false,
      student: "",
      reports: [],
      amount_paid: {},
      isPaid: false,
      payment_details: "",
      total: "",
      student_id: "",
      discounted_amount: 0.0,
      user: window.user,
      expectedIncome:'',
      feeItems:[],
      paystackkey: "", //paystack public key
      email: window.user.email, // Customer email
      amount: 0, // in kobo
      form: new Form({
        id: "",
        tittle: "",
        level_id: "",
        term_id: "",
        session_id: "",
      }),

      DetailsForm: new Form({
        description: "",
        feegroup_id: "",
        amount: "",
      }),
      PayForm: new Form({
        feegroup_id: "",
        student_id: "",
        activation_status: 0,
        amount: "",
        fee: "",
        reference_id: "",
        transaction_id: "",
        message: "",
      }),
    };
  },

  methods: {
    updateReport(id) {
      this.$Progress.start();

      this.DetailsForm.put("/api/fee_description/" + id)
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
      this.DetailsForm.reset();
      $("#detailsModal").modal("show");
      this.DetailsForm.fill(group);
    },
    newModal() {
      this.editmode = false;
      this.DetailsForm.reset();
      $("#detailsModal").modal("show");
      this.DetailsForm.feegroup_id = this.$route.params.feegroup_id;
    },

    CreateReport() {
      this.$Progress.start();

      this.form
        .post("/api/fees")
        .then(() => {
          Fire.$emit("AfterCreate");
          $("#addNew").modal("hide");

          toast.fire({
            type: "success",
            title: "Student Created  successfully",
          });
          this.$Progress.finish();
          $("#addNew").modal("hide");
          Fire.$emit("AfterCreate");
        })
        .catch(() => {});
    },
    deleteDescription(id) {
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
              .delete("/api/fee_description/" + id)
              .then(() => {
                swal.fire("Deleted!", "level has been deleted.", "success");
                Fire.$emit("AfterCreate");
              })
              .catch(() => {
                swal.fire("Failed!", "There was something wronge.", "warning");
              });
          }
        });
    },
    loadFeeDescription() {
      if (this.$gate.isAdminOrTutorOrStudentOrParent()) {
        axios
          .get(
            "/api/fee_description/" +
              this.$route.params.feegroup_id +
              "/" +
              this.PayForm.student_id
          )
          .then((response) => {
            this.reports = response.data.details;
            this.total = response.data.total;
            this.amount = this.total;
            this.amount_paid = response.data.amount_paid;
            this.student = response.data.student;
            //console.log(response.data.amount_paid)
            this.PayForm.amount = response.data.discounted_amount;
            let payment_details = response.data.payment_details;
            this.payment_details = payment_details;
           // this.amount_paid=response.data.amount_paid
           // console.log()
            if (payment_details && payment_details.activation_status > 0) {
              this.isPaid = true;
            } else {
              this.isPaid = false;
            }
            // this.isPaid=payment_details?payment_details.activation_status:0;
            this.levels = response.data.feegroup;
          //   console.log(this.levels.paystacks)
            this.discounted_amount = response.data.discounted_amount;
            this.expectedIncome=response.expectedIncome
          });
      }
    },
    AddDetails(id) {
      $("#detailsModal").modal("show");
      this.DetailsForm.feegroup_id = id;
    },

    printResults() {
      window.print();
    },

    showActivationModal(id) {
      $("#activationModal").modal("show");
      this.activationForm.reset();
      this.activationForm.report_id = id;
    },

    reference() {
      let text = "";
      let possible =
        "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

      for (let i = 0; i < 10; i++)
        text += possible.charAt(Math.floor(Math.random() * possible.length));

      return text;
    },

    createDescription() {
      this.$Progress.start();

      this.DetailsForm.post("/api/fee_description")
        .then(() => {
          $("#detailsModal").modal("hide");

          toast.fire({
            type: "success",
            title: "Description created  successfully",
          });
          this.$Progress.finish();
          Fire.$emit("AfterCreate");
        })
        .catch(() => {});
    },

    round(num) {
      return Math.round((num + Number.EPSILON) * 100) / 100;
    },
    callback: function (response) {
      this.PayForm.reference_id = response.reference;
      this.PayForm.transaction_id = response.transaction;
      this.PayForm.message = response.message;
      this.PayForm.feegroup_id = this.$route.params.feegroup_id;
      //this.PayForm.amount=response.amount
      this.PayForm.fee = this.discounted_amount;
      this.PayForm.student_id = this.student.id;

      this.PayForm.post("/api/fee_pay")
        .then((res) => {
          //$('#detailsModal').modal('hide')
          console.log(res);
          toast.fire({
            type: "success",
            title: "Fee paid successfully",
          });
          this.$Progress.finish();

          Fire.$emit("AfterCreate");
        })
        .catch(() => {});
    },
    close: function () {
      console.log("Payment closed");
    },
    printReciept() {
      this.perfectPrint({
        content: `
                    <div style="font-size:10px;border-style: dashed ; border-width:0.1px;
                     width:44mm; font-family: "Times New Roman", Times, serif;">
<center>
                    <h3 >Fee Payment Reciept</h3>
                     Reciept No: ${this.amount_paid?this.amount_paid.id:'--'}
                    </center>
                    <table style="font-size:10px;width:80%; font-family: " serif;">
                    <tr>
                    <td>
                    ${this.school.name},<br/>

 ${this.school.contact_address},&nbsp;<br/>
 ${this.school.state}<br/>
Phone:&nbsp; ${this.school.phone}. <br/>
E: ${this.school.email}<br/>
URL:&nbsp; ${this.school.website}.
</td>

                    </tr>

                    </table>
                  <span>
                    <b>Name:</b>&nbsp;${ this.student ? this.student.surname : "" } ,
                    &nbsp;${ this.student ? this.student.first_name : "" }
                  </span><br/>
                  <span>
                    <b>Class:</b>&nbsp;${this.levels ? this.levels.levels.level_name : "" },
                    &nbsp;
                  </span>
                      <table style=" margin:5px;width:98%;text-align: left;">
                      <thead >
                        <tr>
                        <td>S/N</td>
                        <td>DSC</td>
                        <td>AMT(NGN) </td>
                       </tr>
                       </thead>
                       <tbody>
                       <tr>
                       <td colspan="3">
                       <hr/>
                       </td>
                       </tr>
                      ${this.createTable()}
                      <tr>
                       <td colspan="3">
                       <hr/>
                       </td>
                       </tr>
                       <tr >
                       <td>
                       EXP AMT
                       </td>
                       <td></td><td>${this.total.toFixed(2)}</td>
                       </tr>
                       <tr >
                       <td>
                       AMT PD
                       </td>
                       <td></td>
                       <td>${
                         this.amount_paid
                           ? Number(this.amount_paid.amount).toFixed(2)
                           : "0.00"
                       }</td>
                       </tr>
                       <tr >
                       <td>
                       BAL
                       </td>
                       <td></td><td>${this.discounted_amount.toFixed(2)}</td>
                       </tr>
                    </tbody>
                </table>

<center>
<p>
Thank for your patronage...
</p>
</center>
                    </div>

                    `,
      });
    },
    createTable() {
      let tr = "";
      this.reports.map((report, index) => {
        tr += `
                    <tr style="">
                    <center>
                    <td>${index + 1}</td>
                    <td>${report.description}</td>
                    <td>${(report.amount).toFixed(2)} </td>
                    </center>
                     </tr>
                      `;
      });
      return tr;
    },
    getFeeItems(){
         axios.get('/api/feeItems')
      .then(res=>{
          this.feeItems=res.data
         // console.log(res.data)
          })
    }
  },
  created() {
    this.PayForm.student_id = this.$route.params.student_id
      ? this.$route.params.student_id
      : window.user.student_id;
    this.loadFeeDescription();
    this.student_id = this.$route.params.student_id
      ? this.$route.params.student_id
      : "";
    if (this.student_id > 0 || window.user.student_id > 0) {
      this.isStudent = true;
    } else {
      this.isStudent = false;
    }
    Fire.$on("AfterCreate", () => {
      this.loadFeeDescription();
    });
    //    setInterval(() => this.loadUsers(), 3000);

    //console.log(window.user)
    this.getFeeItems();
  },
};
</script>

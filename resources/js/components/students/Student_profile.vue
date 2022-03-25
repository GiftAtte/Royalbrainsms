
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
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
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

            <p class="text-muted text-center">STUDENT</p>

            <ul class="list-group list-group-unbordered mb-3">
              <li class="list-group-item">
                <b>Level</b>
                <a class="float-right"
                  >{{ form.levels.level_name }} {{ form.arm.name }}</a
                >
              </li>
              <li class="list-group-item">
                <b>Gender</b> <a class="float-right">{{ form.gender }}</a>
              </li>
              <li class="list-group-item">
                <b>Portal ID</b>
                <a class="float-right">{{ form.users.portal_id }}</a>
              </li>
            </ul>

            <a href="#" class="btn btn-primary btn-block text-uppercase"
              ><b>{{ form.users.type }}</b></a
            >
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <div class="card card-navy">
          <div class="card-body box-profile">
            <h3 class="profile-username text-center">E-WALLET</h3>

            <p class="text-muted text-center">Account Information</p>

            <ul class="list-group list-group-unbordered mb-3">
              <li class="list-group-item">
                <b>Bank Name</b>
                <a class="float-right">{{
                  form.bankName ? form.bankName : ""
                }}</a>
              </li>
              <li class="list-group-item">
                <b>Account Number</b>
                <a class="float-right">{{ form.accountNumber }}</a>
              </li>
              <li class="list-group-item">
                <b>Account Balance</b>
                <a class="float-right"
                  >N{{ Number(accountDetails.balance).toFixed(2) }}</a
                >
              </li>
            </ul>
            <h6 class="text-danger text-uppercase">
              N/B: Transfer money into this account for settlement of your fees
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
                <a class="nav-link" href="#photo-card" data-toggle="tab"
                  >Photo Card</a
                >
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#fee-details" data-toggle="tab"
                  >Fee Details</a
                >
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#security" data-toggle="tab"
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
              <div class="container tab-pane" id="photo-card">
                <button
                  class="btn btn-primary float-right"
                  @click="printResults"
                >
                  <i class="fa fa-print"></i>
                </button>
                <div
                  class="card-body pt-0"
                  id="section-to-print"
                  ref="generatePDF"
                  :style="`background-image: linear-gradient(to bottom, rgba(255,255,255,0.98) 100%,rgba(255,255,255,0.98) 100%), url(/img/schools/${school.logo}) ;background-repeat: no-repeat; background-position: center;background-size: 80%;`"
                >
                  <div class="card-body row col-md-12 pt-1 mt-0">
                    <div class="col-md-2">
                      <img
                        :src="`/img/schools/${school.logo}`"
                        class="img-thumbnail result-logo"
                        alt="logo"
                        width="75"
                        height="75"
                      />
                    </div>
                    <div class="col-md-8">
                      <h3 class="text-primary text-uppercase">
                        {{ school.name }},
                      </h3>
                      <h5>
                        {{ school.contact_address }},&nbsp; {{ school.state }}
                      </h5>
                      <h5>
                        P:&nbsp; {{ school.phone }}. &nbsp; E:
                        {{ school.email }}
                      </h5>
                      <h5 class="text-red">URL:&nbsp; {{ school.website }}.</h5>
                    </div>
                    <div class="col-md-2 mt-3">
                      <img
                        class="profile-user-img img-fluid img-rounded"
                        width="50"
                        height="50"
                        :src="`/img/profile/${form.users.photo}`"
                        alt="User profile picture"
                      />
                    </div>
                  </div>
                  <div
                    class="container text-center text-primary text-uppercase"
                  >
                    <h4>STUDENT PHOTO CARD</h4>
                    <hr />
                  </div>

                  <div class="row container">
                    <!-- Basic Information -->
                    <div class="col-md-12 row py-1">
                      <div class="container">
                        <h4 class="text-danger text-center text-uppercase">
                          Basic Information
                        </h4>
                        <hr />
                      </div>
                      <div class="col-md-6">
                        <ul
                          class="
                            list-group list-group-unbordered
                            mb-3
                            text-uppercase
                          "
                        >
                          <li class="list-group-item">
                            <b>Surname</b>
                            <a class="float-right">{{ form.surname }} </a>
                          </li>
                          <li class="list-group-item">
                            <b>Firstname</b>
                            <a class="float-right">{{ form.first_name }} </a>
                          </li>
                          <li class="list-group-item">
                            <b>Other Name(s)</b>
                            <a class="float-right"
                              >{{
                                form.middle_name ? form.middle_name : "-----"
                              }}
                            </a>
                          </li>
                          <li class="list-group-item">
                            <b>Date Of Birth</b>
                            <a class="float-right">{{ form.dob | myDate }}</a>
                          </li>

                          <li class="list-group-item">
                            <b>Gender</b>
                            <a class="float-right">{{ form.gender }}</a>
                          </li>
                          <li class="list-group-item">
                            <b>Nationality</b>
                            <a class="float-right">{{ form.nationality }}</a>
                          </li>
                        </ul>
                      </div>

                      <div class="col-md-6">
                        <ul
                          class="
                            list-group list-group-unbordered
                            mb-3
                            text-uppercase
                            ml-2
                          "
                        >
                          <li class="list-group-item">
                            <b>State Of Origin</b>
                            <a class="float-right">{{ form.state }} </a>
                          </li>
                          <li class="list-group-item">
                            <b>LGA Of Origin</b>
                            <a class="float-right">{{ form.lga }} </a>
                          </li>
                          <li class="list-group-item">
                            <b>Home Town</b>
                            <a class="float-right">{{ form.home_town }}</a>
                          </li>
                          <li class="list-group-item">
                            <b>Religion</b>
                            <a class="float-right">{{ form.religion }}</a>
                          </li>
                          <li class="list-group-item">
                            <b>Mother Tongue</b>
                            <a class="float-right">{{ form.mother_tongue }}</a>
                          </li>
                          <li class="list-group-item">
                            <b>Other Languages</b>
                            <a class="float-right">{{ form.other_tongue }}</a>
                          </li>
                        </ul>
                      </div>
                    </div>
                    <!-- Contact Information -->
                    <div class="col-md-12 row py-1">
                      <div class="container">
                        <h4 class="text-danger text-center text-uppercase">
                          Contact Information
                        </h4>
                        <hr />
                      </div>
                      <div class="col-md-6">
                        <ul
                          class="
                            list-group list-group-unbordered
                            mb-3
                            text-uppercase
                          "
                        >
                          <li class="list-group-item">
                            <b>State Of Resident</b>
                            <a class="float-right"
                              >{{ form.state_of_resident }}
                            </a>
                          </li>
                          <li class="list-group-item">
                            <b>LGA Of Resident</b>
                            <a class="float-right"
                              >{{ form.lga_of_resident }}
                            </a>
                          </li>
                          <li class="list-group-item">
                            <b>Address</b>
                            <a class="float-right"
                              >{{
                                form.middle_name ? form.middle_name : "-----"
                              }}
                            </a>
                          </li>
                        </ul>
                      </div>

                      <div class="col-md-6">
                        <ul
                          class="
                            list-group list-group-unbordered
                            mb-3
                            text-uppercase
                            ml-2
                          "
                        >
                          <li class="list-group-item">
                            <b>Nearest Bus Stop</b>
                            <a class="float-right">{{ form.bustop }}</a>
                          </li>
                          <li class="list-group-item">
                            <b>Email Address</b>
                            <a class="float-right text-lowercase">{{
                              form.users.email
                            }}</a>
                          </li>
                          <li class="list-group-item">
                            <b>Contact Phone Number</b>
                            <a class="float-right">{{ form.phone }} </a>
                          </li>
                        </ul>
                      </div>
                    </div>

                    <div class="col-md-12 row py-1 mb-2">
                      <div class="container">
                        <h4 class="text-danger text-center text-uppercase">
                          Academic Information
                        </h4>
                        <hr />
                      </div>
                      <div class="col-md-6">
                        <ul
                          class="
                            list-group list-group-unbordered
                            mb-3
                            text-uppercase
                          "
                        >
                          <li class="list-group-item">
                            <b>Admission Date</b>
                            <a class="float-right"
                              >{{ form.admission_date | myDate }}
                            </a>
                          </li>
                          <li class="list-group-item">
                            <b>Class Of Admission</b>
                            <a class="float-right"
                              >{{ form.admission_level }}
                            </a>
                          </li>
                          <li class="list-group-item">
                            <b>Current Class</b>
                            <a class="float-right"
                              >{{
                                form.levels ? form.levels.level_name : "-----"
                              }}
                            </a>
                          </li>
                        </ul>
                      </div>

                      <div class="col-md-6">
                        <ul
                          class="
                            list-group list-group-unbordered
                            mb-3
                            text-uppercase
                            ml-2
                          "
                        >
                          <li class="list-group-item">
                            <b>Former School Attended</b>
                            <a class="float-right">{{ form.former_school }}</a>
                          </li>
                          <li class="list-group-item">
                            <b>Field Of Studies</b>
                            <a class="float-right">{{ form.course }}</a>
                          </li>
                          <li class="list-group-item">
                            <b>Registration/portal ID</b>
                            <a class="float-right text">{{
                              form.users.portal_id
                            }}</a>
                          </li>
                        </ul>
                      </div>
                    </div>

                    <!-- Parent Information -->

                    <div class="col-md-12 row py-1 mb-2">
                      <div class="container">
                        <h4 class="text-danger text-center text-uppercase">
                          Parent Information
                        </h4>
                        <hr />
                      </div>
                      <div class="col-md-6">
                        <ul
                          class="
                            list-group list-group-unbordered
                            mb-3
                            text-uppercase
                          "
                        >
                          <li class="list-group-item">
                            <b>Father's Name</b>
                            <a class="float-right">{{ form.fname }} </a>
                          </li>
                          <li class="list-group-item">
                            <b>Father's Email</b>
                            <a class="float-right text-lowercase"
                              >{{ form.femail }}
                            </a>
                          </li>
                          <li class="list-group-item">
                            <b>Phone Number</b>
                            <a class="float-right">{{ form.fphone }} </a>
                          </li>
                          <li class="list-group-item">
                            <b>Occupation</b>
                            <a class="float-right">{{ form.foccupation }}</a>
                          </li>
                        </ul>
                      </div>

                      <div class="col-md-6">
                        <ul
                          class="
                            list-group list-group-unbordered
                            mb-3
                            text-uppercase
                            ml-2
                          "
                        >
                          <li class="list-group-item">
                            <b>Mother's Name</b>
                            <a class="float-right">{{ form.mname }} </a>
                          </li>
                          <li class="list-group-item">
                            <b>Mother's Email</b>
                            <a class="float-right text-lowercase"
                              >{{ form.memail }}
                            </a>
                          </li>
                          <li class="list-group-item">
                            <b>Phone Number</b>
                            <a class="float-right">{{ form.mphone }} </a>
                          </li>
                          <li class="list-group-item">
                            <b>Occupation</b>
                            <a class="float-right">{{ form.moccupation }}</a>
                          </li>
                        </ul>
                      </div>
                    </div>

                    <div class="col-md-12 row py-1 mb-2">
                      <div class="container">
                        <h4 class="text-danger text-center text-uppercase">
                          Medical Information
                        </h4>
                        <hr />
                      </div>
                      <div class="col-md-6">
                        <ul
                          class="
                            list-group list-group-unbordered
                            mb-3
                            text-uppercase
                          "
                        >
                          <li class="list-group-item">
                            <b>Blood Group</b>
                            <a class="float-right">{{ form.blood_group }} </a>
                          </li>
                          <li class="list-group-item">
                            <b>Genotype</b>
                            <a class="float-right">{{ form.genotype }} </a>
                          </li>
                        </ul>
                      </div>

                      <div class="col-md-6">
                        <ul
                          class="
                            list-group list-group-unbordered
                            mb-3
                            text-uppercase
                            ml-2
                          "
                        >
                          <li class="list-group-item">
                            <b>Hieght</b>
                            <a class="float-right">{{ form.height }} </a>
                          </li>
                          <li class="list-group-item">
                            <b>Disability</b>
                            <a class="float-right">{{ form.disability }}</a>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
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
                        <div><span>Class/Level </span></div>
                      </th>
                      <th>Bank</th>
                      <th>Discount</th>

                      <th>Due Date</th>
                      <th>Action</th>
                    </tr>

                    <tr v-for="report in reports.data" :key="report.id">
                      <td>{{ report.id }}</td>
                      <td>{{ report.tittle }}</td>
                      <td>
                        {{ report.levels ? report.levels.level_name : "" }}
                      </td>
                      <td>
                        {{ report.paystacks ? report.paystacks.bank : "" }}
                      </td>
                      <td>{{ report.discount + "%" }}</td>
                      <td>{{ report.due_date | myDate }}</td>
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

                      <td class="row" v-show="$gate.isStudent()">
                        <router-link
                          :to="`fee_description/${report.id}`"
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
              <div class="tab-pane active show" id="profile-update">
                <div class="card-bod">
                  <form-wizard
                    @on-complete="onComplete"
                    title="Student Infomation Center"
                    subtitle="Collection/update Center"
                    color="navy"
                  >
                    <hr class="mb-2" />
                    <tab-content
                      title="Basic Information"
                      icon="fas fa-book-open"
                    >
                      <form class="form-horizontal">
                        <div class="form-group row">
                          <label for="inputName" class="col-sm-2 control-label"
                            >Surname</label
                          >

                          <div class="col-sm-10">
                            <input
                              type=""
                              v-model="form.surname"
                              class="form-control"
                              id="inputName"
                              placeholder="Name"
                              :class="{
                                'is-invalid': form.errors.has('surnname'),
                              }"
                            />
                            <has-error :form="form" field="surname"></has-error>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label
                            for="inputFirstname"
                            class="col-sm-2 control-label"
                            >Firstname</label
                          >
                          <div class="col-sm-10">
                            <input
                              type="text"
                              v-model="form.first_name"
                              class="form-control"
                              name="firstname"
                              placeholder="firstname"
                              :class="{
                                'is-invalid': form.errors.has('first_name'),
                              }"
                            />
                            <has-error :form="form" field="email"></has-error>
                          </div>
                        </div>

                        <div class="form-group row">
                          <label
                            for="inputMiddle"
                            class="col-sm-2 control-label"
                            >Middlename</label
                          >
                          <div class="col-sm-10">
                            <input
                              type="text"
                              v-model="form.middle_name"
                              class="form-control"
                              name="middle_name"
                              placeholder="Middlename"
                              :class="{
                                'is-invalid': form.errors.has('middle_name'),
                              }"
                            />
                            <has-error
                              :form="form"
                              field="middle_name"
                            ></has-error>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label
                            for="inputMiddle"
                            class="col-sm-2 control-label"
                            >Dob</label
                          >
                          <div class="col-sm-10">
                            <input
                              type="date"
                              v-model="form.dob"
                              class="form-control"
                              name="dob"
                              placeholder="dob"
                              :class="{
                                'is-invalid': form.errors.has('middle_name'),
                              }"
                            />
                            <has-error :form="form" field="dob"></has-error>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="phone" class="col-sm-2 control-label"
                            >Gender</label
                          >
                          <div class="col-sm-10">
                            <select
                              name="gender"
                              v-model="form.gender"
                              id="gender"
                              class="form-control"
                              :class="{
                                'is-invalid': form.errors.has('gender'),
                              }"
                            >
                              <option value="">Select sex</option>
                              <option value="male">Male</option>
                              <option value="female">Female</option>
                            </select>
                            <has-error :form="form" field="gender"></has-error>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="phone" class="col-sm-2 control-label"
                            >Religion</label
                          >
                          <div class="col-sm-10">
                            <select
                              name="religion"
                              v-model="form.religion"
                              id="religion"
                              class="form-control"
                              :class="{
                                'is-invalid': form.errors.has('gender'),
                              }"
                            >
                              <option value="">Select Religion</option>
                              <option value="Christianity">Christianity</option>
                              <option value="Muslim">Muslim</option>
                            </select>
                            <has-error
                              :form="form"
                              field="religion"
                            ></has-error>
                          </div>
                        </div>

                        <div class="form-group row">
                          <label for="phone" class="col-sm-2 control-label"
                            >LGA</label
                          >
                          <div class="col-sm-10">
                            <input
                              type="text"
                              v-model="form.lga"
                              class="form-control"
                              name="lga"
                              placeholder="LGA"
                              :class="{ 'is-invalid': form.errors.has('lga') }"
                            />
                            <has-error :form="form" field="lga"></has-error>
                          </div>
                        </div>

                        <div class="form-group row">
                          <label for="phone" class="col-sm-2 control-label"
                            >State</label
                          >
                          <div class="col-sm-10">
                            <input
                              type="text"
                              v-model="form.state"
                              class="form-control"
                              name="state"
                              placeholder="state of origin"
                              :class="{
                                'is-invalid': form.errors.has('middle_name'),
                              }"
                            />
                            <has-error :form="form" field="state"></has-error>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="phone" class="col-sm-2 control-label"
                            >Nationality</label
                          >
                          <div class="col-sm-10">
                            <input
                              type="text"
                              v-model="form.nationality"
                              class="form-control"
                              name="nationality"
                              placeholder="nationality"
                              :class="{
                                'is-invalid': form.errors.has('middle_name'),
                              }"
                            />
                            <has-error
                              :form="form"
                              field="nationality"
                            ></has-error>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="phone" class="col-sm-2 control-label"
                            >Mother Tongue</label
                          >
                          <div class="col-sm-10">
                            <input
                              type="text"
                              v-model="form.mother_tongue"
                              class="form-control"
                              name="nationality"
                              placeholder="eg Igbo"
                              :class="{
                                'is-invalid': form.errors.has('middle_name'),
                              }"
                            />
                            <has-error
                              :form="form"
                              field="mother_tongue"
                            ></has-error>
                          </div>
                        </div>

                        <div class="form-group row">
                          <label for="phone" class="col-sm-2 control-label"
                            >Other Tongues</label
                          >
                          <div class="col-sm-10">
                            <input
                              type="text"
                              v-model="form.other_tongue"
                              class="form-control"
                              name="nationality"
                              placeholder="English, Efik Igbo etc"
                              :class="{
                                'is-invalid': form.errors.has('middle_name'),
                              }"
                            />
                            <has-error
                              :form="form"
                              field="mother_tongue"
                            ></has-error>
                          </div>
                        </div>

                        <div class="form-group row">
                          <label for="phone" class="col-sm-2 control-label"
                            >Home Town</label
                          >
                          <div class="col-sm-10">
                            <input
                              type="text"
                              v-model="form.home_town"
                              class="form-control"
                              name="home town"
                              placeholder="home town"
                              :class="{
                                'is-invalid': form.errors.has('middle_name'),
                              }"
                            />
                            <has-error
                              :form="form"
                              field="mother_tongue"
                            ></has-error>
                          </div>
                        </div>

                        <div class="form-group row">
                          <div class="col-sm-offset-2 col-sm-12">
                            <button
                              @click.prevent="updateInfo"
                              type="submit"
                              class="btn btn-danger"
                            >
                              Update
                            </button>
                          </div>
                        </div>
                      </form>
                    </tab-content>

                    <!-- Contact -->

                    <tab-content title="Contact Information" icon="fas fa-user">
                      <!-- Medical -->

                      <div class="form-group row">
                        <label for="inputName" class="col-sm-2 control-label"
                          >State Of Resident</label
                        >

                        <div class="col-sm-10">
                          <input
                            type="text"
                            v-model="form.state_of_resident"
                            class="form-control"
                            id="inputName"
                            placeholder="Name"
                            :class="{
                              'is-invalid': form.errors.has('surnname'),
                            }"
                          />
                          <has-error
                            :form="form"
                            field="state_of_resident"
                          ></has-error>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputName" class="col-sm-2 control-label"
                          >LGA Of Resident</label
                        >

                        <div class="col-sm-10">
                          <input
                            type="text"
                            v-model="form.lga_of_resident"
                            class="form-control"
                            id="inputName"
                            placeholder="Name"
                            :class="{
                              'is-invalid': form.errors.has('surnname'),
                            }"
                          />
                          <has-error
                            :form="form"
                            field="state_of_resident"
                          ></has-error>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="inputMiddle" class="col-sm-2 control-label"
                          >Nearest Bustop</label
                        >
                        <div class="col-sm-10">
                          <input
                            type="text"
                            v-model="form.bustop"
                            class="form-control"
                            name="middle_name"
                            placeholder="Middlename"
                            :class="{
                              'is-invalid': form.errors.has('middle_name'),
                            }"
                          />
                          <has-error :form="form" field="bustop"></has-error>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="phone" class="col-sm-2 control-label"
                          >Phone</label
                        >
                        <div class="col-sm-10">
                          <input
                            type="text"
                            v-model="form.phone"
                            class="form-control"
                            name="phone"
                            placeholder="phone"
                            :class="{
                              'is-invalid': form.errors.has('middle_name'),
                            }"
                          />
                          <has-error :form="form" field="phone"></has-error>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="phone" class="col-sm-2 control-label"
                          >Contact Address</label
                        >
                        <div class="col-sm-10">
                          <input
                            type="text"
                            v-model="form.contact_adress"
                            class="form-control"
                            name="contact_adress"
                            placeholder="contact_adress"
                            :class="{
                              'is-invalid': form.errors.has('contact_adress'),
                            }"
                          />
                          <has-error :form="form" field="contact_adress"></has-error>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="phone" class="col-sm-2 control-label"
                          >Email</label
                        >
                        <div class="col-sm-10">
                          <input
                            type="text"
                            v-model="form.users.email"
                            class="form-control text-lowercase"
                            name="phone"
                            placeholder="phone"
                            disabled
                            :class="{
                              'is-invalid': form.errors.has('middle_name'),
                            }"
                          />
                          <has-error :form="form" field="phone"></has-error>
                        </div>
                      </div>

                      <div class="form-group row">
                        <div class="col-sm-offset-2 col-sm-12">
                          <button
                            @click.prevent="updateInfo"
                            type="submit"
                            class="btn btn-danger"
                          >
                            Update
                          </button>
                        </div>
                      </div>
                    </tab-content>

                    <tab-content
                      title="Academic Information"
                      icon=" fas fa-graduation-cap"
                    >
                      <div class="form-group row">
                        <label for="inputName" class="col-sm-2 control-label"
                          >Admission Date</label
                        >

                        <div class="col-sm-10">
                          <input
                            type="date"
                            v-model="form.admission_date"
                            class="form-control"
                            id="inputName"
                            placeholder="Admission date"
                            :class="{
                              'is-invalid':
                                form.errors.has('date_of_admission'),
                            }"
                          />
                          <has-error :form="form" field="surname"></has-error>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label
                          for="inputFirstname"
                          class="col-sm-2 control-label"
                          >Entry Level</label
                        >
                        <div class="col-sm-10">
                          <input
                            type="text"
                            v-model="form.admission_level"
                            class="form-control"
                            name="entry_level"
                            placeholder="Entry level"
                            :class="{
                              'is-invalid': form.errors.has('entry_level'),
                            }"
                          />
                          <has-error :form="form" field="email"></has-error>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="inputMiddle" class="col-sm-2 control-label"
                          >Current Level</label
                        >
                        <div class="col-sm-10">
                          <input
                            type="text"
                            v-model="form.levels.level_name"
                            class="form-control"
                            disabled
                            placeholder="Current Level"
                            :class="{
                              'is-invalid': form.errors.has('middle_name'),
                            }"
                          />
                          <has-error
                            :form="form"
                            field="levels.level_name"
                          ></has-error>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="phone" class="col-sm-2 control-label"
                          >Course</label
                        >
                        <div class="col-sm-10">
                          <input
                            type="text"
                            v-model="form.course"
                            class="form-control"
                            name="phone"
                            placeholder="Science/Art/Not Applicable"
                            :class="{ 'is-invalid': form.errors.has('course') }"
                          />
                          <has-error :form="form" field="phone"></has-error>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="phone" class="col-sm-2 control-label">
                          Former School</label
                        >
                        <div class="col-sm-10">
                          <input
                            type="text"
                            v-model="form.former_school"
                            class="form-control"
                            name="former_school"
                            placeholder="former school attended"
                            :class="{
                              'is-invalid': form.errors.has('former_school'),
                            }"
                          />
                          <has-error
                            :form="form"
                            field="former_school"
                          ></has-error>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="phone" class="col-sm-2 control-label"
                          >Registration ID</label
                        >
                        <div class="col-sm-10">
                          <input
                            type="text"
                            v-model="form.users.portal_id"
                            class="form-control"
                            name="lga"
                            disabled
                          />
                        </div>
                      </div>
                    </tab-content>

                    <tab-content title="Parent Infomation" icon="fas fa-user">
                      <!-- Medical -->

                      <div class="form-group row">
                        <label for="fname" class="col-sm-2 control-label"
                          >Father's Name</label
                        >

                        <div class="col-sm-10">
                          <input
                            type=""
                            v-model="form.fname"
                            class="form-control"
                            id="fname"
                            placeholder="Father's Name"
                            :class="{
                              'is-invalid': form.errors.has('surnname'),
                            }"
                          />
                          <has-error :form="form" field="fname"></has-error>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label
                          for="inputFirstname"
                          class="col-sm-2 control-label"
                          >Father's Phone</label
                        >
                        <div class="col-sm-10">
                          <input
                            type="text"
                            v-model="form.fphone"
                            class="form-control"
                            name="firstname"
                            placeholder="Father's phone number"
                            :class="{
                              'is-invalid': form.errors.has('first_name'),
                            }"
                          />
                          <has-error :form="form" field="email"></has-error>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="inputMiddle" class="col-sm-2 control-label"
                          >Father's Email</label
                        >
                        <div class="col-sm-10">
                          <input
                            type="email"
                            v-model="form.femail"
                            class="form-control"
                            name="femail"
                            placeholder="Father's Email"
                            :class="{
                              'is-invalid': form.errors.has('middle_name'),
                            }"
                          />
                          <has-error
                            :form="form"
                            field="middle_name"
                          ></has-error>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="phone" class="col-sm-2 control-label"
                          >Father's Occupation</label
                        >
                        <div class="col-sm-10">
                          <input
                            type="text"
                            v-model="form.foccupation"
                            class="form-control"
                            name="phone"
                            placeholder="Father's occupation"
                            :class="{
                              'is-invalid': form.errors.has('middle_name'),
                            }"
                          />
                          <has-error
                            :form="form"
                            field="foccupation"
                          ></has-error>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="inputName" class="col-sm-2 control-label"
                          >Mother's Name</label
                        >

                        <div class="col-sm-10">
                          <input
                            type=""
                            v-model="form.mname"
                            class="form-control"
                            id="inputName"
                            placeholder="Father's Name"
                            :class="{
                              'is-invalid': form.errors.has('surnname'),
                            }"
                          />
                          <has-error :form="form" field="fname"></has-error>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label
                          for="inputFirstname"
                          class="col-sm-2 control-label"
                          >Mother's Phone</label
                        >
                        <div class="col-sm-10">
                          <input
                            type="text"
                            v-model="form.mphone"
                            class="form-control"
                            name="firstname"
                            placeholder="mothers's phone number"
                            :class="{
                              'is-invalid': form.errors.has('first_name'),
                            }"
                          />
                          <has-error :form="form" field="memail"></has-error>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="inputMiddle" class="col-sm-2 control-label"
                          >Mother's Email</label
                        >
                        <div class="col-sm-10">
                          <input
                            type="email"
                            v-model="form.memail"
                            class="form-control"
                            name="middle_name"
                            placeholder="mother's email"
                            :class="{
                              'is-invalid': form.errors.has('middle_name'),
                            }"
                          />
                          <has-error :form="form" field="memail"></has-error>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="phone" class="col-sm-2 control-label"
                          >Mother's Occupation</label
                        >
                        <div class="col-sm-10">
                          <input
                            type="text"
                            v-model="form.moccupation"
                            class="form-control"
                            name="phone"
                            placeholder="Mother's occupation"
                            :class="{
                              'is-invalid': form.errors.has('middle_name'),
                            }"
                          />
                          <has-error
                            :form="form"
                            field="moccupation"
                          ></has-error>
                        </div>
                      </div>

                      <div class="form-group row">
                        <div class="col-sm-offset-2 col-sm-12">
                          <button
                            v-show="$gate.isAdminOrTutor()"
                            @click.prevent="updateInfo"
                            type="submit"
                            class="btn btn-danger"
                          >
                            Update
                          </button>
                        </div>
                      </div>
                    </tab-content>

                    <tab-content title="Medical Record" icon="fas fa-check">
                      <div class="form-group row">
                        <label for="phone" class="col-sm-2 control-label"
                          >Blood Group</label
                        >
                        <div class="col-sm-10">
                          <input
                            type="text"
                            v-model="form.blood_group"
                            class="form-control"
                            name="phone"
                            placeholder="phone"
                            :class="{
                              'is-invalid': form.errors.has('middle_name'),
                            }"
                          />
                          <has-error
                            :form="form"
                            field="blood_group"
                          ></has-error>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="phone" class="col-sm-2 control-label"
                          >Genotype</label
                        >
                        <div class="col-sm-10">
                          <input
                            type="text"
                            v-model="form.genotype"
                            class="form-control"
                            name="genotype"
                            placeholder="genotype"
                            :class="{ 'is-invalid': form.errors.has('lga') }"
                          />
                          <has-error :form="form" field="genotype"></has-error>
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="phone" class="col-sm-2 control-label"
                          >Average Hieght</label
                        >
                        <div class="col-sm-10">
                          <input
                            type="text"
                            v-model="form.height"
                            class="form-control"
                            name="height"
                            placeholder="Average height"
                            :class="{
                              'is-invalid': form.errors.has('middle_name'),
                            }"
                          />
                          <has-error :form="form" field="height"></has-error>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="phone" class="col-sm-2 control-label"
                          >Disability</label
                        >
                        <div class="col-sm-10">
                          <input
                            type="text"
                            v-model="form.disability"
                            class="form-control"
                            name="nationality"
                            placeholder="If none, enter none"
                            :class="{
                              'is-invalid': form.errors.has('middle_name'),
                            }"
                          />
                          <has-error :form="form" field="phone"></has-error>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="phone" class="col-sm-2 control-label"
                          >upload Record</label
                        >
                        <div class="col-sm-10">
                          <input
                            type="file"
                            class="form-control"
                            name="nationality"
                            placeholder="If none, enter none"
                            :class="{
                              'is-invalid': form.errors.has('middle_name'),
                            }"
                          />
                          <has-error :form="form" field="phone"></has-error>
                        </div>
                      </div>

                      <div class="form-group row">
                        <div class="col-sm-offset-2 col-sm-12">
                          <button
                            @click.prevent="updateInfo"
                            type="submit"
                            class="btn btn-danger"
                          >
                            Update
                          </button>
                        </div>
                      </div>
                    </tab-content>
                  </form-wizard>
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
export default {
  computed: mapState(["school"]),
  data() {
    return {
      reports: "",
      id: this.$route.params.id,
      accountDetails: "",
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
    if (this.$route.params.id) {
      axios.get("/api/profile/" + this.id).then(({ data }) => {
        console.log(data);
        this.form.fill(data);
      });
    } else {
      axios.get("/api/profile").then(({ data }) => {
        console.log(data);
        this.form.fill(data);
      });
    }
    this.getAccountBalance();
  },

  methods: {
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
        this.form.put("/api/updateStudent/" + this.id).then((res) => {
          // console.log(res.data)

          //Fire.$emit('AfterCreate');
          this.$Progress.finish();
          return true;
        });
      } else {
        this.form.put("/api/updateStudent").then((res) => {
          console.log(res.data);
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
    this.user = window.user;
  },
};
</script>

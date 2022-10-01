<template>
    <div>
        <button class="btn btn-primary float-right" @click="printResults">
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
                    <h4 class="text-primary text-uppercase">
                        {{ school.name }},
                    </h4>
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
            <div class="container text-center text-primary text-uppercase">
                <h5>Admission Form</h5>
                <hr />
            </div>

            <div class="row container">
                <!-- Basic Information -->
                <div class="col-md-12 row py-1">
                    <div class="container">
                        <h5 class="text-danger text-center text-uppercase">
                            Basic Information
                        </h5>
                        <hr />
                    </div>
                    <div class="col-md-6">
                        <ul
                            class="list-group list-group-unbordered mb-3 text-uppercase"
                        >
                            <li class="list-group-item">
                                <b>Surname</b>
                                <a class="float-right">{{ form.surname }} </a>
                            </li>
                            <li class="list-group-item">
                                <b>Firstname</b>
                                <a class="float-right"
                                    >{{ form.first_name }}
                                </a>
                            </li>
                            <li class="list-group-item">
                                <b>Other Name(s)</b>
                                <a class="float-right"
                                    >{{
                                        form.middle_name
                                            ? form.middle_name
                                            : "-----"
                                    }}
                                </a>
                            </li>
                            <li class="list-group-item">
                                <b>Date Of Birth</b>
                                <a class="float-right">{{
                                    form.dob | myDate
                                }}</a>
                            </li>

                            <li class="list-group-item">
                                <b>Gender</b>
                                <a class="float-right">{{ form.gender }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Nationality</b>
                                <a class="float-right">{{
                                    form.nationality
                                }}</a>
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-6">
                        <ul
                            class="list-group list-group-unbordered mb-3 text-uppercase ml-2"
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
                                <a class="float-right">{{
                                    form.mother_tongue
                                }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Other Languages</b>
                                <a class="float-right">{{
                                    form.other_tongue
                                }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Contact Information -->
                <div class="col-md-12 row py-1">
                    <div class="container">
                        <h5 class="text-danger text-center text-uppercase">
                            Contact Information
                        </h5>
                        <hr />
                    </div>
                    <div class="col-md-6">
                        <ul
                            class="list-group list-group-unbordered mb-3 text-uppercase"
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
                                        form.middle_name
                                            ? form.middle_name
                                            : "-----"
                                    }}
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-6">
                        <ul
                            class="list-group list-group-unbordered mb-3 text-uppercase ml-2"
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
                        <h5 class="text-danger text-center text-uppercase">
                            Academic Information
                        </h5>
                        <hr />
                    </div>
                    <div class="col-md-6">
                        <ul
                            class="list-group list-group-unbordered mb-3 text-uppercase"
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
                                        form.levels
                                            ? form.levels.level_name
                                            : "-----"
                                    }}
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-6">
                        <ul
                            class="list-group list-group-unbordered mb-3 text-uppercase ml-2"
                        >
                            <li class="list-group-item">
                                <b>Former School Attended</b>
                                <a class="float-right">{{
                                    form.former_school
                                }}</a>
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
                        <h5 class="text-danger text-center text-uppercase">
                            Parent/Guardian Information
                        </h5>
                        <hr />
                    </div>
                    <div class="col-md-6">
                        <ul
                            class="list-group list-group-unbordered mb-3 text-uppercase"
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
                                <a class="float-right">{{
                                    form.foccupation
                                }}</a>
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-6">
                        <ul
                            class="list-group list-group-unbordered mb-3 text-uppercase ml-2"
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
                                <a class="float-right">{{
                                    form.moccupation
                                }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["form", "school", "printResults"],
};
</script>

<template>
    <div class="col-md-12">
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
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-navy">
                    <div class="card-header">
                        <h3 class="card-title">Welcome Back!</h3>
                    </div>

                    <div class="card-body student-dashboard">
                        <div class="ribbon-wrapper">
                            <div class="ribbon bg-primary">Dashboard</div>
                        </div>

                        <div class="row col-md-12 pb-5">
                            <student-dashboard
                                v-if="$gate.isStudentOrParent()"
                            />
                            <admin-dashboard v-else />
                        </div>
                        <div class="col-md-12 text-bold"><hr /></div>

                        <div class="card card-navy">
                            <div class="card-header">
                                <h3 class="card-title">Calender</h3>
                            </div>
                            <div class="card-body">
                                <Fullcalendar
                                    :plugins="CalenderPlugins"
                                    :header="{
                                        left: 'title',
                                        //center:'dayGridMonth' //, timeGridWeek , timeGridDay ',
                                        right: 'prev today next',
                                    }"
                                    :weekends="true"
                                    :month="false"
                                    :selectable="true"
                                    :events="events"
                                    @eventClick="showEvent"
                                />
                            </div>
                        </div>
                        <div
                            class="modal fade"
                            id="addNew"
                            tabindex="-1"
                            role="dialog"
                            aria-labelledby="addNewLabel"
                            aria-hidden="true"
                        >
                            <div
                                class="modal-dialog modal-dialog-centered"
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
                                            Update Event
                                        </h5>
                                        <button
                                            type="button"
                                            class="close"
                                            data-dismiss="modal"
                                            aria-label="Close"
                                        >
                                            <span aria-hidden="true"
                                                >&times;</span
                                            >
                                        </button>
                                    </div>
                                    <form
                                        @submit.prevent="
                                            editmode
                                                ? updateEvent()
                                                : createEvent()
                                        "
                                    >
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <input
                                                    v-model="form.event_name"
                                                    type="text"
                                                    name="event_name"
                                                    placeholder="Event Name"
                                                    class="form-control"
                                                    :class="{
                                                        'is-invalid':
                                                            form.errors.has(
                                                                'event_name'
                                                            ),
                                                    }"
                                                />
                                                <has-error
                                                    :form="form"
                                                    field="event_name"
                                                ></has-error>
                                            </div>

                                            <div class="form-group">
                                                <input
                                                    v-model="form.start_date"
                                                    type="date"
                                                    name="start_date"
                                                    id="start_date"
                                                    placeholder="Start Date"
                                                    class="form-control"
                                                    :class="{
                                                        'is-invalid':
                                                            form.errors.has(
                                                                'start_date'
                                                            ),
                                                    }"
                                                />
                                                <has-error
                                                    :form="form"
                                                    field="start_date"
                                                ></has-error>
                                            </div>

                                            <div class="form-group">
                                                <input
                                                    v-model="form.end_date"
                                                    type="date"
                                                    name="end_date"
                                                    id="end_date"
                                                    class="form-control"
                                                    :class="{
                                                        'is-invalid':
                                                            form.errors.has(
                                                                'end_date'
                                                            ),
                                                    }"
                                                />
                                                <has-error
                                                    :form="form"
                                                    field="password"
                                                ></has-error>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button
                                                type="button"
                                                class="btn btn-info btn-sm"
                                                data-dismiss="modal"
                                            >
                                                Close
                                            </button>
                                            <button
                                                v-show="editmode"
                                                type="submit"
                                                class="btn btn-sm btn-success"
                                            >
                                                Update
                                            </button>
                                            <button
                                                class="btn btn-sm btn-danger"
                                                @click="deleteEvent"
                                                type="button"
                                            >
                                                Delete
                                            </button>
                                            <button
                                                v-show="!editmode"
                                                type="submit"
                                                class="btn btn-primary btn-sm"
                                            >
                                                Add Event
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Fullcalendar from "@fullcalendar/vue";
import DaygridPlugin from "@fullcalendar/daygrid";
import TimegridPlugin from "@fullcalendar/timegrid";
import InteractionPlugin from "@fullcalendar/interaction";
import ListPlugin from "@fullcalendar/list";
import { mapGetters, mapActions } from "vuex";
export default {
    computed: {
        school() {
            return this.$store.state.school;
        },
    },
    mounted() {
        this.loadSchool();
    },

    components: { Fullcalendar },
    data: () => {
        return {
            editmode: false,
            CalenderPlugins: [
                DaygridPlugin,
                TimegridPlugin,
                InteractionPlugin,
                ListPlugin,
            ],
            active_school: "",
            user_count: "",
            student_count: "",
            staff_count: "",
            level_count: "",

            events: "",
            indexToUpdate: "",
            form: new Form({
                id: "",
                event_name: "",
                start_date: "",
                end_date: "",
                school_id: window.user.school_id,
            }),
        };
    },

    methods: {
        ...mapGetters(["getSchool"]),
        ...mapActions(["loadSchool"]),

        editModal(event) {
            this.editmode = true;
            this.form.reset();
            $("#addNew").modal("show");
            this.form.fill(event);
        },
        newModal() {
            this.editmode = false;
            this.form.reset();
            $("#addNew").modal("show");
        },
        getEvent() {
            axios
                .get("api/event")
                .then((response) => {
                    this.events = response.data;
                    console.log(this.events);
                })
                .catch();
        },
        showEvent(arg) {
            this.editmode = true;
            this.form.reset();
            $("#addNew").modal("show");
            const { id, title, start, end } = arg.event;
            this.form.id = id;
            this.form.event_name = title;
            this.form.start_date = start;
            this.form.end_date = end;
            console.log([id, title, start, end]);
        },

        createEvent() {
            this.$Progress.start();

            this.form
                .post("api/event")
                .then(() => {
                    Fire.$emit("AfterCreate");
                    $("#addNew").modal("hide");
                    this.$Progress.finish();
                    toast.fire({
                        type: "success",
                        title: "Event Created in successfully",
                    });
                })
                .catch(() => {});
        },
        updateEvent() {
            this.$Progress.start();
            // console.log('Editing data');
            this.form
                .put("api/event/" + this.form.id)
                .then(() => {
                    // success
                    $("#addNew").modal("hide");
                    swal.fire("Updated!", "event has been updated.", "success");
                    this.$Progress.finish();
                    Fire.$emit("AfterCreate");
                })
                .catch(() => {
                    this.$Progress.fail();
                });
        },
        deleteEvent() {
            $("#addNew").modal("hide");
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
                    axios
                        .delete("api/event/" + this.form.id)
                        .then(() => {
                            swal.fire(
                                "Deleted!",
                                "Your file has been deleted.",
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
    },

    created() {
        //   console.log(user)
        //   if(this.$gate.isStudent()){
        //     this.$router.push('/reports')

        //   }

        this.getEvent();
        Fire.$on("AfterCreate", () => {
            this.getEvent();
        });
    },
};
</script>

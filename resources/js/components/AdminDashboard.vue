<template>
    <section class="section-features">
        <div class="col-md-12 row">
            <marquee behavior="scroll" direction="left" class="message-box">
                <h4 class="text-bold">
                    INFO.....!!! &nbsp;&nbsp;&nbsp; Please click on the boxes
                    below to access Information as described on it. You can also
                    access other links through the sidebar by your left.
                </h4>
            </marquee>
            <div class="col-md-12 text-primary text-bold">
                <hr />
            </div>
        </div>

        <div class="row">
            <div class="col-md-3 col-sm-12">
                <div
                    class="feature-box container"
                    @click="navigate('/reports')"
                >
                    <i class="feature-box__icon warning fa fa-users"></i>
                    <h3 class="header-tertiary" style="color: orange">USERS</h3>
                    <p>{{ user_count }} &nbsp;Active Users</p>
                </div>
            </div>

            <div class="col-md-3 col-sm-12" @click="navigate('/exams')">
                <div class="feature-box container">
                    <i
                        class="feature-box__icon primary fa fa-graduation-cap"
                    ></i>
                    <h3 class="text-primary u-margin-bottom-small">STUDENTS</h3>
                    <p>{{ student_count }} &nbsp; Active Students</p>
                </div>
            </div>

            <div class="col-md-3 col-sm-12">
                <div
                    class="feature-box container"
                    @click="navigate('/feegroup')"
                >
                    <i class="feature-box__icon success fas fa-user"></i>
                    <h3 class="text-success u-margin-bottom-small">STAFF</h3>
                    <p>{{ staff_count }} &nbsp; Active Staff</p>
                </div>
            </div>

            <div class="col-md-3 col-sm-12">
                <div
                    class="feature-box container"
                    @click="navigate('/assignments')"
                >
                    <i class="feature-box__icon danger fa fa-edit"></i>
                    <h3 class="text-danger u-margin-bottom-small">LEVELS</h3>
                    <p>{{ level_count }} &nbsp; Active Classes</p>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import Fullcalendar from "@fullcalendar/vue";
import DaygridPlugin from "@fullcalendar/daygrid";
import TimegridPlugin from "@fullcalendar/timegrid";
import InteractionPlugin from "@fullcalendar/interaction";
import ListPlugin from "@fullcalendar/list";
import { mapState, mapGetters, mapActions } from "vuex";
export default {
    computed: {
        school() {
            return this.getSchool();
        },
    },
    mounted() {},

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
            user_count: 0,
            student_count: 0,
            staff_count: 0,
            level_count: 0,

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
    mounted() {
        this.getCounts();
    },
    methods: {
        ...mapGetters(["getSchool"]),
        ...mapActions(["loadSchool"]),
        getCounts() {
            axios
                .get("/api/count/" + window.user.school_id)
                .then((response) => {
                    const {
                        user_count,
                        student_count,
                        staff_count,
                        level_count,
                    } = response.data;
                    this.user_count = user_count;
                    this.student_count = student_count;
                    this.staff_count = staff_count;
                    this.level_count = level_count;
                })
                .catch((err) => console.log(err.response.data));
        },
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
    // if (this.$gate.isStudent()) {
    //     this.$router.push("/reports");
    // }
    created() {
        this.loadSchool();

        this.getEvent();
        Fire.$on("AfterCreate", () => {
            this.getEvent();
        });
    },
};
</script>

<style>
.small-box {
    width: 100%;
}
</style>

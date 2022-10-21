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
        <div class="card">
          <div class="card-body student-dashboard">
            <div class="ribbon-wrapper">
              <div class="ribbon bg-primary">Dashboard</div>
            </div>

            <div class="col-md-12 pb-5">
              <student-dashboard v-if="$gate.isStudentOrParent()" />
              <candidate-dashboard v-if="$gate.isCandidate()" />
              <admin-dashboard v-if="$gate.isAdmin()" />
              <teachers-dashboard v-if="$gate.isSubjectOrFormTutor()" />
            </div>
            <div class="col-md-12 text-bold"><hr /></div>
            <div class="col-md-12 row">
              <div class="col-md-6">
                <div class="card card-navy card-outline">
                  <div class="card-header bg-primary">
                    <h4 class="py-2">NEWS FEED</h4>
                  </div>
                  <div class="card-body">
                    <div class="col-md-12">
                      <news-feed
                        :school="school"
                        v-for="news in news"
                        :key="news.id"
                        :news="news"
                      />
                    </div>
                  </div>
                  <div class="card-footer">
                    <router-link
                      v-if="$gate.isAdminOrTutor()"
                      to="/news"
                      class="btn btn-flat"
                      >View More <i class="fa fa-eye"></i
                    ></router-link>
                  </div>
                </div>
              </div>
              <div class="col-md-6 birthday">
                <today-calender
                  :addEvent="newModal"
                  :events="events"
                  :updateEvent="showEvent"
                />
                <div>
                  <birthday />
                </div>
                <hr />
                <div class="card birthday" v-show="false">
                  <div class="card-header bg-info">
                    <h3 class="card-title">Calender</h3>
                    <button
                      @click="newModal"
                      class="btn btn-success float-right"
                    >
                      Add Event
                    </button>
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
              <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode" id="addNewLabel">
                      Add New
                    </h5>
                    <h5 class="modal-title" v-show="editmode" id="addNewLabel">
                      Update Event
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
                    @submit.prevent="editmode ? updateEvent() : createEvent()"
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
                            'is-invalid': form.errors.has('event_name'),
                          }"
                        />
                        <has-error :form="form" field="event_name"></has-error>
                      </div>

                      <div class="form-group">
                        <input
                          v-model="form.start_date"
                          onfocus="this.type = 'date'"
                          onblur="this.type = 'text'"
                          name="start_date"
                          id="start_date"
                          placeholder="dd/mm/yyyy"
                          class="form-control"
                          :class="{
                            'is-invalid': form.errors.has('start_date'),
                          }"
                        />
                        <has-error :form="form" field="start_date"></has-error>
                      </div>

                      <div class="form-group">
                        <input
                          v-model="form.end_date"
                          onfocus="this.type = 'date'"
                          onblur="this.type = 'text'"
                          placeholder="dd/mm/yyyy"
                          name="end_date"
                          id="end_date"
                          class="form-control"
                          :class="{
                            'is-invalid': form.errors.has('end_date'),
                          }"
                        />
                        <has-error :form="form" field="password"></has-error>
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
import CandidateDashboard from "./CandidateDashboard.vue";
import Fullcalendar from "@fullcalendar/vue";
import DaygridPlugin from "@fullcalendar/daygrid";
import TimegridPlugin from "@fullcalendar/timegrid";
import InteractionPlugin from "@fullcalendar/interaction";
import ListPlugin from "@fullcalendar/list";
import { mapGetters, mapActions } from "vuex";
import Birthday from "./students/Birthday.vue";
import NewsFeed from "./NewsFeed.vue";
import moment from "moment";
import TodayCalender from "./utils/TodayCalender.vue";
import TeachersDashboard from "./TeachersDashboard.vue";
export default {
  computed: {
    school() {
      return this.$store.state.school;
    },
  },
  mounted() {
    this.loadSchool();
  },

  components: {
    Fullcalendar,
    CandidateDashboard,
    Birthday,
    NewsFeed,
    TodayCalender,
    TeachersDashboard,
  },
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

      events: [],
      news: [],
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
    axios.get("/api/news/published").then((res) => (this.news = res.data));
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
      this.events = [];
      axios
        .get("api/event")
        .then(({ data }) => {
          data.forEach((event) => {
            this.events.push({
              id: event.id,
              title: event.event_name,
              start: event.start_date,
              end: event.end_date,
            });
            console.log(this.events);
          });
        })
        .catch();
    },
    showEvent(arg) {
      console.log(arg);
      if (this.$gate.isAdminOrCandidate()) {
        this.editmode = true;
        this.form.reset();
        $("#addNew").modal("show");
        const { id, title, start, end } = arg;
        this.form.id = id;
        this.form.event_name = title;
        this.form.start_date = moment(start).format("DD/MM/YYYY");
        this.form.end_date = moment(end).format("DD/MM/YYYY");
      }
      // console.log(this.form.start_date);
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
      if (this.$gate.isAdmin()) {
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
      }
    },
    deleteEvent() {
      $("#addNew").modal("hide");
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
              .delete("api/event/" + this.form.id)
              .then(() => {
                swal.fire("Deleted!", "Your file has been deleted.", "success");
                Fire.$emit("AfterCreate");
              })
              .catch(() => {
                swal.fire("Failed!", "There was something wronge.", "warning");
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

    // axios.get("/api/news/published").then((res) => (this.newsFeed = res.data));

    this.getEvent();
    Fire.$on("AfterCreate", () => {
      this.getEvent();
    });
  },
};
</script>

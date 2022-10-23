<template>
    <div>
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                       <h5> Import History/Promotion</h5>
                    </div>
                    <div class="col ">
                         <input class="form-control" type="file" ref="file" @change="handleUpload">
                    </div>


                </div>

            </div>
            <div class="card-body">
                <Loading v-show="isLoading"></Loading>

                <div class="col-12 table-responsive mt-2">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>L/ID</th>
                                <th>Level Names</th>
                                <th>Class Teachers</th>
                                <th>Has Arms</th>
                                <th>Promoted?</th>
                                <th>Assign Arms?</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="level in history.data" :key="level.id">
                                <td>{{ level.id }}</td>
                                <td>
                                    {{
                                        level.level_name ? level.level_name : ""
                                    }}
                                </td>
                                <td v-if="level.has_arm">
                                    <router-link
                                        :to="`levels/${level.id}`"
                                        tag="a"
                                        exact
                                        >View Arms/class teachers</router-link
                                    >
                                </td>
                                <td v-else-if="level.staff">
                                    {{ level.staff.surname }}&nbsp;{{
                                        level.staff.first_name
                                    }}
                                </td>
                                <td v-else>{{ "" }}</td>
                                <td v-if="level.has_arm">yes</td>
                                <td v-else>No</td>
                                <td>
                                    <a href="#" v-show="!level.is_promoted">
                                        <i
                                            class="fa fa-times red"
                                            aria-hidden="true"
                                            title="Students have not been moved to a new level"
                                            @click="newModal(level.id)"
                                        ></i
                                    ></a>
                                    <a href="#" v-show="level.is_promoted">
                                        <i
                                            class="fa fa-check green"
                                            aria-hidden="true"
                                            title="Students have been moved to a new level"
                                        ></i
                                    ></a>
                                </td>
                                <td>
                                    <router-link
                                        :to="`/assign_arms/${level.id}`"
                                        v-show="!level.assign_arm"
                                    >
                                        <i
                                            class="fa fa-times red"
                                            aria-hidden="true"
                                            title="Students have not been assign arms"
                                        ></i
                                    ></router-link>
                                    <router-link
                                        :to="`/assign_arms/${level.id}`"
                                        v-show="level.assign_arm"
                                    >
                                        <i
                                            class="fa fa-check green"
                                            aria-hidden="true"
                                            title="Students have assign to arms"
                                        ></i
                                    ></router-link>
                                </td>

                                <td class="row">
                                    <a
                                        href="#"
                                        @click="deleteLevel(level.id)"
                                        class="pr-1 text-danger"
                                    >
                                        delete
                                    </a>
                                    /
                                    <router-link
                                        class="pl-1"
                                        :to="`/students/${level.id}`"
                                        >view students
                                    </router-link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <pagination
                        :data="history"
                        @pagination-change-page="getResults"
                    ></pagination>
                </div>
                <div>
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
                                    <h5 class="modal-title" id="addNewLabel">
                                        Promote To
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
                                <form @submit.prevent="promotStudents">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <select
                                                name="level_id"
                                                id="level_id"
                                                :class="{
                                                    'is-invalid':
                                                        form.errors.has(
                                                            'level_id'
                                                        ),
                                                }"
                                                class="form-control"
                                                v-model="form.newlevel_id"
                                            >
                                                <option value selected>
                                                    Select Level
                                                </option>
                                                <option
                                                    v-for="level in source"
                                                    :key="level.id"
                                                    :value="level.id"
                                                >
                                                    {{ level.level_name }}
                                                </option>
                                            </select>
                                            <has-error
                                                :form="form"
                                                field="level_id"
                                            ></has-error>
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
                                            type="submit"
                                            class="btn btn-success"
                                        >
                                            Promote
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-header bg-primary">
                    <h3 class="text-center text-uppercase">
                        Move Levels To History
                    </h3>
                </div>
                <div class="table-responsive">
                    <div class="row card-header mt-2">
                        <div class="col-md-6 col-sm-6 text-center">
                            <h3 class="text-bold text-uppercase">
                                Current Levels
                            </h3>
                        </div>
                        <div class="col-md-6 col-sm-6 text-center">
                            <h3 class="text-bold text-uppercase">
                                History Levels
                            </h3>
                        </div>
                    </div>

                    <DualListBox
                        class="mt-5"
                        :source="source"
                        :destination="destination"
                        label="level_name"
                        @onChangeList="onChangeList"
                    />
                </div>
                <div class="card-footer mt-5">
                    <button class="btn btn-danger" @click="setHistory">
                        submit
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import DualListBox from "dual-listbox-vue";
import "dual-listbox-vue/dist/dual-listbox.css";
import Loading from "vue-loading-overlay";

export default {
    name: "App",
    components: {
        DualListBox,
        Loading,
    },
    data() {
        return {
            source: [],
            destination: [],
            history: [],
            seleted: [],
            isLoading: false,
            form: new Form({
                level: [],
                current: "",
                history: "",
                oldlevel_id: "",
                newlevel_id: "",
            }),
        };
    },

    methods: {
        getResults(page = 1) {
            axios.get("api/level_history?page=" + page).then((response) => {
                this.history = response.data;
            });
        },
        onChangeList({ source, destination }) {
            this.source = source;
            this.destination = destination;
        },
        newModal(id) {
            this.form.oldlevel_id = id;

            $("#addNew").modal("show");
        },
        setHistory() {
            this.form.history = this.destination;
            this.form.current = this.source;
            this.isLoading = true;
            this.$router.push("/level_history");
            this.form.post("api/set_history").then((res) => {
                toast.fire({
                    type: "success",
                    title: "History Created in successfully",
                });
                // this.destination=[];
                this.isLoading = false;
                Fire.$emit("AfterCreate");
            });
        },
        loadLevel() {
            axios.get("api/transferlevels").then((res) => {
                this.source = res.data;
            });
        },
        promotStudents() {
            this.isLoading = true;
            this.form.post("api/promote_students").then((res) => {
                toast.fire({
                    type: "success",
                    title: "Students Promoted successfully",
                });
                // this.destination=[];
                $("#addNew").modal("hide");
                this.isLoading = false;
                Fire.$emit("AfterCreate");
            });
        },
        loadHistory() {
            axios.get("api/level_history").then((res) => {
                this.history = res.data;
                this.destination = res.data.data;
            });
        },


              handleUpload() {
                 const file = this.$refs.file.files[0];
                 const formData = new FormData();
                  formData.append('file', file);
                  this.isLoading=true;
                 axios.post('/api/import_students_history', formData)
                       .then(res => {
                           console.log(res.data)
                            this.isLoading=false;
                            swal.fire(
                                        'Updated!',
                                        'Uploaded Success',
                                        'success'
                                        )
                       }).catch(err => {
                          toast.fire({
                        type: 'error',
                        title: 'There was a problem importing your file'
                        })
                           this.isLoading=false;
                       })
                      ;
              }

    },
    created() {
        this.loadLevel();
        this.loadHistory();
        Fire.$on("AfterCreate", () => {
            this.loadHistory();
            this.loadLevel();
        });
    },
};
</script>

<style></style>

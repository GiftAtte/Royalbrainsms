<template>
    <app-body pageTitle="Live Class" pageSubTitle="Live Classes">
        <app-table
            :headers="tbHeaders"
            :data="tbData.data"
            :deleteAction="deleteMeeting"
            :updateAction="updateMeeting"
            :createAction="createMeeting"
            createButton="true"
            :form="form"
            action="true"
            search="true"
            title="Live Classes"
            updateTitle="Update Meeting"
            modalTitle="SCHEDULE A CLASS"
            modalSize="modal-lg"
        >
            <template #actionBtn v-if="$gate.isAdminOrTutor()">
                <div>
                    <a
                        v-if="$gate.isSuperadmin()"
                        :href="zoomUrl"
                        class="btn btn-warning float-right mx-2"
                    >
                        GET TOKEN
                    </a>
                    <a
                        v-show="false"
                        href="#"
                        class="btn btn-warning float-right mx-2"
                        @click="refreshToken"
                    >
                        REFRESH TOKEN
                    </a>
                </div>
            </template>
            <template #extra-action>
                <th class="text-danger">Meeting Date</th>
                <th class="text-danger">Action</th>
            </template>

            <template v-slot:extra-action-body="{ row }">
                <td>{{ row.meeting_date | myDate }}</td>
                <td>
                    <a
                        v-if="$gate.isOwnsMeeting(user.staff_id)"
                        title="Start Class"
                        class="btn btn-warning btn-sm"
                        :href="`/meeting/${row.id}`"
                        ><i class="fa fa-expand" aria-hidden="true"></i>
                        &nbsp;LIVE
                    </a>

                    <a
                        v-else
                        title="Start Class"
                        class="btn btn-warning btn-sm"
                        :href="`/meeting/${row.id}`"
                        ><i class="fa fa-expand" aria-hidden="true"></i>
                        &nbsp;JOIN
                    </a>
                </td>
            </template>
            <template #card-footer>
                <pagination
                    :data="tbData"
                    @pagination-change-page="paginateData"
                />
            </template>
            <template #modal-fields>
                <div class="continer row">
                    <div class="col-md-6">
                        <input-field
                            label="Title"
                            v-model="form.title"
                            id="title"
                            :form="form"
                            field="title"
                            placeholder="Enter meeting title"
                        />

                        <select-box
                            label="Participants"
                            :form="form"
                            v-model="form.level_id"
                            placeholder="Select level ID"
                            field="level_id"
                            :options="levelOptions"
                            id="level_id"
                            name="level_id"
                            optionLabel="level_name"
                            optionValue="id"
                            :change="loadArms"
                        />

                        <select-box
                            label="Arm (Optional)"
                            :form="form"
                            v-model="form.arm_id"
                            placeholder="Select Arm"
                            field="category_id"
                            :options="armOptions"
                            id="arm_id"
                            name="arm_id"
                            optionLabel="name"
                            optionValue="id"
                        />

                        <input-field
                            label="Meeting Date"
                            v-model="form.meeting_date"
                            id="meeting_date"
                            :form="form"
                            field="meeting_date"
                            type="date"
                            placeholder="Enter Meeting password"
                        />
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <div>
                                <label>Start Time</label>
                            </div>
                            <vue-timepicker
                                format="hh:mm A"
                                input-width="100%"
                                placeholder="Exam time "
                                :close-on-complete="true"
                                v-model="form.start_time"
                                :class="{
                                    'is-invalid': form.errors.has('start_time'),
                                }"
                            />
                        </div>

                        <input-field
                            label="Dudration"
                            v-model="form.duration"
                            id="duration"
                            :form="form"
                            field="duration"
                            placeholder="Enter Meeting password"
                            editable="false"
                            read-only
                        />
                        <div class="form-group">
                            <label for="">Remarks</label>
                            <textarea
                                v-model="form.remarks"
                                id=""
                                rows="4"
                                class="form-control"
                            >
                            </textarea>
                        </div>
                        <div class="form-group">
                            <label>Send SMS To Participants</label>
                            <div>
                                <toggle-button
                                    :label="true"
                                    :labels="{
                                        checked: 'YES',
                                        unchecked: 'NO',
                                    }"
                                    :height="20"
                                    :font-size="14"
                                    v-model="form.sendMessage"
                                    :color="'green'"
                                    :name="'activated'"
                                    size="large"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </app-table>
    </app-body>
</template>

<script>
import Datepicker from "vuejs-datepicker";
import VueTimepicker from "vue2-timepicker/dist/VueTimepicker.umd.min.js";
import { ZOOM_AUTHORIZE_URL } from "./ZoomConf";
import Axios from "axios";
export default {
    components: {
        VueTimepicker,
        Datepicker,
    },
    data() {
        return {
            zoomUrl: ZOOM_AUTHORIZE_URL,
            levelOptions: [],
            armOptions: [],
            tbHeaders: [
                { header: "Title", key: "title" },
                { header: "Created By", key: "creator" },
                { header: "Start Time", key: "start_time" },
            ],
            tbData: [],
            user: window.user,
            form: new Form({
                id: "",
                title: "",
                meeting_id: "",
                meeting_password: "",
                level_id: "",
                arm_id: "",
                remarks: "",
                meeting_date: "",
                start_time: "",
                duration: "40mins",
                created_by: "",
                school_id: "",
            }),
        };
    },
    methods: {
        refreshToken() {
            axios.post("/api/livestream/updateToken").then((res) => {});
        },
        paginateData(page = 1) {
            axios
                .get(`/api/livestream/page?=${page}`)
                .then((res) => {
                    this.tbData = res.data;
                    // setTimeout(() => {
                    //      this.componentKey += 1;
                    // },200)
                })
                .catch((err) => console.log(err));
        },

        loadLevels() {
            axios.get("api/get_levels").then((res) => {
                this.levelOptions = res.data;
            });
        },
        loadArms() {
            this.armOptions = [];
            axios
                .get("/api/getLiveStreamArms/" + this.form.level_id)
                .then((res) => {
                    res.data.forEach((val) => {
                        this.armOptions.push(val.arms);
                    });
                    //  this.armOptions = res.data;
                });
        },
        getZoomToken() {
            axios.get("/api/zoomOauthSign").then((res) => {
                console.log(res.data);
                swal.fire("success!", " Token updated successfully", "success");
            });
        },
        loadMeetings() {
            axios
                .get("/api/livestream")
                .then((res) => {
                    this.tbData = res.data;
                    // setTimeout(() => {
                    //      this.componentKey += 1;
                    // },200)
                })
                .catch((err) => console.log(err));
        },

        createMeeting() {
            this.form.post("/api/livestream").then((res) => {
                if (res.data.code === 124) {
                    swal.fire("error!", " Access Token Expired", "error");
                } else {
                    $("#appModal").modal("hide");
                    swal.fire("success!", " Meeting  successfully.", "success");

                    Fire.$emit("afterCreated");
                }
            });
        },

        updateMeeting() {
            this.form.put("/api/livestream").then((res) => {
                $("#appModal").modal("hide");
                swal.fire(
                    "success!",
                    " Meeting updated successfully.",
                    "success"
                );

                Fire.$emit("afterCreated");
            });
        },
        deleteMeeting(id) {
            axios
                .delete("/api/livestream/" + id)
                .then((res) => Fire.$emit("afterCreated"));
        },
    },

    created() {
        this.loadLevels();
        this.loadMeetings();
        Fire.$on("afterCreated", () => {
            this.loadMeetings();
        });
    },
};
</script>

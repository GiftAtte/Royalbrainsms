<template>
    <app-body pageTitle="Attendance" pageSubTitle="Daily Attendance">
        <div class="card card-outline card-navy">
            <div class="card-header"></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <select-input
                            :change="loadArms"
                            field="report_id"
                            :form="form"
                            id="report_id"
                            label="Report/Level"
                            name="report_id"
                            optionLabel="title"
                            optionValue="id"
                            :options="reports"
                            placeholder="Select Report Level"
                            v-model="form.report_id"
                        />
                    </div>
                    <div class="col-md-4">
                        <select-input
                            field="arm_id"
                            :form="form"
                            id="arm_id"
                            label="Report/Level"
                            name="arm_id"
                            optionLabel="name"
                            optionValue="id"
                            :options="arms"
                            placeholder="Select arm"
                            v-model="form.arm_id"
                        />
                    </div>
                    <div class="col-md-4">
                        <input-field
                            label="Attendance Date"
                            v-model="form.attendance_date"
                            id="attendance_date"
                            :form="form"
                            field="attendance_date"
                            placeholder=" "
                            type="date"
                        />
                    </div>

                    <div class="col-md-12 row">
                        <div class="col-md-6 col-xs-12">
                            <button
                                class="btn btn-warning btn-flat btn-lg col-md-12"
                                @click="getAttendanceCount"
                            >
                                <i class="fa fa-eye mx-1"></i>
                                VIEW
                            </button>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <button
                                class="btn btn-primary btn-flat btn-lg col-md-12"
                                @click="loadAttendance"
                            >
                                <i class="fa fa-plus-circle mx-1"></i>
                                NEW
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mx-2">
                <attendance-new
                    :form="form"
                    :attendance="attendance"
                    v-if="isUpdate"
                />
                <attendance-view
                    v-if="isView"
                    :numPresent="numPresent"
                    :numAbsent="numAbsent"
                    :attendanceList="attendanceList"
                    :form="form"
                />
            </div>
        </div>
    </app-body>
</template>

<script>
import SelectInput from "../utils/SelectInput.vue";
import AttendanceNew from "./AttendanceNew.vue";
import AttendanceView from "./AttendanceView.vue";
export default {
    components: { SelectInput, AttendanceNew, AttendanceView },
    data() {
        return {
            reports: [],
            arms: [],
            attendance: [],
            isUpdate: false,
            isView: false,
            numPresent: 0,
            numAbsent: 0,
            monthlyAttenance: [],
            attendanceList: [],
            form: new Form({
                level_id: "",
                arm_id: "",
                isPresent: 0,
                report_id: "",
                attendance_date: "",
                attendance: [],
                month: "",
                year: "",
            }),
        };
    },
    created() {
        axios
            .get("/api/load_report")
            .then((result) => {
                this.reports = result.data;
            })
            .catch((err) => {});
    },
    methods: {
        loadArms() {
            axios.get("/api/getArms/" + this.form.report_id).then((res) => {
                res.data.forEach((arms) => {
                    this.arms.push({
                        id: arms.arms.id,
                        name: arms.arms.name,
                    });
                });
            });
        },

        viewAttendance() {
            this.form.post("/api/attendance/specific").then((res) => {
                this.numPresent = res.data.numPresent;
                this.numAbsent = res.data.numAbsent;
                this.attendanceList = res.data.studentAttendance;

                this.isUpdate = false;
                this.isView = true;
            });
        },
        getAttendanceCount() {
            this.form.post(`/api/getAttendanceCount`).then((res) => {
                const { numPresent, numAbsent } = res.data;
                this.numPresent = numPresent;
                this.numAbsent = numAbsent;
                this.isUpdate = false;
                this.isView = true;
                this.attendanceList = res.data.studentAttendance;
            });
        },
        loadAttendance() {
            this.attendance = [];
            this.form.post("/api/loadAttendance").then((res) => {
                this.attendance = res.data;
                this.isUpdate = true;
                this.isView = false;
            });
        },
    },
};
</script>

<style scoped>
.btn-flat {
    flex: 1;
}
</style>

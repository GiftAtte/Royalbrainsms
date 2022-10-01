<template>
    <div>
        <div class="content-header">
            <div class="container-fluid navy">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Report</h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="#">Home</a>
                            </li>
                            <li class="breadcrumb-item active">Report Group</li>
                        </ol>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>

        <div class="content" v-if="$gate.isAdminOrTutor()">
            <div class="col-12">
                <div class="card card-navy card-outline">
                    <div class="card-header">
                        <export-excel
                            v-if="masterSheet.length"
                            class="btn btn-primary"
                            name="masterheet"
                            :data="masterSheet"
                        >
                            Download Excel
                            <i class="fa fa-download"></i>
                        </export-excel>
                        <h2
                            class="text-center text-danger"
                            v-if="!reports.length && !masterSheet.length"
                        >
                            Loading........
                        </h2>
                        <button
                            class="btn btn-primary float-right pl-2"
                            @click="printResults"
                        >
                            <i class="fa fa-print"></i>Print Result
                        </button>
                    </div>
                    <div
                        class="card-body pt-0"
                        id="section-to-print"
                        ref="generatePDF"
                        :style="`background-image: linear-gradient(to bottom, rgba(255,255,255,0.98) 100%,rgba(255,255,255,0.98) 100%), url(/img/schools/${school.logo}) ;background-repeat: no-repeat; background-position: center;background-size: 80%;`"
                    >
                        <div
                            class="card-body row col-md-12 col-sm-12 pt-1 mt-0"
                        >
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
                                <h3 class="text-primary text-uppercase">
                                    {{ school.name }},
                                </h3>
                                <h5>
                                    {{ school.contact_address }},&nbsp;
                                    {{ school.state }}
                                </h5>
                                <h5>
                                    P:&nbsp; {{ school.phone }}. &nbsp; E:
                                    {{ school.email }}
                                </h5>
                                <h5 class="text-red">
                                    URL:&nbsp; {{ school.website }}.
                                </h5>
                            </div>
                            <div class="col-md-2 col-sm-2">
                                <h5 class="pt-2 text-danger">
                                    Results Master Sheet
                                </h5>
                            </div>
                        </div>
                        <div
                            class="container text-center text-primary text-uppercase"
                        >
                            <h4 v-if="reports">
                                {{ reports.terms.name }} RESULTS MASTER SHEET
                                FOR {{ reports.levels.level_name }}
                                {{ $route.params.arm_id ? arm : "" }},
                                {{ reports.sessions.name }} Academic Session
                            </h4>
                        </div>
                        <div class="col-md-12">
                            <table
                                class="table table-sm font-weight-bold table-bordered m-2 text-center table-striped mt-2 table-info"
                            >
                                <tbody>
                                    <tr>
                                        <th rowspan="3">
                                            Short<br />
                                            Keys
                                        </th>
                                        <th>TL</th>
                                        <th>AS</th>
                                        <th>AP</th>
                                        <th>CP</th>
                                    </tr>
                                    <tr>
                                        <td>Total Scores</td>
                                        <td>Average Scores</td>
                                        <td>Arm Position</td>
                                        <td>Class Position</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-header -->
                        <div class="table-responsive">
                            <cummulative-master
                                v-if="reports.isCummulative"
                                :reports="reports"
                                :subjects="subjects"
                                :results="results"
                                :students="students"
                                :marks="marks"
                            />

                            <terminal-master
                                v-else
                                :reports="reports"
                                :subjects="subjects"
                                :results="results"
                                :students="students"
                                :marks="marks"
                            />
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>

            <div v-if="!$gate.isAdminOrTutor()">
                <not-found></not-found>
            </div>
            <!-- Arms Modal -->
        </div>
    </div>
</template>

<script>
import jspdf from "jspdf";
import html2canvas from "html2canvas";
import CummulativeMaster from "./CummulativeMaster.vue";
import TerminalMaster from "./TerminalMaster.vue";
import { mapState } from "vuex";
export default {
    components: {
        CummulativeMaster,
        TerminalMaster,
    },
    computed: mapState(["school"]),
    data() {
        return {
            subjects: "",
            reports: "",
            arm: "",
            students: "",
            marks: "",
            results: "",
            masterSheet: [],
            json_fields: {
                StudentID: "masterSheet.id",
                name: "masterSheet.name",
                field: "subjects",
            },
        };
    },

    mounted() {
        // $('#data_tb').DataTable();
    },
    methods: {
        exportMaster() {
            axios
                .get(
                    `/api/export_master/
       ${this.$route.params.report_id}
       /${this.$route.params.arm_id ? this.$route.params.arm_id : ""}`
                )
                .then((result) => {
                    this.masterSheet = result.data.mastersheet;
                    this.arm = result.data.mastersheet[0]["CLASS ARM"];
                    // console.log(result.data);
                })
                .catch((err) => {});
        },

        printResults() {
            window.print();
        },
        masterCard() {
            axios
                .get(
                    `/api/master/
       ${this.$route.params.report_id}
       /${this.$route.params.arm_id ? this.$route.params.arm_id : ""}`
                )
                .then((res) => {
                    this.students = res.data.students;
                    this.subjects = res.data.subjects;
                    this.reports = res.data.report;
                    this.marks = res.data.marks;
                    this.results = res.data.results;
                });
        },
    },
    created() {
        this.masterCard();
        this.exportMaster();

        Fire.$on("searching", () => {
            let query = this.$parent.search;
            axios
                .get("api/findStudent?q=" + query)
                .then((data) => {
                    this.levels = data.data;
                })
                .catch(() => {});
        });

        //    setInterval(() => this.loadUsers(), 3000);
    },
};
</script>

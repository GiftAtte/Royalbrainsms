<template>
    <div>
        <div class="content col-md-12">
            <div class="card card-navy card-outline">
                <div class="card-header">
                    <h5 :class="`header text-danger text-uppercase `">
                        Result sheet for {{ summary.student.surname }}&nbsp;
                        {{ summary.student.first_name }}, &nbsp;
                        {{ report.title }}
                    </h5>
                    <button
                        v-show="$gate.isAdminOrStudent()"
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
                    <div class="card-body row col-md-12 pt-1 mt-0">
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
                            <h5 class="pt-2 text-danger">Results Sheet</h5>
                        </div>
                    </div>
                    <div>
                        <div class="col col-md-2 float-right">
                            <center>
                                <image-loader
                                    :src="`/img/profile/${user.photo}`"
                                    placeholder="/img/profile.png"
                                    width="100px"
                                    height="100px"
                                    class="img-thumbnail img-result"
                                />
                            </center>
                        </div>
                    </div>
                    <div class="col col-12 py-2">
                        <div class="row col-md-10">
                            <div class="col-md-6 py-2">
                                <h5>
                                    <b>Name:</b>&nbsp;
                                    {{ summary.student.surname }}&nbsp;
                                    {{ summary.student.first_name }} &nbsp;
                                    {{
                                        summary.student.middle_name
                                            ? summary.student.middle_name
                                            : ""
                                    }}
                                </h5>
                                <h5>
                                    <b>class:&nbsp;</b>
                                    {{ report.levels.level_name }}&nbsp;{{
                                        arm.name
                                    }}
                                </h5>
                                <h5>
                                    <b>Gender:&nbsp;</b>
                                    {{
                                        summary.student.gender
                                            ? summary.student.gender
                                            : "-----------"
                                    }}
                                </h5>
                                <h5>
                                    <b>Dob:</b>&nbsp;
                                    {{
                                        summary.student.dob
                                            ? dob
                                            : "-------------"
                                    }}
                                </h5>
                                <h5>
                                    <b>Portal ID:</b>&nbsp; {{ user.portal_id }}
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <h4 class="text-primary text-uppercase">
                                    <b>&nbsp;</b>
                                    {{ report.header ? report.header : "" }}
                                </h4>
                                <hr class="text-danger mb-1" />
                                <h5>
                                    <b>Term:&nbsp;</b> {{ report.terms.name }}
                                </h5>
                                <h5>
                                    <b>Session:&nbsp;</b>
                                    {{ report.sessions.name }}
                                </h5>
                                <h5>
                                    <b>Next Term Begins:&nbsp;</b>
                                    {{
                                        report.next_term
                                            ? report.next_term
                                            : "---------"
                                    }}
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 row py-3">
                        <div class="col-md-7 col-sm-7">
                            <div class="row">
                                <table
                                    class="table table-bordered table-sm font-weight-bold table-striped"
                                >
                                    <thead>
                                        <tr class="text-center">
                                            <th>S/N</th>
                                            <th>
                                                COGNITIVE SKILLS<br />SUBJECTS
                                            </th>
                                            <th>CA</th>
                                            <th>EXAMS</th>
                                            <th>TOTAL</th>
                                            <th>GRADE</th>
                                            <th>REMARKS</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr
                                            v-for="(score, index) in scores"
                                            :key="index"
                                        >
                                            <td>{{ index + 1 }}</td>
                                            <td>
                                                {{
                                                    score.subjects
                                                        ? score.subjects.name
                                                        : ""
                                                }}
                                            </td>
                                            <td>
                                                {{
                                                    score.test1
                                                        ? score.test1
                                                        : "-"
                                                }}
                                            </td>
                                            <td>
                                                {{
                                                    score.exams
                                                        ? score.exams
                                                        : "-"
                                                }}
                                            </td>
                                            <td>
                                                {{
                                                    score.total
                                                        ? score.total
                                                        : "-"
                                                }}
                                            </td>
                                            <td>
                                                {{
                                                    score.grade
                                                        ? score.grade
                                                        : "-"
                                                }}
                                            </td>
                                            <td>
                                                {{
                                                    score.narration
                                                        ? score.narration
                                                        : "-"
                                                }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <summary-table
                                        :summary="summary"
                                        :report="report"
                                    />
                                </div>

                                <div class="row">
                                    <general-grading
                                        :grades="grades"
                                        :report="report"
                                        :attendance="attendance"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 col-sm-5">
                            <comments-table :scores="commentTableData" />
                            <div class="py-2">
                                <attendance :attendance="attendance" />
                            </div>
                        </div>
                    </div>

                    <div class="card-body row">
                        <div v-if="report.isPrincipalComment" class="row col-6">
                            <span
                                ><b>Principal's/Head Teacher's Comment:&nbsp;</b
                                >{{
                                    principal_comment ? principal_comment : ""
                                }}</span
                            >
                        </div>
                        <div v-if="report.isTeacherComment" class="row col-6">
                            <span
                                ><b>Teacher's Comment:&nbsp;</b
                                >{{ staff_comment ? staff_comment : "" }}</span
                            >
                        </div>
                    </div>
                    <center>
                        <div class="text-center">
                            <span
                                ><b>Authorized Signature:&nbsp;</b
                                ><img
                                    :src="`/img/signatures/${signature.photo}`"
                                    class="ml-2 img-result"
                                    width="100"
                                    height="50"
                                    onerror="this.style.display='none'"
                            /></span>
                        </div>
                    </center>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
// import jspdf from "jspdf";
// import html2canvas from "html2canvas";
import { mapState } from "vuex";
import LearningDomain from "../admin/LearningDomain.vue";
import Attendance from "./Attendance.vue";
export default {
    components: { LearningDomain, Attendance },
    computed: mapState(["school"]),
    data() {
        return {
            summary: "",
            scores: {},
            noneAcademic: "",
            user: {},
            comment: "",
            Total: {},
            report: {},
            LDomain: {},
            arm: {},
            grades: {},
            signature: "",
            principal_comment: "",
            staff_comment: "",
            commentTableData: [],
            attendance: {},
        };
    },
    mounted() {},
    methods: {
        printResults() {
            window.print();
        },
    },
    created() {
        const student_id = this.$route.params.student_id;
        const report_id = this.$route.params.report_id;
        this.$Progress.start();
        if (this.$route.params.student_id) {
            axios
                .get(`/api/result/${report_id}/${student_id}`)
                .then((result) => {
                    if (result.data.Not_found) {
                        // this.$router.push('/reports');
                        this.$router.push("/not-found");
                    }
                    //console.log(result.data);
                    this.scores = result.data.scores;
                    //console.log(this.scores[0])
                    this.summary = result.data.summary;
                    this.user = result.data.user;
                    this.Total = result.data.pastTotal;
                    this.comment = result.data.comment;
                    this.report = result.data.report;
                    this.arm = result.data.arm;
                    this.grades = result.data.gradings;
                    this.principal_comment = result.data.principal_comment;
                    this.staff_comment = result.data.staff_comment;
                    this.commentTableData = result.data.crecheComment;

                    this.signature = result.data.signature;
                    this.noneAcademic = result.data.noneAcademic;
                    this.LDomain = result.data.LDomain;
                    this.attendance = result.data.attendance;
                    console.log("result.data.Scores", this.commentTableData);

                    this.$Progress.finish();
                })
                .catch((err) => {});
        } else {
            axios
                .get(`/api/result/${report_id}`)
                .then((result) => {
                    if (result.data.Not_found) {
                        const message = "sorry! No results found";
                        // this.$router.push('/reports');
                        this.$router.push("/not-found");
                    }
                    console.log(result.data);
                    this.scores = result.data.scores;
                    this.summary = result.data.summary;
                    this.user = result.data.user;
                    this.Total = result.data.pastTotal;
                    this.comment = result.data.comment;
                    this.report = result.data.report;
                    this.arm = result.data.arm;
                    this.commentTableData = result.data.crecheComment;
                    //console.log(result.data.Scores)
                    this.principal_comment = result.data.principal_comment;
                    this.staff_comment = result.data.staff_comment;
                    console.log("result.data.Scores", this.commentTableData);

                    this.signature = result.data.signature;
                    this.LDomain = result.data.LDomain;
                    this.noneAcademic = result.data.noneAcademic;
                    this.attendance = result.data.attendance;
                    this.$Progress.finish();
                    // console.log(this.signature);
                    //console.log(this.report);
                })
                .catch((err) => {});
        }
    },
};
</script>

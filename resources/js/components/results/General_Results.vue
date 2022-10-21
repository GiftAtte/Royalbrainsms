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
          :style="`background-image: linear-gradient(to bottom, rgba(255,255,255,0.98) 100%,rgba(255,255,255,0.98) 100%), url(/img/schools/${school.logo})
                    ;background-repeat: no-repeat; background-position: top;background-size: 100%;`"
        >
          <result-header
            :report="report"
            :school="school"
            v-if="report.type === 'blue_ridge'"
          />
          <div class="card-body row col-md-12 pt-1 mt-0" v-else>
            <div class="col-md-2 col-sm-3">
              <img
                :src="`/img/schools/${school.logo}`"
                class="img-thumbnail result-logo"
                alt="logo"
                width="100%"
              />
            </div>

            <div
              class="col-md-8 col-sm-12"
              v-if="report.type === 'navy-template'"
            >
              <h3 class="text-success">{{ school.name }},</h3>
              <h5 class="text-danger">
                {{ school.contact_address }},&nbsp;
                {{ school.state }}
              </h5>
              <h5>
                P:&nbsp; {{ school.phone }}. &nbsp; E:
                {{ school.email }}
              </h5>
              <h5>URL:&nbsp; {{ school.website }}.</h5>
            </div>

            <div class="col-md-8 col-sm-12" v-else>
              <h3 class="text-primary text-uppercase">{{ school.name }},</h3>
              <h5>
                <i class="fa fa-home"></i>:&nbsp;
                {{ school.contact_address }},&nbsp;
                {{ school.state }}
              </h5>
              <h5>
                <i class="fa fa-phone"></i>:&nbsp; {{ school.phone }}. &nbsp;
                <i class="fa fa-envelope"></i>:
                {{ school.email }}
              </h5>
              <h5 class="text-red">
                <i class="fa fa-globe"></i>:&nbsp; {{ school.website }}.
              </h5>
            </div>
            <div class="col-md-2 col-sm-2">
              <h5 class="pt-2 text-danger">Result Sheet</h5>
            </div>
          </div>
          <div class="col-md-12">
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
                  <h5 class="text-uppercase">
                    <b class="text-capitalize">Name:</b>&nbsp;
                    {{ summary.student.surname }}&nbsp;
                    {{ summary.student.first_name }} &nbsp;
                    {{
                      summary.student.middle_name
                        ? summary.student.middle_name
                        : ""
                    }}
                  </h5>
                  <h5>
                    <b>Class:&nbsp;</b>
                    {{ report.levels.level_name }}&nbsp;{{ arm.name }}
                  </h5>
                  <h5 v-if="report.isGender">
                    <b>Gender:&nbsp;</b>
                    {{
                      summary.student.gender
                        ? summary.student.gender
                        : "-------------"
                    }}
                  </h5>
                  <h5 v-if="report.isDob">
                    <b>Date of Birth:</b>&nbsp;
                    {{
                      summary.student.dob
                        ? summary.student.dob
                        : "-------------"
                    }}
                  </h5>
                  <h5>
                    <b>Pupil's ID:</b>&nbsp;
                    {{ user.portal_id }}
                  </h5>
                </div>
                <div class="col-md-6">
                  <h4 class="text-primary text-uppercase">
                    <b>&nbsp;</b>
                    {{ report.header ? report.header : "" }}
                  </h4>
                  <hr class="text-danger mb-1" />
                  <h5 v-if="report.type === 'blue_ridge'">
                    <b class="text-capitalize"
                      >{{ report.terms.name }}:&nbsp;</b
                    >

                    {{ report.term_start | myDate }}
                    - {{ report.term_end | myDate }}
                  </h5>
                  <h5 v-else>
                    <b class="text-capitalize">Term:&nbsp;</b>
                    {{ report.terms.name }}
                  </h5>

                  <h5>
                    <b>Session:&nbsp;</b>
                    {{ report.sessions.name }}
                  </h5>
                  <h5>
                    <b>Next Term Begins:&nbsp;</b>
                    <span v-if="report.next_term">{{
                      report.next_term | myDate
                    }}</span>
                    <span v-else>{{ "-----------" }}</span>
                  </h5>
                </div>
              </div>
            </div>
          </div>
          <div>
            <general-score
              v-if="
                report.type === 'default-result' ||
                report.type === 'navy-template'
              "
              :scores="scores"
              :report="report"
              :noneAcademic="noneAcademic"
              :getPastTotal="getPastTotal"
            />
            <blueridge
              v-if="report.type === 'blue_ridge'"
              :grades="grades"
              :scores="scores"
              :LDomain="LDomain"
              :summary="summary"
              :attendance="attendance"
              :avgReport="avgReport"
              :signature="signature"
              :principal_comment="principal_comment"
              :staff_comment="staff_comment"
              :school="school"
              :report="report"
            />
          </div>

          <div class="col-md-12 row py-1" v-if="report.type !== 'blue_ridge'">
            <div class="container">
              <general-grading
                :grades="grades"
                :report="report"
                :attendance="attendance"
              />
            </div>
            <div class="container">
              <attendance
                v-if="report.isAttendance && attendance"
                :attendance="attendance"
              />
            </div>
          </div>

          <div class="card-body row" v-if="report.type !== 'blue_ridge'">
            <div v-if="report.isPrincipalComment" class="row col-6">
              <span
                ><b>Principal's/Head Teacher's Comment:&nbsp;</b
                >{{ principal_comment ? principal_comment : "" }}</span
              >
            </div>
            <div v-if="report.isTeacherComment" class="row col-6">
              <span
                ><b>Teacher's Comment:&nbsp;</b
                >{{ staff_comment ? staff_comment : "" }}</span
              >
            </div>
          </div>
          <center v-if="report.type !== 'blue_ridge'">
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
import Blueridge from "./Blueridge.vue";
import GeneralScore from "./GeneralScore.vue";
import ResultHeader from "./ResultHeader.vue";
export default {
  components: {
    LearningDomain,
    Attendance,
    Blueridge,
    GeneralScore,
    ResultHeader,
  },
  computed: mapState(["school"]),

  data() {
    return {
      summary: "",
      scores: {},
      noneAcademic: "",
      avgReport: [],
      user: {},
      comment: "",
      Total: [],
      report: {},
      LDomain: {},
      arm: {},
      grades: {},
      signature: "",
      principal_comment: "",
      staff_comment: "",
      config_settings: [],
      isThird_term: false,
      isAHScore: false,
      isASAScore: false,
      isASPosition: false,
      isCAPosition: false,
      isCAScore: false,
      isCPosition: false,
      isCSHScore: false,
      isCSLScore: false,
      isASLScore: false,
      isCSPosition: false,
      isGFormula: false,
      isLDomain: false,
      isPStatus: false,
      isRSummary: false,
      NoSetting: false,
      isTest1: true,
      isTest2: false,
      isTest3: false,
      isPComment: false,
      isTComment: false,
      isCummulative: false,
      isMidterm: false,
      subjectsDropped: [],
      attendance: {},
    };
  },
  mounted() {
    axios
      .get("/api/result_config")
      .then((result) => {
        let data = result.data;
        data.forEach((element) => {
          if (element.name === "Show Grading Formula") {
            if (element.status === 1) {
              this.isGFormula = true;
            }
          }
          if (element.name === "Show Class Position") {
            if (element.status === 1) {
              this.isCPosition = true;
            }
          }
          if (element.name === "Show Class Arm Position") {
            if (element.status === 1) {
              this.isCAPosition = true;
            }
          }
          if (element.name === "Show Result Summary") {
            if (element.status === 1) {
              this.isRSummary = true;
            }
          }
          if (element.name === "Show Progress Status") {
            if (element.status === 1) {
              this.isPStatus = true;
            }
          }
          if (element.name === "Show Class Subject Position") {
            if (element.status === 1) {
              this.isCSPosition = true;
            }
          }
          if (element.name === "Show Class Subject Highest Score") {
            if (element.status === 1) {
              this.isCSHScore = true;
            }
          }
          if (element.name === "Show Class Subject Lowest Score") {
            if (element.status === 1) {
              this.isCSLScore = true;
            }
          }
          if (element.name === "Show Class Average Score") {
            if (element.status === 1) {
              this.isCAScore = true;
            }
          }
          if (element.name === "Show Arm Subject Highest Score") {
            this.isAHScore = true;
          }
          if (element.name === "Show Arm Subject Average Score") {
            if (element.status === 1) {
              this.isASAScore = true;
            }
          }
          if (element.name === "Show Grading Formula") {
            if (element.status === 1) {
              this.isGFormula = true;
            }
          }
          if (element.name === "Show Learning Domain") {
            if (element.status === 1) {
              this.isLDomain = true;
            }
          }
          if (element.name === "Show Arm Subject Position") {
            if (element.status === 1) {
              this.isASPosition = true;
            }
          }
          if (element.name === "Show Arm Subject Lowest Score") {
            if (element.status === 1) {
              this.isASLScore = true;
            }
          }
          //     if(element.name==='Use Cummulative For Third Term') {
          //       if(element.status===1){
          //        this.isCummulative=true
          //          }

          //    }

          if (element.name === "Show Test2") {
            if (element.status === 1) {
              this.isTest2 = true;
            }
          }
          if (element.name === "Show Test3") {
            if (element.status === 1) {
              this.isTest3 = true;
            }
          }
          if (element.name === "Show Principal's Comment") {
            if (element.status === 1) {
              this.isPComment = true;
            }
          }
          if (element.name === "Show Teacher's Comment") {
            if (element.status === 1) {
              this.isTComment = true;
            }
          }
          // this.checkSettings(element)
        });

        // console.log(this.config_settings)
      })
      .catch((err) => {});
  },
  methods: {
    printResults() {
      window.print();
    },
    setComment(average) {
      var comment;
      console.log(average);

      if (0 >= average && average <= 39.99) {
        comment === "Fail! Please sit up";
      }

      if (40 >= average && average <= 44.99) {
        comment === "Poor! Please put more effort";
      }

      if (45 >= average && average <= 49.99) {
        comment === "Fair! Please put more effort";
      }

      if (50 >= average && average <= 59.99) {
        comment === "Average performance, work harder";
      }
      if (60 >= average && average <= 64.99) {
        comment === "A Good perfomance! You can do better";
      }

      if (65 >= average && average <= 74.99) {
        comment === "Above average, Very good. Keep it up";
      }

      if (75 >= average && average <= 100) {
        comment === "Excellent Performance. Keep it up";
      } else {
        comment === "Ungraded";
      }
      return comment;
    },

    getPastTotal(subject_id, term_id) {
      const total = this.Total.find(
        (total) => total.term_id === term_id && total.subject_id === subject_id
      );
      if (total) {
        return total.total;
      } else {
        return "";
      }
    },
  },
  created() {
    //  let primaryColor=$('.text-primary')
    //           primaryColor.style.color='green';
    //  console.log(primaryColor)
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
          this.avgReport = result.data.avgReport;

          this.isMidterm = result.data.scores[0].mid_term ? true : false;
          if (this.report.term_id === 3) {
            this.isThird_term = true;
            console.log(this.Total);
            if (this.report.isCummulative === 1) {
              this.isCummulative = true;
            }
          }
          this.signature = result.data.signature;
          this.noneAcademic = result.data.noneAcademic;
          this.LDomain = result.data.LDomain;
          this.attendance = result.data.attendance;
          this.subjectsDropped = result.data.subjectDropt;

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
          this.avgReport = result.data.avgReport;
          //console.log(result.data.Scores)
          this.isMidterm = result.data.scores[0].mid_term ? true : false;
          this.principal_comment = result.data.principal_comment;
          this.staff_comment = result.data.staff_comment;
          if (this.report.term_id === 3) {
            this.isThird_term = true;
            if (this.report.isCummulative === 1) {
              this.isCummulative = true;
            }
          }

          this.signature = result.data.signature;
          this.LDomain = result.data.LDomain;
          this.noneAcademic = result.data.noneAcademic;
          this.attendance = result.data.attendance;
          this.subjectsDropped = result.data.subjectDropt;
          this.avgReport = result.data.avgReport;
          this.$Progress.finish();
          // console.log(this.signature);
          //console.log(this.report);
        })
        .catch((err) => {});
    }
  },
};
</script>

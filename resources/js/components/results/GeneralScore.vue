<template>
    <div class="col-md-12">
        <div class="col-md-12">
            <table
                class="table table-bordered table-sm font-weight-bold table-striped"
            >
                <thead>
                    <tr class="text-center">
                        <th>S/N</th>
                        <th>Subject</th>
                        <th v-if="!report.isCa2">
                            CA<br />{{
                                report.ca1Percent
                                    ? `[${report.ca1Percent}]`
                                    : ""
                            }}
                        </th>
                        <th v-if="report.isCa2">
                            1st CA <br />{{
                                report.ca1Percent
                                    ? `[${report.ca1Percent}]`
                                    : ""
                            }}
                        </th>
                        <th v-if="report.isCa2">
                            2nd CA <br />{{
                                report.ca2Percent
                                    ? `[${report.ca2Percent}]`
                                    : ""
                            }}
                        </th>
                        <th v-if="report.isCa3">
                            3rd CA <br />{{
                                report.ca3Percent
                                    ? `[${report.ca3Percent}]`
                                    : ""
                            }}
                        </th>
                        <th>
                            Exams <br />{{
                                report.examPercent
                                    ? `[${report.examPercent}]`
                                    : ""
                            }}
                        </th>
                        <th v-show="isMidterm">Mid Term</th>
                        <th>Total <br /></th>
                        <th v-if="report.isCummulative">1st Term</th>
                        <th v-if="report.isCummulative">2nd Term</th>
                        <th v-if="report.isCummulative">Grand Total</th>
                        <th v-if="report.isCummulative">C.Avg</th>

                        <th>Grade</th>
                        <th>Narration</th>
                        <th v-if="report.isMaxScore">
                            <div>
                                <span
                                    >ASH<br />
                                    Score</span
                                >
                            </div>
                        </th>
                        <th v-if="report.isMinScore">
                            <div>
                                <span
                                    >ASL <br />
                                    Score</span
                                >
                            </div>
                        </th>
                        <th v-if="!report.isCummulative">
                            ASA <br />
                            Score
                        </th>
                        <th v-if="report.isArmPosition">
                            <div>
                                <span>AS<br />Position</span>
                            </div>
                        </th>

                        <!-- <th v-show="isCSPosition"><div><span>CS<br> Position</span></div></th>
<th v-show="isCSHScore"><div><span>CSH<br> Score</span></div></th>
<th v-show="isCSLScore"><div><span>CSL <br> Score</span></div></th>
<th v-show="isCAScore"><div><span>CSA <br> Score</span></div></th> -->
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="(score, index) in scores" :key="index">
                        <td>{{ index + 1 }}</td>
                        <td>
                            {{ score.subjects ? score.subjects.name : "" }}
                        </td>
                        <td>
                            {{ score.test1 ? score.test1 : "" }}
                        </td>
                        <td v-if="report.isCa2">
                            {{ score.test2 }}
                        </td>
                        <td v-if="report.isCa3">
                            {{ score.test3 }}
                        </td>
                        <td>
                            {{ score.exams ? score.exams : "" }}
                        </td>
                        <td v-show="isMidterm">
                            {{ score.mid_term ? score.mid_term : "" }}
                        </td>
                        <td>
                            {{ score.total ? score.total : "-" }}
                        </td>
                        <td v-if="report.isCummulative">
                            {{ getPastTotal(score.subject_id, 1) }}
                        </td>
                        <td v-if="report.isCummulative">
                            {{ getPastTotal(score.subject_id, 2) }}
                        </td>

                        <td v-if="report.isCummulative">
                            {{ score.grand_total ? score.grand_total : "-" }}
                        </td>
                        <td v-if="report.isCummulative">
                            {{
                                score.cummulative_avg
                                    ? score.cummulative_avg
                                    : "-"
                            }}
                        </td>
                        <td v-if="report.isCummulative">
                            {{
                                score.cummulative_grade
                                    ? score.cummulative_grade
                                    : "-"
                            }}
                        </td>
                        <td v-if="report.isCummulative">
                            {{
                                score.cummulative_narration
                                    ? score.cummulative_narration
                                    : "-"
                            }}
                        </td>

                        <td v-if="!report.isCummulative">
                            {{ score.grade ? score.grade : "" }}
                        </td>
                        <td v-if="!report.isCummulative">
                            {{ score.narration ? score.narration : "" }}
                        </td>

                        <td v-if="report.isMaxScore">
                            {{ score.arm_max_score ? score.arm_max_score : "" }}
                        </td>
                        <td v-if="report.isMinScore">
                            {{ score.arm_min_score ? score.arm_min_score : "" }}
                        </td>
                        <td v-if="!report.isCummulative">
                            {{ score.arm_avg_score ? score.arm_avg_score : "" }}
                        </td>
                        <td v-if="report.isArmPosition">
                            {{
                                score.arm_subj_position
                                    ? score.arm_subj_position
                                    : ""
                            }}
                        </td>

                        <!-- <td v-show="isCSPosition">{{score.class_subj_position?score.class_subj_position:''}}</td>
<td v-show="isCSHScore">{{score.max_class_score?score.max_class_score:''}}</td>
<td v-show="isCSLScore">{{score.min_class_score?score.min_class_score:''}}</td>
<td v-show="isCAScore">{{score.class_avg_score?score.class_avg_score:''}}</td> -->
                    </tr>
                    <!-- None ACADEMICS-->
                    <tr v-show="noneAcademic">
                        <td colspan="20 " style="border: none">
                            <div class="text-center text-bold text-primary">
                                NON ACADEMIC SUBJECTS
                            </div>
                        </td>
                    </tr>

                    <tr v-for="(score, index) in noneAcademic" :key="index">
                        <td>{{ index + 1 }}</td>
                        <td>
                            {{ score.subjects ? score.subjects.name : "" }}
                        </td>
                        <td>
                            {{ score.test1 ? score.test1 : "" }}
                        </td>
                        <td v-if="report.isCa2">
                            {{ score.test2 }}
                        </td>
                        <td v-if="report.isCa3">
                            {{ score.test3 }}
                        </td>
                        <td>
                            {{ score.exams ? score.exams : "" }}
                        </td>
                        <td v-show="isMidterm">
                            {{ score.mid_term ? score.mid_term : "" }}
                        </td>
                        <td>
                            {{ score.total ? score.total : "-" }}
                        </td>
                        <td v-if="report.isCummulative">
                            {{ getPastTotal(score.subject_id, 1) }}
                        </td>
                        <td v-if="report.isCummulative">
                            {{ getPastTotal(score.subject_id, 2) }}
                        </td>

                        <td v-if="report.isCummulative">
                            {{ score.grand_total ? score.grand_total : "-" }}
                        </td>
                        <td v-if="report.isCummulative">
                            {{
                                score.cummulative_avg
                                    ? score.cummulative_avg
                                    : "-"
                            }}
                        </td>
                        <td v-if="report.isCummulative">
                            {{
                                score.cummulative_grade
                                    ? score.cummulative_grade
                                    : "-"
                            }}
                        </td>
                        <td v-if="report.isCummulative">
                            {{
                                score.cummulative_narration
                                    ? score.cummulative_narration
                                    : "-"
                            }}
                        </td>

                        <td v-if="!report.isCummulative">
                            {{ score.grade ? score.grade : "" }}
                        </td>
                        <td v-if="!report.isCummulative">
                            {{ score.narration ? score.narration : "" }}
                        </td>

                        <td v-if="report.isMaxScore">
                            {{ score.arm_max_score ? score.arm_max_score : "" }}
                        </td>
                        <td v-if="report.isMinScore">
                            {{ score.arm_min_score ? score.arm_min_score : "" }}
                        </td>
                        <td v-if="!report.isCummulative">
                            {{ score.arm_avg_score ? score.arm_avg_score : "" }}
                        </td>
                        <td v-if="report.isArmPosition">
                            {{
                                score.arm_subj_position
                                    ? score.arm_subj_position
                                    : ""
                            }}
                        </td>

                        <!-- <td v-show="isCSPosition">{{score.class_subj_position?score.class_subj_position:''}}</td>
<td v-show="isCSHScore">{{score.max_class_score}}</td>
<td v-show="isCSLScore">{{score.min_class_score}}</td>
<td v-show="isCAScore">{{score.class_avg_score}}</td> -->
                    </tr>
                    <tr v-if="subjectsDropped">
                        <td colspan="20">
                            <subjects-dropped :scores="subjectsDropped" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="row col-md-12 col-sm-12 py-1">
            <summary-table :summary="summary" :report="report" />
        </div>
        <div class="col-md-12 container" v-if="report.isLearningDomain">
            <learning-domains :LDomain="LDomain" />
        </div>
    </div>
</template>

<script>
export default {
    props: [
        "scores",
        "subjects",
        "subjectsDropped",
        "report",
        "noneAcademic",
        "LDomain",
        "summary",
        "getPastTotal",
    ],
};
</script>

<style></style>

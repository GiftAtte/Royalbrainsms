<template>
    <div style="top: -10px">
        <div class="col-md-12 table-responsive">
            <table class="table table-sm table-bordered font-weight-bold">
                <thead>
                    <tr class="text-center">
                        <th>SUBJECTS</th>
                        <th>CA<br />CLASS WORK[10%]</th>
                        <th>CA<br />TEST [10%]</th>
                        <th>MID TERM <br />TEST [30%]</th>
                        <th>EXAM [50%]</th>
                        <th>TOTAL</th>
                        <th>CLASS AVG.</th>
                        <th>GRADE</th>
                        <th>GRADE<br />COMMENT</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="(score, index) in scores" :key="index">
                        <td>{{ score.subjects ? score.subjects.name : "" }}</td>
                        <td>{{ score.test1 ? score.test1 : "-" }}</td>
                        <td>{{ score.test2 ? score.test2 : "-" }}</td>
                        <td>{{ score.test3 ? score.test3 : "-" }}</td>
                        <td>{{ score.exams ? score.exams : "-" }}</td>
                        <td>{{ score.total ? score.total : "-" }}</td>
                        <td>
                            {{
                                score.arm_avg_score ? score.arm_avg_score : "-"
                            }}
                        </td>
                        <td>{{ score.grade ? score.grade : "-" }}</td>
                        <td>{{ score.narration ? score.narration : "-" }}</td>
                    </tr>
                    <tr>
                        <th colspan="5">
                            <h5 class="text-bold">
                                TOTAL SCORE
                            </h5>
                        </th>
                        <td>
                            <h5 class="text-bold">
                                {{ summary.total_scores }}
                            </h5>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
                <tfoot>
                    <th colspan="5">
                        <h5 class="text-bold">PERCENTAGE AVERAGE</h5>
                    </th>
                    <th>
                        <h5 class="text-bold">{{ summary.average_scores }}</h5>
                    </th>
                    <td></td>
                    <td></td>

                    <th>
                        <h5 class="text-bold">{{ summary.narration }}</h5>
                    </th>
                </tfoot>
            </table>
        </div>

        <div class="col-md-12">
            <table class="table table-sm">
                <tr>
                    <td>
                        <table class="table table-bordered table-sm">
                            <tr>
                                <th
                                    colspan="2"
                                    class="text-uppercase text-center"
                                >
                                    AFFECTIVE DOMAIN
                                </th>
                            </tr>
                            <tbody>
                                <tr
                                    v-for="ldomain in LDomain"
                                    :key="ldomain.id"
                                >
                                    <td
                                        v-if="
                                            ldomain.ldomain.type ===
                                            'Behavioural'
                                        "
                                    >
                                        {{ ldomain.ldomain.domain }}
                                    </td>
                                    <td
                                        v-if="
                                            ldomain.ldomain.type ===
                                            'Behavioural'
                                        "
                                    >
                                        {{ ldomain.grade }}
                                    </td>
                                </tr>

                                <tr></tr>
                            </tbody>
                        </table>
                    </td>

                    <td>
                        <table class="table table-sm">
                            <tr>
                                <td>
                                    <table
                                        class="table table-sm table-bordered"
                                    >
                                        <tr>
                                            <th>AFFECTIVE DOMAIN KEYS</th>
                                        </tr>
                                        <tr>
                                            <td>
                                                A &nbsp;&nbsp;-&nbsp;&nbsp;
                                                Excellent
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                B &nbsp;&nbsp;-&nbsp;&nbsp; Good
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                C &nbsp;&nbsp;-&nbsp;&nbsp;
                                                Average
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                D&nbsp;&nbsp; -&nbsp;&nbsp;
                                                Below Average
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                E&nbsp; &nbsp; - &nbsp;&nbsp;
                                                Poor
                                            </td>
                                        </tr>
                                    </table>
                                </td>

                                <td>
                                    <table
                                        class="table table-sm table-bordered"
                                    >
                                        <tr>
                                            <th
                                                colspan="2"
                                                class="text-uppercase text-center"
                                            >
                                                Subject Grading Key
                                            </th>
                                        </tr>
                                        <tr
                                            v-for="grade in grades"
                                            :Key="grade.id"
                                        >
                                            <td>
                                                {{ grade.lower_bound }}-{{
                                                    grade.upper_bound
                                                }}
                                            </td>
                                            <td>
                                                {{ grade.grade }}-
                                                {{ grade.narration }}
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-md-12">
            <table class="table">
                <tr>
                    <td>
                        <blueridge-cummulative
                            :avgReport="avgReport"
                            :summary="summary"
                            :attendance="attendance"
                            :signature="signature"
                            :report="report"
                            :staff_comment="staff_comment"
                            :principal_comment="principal_comment"
                        />
                    </td>
                </tr>
                <tr></tr>
            </table>
        </div>
    </div>
</template>

<script>
import BlueridgeCummulative from "./BluerideCummulative.vue";
export default {
    components: { BlueridgeCummulative },
    props: [
        "scores",
        "grades",
        "LDomain",
        "summary",
        "attendance",
        "avgReport",
        "signature",
        "principal_comment",
        "teacher_comment",
        "report",
        "staff_comment",
    ],
};
</script>

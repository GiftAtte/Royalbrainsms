<template>
    <table class="table table-sm">
        <tr>
            <td>
                <table class="table table-bordered">
                    <tr class="text-center">
                        <th>% AVERAGE</th>
                        <th>1ST TERM</th>
                        <th>2ND TERM</th>
                        <th>3RD TERM</th>
                        <th>YR. AVG</th>
                    </tr>

                    <tr>
                        <th>Pupil's Avg.</th>
                        <td>{{ findTerminalAvg(1) }}</td>
                        <td>{{ findTerminalAvg(2) }}</td>
                        <td>{{ findTerminalAvg(3) }}</td>
                        <td>
                            {{
                                summary.cummulative_average
                                    ? summary.cummulative_average
                                    : ""
                            }}
                        </td>
                    </tr>
                    <tr>
                        <th>Class Avg.</th>
                        <td>{{ findClassAvg(1) }}</td>
                        <td>{{ findClassAvg(2) }}</td>
                        <td>{{ findClassAvg(3) }}</td>
                        <td>{{ findClassCummAvg(3) }}</td>
                    </tr>
                    <tr>
                        <th>Class Max Avg</th>
                        <td>{{ findTermMaxAvg(1) }}</td>
                        <td>{{ findTermMaxAvg(2) }}</td>
                        <td>{{ findTermMaxAvg(3) }}</td>
                        <td>{{ findClassCummAvgMax(3) }}</td>
                    </tr>
                </table>
            </td>
            <td>
                <table class="table table-sm table-bordered">
                    <tr>
                        <th colspan="3" class="text-center">ATTENDANCE</th>
                    </tr>
                    <tr class="text-uppercase">
                        <th>School Days</th>
                        <th>Days Present</th>
                        <th>Days Absent</th>
                    </tr>
                    <tr>
                        <td>
                            {{ attendance ? attendance.school_days : "_____" }}
                        </td>
                        <td>
                            {{ attendance ? attendance.days_present : "_____" }}
                        </td>
                        <td>
                            {{ attendance ? attendance.days_absent: "_____" }}
                        </td>
                    </tr>
                    <tr>
                        <th colspan="2">NEXT TERM BEGINS</th>
                        <td>
                            {{
                                report.next_term
                                    ? report.next_term
                                    : "___________"
                            }}
                        </td>
                    </tr>
                </table>
            </td>
            <td>
                <table class="table table-bordered">
                    <tr>
                        <th colspan="4" class="text-center text-uppercase">
                            Class Teacher's Comment
                        </th>
                    </tr>
                    <tr rowspan="3">
                        <td colspan="4">
                            {{
                                staff_comment
                                    ? staff_comment
                                    : "----------------------------------"
                            }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <th colspan="2">
                Academic Supervisor:
                {{ principal_comment ? principal_comment : "________________" }}
            </th>
            <th colspan="1">
                <span
                    ><b>Authorized Signature:&nbsp;</b
                    ><img
                        :src="`/img/signatures/${signature.photo}`"
                        class="ml-2 img-result"
                        width="100"
                        height="50"
                        onerror="this.style.display='none'"
                /></span>
            </th>
        </tr>
    </table>
</template>

<script>
export default {
    props: [
        "avgReport",
        "summary",
        "attendance",
        "signature",
        "staff_comment",
        "principal_comment",
        "report",
    ],
    data() {
        return {
            firstTerm: "",
            secondTerm: "",
            thirdTerm: "",
        };
    },

    methods: {
        findTerminalAvg(termId) {
            let result = this.avgReport.filter(
                (report) => report.term_id == termId
            );
            if (result.length) {
                return result[0].studentAvg;
            } else {
                return "";
            }
        },
        findTermMaxAvg(termId) {
            let result = this.avgReport.filter(
                (report) => report.term_id == termId
            );
            if (result.length) {
                return result[0].maxAvg;
            } else {
                return "";
            }
        },

        findClassAvg(termId) {
            let result = this.avgReport.filter(
                (report) => report.term_id == termId
            );
            if (result.length) {
                return result[0].classAvg;
            } else {
                return "";
            }
        },

        findClassCummAvg(termId) {
            let result = this.avgReport.filter(
                (report) => report.term_id == termId
            );
            if (result.length) {
                return result[0].classCummulativeAvg;
            } else {
                return "";
            }
        },
        findClassCummAvgMax(termId) {
            let result = this.avgReport.filter(
                (report) => report.term_id == termId
            );
            if (result.length) {
                return result[0].classCummulativeMax;
            } else {
                return "";
            }
        },
    },
};
</script>

<template>
    <div class="container">
        <h3 class="text-center">RESULTS SUMMARY</h3>
        <table
            class="table table-bordered table-sm font-weight-bold table-striped text-center"
        >
            <tbody>
                <tr>
                    <td v-if="report.isArmPosition">Student Position</td>
                    <td>Total score</td>
                    <td>Average score</td>
                    <td v-if="report.isCummulative">Cummulative Avg score</td>
                    <td>Grade</td>
                    <td>Narration</td>
                    <td>Students In Class</td>
                    <td v-if="report.isProgress_status">Progress status</td>
                </tr>

                <tr>
                    <td v-if="report.isArmPosition">
                        {{ summary.arm_position }}
                    </td>

                    <td>{{ summary.total_scores }}</td>

                    <td>{{ summary.average_scores }}</td>

                    <td v-if="report.isCummulative">
                        {{ summary.cummulative_average }}
                    </td>

                    <td>{{ summary.grade }}</td>

                    <td>{{ summary.narration }}</td>

                    <td>{{ summary.total_students }}</td>

                    <td v-if="report.isProgress_status">
                        <b>{{
                            summary.progress_status
                                ? summary.progress_status
                                : "---------------"
                        }}</b>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    props: ["report", "summary", "attendance"],
};
</script>

<style></style>

<template>
    <table
        class="table table-bordered table-sm font-weight-bold"
        id="data_tb"
        style="font-size: 12px"
    >
        <tbody>
            <tr class="text-capitalize">
                <th>Names/Subjects</th>
                <th
                    v-if="reports"
                    v-for="subject in subjects"
                    :key="subject.id"
                    class="text-capitalize"
                >
                    {{ subject.subjects.name }}
                </th>

                <th>
                    <tr>
                        <th>T L</th>
                        <th>A S</th>
                        <th>C P</th>
                        <th>P S</th>
                    </tr>
                </th>
            </tr>
            <tr v-for="student in students" :key="student.id">
                <td>
                    {{ student.surname }}, {{ student.first_name }}
                    {{ student.middle_name }}
                </td>
                <td v-for="subject in subjects" :key="subject.id">
                    <span
                        v-for="report in marks"
                        :key="report.id"
                        v-if="
                            report.student_id == student.id &&
                            report.subject_id == subject.subjects.id
                        "
                    >
                        {{ report.total }}
                    </span>
                </td>
                <td
                    v-for="result in results"
                    :key="result.id"
                    v-if="result.student_id == student.id"
                >
                    <tr>
                        <td>{{ result.total_scores }}</td>
                        <td>{{ result.average_scores }}</td>
                        <!-- <td>{{result.arm_position}}</td> -->
                        <td>
                            {{ result.arm_position ? result.arm_position : "" }}
                        </td>
                        <td >
                            {{ result.progress_status ? result.progress_status : "-" }}
                        </td>
                    </tr>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
export default {
    props: ["reports", "subjects", "results", , "students", "marks"],
};
</script>

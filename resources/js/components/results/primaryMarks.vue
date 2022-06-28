<template>
    <table class="table table-hover table-sm">
        <thead>
            <tr>
                <th>S/N</th>
                <th>Name</th>
                <th>First Test[10%]</th>
                <th>Second Test[10%]</th>
                <th>Exams/Mid-term[40%]</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(score, index) in Scores" :key="index">
                <td>{{ index + 1 }}</td>
                <td>
                    {{ score.name }}
                    <input
                        type="hidden"
                        :id="`student_id${index}`"
                        :value="score.student_id"
                    />
                    <input
                        type="hidden"
                        :id="`arm_id${index}`"
                        :value="score.arm_id"
                    />
                </td>
                <td>
                    <input
                        :id="`test1${index}`"
                        :value="score.test1"
                        type="number"
                        placeholder="Class Work"
                        max="100"
                        min="0"
                        step="0.01"
                    />
                </td>
                <td>
                    <input
                        :id="`test2${index}`"
                        :value="score.test2"
                        type="number"
                        placeholder="Assignment "
                        max="100"
                        min="0"
                        step="0.01"
                    />
                </td>
                <td>
                    <input
                        type="number"
                        :id="`midterm${index}`"
                        :value="score.total"
                        placeholder="Class Test "
                        max="100"
                        min="0"
                        step="0.01"
                        disabled
                    />
                </td>
                <!-- <input type="hidden" :id="`test3${index}`" :value="score.test3"  placeholder="Class Test " max="100" min="0" step="0.01">
<input :id="`note${index}`" :value="score.note" type="hidden" placeholder="Notes " max="100" min="0" step="0.01"></td> -->
                <td>
                    <input
                        :id="`exams${index}`"
                        :value="score.exams"
                        type="number"
                        placeholder="Mid-Term Test/Exams"
                        max="100"
                        min="0"
                        step="0.01"
                    />
                </td>
            </tr>
        </tbody>
    </table>
</template>

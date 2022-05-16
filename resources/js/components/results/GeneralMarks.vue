<template>
  <div>
   <table
                    v-if="
                    report.type === 'default-result' || report.type === 'default-midterm'
                  "
                  class="table table-hover table-sm"
                >
                  <thead>
                    <tr>
                      <th>S/N</th>
                      <th>Name</th>
                      <th>CA1 {{report.ca1Percent?`[${report.ca1Percent}]`:''}}</th>
                      <th v-if="report.isCa2">CA2 {{report.ca2Percent?`[${report.ca2Percent}]`:''}}</th>
                      <th v-if="report.isCa3">CA3 {{report.ca3Percent?`[${report.ca3Percent}]`:''}}</th>
                      <th
                        v-show="
                          Scores[0] &&
                          Scores[0].total &&
                          report === 'default-result'
                        "
                      >
                        MID-TERM
                      </th>
                      <th v-show="report.type === 'default-midterm'">
                        MID-TERM EXAMS
                      </th>
                      <th v-show="report.type === 'default-result'">
                        TERMINAL EXAMS {{report.examPercent?`[${report.examPercent}]`:''}}
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(score, index) in Scores" :key="index">
                      <td>{{ index + 1 }}</td>
                      <td>
                        {{ score.name }}
                        <input
                        @input="update"
                          type="hidden"
                          :id="`student_id${index}`"
                          :value="score.student_id"
                        />
                        <input
                        @input="update"
                          type="hidden"
                          :id="`arm_id${index}`"
                          :value="score.arm_id"
                        />
                      </td>
                      <td>
                        <input
                        @input="update"
                          :id="`test1${index}`"
                          :value="score.test1"
                          type="number"
                          placeholder="CA1"
                          :max="report.ca1Percent"
                          min="0"
                          step="0.01"
                        />
                      </td>
                      <td v-if="report.isCa2">
                        <input
                        @input="update"
                          :id="`test2${index}`"
                          :value="score.test2"
                          type="number"
                          placeholder="CA2"
                          :max="report.ca2Percent"
                          min="0"
                          step="0.01"
                        />
                      </td>
                      <td v-if="report.isCa3">
                        <input

                          type="number"
                          :id="`test3${index}`"
                          :value="score.test3"
                          placeholder="CA3 "
                         :max="report.ca3Percent"
                          min="0"
                          step="0.01"
                          @input="update"
                        />
                      </td>
                      <td style="display: none">
                        <input
                          :id="`note${index}`"
                          :value="score.note"
                          type="hidden"
                          placeholder="Notes "
                          max="100"
                          min="0"
                          step="0.01"
                          @input="update"
                        />
                      </td>
                      <td
                        v-show="
                          Scores[0] &&
                          Scores[0].total &&
                          report === 'default-result'
                        "
                      >
                        <input
                          type="number"
                          :id="`midterm${index}`"
                          :value="score.total"
                          placeholder="Exam  "
                          max="100"
                          min="0"
                          step="0.01"
                          disabled
                          @input="update"
                        />
                      </td>
                      <input
                        :id="`exams${index}`"
                        :value="score.exams"
                        type="number"
                        placeholder="Exam"
                        :max="report.examPercent"
                        min="0"
                        step="0.01"
                        @input="update"
                      />
                    </tr>
                  </tbody>
                </table>
  </div>
</template>

<script>
export default {
    props:['report','Scores','form'],
    methods:{
        update(event) {
            this.$emit('input', event.target.value)
        },
    }
}
</script>

<style>

</style>


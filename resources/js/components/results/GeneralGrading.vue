<template>
  <div class="row text-bold">
    <table class="table">
      <tr>
        <td>
           <h6 class="text-bold">GRADING KEYS</h6>
          <hr />
          <p class="text-bold">
            |<span v-for="grade in grades" :Key="grade.id">
              &nbsp;{{ grade.lower_bound }}&nbsp;-&nbsp;
              {{ grade.upper_bound }}&nbsp;:&nbsp;{{ grade.grade }} &nbsp;:
              &nbsp;{{ grade.narration }}&nbsp;|&nbsp;
            </span>
          </p>
          <div v-if="report.isLearningDomain">
            <h6 class="text-bold">AFFECTIVE DOMAIN KEYS</h6>
            <hr />
            <p class="text-bold">
              |Excellent - 5 | Good - 4 | Average - 3 | Below Average - 2 | Poor
              - 1 |
            </p>
          </div>
        </td>
      </tr>
    </table>
  </div>
</template>

<script>
export default {
  props: ["grades", "report"],
};
</script>

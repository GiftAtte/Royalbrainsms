<template>
  <div>
      <form>
   <table
                  v-if="
                    report.type === 'primary-midterm' ||
                    report.type === 'primary-terminal'
                  "
                  class="table table-hover table-sm"
                >
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

<table v-else  class="table table-hover table-sm">
                  <thead>
                    <tr>
                      <th>S/N</th>
                      <th>Name</th>
                      <th>Class work[2]</th>
                      <th>Assignment[2]</th>
                      <th>Class Test[5]</th>
                      <th>Notes[1]</th>
                      <th
                        v-show="
                          Scores[0] && Scores[0].total && report != 'mid_term'
                        "
                      >
                        midterm
                      </th>
                      <th>Exams[20/70]</th>
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
                          :id="`test3${index}`"
                          :value="score.test3"
                          type="number"
                          placeholder="Class Test "
                          max="100"
                          min="0"
                          step="0.01"
                        />
                      </td>
                      <td>
                        <input
                          :id="`note${index}`"
                          :value="score.note"
                          type="number"
                          placeholder="Notes "
                          max="100"
                          min="0"
                          step="0.01"
                        />
                      </td>
                      <td v-if="score.total != null && report != 'mid_term'">
                        <input
                          :id="`midterm${index}`"
                          :value="score.total"
                          disabled
                          max="100"
                          min="0"
                          step="0.01"
                        />
                      </td>
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
</form>
  </div>
</template>

<script>
export default {
    props:['report','Scores','form'],
    methods:{
    update(event) {
            this.$emit('input', event.target.value)
        },
        createScore() {
     // this.isLoading = true;
      this.form.student_id = [];
      this.form.test1 = [];
      this.form.test2 = [];
      this.form.test3 = [];
      this.form.exams = [];
      this.form.note = [];
      this.form.midterm = [];
      this.form.number_of_students = 0;
      var check = false;
      if (this.Scores[0].total != null) {
        check = true;
      }
      for (let index = 0; index < this.Scores.length; index++) {
        var student_id = document.querySelector(`#student_id${index}`).value;
        var test1 = document.querySelector(`#test1${index}`).value;
        var test2 = document.querySelector(`#test2${index}`).value;
        var exams = document.querySelector(`#exams${index}`).value;

        this.form.student_id.push(student_id);
        this.form.test1.push(test1);

        this.form.test2.push(test2);
        if (
          this.report.type != "primary-midterm" &&
          this.report.type != "primary-terminal"
        ) {
          var test3 = document.querySelector(`#test3${index}`).value;
          if (check) {
            var midterm = document.querySelector(`#midterm${index}`).value;
            this.form.midterm.push(midterm);
            console.log(check);
          }
          var note = document.querySelector(`#note${index}`).value;

          this.form.test3.push(test3);
          this.form.note.push(note);
        }
        this.form.exams.push(exams);

        this.form.number_of_students = ++this.form.number_of_students;
      }
      console.log("Running Herae");
      if (
        this.report.type === "primary-midterm" ||
        this.report.type === "primary-terminal"
      ) {
        //   console.log('Running Herae')
        this.form
          .post("/api/primary_scores")

          .then((res) => {
            toast.fire({
              type: "success",
              title: "marks added successfully",
            });
            this.isSubject = false;
            this.isLoading = false;
            // this.Scores=[]
          })
          .catch((err) => {
            toast.fire({
              type: "danger",
              title: "there was error uploading the file" + err,
            });
            this.isLoading = false;
          });
      } else {
        this.form
          .post("/api/scores")

          .then((res) => {
            toast.fire({
              type: "success",
              title: "marks added successfully",
            });
            this.isSubject = false;
            this.isLoading = false;
          })
          .catch((err) => {
            toast.fire({
              type: "danger",
              title: "there was error uploading the file" + err,
            });
            this.isLoading = false;
          });
      }

  },
    }
}
</script>

<style>

</style>


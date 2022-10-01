<template>
  <app-body pageTitle="Birthday" subPageTitle="Birthday List">
    <div class="card">
      <div class="card-header">{{ "" }}</div>
      <div class="card-body">
        <div class="col-md-12">
          <birthday>
            <template #link>
              <router-link to="/birthdays" class="small-box-footer"
                >{{ " " }}
              </router-link>
            </template>
          </birthday>
        </div>
        <div class="small-box col-md-12">
          <div class="inner">
            <h4 class="text-center text-uppercase">This Month Celebrants</h4>
            <div class="table-responsive">
              <table class="table table-sm">
                <tr v-for="student in students" :key="student.id">
                  <td>
                    <img
                      class="img-circle"
                      :src="`/img/profile/stud${student.id}.png`"
                      width="50"
                      height="50"
                      alt="profile"
                      onerror="this.src='/img/profile.png'"
                    />
                  </td>
                  <td>{{ student.surname }} &nbsp;{{ student.first_name }}</td>
                  <td>
                    {{ student.dob | myDate }}
                  </td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </app-body>
</template>

<script>
import Birthday from "./Birthday.vue";
import Carousel from "./Carousel.vue";
export default {
  components: { Carousel, Birthday },
  data() {
    return {
      students: [],
    };
  },
  created() {
    axios.get("/api/birthday/reminder").then((res) => {
      this.students = res.data;
    });
  },
};
</script>

<style>
.bbanner {
  min-height: 50px;
}
</style>

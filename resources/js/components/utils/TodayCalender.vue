<template>
  <div class="card small-box">
    <div class="card-header bg-primary">
      <h1 class="text-center day">
        {{ getDate }}
      </h1>
      <h2 class="text-center">
        {{ today }}
      </h2>
      <p>{{ getDay }}</p>
    </div>
    <div class="card-body">
      <div class="tex-center">
        <center>
          <img src="/img/calendar.png" width="150" height="160" />
        </center>
      </div>
      <div class="col-md-12">
        <h4 class="text-uppercase">Up Coming Events</h4>
        <hr />
        <ul class="list-group list-group-unbordered" v-if="events.length">
          <li
            class="list-group-item text-capitalize text-bold"
            v-for="(event, index) in events"
            :key="index"
            @click="updateEvent(event)"
          >
            {{ event.title }}
            <span class="float-right">{{ event.start | myDate }}</span>
          </li>
        </ul>
        <p v-else>No event !</p>
      </div>

      <div>
        <button class="bg-primary btn-rounded float-right" @click="addEvent">
          <i class="fa fa-plus float-right"></i>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import moment from "moment";
export default {
  props: ["addEvent", "events", "updateEvent"],
  computed: {
    today() {
      return moment().format("dddd");
    },
    getDate() {
      return new Date().getDate().toString();
    },
    getDay() {
      return moment(new Date()).format("MMMM , YYYY");
    },
  },
  data() {
    return {
      todayDate: new Date(),
    };
  },
};
</script>
<style>
.day {
  font-size: 80px;
}
.btn-rounded {
  padding: 10px;
  font-size: 20px;
  height: 50px;
  width: 50px;
  display: flex;
  border-radius: 50%;
  align-items: center;
  justify-content: center;
  border-color: navy;
}
li {
  cursor: pointer;
}
</style>

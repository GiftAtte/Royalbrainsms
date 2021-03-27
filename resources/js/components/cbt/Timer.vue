<template>
    <vue-countdown-timer
      @start_callback="startCallBack('event started')"
      @end_callback="endCallBack"
      :start-time="`${start_date?start_date:endAt}`"
      :end-time="`${end_date}`"
      :interval="1000"
      :start-label="' starting:'"
      :end-label="'ending:'"
      label-position="begin"
      :end-text="' This test has ended!'"
      :day-txt="'days'"
      :hour-txt="'hrs'"
      :minutes-txt="'mins'"
      :seconds-txt="'sec'"
      class="btn btn-primary btn-lg">
      <template slot="start-label" slot-scope="scope">
        <span style="color: red" v-if="scope.props.startLabel !== '' && scope.props.tips && scope.props.labelPosition === 'begin'">{{scope.props.startLabel}}:</span>
        <span style="color: blue" v-if="scope.props.endLabel !== '' && !scope.props.tips && scope.props.labelPosition === 'begin'">{{scope.props.endLabel}}:</span>
      </template>

      <template slot="countdown" slot-scope="scope" >

        <span >{{scope.props.hours}}</span><i>{{scope.props.hourTxt}}</i>
        <span>{{scope.props.minutes}}</span><i>{{scope.props.minutesTxt}}</i>
        <span>{{scope.props.seconds}}</span><i>{{scope.props.secondsTxt}}</i>
      </template>

      <template slot="end-label" slot-scope="scope">
        <span style="color: red" v-if="scope.props.startLabel !== '' && scope.props.tips && scope.props.labelPosition === 'end'">{{scope.props.startLabel}}:</span>
        <span style="color: blue" v-if="scope.props.endLabel !== '' && !scope.props.tips && scope.props.labelPosition === 'end'">{{scope.props.endLabel}}:</span>
      </template>





      <template slot="end-text" slot-scope="scope">
        <span style="color: red">{{ scope.props.endText}}</span>
      </template>
    </vue-countdown-timer>
</template>

<script >
import moment from 'moment';
  export default {
    name: 'Timer',
    data(){
        return{
            date_diff:'',
          start_time:'',
          end_time:'',
          start_date:'',
          end_date:'',
          endAt:  (new Date).getTime()+10000
        }
    },
    mounted(){
      axios.get('/api/exam/'+this.$route.params.id)
      .then(res=>{

        this.start_date=moment(res.data.start_date).format('YYYY-MM-DD h:mm:ss a')
        this.end_date=moment(res.data.end_date).format('YYYY-MM-DD h:mm:ss a')
        this.start_time=res.data.start_time
        this.end_time=res.data.end_time
        let now=moment(new Date()).format('YYYY-MM-DD h:mm:ss a')
        this.date_diff=moment(now).diff(moment(this.end_date))

   console.log(this.date_diff)
      })

    },
    methods: {
      startCallBack: function (x) {

      },
      endCallBack: function (x) {

    },
    checkTime(){
        if(this.date_diff>0){
            this.date_diff=-1
            // this.$router.push('/dashboard')

           }
           console.log('still have time')
    }
    },
    created(){
setInterval(() => this.checkTime(), 3000);

    }
  }
</script>

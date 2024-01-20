<template>
   <div class="timer-containter" >
    <button class="btn btn-lg btn-primary timer">
        {{ `${formatTime(min)}:${formatTime(sec)}` }}
    </button>
   </div>

    <!-- <vue-countdown-timer
        @start_callback="startCallBack('event started')"
        @end_callback="endCallBack('Exam ended')"
        :start-time="`${startedAt}`"
        :end-time="`${endTime}`"
        :interval="1000"
        :start-label="' starting'"
        :end-label="'REM'"
        label-position="begin"
        :end-text="'Time Over'"
        :day-txt="'days'"
        :hour-txt="'Hrs'"
        :minutes-txt="'Min'"
        :seconds-txt="'Sec'"
        class="btn btn-primary btn-lg"
    > -->
    <!-- <template slot="start-label" slot-scope="scope">
            <span
                style="color: red"
                v-if="
                    scope.props.startLabel !== '' &&
                    scope.props.tips &&
                    scope.props.labelPosition === 'begin'
                "
                >{{ scope.props.startLabel }}:</span
            >
            <span
                style="color: blue"
                v-if="
                    scope.props.endLabel !== '' &&
                    !scope.props.tips &&
                    scope.props.labelPosition === 'begin'
                "
                >{{ scope.props.endLabel }}:</span
            >
        </template>

        <template slot="countdown" slot-scope="scope">
            <span>{{ scope.props.hours }}</span
            ><i>{{ scope.props.hourTxt }}</i>
            <span>{{ scope.props.minutes }}</span
            ><i>{{ scope.props.minutesTxt }}</i>
            <span>{{ scope.props.seconds }}</span
            ><i>{{ scope.props.secondsTxt }}</i>
        </template>

        <template slot="end-label" slot-scope="scope">
            <span
                style="color: red"
                v-if="
                    scope.props.startLabel !== '' &&
                    scope.props.tips &&
                    scope.props.labelPosition === 'end'
                "
                >{{ scope.props.startLabel }}:</span
            >
            <span
                style="color: blue"
                v-if="
                    scope.props.endLabel !== '' &&
                    !scope.props.tips &&
                    scope.props.labelPosition === 'end'
                "
                >{{ scope.props.endLabel }}:</span
            >
        </template>

        <template slot="end-text" slot-scope="scope">
            <span style="color: red">{{ scope.props.endText }}</span>
        </template>
    </vue-countdown-timer> -->
</template>

<script>
import moment from "moment";
export default {
    name: "Timer",
    props: ["autoSubmit", "duration"],
    data() {
        return {
            min: 0,
            sec: 0,
            remTime: 0,
            timeDiff: 0,
            startTime: 0,
            endTime: 0,
            userId: window.user.id,
            examId: this.$route.params.id,
            allocatedTimeInMillSec: "",
            endAt: new Date().getTime() + 10000,
        };
    },
    mounted() {
        // setInterval(() => this.checkTime(), 1500);
    },
    methods: {
        formatTime(time) {
            if (time < 10) {
                return `0${time}`;
            } else {
                return time;
            }
        },
        checkTime() {
            let now = moment(new Date()).format("YYYY-MM-DD h:mm:ss a");
            let date_diff = moment(now).diff(moment(this.end_date));

            if (date_diff >= 0) {
                // this.$router.push('/dashboard')
                console.log("Time Elaps");
                this.autoSubmit();
                //  window.location.href = "/exam_list";
                console.log(this.start_time);
            }
            console.log("Time available");
            this.date_diff;
        },

        checkCurrentUser() {
            const userExamInfo = JSON.parse(
                localStorage.getItem(`user${this.userId}${this.examId}`)
            );

            if (
                userExamInfo &&
                userExamInfo.examId === this.$route.params.id &&
                userExamInfo.userId === window.user.id
            ) {
                console.log(userExamInfo);
                return (this.remTime = userExamInfo.remTime);
            } else {
                let remTime = Number(this.duration) * 60 * 1000;
                let userData = {
                    remTime: remTime,
                    userId: window.user.id,
                    examId: this.$route.params.id,
                };
                localStorage.setItem(
                    `user${this.userId}${this.examId}`,
                    JSON.stringify(userData)
                );
                console.log("data", userData);
                return (this.remTime = remTime);
            }
        },

        timeRemaining(remTime) {
            this.min = Math.floor(remTime / 60 / 1000) % 60;
            this.sec = Math.floor(remTime / 1000) % 60;
            this.remTime = remTime;
            let userExamInfo = JSON.parse(
                localStorage.getItem(`user${this.userId}${this.examId}`)
            );
            userExamInfo.remTime = remTime;
            localStorage.setItem(
                `user${this.userId}${this.examId}`,
                JSON.stringify(userExamInfo)
            );
        },
        countDown(time) {
            if (time === 0) return time;
            else {
                return time - 1000;
            }
        },

        startCountDown() {
            setInterval(() => {
                if (this.remTime <= 0) {
                    clearInterval();
                    this.autoSubmit();
                    window.location.href = "/exam_list";
                } else {
                    this.timeRemaining(this.countDown(this.remTime));
                }
            }, 1000);
        },
    },

    created() {
        this.checkCurrentUser();
        setTimeout(() => {
            this.startCountDown();
        }, 1000);
    },
};
</script>

<style scoped>
.timer-container{
    display: flex;
    justify-content: end;
    justify-items: end;
    
}
.timer {
    border-radius: 10px;
    min-width: 100px;
    min-height: 70px;
    font-size: 40px;
    right: 0;
    position: fixed;
    z-index: 9999;
    top:100px;
   
}
</style>

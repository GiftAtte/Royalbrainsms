<template>
    <app-body pageTitle="Live Class" pageSubTitle="Live Classes">
        <!-- For Component View -->
        <div class="card card-outline card-navy">
            <div class="card-header py-y bg-primary text-white">
                <div class="row">
                    <h4 class="text-uppercase">USERNAME: {{ userName }}</h4>
                </div>
            </div>
            <div class="card-body">
                <div class="container text-center">
                    <h3 class="float-right text-primary">
                        Meeting Topic: {{ meetingInfo.title }}
                    </h3>
                    <br />
                    <hr />

                    <div v-if="isLoading">
                        <h3>
                            LOADING...... If Nothing happends after 5 seconds,
                            please refresh the page
                        </h3>
                    </div>
                    <div id="meetingSDKElement" class="container text-center">
                        <!-- Zoom Meeting SDK Component View Rendered Here -->
                    </div>

                    <!-- <button @click="getSignature" class="btn btn-primary btn-lg">
                        Join Meeting
                    </button> -->
                </div>
            </div>
            <div class="card-footer text-center">
                <router-link
                    tag="a"
                    title="Start Class"
                    class="btn btn-warning btn-sm"
                    :to="`/liveClasses`"
                >
                    Back To List
                </router-link>
            </div>
        </div>
    </app-body>
</template>

<script>
import axios from "axios";
import ZoomMtgEmbedded from "@zoomus/websdk/embedded";
import {
    REDIRECT_URI,
    SIGNATURE_ENTRY_ENDPOINT,
    ZOOM_SDK_KEY,
} from "./ZoomConf";

export default {
    created() {
        axios
            .get(`/api/livestream/${this.$route.params.id}`)
            .then((res) => {
                console.log("meeting:", res.data);
                this.meetingInfo = res.data;
            })
            .catch((err) => {
                console.log(err);
            });
    },
    mounted() {
        //location.reload();
        setTimeout(() => {
            this.getSignature();
        }, 2000);
        setTimeout(() => {
            this, (this.isLoading = false);
        }, 5000);
    },
    data() {
        return {
            client: ZoomMtgEmbedded.createClient(),
            meetingInfo: "",
            sdkKey: ZOOM_SDK_KEY,
            leaveUrl: REDIRECT_URI,
            signatureEndpoint: SIGNATURE_ENTRY_ENDPOINT,
            userEmail: window.user.email,
            userName: window.user.name,
            // pass in the registrant's token if your meeting or webinar requires registration. More info here:
            // Meetings: https://marketplace.zoom.us/docs/sdk/native-sdks/web/component-view/meetings#join-registered
            // Webinars: https://marketplace.zoom.us/docs/sdk/native-sdks/web/component-view/webinars#join-registered
            registrantToken: "",
            isLoading: true,
        };
    },
    methods: {
        checkOwner() {
            if (
                window.user.staff_id === parseInt(this.meetingInfo.created_by)
            ) {
                return 1;
            }
            return 0;
        },
        getSignature() {
            axios
                .post(this.signatureEndpoint, {
                    meetingNumber: this.meetingInfo.meeting_id,
                    role: this.checkOwner(),
                })
                .then((res) => {
                    console.log(res.data.signature);
                    this.startMeeting(res.data.signature);
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        startMeeting(signature) {
            let meetingSDKElement =
                document.getElementById("meetingSDKElement");

            this.client.init({ 
                debug: true,
                zoomAppRoot: meetingSDKElement,
                language: "en-US",
                customize: {
                    meetingInfo: [
                        "topic",
                        "host",
                        "mn",
                        "pwd",
                        "telPwd",
                        "invite",
                        "participant",
                        "dc",
                        "enctype",
                    ],
                    toolbar: {
                        buttons: [
                            {
                                text: "Custom Button",
                                className: "CustomButton",
                                onClick: () => {
                                    console.log("custom button");
                                },
                            },
                        ],
                    },
                },
            });

            this.client.join({
                sdkKey: this.sdkKey,
                signature: signature,
                meetingNumber: this.meetingInfo.meeting_id,
                password: this.meetingInfo.meeting_password,
                userName: this.userName,
                userEmail: this.userEmail,
                tk: this.registrantToken,
            });
        },
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
main {
    width: 70%;
    margin: auto;
    text-align: center;
}

main button {
    margin-top: 20px;
    background-color: #2d8cff;
    color: #ffffff;
    text-decoration: none;
    padding-top: 10px;
    padding-bottom: 10px;
    padding-left: 40px;
    padding-right: 40px;
    display: inline-block;
    border-radius: 10px;
    cursor: pointer;
    border: none;
    outline: none;
}

main button:hover {
    background-color: #2681f2;
}
</style>

<template>
    <app-body pageTitle="Live Class" pageSubTitle="Live Classes">
        <!-- For Component View -->
        <div class="card card-outline card-navy">
            <div class="card-header py-y bg-navy text-white">
                <div class="row">
                    <div class="col-md-6">
                        <h4>TUTOR'S CONNER: {{ userName }}</h4>
                    </div>
                    <div class="col-md-6">
                        <h5 class="float-right">
                            TOPIC: {{ meetingInfo.title }}
                        </h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="container">
                    <div id="meetingSDKElement" class="col-md-12"></div>
                </div>
            </div>
        </div>
    </app-body>
</template>

<script>
import { LEAVE_URL, SIGNATURE_ENTRY_ENDPOINT, ZOOM_SDK_KEY } from "./ZoomConf";
import { ZoomMtg } from "@zoomus/websdk";
//import ZoomMtgEmbedded from "@zoomus/websdk/embedded";
import Axios from "axios";
export default {
    created() {
        axios
            .get(`/api/livestream/${this.$route.params.id}`)
            .then((res) => {
                console.log("meeting:", res.data);
                this.meetingInfo = res.data;
                this.initializeZoom();
            })
            .catch((err) => {
                console.log(err);
            });
    },
    beforeMount() {
        ZoomMtg.inMeetingServiceListener("onUserJoin", (data) => {
            console.log("inMeetingServiceListener onUserJoin", data);
        });
    },
    mounted() {
        setTimeout(() => {
            this.getSignature();
        }, 3000);
    },
    data() {
        return {
            // client: ZoomMtgEmbedded.createClient(),
            // This Sample App has been updated to use SDK App type credentials https://marketplace.zoom.us/docs/guides/build/sdk-app
            meetingInfo: {},
            sdkKey: ZOOM_SDK_KEY,
            leaveUrl: LEAVE_URL,
            userName: window.user.name,
            userEmail: window.user.email,
            signatureEndpoint: SIGNATURE_ENTRY_ENDPOINT,
            // pass in the registrant's token if your meeting or webinar requires registration. More info here:
            // Meetings: https://marketplace.zoom.us/docs/sdk/native-sdks/web/client-view/meetings#join-registered
            // Webinars: https://marketplace.zoom.us/docs/sdk/native-sdks/web/client-view/webinars#join-registered
            registrantToken: "",
            isLoading: true,
        };
    },
    methods: {
        getSignature() {
            Axios.get(
                `${this.signatureEndpoint}/${this.meetingInfo.meeting_id}/${1}`,
                {
                    meetingNumber: this.meetingInfo.meeting_id,
                    role:
                        window.user.staff_id ===
                        parseInt(this.meetingInfo.created_by)
                            ? 1
                            : 0,
                }
            )
                .then((res) => {
                    console.log([window.user.staff_id]);
                    this.startMeeting(res.data.signature);
                })
                .catch((error) => {
                    console.log(error);
                });
        },

        initializeZoom() {
            ZoomMtg.setZoomJSLib("https://source.zoom.us/2.6.0/lib", "/av");
            ZoomMtg.preLoadWasm();
            ZoomMtg.prepareWebSDK();
            // loads language files, also passes any error messages to the ui
            ZoomMtg.i18n.load("en-US");
            ZoomMtg.i18n.reload("en-US");
        },

        startMeeting(signature) {
            // let meetingSDKElement =
            //     document.getElementById("meetingSDKElement");

            // this.client.init({
            //     zoomAppRoot: meetingSDKElement,
            //     language: "en-US",
            // });
            // console.log("yyyyy", this.meetingInfo);
            // this.client.join({
            //     meetingNumber: parseInt(this.meetingInfo.meeting_id),
            //     userName: this.userName,
            //     signature: signature,
            //     sdkKey: this.sdkKey,
            //     userEmail: this.userEmail,
            //     passWord: this.meetingInfo.meeting_password,
            //     tk: this.registrantToken,
            // });

            let el = document.getElementById("zmmtg-root");
            el.style.display = "block";
            ZoomMtg.init({
                leaveUrl: this.leaveUrl,
                success: (success) => {
                    console.log(success);
                    ZoomMtg.join({
                        meetingNumber: this.meetingInfo.meeting_id,
                        userName: this.userName,
                        signature: signature,
                        sdkKey: this.sdkKey,
                        userEmail: this.userEmail,
                        passWord: this.meetingInfo.meeting_password,
                        tk: this.registrantToken,
                        success: (success) => {
                            console.log(success);
                        },
                        error: (error) => {
                            console.log(error);
                        },
                    });
                },
                error: (error) => {
                    console.log(error);
                },
            });
        },
    },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->

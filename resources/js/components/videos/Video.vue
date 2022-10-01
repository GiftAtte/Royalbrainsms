<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Video</h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="#">Home</a>
                            </li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>

        <div class="col-12">
            <div class="card card-navy card-outline">
                <div class="card-header">Video Player</div>

                <div class="card-body row">
                    <div class="col-md-8 table-responsive">
                        <h2 class="my-2">Now Playing : {{ video.title }}</h2>

                        <div class="player-container">
                            <vue-core-video-player
                                v-if="video.type === 'private_video'"
                                :src="url"
                                :autoplay="false"
                                :playeroptions="playerOptions"
                            >
                            </vue-core-video-player>
                            <youtube-video v-else :video="video" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card-body table-responsive">
                            <ul
                                class="list-group list-group-unbordered"
                                v-for="video in videos"
                                :key="video.id"
                            >
                                <li class="list-group-item">
                                    <a :href="`/watch_video/${video.id}`">
                                        {{ video.title }}</a
                                    >
                                    <button
                                        @click="downloadVideo(video.id)"
                                        v-if="video.type === 'private_video'"
                                        target="blank"
                                        class="btn btn-flat float-right"
                                    >
                                        <i class="fa fa-download"></i>
                                    </button>
                                    <a
                                        v-else
                                        :href="`https://www.ssyoutube.com/watch?v=${video.video_path}`"
                                        target="blank"
                                        class="btn btn-flat float-right"
                                    >
                                        <i class="fa fa-download"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- /.card-body -->
                        <!-- <div class="card-footer">
                  <pagination :data="videos" @pagination-change-page="getVideos"></pagination>
              </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import HLSCore from "@core-player/playcore-hls";
import YoutubeVideo from "./YoutubeVideo.vue";

// Similarly, you can also introduce the plugin resource pack you want to use within the component
// import 'some-videojs-plugin'

export default {
    components: {
        YoutubeVideo,
    },
    data() {
        return {
            videos: {},
            video: {},
            videoId: "xrqI4rXW3dM",
            HLSCore,
            video_path: "image",
            url: "",
            playerOptions: {
                // videojs options
                muted: true,

                language: "en",
                playbackRates: [0.7, 1.0, 1.5, 2.0],
                sources: [
                    {
                        type: "video/mp4",
                        //src: '/videos/'+this.$route.params.id,
                    },
                ],
                // poster: "/img/user-cover.png",
            },
        };
    },

    computed: {
        player() {
            return this.$refs.videoPlayer.player;
        },
        //   videoUrl(){
        //          return this.getVideo()
        //      }
    },
    mounted() {
        console.log("url", this.url);
    },

    methods: {
        loadVideos() {
            if (this.$gate.isAdminOrTutorOrStudentOrParent()) {
                axios.get("/api/videos").then((res) => {
                    this.videos = res.data.data;
                    // console.log(this.videos);
                });
            }
        },
        playSelected(selected) {
            location.replace("/watch_video/" + selected);
            // this.playerOptions.sources[0].src='/videos/'+selected;
        },
        // listen events
        onPlayerPlay(player) {
            // console.log('player play!', player)
        },
        onPlayerPause(player) {
            // console.log('player pause!', player)
        },
        getVideo() {
            axios
                .get(`/api/getVideo/${this.$route.params.id}`)
                .then((res) => {
                    console.log("/videos/" + res.data.video_path);
                    return "/videos/" + res.data.video_path;
                })
                .catch((err) => "");
        },
        playerReadied(player) {
            console.log("the player is readied", player);
            // you can use it to do something...
            // player.[methods]
        },
        downloadVideo(id) {
            axios
                .get("/api/videos/" + id, { responseType: "arraybuffer" })
                .then((response) => {
                    let blob = new Blob([response.data], {
                        type: "application/mp4",
                    });
                    let link = document.createElement("a");
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "lesson_video.mp4";
                    link.click();
                });
        },
    },
    created() {
        axios.get(`/api/getVideo/${this.$route.params.id}`).then((res) => {
            this.video = res.data;
            this.url = "/videos/" + res.data.video_path;

            //  return '/videos/'+res.data.video_path
        });
        this.loadVideos();
    },
};
</script>

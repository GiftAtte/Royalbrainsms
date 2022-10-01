<template>
    <div class="col-md-12">
        <section>
            <youtube
                class="my-5"
                :video-id="video.video_path"
                @ready="ready"
                @playing="playing"
                width="100%"
            ></youtube>
        </section>
        <!-- <section>
            <h2>add options</h2>
            <youtube
                :video-id="videoId"
                player-width="1280"
                player-height="750"
                :player-vars="{ autoplay: 1 }"
            ></youtube>
        </section> -->
    </div>
</template>
<script>
export default {
    props: ["video"],
    components: {},
    data() {
        return {
            title: "Video Title",
        };
    },
    methods: {
        ready(event) {
            this.player = event.target;
        },
        playing(event) {
            // The player is playing a video.
        },
        change() {
            // when you change the value, the player will also change.
            // If you would like to change `playerVars`, please change it before you change `videoId`.
            // If `playerVars.autoplay` is 1, `loadVideoById` will be called.
            // If `playerVars.autoplay` is 0, `cueVideoById` will be called.
            this.videoId = "another video id";
        },
        stop() {
            this.player.stopVideo();
        },
        pause() {
            this.player.pauseVideo();
        },
    },
};
</script>

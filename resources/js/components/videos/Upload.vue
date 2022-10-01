<template>
    <app-body pageTitle="Videos" pageSubTitle="Video List">
        <app-table
            :headers="tbHeaders"
            :data="tbData.data"
            :deleteAction="deleteVideo"
            :updateAction="updateVideo"
            :createAction="createVideo"
            createButton="true"
            :form="form"
            action="true"
            search="true"
            title="Video List"
            updateTitle="Update Video Info"
        >
            <template #extra-action>
                <th>Title</th>
                <th>Uploaded By</th>
                <th>Level</th>
                <th>Created On</th>
                <th>Action</th>
            </template>
            <template v-slot:extra-action-body="{ row }">
                <td>{{ row.title }}</td>
                <td>{{ row.author ? row.author.name : "" }}</td>
                <td>
                    {{ row.levels ? row.levels.level_name : "" }}
                </td>
                <td>{{ row.created_at | myDate }}</td>

                <td class="row">
                    <router-link
                        :to="`/watch_video/${row.id}`"
                        class="btn btn-flat btn-sm"
                    >
                        <i class="fa fa-eye"></i>
                    </router-link>

                    <a
                        :href="`https://www.ssyoutube.com/watch?v=${row.video_path}`"
                        target="blank"
                        @click="downloadVideo(video.id)"
                        class="btn btn-flat"
                    >
                        <i class="fa fa-download"></i>
                    </a>
                </td>
            </template>

            <template #card-footer>
                <pagination
                    :data="tbData"
                    @pagination-change-page="paginateData"
                />
            </template>
            <template #modal-fields>
                <loading
                    :active.sync="isLoading"
                    :can-cancel="false"
                    :on-cancel="onCancel"
                    color="blue"
                    :is-full-page="fullPage"
                ></loading>
                <input-field
                    label="Title"
                    v-model="form.title"
                    id="title"
                    :form="form"
                    field="title"
                    type="text"
                    placeholder="Eg Math viedes"
                />
                <select-box
                    label="Level"
                    :form="form"
                    v-model="form.level_id"
                    placeholder="Select Level"
                    field="leve_id"
                    :options="levels"
                    id="leve_id"
                    name="leve_id"
                    optionLabel="level_name"
                    optionValue="id"
                    feild="level_id"
                />
                <select-box
                    label="UPLOAD TYPE"
                    :form="form"
                    v-model="form.type"
                    placeholder="Select Level"
                    field="videoType"
                    :options="videoType"
                    id="videoType"
                    name="videoType"
                    optionLabel="type"
                    optionValue="value"
                    feild="type"
                />
                <input-field
                    v-if="form.type === 'youtube_video'"
                    label="Youtube Video ID"
                    v-model="form.video_path"
                    id="video_path"
                    :form="form"
                    field="video_path"
                    type="text"
                    placeholder=" eg rt5yk4knkjnjkHYq"
                />
                <div class="form-group my-1">
                    <label> Select Video File (mp4 file)</label>
                </div>
                <input
                    v-if="form.type === 'private_video'"
                    id="file"
                    type="file"
                    ref="file"
                    @change="setFile"
                />
            </template>
        </app-table>
    </app-body>
</template>

<script>
import Loading from "vue-loading-overlay";

export default {
    components: { Loading },
    data() {
        return {
            videoType: [
                { type: "Youtube Video", value: "youtube_video" },
                { type: "Private Video", value: "private_video" },
            ],
            editmode: false,
            tbHeaders: [],
            tbData: [],
            videos: {},
            levels: [],
            subjects: {},
            file: "",
            isLoading: false,
            form: new Form({
                id: "",
                level_id: "",
                title: "",
                video_path: "",
                type: "",
            }),
        };
    },
    mounted() {
        axios.get("/api/get_levels").then((res) => {
            this.levels = res.data;
        });
    },
    methods: {
        paginateData(page = 1) {
            axios.get("/api/videos?page=" + page).then((response) => {
                this.tbData = response.data;
            });
        },

        updateVideo() {
            this.$Progress.start();
            // console.log('Editing data');
            this.form
                .post("/api/videos")
                .then(() => {
                    // success
                    $("#appModal").modal("hide");
                    swal.fire(
                        "Updated!",
                        "Information has been updated.",
                        "success"
                    );
                    this.$Progress.finish();
                    Fire.$emit("AfterCreate");
                })
                .catch(() => {
                    this.$Progress.fail();
                });
        },
        editModal(video) {
            this.editmode = true;
            this.form.reset();
            $("#apModal").modal("show");
            this.form.fill(video);
        },
        newModal() {
            this.editmode = false;
            this.form.reset();
            $("#appModal").modal("show");
        },
        deleteVideo(id) {
            this.form
                .delete("/api/videos/" + id)
                .then(() => {
                    Fire.$emit("AfterCreate");
                })
                .catch(() => {
                    swal.fire(
                        "Failed!",
                        "There was something wronge.",
                        "warning"
                    );
                });
        },
        loadVideos() {
            if (this.$gate.isAdminOrTutorOrStudentOrStudent()) {
                axios.get("/api/videos").then((res) => {
                    this.tbData = res.data;
                });
            }
        },

        createVideo() {
            this.isLoading = true;
            const formData = new FormData();
            formData.append("file", this.file);
            formData.append("level_id", this.form.level_id);
            formData.append("title", this.form.title);
            formData.append("video_path", this.form.video_path);
            formData.append("type", this.form.type);
            console.log(this.file);
            axios
                .post("/api/videos", formData)
                .then((res) => {
                    $("#appModal").modal("hide");
                    toast.fire({
                        type: "success",
                        title: "Video successfully uploaded",
                    });
                    console.log(res.data);
                    this.$Progress.finish();
                    this.isLoading = false;
                    Fire.$emit("AfterCreate");
                })
                .catch((err) => {
                    this.$Progress.fail();
                    this.isLoading = false;
                    toast.fire({
                        type: "failure",
                        title: "there was error uploading the file" + err,
                    });
                    console.log(err);
                });
        },
        setFile() {
            this.file = this.$refs.file.files[0];
            console.log(this.file);
        },

        loadVideos() {
            axios.get("/api/videos").then((res) => {
                this.tbData = res.data;
                console.log(this.videos);
            });
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
        Fire.$on("searching", () => {
            let query = this.$parent.search;
            axios
                .get("/api/findLesson?q=" + query)
                .then((data) => {
                    this.assignments = data.data;
                })
                .catch(() => {});
        });
        this.loadVideos();
        Fire.$on("AfterCreate", () => {
            this.loadVideos();
        });
        $("#addNew").modal("hide");
        //    setInterval(() => this.loadUsers(), 3000);
    },
};
</script>

<style>
.modal-header {
    background-color: navy;
    color: white;
    text-transform: uppercase;
}
</style>

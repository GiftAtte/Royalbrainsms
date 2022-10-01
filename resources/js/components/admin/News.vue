<template>
    <app-body pageTitle="New Feed" pageSubTitle="School News">
        <app-table
            :headers="tbHeaders"
            :data="tbData.data"
            :deleteAction="deleteNews"
            :updateAction="updateNews"
            :createAction="createNews"
            createButton="true"
            :form="form"
            action="true"
            search="true"
            title="News Updates"
            updateTitle="Update News"
            modalTitle="Create News"
            tb_classes="table-bordered"
        >
            <template #extra-action>
                <th colspan="2">News Body</th>
                <th>Created By</th>
                <th>Created On</th>
                <th class="text-danger">Publish</th>
            </template>

            <template v-slot:extra-action-body="{ row }">
                <td colspan="2" style="width: 40%">{{ row.content }}</td>
                <td>{{ row.users ? row.users.name : "" }}</td>
                <td>{{ row.created_at | myDate }}</td>
                <td>
                    <toggle-button
                        @change="publish(row.id)"
                        :label="true"
                        :labels="{
                            checked: 'ON',
                            unchecked: 'OFF',
                        }"
                        :height="20"
                        :font-size="14"
                        :value="row.isPublished"
                        :color="'green'"
                        :name="'activated'"
                        class="pl-2"
                    />
                </td>
            </template>
            <template #card-footer>
                <pagination
                    :data="tbData"
                    @pagination-change-page="paginateData"
                />
            </template>
            <template #modal-fields>
                <input-field
                    label="Headline"
                    v-model="form.title"
                    id="title"
                    :form="form"
                    field="title"
                    type="text"
                    placeholder="Enter News Headline"
                />

                <div class="form-group">
                    <label for="">News Content</label>
                    <textarea
                        v-model="form.content"
                        id=""
                        rows="4"
                        class="form-control"
                    >
                    </textarea>
                </div>
                <div class="form-group">
                    <label>Upload Image</label>
                    <input
                        class="form-control"
                        id="file"
                        :form="form"
                        field="file"
                        type="file"
                        ref="file"
                        @change="setFile"
                    />
                </div>
            </template>
        </app-table>
    </app-body>
</template>

<script>
export default {
    data() {
        return {
            tbHeaders: [{ header: "Title", key: "title" }],
            tbData: [],
            user: window.user,
            file: "",
            form: new Form({
                id: "",
                title: "",
                content: "",
                created_by: "",
                isPublished: "",
                school_id: "",
                post_img: "",
            }),
        };
    },
    methods: {
        paginateData(page = 1) {
            axios
                .get(`/api/news/page?=${page}`)
                .then((res) => {
                    this.tbData = res.data;
                    // setTimeout(() => {
                    //      this.componentKey += 1;
                    // },200)
                })
                .catch((err) => console.log(err));
        },

        loadNews() {
            axios.get("api/news").then((res) => {
                this.tbData = res.data;
            });
        },

        createNews() {
            let formData = new FormData();
            formData.append("post_img", this.file);
            formData.append("title", this.form.title);
            formData.append("content", this.form.content);

            axios.post("/api/news", formData).then((res) => {
                $("#appModal").modal("hide");
                swal.fire("success!", " Meeting  successfully.", "success");

                Fire.$emit("afterCreated");
            });
        },

        updateNews() {
            if (this.file) {
                let formData = new FormData();
                formData.append("post_img", this.file);
                formData.append("title", this.form.title);
                formData.append("content", this.form.content);
                formData.append("id", this.form.id);
                formData.append("isPublished", this.form.isPublished);
                formData.append("school_id", this.form.school_id);

                axios.post("/api/news/update", formData).then((res) => {
                    $("#appModal").modal("hide");
                    swal.fire("success!", " Meeting  successfully.", "success");

                    Fire.$emit("afterCreated");
                });
            } else {
                this.form.post("/api/news/update").then((res) => {
                    $("#appModal").modal("hide");
                    swal.fire(
                        "success!",
                        " News updated successfully.",
                        "success"
                    );

                    Fire.$emit("afterCreated");
                });
            }
        },
        deleteNews(id) {
            axios
                .delete("/api/news/" + id)
                .then((res) => Fire.$emit("afterCreated"));
        },

        setFile() {
            this.file = this.$refs.file.files[0];
            console.log(this.$refs.file.files[0]);
        },
        publish(id) {
            axios
                .put("/api/news/" + id)
                .then((res) => Fire.$emit("afterCreated"));
        },
    },

    created() {
        this.loadNews();

        Fire.$on("afterCreated", () => {
            this.loadNews();
        });
    },
};
</script>

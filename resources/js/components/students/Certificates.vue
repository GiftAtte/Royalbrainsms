<template>
    <app-body pageTitle="Certificates" pageSubTitle="Certificate list">
        <app-table
            :headers="tbHeaders"
            :data="tbData"
            :deleteAction="deletCertificate"
            :updateAction="updateCertificate"
            :createAction="createCertificate"
            :form="form"
            action="true"
            search="true"
            createButton="true"
            title="certificate list"
            updateTitle="Update certificate"
        >
            <template #addButton></template>

            <template #extra-action>
                <th>Created By</th>
                <th>Created On</th>
            </template>
            <template v-slot:extra-action-body="{ row }">
                <td>{{ row.user.name }}</td>
                <td>{{ row.created | myDate }}</td>
            </template>

            <template #modal-fields>
                <input-field
                    label="Certificate Title"
                    v-model="form.title"
                    id="name"
                    :form="form"
                    field="name"
                    placeholder="Enter certificate name"
                />
                <input-field
                    label="Description"
                    v-model="form.description"
                    id="name"
                    :form="form"
                    field="description"
                    placeholder="Certificate description"
                />
            </template>
        </app-table>
    </app-body>
</template>

<script>
export default {
    data() {
        return {
            editmode: true,
            componentKey: 0,
            updateTitle: "Updat certificate",
            tbHeaders: [{ header: "Title", key: "title" },
                        { header:"Description",key:'description' },
            
        ],
            tbData: [],

            form: new Form({
                id: "",
                title: "",
                description: "",
            }),
        };
    },
    methods: {
        loadCertificate() {
            axios
                .get("/api/certificates")
                .then((res) => {
                    this.tbData = res.data;
                    // setTimeout(() => {
                    //      this.componentKey += 1;
                    // },200)
                })
                .catch((err) => console.log(err));
        },
        editCertificate() {},
        createCertificate() {
            this.form.post("/api/certificates").then((res) => {
                $("#appModal").modal("hide");
                swal.fire(
                    "success!",
                    "certificate created successfully.",
                    "success"
                );

                Fire.$emit("afterCreated");
            });
        },
        deletCertificate(id) {
            axios
                .delete("/api/certificates/" + id)
                .then((res) => Fire.$emit("afterCreated"));
        },
        updateCertificate() {
            this.form.post("/api/certificates/update").then((res) => {
                $("#appModal").modal("hide");
                swal.fire(
                    "success!",
                    "Certificate created successfully.",
                    "success"
                );

                Fire.$emit("afterCreated");
            });
        },

        handleChange() {},
    },
    mounted() {
        this.loadCertificate();
    },
    created() {
        Fire.$on("afterCreated", () => {
            this.loadCertificate();
        });
    },
};
</script>

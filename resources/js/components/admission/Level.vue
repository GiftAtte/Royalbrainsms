<template>
    <app-body pageTitle="Admission" pageSubTitle="Admission Levels">
        <app-table
            :headers="tbHeaders"
            :data="tbData"
            :deleteAction="deleteLevel"
            :updateAction="updateLevel"
            :createAction="createLevel"
            :form="form"
            action="true"
            search="true"
            createButton="true"
            title="Level list"
            updateTitle="Update Level"
        >
            <template #addButton></template>

            <template #extra-action>
                <th>Created On</th>
            </template>
            <template v-slot:extra-action-body="{ row }">
                <td>{{ row.created | myDate }}</td>
            </template>

            <template #modal-fields>
                <input-field
                    label="Level Name"
                    v-model="form.name"
                    id="name"
                    :form="form"
                    field="name"
                    placeholder="Enter level name"
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
            tbHeaders: [{ header: "Category", key: "name" }],
            tbData: [],

            form: new Form({
                id: "",
                name: "",
                school_id: "",
            }),
        };
    },
    methods: {
        loadLevel() {
            axios
                .get("/api/admission/levels")
                .then((res) => {
                    this.tbData = res.data;
                    // setTimeout(() => {
                    //      this.componentKey += 1;
                    // },200)
                })
                .catch((err) => console.log(err));
        },
        editCategory() {},
        createLevel() {
            this.form.post("/api/admission/levels").then((res) => {
                $("#appModal").modal("hide");
                swal.fire("success!", "Level created successfully.", "success");

                Fire.$emit("afterCreated");
            });
        },
        deleteLevel(id) {
            axios
                .delete("/api/admission/levels/" + id)
                .then((res) => Fire.$emit("afterCreated"));
        },
        updateLevel() {
            this.form.put("/api/admission/levels").then((res) => {
                $("#appModal").modal("hide");
                swal.fire("success!", "Level created successfully.", "success");

                Fire.$emit("afterCreated");
            });
        },

        handleChange() {},
    },
    mounted() {
        this.loadLevel();
    },
    created() {
        Fire.$on("afterCreated", () => {
            this.loadLevel();
        });
    },
};
</script>

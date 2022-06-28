<template>
    <app-body pageTitle="Category" pageSubTitle="Category list">
        <app-table
            :headers="tbHeaders"
            :data="tbData"
            :deleteAction="deletCategory"
            :updateAction="updateCategory"
            :createAction="createCategory"
            :form="form"
            action="true"
            search="true"
            createButton="true"
            title="Category list"
            updateTitle="Update Category"
        >
            <template #modal-fields>
                <input-field
                    label="Category Name"
                    v-model="form.name"
                    id="name"
                    :form="form"
                    field="name"
                    placeholder="Enter category name"
                />
                <select-box
                    label="Category Type"
                    :form="form"
                    v-model="form.type"
                    placeholder="Select category Type"
                    field="type"
                    :options="typeOptions"
                    id="type"
                    name="type"
                    optionValue="value"
                    optionLabel="label"
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
            updateTitle: "Update Category",
            tbHeaders: [
                { header: "Category", key: "name", id: 1 },
                { header: "Type", key: "type", id: 1 },
            ],
            tbData: [],
            typeOptions: [
                { id: 1, label: "Running Assets", value: "Running Assets" },
                { id: 2, label: "Fixed Assets", value: "Fixed Assets" },
                { id: 3, label: "Stock", value: "Stock" },
            ],
            form: new Form({
                id: "",
                name: "",
                type: "",
            }),
        };
    },
    methods: {
        loadCategory() {
            axios
                .get("/api/inventory/category")
                .then((res) => {
                    this.tbData = res.data;
                    // setTimeout(() => {
                    //      this.componentKey += 1;
                    // },200)
                })
                .catch((err) => console.log(err));
        },
        editCategory() {},
        createCategory() {
            this.form.post("/api/inventory/category").then((res) => {
                $("#appModal").modal("hide");
                swal.fire(
                    "success!",
                    "Category created successfully.",
                    "success"
                );

                Fire.$emit("afterCreated");
            });
        },
        deletCategory(id) {
            axios
                .delete("/api/inventory/category/" + id)
                .then((res) => Fire.$emit("afterCreated"));
        },
        updateCategory() {
            this.form.put("/api/inventory/category").then((res) => {
                $("#appModal").modal("hide");
                swal.fire(
                    "success!",
                    "Category created successfully.",
                    "success"
                );

                Fire.$emit("afterCreated");
            });
        },

        handleChange() {},
    },
    mounted() {
        this.loadCategory();
    },
    created() {
        Fire.$on("afterCreated", () => {
            this.loadCategory();
        });
    },
};
</script>

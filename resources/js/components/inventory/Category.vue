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
            <template #addButton></template>

            <template #extra-action>
                <th>Created On</th>
            </template>
            <template v-slot:extra-action-body="{ row }">
                <td>{{ row.created_at | myDate }}</td>
            </template>

            <template #modal-fields>
                <input-field
                    label="Category Name"
                    v-model="form.name"
                    id="name"
                    :form="form"
                    field="name"
                    placeholder="Enter category name"
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
            tbHeaders: [{ header: "Category", key: "name" }],
            tbData: [],

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

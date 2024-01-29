<template>
    <app-body pageTitle="Product Unit" pageSubTitle="Product unit list">
        <app-table
            :headers="tbHeaders"
            :data="tbData"
            :deleteAction="deletProductUnit"
            :updateAction="updateProductUnit"
            :createAction="createProductUnit"
            :form="form"
            action="true"
            search="true"
            createButton="true"
            title="Product Unit list"
            updateTitle="Update Product unit"
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
                    label="Product Unit"
                    v-model="form.name"
                    id="name"
                    :form="form"
                    field="name"
                    placeholder="Enter Product unit"
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
            updateTitle: "Update Unit",
            tbHeaders: [{ header: "Unit", key: "name" }],
            tbData: [],

            form: new Form({
                id: "",
                name: "",
         
            }),
        };
    },
    methods: {
        loadProductUnit() {
            axios
                .get("/api/product-units")
                .then((res) => {
                    this.tbData = res.data.data.product_units;
                
                })
                .catch((err) => console.log(err));
        },
        editProductUnit() {},
        createProductUnit() {
            this.form.post("/api/product-units").then((res) => {
                $("#appModal").modal("hide");
                swal.fire(
                    "success!",
                    "unit created successfully.",
                    "success"
                );

                Fire.$emit("afterCreated");
            });
        },
        deletProductUnit(id) {
            axios
                .delete("/api/product-units/" + id)
                .then((res) => Fire.$emit("afterCreated"));
        },
        updateProductUnit() {
            this.form.put("/api/product-units/"+this.form.id).then((res) => {
                $("#appModal").modal("hide");
                swal.fire(
                    "success!",
                    "unit created successfully.",
                    "success"
                );

                Fire.$emit("afterCreated");
            });
        },

        handleChange() {},
    },
    mounted() {
        this.loadProductUnit();
    },
    created() {
        Fire.$on("afterCreated", () => {
            this.loadProductUnit();
        });
    },
};
</script>

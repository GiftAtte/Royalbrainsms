<template>
    <app-body pageTitle="School Items" pageSubTitle="Items list">
        <app-table
            :headers="tbHeaders"
            :data="tbData"
            :deleteAction="deletItem"
            :updateAction="updateItem"
            :createAction="createItem"
            createButton="true"
            :form="form"
            action="true"
            search="true"
            title="School Item list"
            updateTitle="Update Item"
        >
            <template #modal-fields>
                <input-field
                    label="Item Name"
                    v-model="form.name"
                    id="name"
                    :form="form"
                    field="name"
                    placeholder="Enter Item name"
                />
                <select-box
                    label="Category"
                    :form="form"
                    v-model="form.category_id"
                    placeholder="Select category Type"
                    field="type"
                    :options="options"
                    id="category_id"
                    name="category_id"
                    optionLabel="name"
                    optionValue="id"
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
            options: [],
            tbHeaders: [
                { header: "Items Name", key: "name", id: 1 },
                { header: "Category", key: "category", id: 2 },
            ],
            tbData: [],
            options: [],
            form: new Form({
                id: "",
                name: "",
                category_id: "",
                school_id: "",
            }),
        };
    },
    methods: {
        loadCategory() {
            axios
                .get("/api/inventory/category")
                .then((res) => {
                    this.options = res.data;
                })
                .catch((err) => console.log(err));
        },

        loadItems() {
            axios
                .get("/api/inventory/items")
                .then((res) => {
                    this.tbData = res.data;
                    console.log(res.data);
                })
                .catch((err) => console.log(err));
        },
        editItem() {},
        createItem() {
            this.form.post("/api/inventory/items").then((res) => {
                $("#appModal").modal("hide");
                swal.fire("success!", "Item created successfully.", "success");

                Fire.$emit("afterCreated");
            });
        },
        deletItem(id) {
            axios
                .delete("/api/inventory/items/" + id)
                .then((res) => Fire.$emit("afterCreated"));
        },
        updateItem() {
            this.form.put("/api/inventory/items").then((res) => {
                $("#appModal").modal("hide");
                swal.fire(
                    "success!",
                    "Category created successfully.",
                    "success"
                );

                Fire.$emit("afterCreated");
            });
        },
    },
    mounted() {
        this.loadCategory();
    },
    created() {
        this.loadItems();
        Fire.$on("afterCreated", () => {
            this.loadItems();
        });
    },
};
</script>

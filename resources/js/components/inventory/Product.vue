<template>
    <app-body pageTitle="Products" pageSubTitle="Product list">
        <app-table
            :headers="tbHeaders"
            :data="tbData"
            :deleteAction="deletProducts"
            :updateAction="updateProducts"
            :createAction="createProducts"
            createButton="true"
            :form="form"
            action="true"
            search="true"
            title="Product list"
            updateTitle="Update Category"
            cart="true"
        >
            <template #modal-fields>
                <input-field
                    label="Product Name"
                    v-model="form.name"
                    id="name"
                    :form="form"
                    field="name"
                    placeholder="Enter category name"
                />
                <input-field
                    label="Product Code"
                    v-model="form.product_code"
                    id="product_code"
                    :form="form"
                    field="product_code"
                    placeholder="eg bk-01"
                />
                <input-field
                    label="Description"
                    v-model="form.description"
                    id="description"
                    :form="form"
                    field="description"
                    placeholder="eg jss1 textbook by prof. ABC"
                />
                <input-field
                    label="Per Roll/Catton Quantity"
                    v-model="form.quantity_per_roll"
                    id="quantity_per_roll"
                    :form="form"
                    type="number"
                    field="quantity_per_roll"
                    placeholder="Eg 10 per roll"
                />
                <select-box
                    label="Category"
                    :form="form"
                    v-model="form.category_id"
                    placeholder="Select category Type"
                    field="category_id"
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
            updateTitle: "Product Category",
            tbHeaders: [
                { header: "Products", key: "name" },
                { header: "category", key: "category" },
            ],
            tbData: [],
            options: [],
            form: new Form({
                id: "",
                name: "",
                description: "",
                product_code: "",
                category_id: "",
                quantity_per_roll: "",
                school_id: "",
            }),
        };
    },
    methods: {
        loadCategories() {
            axios
                .get("/api/inventory/category")
                .then((res) => {
                    this.options = res.data;
                    // setTimeout(() => {
                    //      this.componentKey += 1;
                    // },200)
                })
                .catch((err) => console.log(err));
        },
        loadProducts() {
            axios
                .get("/api/inventory/products")
                .then((res) => {
                    this.tbData = res.data;
                    // setTimeout(() => {
                    //      this.componentKey += 1;
                    // },200)
                })
                .catch((err) => console.log(err));
        },
        createProducts() {
            this.form.post("/api/inventory/products").then((res) => {
                $("#appModal").modal("hide");
                swal.fire(
                    "success!",
                    " A Product created successfully.",
                    "success"
                );

                Fire.$emit("afterCreated");
            });
        },
        deletProducts(id) {
            axios
                .delete("/api/inventory/products/" + id)
                .then((res) => Fire.$emit("afterCreated"));
        },
        updateProducts() {
            this.form.put("/api/inventory/products").then((res) => {
                $("#appModal").modal("hide");
                swal.fire(
                    "success!",
                    "Products created successfully.",
                    "success"
                );

                Fire.$emit("afterCreated");
            });
        },

        handleChange() {},
    },
    mounted() {
        this.loadCategories();
        this.loadProducts();
    },
    created() {
        Fire.$on("afterCreated", () => {
            this.loadProducts();
        });
    },
};
</script>

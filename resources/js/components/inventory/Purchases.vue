<template>
    <app-body pageTitle="Purchases" pageSubTitle="Purchase list">
        <app-table
            :headers="tbHeaders"
            :data="tbData"
            :deleteAction="deletePurchase"
            :updateAction="createPurchase"
            :createAction="createPurchase"
            createButton="true"
            :form="form"
            action="false"
            search="true"
            title="Purchase list"
            updateTitle="Update Purchases"
            cart="true"
        >
            <template #modal-fields>
                <select-box
                    label="Products List"
                    :form="form"
                    v-model="form.product_id"
                    placeholder="Select product Type"
                    field="product_id"
                    :options="options"
                    id="product_id"
                    name="product_id"
                    optionLabel="name"
                    optionValue="id"
                    feild="product_id"
                />
                <input-field
                    label="Quantity"
                    v-model="form.quantity"
                    id="quantity"
                    :form="form"
                    field="quantity"
                    type="number"
                    placeholder="Eg 5"
                    min="1"
                    step="any"
                />
                <input-field
                    label="Total Cost(NGN)"
                    v-model="form.total_cost"
                    id="total_cost"
                    :form="form"
                    field="total_cost"
                    placeholder="100.00"
                    min="1.00"
                    step="any"
                    type="number"
                    name="total_cost"
                />
                <input-field
                    label="Date Purchased"
                    v-model="form.purchased_date"
                    id="purchased_date"
                    :form="form"
                    type="date"
                    name="Date Purchased"
                    field="purchased_date"
                />
                <select-box
                    label="Products List"
                    :form="form"
                    v-model="form.supplier_id"
                    placeholder="Select Supplier"
                    field="supplier_id"
                    :options="supplier"
                    id="supplier_id"
                    name="supplier_id"
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
                { header: "Products", key: "product" },
                { header: "Category", key: "category" },
                { header: "Quantity", key: "quantity" },
                { header: "Cost P/Unit(NGN)", key: "unit_cost" },
                { header: "Total Cost(NGN)", key: "total_cost" },
                { header: "Purchased Date", key: "purchased_date" },
            ],
            tbData: [],
            options: [],
            supplier: [],
            form: new Form({
                id: "",
                product_id: "",
                quantity: 1.0,
                supplier_id: "",
                total_cost: 0.0,
            }),
        };
    },
    methods: {
        loadSuppliers() {
            axios
                .get("/api/inventory/suppliers")
                .then((res) => {
                    this.supplier = res.data;
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
                    this.options = res.data;
                    // setTimeout(() => {
                    //      this.componentKey += 1;
                    // },200)
                })
                .catch((err) => console.log(err));
        },

        loadPurchase() {
            axios
                .get("/api/inventory/products/purchases")
                .then((res) => {
                    this.tbData = res.data;
                    // setTimeout(() => {
                    //      this.componentKey += 1;
                    // },200)
                })
                .catch((err) => console.log(err));
        },

        createPurchase() {
            this.form.post("/api/inventory/products/purchases").then((res) => {
                $("#appModal").modal("hide");
                swal.fire(
                    "success!",
                    " A Product created successfully.",
                    "success"
                );

                Fire.$emit("afterCreated");
            });
        },

        deletePurchase(id) {
            axios
                .delete("/api/inventory/products/purchases/" + id)
                .then((res) => Fire.$emit("afterCreated"));
        },
        updatePurchase() {
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
        this.loadPurchase();
        this.loadProducts();
        this.loadSuppliers();
    },
    created() {
        Fire.$on("afterCreated", () => {
            this.loadPurchase();
        });
    },
};
</script>

<template>
    <app-body pageTitle="Prices" pageSubTitle="Price list">
        <app-table
            :headers="tbHeaders"
            :data="tbData"
            :deleteAction="deletPrice"
            :updateAction="updatePrice"
            :createAction="createPrice"
            :form="form"
            createButton="true"
            action="true"
            search="true"
            title="Price list"
            updateTitle="Update Price"
        >
            <template #extra-action>
                <th class="text-danger">Status</th>
                <th class="text-danger">Actions</th>
            </template>

            <template v-slot:extra-action-body="slotProps">
                <td>Available</td>
                <td>
                    <router-link :to="`/inventory/stock`"> stock </router-link>
                </td>
            </template>
            <template #modal-fields>
                <select-box
                    label="Product"
                    :form="form"
                    v-model="form.product_id"
                    placeholder="Select product"
                    field="product_id"
                    :options="options"
                    id="product_id"
                    name="product_id"
                    createButton="true"
                    optionLabel="name"
                    optionValue="id"
                />
                <input-field
                    label="Cost Price"
                    v-model="form.cost_price"
                    id="cost_price"
                    :form="form"
                    field="cost_price"
                    placeholder="Eg 100.00"
                    type="number"
                />
                <input-field
                    label="Cost Price"
                    v-model="form.selling_price"
                    id="selling_price"
                    :form="form"
                    field="selling_price"
                    placeholder="Eg 100.00"
                    type="number"
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
            updateTitle: "Update Price",
            options: [],
            tbHeaders: [
                { header: "Products", key: "name" },
                { header: "Cost Price(NGN)", key: "cost_price" },
                { header: "Selling Price(NGN)", key: "selling_price" },
            ],
            tbData: [],
            options: [],
            form: new Form({
                id: "",
                product_id: "",
                cost_price: 0.0,
                selling_price: 0.0,
            }),
        };
    },
    methods: {
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

        loadPrice() {
            axios
                .get("/api/inventory/products/price")
                .then((res) => {
                    this.tbData = res.data;
                    // setTimeout(() => {
                    //      this.componentKey += 1;
                    // },200)
                })
                .catch((err) => console.log(err));
        },
        createPrice() {
            this.form.post("/api/inventory/products/price").then((res) => {
                $("#appModal").modal("hide");
                swal.fire("success!", "price created successfully.", "success");

                Fire.$emit("afterCreated");
            });
        },
        deletPrice(id) {
            axios
                .delete("/api/inventory/products/price/" + id)
                .then((res) => Fire.$emit("afterCreated"));
        },
        updatePrice() {
            this.form.put("/api/inventory/products/price").then((res) => {
                $("#appModal").modal("hide");
                swal.fire("success!", "price created successfully.", "success");

                Fire.$emit("afterCreated");
            });
        },
    },
    mounted() {
        this.loadPrice();
        this.loadProducts();
    },
    created() {
        Fire.$on("afterCreated", () => {
            this.loadPrice();
        });
    },
};
</script>

<template>
    <app-body pageTitle="Supplier" pageSubTitle="Supplier list">
        <app-table
            :headers="tbHeaders"
            :data="tbData"
            :deleteAction="deleteSupplier"
            :updateAction="updateSupplier"
            :createAction="createSupplier"
            createButton="true"
            :form="form"
            action="true"
            search="true"
            title="Supplier list"
            updateTitle="Update Supplier"
        >
            <template #modal-fields>
                <input-field
                    label="Full Name"
                    v-model="form.name"
                    id="name"
                    :form="form"
                    field="name"
                    placeholder="Supplier's Name"
                />
                <input-field
                    label="Email"
                    v-model="form.email"
                    id="email"
                    :form="form"
                    field="email"
                    placeholder="email address"
                />
                <input-field
                    label="Phone Number"
                    v-model="form.phone"
                    id="phone"
                    :form="form"
                    field="name"
                    placeholder="Enter phone number"
                />
                <input-field
                    label="Contact Address"
                    v-model="form.address"
                    id="address"
                    :form="form"
                    field="address"
                    placeholder="Enter contact/company address"
                />
                <input-field
                    label="Supplier's Product(s)"
                    v-model="form.products"
                    id="products"
                    :form="form"
                    field="products"
                    placeholder="eg books,uniform etc"
                />
                <input-field
                    label="Bank Details"
                    v-model="form.bank_details"
                    id="bank_details"
                    :form="form"
                    field="bank_details"
                    placeholder="Supplier's Name"
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
            updateTitle: "Update Supplire's Info",
            tbHeaders: [
                { header: "Name", key: "name" },
                { header: "Phone Number", key: "phone" },
                { header: "Products", key: "products" },
            ],
            tbData: [],

            form: new Form({
                id: "",
                name: "",
                phone: "",
                address: "",
                email: "",
                products: "",
                school_id: "",
                bank_details: "",
            }),
        };
    },
    methods: {
        loadSuppliers() {
            axios
                .get("/api/inventory/suppliers")
                .then((res) => {
                    this.tbData = res.data;
                    // setTimeout(() => {
                    //      this.componentKey += 1;
                    // },200)
                })
                .catch((err) => console.log(err));
        },
        createSupplier() {
            this.form.post("/api/inventory/suppliers").then((res) => {
                $("#appModal").modal("hide");
                swal.fire(
                    "success!",
                    "Category created successfully.",
                    "success"
                );

                Fire.$emit("afterCreated");
            });
        },
        deleteSupplier(id) {
            axios
                .delete("/api/inventory/suppliers/" + id)
                .then((res) => Fire.$emit("afterCreated"));
        },
        updateSupplier() {
            this.form.put("/api/inventory/suppliers").then((res) => {
                $("#appModal").modal("hide");
                swal.fire(
                    "success!",
                    "Supplier created successfully.",
                    "success"
                );

                Fire.$emit("afterCreated");
            });
        },
    },
    mounted() {
        this.loadSuppliers();
    },
    created() {
        Fire.$on("afterCreated", () => {
            this.loadSuppliers();
        });
    },
};
</script>

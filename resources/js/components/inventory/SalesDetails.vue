<template>
    <app-body pageTitle="Sales Details" pageSubTitle="Sales Details">
        <app-table
            :headers="tbHeaders"
            :data="tbData"
            search="true"
            title="Sales Details"
        >
            <template #extra-row>
                <tr>
                    <th colspan="3">TOTAL</th>
                    <th>NGN{{ getTotal() }}</th>
                </tr>
            </template>
        </app-table>
    </app-body>
</template>

<script>
import { mapGetters, mapMutations } from "vuex";
import Templates from "../results/Templates.vue";
export default {
    components: { Templates },
    data() {
        return {
            editmode: true,
            componentKey: 0,
            updateTitle: "Update Cart",
            options: [],
            isPartial: false,
            tbHeaders: [
                { header: "Items", key: "name" },
                { header: "Quantity", key: "quantity" },
                { header: "Unit Price", key: "price" },
                { header: "Amount", key: "amount" },
            ],
            tbData: [],
            options: [
                { name: "Cash Payment", value: "cash" },
                { name: "Mobile Transfer", value: "Mobile Transfer" },
                { name: "POS Payment", value: "POS" },
                { name: "Paid As School Fees", value: "As School Fees" },
            ],
            form: new Form({
                products: [],
                student_id: "",
                payment_method: "",
                paid_amount: 0.0,
                total_amount: 0.0,
            }),
        };
    },
    methods: {
        ...mapGetters(["getProducts"]),
        ...mapMutations(["removeFromCart", "removeAllFromCart"]),

        toggleIsPartial() {
            this.isPartial = !this.isPartial;
        },
        navigate(link) {
            this.$router.push(link);
        },
        loadSales() {
            this.form.products = this.getProducts();
            this.form.post("/api/inventory/products/sell").then((res) => {
                $("#appModal").modal("hide");
                swal.fire("success!", "Transaction completed.", "success");

                this.removeAllFromCart();
            });
        },

        deleteItem(row) {
            swal.fire({
                title: "Are you sure",
                text: `You want to remove this item from the cart?`,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                // Send request to the server
                if (result.value) {
                    this.removeFromCart(row);
                    swal.fire("Deleted!", "Item removed", "success");
                }
            });
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
        getTotal() {
            let products = this.getProducts();
            console.log(products);
            if (products.length) {
                let sum = products.reduce(
                    (sum, item) => sum + Number(item.amount),
                    0
                );

                return sum.toFixed(2);
            } else {
                return "0.00";
            }
        },

        showPaymentModal() {
            this.form.paid_amount = this.getTotal();
            this.form.total_amount = this.getTotal();
            document.getElementById("createButton").disabled = true;
            $("#appModal").modal("show");
        },
        enableSell() {
            if (this.form.student_id.length && this.form.payment_method) {
                document.getElementById("createButton").disabled = false;
            }
        },
    },

    created() {},
};
</script>

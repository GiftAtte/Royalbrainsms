<template>
    <app-body pageTitle="Sales" pageSubTitle="Sales list">
        <app-table
            :headers="tbHeaders"
            :data="tbData"
            :updateAction="updateItem"
            :form="form"
            search="true"
            title="Sales List"
            updateTitle="Update Item"
            :sumitButtonText="'SELL'"
            :modalTitle="`Payment of NGN:${form.total_amount}`"
        >
            <template #extra-action>
                <th>Transaction Dete</th>
                <th class="text-danger">Actions</th>
            </template>
            <template v-slot:extra-action-body="{ row }">
                <td>{{ row.sell_date | myDate }}</td>
                <td>
                    <router-link
                        class="btn text-primary"
                        tag="a"
                        :to="`/inventory/sales/details/${row.id}`"
                    >
                        Details
                    </router-link>
                </td>
            </template>
            <template #extra-row>
                <tr>
                    <th colspan="2" class="text-primary">
                        TOTAL SALES:&nbsp;&nbsp; NGN{{ getTotalSales() }}
                    </th>
                    <th colspan="2" class="text-success">
                        TOTAL RECIEVED:&nbsp;&nbsp; NGN{{ getTotalRecieved() }}
                    </th>
                    <th colspan="2" class="text-danger">
                        TOTAL BALANCE:&nbsp;&nbsp; NGN{{ getBalance() }}
                    </th>
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
                { header: "Bought By", key: "name" },
                { header: "Items Bought", key: "products_count" },
                { header: "Total Amount", key: "total_amount" },
                { header: "Amount Paid", key: "paid_amount" },
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
            this.form.get("/api/inventory/sales").then((res) => {
                this.tbData = res.data;
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
        getTotalSales() {
            if (this.tbData.length) {
                let sum = this.tbData.reduce(
                    (sum, item) => sum + Number(item.total_amount),
                    0
                );

                return sum.toFixed(2);
            } else {
                return 0.0;
            }
        },
        getTotalRecieved() {
            if (this.tbData.length) {
                let sum = this.tbData.reduce(
                    (sum, item) => sum + Number(item.paid_amount),
                    0
                );

                return sum.toFixed(2);
            } else {
                return 0.0;
            }
        },

        getBalance() {
            return (this.getTotalSales() - this.getTotalRecieved()).toFixed(2);
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

    created() {
        this.loadSales();
    },
};
</script>

<template>
    <app-body pageTitle="Cart Items" pageSubTitle="Cart list">
        <app-table
            :headers="tbHeaders"
            :data="getProducts()"
            :deleteAction="deletItem"
            :updateAction="updateItem"
            :createAction="sellProducts"
            :form="form"
            search="true"
            title="School Item list"
            updateTitle="Update Item"
            :sumitButtonText="'SELL'"
            :modalTitle="`Payment of NGN:${form.total_amount}`"
        >
            <template #extra-action>
                <th class="text-danger">Actions</th>
            </template>
            <template v-slot:extra-action-body="{ row }">
                <td>
                    <button
                        title="Remove from cart"
                        class="btn btn-danger"
                        @click="deleteItem(row)"
                    >
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </template>
            <template #extra-row>
                <tr>
                    <th colspan="3">TOTAL</th>
                    <th>NGN{{ getTotal() }}</th>
                    <th>
                        <button
                            @click="showPaymentModal"
                            title="check out"
                            class="btn btn-primary rounded"
                        >
                            <i class="fa fa-check"></i>
                        </button>
                    </th>
                </tr>
            </template>
            <template #modal-fields>
                <input-field
                    label="STUDENT ID"
                    v-model="form.student_id"
                    id="student_id"
                    :form="form"
                    field="student_id"
                    placeholder="Enter student ID"
                    :onChange="enableSell()"
                />
                <select-box
                    label="PAYMENT METHOD"
                    :form="form"
                    v-model="form.payment_method"
                    placeholder="Select Payment Method "
                    field="payment_method"
                    :options="options"
                    id="payment_method"
                    name="payment_method"
                    optionLabel="name"
                    optionValue="value"
                    :onChange="enableSell()"
                />

                <div class="form-group form-check">
                    <input
                        @click="toggleIsPartial"
                        type="checkbox"
                        class="form-check-input"
                        id="exampleCheck1"
                        v-model="isPartial"
                    />
                    <label class="form-check-label" for="exampleCheck1"
                        >Partial Payment</label
                    >
                </div>
                <input-field
                    v-show="isPartial"
                    label="PARTIAL AMOUNT"
                    v-model="form.paid_amount"
                    id="paid_amount"
                    :form="form"
                    field="paid_amount"
                    type="number"
                    placeholder="Enter amount"
                />
            </template>
        </app-table>
        <div class="col-md-12">
            <button
                class="btn text-primary"
                @click="navigate('/inventory/stock')"
            >
                Add More Items
                <i class="fa fa-plus"></i>
                &rarr;
            </button>
        </div>
        <p class="text-center" v-if="!getProducts().length">Empty cart</p>
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
        sellProducts() {
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

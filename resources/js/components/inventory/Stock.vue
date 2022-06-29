<template>
    <app-body pageTitle="Stock" pageSubTitle="Stock list">
        <app-table
            :headers="tbHeaders"
            :data="tbData"
            :form="form"
            search="true"
            title="Stock list"
        >
            <template #addButton>&nbsp;</template>
            <template #extra-action>
                <th class="text-danger">Actions</th>
            </template>
            <template v-slot:extra-action-body="slotProps">
                <td>
                    <button
                        title="Add to cart"
                        class="btn btn-primary"
                        @click="showCartModal(slotProps)"
                    >
                        <i class="fa fa-plus"></i>
                    </button>
                </td>
            </template>
        </app-table>
        <app-modal
            id="cartModal"
            updateTitle="Remove from cart"
            :updateAction="removeFromCart"
            :createAction="addItemToCart"
            :modalTitle="form.name"
            :sumitButtonText="'AddToCart'"
            :form="form"
        >
            <input-field
                label="Quantity "
                v-model="form.quantity"
                id="quantity"
                :form="form"
                field="quantity"
                placeholder="0"
                min="1"
                type="number"
                name="quantity"
            />
            <div class="container">
                <span class="text-danger" v-show="isError">
                    {{ message }}
                </span>
            </div>
            <div class="container">
                <span class="text-success">
                    Max Quantity:{{ form.maxQuantity }}
                </span>
            </div>
        </app-modal>
    </app-body>
</template>

<script>
import { mapMutations } from "vuex";
export default {
    computed: {
        amount() {
            return (this.form.price * this.form.quantity).toFixed(2);
        },
    },
    data() {
        return {
            editmode: true,
            isError: false,
            message: "",
            tbHeaders: [
                { header: "Product", key: "name" },
                { header: "Last Added Stock", key: "last_added_quantity" },
                { header: "Available Quantity", key: "available_quantity" },
                { header: "Unit Price", key: "price" },
                { header: "Status", key: "status" },
            ],
            tbData: [],
            options: [],
            form: new Form({
                id: "",
                product_id: "",
                quantity: 1,
                name: "Add To Cart",
                maxQuantity: "",
                price: 0.0,
            }),
        };
    },
    methods: {
        ...mapMutations(["addToCart"]),
        loadStock() {
            axios
                .get("/api/inventory/products/stock")
                .then((res) => {
                    this.tbData = res.data;
                    // setTimeout(() => {
                    //      this.componentKey += 1;
                    // },200)
                })
                .catch((err) => console.log(err));
        },
        showCartModal({ row }) {
            this.isError = false;
            this.form.reset();
            this.form.name = row.name;
            this.form.product_id = row.product_id;
            this.form.maxQuantity = row.available_quantity;
            this.form.price = row.price;
            this.form.id = row.product_id;
            $("#cartModal").modal("show");
        },

        removeFromCart(id) {
            axios
                .delete("/api/inventory/category/" + id)
                .then((res) => Fire.$emit("afterCreated"));
        },
        addItemToCart() {
            if (
                this.form.quantity < this.form.maxQuantity &&
                this.form.quantity > 0
            ) {
                const product = {
                    id: this.form.id,
                    name: this.form.name,
                    quantity: this.form.quantity,
                    available_quantity: this.form.maxQuantity,
                    price: this.form.price,
                    amount: this.amount,
                };

                this.addToCart(product);
                $("#cartModal").modal("hide");
            }
            if (this.form.quantity < 1) {
                return this.setError("Quantity can not be lessthan 1");
            } else {
                return this.setError("Maximum quantity exceeded");
            }
        },

        setError(message) {
            this.isError = true;
            this.message = message;
        },
    },
    mounted() {
        this.loadStock();
    },
    created() {
        Fire.$on("afterCreated", () => {});
    },
    provide() {
        return {
            name: this.form.name,
        };
    },
};
</script>

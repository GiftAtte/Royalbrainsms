<template>
    <app-body pageTitle="Issued Items" pageSubTitle="Issued list">
        <app-table
            :headers="tbHeaders"
            :data="tbData.data"
            :deleteAction="deleteIssuedItem"
            :updateAction="updateIssuedItem"
            :createAction="issueItems"
            createButton="true"
            :form="form"
            action="false"
            search="true"
            title="Issued list"
            updateTitle="Update Purchases"
            modalSize="modal-lg"
            modalTitle="Issue Item"
        >
            <template #extra-action>
                <th>Date Issued</th>
                <th>Status</th>
            </template>
            <template v-slot:extra-action-body="{ row }">
                <td>{{ row.issued_date | myDate }}</td>
                <td v-if="row.type === 'Returnables'">
                    <button
                        @click="showReturnModal(row)"
                        class="btn btn-sm btn-danger"
                        v-if="row.isReturned === 0"
                    >
                        click To Return
                    </button>
                    <button v-else class="btn btn-sm btn-success">
                        ---Returned---
                    </button>
                </td>
                <td v-else>
                    <button class="btn btn-sm btn-warning">
                        Running Asset
                    </button>
                </td>
            </template>

            <template #modal-fields>
                <div class="col-md-12 row">
                    <div class="col-md-6">
                        <select-box
                            label="Category"
                            :form="form"
                            v-model="form.category_id"
                            placeholder="Select category"
                            field="category_id"
                            :options="getCategory()"
                            id="category_id"
                            name="category_id"
                            optionLabel="name"
                            optionValue="id"
                            :change="getItemsByCategory"
                        />
                    </div>
                    <div class="col-md-6">
                        <select-box
                            label="Item"
                            :form="form"
                            v-model="form.item_id"
                            placeholder="Select Item"
                            field="item_id"
                            :options="items"
                            id="item_id"
                            name="item_id"
                            optionLabel="name"
                            optionValue="id"
                            feild="item_id"
                            :change="getType"
                        />
                    </div>
                    <div class="col-md-6">
                        <input-field
                            label="Type"
                            :form="form"
                            v-model="form.type"
                            placeholder=" Type"
                            field="type"
                            id="type"
                            type="text"
                            name="type"
                            disabled="true"
                        />
                    </div>

                    <div class="col-md-6">
                        <select-box
                            label="Issued To"
                            :form="form"
                            v-model="form.issued_to"
                            placeholder="Select Who Recieves The Item"
                            field="issued_to"
                            :options="getEmployees()"
                            id="issued_to"
                            name="issued_to"
                            optionLabel="name"
                            optionValue="id"
                            feild="product_id"
                        />
                    </div>
                    <div class="col-md-6">
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
                        <span class="text-success">
                            Available Quantity: {{ availableQuantity }}</span
                        >
                        <span class="text-danger" v-if="isErrMaxQ">
                            Maximum quantity exceeded
                        </span>
                    </div>
                    <div class="col-md-6">
                        <input-field
                            label="Date Issued"
                            v-model="form.issued_date"
                            id="issued_date"
                            :form="form"
                            field="tissued_date"
                            placeholder="Date Issue"
                            type="date"
                            name="issued_date"
                        />
                    </div>
                    <div class="col-md-6 text-danger" v-if="form.isReturnabled">
                        <input-field
                            label="When To Return"
                            v-model="form.return_date"
                            id="return_date"
                            :form="form"
                            type="date"
                            name="Date Purchased"
                            field="issued_date"
                        />
                    </div>
                    <div class="col-md-6">
                        <input-field
                            label="Comment"
                            v-model="form.comment"
                            id="comment"
                            :form="form"
                            type="text"
                            name="Date Purchased"
                            field="comment"
                            placeholder="Optional"
                        />
                    </div>
                </div>
            </template>
        </app-table>

        <app-modal
            id="commentModal"
            modalTitle="Are you sure you want to return?"
            :createAction="returnItems"
            sumitButtonText="Return"
        >
            <p><b>Item :</b>&nbsp;&nbsp;&nbsp;{{ form.item }}</p>
            <p><b>Quantity :</b>&nbsp;&nbsp;&nbsp;{{ form.quantity }}</p>
            <p><b>Returned By :</b>&nbsp;&nbsp;&nbsp;{{ form.reciever }}</p>
            <hr />
            <input-field
                label="Comment"
                v-model="form.comment"
                id="comment"
                :form="form"
                type="text"
                name="Date Purchased"
                field="comment"
                placeholder="Optional"
            />
        </app-modal>
    </app-body>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
export default {
    data() {
        return {
            editmode: true,
            componentKey: 0,
            updateTitle: "Product Category",
            tbHeaders: [
                { header: "Item", key: "item" },
                { header: "Type", key: "type" },
                { header: "Unit", key: "quantity" },
                { header: "Issued To", key: "reciever" },
            ],
            tbData: [],
            options: [],
            supplier: [],
            items: [],
            availableQuantity: 0,
            isReturnable: false,
            isErrMaxQ: false,
            form: new Form({
                id: "",
                item_id: "",
                quantity: 1.0,
                category_id: "",
                type: "",
                issued_date: "",
                issued_to: "",
                comment: "",
                return_date: "",
                isReturned: false,
                item: "",
                reciever: " ",
            }),
        };
    },
    methods: {
        ...mapActions(["loadEmployees", "loadItems", "loadCategory"]),
        ...mapGetters(["getEmployees", "getItems", "getCategory"]),

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

        loadIssuedItems() {
            axios
                .get("/api/inventory/issueItems")
                .then((res) => {
                    this.tbData = res.data;
                })
                .catch((err) => console.log(err));
        },

        issueItems() {
            if (this.form.quantity > this.availableQuantity) {
                this.isErrMaxQ = true;
            } else {
                this.isErrMaxQ = false;

                this.form.post("/api/inventory/issueItems").then((res) => {
                    $("#appModal").modal("hide");
                    swal.fire(
                        "success!",
                        "Items issued successfully.",
                        "success"
                    );

                    Fire.$emit("afterCreated");
                });
            }
        },
        showReturnModal(item) {
            this.form.fill(item);
            console.log(item);
            $("#commentModal").modal("show");
        },
        returnItems() {
            this.form.put("/api/inventory/returnItems").then((res) => {
                $("#commentModal").modal("hide");
                swal.fire("success!", "Items issued successfully.", "success");

                Fire.$emit("afterCreated");
            });
        },

        deleteIssuedItem(id) {
            axios
                .delete("/api/inventory/issueItems/" + id)
                .then((res) => Fire.$emit("afterCreated"));
        },
        updateIssuedItem() {
            this.form.put("/api/inventory/issueItems").then((res) => {
                $("#appModal").modal("hide");
                swal.fire(
                    "success!",
                    "Products created successfully.",
                    "success"
                );

                Fire.$emit("afterCreated");
            });
        },

        getType() {
            // console.log(this.form.item_id);
            let item = this.getItems().find((item) => {
                return item.id === Number(this.form.item_id);
            });
            //console.log(item);
            this.form.type = item.type;
            if (item.type === "Returnables") {
                this.form.isReturnabled = true;
            } else {
                this.form.isReturnabled = false;
            }
            this.showQuantity(this.form.item_id);
        },
        getItemsByCategory() {
            let items = this.getItems().filter(
                (item) => item.category_id === Number(this.form.category_id)
            );
            console.log(this.getItems());
            this.items = items;
        },

        showQuantity(id) {
            axios.get(`/api/inventory/availableItems/${id}`).then((res) => {
                this.availableQuantity = res.data;
                $("#availableModal").modal("show");
            });
        },
    },

    created() {
        this.loadIssuedItems();
        this.loadEmployees();
        this.loadItems();
        this.loadCategory();
        Fire.$on("afterCreated", () => {
            this.loadIssuedItems();
        });
    },
};
</script>

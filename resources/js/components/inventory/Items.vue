<template>
    <app-body pageTitle="School Items" pageSubTitle="Items list">
        <app-table
            :headers="tbHeaders"
            :data="getItems()"
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
            <template #extra-action>
                <th class="text-danger">Action</th>
            </template>
            <template v-slot:extra-action-body="{ row }">
                <td>
                    <button class="btn btn-primary" @click="showQuantity(row)">
                        AQty
                    </button>
                </td>
            </template>

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

                <select-box
                    label="Item Type"
                    :form="form"
                    v-model="form.type"
                    placeholder="Select Item Type"
                    field="type"
                    :options="typeOptions"
                    id="type"
                    name="type"
                    optionLabel="name"
                    optionValue="id"
                />
            </template>
        </app-table>

        <app-modal
            id="availableModal"
            modalTitle="Available Quantity"
            :createAction="showQuantity"
            sumitButtonText="Return"
            hideCreateBtn="true"
        >
            <p><b>Item :</b>&nbsp;&nbsp;&nbsp;{{ form.name }}</p>
            <p>
                <b>Available Quantity :</b>&nbsp;&nbsp;&nbsp;{{
                    availableQuantity
                }}
            </p>
        </app-modal>
    </app-body>
</template>

<script>
import { mapGetters, mapActions, mapState } from "vuex";
export default {
    data() {
        return {
            editmode: true,
            componentKey: 0,
            availableQuantity: 0,
            options: [],
            items: [],
            tbHeaders: [
                { header: "Items Name", key: "name" },
                { header: "Category", key: "category" },
                { header: "Type", key: "type" },
            ],
            typeOptions: [
                { name: "Returnable Item", id: "Returnables" },
                { name: "None Returnable Item", id: "None-Returnables" },
            ],
            tbData: [],
            options: [],
            form: new Form({
                id: "",
                name: "",
                type: "",
                category_id: "",
                school_id: "",
            }),
        };
    },
    methods: {
        ...mapGetters(["getItems"]),
        ...mapActions(["loadItems"]),
        loadCategory() {
            axios
                .get("/api/inventory/category")
                .then((res) => {
                    this.options = res.data;
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

        showQuantity(item) {
            //console.log(item);
            this.form.fill(item);
            // $("#availableModal").modal("show");
            axios
                .get(`/api/inventory/availableItems/${item.id}`)
                .then((res) => {
                    this.availableQuantity = res.data;
                    this.form.fill(item);
                    $("#availableModal").modal("show");
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

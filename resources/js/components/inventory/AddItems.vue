<template>
    <app-body pageTitle="School Items" pageSubTitle="Purchase list">
        <app-table
            :headers="tbHeaders"
            :data="tbData.data"
            :deleteAction="deleteStockItems"
            :updateAction="updateStockItems"
            :createAction="createStockItems"
            createButton="true"
            :form="form"
            action="false"
            search="true"
            title="Items Added"
            updateTitle="Update Purchases"
        >
        <template #extra-action>
          <th>Added On</th>
        </template>
        <template  v-slot:extra-action-body="{row}">
            <td>{{ row.created_at | myDate }}</td>
        </template>

            <template #card-footer>
                <pagination
                    :data="tbData"
                    @pagination-change-page="paginateData"
                />
            </template>
            <template #modal-fields>
                <select-box
                    label="Item List"
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
            </template>


        </app-table>
    </app-body>
</template>

<script>
import {mapGetters,mapActions} from 'vuex'
import Templates from '../results/Templates.vue';
export default {
  components: { Templates },
    computed: {
        items() {
            return this.getItems();
        },
    },
    data() {
        return {
            editmode: true,
            componentKey: 0,
            updateTitle: "Product Category",
            url:'inventory/stockItems',
            tbHeaders: [
                { header: "Item", key: "item" },
                { header: "Category", key: "category" },
                { header: "Quantity", key: "quantity" },
                { header: "Added By", key: "added_by" },
                      ],
            options: [],
            tbData: [],
            form: new Form({
                id: "",
                 item_id: "",
                quantity: 1.0,
                school_id: "",
            employee_id:''
            }),
        };
    },
    methods: {
        ...mapGetters(['getItems','getData']),
        ...mapActions(['loadItems']),
        paginateData(page=1) {
            axios.get('/api/inventory/stockItems?page=' + page)
           .then(res=>this.tbData=res.data)
          },


        createStockItems() {
            this.form.post('/api/inventory/stockItems').then((res) => {
                $("#appModal").modal("hide");
                swal.fire(
                    "success!",
                    " A Product created successfully.",
                    "success"
                );

                Fire.$emit("afterCreated");
            });
        },

        deleteStockItems(id) {
            axios
                .delete('/api/inventory/stockItems/' + id)
                .then((res) => Fire.$emit("afterCreated"));
        },
        updateStockItems() {
            this.form.put('/api/inventory/stockItems').then((res) => {
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
        this.paginateData();
    },
    created() {
        this.loadItems();
       // this.loadPaginatedData(this.url);
        Fire.$on("afterCreated", () => {
           this.paginateData();
        });
    },
};
</script>
</script>

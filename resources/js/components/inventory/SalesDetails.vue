<template>
    <app-body pageTitle="Sales Details" pageSubTitle="Sales Details">
        <app-table
            :headers="tbHeaders"
            :data="tbData"
            search="true"
            :title="`Sales Details .      Buyer: ${tbData[0].student}`"
        >
            <template #addButton>
                <button
                    @click="printReciept"
                    class="btn btn-primary float-right"
                >
                    <i class="fas fa-print"></i>
                </button>
            </template>
            <template #extra-row>
                <tr>
                    <th colspan="3">TOTAL</th>
                    <th colspan="3">NGN{{ tbData[0].total }}</th>
                </tr>
                <tr>
                    <th class="text-primary">
                        TOTAL SALES:&nbsp;&nbsp; NGN{{ tbData[0].total }}
                    </th>
                    <th class="text-success">
                        TOTAL RECIEVED:&nbsp;&nbsp; NGN{{
                            tbData[0].paid_amount
                        }}
                    </th>
                    <th colspan="2" class="text-danger">
                        TOTAL BALANCE:&nbsp;&nbsp; NGN{{
                            tbData[0].total - tbData[0].paid_amount
                        }}
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
            tbHeaders: [
                { header: "Items", key: "name" },
                { header: "Quantity", key: "quantity" },
                { header: "Unit Price", key: "unit_price" },
                { header: "Amount", key: "amount" },
            ],
            tbData: [],
        };
    },
    methods: {
        ...mapGetters(["getProducts"]),
        ...mapMutations(["removeFromCart", "removeAllFromCart"]),
        navigate(link) {
            this.$router.push(link);
        },
        loadSales() {
            axios
                .get(`/api/inventory/sales/${this.$route.params.salesId}`)
                .then((res) => {
                    this.tbData = res.data;
                });
        },
    },

    created() {
        this.loadSales();
    },
};
</script>

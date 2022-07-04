<template>
    <app-body pageTitle="Petty Cash" pageSubTitle="Petty Cash list">
        <app-table
            :headers="tbHeaders"
            :data="tbData.data"
            :deleteAction="deletePettyCash"
            :updateAction="updatePettyCash"
            :createAction="createPettyCash"
            createButton="true"
            :form="form"
            action="true"
            search="true"
            title="Pettycash list"
            updateTitle="Update cash"
        >
            <template #search>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Search By Group</label>
                    </div>
                    <select
                        :form="form"
                        name="petty_id"
                        v-model="form.petty_id"
                        placeholder="Select Petty Group"
                        @change="loadpettyCash"
                        class="form-control"
                        id="petty_id"
                    >
                        <option selected value=" ">Select group</option>
                        <option
                            v-for="(option, index) in options"
                            :key="index"
                            :value="option.id"
                        >
                            {{ option.title }}
                        </option>
                    </select>
                </div>
            </template>
            <template #extra-row>
                <tr>
                    <th class="text-primary">
                        TOTAL CREDITS:&nbsp;&nbsp; NGN{{ totalCredit }}
                    </th>
                    <th class="text-danger">
                        TOTAL EXPENSES:&nbsp;&nbsp; NGN{{ totalExpenses }}
                    </th>
                    <th class="text-success">
                        TOTAL BALANCE:&nbsp;&nbsp; NGN{{ totalBalance }}
                    </th>
                </tr>
            </template>
            <template #modal-fields>
                <select-box
                    label="Petty Group"
                    :form="form"
                    v-model="form.pettygroup_id"
                    placeholder="Select Petty Group"
                    field="type"
                    :options="options"
                    id="term_id"
                    name="pettygroup_id"
                    optionLabel="title"
                    optionValue="id"
                />
                <input-field
                    label="Petty Cash"
                    v-model="form.amount"
                    id="amount"
                    :form="form"
                    field="amount"
                    type="number"
                    placeholder="Amount"
                />
                <input-field
                    label="Issued Date"
                    v-model="form.issued_date"
                    id="issued_date"
                    :form="form"
                    field="issued_date"
                    type="date"
                    placeholder="Date Issued"
                />
                <input-field
                    label="Balance Carried Forward"
                    id="balance_carried_forward"
                    :form="form"
                    field="balance_carried_forward"
                    type="number"
                    :value="totalBalance"
                    placeholder="Amount"
                    disabled="true"
                />
            </template>
            <template #card-footer>
                <pagination
                    :data="tbData"
                    @pagination-change-page="getPettyPage"
                />
            </template>
        </app-table>
    </app-body>
</template>

<script>
import Options from "../cbt/Options.vue";
export default {
    components: { Options },
    data() {
        return {
            editmode: true,
            componentKey: 0,
            updateTitle: "Update Supplire's Info",
            tbHeaders: [
                { header: "Group", key: "group" },
                { header: "Amount Credited", key: "amount" },
                { header: "Date Credited", key: "issued_date" },
            ],
            tbData: {},
            options: [],
            totalExpenses: 0.0,
            totalCredit: 0.0,
            totalBalance: 0.0,
            form: new Form({
                id: "",
                school_id: "",
                amount: "",
                issued_date: "",
                petty_id: "",
                balance_carried_forward: "",
            }),
        };
    },
    methods: {
        getPettyPage(page = 1) {
            axios
                .get("/api/inventory/pettycash?page=" + page)
                .then((response) => {
                    this.tbData = response.data;
                });
        },
        loadpettyCash() {
            axios
                .get(
                    `/api/inventory/pettycash/${
                        this.form.petty_id ? this.form.petty_id : ""
                    }`
                )
                .then((res) => {
                    this.tbData = res.data;
                })
                .catch((err) => console.log(err));
            this.getPettyBalance();
        },
        createPettyCash() {
            this.form.post("/api/inventory/pettycash").then((res) => {
                $("#appModal").modal("hide");
                swal.fire(
                    "success!",
                    "Pettycash created successfully.",
                    "success"
                );

                Fire.$emit("afterCreated");
            });
        },
        deletePettyCash(id) {
            axios
                .delete("/api/inventory/pettycash/" + id)
                .then((res) => Fire.$emit("afterCreated"));
        },

        getPettyBalance() {
            axios
                .get(
                    `/api/inventory/pettyCashBalance/${
                        this.form.petty_id ? this.form.petty_id : ""
                    }`
                )
                .then((res) => {
                    this.totalExpenses = res.data.totalExpenses;
                    this.totalCredit = res.data.totalCredit;
                    this.totalBalance = res.data.totalBalance;
                });
        },
        updatePettyCash() {
            this.form.put("/api/inventory/pettycash").then((res) => {
                $("#appModal").modal("hide");
                swal.fire(
                    "success!",
                    "Pettycash created successfully.",
                    "success"
                );

                Fire.$emit("afterCreated");
            });
        },
    },
    mounted() {
        this.loadpettyCash();
    },
    created() {
        axios
            .get("/api/inventory/pettycash/groups/all")
            .then((res) => (this.options = res.data));

        Fire.$on("afterCreated", () => {
            this.loadpettyCash();
        });
    },
};
</script>

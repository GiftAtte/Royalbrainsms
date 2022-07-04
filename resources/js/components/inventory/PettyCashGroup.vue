<template>
    <app-body pageTitle="Pettycash Group" pageSubTitle="Petty Group list">
        <app-table
            :headers="tbHeaders"
            :data="tbData.data"
            :deleteAction="deletePettyGroup"
            :updateAction="updatePettyGroup"
            :createAction="createPettyGroup"
            createButton="true"
            :form="form"
            action="true"
            search="true"
            title="Petty cash list"
            updateTitle="Update cash"
        >
            <template #modal-fields>
                <input-field
                    label="Title"
                    v-model="form.title"
                    id="title"
                    :form="form"
                    field="title"
                    type="text"
                    placeholder="title"
                />
                <select-box
                    label="Session"
                    :form="form"
                    v-model="form.session_id"
                    placeholder="Select Session"
                    field="type"
                    :options="sessions"
                    id="session_id"
                    name="session_id"
                    optionLabel="name"
                    optionValue="id"
                />
                <select-box
                    label="Term"
                    :form="form"
                    v-model="form.term_id"
                    placeholder="Select Term"
                    field="type"
                    :options="terms"
                    id="term_id"
                    name="term_id"
                    optionLabel="name"
                    optionValue="id"
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
import Templates from "../results/Templates.vue";
export default {
    components: { Templates },
    data() {
        return {
            editmode: true,
            componentKey: 0,
            sessions: [],
            terms: [],
            updateTitle: "Update Supplire's Info",
            tbHeaders: [
                { header: "Title", key: "title" },
                { header: "Session", key: "term" },
                { header: "Term", key: "session" },
            ],
            tbData: {},

            form: new Form({
                id: "",
                session_id: "",
                term_id: "",
                title: "",
                school_id: "",
            }),
        };
    },
    methods: {
        getPettyPage(page = 1) {
            axios.get("/api/inventory/groups?page=" + page).then((response) => {
                this.tbData = response.data;
            });
        },

        loadPettyGroup() {
            axios
                .get("/api/inventory/groups")
                .then((res) => {
                    this.tbData = res.data;
                    // setTimeout(() => {
                    //      this.componentKey += 1;
                    // },200)
                })
                .catch((err) => console.log(err));
        },
        createPettyGroup() {
            this.form.post("/api/inventory/pettycash/groups").then((res) => {
                $("#appModal").modal("hide");
                swal.fire(
                    "success!",
                    "Petty group created successfully.",
                    "success"
                );

                Fire.$emit("afterCreated");
            });
        },
        deletePettyGroup(id) {
            axios
                .delete("/api/inventory/pettycash/groups/" + id)
                .then((res) => Fire.$emit("afterCreated"));
        },
        updatePettyGroup() {
            this.form.put("/api/inventory/pettycash/groups").then((res) => {
                $("#appModal").modal("hide");
                swal.fire(
                    "success!",
                    "Petty group created successfully.",
                    "success"
                );

                Fire.$emit("afterCreated");
            });
        },
    },
    mounted() {
        this.loadPettyGroup();
    },
    created() {
        axios.get("/api/term").then((res) => {
            this.terms = res.data;
        });

        axios.get("/api/load_session").then((res) => {
            this.sessions = res.data;
        });

        Fire.$on("afterCreated", () => {
            this.loadPettyGroup();
        });
    },
};
</script>

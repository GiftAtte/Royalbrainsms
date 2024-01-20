<template>
    <app-body pageTitle="Admission" pageSubTitle="Admission Levels">
        <app-table
            :headers="tbHeaders"
            :data="tbData"
            :form="form"
            action="true"
            search="true"
            title="Candidate List"
           
        >
            <template #addButton></template>

            <template #extra-action>
                <th>Created On</th>
            </template>
            <template v-slot:extra-action-body="{ row }">
                <td>{{ row.created_at | myDate }}</td>
            </template>

           
        </app-table>
    </app-body>
</template>

<script>
export default {
    data() {
        return {
            editmode: true,
            componentKey: 0,
            tbHeaders: [{ 
                header: "Surname", key: "surname",
                header: "First Name", key: "first_name" ,
                header: "Gender", key: "gender" ,
                header: "Class", key: "level" 
        }],
            tbData: [],

            form: new Form({
                id: "",
                name: "",
                school_id: "",
            }),
        };
    },
    methods: {
        loadCandidates() {
            axios
                .get("/api/candidates")
                .then((res) => {
                    this.tbData = res.data; 
                })
                .catch((err) => console.log(err));
        },
       
        handleChange() {},
    },
    mounted() {
        this.loadLevel();
    },
    created() {
       this.loadCandidates();
    },
};
</script>

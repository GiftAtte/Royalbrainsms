<template>
    <div class="col-md-12">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Dashboard</h1>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="#">Home</a>
                            </li>
                            <li class="breadcrumb-item active">
                                Assignment/Notes
                            </li>
                        </ol>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>

        <div class="container">
            <div class="card">
                <div class="card-header">
                    <h3>{{ assignment.comment }}</h3>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Loading from "vue-loading-overlay";

export default {
    components: { Loading },

    data() {
        return {
            assignment: "",
            isLoading: false,
        };
    },
    mounted() {
        this.$Progress.start();
        axios.get("/api/get_pdf/" + this.$route.params.id).then((res) => {
            this.assignment = res.data;
            this.$Progress.finish();
        });
    },
    methods: {
        downloadFile(id) {
            axios
                .get("/api/assignment_view/" + id, {
                    responseType: "arraybuffer",
                })
                .then((response) => {
                    // let blob = new Blob([response.data], {
                    //     type: "application/pdf",
                    // });
                    // let link = document.createElement("a");
                    // link.href = window.URL.createObjectURL(blob);
                    // link.download = "assignment.pdf";
                    // link.click()
                });
        },
    },

    created() {
        Fire.$on("searching", () => {
            let query = this.$parent.search;
            axios
                .get("/api/findAssignment?q=" + query)
                .then((data) => {
                    this.assignments = data.data;
                })
                .catch(() => {});
        });

        Fire.$on("AfterCreate", () => {});

        //    setInterval(() => this.loadUsers(), 3000);
    },
};
</script>

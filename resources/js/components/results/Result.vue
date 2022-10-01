<template>
    <div>
        <div class="content-header">
            <div class="container-fluid navy">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h2 class="m-0 text-dark">Students Report Sheet</h2>
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="#">Home</a>
                            </li>
                            <li class="breadcrumb-item active">Report Sheet</li>
                        </ol>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <div v-if="isLoading"><h1>Loading.......</h1></div>
        <section slot="pdf-content">
            <annual v-if="isAnnual"></annual>
            <midterm
                v-show="!isAnnual"
                v-if="report.type === 'mid_term'"
            ></midterm>
            <terminal
                v-show="!isAnnual"
                v-if="report.type === 'terminal'"
            ></terminal>
            <mock v-show="!isAnnual" v-if="report.type === 'mock'"></mock>
            <primary-midterm
                v-show="!isAnnual"
                v-if="report.type === 'primary-midterm'"
            />
            <primary-terminal
                v-show="!isAnnual"
                v-if="report.type === 'primary-terminal'"
            />
            <creche-comment v-if="report.type === 'creche'" />
            <comments-with-scores v-if="report.type === 'diamond'" />
            <general-results
                v-if="
                    report.type === 'default-result' ||
                    report.type === 'navy-template' ||
                    report.type === 'blue_ridge' ||
                    report.type === 'default-midterm'
                "
            />
        </section>
    </div>
</template>

<script>
import CrecheComment from "./CrecheComment.vue";
import Blueridge from "./Blueridge.vue";
// import jspdf from "jspdf";
// import html2canvas from "html2canvas";

export default {
    components: { CrecheComment, Blueridge },
    // computed: mapState(['school']),

    data() {
        return {
            report: "",
            isAnnual: false,
            isLoading: true,
        };
    },
    mounted() {
        this.report = "";
        if (this.$route.params.annual === "annual") {
            this.isAnnual = true;
        } else {
            axios
                .get("/api/checkreport/" + this.$route.params.report_id)
                .then((response) => {
                    this.report = response.data;

                    // console.log(this.report_id)
                });
        }
    },

    created() {
        setTimeout(() => {
            this.isLoading = false;
        }, 15000);
    },
};
</script>

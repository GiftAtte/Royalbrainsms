<template>
    <app-body
        pageTitle="Download Assignment"
        subPageTitle="Download Assignment"
    >
        <div class="card card-outline card-navy">
            <div class="card-header">
                <button
                    class="btn btn-rounded btn-primary float-right"
                    @click="downloadPDF"
                >
                    <i class="fa fa-download"></i>
                </button>
            </div>
            <div class="card-body">
                <div id="section-to-print" ref="generatePDF">
                    <div class="container">
                        <h3 class="text-center">HOME WORK</h3>
                        <hr />
                        <h4 class="text-center text-bold">
                            {{ assignment.subject.name }}
                        </h4>
                        <h4 class="py-2">
                            <span class="text-bold"> Instruction: </span>
                            {{ assignment.comment }}
                        </h4>
                        <h4>{{ assignment.title }}</h4>
                        <div v-html="assignment.note" class="mx-auto"></div>
                    </div>
                </div>
                <!-- <vue-html2pdf
                    :show-layout="false"
                    :float-layout="false"
                    :enable-download="true"
                    :preview-modal="false"
                    :paginate-elements-by-height="1400"
                    :filename="`${assignment.subject.name} home work`"
                    :pdf-quality="2"
                    :manual-pagination="false"
                    pdf-format="a4"
                    pdf-orientation="portrait"
                    ref="html2Pdf"
                >
                    <section slot="pdf-content" id="section-to-print">
                        <div class="container">
                            <h3 class="text-center">HOME WORK</h3>
                            <hr />
                            <h4 class="text-center text-bold">
                                {{ assignment.subject.name }}
                            </h4>
                            <h4 class="py-2">
                                <span class="text-bold"> Instruction: </span>
                                {{ assignment.comment }}
                            </h4>
                            <h4>{{ assignment.title }}</h4>
                            <div v-html="assignment.note" class="mx-auto"></div>
                        </div>
                    </section>
                </vue-html2pdf> -->
            </div>
        </div>
        <!-- <ejs-pdfviewer
            id="pdfViewer"
            :serviceUrl="serviceUrl"
            :documentPath="documentPath"
        >
        </ejs-pdfviewer> -->
    </app-body>
</template>
<script>
import VueHtml2pdf from "vue-html2pdf";
export default {
    components: {
        VueHtml2pdf,
    },
    data() {
        return {
            assignment: {},
            htmlToPdfOptions: {
                margin: 0,

                filename: `hehehe.pdf`,

                image: {
                    type: "jpeg",
                    quality: 0.98,
                },

                enableLinks: false,

                html2canvas: {
                    scale: 1,
                    useCORS: true,
                },

                jsPDF: {
                    unit: "in",
                    format: "a4",
                    orientation: "portrait",
                },
            },
        };
    },
    mounted() {
        axios.get("/api/assignment/" + this.$route.params.id).then((res) => {
            this.assignment = res.data;
        });
    },
    methods: {
        downloadPDF() {
            // this.$refs.html2Pdf.generatePdf();
            window.print();
        },
        downloadFile() {
            this.$http
                .get("api/assignment", { responseType: "arraybuffer" })
                .then((response) => {
                    let blob = new Blob([response.data], {
                        type: "application/pdf",
                    });
                    let link = document.createElement("a");
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "test.pdf";
                    link.click();
                });
        },
    },
};
</script>

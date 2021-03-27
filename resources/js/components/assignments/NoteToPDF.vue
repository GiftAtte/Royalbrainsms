<template>
   <div class="card-body">
       <div class="col-md-6">
             <vue-html2pdf
        :show-layout="true"
        :enable-download="true"
        :preview-modal="false"
        :paginate-elements-by-height="1400"
        :filename="assignment.comment"
        :pdf-quality="4"
        pdf-format="a4"
        pdf-orientation="portrait"
        pdf-content-width="80%"


        ref="html2Pdf"
    >
        <section slot="pdf-content" >
          <div  class="card ">
              <div class="card-body m-2" v-html="assignment.note">

              </div>
          </div>
        </section>
    </vue-html2pdf>
       </div>

   </div>
</template>

<script>

  import VueHtml2pdf from 'vue-html2pdf'
    export default {

components: {
        VueHtml2pdf,

    },
        data() {
            return {

                   assignment:'',
                  isLoading:false,
                  message:"dshsofhasfd",

            }
        },
        mounted(){
            this.$Progress.start()
              axios.get('/api/get_pdf/'+this.$route.params.id)
                .then(res=>{
                    this.assignment=res.data;
                    this.message=assignment.note
                    this.$Progress.finish()
                    })
this.generateReport();

        },
        methods: {
  generateReport () {
            this.$refs.html2Pdf.generatePdf()
        },

downloadFile(id) {
  axios.get('/api/assignment_view/'+id,{responseType: 'arraybuffer'})
    .then(response => {
      let blob = new Blob([response.data], { type: 'application/pdf' })
      let link = document.createElement('a')
      link.href = window.URL.createObjectURL(blob)
      link.download = 'assignment.pdf'
      link.click()
    })
},

        },







        created() {
        const viewer = new toastui.Editor({
        el: document.querySelector('#viewer'),
        initialValue: content
      });


            Fire.$on('searching',() => {
                let query = this.$parent.search;
                axios.get('/api/findAssignment?q=' + query)
                .then((data) => {
                    this.assignments = data.data
                })
                .catch(() => {

                })
            })

           Fire.$on('AfterCreate',() => {

           });

        //    setInterval(() => this.loadUsers(), 3000);
        }

    }
</script>

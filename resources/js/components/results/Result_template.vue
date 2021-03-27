<template>
     <div class="container">
<div class="content-header">

      <div class="container-fluid navy" > 
        <div class="row mb-2">
          <div class="col-sm-6">
            <h2 class="m-0 text-dark">Students Report Sheet</h2>
          </div><!-- /.col -->
          <div class="col-sm-6">
          
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Repord Sheet</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>





    </div>
</template>

<script >
    import jspdf from "jspdf";
    import html2canvas from "html2canvas"

    export default {
        data(){
            return {
               summary:'',
               scores:{},
               user:{},
               comment:'',
               Total:{},
               report:{},
               arm:{},
            }
        },
        mounted(){
            const student_id=this.$route.params.student_id
            const report_id=this.$route.params.report_id
            if(this.$route.params.student_id){

            axios.get(`/api/result/${report_id}/${student_id}`)
            .then((result) => {
                if(result.data.Not_found){
               
                // this.$router.push('/reports');
                this.$router.push('/not-found')
              }
              console.log(result.data);
                this.scores=result.data.scores
                 this.summary=result.data.summary
                 this.user=result.data.user
                  this.Total=result.data.pastTotal
                  this.comment=result.data.comment
                this.report=result.data.report
                 this.arm=result.data.arm
                  console.log(this.Total);
                 
            }).catch((err) => {
                
            });
            }else{
            axios.get(`/api/result/${report_id}`)
            .then((result) => {
              if(result.data.Not_found){
               const message="sorry! No results found";
                // this.$router.push('/reports');
                this.$router.push('/not-found')
              }
                this.scores=result.data.scores
                 this.summary=result.data.summary
                 this.user=result.data.user
                 this.Total=result.data.pastTotal
                 this.report=result.data.report
                 this.arm=result.data.arm
       
                 
               console.log(this.report);
                 
            }).catch((err) => {
                
            });
        }},
        methods:{
                printResult(){
                  window.print();
                },
                generatePDF(){
         

            //       const doc=new jspdf();
            //       const result=this.$refs.generatePDF.innerHTML
            //     doc.fromHTML(result, 15, 15 ,{
            //              elementHandlers: function() {
            //                             return true}}
            //  )
            //  doc.save("result.pdf");
             },

       generatePDF() {
   const doc = new jspdf({  format: 'a4' });
   /** WITH CSS */
   var canvasElement = document.createElement('canvas');
    html2canvas(this.$refs.generatePDF, { canvas: canvasElement})
    .then((canvas) =>{
    const img = canvas.toDataURL("image/jpeg", 0.8);
    doc.addImage(img,'JPEG',20,20);
    doc.fromHTML(this.$refs.generatePDF.innerHTML)
    doc.save("sample.pdf");
   });
               }
    }}
    
    
</script>




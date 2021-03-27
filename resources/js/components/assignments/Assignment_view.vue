<template>
    <div>
     <ejs-pdfviewer id="pdfViewer" 
     :serviceUrl="serviceUrl" 
     :documentPath="documentPath"> 
     </ejs-pdfviewer>
</div>
</template>
<script>


 

export default{
   
  data(){
    return {
      serviceUrl:"https://ej2services.syncfusion.com/production/web-services/api/pdfviewer",
        documentPath:''
    }
  },
  mounted(){
      axios.get('/api/assignment_view/'+this.$route.params.id)
      .then(res=>{
          console.log(res.data)
          this.documentPath='storage/'+res.data;
      })
   
    } ,
    methods : {
      downloadFile() {
  this.$http.get('api/assignment', {responseType: 'arraybuffer'})
    .then(response => {
      let blob = new Blob([response.data], { type: 'application/pdf' })
      let link = document.createElement('a')
      link.href = window.URL.createObjectURL(blob)
      link.download = 'test.pdf'
      link.click()
    })
}
    } }

</script>

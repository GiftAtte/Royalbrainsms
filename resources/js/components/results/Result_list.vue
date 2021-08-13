<template>
   <div >
<div class="content-header">

      <div class="container-fluid navy" >
        <div class="row mb-2">
          <div class="col-sm-6">
            <h2 class="m-0 text-dark">Students Class List</h2>
          </div><!-- /.col -->
          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Class List</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

<div class="content col-12">
<div class="card card-navy card-outline">
<div class="card-header">
<div class="ribbon-wrapper">
    <div class="ribbon bg-primary">
      Result
      </div>
    </div>
<h5 class="header text-danger text-uppercase">Result Class List for {{report.title}}</h5>
</div>
<div class="card-body table-responsive col-md-12">
<table class="table table-hover  display" id="data_tb" width="100%">
<thead>
<tr>
<th>S/N</th><th>Student Names</th> <th>Arms</th><th>Action</th>
</tr>
</thead>
<tbody>
<tr v-for="(student,index) in students.data" :key="student.id">
<td>{{index+1}}</td>
<td>{{student.surname}}&nbsp;{{student.first_name}}&nbsp;{{student.middle_name}}</td>
<td>{{student.arm?student.arm.name:''}}</td>
 <td class="row">
<router-link v-if="$gate.isTutorOrAdmin()"     :to="`/result/${report}/${student.id}`"  tag="a" exact class="pr-2">view report card</router-link>
<router-link v-show="isThirdTerm" v-if="$gate.isTutorOrAdmin()"     :to="`/result/${report}/${student.id}/annual`"  tag="a" exact class="pr-2">view Annual Report</router-link>

<router-link :to="`/transcript/${student.id}`"  tag="a" exact>view transcript</router-link>

                    </td>
</tr>

</tbody>
</table>
</div>
<div class="card-footer">
                  <pagination :data="students" @pagination-change-page="getResults"></pagination>
              </div>
</div>
</div>


    </div>
</template>

<script>


import VueHtml2pdf from 'vue-html2pdf'
    export default {

components: {
        VueHtml2pdf,

    },

        data(){
            return{
                students:{},
                report:this.$route.params.id,
                isThirdTerm:false

            }
        },

        mounted(){

            // $('#data_tb').DataTable();
        axios.get(`/api/results/${this.$route.params.id}`)
            .then(result => {
                this.students=result.data;

              console.log(result.data);
            }).catch((err) => {

            });


        },
            methods:{
 getResults(page = 1) {
                        axios.get(`/api/results/${this.$route.params.id}?page=${page}`)
                            .then(response => {
                                this.students = response.data;
                            });
                },


                printResult(){
                  window.print();
                }},
                created(){
                    axios.get('/api/checkreport/'+this.report)
                    .then(res=>{
                      if(res.data.term_id===3){
                          this.isThirdTerm=true
                      }
                    })

 Fire.$on('searching',() => {
                let query = this.$parent.search;
                axios.get(`/api/findStudent/${this.report}?q=` + query)
                .then((data) => {
                    this.students = data.data
                })
                .catch(() => {

                })
            })



 //setTimeout(function(){$('#data_tb').DataTable();}, 0);
             }

    }




</script>


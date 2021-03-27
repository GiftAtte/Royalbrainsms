<template>
 <div >
<div class="content-header">

      <div class="container-fluid navy" >
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Report</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Report Group</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>




        <div class="content" v-if="$gate.isAdminOrTutorOrStudent()">
          <div class="col-12">
            <div class="card card-navy card-outline">
                <div class="card-header">

 <export-excel
       class="btn btn-primary"

       :data="masterSheet"
       >
       Download Excel
       <i class="fa fa-download"></i>
     </export-excel>
    <button class="btn btn-primary float-right pl-2" @click="printResults"><i class="fa fa-print"></i>Print Result</button>

    </div>
<div class="card-body pt-0" id="section-to-print"  ref="generatePDF" :style="`background-image: linear-gradient(to bottom, rgba(255,255,255,0.98) 100%,rgba(255,255,255,0.98) 100%), url(/img/schools/${school.logo}) ;background-repeat: no-repeat; background-position: center;background-size: 80%;`">
<div class="card-body row col-md-12 col-sm-12 pt-1 mt-0">
<div class="col-md-2 col-sm-2"><img :src="`/img/schools/${school.logo}`" class="img-thumbnail  result-logo" alt="logo" width="100" height="100"></div>
<div class="col-md-8 col-sm-12">
<h3 class="text-primary text-uppercase">{{school.name}},
</h3>
<h5>{{school.contact_address}},&nbsp; {{school.state}}</h5>
<h5>P:&nbsp; {{school.phone}}. &nbsp; E: {{school.email}}</h5>
<h5 class="text-red">URL:&nbsp; {{school.website}}.</h5>

</div>
<div class="col-md-2 col-sm-2">
<h5 class="pt-2 text-danger">Results Master Sheet</h5>
</div>
</div>
<div class="container text-center text-primary text-uppercase"><h4>{{reports.terms.name}} RESULTS MASTER SHEET FOR {{reports.levels.level_name}}, {{reports.sessions.name}} Academic Session </h4></div>
        <div class="container">
            <table class="table table-sm table-bordered m-2 text-center table-striped mt-2 table-info myTable ">
                <tbody>
                    <tr>
                        <th rowspan="3" >Short<br> Keys</th>
                        <th>TL</th>
                        <th>AS</th>
                        <th>AP</th>
                        <th>CP</th>
                    </tr>
                    <tr>
                        <td>Total Scores</td>
                        <td>Average Scores</td>
                        <td>Arm Position</td>
                        <td>Class Position</td>
                    </tr>
                </tbody>
            </table>
            </div>      <!-- /.card-header -->
              <div class="card-body table-responsive">
                <table class="  table table-hover myTable table-sm table-bordered" id="data_tb">
                  <tbody>
                    <tr class=" text-capitalize">
                        <th>Names/Subjects</th>
                        <th  v-for="subject in subjects" :key="subject.id" class=" text-capitalize">
                          {{subject.subjects.name}}
                        </th>



                        <th><tr><th>T L</th><th>A S</th><th>A P</th><th>C P</th></tr></th>

                  </tr>
                  <tr v-for="student in students" :key="student.id">
                      <td>{{student.surname}}, {{student.first_name}} {{student.middle_name}}</td>
                      <td v-for="subject in subjects" :key="subject.id"
                       >
                       <span v-for="report in marks" :key="report.id"
                       v-if="report.student_id===student.id && report.subject_id===subject.subjects.id">
                       {{report.total}}</span>
                       </td>
                       <td v-for="result in results" :key="result.id" v-if="result.student_id===student.id">
                          <tr><td>{{result.total_scores}}</td>
                          <td>{{result.average_scores}}</td>
                           <td>{{result.arm_position}}</td>
                           <td>{{result.class_position}}</td></tr>

                       </td>

                  </tr>


                </tbody></table>
              </div>
              <!-- /.card-body -->

            </div>
            <!-- /.card -->
          </div>
        </div>

        <div v-if="!$gate.isAdminOrTutorOrStudent()">
            <not-found></not-found>
        </div>
<!-- Arms Modal -->



        </div>


 </div>
</template>

<script>
          import jspdf from "jspdf";
    import html2canvas from "html2canvas";
    import {mapState} from "vuex";
    export default {
      computed: mapState(['school']),
        data() {
            return {
               subjects:'',
               reports:'',
               students:'',
               marks:'',
               results:'',
               masterSheet:[],
               json_fields: {
               
                  'StudentID':'masterSheet.id',
                  'name': 'masterSheet.name',
                   field: 'subjects',
           
        },
            }},

    mounted(){

            // $('#data_tb').DataTable();
        axios.get(`/api/export_master/${this.$route.params.report_id}`)
            .then(result => {
               this.masterSheet=result.data.mastersheet;

              console.log(result.data);
            }).catch((err) => {

            })
    },
        methods: {

printResults(){
                  window.print();
                },
   masterCard(){
       axios.get(`/api/master/${this.$route.params.report_id}`)
       .then(res=>{
           this.students=res.data.students;
           this.subjects=res.data.subjects;
           this.reports=res.data.report;
           this.marks=res.data.marks;
           this.results=res.data.results;
       })
   }

        },
        created() {

          this.masterCard();

            Fire.$on('searching',() => {
                let query = this.$parent.search;
                axios.get('api/findStudent?q=' + query)
                .then((data) => {
                    this.levels = data.data
                })
                .catch(() => {

                })
            })


        //    setInterval(() => this.loadUsers(), 3000);

        }

    }
</script>

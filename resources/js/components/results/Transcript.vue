<template>
<div>
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


<div class="card card-navy card-outline" v-if="$gate.isAdminOrTutorOrStudent()">
 <div class="card-header">
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
<h5 class="pt-2 text-danger">Student Transcript</h5>
</div>
</div>
<div>

<div class="col col-12 py-2 ">
<div class=" col col-md-2 float-right" >
<center>
    <image-loader
        :src="`/img/profile/${student.photo}`"

        placeholder="/img/profile.png"
         width="100px" height="100px"
         class="img-thumbnail img-result "
         />




</center>
</div><div class="row col-md-10">
<div class="col-md-6 py-2">
<h5><b>Name:</b>&nbsp; {{student.students.surname}}&nbsp; {{student.students.first_name}} &nbsp; {{student.students.middle_name?student.students.middle_name:''}}</h5>
<h5  v-if="school_id===18"><b >Gender:&nbsp;</b> {{student.students.gender}} </h5>
<h5  v-if="school_id===18"><b>Dob:</b>&nbsp; {{student.students.dob}}</h5>
<h5 ><b>Portal ID:</b>&nbsp; {{student.portal_id}}</h5>
</div>
<div class="col-md-6">
</div>

</div>

</div>
<div v-for="(level, index) in levels " :key="level.level_id">
<h4 class="text-center text-primary mt-3">{{transcripts[index].report.levels.level_name}}: &nbsp; {{transcripts[index].report.sessions.name}} Academic Session</h4>
<div class="  table-responsive py-3"  >
    <table class="table table-bordered mt-3 table-sm" >
<tbody>
        <tr>
            <th>S/N</th>
            <th>Subject</th>
            <th>First Term</th>
            <th>Second Term</th>
            <th>Third Term</th>
            <th>Grand Total</th>
            <th>Cummulative Average</th>
            <th>Grade</th>
        </tr>
        <tr v-for="(transcript,index2) in transcripts[index].scores " :key="index2">
            <td>{{index2+1}}</td>
             <td>{{transcript.subject}}</td>
              <td v-if="transcript.first_term>0">{{transcript.first_term}}<span style="display:none" v-if="transcript.first_term>0">{{total1=total1+transcript.first_term}}<span style="display:none">{{count1=count1+1}}</span></span></td>
              <td v-else>{{'--'}}</td>
              <td>{{transcript.second_term?transcript.second_term:'-'}} <span style="display:none" v-if="transcript.second_term>0">{{total2=total2+transcript.second_term}}<span style="display:none">{{count2=count2+1}}</span></span></td>
              <td>{{transcript.third_term?transcript.third_term:'-'}}<span style="display:none" v-if="transcript.third_term>0">{{total3=total3+transcript.third_term}}<span style="display:none">{{count3=count3+1}}</span></span></td>
              <td>{{transcript.total?(transcript.total).toFixed(2):'-'}}</td>
              <td>{{transcript.average?transcript.average:'-'}}</td>
              <td>{{transcript.grade?transcript.grade:'-'}}</td>
        </tr>
        <tr>
            <td></td>
            <td class="text-bold">Total</td>
            <td v-if="parseFloat(total1)>0">{{total1}}</td>
            <td v-else>-</td>
            <td v-if="parseFloat(total2)>0">{{total2}}</td>
            <td v-else>-</td>
            <td v-if="parseFloat(total3)>0">{{total3}}</td>
            <td v-else>-</td>

        </tr>
         <tr>
            <td></td>
            <td class="text-bold">Terminal Average</td>
            <td v-if="parseFloat(total1)>0">{{(total1/count1).toFixed(2)}}<span style="display:none">{{total1=0}}{{count1=0}}</span></td>
            <td v-else></td>
            <td v-if="parseFloat(total2)>0">{{(total2/count2).toFixed(2)}}<span style="display:none">{{total2=0}}{{count2=0}}</span></td>
            <td v-else></td>
            <td v-if="parseFloat(total3)>0">{{(total3/count3).toFixed(2)}}<span style="display:none">{{total3=0}}{{count3=0}}</span></td>
            <td v-else></td>
        </tr>
</tbody>
    </table></div>


    </div>
    <div class="container">
        <div class="  card-body row ">
<div  class=" row col-6"><span><b>Authorized Signature:&nbsp;&nbsp;</b></span></div>
<div  class=" row col-6 "><span><b>Name:&nbsp;</b >{{principal.name}}</span></div>

</div>
    </div>
</div></div></div></div>
</template>
<script>
     import jspdf from "jspdf";
    import html2canvas from "html2canvas";
    import {mapState} from "vuex";
    export default {
      computed: mapState(['school']),
    data(){
        return{
                transcripts:'',
                levels:'',
                total1:0,
                total2:0,
                total3:0,
                count1:0,
                count2:0,
                count3:0,
                student:0,
                school_id:'',
                principal:''
        }
    },
    mounted(){
axios.get('/api/transcript/'+this.$route.params.student_id)
.then(res=>{
    this.transcripts=res.data.transcript;
    this.levels=res.data.levels;
    this.student=res.data.student;
    this.principal=res.data.principal


})
    },
    methods:{
      printResults(){
                  window.print();
                },
    },
    created(){
        this.school_id=window.user.school_id
        console.log(this.school_id)
    }
}
</script>

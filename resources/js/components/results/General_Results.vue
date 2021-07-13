<template>
     <div >



<div  class="content col-md-12" >
<div  class="card card-navy card-outline">
<div class="card-header">

    <h5 class="header text-danger text-uppercase float-left">Result sheet for {{summary.student.surname}}&nbsp; {{summary.student.first_name}}, &nbsp; {{report.title}}</h5>
    <button v-show="$gate.isAdminOrStudent()" class="btn btn-primary float-right pl-2" @click="printResults"><i class="fa fa-print"></i>Print Result</button>

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
<h5 class="pt-2 text-danger">Results Sheet</h5>
</div>
</div>
<div>
<div class=" col col-md-2 float-right" >
<center>
    <image-loader
        :src="`/img/profile/${user.photo}`"

        placeholder="/img/profile.png"
         width="100px" height="100px"
         class="img-thumbnail img-result "
         />




</center>
</div>
</div>
<div class="col col-12 py-2 ">

<div class="row col-md-10">
<div class="col-md-6 py-2">
<h5><b>Name:</b>&nbsp; {{summary.student.surname}}&nbsp; {{summary.student.first_name}} &nbsp; {{summary.student.middle_name?summary.student.middle_name:''}}</h5>
<h5><b>class:&nbsp;</b> {{report.levels.level_name}}&nbsp;{{arm.name}}</h5>
<h5><b>Gender:&nbsp;</b> {{'-----------'}} </h5>
<h5 ><b>Dob:</b>&nbsp; {{'-------------'}}</h5>
<h5 ><b>Portal ID:</b>&nbsp; {{user.portal_id}}</h5>
</div>
<div class="col-md-6">
<h4 class="text-primary text-uppercase  "><b>&nbsp;</b> {{report.header?report.header:''}}</h4>
<hr class="text-danger mb-1">
<h5 ><b>Term:&nbsp;</b> {{report.terms.name}}</h5>
<h5><b>Session:&nbsp;</b> {{report.sessions.name}} </h5>
<h5 ><b>Next Term Begins:&nbsp;</b> {{report.term_start?report.term_start:'---------'}}</h5>

</div>

</div>

</div>
<div class="  table-responsive py-3"  >
<table class="table table-bordered table-sm font-weight-bold  text-nowrap">
<thead>
<tr>
<th>S/N</th>
<th>Subject</th>
<th v-show="!isTest2">CA</th>
<th v-show="isTest2">1st CA</th>
<th v-show="isTest2">2nd CA</th>
<th v-show="isTest3">3rd CA</th>
<th>Exams</th>
<th v-show="isMidterm">Mid Term</th>
<th>Total</th>
<th v-show="isThird_term && isCummulative" >1st Term</th>
<th v-show="isThird_term && isCummulative" >2nd Term</th>
<th v-show="isThird_term && isCummulative" >Grand Total</th>
<th v-show="isThird_term && isCummulative"  >C.Avg</th>

<th >Grade</th>
<th>Narration</th>
<th class="text-center" v-show="isAHScore"><div><span>ASH<br> Score</span></div></th>
<th class="text-center" v-show="isASLScore"><div><span>ASL <br> Score</span></div></th>
<th class="text-center" v-show="isASAScore"><div><span>ASA <br> Score</span></div></th>
<th class="text-center" v-show="isASPosition"><div><span>AS<br>Position</span></div></th>

<th class="text-center" v-show="isCSPosition"><div><span>CS<br> Position</span></div></th>
<th class="text-center" v-show="isCSHScore"><div><span>CSH<br> Score</span></div></th>
<th class="text-center" v-show="isCSLScore"><div><span>CSL <br> Score</span></div></th>
<th class="text-center" v-show="isCAScore"><div><span>CSA <br> Score</span></div></th>


</tr>
</thead>

<tbody>
<tr v-for="(score,index) in scores">
<td>{{index+1}}</td>
<td>{{score.subjects?score.subjects.name:''}}</td>
<td>{{score.test1?score.test1:'-'}}</td>
<td v-show="isTest2">{{score.test2}}</td>
<td v-show="isTest3">{{score.test3}}</td>
<td>{{score.exams?score.exams:'-'}}</td>
<td  v-show="isMidterm">{{score.mid_term?score.mid_term:'-'}}</td>
<td>{{score.total?score.total:'-'}}</td>
<td v-show="isThird_term && isCummulative"  v-for="total in Total"  v-if="(total.subject_id===score.subject_id && total.term_id===1)">
{{total.total?total.total:'-'}}</td>
<td v-show="isThird_term && isCummulative"   v-for="total in Total"  v-if="(total.subject_id===score.subject_id && total.term_id===2)">
{{total.total?total.total:'-'}}</td>
<td v-show="isThird_term && isCummulative" >{{score.grand_total?score.grand_total:'-'}}</td>
<td v-show="isThird_term && isCummulative" >{{score.average?score.average:'-'}}</td>

<td v-show="isThird_term && isCummulative" >{{score.cummulative_grade?score.cummulative_grade:'-'}}</td>
<td v-show="isThird_term && isCummulative" >{{score.cummulative_narration?score.cummulative_narration:'-'}}</td>

<td v-show="!isThird_term ||!isCummulative">{{score.grade}}</td>
<td v-show="!isThird_term ||!isCummulative">{{score.narration}}</td>

<td v-show="isAHScore">{{score.arm_max_score?score.arm_max_score:'-'}}</td>
<td v-show="isASLScore">{{score.arm_min_score?score.arm_min_score:'-'}}</td>
<td v-show="isASAScore">{{score.arm_avg_score?score.arm_avg_score:'-'}}</td>
<td v-show="isASPosition">{{score.arm_subj_position}}</td>

<td v-show="isCSPosition">{{score.class_subj_position?score.arm_subj_position:''}}</td>
<td v-show="isCSHScore">{{score.max_class_score}}</td>
<td v-show="isCSLScore">{{score.min_class_score}}</td>
<td v-show="isCAScore">{{score.class_avg_score}}</td>


</tr>
       <!-- None ACADEMICS-->
<tr v-show="noneAcademic">
<td  colspan="20 " style="border:none"><div class="text-center text-bold text-primary"> NON ACADEMIC SUBJECTS</div></td>
</tr>

<tr v-for="(score,index) in noneAcademic">
<td>{{index+1}}</td>
<td>{{score.subjects?score.subjects.name:''}}</td>
<td>{{score.test1?score.test1:''}}</td>
<td v-show="isTest2">{{score.test2}}</td>
<td v-show="isTest3">{{score.test3}}</td>
<td>{{score.exams?score.exams:''}}</td>
<td  v-show="isMidterm">{{score.mid_term?score.mid_term:''}}</td>
<td>{{score.total?score.total:''}}</td>
<td v-show="isThird_term && isCummulative"  v-for="total in Total"  v-if="(total.subject_id===score.subject_id && total.term_id===1)">
{{total.total?total.total:'-'}}</td>
<td v-show="isThird_term && isCummulative"   v-for="total in Total"  v-if="(total.subject_id===score.subject_id && total.term_id===2)">
{{total.total?total.total:'-'}}</td>
<td v-show="isThird_term && isCummulative" >{{score.grand_total?score.grand_total:'-'}}</td>
<td v-show="isThird_term && isCummulative" >{{score.average?score.average:'-'}}</td>
<td v-show="isThird_term && isCummulative" >{{score.cummulative_grade?score.cummulative_grade:'-'}}</td>
<td v-show="isThird_term && isCummulative" >{{score.cummulative_narration?score.cummulative_narration:'-'}}</td>

<td v-show="!isThird_term ||!isCummulative">{{score.grade}}</td>
<td v-show="!isThird_term ||!isCummulative">{{score.narration}}</td>

<td v-show="isAHScore">{{score.arm_max_score?score.arm_max_score:''}}</td>
<td v-show="isASLScore">{{score.arm_min_score?score.arm_min_score:''}}</td>
<td v-show="isASAScore">{{score.arm_avg_score?score.arm_avg_score:''}}</td>
<td v-show="isASPosition">{{score.arm_subj_position?score.arm_subj_position:''}}</td>

<td v-show="isCSPosition">{{score.class_subj_position?score.arm_subj_position:''}}</td>
<td v-show="isCSHScore">{{score.max_class_score}}</td>
<td v-show="isCSLScore">{{score.min_class_score}}</td>
<td v-show="isCAScore">{{score.class_avg_score}}</td>

</tr>
</tbody>


</table>

</div>

<div class="col-md-12 row" v-show="isLDomain">
<div class="col-md-6">
    <table class="table table-bordered table-sm font-weight-bold  table-striped" >
<tr >
 <th colspan="2"  class="text-uppercase text-center text-danger text-bold">Behavioural Assesment</th>
 </tr>
 <tbody>
     <tr v-for="ldomain in LDomain" :key="ldomain.id">
     <td v-if="ldomain.ldomain.type==='Behavioural'">{{ldomain.ldomain.domain}}</td>
     <td v-if="ldomain.ldomain.type==='Behavioural'">{{ldomain.grade}}</td>
     </tr>
 </tbody>
    </table>
</div>
<div class="col-md-6">
<table class="table table-bordered table-sm table-striped" >
<tr >
 <th colspan="2"  class="text-uppercase text-center text-danger text-bold">Skill Assesment</th>
 </tr>

 <tbody>
     <tr v-for="ldomain in LDomain" :key="ldomain.id">
     <td v-if="ldomain.ldomain.type==='Skill'">{{ldomain.ldomain.domain}}</td>
     <td v-if="ldomain.ldomain.type==='Skill'">{{ldomain.grade}}</td>
     </tr>
 </tbody>
    </table>
</div>

</div>



<div class="row col-md-12 py-1">
<div class="col-md-4 " v-show="isRSummary">
 <table class="table table-bordered table-sm font-weight-bold  table-striped" >
 <tr >
 <th colspan="2"  class="text-uppercase text-center text-primary text-bold">Results summary</th>
 </tr>

 <tbody>
 <tr>
 <td v-show="isCPosition" >Position In Class</td><td v-show="isCPosition" >{{summary.class_position}}</td>
 </tr>
 <tr>
 <td v-show="isCAPosition" >Position In Arm</td><td v-show="isCAPosition" >{{summary.arm_position}}</td>
 </tr>
 <tr>
 <td>Total score</td><td>{{summary.total_scores}}</td>
 </tr>
 <tr>
 <td>Average score</td><td>{{summary.average_scores	}}</td>
 </tr>
 <tr>
 <td v-show="isThird_term && isCummulative">Cummulative Avg score</td><td v-show="isThird_term && isCummulative">{{summary.cummulative_average	}}</td>
 </tr>
 <tr>
 <td>Grade</td><td>{{summary.grade}}</td>
 </tr>
 <tr>
 <td>Narration<td>{{summary.narration}}</td>
 </tr>
 <tr>
 <td>Total Students in Arm</td><td>{{summary.total_students}}</td>
 </tr>
 <tr>
 <td>Progress status</td><td>{{comment?comment.comment:''}}</td>
 </tr>
 <tr>
 <td>Remarks</td><td>{{comment?comment.comment:''}}</td>
 </tr>

 </tbody>
 </table>
</div>
<div class="  col-md-4  " v-show="isGFormula">
<table class=" table table-bordered table-sm   table-striped" width="100%">
<tr>
<th colspan="3" class="text-center text-primary" >Grading Key</th>
</tr>

<tbody>
<tr v-for="grade in grades" :Key="grade.id">
<td>{{grade.lower_bound}} - {{grade.upper_bound}}</td><td>{{grade.grade}}</td><td>{{grade.narration}}</td>
</tr>
</tbody>
</table>
</div>
<div class=" col-md-4 ">
<table class="table table-bordered table-sm  table-striped " width="100%" >
 <tr >
 <th colspan="2"  class="text-uppercase text-center text-bold text-primary">Short Keys</th>
 </tr>

 <tbody>

 <tr>
 <td>CA</td><td>Contineous Assesment</td>
 </tr>
 <tr>
 <td>CS Position</td><td>Class Subject Position</td>
 </tr>
 <tr>
 <td>SH Score</td><td>Subject Highest Score</td>
 </tr>
 <tr>
 <td>SL Score</td><td>Subject Lowest Score</td>
 </tr>
 <tr>
 <td>CSA Score<td>Class Subject Avg. Score</td>
 </tr>

 <tr>
 <td>AS Position</td><td>Arm Subject Position</td>
 </tr>
 <tr>
 <td>ASL Score</td><td>Arm Subject Lowest Score</td>
 </tr>
 <tr>
 <td>ASH Score</td><td>Arm Subject Highest Score</td>
 </tr>
 <tr>
 <td>ASA Score</td><td>Arm Subject Average Score</td>
 </tr>


 </tbody>
 </table>

</div>
</div>
<div class="  card-body row ">
<div v-show="isPComment" class=" row col-6"><span><b>Principal's Comment:&nbsp;</b>{{principal_comment?principal_comment:''}}</span></div>
<div v-show="isTComment" class=" row col-6 "><span><b>Teacher's Comment:&nbsp;</b >{{staff_comment?staff_comment:''}}</span></div>

</div>
<center>
<div class=" text-center"><span><b>Authorized Signature:&nbsp;</b ><img :src="`/img/signatures/${signature.photo}`" class="ml-2 img-result " width="100" height="50" onerror="this.style.display='none'"></span></div>
</center>
</div>


</div>

</div>

    </div>

</template>

<script >
   // import jspdf from "jspdf";
   // import html2canvas from "html2canvas";
    import {mapState} from "vuex";
    export default {
      computed: mapState(['school']),
        data(){
            return {
               summary:'',
               scores:{},
               noneAcademic:'',
               user:{},
               comment:'',
               Total:{},
               report:{},
               LDomain:{},
               arm:{},
               grades:{},
               signature:'',
               principal_comment:'',
               staff_comment:'',
               config_settings:[],
               isThird_term:false,
                 isAHScore:false,
                 isASAScore:false,
                 isASPosition:false,
                 isCAPosition:false,
                 isCAScore:false,
                 isCPosition:false,
                 isCSHScore:false,
                 isCSLScore:false,
                 isASLScore:false,
                 isCSPosition:false,
                 isGFormula:false,
                 isLDomain:false,
                 isPStatus:false,
                 isRSummary:false,
                 NoSetting:false,
                 isTest1:true,
                 isTest2:false,
                 isTest3:false,
                 isPComment:false,
                 isTComment:false,
                 isCummulative:false,
                 isMidterm:false

            }
        },
        mounted(){

axios.get('/api/result_config')
   .then((result) => {
     let data=result.data
     data.forEach(element => {

       if(element.name==='Show Grading Formula') {
         if(element.status===1){
          this.isGFormula=true
         }

   }
   if(element.name==='Show Class Position'){
       if(element.status===1){
    this.isCPosition=true
         }
   }
    if(element.name==='Show Class Arm Position') {
       if(element.status===1){
    this.isCAPosition=true
         }
   }
     if(element.name==='Show Result Summary') {
       if(element.status===1){
        this.isRSummary=true
         }
   }
     if(element.name==='Show Progress Status') {

     if(element.status===1){
    this.isPStatus=true
         }
   }
    if(element.name==='Show Class Subject Position') {
      if(element.status===1){
    this.isCSPosition=true
         }

   }
     if(element.name==='Show Class Subject Highest Score') {
      if(element.status===1){
    this.isCSHScore=true
         }

   }
     if(element.name==='Show Class Subject Lowest Score') {
      if(element.status===1){
       this.isCSLScore=true
         }

   }
   if(element.name==='Show Class Average Score') {
       if(element.status===1){
    this.isCAScore=true
         }

   }
   if(element.name==='Show Arm Subject Highest Score') {
     this.isAHScore=true

   }
 if(element.name==='Show Arm Subject Average Score') {
       if(element.status===1){
    this.isASAScore=true
         }
   }
   if(element.name==='Show Grading Formula') {
       if(element.status===1){
    this.isGFormula=true
         }


   }
    if(element.name==='Show Learning Domain') {
      if(element.status===1){
       this.isLDomain=true
         }

   }
    if(element.name==='Show Arm Subject Position') {
      if(element.status===1){
       this.isASPosition=true
         }

   }
   if(element.name==='Show Arm Subject Lowest Score') {
      if(element.status===1){
       this.isASLScore=true
         }

   }
//     if(element.name==='Use Cummulative For Third Term') {
//       if(element.status===1){
//        this.isCummulative=true
//          }

//    }


   if(element.name==='Show Test2') {
      if(element.status===1){
       this.isTest2=true
         }

   }
   if(element.name==='Show Test3') {
      if(element.status===1){
       this.isTest3=true
         }

   }
   if(element.name==="Show Principal's Comment") {
      if(element.status===1){
       this.isPComment=true
         }

   }
   if(element.name==="Show Teacher's Comment") {
      if(element.status===1){
       this.isTComment=true
         }

   }
      // this.checkSettings(element)
     });

    // console.log(this.config_settings)
   }).catch((err) => {

   });


},
        methods:{
                printResults(){
                  window.print();
                },
            setComment(average){
              var comment;
              console.log(average)

   if (0>=average && average<=39.99){
    comment==='Fail! Please sit up'
   }

   if (40>=average && average<=44.99) {
     comment==='Poor! Please put more effort'
   }

   if (45>=average && average<=49.99) {
     comment==='Fair! Please put more effort'
   }

   if (50>=average && average<=59.99) {
     comment==='Average performance, work harder'
   }
   if (60>=average && average<=64.99) {
     comment==='A Good perfomance! You can do better'
   }

   if (65>=average && average<=74.99) {
     comment==='Above average, Very good. Keep it up'
   }

   if((75 >= average) && (average <= 100)) {
     comment==='Excellent Performance. Keep it up'
   }


  else {
    comment==='Ungraded'
   }
  return comment
}




    },
    created(){

            const student_id=this.$route.params.student_id
            const report_id=this.$route.params.report_id
            this.$Progress.start()
            if(this.$route.params.student_id){

            axios.get(`/api/result/${report_id}/${student_id}`)
            .then((result) => {
                if(result.data.Not_found){

                // this.$router.push('/reports');
                this.$router.push('/not-found')
              }
              //console.log(result.data);
                this.scores=result.data.scores
                //console.log(this.scores[0])
                 this.summary=result.data.summary
                 this.user=result.data.user
                  this.Total=result.data.pastTotal
                  this.comment=result.data.comment
                this.report=result.data.report
                 this.arm=result.data.arm
                 this.grades=result.data.gradings
                 this.principal_comment=result.data.principal_comment
                 this.staff_comment=result.data.staff_comment

                 this.isMidterm=result.data.scores[0].mid_term?true:false
                 if(this.report.term_id===3){
                     this.isThird_term=true
                      console.log(this.report)
                      if(this.report.isCummulative===1){
                          this.isCummulative=true

                     }
                 }
                 this.signature=result.data.signature
                  this.noneAcademic=result.data.noneAcademic
                 this.LDomain=result.data.LDomain


                 this.$Progress.finish()
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
               console.log(result.data);
                this.scores=result.data.scores
                 this.summary=result.data.summary
                 this.user=result.data.user
                  this.Total=result.data.pastTotal
                  this.comment=result.data.comment
                this.report=result.data.report
                 this.arm=result.data.arm
                 //console.log(result.data.Scores)
                 this.isMidterm=result.data.scores[0].mid_term?true:false
                 this.principal_comment=result.data.principal_comment
                 this.staff_comment=result.data.staff_comment
                 if(this.report.term_id===3){
                     this.isThird_term=true
                     if(this.report.isCummulative===1){
                          this.isCummulative=true
                     }
                 }

                  this.signature=result.data.signature
                    this.LDomain=result.data.LDomain
                 this.noneAcademic=result.data.noneAcademic
                 this.$Progress.finish()
                 // console.log(this.signature);
               //console.log(this.report);

            }).catch((err) => {

            });
        }






    }
    }


</script>




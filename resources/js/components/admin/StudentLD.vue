<template>
  <div >
<div class="content-header">

      <div class="container-fluid navy" >
        <div class="row mb-2">
          <div class="col-sm-6">
            <h2 class="m-0 text-dark">Learning Domain Grading</h2>
          </div><!-- /.col -->
          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Learning Domain</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
   <div class="content col-12">
<div class="card card-navy card-outline ">
<form  @submit.prevent="createAssessment" >
<div class="card-header">
<div class="ribbon-wrapper">
    <div class="ribbon bg-primary">
      Admin
      </div>
    </div>


</div>

<div class="card-body" v-if="$gate.isTutorOrAdmin()">

<div  class="form-group col-12" >
                             <select
                    name="report_id"
                    id="report_id"
                    :class="{'is-invalid':form.errors.has('report_id')}"
                    class="form-control"
                    v-model="form.report_id"
                   @change="loadArms"

                         >
                    <option value selected>Select Report class set</option>
                    <option
                      v-for="report in reports"
                      :key="report.id"
                      :value="report.id"
                    >{{report.title}}</option>
                  </select>
                        <has-error :form="form" field="subject"></has-error>
                    </div>

                 <div  class="form-group col-12"  >
                             <select
                    name="arm_id"
                    id="arm_id"
                    :class="{'is-invalid':form.errors.has('report_id')}"
                    class="form-control"
                    v-model="form.arm_id"

                @change="setResult"
                         >
                    <option value selected>Select Class/Level Arm</option>
                    <option
                      v-for=" arm in arms"
                      :key="arm.arms.id"
                      :value="arm.arms.id"

                    >{{arm.arms.name}}</option>
                  </select>
                        <has-error :form="form" field="subject"></has-error>
                    </div>
                    <div  class="form-group col-12" v-show="isResults">
                             <select
                    name="learning_domain_id"
                    id="report_id"
                    :class="{'is-invalid':form.errors.has('report_id')}"
                    class="form-control"
                    v-model="form.learning_domain_id"
                      @change="loadAssessment"

                         >
                    <option value selected>Select Assessments</option>
                    <option
                      v-for=" ldomain in LDomain"
                      :key="ldomain.id"
                      :value="ldomain.id"
                    >{{ldomain.domain}}</option>
                  </select>
                        <has-error :form="form" field="learning_domain_id"></has-error>
                    </div>

<div v-show="isResults" class="card-body">
    <div class="container"><button v-show="isEntered" @click="removeAssesment" type="button"  class="btn btn-flat btn-danger float-right">Assessment Already Entered. Click To Remove</button></div>
<table class="table  table-sm table-striped ">
<thead>
<tr>
<th>S/N</th><th>Name</th><th>Average Scores</th>
<th>
 Grades
</th>
</tr>
</thead>
<tbody>
<tr v-for="(assessment,index) in Assessments " :key="index">
<td>{{index+1}}</td>
<td>{{assessment.name}}
 <input type="hidden" :id="`student_id${index}`" :value="assessment.student_id">
 </td>
 <td>{{assessment.average_score?assessment.average_score:''}}</td>

<td  >

<div class="icheck-success icheck-inline">
    <input type="radio" :id="`grade1${index}`"   value="5" :name="`grade${index}`" :checked="assessment.grade==='A'?true:false">

    <label :for="`grade1${index}`">5</label>
</div>
<div class="icheck-success icheck-inline pl-2">
    <input type="radio" :id="`grade2${index}`"   value="4" :name="`grade${index}`"  :checked="assessment.grade==='B'?true:false">
    <label :for="`grade2${index}`">4</label>
</div>
<div class=" icheck-success icheck-inline ">
    <input type="radio" :id="`grade3${index}`"  value="3" :name="`grade${index}`" :checked="assessment.grade==='C'?true:false">
    <label :for="`grade3${index}`">3</label>
</div>
<div class="icheck-warning icheck-inline ">
    <input type="radio" :id="`grade4${index}`"  value="2" :name="`grade${index}`" :checked="assessment.grade==='D'?true:false">
    <label :for="`grade4${index}`">2</label>
</div>
<div class="icheck-danger icheck-inline ">
    <input type="radio" :id="`grade5${index}`"   value="1" :name="`grade${index}`" :checked="assessment.grade==='E'?true:false">
    <label :for="`grade5${index}`">1</label>
</div>

</td>

</tr>

</tbody>
</table>
<div class="card-footer"><button class="btn btn-success">submit</button></div>
</div>
</div>


</form>

    </div></div></div>
</template>

<script>


    export default {


        data(){

            return{
                isEntered:false,
                Assessments:{},
                reports:{},
                LDomain:{},
               isResults:false,
           arms:{},

form:new Form({
     report_id:'',
     arm_id:'',
     student_id:[],
     grade:[],
     learning_domain_id:'',
     number_of_students:0,
})
            }


        },
        mounted(){
            axios.get('/api/load_report')
            .then(result => {
               console.log(result.data);
                this.reports=result.data;
            }).catch((err) => {

            });
           // this.loadSubjects();
        },
        methods:{


           loadAssessment(){
                this.$Progress.start();
          axios.get(`/api/load_assessment/${this.form.arm_id}/${this.form.report_id}/${this.form.learning_domain_id}`)
          .then((result) => {
             this.Assessments=result.data;
             let counter=0;
              this.Assessments.map(el=>{
                    if(el.grade){
                        ++counter
                    }
              })
              if(counter>0){
                  this.isEntered=true
              }else{
                  this.isEntered=false
              }

             this.$Progress.finish();


          }).catch((err) => {
              this.$Progress.fail();
          });
           },
             setResult(){
               this.isResults=true
             },
            createAssessment(){


            this.form.student_id=[];
            this.form.grade=[];

            this.form.number_of_students=0;
            let selected;
         for (let index = 0; index < this.Assessments.length; index++) {
                 let grade=document.getElementsByName(`grade${index}`)
                     for (let i = 0; i < grade.length; i++){
                         if(grade[i].checked){
                             selected = grade[i];
                                break;
                         }
                     }
                     if(selected){
                         this.form.grade.push(selected.value)
                     }
           var student_id=document.querySelector(`#student_id${index}`).value
            this.form.student_id.push(student_id)
            this.form.number_of_students=++this.form.number_of_students;
        }
           if(this.form.learning_domain_id===""){
               alert('please select learning domain');
           }else{

        this.$Progress.start();
          if(this.$gate.isAdminOrTutor()){
            this.form.post(`api/assessment`)

            .then(res=>{
             console.log(res.data)

              toast.fire({
                        type: 'success',
                        title: 'Result activation updated successfully'
                        })
             this.$Progress.finish();

              }).catch(err=>{
                toast.fire({
                        type: 'danger',
                        title: 'there was error uploading the file'+err
                        })
              });


    }}},
    loadArms(){
       axios.get('/api/getArms/'+this.form.report_id).then(res=>{
           this.arms=res.data
           console.log(this.arms)
       })


    },
    removeAssesment(){
          this.form.post('/api/delete_ssessment')
          .then(res=>{
              toast.fire({
                        type: 'success',
                        title: 'Assessment removed successfully'
                        });
                       // this.Assessments={};
                        this.loadAssessment();
                       

          })

    },
loadDomain(){
    axios.get('api/get_domain')
    .then(res=>{
        this.LDomain=res.data

    })
}

    },
    created() {

         this.loadDomain();

        //    Fire.$on('AfterCreate',() => {

        //       // this.loadSubjects();
        //       // $this.form.reset();
        //    });
        // //    setInterval(() => this.loadUsers(), 3000);

        }
    }

</script>

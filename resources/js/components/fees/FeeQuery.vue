<template>
 <div >
<div class="content-header">

      <div class="container-fluid navy" >
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Fee </h1>
          </div><!-- /.col -->
          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Fees Group</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

        <div class="content" v-if="$gate.isAdminOrTutorOrStudentOrParent()">
          <div class="col-12">
            <div class="card card-navy card-outline">
              <div class="card-header">
                  <div class="col-md-10">
                       <div class="form-group">
                           <label>Session</label>
                           <select class="form-control"
                           v-model="form.session_id">
                           <option value="">Select session</option>
                           <option v-for="session in sessions" :key="session.id"
                           :value="session.id"
                           >{{session.name}}</option>

                           </select>
                       </div>
                   </div>
               <div class="row col-md-12">

                   <div class="col-md-4">
                       <div class="form-group">
                           <label>Serach By</label>
                     <select
                    name="search"
                    id="search"
                    class="form-control"
                    v-model="searchCondition"
                    @change="checkSearchCondition"
                  >
                    <option value selected>search by</option>
                    <option
                      v-for="condition in SearchConditions"
                      :key="condition.condition"
                      :value="condition.condition"
                    >{{condition.by}}</option>
                  </select>

                 </div>


                   </div>
                   <div class="col-md-4" v-show="SEARCH_BY_TERM">
                    <div class="form-group">
                           <label>Serach Term</label>
                     <select
                    name="term_id"
                    id="term_id"
                    :class="{'is-invalid':form.errors.has('term_id')}"
                    class="form-control"
                    v-model="form.term_id"
                    @change="queryFees"
                  >
                    <option value selected>Select Term</option>
                    <option
                      v-for="term in terms"
                      :key="term.id"
                      :value="term.id"
                    >{{term.name}}</option>
                  </select>
                        <has-error :form="form" field="term_id"></has-error>
                 </div>
                 </div>
                 <div class="col-md-4" v-show="SEARCH_BY_FEE_TYPE">

              <div class="form-group">
                           <label>Search By Fee Type</label>
                     <select
                    name="feetype"
                    id="feetype"
                    :class="{'is-invalid':form.errors.has('term_id')}"
                    class="form-control"
                    v-model="form.feeType"
                    @change="checkFeeType"
                  >
                    <option value selected>Select Fee Type</option>
                    <option
                      v-for="feeType in feeTypes"
                      :key="feeType.value"
                      :value="feeType.value"
                    >{{feeType.text}}</option>
                  </select>

                 </div>
                  </div>
                   <div class="col-md-4" v-show="isClassBase">
                       <div class="form-group" >
                           <label>Search By Level</label>
                     <select
                    name="level_id"
                    id="level_id"
                    class="form-control"
                    v-model="form.level_id"
                    @change="queryFees"
                  >
                    <option value selected>Select Level</option>
                    <option
                      v-for="level in levels"
                      :key="level.id"
                      :value="level.id"
                    >{{level.level_name}}</option>
                  </select>

                 </div>
                   </div>

               </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <table class="  table table-hover " id="data_tb">
                  <tbody>
                    <tr>
                        <th>S/ID</th>
                        <th>Students</th>

                        <th >Level</th>
                        <th >Fee Type</th>
                        <th>Amount</th>
                  </tr>
                  <tr v-for="report in reports" :key="report.id">

                    <td>{{report.id}}</td>
                    <td>{{report.name}}</td>
                    <td>{{report.level}} </td>
                     <td>{{report.fee_type?(report.fee_type):''}}</td>
                     <td>{{report.amount}}</td>
                  </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="reports" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>

        <div v-if="!$gate.isAdminOrTutorOrStudentOrParent()">
            <not-found></not-found>
        </div>
<!-- Arms Modal -->



<!-- DetailsModal -->



        </div>



</template>

<script>
import Options from '../cbt/Options.vue';
    export default {
  components: { Options },

        data() {
            return {
                editmode: false,
                levels : {},
                sessions:{},
                terms:{},

                gradinggroup:'',

                reports:[],
                isClassBase:false,
                 feeTypes:[
                       {text:'General Fees', value:'GENERAL-BASED'},
                       {text:'New Intakes-General', value:'NEW-BASED'},
                       {text:'New Intakes-Class specific', value:'NEW-CLASS-BASED'},
                       {text:'Class Specific', value:'CLASS-BASED'},
                         ],

                form: new Form({
                    level_id : '',
                    term_id: '',
                    feeType:'',
                    session_id:'',
                    searchCondition:''
                }),
              searchCondition:'',
               SEARCH_BY_TERM:false,
               SEARCH_BY_FEE_TYPE:false,
              SearchConditions:[
                  {condition:"TERM",by:"TERM"},
                  {condition:"FEE-TYPE",by:"FEE-TYPE"},
              ]

        }},
        mounted(){
      axios.get('api/get_levels')
                .then(res=>{
                    this.levels=res.data;})

                    axios.get('api/term')
                .then(res=>{
                    this.terms=res.data;})

                    axios.get('api/load_session')
                .then(res=>{
                    this.sessions=res.data;})

        },


        methods: {
            getResults(page = 1) {
                        axios.get('api/fees?page=' + page)
                            .then(response => {
                                this.reports = response.data;
                            });
                },


            checkFeeType(){

if( this.SEARCH_BY_FEE_TYPE &&(this.form.feeType==="CLASS-BASED"||this.form.feeType==="NEW-CLASS-BASED")){
    this.isClassBase=true
}else{
     this.isClassBase=false
     this.queryFees();
}
            },

checkSearchCondition(){
if(this.searchCondition==="TERM" &&this.searchCondition){
    this.SEARCH_BY_TERM=true
    this.SEARCH_BY_FEE_TYPE=false
    this.isClassBase=false
}else
if(this.searchCondition==="FEE-TYPE" &&this.searchCondition)
{
      this.SEARCH_BY_TERM=false
      this.SEARCH_BY_FEE_TYPE=true
    }
    else{
      this.SEARCH_BY_TERM=false
      this.SEARCH_BY_FEE_TYPE=false
    }
            },

            loadFees(){

                if(this.$gate.isAdminOrTutorOrStudentOrParent()){
                    axios.get("api/fees").then( response  => {

                      this.reports = response.data

                      });

                }
            },


  queryFees(){
        if(this.form.session_id){
            this.form.searchCondition=this.searchCondition
      this.form.post('/api/queryFees')
      .then(res=>{
          this.reports=res.data
      })
        }else{
            alert('Please select session')
        }
  },



createDescription(){
         this.$Progress.start();

                this.DetailsForm.post('api/fee_description')
                .then((res)=>{
                    $('#detailsModal').modal('hide')

                    toast.fire({
                        type: 'success',
                        title: 'Description created  successfully'
                        })
                    this.$Progress.finish();
                     this.$router.push('/fee_description/'+res.data.feegroup_id)
                })
                .catch(()=>{

                })

},



        },
        created() {

            Fire.$on('searching',() => {
                let query = this.$parent.search;
                axios.get('api/findStudent?q=' + query)
                .then((data) => {
                    this.levels = data.data
                })
                .catch(() => {

                })
            })
              this.loadPaystack();
           this.loadFees();
           Fire.$on('AfterCreate',() => {
               this.loadFees();
           });
        //    setInterval(() => this.loadUsers(), 3000);

        }

    }
</script>

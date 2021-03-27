<template>
<div >
<div class="content-header">

      <div class="container-fluid navy" >
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Level  History</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Move Level To History</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
<div class="card">


<div class="card-header"><h3 class="text-center"> Move Student To Arms</h3></div>
  <div class="card-body">

<div class="container">
    <div class="form-group">
        <select name="arm" id="arm" class="form-control"
        v-model="form.arm_id"
        :class="{ 'is-invalid': form.errors.has('arm_id') }"
        @change="loadStudentsByArm"
        >
   <option value="">Select Arm to move students</option>
        <option v-for="arm in arms" :key="arm.id"
        :value="arm.arms.id">{{arm.arms.name}}</option>

        </select>
        <has-error :form="form" field="arm_id"></has-error>
    </div>
</div>

<div class="card-body table-responsive " v-show="isArm">
<datatable
	title="Students List"
	:columns="tableColumns"
	:rows="tableRows"
	locale="en"
    :perPage="[15, 30, 60]"
>

</datatable>


              </div>

  <div class="table-responsive">
  <div class=" row card-header mt-2">
        <div class="col-md-6 col-sm-6 text-center"><h3 class="text-success">Current Student</h3></div>
        <div class="col-md-6 col-sm-6 text-center"><h3 class="text-danger">  Move To Arm </h3></div>
    </div>

     <DualListBox
     class="mt-5"

    :source="source"
    :destination="destination"
    label="name"
    @onChangeList="onChangeList"
/>
</div>
<div class="card-footer mt-5">
    <button class="btn btn-danger" @click="AssignArms">submit</button>
</div>

</div>

</div>
</div>
</template>

<script>
import DualListBox from "dual-listbox-vue";
import "dual-listbox-vue/dist/dual-listbox.css";
import DataTable from "vue-materialize-datatable";
export default {
    name: "App",
    components: {
        DualListBox,
       datatable: DataTable

    },
    data() {
        return {
            isArm:false,
            source: [],
            destination: [],
            history:[],
            students:[],
            id:this.$route.params.id,
            seleted:[],
            arms:[],
            arm:'',
            form:new Form({
                students:[],
                arm_id:'',
                oldlevel_id:this.$route.params.id,
                newlevel_id:'',
                level_id:''
            }

            ),
            tableColumns: [
                  {
				label: "S/ID",
				field: "id",
				numeric: false,
				html: false
            },
			{
				label: "SURNAME",
				field: "surname",
				numeric: false,
				html: false
            },
            {
				label: "FIRST NAME",
				field: "first_name",
				numeric: false,
				html: false
            },
            {
				label: "MIDDLE NAME",
				field: "middle_name",
				numeric: false,
				html: false
            },
            {
				label: "PHONE",
				field: "phone",
				numeric: false,
				html: false
            },
            {
				label: "CLASS",
				field: "levels.level_name",
				numeric: false,
				html: false
            },
            {
				label: "ARM",
				field: "arm.name",
				numeric: false,
				html: false
            },
            {
				label: "GENDER",
				field: "gender",
				numeric: false,
				html: false
			},

		],
              tableRows:[]


        };
    },

    methods: {
        getResults(page = 1) {
                        axios.get('api/level_history?page=' + page)
                            .then(response => {
                                this.history = response.data;
                            });
                },
        onChangeList ({ source, destination }) {
            this.source = source;
            this.destination = destination;


        },
         newModal(id){
                    this.form.oldlevel_id=id

                $('#addNew').modal('show');
            },
        setHistory(){
            this.form.history=this.destination
            this.form.current=this.source

        this.form.post('api/set_history')
        .then(res=>{
            toast.fire({
                        type: 'success',
                        title: 'History Created in successfully'
                        })
                   // this.destination=[];
            fire.$emit('AfterCreate')})

        },
        loadStudents(){
             axios.get('/api/getnew_students/'+this.$route.params.id)
     .then(res=>{
    this.source=res.data;
         let Level=this.source[0];
         this.loadArms(Level.class_id)
         this.form.level_id=Level.class_id

    })
        },

 loadStudentsByArm(){
                this.$Progress.start();
         this.form.get(`/api/promoted_arms/${this.form.level_id}/${this.form.arm_id}`)
          .then((data) => {
             this.tableRows= data.data.data

              console.log(data.data.data);
             this.$Progress.finish();
              this.isArm=true

          }).catch((err) => {
              this.$Progress.fail();
          });
           },


       AssignArms(){
           this.form.students=this.destination
           console.log([this.form.students, this.form.arm_id,this.form.level_id])
          this.form.post('/api/assign_arm')
         .then(res=>{
            toast.fire({
                        type: 'success',
                        title: 'Students Promoted successfully'
                        })

            fire.$emit('AfterCreate')})

        },

        loadArms(id){
       axios.get('/api/loadArms/'+id).then(res=>{
           this.arms=res.data
           console.log(this.arms)
       })},
    },
    created(){
       this.loadStudents();
       //this.loadHistory();
    Fire.$on('AfterCreate',() => {
        //this.loadHistory();
               this.loadStudents();
               this.loadStudentsByArm();

           });
    }
};
</script>



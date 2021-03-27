<<template>
<div >
<div class="content-header">

      <div class="container-fluid navy" >
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Performance Tracker</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Performance Tracker</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->

    </div>


<div class='content col-12 '>
  <div class="card card-navy card-outline">
  <div class="card-header">

      <h3 class="card-title">Performance Tracker for {{subject_name}}</h3>
      <div class="ribbon-wrapper">
    <div class="ribbon bg-primary">
      chart
      </div>
    </div>
  </div>
  <div class="card-body">
      
       <div class="form-group" >

                     <select
                    name="level_id"
                    id="level_id"
                    :class="{'is-invalid':form.errors.has('level_id')}"
                    class="form-control"
                    v-model="form.level_id"
                    @change="loadStudents()"
                  >
                    <option value selected>Class Level</option>
                    <option
                      v-for="level in levels"
                      :key="level.id"
                      :value="level.id"

                    >{{level.level_name}}</option>
                  </select>
                        <has-error :form="form" field="level_id"></has-error>
                 </div>
                 <!-- /.card-header -->
              <div v-show="isStudents" class="card-body table-responsive ">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th>Roll No</th>
                        <th>Name</th>
                        <th>Phone </th>
                        <th>Gender</th>
                        <th>Subject</th>
                  </tr>


                  <tr v-for="student in students" :key="student.id">

                    <td>{{student.id}}</td>
                    <td>{{student.surname}}, &nbsp;{{student.first_name}} </td>
                    <td>{{student.phone}}</td>
                    <td>{{student.gender}}</td>

                    <td>
                     <div class="form-group">

                     <select
                    name="subject_id"
                    :id="'subject'"
                    :class="{'is-invalid':form.errors.has('subject_id')}"
                    class="form-control"
                    v-model="form.subject_id"
                    @change="loadChart(student.id)"
                  >
                    <option value selected>select Subject</option>
                    <option
                      v-for="subject in subjects"
                      :key="subject.id"
                      :value="subject.subjects.id"

                    >{{subject.subjects.name}}</option>
                  </select>
                        <has-error :form="form" field="subject_id"></has-error>
                 </div>

                    </td>
                  </tr>
                </tbody></table>
              </div>
  <line-chart v-show="!isStudents" :data="chartData" :library="{backgroundColor: '#00ee'}"></line-chart>
  </div>
  <div>
<column-chart v-show="!isStudents" :data="chartData" ></column-chart></div>
  </div>
</div></div>


</template>

<script >

  export default {
    data(){
      return {
         chartData: [],
        levels:{},
        students:{},
        subjects:{},
        isStudents:false,
        subject_name:'',
        form:new Form({
         level_id:'',
         subject_id:'',
         student_id:''

        })
      }
    },
    mounted(){
     axios.get('/api/chart')
     .then((result) => {
      this.levels=result.data;
      console.log((this.levels));

     }).catch((err) => {
       console.log(err)
     });



    },
    methods:{
      loadStudents(){
        // console.log( this.form.level_id);
           axios.get(`api/load_students/${this.form.level_id}`)
           .then((result) => {
             this.students=result.data.students
             this.subjects=result.data.subjects
            // console.log( this.subjects);
          this.isStudents=true
           }).catch((err) => {

           });
      },
      loadChart(id){
       // console.log( `id selevted atr:${subject_id},${student_id} `);
        this.form.student_id=id;
        this.form.post(`api/load_chart`)
         .then((result) => {
          result.data.forEach(element => {
             this.chartData.push([element.term,element.score])
           });
     const  subject_name=    document.querySelector('#subject')
       this.subject_name = subject_name.options[subject_name.selectedIndex].text;
         console.log(this.subject_name)
         this.isStudents=false
         }).catch((err) => {

         });
      }

    }
  }
</script>

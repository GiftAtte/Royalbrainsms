<template>
 <div >
<div class="content-header">

      <div class="container-fluid navy" >
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Transactions </h1>
          </div><!-- /.col -->
          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Transactions</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>




        <div class="content" v-if="$gate.isAdminOrTutorOrStudent()">
          <div class="col-12">
            <div class="card card-navy card-outline">
              <div class="card-header">
                <div class="row float-right">


                <div class="card-tools">

               <button v-show="$gate.isAdmin()" class="btn btn-success btn-sm float-right m-2" @click="newModal">Add New Fee <i class="fas fa-user-plus fa-fw"></i></button>
                </div>
                 </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <table class="  table table-hover " id="data_tb">
                  <tbody>
                    <tr>
                        <th>R/ID</th>
                        <th>Fee Title</th>

                        <th ><div><span>Class/Level </span></div></th>
                          <th >Bank</th>
                        <th >Discount</th>

                         <th >Due Date</th>
                        <th>Action</th>
                  </tr>


                  <tr v-for="transacion in transacions" :key="transacion.id">
                     <td>{{transacion.id}}</td>
                     <td>{{transacion.id}}</td>
                     <td>{{transacion.id}}</td>
                     <td>{{transacion.id}}</td>
                  </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->

            </div>
            <!-- /.card -->
          </div>
        </div>


        </div>



</template>

<script>
    export default {

        data() {
            return {
               transactions:''



        }},
        mounted(){
           axios.get('/api/getTransactions/'+this.$route.params.accountNumber)
                .then(res=>{

                    this.transactions=res.data;})



        },


        methods: {



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

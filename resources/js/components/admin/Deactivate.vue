<template>
     <div >
<div class="content-header">

      <div class="container-fluid navy" >
        <div class="row mb-2">
          <div class="col-sm-6">
            <h2 class="m-0 text-dark">Deactivate Bulk</h2>
          </div><!-- /.col -->
          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Import List</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>


<div class="card card-navy card-outline">
<div class="card-header">
<div class="ribbon-wrapper">
    <div class="ribbon bg-primary">
      Deactivate Bulk
      </div>
    </div>
<h5 class="header text-danger text-uppercase">Select cvs file and click upload</h5>
</div>
<div class="card-body">
        <loading :active.sync="isLoading"
        :can-cancel="false"
        :on-cancel="onCancel"
        color="green"
        :is-full-page="fullPage"></loading>

<vue-csv-import
    v-model="csv"
    url="api/deactivate_results"
    :map-fields="['surname','students_id','report_id']"
      :callback="afterImport"
      :catch="errImport"

     >

    <template slot="hasHeaders" slot-scope="{headers, toggle}">

        <template slot="hasHeaders" slot-scope="{headers, toggle}">
        <label>
            <input type="checkbox" checked id="hasHeaders" :value="headers" @change="toggle">
            Headers?
        </label>
    </template>


    </template>

    <template slot="error">
     <span>   File type is invalid</span>
    </template>

    <template slot="thead">
        <tr>
            <th>Text Fields</th>
            <th>Select Columns To Match</th>
        </tr>
    </template>

    <template slot="next" slot-scope="{load}">
        <button class="btn btn-primary" @click.prevent="load">load student!</button>
    </template>

    <template slot="submit" slot-scope="{submit}">
        <button class="btn btn-success" @click.prevent="submit"><i class="fa fa-user-plus" @click="setLoading"> Submit</i></button>
    </template>
</vue-csv-import>
    </div>    </div>

    </div>

</template>





<script >
import {RotateSquare2} from 'vue-loading-spinner'
  import { VueCsvImport } from 'vue-csv-import';
  import Loading from 'vue-loading-overlay';

    export default{
        components: { VueCsvImport,RotateSquare2,Loading },
        data(){
          return {
                isLoading:false,
                fullPage: true
          }
        },
         methods:{
           afterImport(){

                   this.isLoading=false;
                    toast.fire({
                        type: 'success',
                        title: 'Deactivate  successfully'
                        })

                        this.$router.push('/load_activation')
           },
           errImport(){
                    this.isLoading=false;
                    toast.fire({
                        type: 'error',
                        title: 'There was a problem importing your file'
                        })
                           this.resetLoading();


           },
           setLoading(){
             this.isLoading=true
           },
           resetLoading(){
             this.isLoading=false
           }

        },
        created(){
            this.resetLoading();

        }
        }

</script>


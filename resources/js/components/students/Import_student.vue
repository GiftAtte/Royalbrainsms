<template>
     <div >
<div class="content-header">

      <div class="container-fluid navy" >
        <div class="row mb-2">
          <div class="col-sm-6">
            <h2 class="m-0 text-dark">Import Students</h2>
          </div><!-- /.col -->
          <div class="col-sm-6">

            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Import Students</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>


<div class="card card-navy card-outline">
<div class="card-header">
<div class="ribbon-wrapper">
    <div class="ribbon bg-primary">
      Import
      </div>
    </div>
<h5 class="header text-primary text-uppercase">Select cvs for import</h5>


</div>
<div class="card-body">
        <loading :active.sync="isLoading"
        :can-cancel="false"
        :on-cancel="onCancel"
        color="green"
        :is-full-page="fullPage">
        </loading>

<vue-csv-import
    v-model="csv"
    url="api/import_students"
    :map-fields="['id','surname','first_name','middle_name','dob', 'gender','phone','class_id','reg_date','roll_no','blood_group',
                  'arm_id','contact_address','nationality','state','lga','school_id']"
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
        <button class="btn btn-success" @click.prevent="load">load student!</button>
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
                        title: 'Student Created in successfully'
                        })

                        this.$router.push('/students')
           },
           errImport(){
                    this.isLoading=false;
                    toast.fire({
                        type: 'error',
                        title: 'There was a problem importing your file'
                        })
                           this.resetLoading();
                        this.$router.push('/import_students')

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


<template>
<div class="col-md-12 row">
    
  <div class="col-md-6">
    <table class="table table-bordered table-sm font-weight-bold  table-striped" >
<tr >
 <th colspan="2"  class="text-uppercase text-center text-danger text-bold">AFFECTIVE</th>
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
 <th colspan="2"  class="text-uppercase text-center text-danger text-bold">PSYCHOMOTOR</th>
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
</template>

<script>
export default {
 props:[
     'LDomain',

 ]
}
</script>


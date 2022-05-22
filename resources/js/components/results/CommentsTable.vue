<template>

<table class="table table table-bordered table-sm  text-bold" >

    <tr>
         <td><b class="float-left">OBSERVABLE DOMAIN </b><b class="float-right pr-5">RATINGS</b></td>

    </tr>

    
<table class="table table-bordered"  v-for="(score,index) in scores" :key="index">
  <tr><td style="width: 5%">***</td><td class="text-uppercase text-bold text-primary text-center" style="width: 65%" >{{score.domain}}</td><td>-----</td></tr>
  <tr v-for="(sub,index2) in score.subdomains" :key="index2">
 <td style="width: 5%">{{ index2+1}}</td><td  style="width: 70%" >{{ sub.subdomains.name }}</td><td>{{ sub.ratings.rate }}</td>
   </tr>

</table>


</table>

</template>

<script>
export default {
props:['scores'],
created(){
    console.log(this.scores)
}
}
</script>

<style>

</style>

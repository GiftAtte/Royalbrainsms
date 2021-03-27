<template>
    <div>
   <div v-show="isLoading"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
<span class="sr-only">Loading...</span></div>
    </div>
</template>

<script >
 

    export default {
      mounted(){
          axios.post('/logout')
          .then(() => {
              location.replace('/login')
          }).catch((err) => {
              
          });
      }  
    }
</script>


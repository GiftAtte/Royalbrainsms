<template>
  
<div>
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-navy bg-navy">
    <!-- Left navbar links -->
    
    
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link bg-navy" @click.prevent="toggleMenu" href="#"><i class="fas fa-bars"></i></a>
      </li></ul>

    

      
      
    <div class="input-group input-group-sm col-md-8">
      <input class="form-control form-control-navbar" @keyup="searchit" v-model="search" type="search" placeholder="Search" aria-label="Search">
      <div class="input-group-append">
        <button class="btn btn-navbar" @click="searchit">
          <i class="fa fa-search"></i>
        </button>
      </div>
    </div>
     
     
  <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <div class="image">
            <img src="/img/admin.jpg" height="30" width="30" class="img-circle elevation-2" alt="User Image">
          </div>
          
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <a class="dropdown-item text-danger text-bold" href="#"
            @click="logout">logout
            
         </a>

         
        </div>
      </li>
    </ul>
</nav>
    <!-- Right navbar links -->
    
  <!-- /.navbar -->

  </div>
</template>

<script >
 import { mapState } from "vuex";
   
export default {
  computed: {
    search(){
      return this.$search;
    },
    searchit(){
      return this.$searchit
    },


  },
  
   methods: {
     logout(){
     axios.post('/logout')
     .then((result) => {
      
        window.location.replace("/login");
     }).catch((err) => {
       
     });
     },
    toggleMenu() {
     Fire.$emit('menu/toggle');
    },

  },

};

</script>

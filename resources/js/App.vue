<template>

  <div id="wrapper" :class="wrapperClass">
  
 <Menu></Menu>

    

    <ContentOverlay></ContentOverlay>


   </div>
   
  

</template>

<script>
// @ is an alias to /src
// import MenuToggleBtn from './components/MenuToggleBtn.vue'
import Menu from './components/Menu.vue'
import ContentOverlay from './components/layout/ContentOverlay.vue'
import Header from './components/layout/Header.vue'
import Footer from './components/layout/Footer.vue'
export default {

  components: {
    // MenuToggleBtn,
    Menu,
    ContentOverlay,
    Header,
    Footer
  },

  created() {

    Fire.$on('menu/toggle', () => {
      window.setTimeout(() => {
        this.isOpenMobileMenu = !this.isOpenMobileMenu;
      }, 200);
    });

    Fire.$on('menu/closeMobileMenu', () => {
      this.isOpenMobileMenu = false;
    });

  },


  data() {
    return {
      isOpenMobileMenu: false,
    };
  },

  computed: {
    wrapperClass() {
      return {
        'toggled': this.isOpenMobileMenu === true,
      };
    },
  }

}

</script>

<style lang="scss" >
@import 'styles/layout.scss';
@import 'styles/menu-toggle-btn.scss';
@import 'styles/menu.scss';
@import 'styles/content-overlay.scss';
@import 'styles/media-queries.scss';
</style>

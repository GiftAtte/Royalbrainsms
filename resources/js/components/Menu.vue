<template>
  <div  class="menu-container font-weight-light sidebar-dark-primary elevation-4">
    <!-- root level itens -->
    <ul class="menu py-5">

      <li class=" brand-link">
        <a href="/dashboard" class="menu__logo">
        <img :src="`/img/schools/${school.logo}`" alt=" Logo" class="brand-image img-circle elevation-3"
           style="opacity: .98">
        </a>
        <span class="brand-text font-weight-light text-white">{{school.short_name}}</span>

      </li>

        <li>

         <div class="user-panel mt-3 text-center  d-flex">
          <div class="image">

          <a href="#" class="d-block text-white text-uppercase">
              {{name}}
              <p> ==={{type}}===</p>
            </a>
          </div>
         <div class="info ">


          </div>
        </div>

        </li>
      <li>
        <a
        href="/dashboard"
        @click.prevent="updateMenu('home')"
        :class="highlightSection('home')"
        >
            <i class="fa fa-home menu__icon" aria-hidden="true"></i>
            Home
        </a>
      </li>

      <li v-show="$gate.isAdmin()">
        <a
        href="#"
        @click.prevent="updateMenu('manage')"
        :class="highlightSection('manage')"
        >
          <i class="nav-icon fas fas fa-tachometer-alt red menu__icon" aria-hidden="true"></i>
          Manage
          <i class="fa fa-chevron-right menu__arrow-icon" aria-hidden="true"></i>
        </a>
      </li>

      <li v-show="$gate.isAdmin()">
        <a
        href="#"
        @click.prevent="updateMenu('reports')"
        :class="highlightSection('reports')"
        >
          <i class="fa fa-book green menu__icon" aria-hidden="true"></i>
          Reports
          <i class="fa fa-chevron-right menu__arrow-icon" aria-hidden="true"></i>
        </a>
      </li>

       <li v-show="$gate.isStudent()">
        <a
        href="#"
        @click.prevent="updateMenu('accademics')"
        :class="highlightSection('accademics')"
        >
          <i class="fa fa-graduation-cap navy menu__icon" aria-hidden="true"></i>
          My Domain
          <i class="fa fa-chevron-right menu__arrow-icon" aria-hidden="true"></i>
        </a>
      </li>

<!-- Parents Conner -->


<li v-show="$gate.isParent()">
        <a
        href="#"
        @click.prevent="updateMenu('parent')"
        :class="highlightSection('manage')"
        >
          <i class="nav-icon fa fa-user-friends red menu__icon" aria-hidden="true"></i>
          Parent Conner
          <i class="fa fa-chevron-right menu__arrow-icon" aria-hidden="true"></i>
        </a>
      </li>



         <!-- Form Tutor -->
<li v-show="$gate.isSubjectOrFormTutor()">
        <a
        href="#"
        @click.prevent="updateMenu('tutor')"
        :class="highlightSection('tutor')"
        >
          <i class="fas fa-chalkboard-teacher navy menu__icon" aria-hidden="true"></i>
          Form Tutor
          <i class="fa fa-chevron-right menu__arrow-icon" aria-hidden="true"></i>
        </a>
      </li>


        <li v-show="$gate.isAdminOrTutor()">
        <a
        href="#"
        @click.prevent="updateMenu('cbt')"
        :class="highlightSection('cbt')"
        >
          <i class="fas fa-book-open navy menu__icon yellow" aria-hidden="true"></i>
         CBT
          <i class="fa fa-chevron-right menu__arrow-icon" aria-hidden="true"></i>
        </a>
      </li>

   <li v-show="$gate.isAdminOrTutorOrStudentOrParent()">
        <a
        href="#"
        @click.prevent="updateMenu('chat')"
        :class="highlightSection('chat')"
        >
          <i class="fas fa-comments navy menu__icon green" aria-hidden="true"></i>
         Chats
          <i class="fa fa-chevron-right menu__arrow-icon" aria-hidden="true"></i>
        </a>
      </li>


<li v-show="$gate.isAdminOrTutorOrParent()">
        <a
        href="#"
        @click.prevent="updateMenu('fees')"
        :class="highlightSection('fees')"
        >
          <i class="fas fa-money-bill-alt navy menu__icon red" aria-hidden="true"></i>
         Fee Management
          <i class="fa fa-chevron-right menu__arrow-icon" aria-hidden="true"></i>
        </a>
      </li>


      <li v-show="$gate.isAdmin()">
        <a
        href="#"
        @click.prevent="updateMenu('settings')"
        :class="highlightSection('settings')"
        >
          <i class="fa fa-cog navy menu__icon indigo" aria-hidden="true"></i>
         Admin Settings
          <i class="fa fa-chevron-right menu__arrow-icon" aria-hidden="true"></i>
        </a>
      </li>

      <!-- super Admin -->
<li v-show="$gate.isSuperadmin()">
        <a
        href="#"
        @click.prevent="updateMenu('superadmin')"
        :class="highlightSection('superadmin')"
        >
          <i class="fa fa-cog navy menu__icon red" aria-hidden="true"></i>
        Superadmin
          <i class="fa fa-chevron-right menu__arrow-icon" aria-hidden="true"></i>
        </a>
      </li>


      <li v-if="this.$gate.isAdminOrTutorOrParent()">
        <a
        href="#"
        @click.prevent="updateMenu('account')"
        :class="highlightSection('account')"
        >
          <i class="fa fa-user menu__icon" aria-hidden="true"></i>
           My Account
          <i class="fa fa-chevron-right menu__arrow-icon" aria-hidden="true"></i>
        </a>
      </li>
      <li v-if="this.$gate.isStudent()">
        <a
        href="#"
        @click.prevent="updateMenu('student_account')"
        :class="highlightSection('student_account')"
        >
          <i class="fa fa-user menu__icon" aria-hidden="true"></i>
           My Account
          <i class="fa fa-chevron-right menu__arrow-icon" aria-hidden="true"></i>
        </a>
      </li>


    </ul>

    <!-- context menu: childs of root level itens -->
    <transition name="slide-fade">

      <div class="context-menu-container" v-show="showContextMenu">

        <ul class="context-menu">

          <li v-for="(item, index) in menuItens" :key="index">

            <h5 v-if="item.type === 'title'" class="context-menu__title">

              <i :class="item.icon" aria-hidden="true"></i>

              {{item.txt}}

              <a
              v-if="index === 0"
              @click.prevent="closeContextMenu"
              class="context-menu__btn-close"
              href="#"
              >
                <i class="fa fa-window-close" aria-hidden="true"></i>
              </a>

            </h5>

            <a
            v-else
            href="#"
            @click.prevent="openSection(item)"
            :class="subMenuClass(item.txt)"
            >
              {{item.txt}}
            </a>

          </li>

        </ul>

      </div>

    </transition>

  </div>

</template>

<script>
import menuData from './support/menu-data';
import kebabCase from 'lodash/kebabCase';
import {mapState} from "vuex";
export default {
  mounted(){
    this.$store.dispatch('loadSchool');
    this.$store.dispatch('getLevel');
  },
  name: 'Menu',
  data(){
    return {
      name:window.user.name,
       type:window.user.type,
       user_image:window.user.photo,
      contextSection: '',

      menuItens: [],

      menuData: menuData,

      activeSubMenu: ''
    }
  },

  methods: {

    openProjectLink() {
      alert('You could open the project frontend in another tab here, so the logged admin could see changes made to the project ;)');
    },

    updateMenu(context) {
      this.contextSection = context;
      this.menuItens = this.menuData[context];

      if (context === 'home') {
        this.$router.push('/');
        Fire.$emit('menu/closeMobileMenu');
      }
    },

    highlightSection(section) {
      return {
        'menu__link': true,
        'menu__link--active': section === this.contextSection,
      };
    },

    subMenuClass(subMenuName) {
      return {
        'context-menu__link': true,
        'context-menu__link--active': this.activeSubMenu === subMenuName,
      };
    },

    closeContextMenu() {
      this.contextSection = '';
      this.menuItens = [];
    },

    openSection(item) {
      this.activeSubMenu = item.txt;

      this.$router.push(this.getUrl(item));
      Fire.$emit('menu/closeMobileMenu');
    },

    getUrl(item) {
      let sectionSlug = kebabCase(item.txt);

      return `${item.link}`;
    }

  },

  computed: {
    school(){
      return this.$store.state.school;
    },
    showContextMenu() {

      return this.menuItens.length;
    },
  }

}
</script>

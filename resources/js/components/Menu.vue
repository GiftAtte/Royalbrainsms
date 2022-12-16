<template>
  <div
    class="menu-container font-weight-light sidebar-dark bg-navy elevation-4"
  >
    <ul class="menu py-5" style="background-color: navy">
      <li class="brand-link">
        <a href="/dashboard" class="menu__logo">
          <img
            :src="`/img/schools/${school.logo}`"
            alt=" Logo"
            class="brand-image img-circle elevation-3"
            style="opacity: 0.98"
          />
        </a>
        <span class="brand-text font-weight-light">{{
          school.short_name
        }}</span>
      </li>

      <li>
        <div
          class="user-panel mt-2 text-center d-flex"
          style="background-color: navy"
        >
          <div class="image">
            <a href="#" class="pt-2 d-block text-white text-uppercase">
              <i class="fa fa-globe mx-2 red"></i>
              {{ "SCHOOL PORTAL" }}
              <p>=======================</p>
            </a>
          </div>
          <div class="info"></div>
        </div>
      </li>
      <li>
        <a
          href="/dashboard"
          @click.prevent="updateMenu('home')"
          :class="highlightSection('home')"
        >
          <i class="nav-icon fa fa-home menu__icon" aria-hidden="true"></i>
          DASHBOARD
        </a>
      </li>

      <li v-show="$gate.isAdmin()">
        <a
          href="#"
          @click.prevent="updateMenu('manage')"
          :class="highlightSection('manage')"
        >
          <i
            title="ACADEMICS"
            class="nav-icon fas fas fa-tachometer-alt menu__icon"
            aria-hidden="true"
          ></i>
          Academics
          <i
            class="fa fa-chevron-right menu__arrow-icon"
            aria-hidden="true"
          ></i>
        </a>
      </li>

      <li v-show="$gate.isAdmin()">
        <a
          href="#"
          @click.prevent="updateMenu('reports')"
          :class="highlightSection('reports')"
        >
          <i
            class="fa fa-book menu__icon"
            aria-hidden="true"
            title="EXAMS AND RECORDS"
          ></i>
          Exams/Records
          <i
            class="fa fa-chevron-right menu__arrow-icon"
            aria-hidden="true"
          ></i>
        </a>
      </li>

      <li v-show="$gate.isStudent()">
        <a
          href="#"
          @click.prevent="updateMenu('accademics')"
          :class="highlightSection('accademics')"
        >
          <i class="fa fa-graduation-cap menu__icon" aria-hidden="true"></i>
          My Domain
          <i
            class="fa fa-chevron-right menu__arrow-icon"
            aria-hidden="true"
          ></i>
        </a>
      </li>

      <!-- Parents Conner -->

      <li v-show="$gate.isParent()">
        <a
          href="#"
          @click.prevent="updateMenu('parent')"
          :class="highlightSection('manage')"
        >
          <i
            title="PARENTS CONNER"
            class="nav-icon fa fa-user-friends menu__icon"
            aria-hidden="true"
          ></i>
          Parent Conner
          <i
            class="fa fa-chevron-right menu__arrow-icon"
            aria-hidden="true"
          ></i>
        </a>
      </li>

      <!-- Form Tutor -->




      <li v-show="$gate.isSubjectOrFormTutor()">
        <a
          href="#"
          @click.prevent="updateMenu('tutorGenerals')"
          :class="highlightSection('tutor')"
        >
          <i
            class="fas fa-graduation-cap menu__icon"
            aria-hidden="true"
          ></i>
          Accademics
          <i
            class="fa fa-chevron-right menu__arrow-icon"
            aria-hidden="true"
          ></i>
        </a>
      </li>





      <li v-show="$gate.isSubjectOrFormTutor()">
        <a
          href="#"
          @click.prevent="updateMenu('tutor')"
          :class="highlightSection('tutor')"
        >
          <i
            class="fas fa-chalkboard-teacher menu__icon"
            aria-hidden="true"
          ></i>
          Exams And Records
          <i
            class="fa fa-chevron-right menu__arrow-icon"
            aria-hidden="true"
          ></i>
        </a>
      </li>

      <li v-show="$gate.isAdminOrTutor()">
        <a
          href="#"
          @click.prevent="updateMenu('cbt')"
          :class="highlightSection('cbt')"
        >
          <i
            title="CBT"
            class="fas fa-book-open menu__icon"
            aria-hidden="true"
          ></i>
          CBT
          <i
            class="fa fa-chevron-right menu__arrow-icon"
            aria-hidden="true"
          ></i>
        </a>
      </li>

      <li v-show="$gate.isAdminOrTutorOrStudentOrParent()">
        <a
          href="#"
          @click.prevent="updateMenu('chat')"
          :class="highlightSection('chat')"
        >
          <i
            title="COMMUNICATION"
            class="fas fa-comments menu__icon"
            aria-hidden="true"
          ></i>
          Communication
          <i
            class="fa fa-chevron-right menu__arrow-icon"
            aria-hidden="true"
          ></i>
        </a>
      </li>

      <li v-show="$gate.isAdmin()">
        <a
          href="#"
          @click.prevent="updateMenu('fees')"
          :class="highlightSection('fees')"
        >
          <i
            title="FEE MANAGEMENT"
            class="fas fa-money-bill-alt menu__icon"
            aria-hidden="true"
          ></i>
          Fee Management
          <i
            class="fa fa-chevron-right menu__arrow-icon"
            aria-hidden="true"
          ></i>
        </a>
      </li>

      <li v-show="$gate.isAdmin()">
        <a
          href="#"
          @click.prevent="updateMenu('Inventory')"
          :class="highlightSection('Inventory')"
        >
          <i
            title="INVENTORY"
            class="fas fa-money-bill-alt navy menu__icon"
            aria-hidden="true"
          ></i>
          Inventory
          <i
            class="fa fa-chevron-right menu__arrow-icon"
            aria-hidden="true"
          ></i>
        </a>
      </li>

      <li v-show="$gate.isAdminOrCandidate()">
        <a
          href="#"
          @click.prevent="updateMenu('Admission')"
          :class="highlightSection('Admission')"
        >
          <i
            title="ADMISSION"
            class="fa fa-user-plus navy menu__icon"
            aria-hidden="true"
          ></i>
          Admission
          <i
            class="fa fa-chevron-right menu__arrow-icon"
            aria-hidden="true"
          ></i>
        </a>
      </li>

      <li v-show="$gate.isAdmin()">
        <a
          href="#"
          @click.prevent="updateMenu('Downloads')"
          :class="highlightSection('Downloads')"
        >
          <i
            title="DOWNLOAD CENTER"
            class="fa fa-history navy menu__icon"
            aria-hidden="true"
          ></i>
          Downloads & History
          <i
            class="fa fa-chevron-right menu__arrow-icon"
            aria-hidden="true"
          ></i>
        </a>
      </li>

      <li v-show="$gate.isAdmin()">
        <a
          href="#"
          @click.prevent="updateMenu('settings')"
          :class="highlightSection('settings')"
        >
          <i
            title="ADMIN SETTINGS"
            class="fa fa-cog navy menu__icon"
            aria-hidden="true"
          ></i>
          Admin Settings
          <i
            class="fa fa-chevron-right menu__arrow-icon"
            aria-hidden="true"
          ></i>
        </a>
      </li>

      <!-- super Admin -->
      <li v-show="$gate.isSuperadmin()">
        <a
          href="#"
          @click.prevent="updateMenu('superadmin')"
          :class="highlightSection('superadmin')"
        >
          <i class="fa fa-cog navy menu__icon" aria-hidden="true"></i>
          Superadmin
          <i
            class="fa fa-chevron-right menu__arrow-icon"
            aria-hidden="true"
          ></i>
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
          <i
            class="fa fa-chevron-right menu__arrow-icon"
            aria-hidden="true"
          ></i>
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
          <i
            class="fa fa-chevron-right menu__arrow-icon"
            aria-hidden="true"
          ></i>
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

              {{ item.txt }}

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
              {{ item.txt }}
            </a>
          </li>
        </ul>
      </div>
    </transition>
  </div>
</template>

<script>
import menuData from "./support/menu-data";
import kebabCase from "lodash/kebabCase";
import { mapState } from "vuex";
export default {
  mounted() {
    this.$store.dispatch("loadSchool");
    this.$store.dispatch("getLevel");
  },
  name: "Menu",
  data() {
    return {
      name: window.user.name,
      type: window.user.type,
      user_image: window.user.photo,
      contextSection: "",

      menuItens: [],

      menuData: menuData,

      activeSubMenu: "",
    };
  },

  methods: {
    openProjectLink() {
      alert(
        "You could open the project frontend in another tab here, so the logged admin could see changes made to the project ;)"
      );
    },

    updateMenu(context) {
      this.contextSection = context;
      this.menuItens = this.menuData[context];

      if (context === "home") {
        this.$router.push("/");
        Fire.$emit("menu/closeMobileMenu");
      }
    },

    highlightSection(section) {
      return {
        menu__link: true,
        "menu__link--active": section === this.contextSection,
      };
    },

    subMenuClass(subMenuName) {
      return {
        "context-menu__link": true,
        "context-menu__link--active": this.activeSubMenu === subMenuName,
      };
    },

    closeContextMenu() {
      this.contextSection = "";
      this.menuItens = [];
    },

    openSection(item) {
      this.activeSubMenu = item.txt;

      this.$router.push(this.getUrl(item));
      Fire.$emit("menu/closeMobileMenu");
    },

    getUrl(item) {
      let sectionSlug = kebabCase(item.txt);

      return `${item.link}`;
    },
  },

  computed: {
    school() {
      return this.$store.state.school;
    },
    showContextMenu() {
      return this.menuItens.length;
    },
  },
};
</script>

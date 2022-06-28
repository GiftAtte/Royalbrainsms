import Vue from "vue";
import Vuex from "vuex";
//import VueCookies from "vue-cookies";
//Vue.use(VueCookies);
import createPersistedState from "vuex-persistedstate";

import Sales from "./sales";

Vue.use(Vuex);
export const store = new Vuex.Store({
    state: {
        school: [],
        student_login: [],
        parent_login: [],
        staff_login: [],
        level: [],
    },
    getters: {},
    mutations: {
        SAVE_SCHOOL(state, school) {
            state.school = school;
        },
        SAVE_LEVEL(state, level) {
            state.level = level;
        },
        SAVE_STUDENT_LOGIN(state, student_login) {
            state.student_login = student_login;
        },
        SAVE_STAFF_LOGIN(state, staff_login) {
            state.staff_login = staff_login;
        },
        SAVE_PARENT_LOGIN(state, parent_login) {
            state.parent_login = parent_login;
        },
    },
    actions: {
        loadSchool({ commit }) {
            axios
                .get("/api/school/" + window.user.school_id)
                .then((result) => {
                    //  console.log(window.user.school_id)
                    commit("SAVE_SCHOOL", result.data);
                })
                .catch((error) => {
                    throw new Error(`API ${error}`);
                });
        },
        getLevel({ commit }) {
            axios.get("api/get_chat_level").then((res) => {
                commit("SAVE_LEVEL", res.data).catch((error) => {
                    throw new Error(`API ${error}`);
                });
            });
        },
        loginDetails({ commit }) {
            axios
                .get("/api/login_export")
                .then((result) => {
                    commit("SAVE_STUDENT_LOGIN", result.data.student_login);
                    commit("SAVE_STAFF_LOGIN", result.data.staff_login);
                    commit("SAVE_PARENT_LOGIN", result.data.parent_login);
                    // console.log(this.state.num_staff)
                })
                .catch((error) => {
                    throw new Error(`API ${error}`);
                });
        },
    },
    //plugins: [createPersistedState()],
    modules: {
        Sales,
    },
    // plugins: [createPersistedState()],
});

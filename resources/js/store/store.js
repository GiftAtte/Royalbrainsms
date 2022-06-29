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
    getters: {
        getSchool(state) {
            return state.school;
        },
        getStudentLogin(state) {
            return state.student_login;
        },
        getParentLogn(state) {
            return state.parent_login;
        },
        getStaffLogin(state) {
            return state.school;
        },
        getLevel(state) {
            return state.level;
        },
    },
    mutations: {
        SAVE_SCHOOL(state, payload) {
            state.school = payload;
        },
        SAVE_LEVEL(state, level) {
            state.level = level;
        },
        SAVE_STUDENT_LOGIN(state, payload) {
            state.student_login = payload;
        },
        SAVE_STAFF_LOGIN(state, payload) {
            state.staff_login = payload;
        },
        SAVE_PARENT_LOGIN(state, payload) {
            state.parent_login = payload;
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
    plugins: [createPersistedState()],
    modules: {
        Sales,
    },
    // plugins: [createPersistedState()],
});

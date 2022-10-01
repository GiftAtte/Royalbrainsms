export const AdmissionRoutes = [
    {
        path: "/admission/profile/:id?",
        component: require("../components/admission/biodata/BioData.vue")
            .default,
    },
    {
        path: "/admission/levels",
        component: require("../components/admission/Level.vue").default,
    },
    {
        path: "/birthdays",
        component: require("../components/students/BirthdayList.vue").default,
    },
    {
        path: "/daily/attendance",
        component: require("../components/students/Attendance.vue").default,
    },
];

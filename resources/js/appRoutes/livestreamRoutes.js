export const LivestreamRoutes = [
    {
        path: "/liveClasses",
        component: require("../components/livestream/LiveClasses.vue").default,
    },
    {
        path: "/meeting/:id",
        component: require("../components/livestream/StudentClass.vue").default,
    },

    {
        path: "/news",
        component: require("../components/admin/News.vue").default,
    },
];

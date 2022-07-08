export const InventoryRoutes = [
    {
        path: "/inventory/category",
        component: require("../components/inventory/Category.vue").default,
    },
    {
        path: "/inventory/items",
        component: require("../components/inventory/Items.vue").default,
    },
    {
        path: "/inventory/suppliers",
        component: require("../components/inventory/Supplier.vue").default,
    },
    {
        path: "/inventory/products",
        component: require("../components/inventory/Product.vue").default,
    },
    {
        path: "/inventory/purchases",
        component: require("../components/inventory/Purchases.vue").default,
    },
    {
        path: "/inventory/prices",
        component: require("../components/inventory/PriceList.vue").default,
    },
    {
        path: "/inventory/stock",
        component: require("../components/inventory/Stock.vue").default,
    },
    {
        path: "/inventory/cart",
        component: require("../components/inventory/Cart.vue").default,
    },
    {
        path: "/inventory/sales",
        component: require("../components/inventory/Sales.vue").default,
    },
    {
        path: "/inventory/sales/details/:salesId",
        component: require("../components/inventory/SalesDetails.vue").default,
    },
    {
        path: "/petty/cash",
        component: require("../components/inventory/PettyCash.vue").default,
    },
    {
        path: "/petty/cash/groups",
        component: require("../components/inventory/PettyCashGroup.vue")
            .default,
    },
    {
        path: "/petty/cash/expenses",
        component: require("../components/inventory/Expense.vue").default,
    },
    {
        path: "/inventory/items/issue",
        component: require("../components/inventory/IssueItems.vue").default,
    },
    {
        path: "/inventory/items/addItems",
        component: require("../components/inventory/AddItems.vue").default,
    },
];
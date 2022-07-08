const Sales = {
    state: {
        employees: [],
        category: [],
        items: [],
        productCount: 1,
        product: {},
        products: localStorage.getItem("products")
            ? JSON.parse(localStorage.getItem("products"))
            : [],
    },

    getters: {
        getCount(state) {
            return state.productCount;
        },
        getProducts(state) {
            return state.products;
        },
        getEmployees(state) {
            return state.employees;
        },
        getItems(state) {
            return state.items;
        },
        getCategory(state) {
            return state.category;
        },
    },
    mutations: {
        addToCart(state, payload) {
            let products = [];
            if (
                localStorage.getItem("products") &&
                JSON.parse(localStorage.getItem("products").length)
            ) {
                products = JSON.parse(localStorage.getItem("products"));
                let product = products.find((item) => item.id === payload.id);
                if (product) {
                    product.amount =
                        parseFloat(product.amount) + parseFloat(payload.amount);
                    product.quantity =
                        parseFloat(product.quantity) +
                        parseFloat(payload.quantity);
                    products = products.filter((item) => item.id != payload.id);
                    products.push(product);
                    localStorage.removeItem("products");
                    localStorage.setItem("products", JSON.stringify(products));
                } else {
                    products.push(payload);
                    localStorage.removeItem("products");
                    localStorage.setItem("products", JSON.stringify(products));
                }

                //products.push(payload);
            } else {
                products = [payload];
                localStorage.setItem("products", JSON.stringify(products));
            }

            state.products = JSON.parse(localStorage.getItem("products"));
        },

        removeFromCart(state, payload) {
            console.log(payload);
            let products = JSON.parse(localStorage.getItem("products"));
            let filteredProduct = products.filter(
                (product) => product.id != payload.id
            );

            localStorage.removeItem("products");
            if (filteredProduct.length) {
                localStorage.setItem(
                    "products",
                    JSON.stringify(filteredProduct)
                );
                state.products = JSON.parse(localStorage.getItem("products"));
            } else {
                state.products = [];
            }
        },
        removeAllFromCart(state) {
            localStorage.removeItem("products");
            state.products = [];
        },
        setEmployees(state, payload) {
            state.employees = payload;
        },
        setItems(state, payload) {
            state.items = payload;
        },
        setCategory(state, payload) {
            state.category = payload;
        },
    },
    actions: {
        loadEmployees({ commit }) {
            axios.get("/api/employees").then((res) => {
                commit("setEmployees", res.data);
            });
        },

        loadItems({ commit }) {
            axios.get("/api/inventory/items").then((res) => {
                commit("setItems", res.data);
                //   console.log(res, res.data);
            });
            // .catch((err) => console.log(err));
        },
        loadCategory({ commit }) {
            axios.get("/api/inventory/category").then((res) => {
                commit("setCategory", res.data);
                console.log(res.data);
            });
        },
    },
};

export default Sales;

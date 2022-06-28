const Sales = {
    state: {
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
    },
    actions: {},
};

export default Sales;

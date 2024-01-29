<template>
    <app-body pageTitle="Stocks" pageSubTitle="Stock list">
        <app-table
            :headers="tbHeaders"
            :data="tbData"
            :deleteAction="deleteStock"
            :updateAction="createStock"
            :createAction="createStock"
            createButton="true"
            :form="form"
            search="true"
            title="Stock list"
            updateTitle="Update Stocks"
        >
            <template #modal-fields>
         <div class="form-group mt-3 mb-2">
            <label for="">Product</label>
            <multiselect 
         v-model="product" 
         :options="products"
         :searchable="true" 
         :close-on-select="true"
         :show-labels="true" 
         @select="addUnitPrice"
         label="name"
         track-by="id"
         placeholder="Pick a value"/>
        </div>
               <input-field
                    label="Supplier OR Manufacturer"
                    v-model="form.manufacturer"
                    id="manufacturer"
                    :form="form"
                    field="manufacturer"
                    placeholder="Enter manufacturer"
                />
                   
                    <div class="row" v-for="(item,index) in form.unit_price" :key="item.unit">
                 
                   <div class="col">
                    <div class="form-group">
                    <label for="quantity">{{ item.unit }}Qty </label>
                    <input class="form-control" type="number"
                    @input="updateQty($event,index)"
                    :placeholder="'Qty of '+item.unit"
                   />
                    </div>
                   </div>
                   <div class="col">
                    <div class="form-group">
                    <label for="quantity">Price (&#8358;)</label>
                    <input class="form-control" type="number"
                    @input="updatePrice($event,index)"
                    :placeholder="'Price Per '+item.unit"
                    :value="item.price"
                   />
                    </div>
                   </div>
                    </div>
            
            </template>
        </app-table>
    </app-body>
</template>

<script>
export default {
    data() {
        return {
            editmode: true,
            componentKey: 0,
            updateTitle: "Product Category",
            tbHeaders: [
                { header: "Products", key: "product" },
                { header: "Category", key: "category" },
                { header: "Quantity", key: "quantity" },
                { header: "Cost P/Unit(NGN)", key: "unit_cost" },
                { header: "Total Cost(NGN)", key: "total_cost" },
                { header: "Stockd Date", key: "Stockd_date" },
            ],
            tbData: [],
            options: [],
            supplier: [],
            product:[],
            products:[],
            form: new Form({
                id: "",
                product_id: "",
                quantity: 1.0,
                manufacturer: '',
                unit_price:[],
                quantity: 0,
                barcode: null,
            }),
        };
    },
    methods: {
        loadProducts() {
            axios
                .get("/api/products")
                .then((res) => {
                    this.products = res.data.data.products
                })
                .catch((err) => console.log(err));
               },


        addUnitPrice(){
            
     let unitPrice=this.product.unit_price
     this.form.product_id=this.product.id
      console.log('unit price',unitPrice)
      this.form.unit_price=[]
       unitPrice.forEach(up=>{
      this.form.unit_price.push({
        id:up.id,
        price:up.price,
        skuQtyPerUnit:up.skuQtyPerUnit,
        quantity:0,
        unit:up.unit
      })
    })
},


         updatePrice(event,index){
        this.form.unit_price.at(index).price=event.target.value
        },
        updateQty(event,index){
      this.form.unit_price.at(index).quantity=event.target.value

        }  ,   
 
        loadStock() {
            axios
                .get("/api/stocks")
                .then((res) => {
                    this.tbData = res.data;
                })
                .catch((err) => console.log(err));
        },

        createStock() {
            this.form.quantity=this.form.unit_price.map(up=>{
                return{
                    unit:up.unit,
                    value:up.quantity,
                    skuQtyPerUnit:up.skuQtyPerUnit
                }
             })
            this.form.post("/api/stocks").then((res) => {
                $("#appModal").modal("hide");
                swal.fire(
                    "success!",
                    " A Product created successfully.",
                    "success"
                );

                Fire.$emit("afterCreated");
            });
        },

        deleteStock(id) {
            axios
                .delete("/api/Stocks/" + id)
                .then((res) => Fire.$emit("afterCreated"));
        },

    },
    mounted() {
        this.loadStock();
        this.loadProducts();
    },
    created() {
        Fire.$on("afterCreated", () => {
            this.loadStock();
        });
    },
};
</script>

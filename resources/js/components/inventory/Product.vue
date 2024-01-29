<template>
    <app-body pageTitle="Products" pageSubTitle="Product list">
        <app-table
            :headers="tbHeaders"
            :data="tbData"
            :deleteAction="deletProducts"
            :updateAction="updateProducts"
            :createAction="createProducts"
            :action="false"
            createButton="true"
            :form="form"
            search="true"
            title="Product list"
            updateTitle="Update Product"
            cart="true"
            modalSize="modal-lg"
        >
        <template #extra-action>
            <th>SKU Quantity Per Unit</th>
          <th>Modify</th>
        </template>
        <template  v-slot:extra-action-body="{row}">
    <td>
        <span class="unit-price" v-for="value of row.unit_price" :key="value.id">
               <span>{{ value.unit }}</span>
               <span>-</span>
               <span>{{ value.skuQtyPerUnit }}</span>
        </span>
    </td>

                           <td >
                                <a href="#" @click="editModal(row)">
                                    <i class="fa fa-edit blue"></i>
                                </a>
                                &nbsp;|&nbsp;
                                <a href="#" @click="deletProducts(row.id)">
                                    <i class="fa fa-trash red"></i>
                                </a>
                            </td>
        </template>



            <template #modal-fields>
               <div class="pl-5 container row">
               <div class="col-md-6 col-xs-12">
                <input-field
                    label="Product Name"
                    v-model="form.name"
                    id="name"
                    :form="form"
                    field="name"
                    placeholder="Enter category name"
                />
                <input-field
                    label="Product Code"
                    v-model="form.product_code"
                    id="product_code"
                    :form="form"
                    field="product_code"
                    placeholder="eg bk-01"
                />
                <input-field
                    label="Description"
                    v-model="form.barcode"
                    id="description"
                    :form="form"
                    field="description"
                    placeholder="Enter barcode"
                />

  
        </div>

        <div class="col-md-6 col-xs-12">
                <select-box
                    label="Category"
                    :form="form"
                    v-model="form.category_id"
                    placeholder="Select category Type"
                    field="category_id"
                    :options="options"
                    id="category_id"
                    name="category_id"
                    optionLabel="name"
                    optionValue="id"
                />
               
        <div class="form-group mt-3 mb-2">
            <label for="">Product Units</label>
            <multiselect 
         v-model="units" 
         :options="product_units"
         :searchable="true" 
         multiple
         :close-on-select="true"
         :show-labels="true" 
         @remove="handleChange"
         @select="handleChange"
         label="name"
         track-by="id"
          placeholder="Pick a value"/>
        </div>

                <select-box
                    label="SKU(Smallest unit)"
                    :form="form"
                    v-model="form.sku"
                    placeholder="Select smallest unit"
                    field="sku"
                    :options="form.units"
                    id="sku"
                    name="category_id"
                    optionLabel="name"
                    optionValue="name"
                    :change="handleChange"
                />
        </div>
      </div>
      <div class="px-5">
      <div class="form-group my-2" v-for="(item,index) in form.unit_price" :key="item.unit">
        <label for="">{{ item.unit }}<span class="text-danger">&nbsp; ({{ 'Qty of '+form.sku+' in '+item.unit }})</span></label>
                  <input class="form-control" type="number"
                    @input="updateUnitPrice($event,index)"
                    :value="item.skuQtyPerUnit"
                    :disabled="item.unit===form.sku"
                    :placeholder="'Qty of '+form.sku+' in '+item.unit"
                />
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
                { header: "Products", key: "name" },
                { header: "category", key: "category" },
            ],
            tbData: [],
            options: [],
            productUnits:[],
            units: [],
            product_units:[],
            formData:{},
           

            form: new Form({
                id: "",
                name: "",
                barcode: "",
                product_code: "",
                category_id: "",
                school_id: "",
                unit_price:[],
                units:[],
                sku:''

            }),
        };
    },
    methods: {

        editModal(data) {
            this.form.fill(data);
            this.form.units=this.units=data.unit_price.map(up=>{
                return{
                    name:up.unit,
                    id:up.id,
                    price:up.price
                }
             })
             this.form.sku=data.sku
             this.editmode = true;
            $("#appModal").modal("show");
            
        },
        newModal() {
            $("#appModal").modal("show");
            this.editmode = false;
            this.form.reset();
        },


        loadProductUnit() {
            axios
                .get("/api/product-units")
                .then((res) => {
                    this.product_units = res.data.data.product_units;
                
                })
                .catch((err) => console.log(err));
        },
        loadCategories() {
            axios
                .get("/api/categories")
                .then((res) => {
                    this.options = res.data.data.categories;
                    // setTimeout(() => {
                    //      this.componentKey += 1;
                    // },200)
                })
                .catch((err) => console.log(err));
        },
        loadProducts() {
            axios
                .get("/api/products")
                .then((res) => {
                    this.tbData = res.data.data.products.map(p=>{
                        p['category']=p.category.name

                        return p
                    });
                    // setTimeout(() => {
                    //      this.componentKey += 1;
                    // },200)
                })
                .catch((err) => console.log(err));
        },
        createProducts() {
            console.log(this.form)
            this.form.post("/api/products").then((res) => {
                $("#appModal").modal("hide");
                swal.fire(
                    "success!",
                    " A Product created successfully.",
                    "success"
                );

                Fire.$emit("afterCreated");
            });
        },
        deletProducts(id) {
           

                swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                // Send request to the server
                if (result.value) {
                    axios
                .delete("/api/products/" + id)
                .then((res) => Fire.$emit("afterCreated"));
                    swal.fire("Deleted!", "Item has been deleted.", "success");
                }
            });
        },
        updateProducts() {
            this.form.put("/api/pos/products/"+this.form.id).then((res) => {
                $("#appModal").modal("hide");
                swal.fire(
                    "success!",
                    "Products created successfully.",
                    "success"
                );

                Fire.$emit("afterCreated");
            });
        },

        handleChange() {

           this.form.units= this.units.map(u=>this.product_units.find(pu=>pu.id===u.id))
            this.addUnitPrice();

        },
        addUnitPrice(){
            let sku=this.form.sku;
            this.form.unit_price=[];
            if(sku){
                this.form.units.forEach((element,index) => {
                this.form.unit_price.push({
                unit:element.name,
                skuQtyPerUnit:element.name===sku?1:0,
                  index,
                  id:element.id,
                  price:element.price?element.price:0
            })
            });
            }
        },
        updateUnitPrice(event,index){
this.form.unit_price.at(index).skuQtyPerUnit=event.target.value
console.log(this.form.unit_price)
        }
    },
    mounted() {
        this.loadCategories();
        this.loadProducts();
        this.loadProductUnit()
    },
    created() {
        Fire.$on("afterCreated", () => {
            this.loadProducts();
        });
    },
};
</script>
<style scoped>
.unit-price{
    display: flex;
    justify-content: space-between;
    border-radius: 25px;
    font-size: 0.8rem;
    padding: 0px 5px;
    border: rgb(209, 209, 221) solid 0.5px;
    margin: 2px;
    align-items: center;
}
</style>
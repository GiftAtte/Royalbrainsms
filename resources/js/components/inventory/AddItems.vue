<template>
    <app-body pageTitle="School Items" pageSubTitle="Purchase list">
        <app-table
            :headers="tbHeaders"
            :data="tbData.data"
            :deleteAction="deleteStockItems"
            :updateAction="updateStockItems"
            :createAction="createStockItems"
            createButton="true"
            :form="form"
            action="false"
            search="true"
            title="Items Added"
            updateTitle="Update Purchases"
            
        >
        <template #extra-action>
          <th>Added On</th>
        </template>
        <template  v-slot:extra-action-body="{row}">
            <td>{{ row.created_at | myDate }}</td>
        </template>

            <template #card-footer>
                <pagination
                    :data="tbData"
                    @pagination-change-page="paginateData"
                />
            </template>
            <template #modal-fields>
                <select-box
                    label="Item List"
                    :form="form"
                    v-model="form.item_id"
                    placeholder="Select Item"
                    field="item_id"
                    :options="items"
                    id="item_id"
                    name="item_id"
                    optionLabel="name"
                    optionValue="id"
                    feild="item_id"
                />
                <input-field
                    label="Quantity"
                    v-model="form.quantity"
                    id="quantity"
                    :form="form"
                    field="quantity"
                    type="number"
                    placeholder="Eg 5"
                    min="1"
                    step="any"
                />

    <div class="form-wrapper">
        <multiselect 
        v-model="value" :options="options2"
         :searchable="true" 
         multiple
         :close-on-select="false"
          :show-labels="false" 
          placeholder="Pick a value"/>
          
    <FormulateForm
      v-model="formData"
    >
      <FormulateInput
        type="group"
        name="attendees"
        :repeatable="true"
        label="Who is going to attend?"
        add-label="+ Add Attendee"
        validation="required"
        remove-position="after"
      >
        <div class="attendee">
          <FormulateInput
            name="name"
            validation="required"
            label="Attendee’s name"
            input-class="form-control"
          />
          <strong class="price" v-text="`$100`" />
        </div>
      </FormulateInput>
      <FormulateInput
  :options="{first: 'First', second: 'Second', third: 'Third', fourth: 'Fourth'}"
  type="select"
  placeholder="Select an option"
  label="Which of your children is your favorite?"
   multiple="multiple"
 
/>
      <strong>Total: {{ total }}</strong>
      <FormulateInput
        type="submit"
        label="Purchase tickets"
        input-class="btn btn-primary"
      />
  
    </FormulateForm>
    <code class="code code--block">{{ formData }}</code>
  </div>



            </template>


        </app-table>
    </app-body>
</template>

<script>
import {mapGetters,mapActions} from 'vuex'
import Templates from '../results/Templates.vue';

export default {
  components: { Templates},
    computed: {
        items() {
            return this.getItems();
        },
        total () {
      const count = Array.isArray(this.formData.attendees) ? this.formData.attendees.length : 1
      return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD'}).format(count * 100)
    }
    },
    data() {
        return {
            formData: {
                attendees:[
                    {name:'Gift'},
                    {name:'Eno'},
                ]
            },
            value: '',
      options2: ['Select option', 'options', 'selected', 'multiple', 'label', 'searchable', 'clearOnSelect', 'hideSelected', 'maxHeight', 'allowEmpty', 'showLabels', 'onChange', 'touched'],
            editmode: true,
            componentKey: 0,
            updateTitle: "Product Category",
            url:'inventory/stockItems',
            tbHeaders: [
                { header: "Item", key: "item" },
                { header: "Category", key: "category" },
                { header: "Quantity", key: "quantity" },
                { header: "Added By", key: "added_by" },
                      ],
            options: [],
            tbData: [],
            form: new Form({
                id: "",
                 item_id: "",
                quantity: 1.0,
                school_id: "",
            employee_id:''
            }),
        };
    },
    methods: {
        ...mapGetters(['getItems','getData']),
        ...mapActions(['loadItems']),
        paginateData(page=1) {
            axios.get('/api/inventory/stockItems?page=' + page)
           .then(res=>this.tbData=res.data)
          },


        createStockItems() {
            this.form.post('/api/inventory/stockItems').then((res) => {
                $("#appModal").modal("hide");
                swal.fire(
                    "success!",
                    " A Product created successfully.",
                    "success"
                );

                Fire.$emit("afterCreated");
            });
        },

        deleteStockItems(id) {
            axios
                .delete('/api/inventory/stockItems/' + id)
                .then((res) => Fire.$emit("afterCreated"));
        },
        updateStockItems() {
            this.form.put('/api/inventory/stockItems').then((res) => {
                $("#appModal").modal("hide");
                swal.fire(
                    "success!",
                    "Products created successfully.",
                    "success"
                );

                Fire.$emit("afterCreated");
            });
        },

        handleChange() {},
    },
    mounted() {
        this.paginateData();
    },
    created() {
        this.loadItems();
       // this.loadPaginatedData(this.url);
        Fire.$on("afterCreated", () => {
           this.paginateData();
        });
    },
};
</script>
</script>
<style scoped>
.form-wrapper {
  padding: 2em;
  border: 1px solid #a8a8a8;
  border-radius: .5em;
  box-sizing: border-box;
}
@media (min-width: 650px) {
  .attendee {
    display: flex;
  }
}

@media (min-width: 720px) {
  .attendee {
    display: block;
  }
}

@media (min-width: 850px) {
  .attendee {
    display: flex;
  }
  .attendee .formulate-input {
    margin-right: 1.5em;
  }
}
.attendee .formulate-input {
  margin-right: 2em;
  margin-bottom: 0;
}

strong {
  display: block;
  margin: 1em 0;
}

strong.price {
  margin-top: 1.25em;
  margin-bottom: 0;
  height: 2.5em;
  display: inline-flex;
  align-items: center;
}

code {
  margin-top: 2em;
}
</style>
<template>
    <app-body pageTitle="Category" pageSubTitle="Category list">
        <div class="card card-navy card-outline">
            <div class="card-header">
                <button @click="newModal" class="btn btn-primary float-right">
                    Add New
                </button>
            </div>
            <div class="card-body">

            <app-table
            :headers="tbHeaders"
            :data="tbData"
            deleteAction
            updateAction
            search="true"
            title="Category"
             >

             </app-table>





                <app-modal
                    modalId="categoryModal"
                    :editmode="editmode"
                    :editFunc="editCategory"
                    :createFunc="createCategory"
                    :updateTitle="updateTitle"
                >
                    <input-field
                        v-model="form.name"
                        type="text"
                        label="Category Name"
                        :form="form"
                        field="name"
                        placeholder="Enter fullname"
                    />
                    <select-box
                        :form="form"
                        label="Category Type"
                        v-model="form.type"
                        :onChange="handleChange"
                        :options="[
                            {
                                id: 1,
                                value: '',
                                label: 'Select category type',
    },
                           {
                                id: 2,
                                value: 'Stock',
                                label: 'Stock',
                            },
                        ]"
                    />
                </app-modal>
            </div>
        </div>
    </app-body>
</template>

<script>

export default {

    data() {
        return {
            editmode: true,
            updateTitle: "Update Category",
            tbHeaders: [
                { header: 'Category', key: 'category', id: 1 },
                {header:'Type',key:'type',id:1}
            ],
            tbData: [
                { id: 1, category: 'Books',type:'Running Assets' },
                 {id:2,category:'Uniform',type:'Running Stock'},
            ],
            form: new Form({
                name: "",
                type: "",
            }),
        };
    },
    methods: {
        newModal() {
            $("#categoryModal").modal("show");
        },
        editCategory() {},
        createCategory() {
            console.log([this.form.type,this.form.name])
        },
        handleChange(){}
    },
};
</script>

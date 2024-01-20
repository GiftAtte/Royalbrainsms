<template>
    <app-body pageTitle="Certificates" pageSubTitle="Payment list">
        <div id="section-to-print">
        <app-table 
            :headers="tbHeaders"
            :data="tbData"
            :deleteAction="deletCertificate"
            :updateAction="updateCertificate"
            :createAction="createCertificate"
            :form="form"
            :showAction="false"
            search="true"
            title="Payment List"
            updateTitle="Update certificate"
            modalSize="modal-lg"
             
        >
            <template #addButton>
                <div class="container"><button @click="genratePDF" class="btn btn-primary float-right">PrintPDF</button></div>
            </template>

            <template #extra-action>
                <th>Amount</th>
                <th>Paid On</th>
               
            </template>
            <template v-slot:extra-action-body="{ row }">
               
                <td> &#8358;{{ row.amount.toFixed(2) }}</td>
                <td>{{ row.created_at | myDate }}</td>
            
            </template>

            <template #modal-fields>
                

<div class="row" style=" display:flex;justify-content: center; align-items: center;">
    <div class="col-md-8 " >
        <select-box
                    label="Certificates"
                    :form="form"
                    v-model="form.certificate_id"
                    placeholder="Select certificate"
                    field="category_id"
                    :options="certificates"
                    id="certificate_id"
                    name="certificate_id"
                    optionLabel="title"
                    optionValue="id"
                />
    </div>
    <div class="col mt-2"><button type="button" class="btn btn-primary float-right" @click=" toggstudentList">{{ isStudentList?'Add To List':"Add Students" }}</button></div>
</div>


                        
                          
            <div class="container table-responsive" v-if="!isStudentList">
                <table class="table table-hover table-sm">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>
                       Certificate Number
                    </th>
                    
                </tr>
            </thead>
            <tbody>
                <tr v-for="(student, index) in form.studentIds" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>
                        {{ student.name }}
                        <input
                            @input="update"
                            type="hidden"
                            :id="`student${index}`"
                            :value="student.id"
                        />
                       
                    </td>
                       <td>
                        <input
                            @input="update"
                            :id="`certificate${student.id}`"
                            :value="student.id"
                            type="text"
                            placeholder="Certificate number"
                        />
                    </td>
                </tr>
            </tbody>
        </table>
                          </div>
            </template>
        </app-table>
    </div>
        
    </app-body>
</template>

<script>

export default {
    data() {
        return {
            editmode: true,
            students:[],
            isStudentList:false,
            selectedStudents:[],
            certificates:[],
            componentKey: 0,
            updateTitle: "Updat certificate",
            tbHeaders: [{ header: "Name", key: "name" },
                        { header:"Level",key:'arm' },
                        { header:"Term",key:'term_id' },
                        { header:"Account Number",key:'accountNumber' },
            
        ],
            tbData: [],
            
            form: new Form({
                id: "",
                certificate_id:'',
                studentIds:[],
                students:[],
                student_id:''
            }),
        };
    },
    methods: {
      genratePDF(){
     window.print()
      },
        loadPayment() {
            axios.get('/api/fee-payment-list')
            .then(res=>{
                this.tbData=res.data
                });
          
        },

       
        toggstudentList(){
            this.isStudentList=!this.isStudentList;
            
        }
    },
    mounted() {
        this.loadPayment()
    },
    created() {

        Fire.$on("afterCreated", () => {
        
        });
    },
};
</script>

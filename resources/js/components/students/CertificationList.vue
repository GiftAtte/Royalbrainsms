<template>
    <app-body pageTitle="Certificates" pageSubTitle="Certificate list">
        <app-table
            :headers="tbHeaders"
            :data="tbData"
            :deleteAction="deletCertificate"
            :updateAction="updateCertificate"
            :createAction="createCertificate"
            :form="form"
            search="true"
            createButton="true"
            title="certificate list"
            updateTitle="Update certificate"
            modalSize="modal-lg"
             
        >
            <template #addButton></template>

            <template #extra-action>
                <th>Created On</th>
                <th>Action</th>
            </template>
            <template v-slot:extra-action-body="{ row }">
                <td>{{ row.created_at | myDate }}</td>
                <td>
                    <a href="#" @click="deletCertificate(row.id)">
                                    <i class="fa fa-trash red"></i>
                                </a></td>
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


                          <div v-if="isStudentList" class="row">
                            <multiselect 
                                    v-model="form.studentIds"
                                    :items="students"
                                    item-key="id"
                                    item-label="name"
                                    title="Select students"
                                    menu-position="float"
                                >
                                    <template v-slot:selected-items-footer>
                                      ..
                                    </template>
                                </multiselect>
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
                        { header:"Certificate Number",key:'certificateNumber' },
            
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
        loadCertificate() {
            axios
                .get("/api/certificates")
                .then((res) => {
                    this.certificates = res.data;
                 
                })
                .catch((err) => console.log(err));
        },
        loadCertifications() {

axios.get('/api/certifications')
.then(res=>{
    this.tbData=res.data.map(student=>{
        return{
            name:`${student.student.surname} ${student.student.first_name}`,
            certificateNumber:student.certificate_number,
            created_at:student.created_at,
            id:student.id
        
        }
    });
})
            
        },
        createCertificate() {
            
         this.form.studentIds.forEach(student => {
            let certificate=document.querySelector(`#certificate${student.id}`)
            
        if(certificate){
            this.form.students.push({
                id:student.id,
                certificateNumber:certificate.value
            });
        }

       
      });
      console.log(this.form)
      if(this.form.students.length){
 this.form.post("/api/certificates/assign").then((res) => {



                $("#appModal").modal("hide");
                swal.fire(
                    "success!",
                    "certificate created successfully.",
                    "success"
                );

                Fire.$emit("afterCreated");
            });
        }
        },

        update(event) {
            this.$emit("input", event.target.value);
        },


        deletCertificate(id) {


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
                .delete("/api/certificates/assign/" + id)
                .then((res) => Fire.$emit("afterCreated"));
                    swal.fire("Deleted!", "Item has been deleted.", "success");
                }
            });



        },
        updateCertificate() {
            this.form.post("/api/certificates/assign").then((res) => {
                $("#appModal").modal("hide");
                swal.fire(
                    "success!",
                    "Certificate created successfully.",
                    "success"
                );

                Fire.$emit("afterCreated");
            });
        },

        loadStudents() {
            axios.get('/api/certificatification/students')
            .then(res=>{
                this.students=res.data.map(student=>{
                    return{
                        name:`${student.surname} ${student.first_name}`,
                        id:student.id
                    }
                }
                );
                console.log('students',this.students)
            })
        },

       
        toggstudentList(){
            this.isStudentList=!this.isStudentList;
            
        }
    },
    mounted() {
        this.loadCertificate();
        this.loadStudents();
        this.loadCertifications()
    },
    created() {
        Fire.$on("afterCreated", () => {
            this.loadCertificate();
            this.loadCertifications();
        });
    },
};
</script>

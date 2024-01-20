<template>
    <app-body pageTitle="Biometric" pageSubTitle="Biometric Attendance">
       <div @keydown="handleKeyDown">
                    <div class="row" v-if="isScanner" >
                        <div class="col-md-6">
                            <StreamBarcodeReader @decode="onDecode" @loaded="onLoaded"></StreamBarcodeReader>
                        </div>
                        <div class="col-md-6">
                            <h1>Student Details</h1>
                            <ul v-show="barcodeCaptured">
                                <li>Name:{{ studentDetails.name }} <span class="float-right"><img
                                            class="profile-user-img img-fluid img-rounded" width="50" height="50"
                                            :src="`/img/profile/${studentDetails.img}`" alt="User profile picture"
                                            onerror="this.src='/img/profile.png'" />
                                    </span></li>
                                <li>Gender:{{ studentDetails.gender }}</li>
                                <li>Level:{{ studentDetails.level }}</li>
                                <li>Student ID:Stud-{{ studentDetails.id }}</li>
                                <i>
                                    <h3>Attendance</h3>
                                    <hr>
                                </i>

                                <li>Status:
                                    <p>Date:{{ new Date() | myDateTime }} <span class="float-right"><i
                                                class="fa fa-check text-success"></i></span></p>
                                    <h3 class="text-center text-success">{{ message }}</h3>
                                </li>
                            </ul>
                            <h3 v-show="!barcodeCaptured" class=" my-5 text-center text-success">Next Student!</h3>
                        </div>
                    </div>
             

<div id="section-to-print">
            <app-table 
            :headers="tbHeaders"
            sumitButtonText="Query"
            :data="tbData"
            :createAction="getAttendanceByDate"
            modalTitle="Select Date"
            search="true"
            :title="`Attendance List For - ${today}`"
            updateTitle="Update Category"
        >
        <template #cart>
            <button class="btn btn-primary" @click="showModal">
                Query 
            </button>
            <button class="btn btn-primary" @click="printAttendance">
               <i class="fa fa-print"></i> 
            </button>
        </template>

            <template #addButton>
                <div class="row">
                        <div class="col-md-6 text-primary">
                            SCANNER <span>
                                <toggle-button @change="toggleScanner(school.id)" :label="true" :labels="{
                                    checked: 'ON',
                                    unchecked: 'OFF',
                                }" :height="30" :font-size="16" :width="80" v-model="isScanner" :color="'navy'"
                                    :name="'activated'" class="pl-2" />
                            </span>
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-6 float-right">
                            CHECK IN/OUT <span>
                                <toggle-button  
                               sync="true"
                                @change="toggleIsCheckout(school.id)" 
                                :label="true" 
                                :labels="{
                                    checked: 'IN',
                                    unchecked: 'OUT',
                                }" 
                                :height="30" :font-size="16" :width="80" v-model="isCheckIn" :color="'navy'"
                                    :name="'checkin-out'"  />
                            </span>
                        </div>   
                        </div>
                    </div>
            </template>

            <template #extra-action>
                <th>CheckedIn</th>
                <th>Checked Out</th>
            </template>
            <template v-slot:extra-action-body="{ row }">
                <td>{{ row.checkedInTime| myDateTime }} <i class="fa fa-check text-success "></i></td>
                <td v-if="row.checkedOutTime">{{ row.checkedOutTime| myDateTime }}</td>
                <td v-else class="text-danger"><i class="fa fa-times text-danger"></i></td>
            </template>

     <template #modal-fields>
      <input-field
        label="Attendance Date"
        v-model="form.attendance_date"
        id="attendance_date"
        :form="form"
        field="attendance_date"
        placeholder=" "
        type="date"
      />
     </template>
        </app-table>
    </div></div>
    </app-body>
</template>
<script>
import { StreamBarcodeReader, ImageBarcodeReader } from "vue-barcode-reader";
export default {
    components: { StreamBarcodeReader, ImageBarcodeReader },

    data() {
        return {
            studentDetails: '',
            barcodeCaptured: false,
            message: '',
            isScanner: false,
            tbData: [],
            isCheckIn:true,
            today:new Date().toDateString(),
            tbHeaders:[
                {header:"Student Name",key:"name"},
                {header:"Level",key:"level"},
               
            ],
            
form:new Form({
    attendance_date:''
})
        }
    },

    methods: {
        printAttendance(){
            window.print();
        },

        showModal(){
             $('#appModal').modal('show')
        },
        onDecode(result) {
            console.log(result)
            if (result) {
                let data = JSON.parse(result)
                this.studentDetails = data;
                this.markAttendance(data)

            }
            else {
                this.barcodeCaptured = false;
            }
        },


        markAttendance(data) {
            this.barcodeCaptured = true
            const formData = new FormData();
            formData.append('student_id', data.id);
            formData.append('level_id', data.level_id);
            formData.append('arm_id', data.arm_id)
            if (this.isCheckIn) {
                this.checkIn(formData);
                
            } else {
                this.checkOut(formData);
            }

        },

        toggleScanner() {
            this.isScanner = !this.isScanner
        },

        toggleIsCheckIn() {
            this.isCheckIn = !this.isCheckIn
        },


        checkIn(formData) {
            axios.post(`/api/attendance/check-in`, formData)
                .then(res => {
                    this.message = res.data.message
                    this.loadAttendance();
                    setTimeout(() => {
                        this.barcodeCaptured = false;
                        this.studentDetails = '';

                    }, 5000)
                })
        },
        checkOut(formData) {
            axios.post(`/api/attendance/check-out`, formData)
                .then(res => {
                    this.message = res.data.message
                    this.loadAttendance();
                    setTimeout(() => {
                        this.barcodeCaptured = false;
                        this.studentDetails = '';

                    }, 5000)
                })
        },

handleKeyDown(event){
//     var charCode = (typeof e.which == "number") ? e.which : e.keyCode;

//     if (charCode != 13) { // ascii 13 is return key
//         lastScannedBarCode += String.fromCharCode(charCode);
//     } else { // barcode reader indicate code finished with "enter"
//         var lastCode = lastScannedBarCode;
       
//         if (lastCode == "CommandAdd") { // Switch to add command
//             operationState.current = "Adding";
//         } if (lastCode == "CommandDelete") { // Switch to delete command
//             operationState.current = "Deleting";
//         } else  this.handleBarcode(lastCode);

//         lastScannedBarCode = ""; // zero out last code (so we do not keep adding)
        
// }





    let barcode='';
let interval;

if(interval){
  clearInterval(interval);
}
if(event.code=="Enter"){
  if(barcode){
    this.handleBarcode(barcode);
    barcode="";
    return;
  }
  if(event.key!="Shift"){
    barcode+=event.key;
    interval=setTimeout(()=>'',20)
  }

}

},


handleBarcode(barcode){
axios.get('/api/student/'+barcode).then(res=>{
    console.log(res.data)
})
},

        loadAttendance() {
            axios.get('/api/attendance/checkin-checkout')
                .then(res => {
                    this.tbData = res.data.map(student=>{
                        return{
                            name:`${student.students.first_name} ${student.students.surname}`,
                            level:student.level.level_name,
                            checkedInTime:student.checkin_time,
                            checkedOutTime:student.checkout_time
                        }
                    })
                    console.log( this.tbData)
                })
        },
        getAttendanceByDate(){
          this.today=  new Date(this.form.attendance_date).toDateString(),
            axios.post(`/api/attendance/checkin-checkout/${this.form.attendance_date}`)
            .then(res=>{
                this.tbData = res.data.map(student=>{
                        return{
                            name:`${student.students.first_name} ${student.students.surname}`,
                            level:student.level.level_name,
                            checkedInTime:student.checkin_time,
                            checkedOutTime:student.checkout_time
                        }
                    })
                    $('#appModal').modal('hide');
            })
        }
    },

    created() {
this.loadAttendance();
var lastScannedBarCode = "";
document.addEventListener('keydown',(e)=>{
    var charCode = (typeof e.which == "number") ? e.which : e.keyCode;

    if (charCode != 13) { // ascii 13 is return key
        lastScannedBarCode += String.fromCharCode(charCode);
    } else { // barcode reader indicate code finished with "enter"
        var lastCode = lastScannedBarCode;
       
        if (lastCode == "CommandAdd") { // Switch to add command
            operationState.current = "Adding";
        } if (lastCode == "CommandDelete") { // Switch to delete command
            operationState.current = "Deleting";
        } else  this.handleBarcode(lastCode);

        lastScannedBarCode = ""; // zero out last code (so we do not keep adding)
    
     } })
    }

}



</script>
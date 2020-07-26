<template>
<b-row>
<update-fee
  :studentFeeForm = "studentFeeForm"
  :loading = "loading"
>
</update-fee>
<update-transaction
 :transactionForm = "transactionForm"
 :loading = "loading"
 >
   
 </update-transaction>
<b-col md="12">
     <div class="card card-defult mt-3">
        <div class="card-header">Pay List</div>
        <div class="card-body">
  
          <table style="border-collapse: collapse;" class="table-hover table-bordered studentFee-table" id="list-student-fee">
            <thead>
              <tr>
                <th style="text-align: center">No</th>
                <th>Programe</th>
                <th>Level</th>
                <th style="text-align: center">Fee($)</th>
                <th style="text-align: center">Amount($)</th>
                <th style="text-align: center">Discount(%)</th>
                <th style="text-align: center">Paid($)</th>
                <th style="text-align: center">Balance($)</th>
                <th style="text-align: center">Action</th>
              </tr>
              
            </thead>
            <tbody id="tbody-student-fee" v-if="studentFees" v-for="(studentFee, index) in studentFees">
                
              <tr id="sfeeId">
                <td style="text-align: center;">{{ index+1 }}</td>
                <td style="text-align: center;">{{ studentFee.programe }}</td>
                <td style="text-align: center;">{{ studentFee.level }}</td>
                <td style="text-align: center;">{{ studentFee.course_fee | currency}}</td>
                <td style="text-align: center;">{{ studentFee.student_amount | currency}}</td>
                <td style="text-align: center;">{{ studentFee.discount }} %</td>
                <td style="text-align: center;">{{ studentFee.total_paid | currency}}</td>
              
                <!-- <div v-for="studentTransaction in studentTransactions">
                  <div v-if="studentTransaction.s_fee_id == studentFee.s_fee_id">
                      <td style="text-align: center;">{{ studentTransaction.paid }}</td>
                  </div>
                </div> -->
                

                <td style="text-align: center; color: red;">
                <b>{{ (studentFee.student_amount - studentFee.total_paid)  | currency }}</b>
                </td>

                <td style="text-align: center; width: 120px;">
                  <a href="#" @click="editFee(studentFee.s_fee_id)">
                    <i class="fa fa-edit pink fa-fw"></i>
                  </a> /
                  <button type="button" style="background:none;border:none;padding: 0;" :value="(studentFee.student_amount - studentFee.total_paid)" @click='extraPaid(studentFee.s_fee_id)' id="extraPaid">
                    <i class="fa fa-dollar blue fa-fw">($)</i>
                  </button> / 
                  <a href="#" @click="fullPrintInvoice(studentFee.s_fee_id)" >
                    <i class="fa indigo fa-print"></i>
                  </a> / 
                  <a href="#" class="according-toggle" data-toggle="collapse" :data-target="'#demo' + index">
                    <i class="fa fa-eye green"></i>
                  </a> / 
                  <a href="#" @click="deleteStudentFee(studentFee.s_fee_id)" >
                    <i class="fa red fa-trash"></i>
                  </a> 

                </td>
              </tr>
              <tr>
                <th colspan="9" class="hiddenrow">
                   <div class="'accordian-body collapse" :id="'demo'+index">
                    <table class="transaction-table">
                      <thead>
                        <tr style="background: #f1d359">
                          <th style="text-align: center;">#</th>
                          <th>Transaction Date</th>
                          <th>Cashier</th>
                          <th>Paid ($)</th>
                          <th>Remark</th>
                          <th>Description</th>
                          <th style="text-align: center;">Action</th>
                        </tr>
                      </thead>

                      <tbody v-if="studentTransactions" >
                        <tr v-for="(studentTransaction,index) in studentTransactions" v-if="studentFee.s_fee_id == studentTransaction.s_fee_id" style="background: #fff6d1">
                          <td>{{ index+1 }}</td>
                          <td>{{ studentTransaction.transact_date }}</td>
                          <td>{{ studentTransaction.name }}</td>
                          <td><b>{{ studentTransaction.paid }}</b></td>
                          <td>{{ studentTransaction.remark }}</td>
                          <td>{{ studentTransaction.description }}</td>
                          <td style="text-align: center; width: 112px;">
                            <a href="#" @click="editTransaction(studentTransaction.transact_id)"><i class="fa blue fa-edit"></i></a> / 
                            <a href="#" @click="printInvoice(studentTransaction.receipt_id)" >
                              <i class="fa indigo fa-print"></i>
                            </a> / 
                            <a href="#" @click="deleteTransaction(studentTransaction.transact_id)"><i class="fa red fa-trash"></i></a>
                          </td>
                        </tr>
                        <tr style="background: #f1d359">
                          <td></td>
                          <td></td>
                          <td><b>Total Paid</b></td>
                          <td class="green"><b>{{ studentFee.total_paid | currency }}</b></td>
                          <td><b>Due</b></td>
                          <td class="red"><b>{{ (studentFee.student_amount - studentFee.total_paid)  | currency }}</b></td>
                          <td></td>
                        </tr>

                      </tbody>
                    </table>

                  </div>
                </th>
              </tr>
            </tbody>
          </table>

        </div>
        <div class="card-footer">
               
        </div>
    </div>
</b-col>
</b-row>

</template>




<script>


import UpdateFee from '../model/UpdateFee.vue';
import UpdateTransaction from '../model/UpdateTransaction.vue';
import { BContainer, BRow, BCol, BCard} from 'bootstrap-vue'

export default {
    components: {
        'b-container': BContainer,
        'b-row': BRow,
        'b-col': BCol,
        'b-card': BCard,
        'update-fee': UpdateFee,
        'update-transaction': UpdateTransaction,

    },
    props:{
      studentFees:{},
      studentTransactions:{},
      form:{},
      totalPaid:{}
    },
    data() {
        return {

          loading:false,
          sum: 0,
          studentFeeForm: new Form({
            programe:'',
            programe_id:'',
            level:'',
            level_id:'',
            student_id:'',
            school_fee:'',
            fee_id:'',
            s_fee_id:'',
            student_amount:'',
            discount:'',
          }),
          transactionForm: new Form({
            transact_id:'',
            transact_date:'',
            fee_id:'',
            user_id:'',
            name:'',
            student_id:'',
            s_fee_id:'',
            paid:'',
            remark:'',
            description:''
          })
        }
    },
    methods: {
       
       extraPaid(s_fee_id){
            
              axios.get('api/payment/get-extra-pay/'+s_fee_id)
              .then((data) => {
                this.form.fee = data.data.school_fee;
                this.form.amount = data.data.student_amount;
                this.form.discount = data.data.discount;
                this.form.balance = (data.data.student_amount-data.data.total_paid);
                this.form.extraPaid = (data.data.student_amount-data.data.total_paid);
                this.form.fee_id = data.data.fee_id;
                this.form.s_fee_id = data.data.s_fee_id;
                this.form.level_id = data.data.level_id;
                this.form.programe_id = data.data.programe_id;
                this.form.student_id = data.data.student_id;

                if (this.form.balance == 0) {
                  Swal.fire({
                    title: 'Sorry to Pay',
                    text: "Here is no balance!",
                    icon: 'warning',
                    showCancelButton: true,
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                  });
                  this.form.fee = '';
                  this.form.amount = '';
                  this.form.discount = '';
                  this.form.extraPaid = '';
                  this.form.fee_id = '';
                  this.form.s_fee_id = '';
                  this.form.level_id = '';
                  this.form.programe_id = '';
                }



             })
             .catch(()=>{
                Toast.fire({icon: 'error',title: 'Ops !'});
             });
           
       },
       printInvoice(receipt_id){
            let routeData = this.$router.resolve({ name: 'printInvoice', query: {receipt_id: receipt_id} })
            window.open(routeData.href, '_blank');
            /*this.$router.push({ name: 'printInvoice', params: { receipt_id } })*/
       },
       fullPrintInvoice(s_fee_id){
            let routeData = this.$router.resolve({ name: 'fullPrintInvoice', query: {s_fee_id: s_fee_id} })
            window.open(routeData.href, '_blank');
       },
       editFee(s_fee_id){
         this.loading=true;
          $('#editFee').modal('show');
          axios.get('api/payment/get-student-fee-by-id/'+s_fee_id)
          .then((data)=>{
            this.studentFeeForm.fill(data.data);
            this.loading=false;
          })
          .catch(()=>{
            Toast.fire({icon: 'error',title: 'Ops !'});
            this.loading=false;
          })
       },
       editTransaction(transact_id){
          this.loading=true;
          $('#editTransaction').modal('show');
          axios.get('api/payment/get-transaction-by-id/'+transact_id)
          .then((data)=>{
            this.transactionForm.fill(data.data);
            this.loading=false;
          })
          .catch(()=>{
            Toast.fire({icon: 'error',title: 'Ops !'});
            this.loading=false;
          })
       },
       deleteTransaction(transact_id){
         Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to Delete this Transaction!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
          }).then((result) => {
            if (result.value) {

              this.form.delete('api/payment/delete-transaction/'+transact_id)
              .then(()=>{
                  Swal.fire(
                    'Deleted!',
                    'Transaction has been deleted.',
                    'success'
                  )
                Fire.$emit('loadData');
              }).catch(()=>{
                Swal.fire('Failed','Ops Something Wrong','warning');
              })
              
            }
          })
       },
       deleteStudentFee(s_fee_id){
         Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to Delete this Student Fee!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
          }).then((result) => {
            if (result.value) {

              this.form.delete('api/payment/delete-student-fee/'+s_fee_id)
              .then(()=>{
                  Swal.fire(
                    'Deleted!',
                    'Student Fee has been deleted.',
                    'success'
                  )
                Fire.$emit('loadData');
              }).catch(()=>{
                Swal.fire('Failed','Ops Something Wrong','warning');
              })
              
            }
          })
       }
        
    },
    
    mounted() {
    },
    computed:{
       
    },
    created() {
        Fire.$on('loadData',()=>{
        });
    }
}
</script>


<style scoped lang="scss">

table{
    border-collapse: collapse;
    width: 100%;
    white-space: nowrap;
    text-align:center;
}
table.transaction-table tr {
    background: red;
    border: 1px solid #d0d0d0;
}

table.transaction-table tr th {
    border: none;
}

table.transaction-table tr td {
    border: none;
}

table.transaction-table {
    font-size: 14px;
    font-weight: normal;
    color: #688a7e;
}
table.transaction-table tr {
    color: #688a7e;
}
.table-hover tbody tr:hover {
    color: none ;
}
  .create-fee{
        cursor: pointer;
        color: blue;
        padding: 3px 7px;
        width: 100%;
        float: right;
        position: absolute;
        overflow: hidden;
        display: block;
        text-align: right;
  }
  
</style>
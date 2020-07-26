<template>
	<table class="table-hover table-striped table-bordered" id="table-class-info">
                <thead>
                    <tr class="text-center">
                        <th>S.ID</th>
                        <th>Student Name</th>
                        <th>Course Perform</th>
                        <th>Payment Complete</th>
                        <th>Due</th>
                        <th>Action</th>
                    </tr>
                </thead>
                
                <tbody v-if="students" v-for="(student,index) in students">
                        <tr>
                            <td>{{ index+1 }}</td>
                            <td>{{ student.name }} {{ student.nick_name }}</td>
                            <td>
                              <span v-for="(studentFee, sfindex) in studentFees" v-if="studentFee.student_id == student.student_id">
                                <i class="fa fa-star yellow"></i>
                              </span>
                            </td>
                            <td>
                              <span v-for="(studentFee) in studentFees" v-if="studentFee.student_id == student.student_id">
                                <span v-if="studentFee.studentAmount==studentFee.total_paid">
                                  <i class="fa fa-briefcase green mr-1"></i>
                                </span>
                                <span v-else="studentFee.studentAmount==studentFee.total_paid">
                                  <i class="fa fa-briefcase red mr-1"></i>
                                </span>
                              </span>
                            </td>
                            <td>
                               <span v-for="(studentFee) in studentFees" v-if="studentFee.student_id == student.student_id">
                                  <span v-if="studentFee.studentAmount!=studentFee.total_paid">
                                    <i class="fa fa-briefcase red mr-1"></i> 
                                    {{ (studentFee.studentAmount - studentFee.total_paid) | currency }} of <span class="badge badge-danger">{{ studentFee.programe }}( {{studentFee.level}} )
                                      </span>
                                  </span>
                                  
                                    
                                </span>
                               
                              
                            </td>
                            <td>
                              <a href="#" class="according-toggle" data-toggle="collapse" :data-target="'#demo'+index">
                                <i class="fa fa-eye green"></i>
                              </a>
                            </td>
                        </tr>
                        <tr>
                          <th colspan="9" class="hiddenrow">
                             <div class="'accordian-body collapse" :id="'demo'+index">
                              <table class="transaction-table">
                                <thead class="bgwhite">
                                  <tr>
                                    <th>No</th>
                                    <th>Programe</th>
                                    <th>Level</th>
                                    <th>Course Fee($)</th>
                                    <th>Student Fee($)</th>
                                    <th>Discount(%)</th>
                                    <th>Total Paid($)</th>
                                    <th>Due($)</th>
                                    <th>Action</th>
                                  </tr>
                                </thead>

                                <tbody v-for="(studentFee, sfindex) in studentFees" v-if="studentFee.student_id == student.student_id" >
                                  <tr v-if="studentFee.studentAmount==studentFee.total_paid" class="bggreen">
                                    <td>{{ sfindex+1 }}</td>
                                    <td>{{ studentFee.programe }}</td>
                                    <td>{{ studentFee.level }}</td>
                                    <td>{{ studentFee.courseFee | currency}}</td>
                                    <td>{{ studentFee.studentAmount | currency}}</td>
                                    <td>{{ studentFee.discount }}%</td>
                                    <td>{{ studentFee.total_paid | currency}}</td>
                                    <td>{{ (studentFee.studentAmount - studentFee.total_paid) | currency}}</td>
                                    <td>
                                       
                                      <a href="#" @click="fullPrintInvoice(studentFee.s_fee_id)" >
                                      <i class="fa indigo fa-print"></i>
                                    </a> /
                                    <a href="#" class="according-toggle" data-toggle="collapse" :data-target="'#transac'+sfindex">
                                        <i class="fa fa-eye green"></i>
                                      </a>
                                    </td>
                                  </tr>
                                  <tr v-else="studentFee.studentAmount==studentFee.total_paid" class="bgred">
                                    <td>{{ sfindex+1 }}</td>
                                    <td>{{ studentFee.programe }}</td>
                                    <td>{{ studentFee.level }}</td>
                                    <td>{{ studentFee.courseFee | currency}}</td>
                                    <td>{{ studentFee.studentAmount | currency }}</td>
                                    <td>{{ studentFee.discount }}</td>
                                    <td>{{ studentFee.total_paid | currency}}</td>
                                    <td>{{ (studentFee.studentAmount - studentFee.total_paid) | currency}}</td>
                                    <td>
                                       
                                      <a href="#" @click="fullPrintInvoice(studentFee.s_fee_id)" >
                                      <i class="fa indigo fa-print"></i>
                                    </a> /
                                    <a href="#" class="according-toggle" data-toggle="collapse" :data-target="'#transac'+sfindex">
                                        <i class="fa fa-eye green"></i>
                                      </a>
                                    </td>
                                  </tr>
                                  
                                  <tr>
                                    <th colspan="9" class="hiddenrow">
                                       <div class="'accordian-body collapse" :id="'transac'+sfindex">
                                        <table class="transaction-table">
                                          <thead>
                                            <tr class="bggray">
                                              <th>#</th>
                                              <th>Transaction Date</th>
                                              <th>Cashier</th>
                                              <th>Paid ($)</th>
                                              <th>Remark</th>
                                              <th>Description</th>
                                              <th>Action</th>
                                            </tr>
                                          </thead>

                                          <tbody v-if="studentTransactions" >
                                            <tr v-for="(studentTransaction,index) in studentTransactions" v-if="studentFee.s_fee_id == studentTransaction.s_fee_id" style="background: #fff6d1">
                                              <td>{{ index+1 }}</td>
                                              <td>{{ studentTransaction.transact_date }}</td>
                                              <td>{{ studentTransaction.name }}</td>
                                              <td>{{ studentTransaction.paid | currency}}</td>
                                              <td>{{ studentTransaction.remark }}</td>
                                              <td>{{ studentTransaction.description }}</td>
                                              <td style="text-align: center; width: 112px;">
                                                <a href="#" @click="printInvoice(studentTransaction.receipt_id)" >
                                                  <i class="fa indigo fa-print"></i>
                                                </a>
                                              </td>
                                            </tr>
                                            <tr class="bggray">
                                              <td></td>
                                              <td></td>
                                              <td><b>Total Paid</b></td>
                                              <td><b>{{ studentFee.total_paid | currency }}</b></td>
                                              <td><b>Due</b></td>
                                              <td class="red"><b>{{ (studentFee.studentAmount - studentFee.total_paid)  | currency }}</b></td>
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
                          </th>
                        </tr>

                </tbody>
            </table>
</template>
  
<script>
export default {
  name: "payment-history-list",
  components: {
    
  },
  props: {
    students:{},
    studentFees:{},
    studentTransactions:{},
  },

  data(){
      return {
        
      }
  },
  compute:{
      
      
  },

  
  methods:{
    printInvoice(receipt_id){
            let routeData = this.$router.resolve({ name: 'printInvoice', query: {receipt_id: receipt_id} })
            window.open(routeData.href, '_blank');
            this.$router.push({ name: 'printInvoice', params: { receipt_id } })
       },
    fullPrintInvoice(s_fee_id){
            let routeData = this.$router.resolve({ name: 'fullPrintInvoice', query: {s_fee_id: s_fee_id} })
            window.open(routeData.href, '_blank');
    },

       
  
  },
  created(){
    // this.total_amount()

  }
};
</script>

<style scoped lang="scss">

table{
    border-collapse: collapse;
    width: 100%;
    white-space: nowrap;
    text-align:center;
}

table.customeTableStyle thead tr {
    background: #9424e1;
}
table.customeTableStyle thead tr th{
    color: #fff;
}
table.customeTableStyleUnderRow thead tr {
    background: #eae9ea;
}
table.customeTableStyleUnderRow thead tr th{
    color: #000;
}





</style>
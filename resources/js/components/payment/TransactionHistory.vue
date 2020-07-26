
<template>
    <b-container class="bv-example-row">
         <b-row class="justify-content-md-center">
           <b-col md="12">
                 <h3 class="page-header mt-2"><i class="fa fa-user"></i> Student</h3>
                 <b-breadcrumb :items="items"></b-breadcrumb>
                 
            </b-col>

        </b-row>
        
    
    <div class="card card-defult mt-3">
        <div class="card-header">Transaction Information 
            <span class="loaderStyle" style="float:right" v-if="loading">
                <i class="fa fa-cog fa-spin fa-2x indigo"></i>
            </span> 
        </div>
        <div class="card-body" id="show-transaction-info" >

            <!--
           <table class="table table-hover table-striped table-bordered" id="table-trsansaction-info">
                <thead>
                    <tr class="text-center">
                        <th>Transact ID</th>
                        <th>Transact Date</th>
                        <th>Fees</th>
                        <th>Cashier</th>
                        <th>Student Name(ID)</th>
                        <th>Fee Type</th>
                        <th>Paid</th>
                    </tr>
                </thead>
                <tbody>
                        <tr v-for="transaction in transactions.data" :key='transaction.transact_id' class="text-center">
                            <td>{{ transaction.transact_id }}</td>
                            <td><b>{{ transaction.transact_date | trsansactDate}}</b></td>
                            <td>{{ transaction.feeType }}</td>
                            <td>{{ transaction.cashier }}</td>
                            <td>{{ transaction.studentName }} <b>({{ transaction.student_id }})</b></td>
                            <td>{{ transaction.fee_type }}</td>
                            <td><b>{{ transaction.paid | currency }}</b></td>
                        </tr>
                </tbody>
            </table>
         -->

        </div>
        <div class="card-footer">
            
        </div>
    </div>


   </b-container>

   
</template>

<script>

import DatePick from 'vue-date-pick';
import 'vue-date-pick/dist/vueDatePick.css';
import { BBreadcrumb, BContainer, BRow, BCol, BCard} from 'bootstrap-vue'
export default {
        components: {
            DatePick,
            'b-breadcrumb': BBreadcrumb,
            'b-container': BContainer,
            'b-row': BRow,
            'b-col': BCol,
            'b-card': BCard,
        },
        data() {
            return {
            	items: [
	              {text: 'Home',to: '/dashboard'},
	              {text: 'Trsansaction History',to: '/transaction-history'},
	              {text: 'List',active: true}
	            ],
                editmode: false,
                transactions :{},
                loading: false,
                search_it:null,
            }
        },
        methods: {
            loadTransactions(){
            	if (this.$gate.isAdminOrAuthor()) {
                    this.loading = true
                    axios.get('api/payment/get_transaction-history')
                    .then((data) => {
                        $('#show-transaction-info').empty().append(data.data);
                        this.loading = false
                    })
                    .catch(()=>{
                        this.loading = false
                    });


               }
            }
            
            
        },
        
        mounted() {
            console.log('Component mounted.')
        },
        created() {
            Fire.$on('searcing',()=>{
              this.loading = true
              let query = this.$parent.search;
              axios.get('api/payment/globalFindTransaction?q=' + query)
              .then((data)=>{
                $('#show-transaction-info').empty().append(data.data);
                this.loading = false
              })
              .then(()=>{
                this.loading = false
              })
            });
            this.loadTransactions();
         
        },
        computed:{
            
        }
    }
</script>



<template>
    <b-container class="bv-example-row">
         <b-row class="justify-content-md-center">
           <b-col md="12">
                 <h3 class="page-header mt-2"><i class="fa fa-envelope"></i> Message</h3>
                 <b-breadcrumb :items="items"></b-breadcrumb>
                 
            </b-col>

        </b-row>
        
    
    <div class="card card-defult mt-3">
        <div class="card-header">Payment Information 
            <span class="loaderStyle" style="float:right" v-if="loading">
                <i class="fa fa-cog fa-spin fa-2x indigo"></i>
            </span> 
        </div>
        <div class="card-body" id="show-payment-info" >

            
         

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
	              {text: 'Payment History',to: '/payment-history'},
	              {text: 'List',active: true}
	            ],
                editmode: false,
                transactions :{},
                loading: false,
                search_it:null,
            }
        },
        methods: {
            loadStudentFees(){
            	if (this.$gate.isAdminOrAuthor()) {
                    this.loading = true
                    axios.get('api/report/payment/all_payment')
                    .then((data) => {
                        $('#show-payment-info').empty().append(data.data);
                        this.loading = false
                    })
                    .catch(()=>{
                        this.loading = false
                    });
               }
            },
            
            
            
        },
        
        mounted() {
            console.log('Component mounted.')
        },
        created() {
            Fire.$on('searcing',()=>{
              this.loading = true
              let query = this.$parent.search;
              axios.get('api/report/payment/globalFindStudentPaymentReport?q=' + query)
              .then((data)=>{
                 $('#show-payment-info').empty().append(data.data);
                 this.loading = false;
              })
              .then(()=>{
                this.loading = false
              })
            });
            this.loadStudentFees();
        },
        computed:{
            
        }
    }
</script>


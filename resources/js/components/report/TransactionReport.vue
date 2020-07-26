
<template>
    <b-container class="bv-example-row">
        <b-row class="justify-content-md-center">
           <b-col md="12">
                 <h3 class="page-header mt-2"><i class="fa fa-list"></i> Student Report</h3>
                 <b-breadcrumb :items="items"></b-breadcrumb>
            </b-col>
        </b-row>

    <div class="card card-defult mt-3">
        <div class="card-header">Student  
            <div class="card-tools" style="float:right">
                <span class="loaderStyle" style="float:right;" v-if="loading">
                    <i class="fa fa-cog fa-spin fa-2x indigo"></i>
                </span>
                <span class="loaderStyle" style="float:right">
            
           		    <div class="input-group">
                        <date-pick
                            v-model="from"
                            :format="'YYYY-MM-DD'">
                        </date-pick>
	                <span class="mt-2 ml-3 mr-3"><b> To </b></span>
	                    <date-pick
                            v-model="to"
                            :format="'YYYY-MM-DD'">
                        </date-pick>
                    <span class="mt-1 ml-3 mr-3">
                    	<button class="btn btn-success btn-sm" @click.prevent="getReportByDate">
                    		<i class="fa fa-search"></i>
                    	</button>
                    </span>
	                </div>
            </span>
            </div>
        </div>
        <div class="card-body" id="show-payment-info">
			

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
            'b-card': BCard
        },
        data() {
            return {
            	items: [
	              { text: 'Home', to: '/home' },
	              { text: 'Payment Report', to: '/report-payment'  },
	              { text: 'Report', active: true }
	            ],
                editmode: false,
                loading: false,
                from: null,
                to: null
            }
        },
        methods: {
        	getReportByDate(){
        		this.loading = true
		        let data = {from:this.from,to:this.to}
		        console.log(data)
		          axios.post('api/report/payment/get_from_to_date', data )
		         .then(response => {
		            $('#show-payment-info').empty().append(response.data);
                    this.loading = false
		          })
		         .catch(()=>{
		         	this.loading = false
		         })
        	},
            loadStudents(){
               if (this.$gate.isAdminOrAuthor()) {
                    this.loading = true
                    axios.get('api/report/payment/all_transact')
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
            console.log('Component mounted.');
        },
        created() {
            Fire.$on('searcing',()=>{
              this.loading = true
              let query = this.$parent.search;
              axios.get('api/report/student/global_find_student?q=' + query)
              .then((data)=>{
                $('#show-student-info').empty().append(data.data);
                this.loading = false
              })
              .then(()=>{
                this.loading = false
              })
            });
         	this.loadStudents();
        },
        computed:{
            
        }
    }
</script>



<template>
    <b-container class="bv-example-row">
         <b-row class="justify-content-md-center">
           <b-col md="12">
                 <h3 class="page-header mt-2"><i class="fa fa-user"></i> Student</h3>
                 <b-breadcrumb :items="items"></b-breadcrumb>
                 
            </b-col>

        </b-row>
        
    
    <div class="card card-defult mt-3">
        <div class="card-header">Payment Information 
            <span class="loaderStyle" style="float:right" v-if="loading">
                <i class="fa fa-cog fa-spin fa-2x indigo"></i>
            </span> 
        </div>
        <div class="card-body" id="show-payment-info">
        
            <payment-history-list
                :studentFees="studentFees"
            >
            
            </payment-history-list>
         

        </div>
        <div class="card-footer">
            
        </div>
    </div>


   </b-container>

   
</template>

<script>
import paymentHistoryList from './list/paymentHistoryList.vue';
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
            'payment-history-list': paymentHistoryList,
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
                studentFees:{},
            }
        },
        methods: {
            loadStudentFees(){
            	if (this.$gate.isAdminOrAuthor()) {
                    this.loading = true
                    axios.get('api/payment/get_payment_history')
                    .then((data) => {
                        $('#show-payment-info').empty().append(data.data);
                        // console.log(data.data.studentFee);
                        // this.studentFees = data.data.studentFee;
                        this.loading = false;
                        this.MergeCommonRows($('#show-payment-info'));
                    })
                    .catch(()=>{
                        this.loading = false
                    });


               }
            },
            MergeCommonRows(table) {
                var firstColumnBrakes = [];
                $.each(table.find('th'),function(i){
                    var previous = null, cellToExtend = null, rowspan = 1;
                    table.find("td:nth-child(" + i + ")").each(function(index, e){
                        var jthis = $(this), content = jthis.text();
                        if (previous == content && content !== "" && $.inArray(index, firstColumnBrakes) === -1) {
                            jthis.addClass('hidden');
                            cellToExtend.attr("rowspan", (rowspan = rowspan+1));
                        } else {
                            if (i === 1) firstColumnBrakes.push(index);
                            rowspan = 1;
                            previous = content;
                            cellToExtend = jthis;
                        }
                    });
                });
                $('td.hidden').remove();
            }
            
            
        },
        
        mounted() {
            console.log('Component mounted.')
        },
        created() {
            Fire.$on('searcing',()=>{
              this.loading = true
              let query = this.$parent.search;
              axios.get('api/payment/globalFindStudentPayment?q=' + query)
              .then((data)=>{
                 $('#show-payment-info').empty().append(data.data);
                 this.loading = false;
                 this.MergeCommonRows($('#show-payment-info'));
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


<template>
  <b-container class="bv-example-row" v-if='$gate.isAdminOrAuthor()'>
        <b-row class="justify-content-md-center">
           <b-col md="12 mt-3">
               
                 <b-breadcrumb :items="items"></b-breadcrumb>
            </b-col>
        </b-row>
        
    
    <b-row class="justify-content-center">
        <b-col md="6">
          <expense-type></expense-type>
        </b-col>
        <b-col md="6">
          <fee-type></fee-type>
        </b-col>
        <b-col md="12">
          <course-fee></course-fee>
        </b-col>
      
    </b-row>
<div v-if='!$gate.isAdminOrAuthor()'>
          <not-found></not-found>
</div>
   <academic></academic>
   <programe></programe>
   <level></level>
  </b-container>

        
</template>
<script>

import Academic from '.././course/model/academicYear.vue';
import Programe from '.././course/model/Programe.vue';
import Level from '.././course/model/Level.vue';

import ExpenseType from './components/ExpenseType.vue';
import FeeType from './components/FeeType.vue';
import CourseFee from './components/CourseFee.vue';
import { BBreadcrumb, BContainer, BRow, BCol, BCard} from 'bootstrap-vue';
export default {
        components: {
            'b-breadcrumb': BBreadcrumb,
            'b-container': BContainer,
            'b-row': BRow,
            'b-col': BCol,
            'b-card': BCard,
            'expense-type': ExpenseType,
            'fee-type': FeeType,
            'course-fee': CourseFee,
            'academic': Academic,
            'programe': Programe,
            'level': Level
        },
        data() {
            return {
              items: [
                { text: 'Home', to: '/home' },
                { text: 'Settings', to: '/settings'  },
                { text: 'Setting', active: true }
              ],
              loading: false,
              expenseTypes:{},
              feeTypes:{},
              fees:{},

            }
        },
        methods: {
          
         
          
          loadData(){
          	if (this.$gate.isAdminOrAuthor()) {
                    this.loading = true;
                    axios.get('api/settings/get_data')
                    .then((data) => {
                        this.expenseTypes = data.data.expenseTypes;
                        this.feeTypes = data.data.feeTypes;
                        this.fees = data.data.fees;
                        this.loading = false;
                    })
                    .catch(()=>{
                        this.loading = false
                    });


              }
          }
            
        },
        
        mounted() {
            console.log('Component mounted.');
        },
        created() {
           this.loadData();
          

        },
        computed:{
            
        }
    }
</script>


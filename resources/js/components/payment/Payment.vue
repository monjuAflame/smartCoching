
<template>

    <b-container class="bv-example-row">
        <b-row class="justify-content-md-center">
            <b-col md="12">
                 <h3 class="page-header mt-2"><i class="fa fa-user"></i> Student</h3>
                 <b-breadcrumb :items="items"></b-breadcrumb>
                 
            </b-col>

        </b-row>

 <create-fee 
  v-bind:feeTypes="feeTypes"
  v-bind:studentDetails="studentDetails"
  @schoolFee="getSchoolFee">
 </create-fee>

 <create-new-course
  :form="form"
  :courses="courses"
  @getStudentDetailsByNewCourse="getStudentDetailsByNewCourse"
>
</create-new-course>
<choice-course 
  @getCourse="getCourse"
>
</choice-course>

        <b-row class="row panel mb-3">
	        <div class="panel-header">
	           <div class="searchId">
	           	    <form @submit.prevent="searchId()">
	           		    <div class="input-group">
                        <input type="text" v-model="form.searchid" class="form-control" id="student_id" name="student_id" placeholder="Student ID" required>
                        <div class="input-group-prepend" style="float:right">
		                      <button type="submit"><i class="fa fa-search blue"></i></button>
		                  
		                    </div>
                    </div>
                	</form>
                </div>
            
                <div class="studentName">
                <span class="loaderStyle text-center"  v-if="loading">
                    <i class="fa fa-cog fa-spin fa-2x indigo"></i>
                </span>
                    <label for="name">Name: <b class="student-name" v-if="studentDetails!=null">{{ studentDetails.name }} {{ studentDetails.nick_name }}</b></label>
                </div>
                
                <div class="dateStyle" >
                    <label for="" class="date-invoice">Date: <b>{{ moment().format('D-MMM-YYYY')}}</b></label>
                </div>
                <div class="invoiceNumber">
                    <label for="" class="invoice-number">Receipt NO: 
                      <b v-if="receiptId==null">00000</b>
                      <b v-if="receiptId!=null">{{ this.receiptId }}</b>
                    </label>
                </div>
	        </div>
			<caption>
				<div class="course-detail" v-if="studentDetails!=null">
                      
                       <span class="badge badge-success badge-pill">
                            Program: {{ studentDetails.programe }}
                        </span>
                        <span class="badge badge-primary badge-pill">
                            Academic: {{ studentDetails.academic }}
                        </span>
                        <span class="badge badge-danger badge-pill">
                            Level: {{ studentDetails.level }}
                        </span>
                        <span class="badge badge-success badge-pill">
                            Shift: {{ studentDetails.shift }}
                        </span>
                        <span class="badge badge-primary badge-pill">
                            Time: {{ studentDetails.time }}
                        </span>
                        <span class="badge badge-danger badge-pill">
                            Batch: {{ studentDetails.batch }}
                        </span>
                        <span class="badge badge-primary badge-pill">
                            Group: {{ studentDetails.group }}
                        </span>
                        <span class="badge badge-success badge-pill">
                            StartDate: {{ studentDetails.start_date }}
                        </span>
                        <span class="badge badge-danger badge-pill">
                            EndDate: {{ studentDetails.end_date }}
                        </span>
        </div>
			</caption>
	     <form @submit.prevent="studentFees==null ? savePayment() : extraPay()">         
        <create-payment
      			v-bind:programes="programes"
      			v-bind:levels="levels"
      			v-bind:schoolFees="schoolFees"
      			v-bind:studentDetails="studentDetails"
            v-bind:form="form"
            v-bind:feeButton="feeButton"
            v-bind:disable="disable"
        >
        </create-payment>          
	        <div class="panel-footer">   
              <button :disabled="form.busy" v-if="studentFees==null" type="submit" class="btn btn-primary  btn-sm">
                   <i class="fa fa-save fa-fw"></i> Save
              </button>
              <button :disabled="form.busy" v-if="studentFees!=null" type="submit" class="btn btn-primary btn-sm">
                   <i class="fa fa-save fa-fw"></i> Extra Pay
              </button>
              <span style="float:right">
                <button  type="button" class="btn btn-primary btn-sm" v-if="addNewCourseButton" @click="createNewCourse" title="Create Another Course">
                   <i class="fa fa-plus fa-fw"></i> Add New Course
                </button>
                <button  type="button" class="btn btn-primary btn-sm" v-else="addNewCourseButton" disabled title="Should Complete Existing Course Payment">
                   <i class="fa fa-plus fa-fw"></i> Add New Course
                </button>
                <input type="button" class="btn btn-primary pull-right btn-sm ml-2" style="float:right" value="Reset">
              </span>
	        </div>
      </form>
   

        </b-row>
          <div v-if="studentFees!=null">
             <pay-list
               :studentFees="studentFees"
               :studentTransactions="studentTransactions"
               :form="form"
               :totalPaid="totalPaid"
             >
             </pay-list>
          </div>
    
    </b-container>

   
</template>

<script>

var sprintf = require('sprintf-js').sprintf;

import CreateNewCourse from './model/CreateNewCourse.vue';
import CreateFee from './model/CreateFee.vue';
import CreatePayment from './components/CreatePayment.vue';
import PayList from './list/PayList.vue';
import ChoiceCourse from '../student/model/ChoiceCourse.vue';
import { BBreadcrumb, BContainer, BRow, BCol, BCard} from 'bootstrap-vue';

export default {
    components: {
        'b-breadcrumb': BBreadcrumb,
        'b-container': BContainer,
        'b-row': BRow,
        'b-col': BCol,
        'b-card': BCard,
        'create-new-course': CreateNewCourse,
        'create-fee': CreateFee,
        'create-payment': CreatePayment,
        'pay-list': PayList,
        'choice-course': ChoiceCourse,
    },
    data() {
        return {
            items: [
              {text: 'Home',to: '/dashboard'},
              {text: 'Student',to: '/student-create'},
              {text: 'Create',active: true}
            ],
            loading: false,
            studentDetails:null,
            feeTypes:{},
            schoolFees:{},
            studentFees: null,
            studentTransactions:{},
            receiptId:{},
            programes:{},
            levels:{},
            courses:{},
            feeButton: false,
            disable: false,
            addNewCourseButton:false,
            totalPaid:{},
            form : new Form({
               searchid: this.$route.query.studentId ? this.$route.query.studentId : null,
               user_id: window.user.id,
               s_fee_id:'',
               fee_id: '',
               student_id: '',
               programe_id: '',
               level_id: '',
               course_id: '',
               amount:'',
               discount:'',
               paid:'',
               due:'',
               fee:'',
               remark:'',
               description:'',
               balance:'',
               extraPaid:'',
            })
          }
    },
    methods: {
    	  getSchoolFee(value){
            this.schoolFees = value;
            this.form.fee_id = value.fee_id;
        },
        getCourse(value){
          this.form.course_id = value.course_id;
          this.courses = value;
        },
        getStudentDetailsByNewCourse(value){
          // console.log(value.schoolFees);
          this.form.fee_id = value.schoolFees.fee_id;
          this.form.student_id = value.studentDetails.student_id;
          this.form.level_id = value.studentDetails.level_id;
          this.loading = false;
          this.schoolFees = value.schoolFees;
          this.studentDetails = value.studentDetails;
          this.programes = value.programes;
          this.levels = value.levels;
          this.studentFees = null;
          this.disable = false;
          this.un_disabled_input();
        },
        searchId(){
          this.loading = true
          this.form.amount = null
          this.form.discount = null
          this.form.paid = null
          this.form.due = null
          this.form.remark = null
          this.form.description = null
          this.form.course_id = null
          if (this.form.searchid!=null) {
        	axios.get('api/payment/get/student/'+this.form.searchid)
           .then((data) => {
           		this.feeButton = true
           		this.feeTypes = data.data.feeTypes
              this.schoolFees = data.data.schoolFees
              if (data.data.balance==0) {
                this.addNewCourseButton = true
              } else {
                this.addNewCourseButton = false
              }

              if (data.data.studentDetails.length == 0) {
                this.studentDetails = null
              } else {
                this.studentDetails = data.data.studentDetails
              }
              if (data.data.studentFees.length == 0) {
                this.studentFees = null
                this.disable = false
                this.un_disabled_input()
              } else {
                this.studentFees = data.data.studentFees
                this.disable = true
                if (this.disable) {
                  this.disabled_input()
                }
              }
              if (data.data.studentTransactions.length == 0) {
                this.disable = false
              } else {
                this.studentTransactions = data.data.studentTransactions
                this.disable = true
                /*this.totalPaid = 0
                this.studentFees.forEach(studentFee => {
                  let found = this.studentTransactions.filter(studentTransaction => studentTransaction.s_fee_id == studentFee.s_fee_id)
                    if(found){
                        found.forEach(item => {
                            this.totalPaid +=  item.paid
                        })
                    }
                })*/
            
              }
              this.receiptId = data.data.receiptId;
              this.programes = data.data.programes;
              this.levels = data.data.levels;

           		this.form.fee_id = data.data.schoolFees.fee_id;
           		this.form.student_id = data.data.studentDetails.student_id;
           		this.form.level_id = data.data.studentDetails.level_id;
           	  this.loading = false;
           })
           .catch(()=>{
              Toast.fire({icon: 'error',title: 'Ops ! Search again'});
              this.loading = false;
           });
          } else {
            Toast.fire({icon: 'error',title: 'please search student ID!'});
          }
        },
        un_disabled_input(){
          $.each($('body').find('.d'), function(i,iteam){

            $(iteam).attr('disabled',false).css({'background':'#fff',
                                                'border':'1px solid #ccc'});
            $(iteam).attr('readonly',false);

          })
        },
        disabled_input(){
          $.each($('body').find('.d'), function(i,iteam){

            $(iteam).attr('disabled',true).css({'background':'#f5f5f5',
                                                'border':'1px solid #ccc'});
            $(iteam).attr('readonly',false);

          })
        },
        
        savePayment(){
          this.$Progress.start()
          this.form.post('api/payment/save-pay')
          .then((data)=>{
                Fire.$emit('loadData');
                Toast.fire({icon: 'success',title: 'Create in successfully'});
                this.$Progress.finish();
                
          })
          .catch(()=>{
            this.$Progress.fail();
             Toast.fire({icon: 'error',title: 'Ops !'});
          })
        },
        extraPay(){
          this.$Progress.start()
          this.form.post('api/payment/extra-pay')
          .then((data)=>{
                Fire.$emit('loadData');
                Toast.fire({icon: 'success',title: 'Create in successfully'});
                this.$Progress.finish();
                this.extraPaid = null;
             
          })
          .catch(()=>{
            this.$Progress.fail();
             Toast.fire({icon: 'error',title: 'Ops !'});
          })
        },
        createNewCourse(){
           $('#addNewCourse').modal('show');
        }
    },

    
    mounted() {
        console.log('Component mounted.')
    },
    created() {
        if (this.$route.query.studentId!=null) {
          this.searchId();
        }
        Fire.$on('loadData',()=>{
         this.searchId();
        });
    },
    computed:{
        
    }
}
</script>


<style scoped lang="scss">

.searchId{
	width:15%;
	float:left;
}
.studentName{
	width:40%;
	padding:5px 20px;
	float:left;
}
.dateStyle{
	width:25%;
	padding:5px 20px;
	float:left;
}
.invoiceNumber{
	width:20%;
	padding:5px 5px;
	float:left;
	text-align:right;
}
.panel-heading label {
	    font-weight: 300;
	    color: #fff;
}
.course-detail {
    margin: 0 10px 10px 10px;
    text-align: center;
}
</style>
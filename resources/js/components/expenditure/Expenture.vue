<template>
	<div class="container">
        <div class="row" v-if='$gate.isAdminOrAuthor()'>
          <div class="col-md-12 mt-5">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Expenditure</h3>

                <div class="card-tools">
                  
                </div>
                <div class="card-tools" style="float:right">
                    
                    <span class="loaderStyle" style="float:right;" v-if="loading">
                      <i class="fa fa-cog fa-spin fa-2x"></i>
                  </span>
              </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <form @submit.prevent="editmode ? updateDailyExpense() : createDailyExpense()">
                 <table class="table table-hover">
                 
                  <thead>
                    <tr>
                      <td width="25%">
                      		<input type="text" v-show="!editmode" class="form-control" :value="moment().format('D-MMM-YYYY')" disabled>
                      		<input type="text" v-show="editmode" v-model="form.date" class="form-control"  disabled>
	                       <!--  <date-pick
	                            v-model="form.date"
	                            :format="'YYYY-MM-DD'">
	                        </date-pick> -->
	                    
                      </td>
                      <td width="15%">
                      	<select v-model="form.e_type_id" class="form-control" :class="{ 'is-invalid': form.errors.has('e_type_id') }" v-if="expenseTypes">
                           <option v-for="expenseType in expenseTypes" :value="expenseType.e_type_id">{{ expenseType.expense_type }}</option>
                      	</select>
                      </td>
                      <td width="40%">
                      	<input type="text" v-model="form.description" :class="{ 'is-invalid': form.errors.has('description') }" class="form-control"  placeholder="Description">
                      </td>
                      <td width="15%">
                      	<input type="text" v-model="form.amount" :class="{ 'is-invalid': form.errors.has('amount') }" class="form-control"  placeholder="Amount">
                      </td>
                      <td width="5%">
                      	<button type="submit" class="btn btn-primary" v-show="!editmode">
                      		Save
                      	</button>
                      	<button type="submit" class="btn btn-primary" v-show="editmode">
                      		Update
                      	</button>
                      </td>
                    </tr>
                  </thead>
                 </table>
                  
                  <table style="border-collapse: collapse;" class="table-hover table-bordered customeTableStyle" id="list-student-fee">
			            <thead>
			              <tr class="text-center">
			                <th>No</th>
			                <th>Date</th>
			                <th>Total Expense($)</th>
			                <th>Action</th>
			              </tr>
			              
			            </thead>
			            <tbody id="tbody-student-fee" v-if="expenses" v-for="(expense, index) in expenses.data" :key="expense.expense_id" >
			                
			              <tr class="text-center">
			                <td>{{ index+1 }}</td>
			                <td>{{ expense.date | myDate }}</td>
			                <td>{{ expense.total_expense | currency }}</td>
			                <td style="text-align: center; width: 120px;">
			                  
			                  <a href="#" class="according-toggle" data-toggle="collapse" :data-target="'#demo' + index">
			                    <i class="fa fa-eye green"></i>
			                  </a> / 
			                    
			                  <a href="#" @click="expenseDelete(expense.expense_id)" >
			                    <i class="fa red fa-trash"></i>
			                  </a> 

			                </td>
			              </tr>
			              <tr>
			                <th colspan="9" class="hiddenrow">
			                   <div class="'accordian-body collapse" :id="'demo'+index">
			                    <table class="customeTableStyleUnderRow">
			                      <thead>
						              <tr>
						                <th>#</th>
						                <th>Date</th>
						                <th>Expense By</th>
						                <th>Type</th>
						                <th>Description</th>
						                <th>Amount($)</th>
						                <th>Action</th>
						              </tr>
						              
						            </thead>

			                      <tbody v-if="dailyExpenses">
			                
						              <tr v-for="(dailyExpense,index) in dailyExpenses" v-if="expense.expense_id == dailyExpense.expense_id">
						                <td>{{ index+1 }}</td>
						                <td>{{ dailyExpense.date | dateTime }}</td>
						                <td>{{ dailyExpense.userType  }}</td>
						                <td>{{ dailyExpense.expenseType }}</td>
						                <td>{{ dailyExpense.description }}</td>
						                <td>{{ dailyExpense.amount | currency }}</td>
						                <td style="text-align: center; width: 120px;">
						                  <a href="#" @click="editDailyExpense(dailyExpense.d_e_id)">
						                    <i class="fa fa-edit pink fa-fw"></i>
						                  </a> /
						                    
						                  <a href="#" @click="deleteDailyExpense(dailyExpense.d_e_id)" >
						                    <i class="fa red fa-trash"></i>
						                  </a> 

						                </td>
						              </tr>
			                        

			                      </tbody>
			                    </table>

			                  </div>
			                </th>
			              </tr>
			            </tbody>
			       </table>
				</form>
              </div>
              <div class="card-footer">
                <pagination :data="expenses" @pagination-change-page="getResults"></pagination>

              </div>
            </div>
            <div class="card">
              <div class="card-header">Table</div>
              <div class="card-body">
                <table>
                  <tr>
                    <td align="center">Dept</td>
                    <td>Science</td>
                    <td>Humanities</td>
                    <td>Business Staduies</td>
                  </tr>
                  <tr>
                    <td rowspan="2" align="center">Subject</td>
                    <td colspan="2" align="center">Eng</td>
                    <td rowspan="2"><br></td>
                  </tr>
                  <tr>
                    <td colspan="2" align="center">ICT</td>
                  </tr>
                </table>
                <table>
                  <tr>
                    <td>Dept</td>
                    <td colspan="2">Sub</td>
                  </tr>
                  <tr>
                    <td>Science</td>
                    <td colspan="1">ICT</td>
                    <td colspan="1">Eng</td>

                  </tr>
                  <tr>
                    <td>humanities</td>

                  </tr>
                  <tr>
                    <td>Buisness</td>
                    <td colspan="2"><br></td>
                  </tr>
                </table>
              </div>
              <div class="card-header">
                end
              </div>
            </div>
          </div>
          </div>

        <div v-if='!$gate.isAdminOrAuthor()'>
          <not-found></not-found>
        </div>
    </div>
</template>
<script>
import DatePick from 'vue-date-pick';

    export default {
    	components:{
            DatePick,

    	},
        data() {
            return {
                editmode: false,
                loading: false,
                expenseTypes :{},
                expenses :{},
                dailyExpenses:{},
                form : new Form({
                     d_e_id: '',
                     expense_id: '',
                     date: '',
                     user_id: window.user.id,
                     e_type_id: null,
                     description: null,
                     amount: null,
                })
            }
        },
        methods: {
        	getResults(page = 1) {
              axios.get('api/expenditure?page=' + page)
                .then(response => {
                  this.expenses = response.data;
                });
            },
        	createDailyExpense(){
        		if (this.$gate.isAdminOrAuthor()) {
	              this.$Progress.start();
        		  this.form.date = moment().format('D-MMM-YYYY');

	              this.form.post('api/expenditure')
	              .then((data)=>{

	                    this.form.reset();
	                    Fire.$emit('loadData');
	                    Toast.fire({
	                                icon: 'success',
	                                title: 'Create in successfully'
	                              });
	                    this.$Progress.finish();
	              })
	              .catch(()=>{
	                this.$Progress.fail();
	                 Toast.fire({
	                            icon: 'error',
	                            title: 'Ops !'
	                          });
	              })
               }
        	},
        	editDailyExpense(id){
        		if (this.$gate.isAdminOrAuthor()) {
              	  this.loading=true;
		          axios.get('api/expenditure/'+id)
		          .then((data)=>{
              	    this.editmode=true;
		            this.form.fill(data.data);
		            this.loading=false;
		          })
		          .catch(()=>{
		            Toast.fire({icon: 'error',title: 'Ops !'});
		            this.loading=false;
		          })
               }
        	},
        	updateDailyExpense(){
        		if (this.$gate.isAdminOrAuthor()) {
	              this.$Progress.start();
	              this.form.put('api/expenditure/'+this.form.d_e_id)
	              .then((data)=>{
              	    this.editmode=false;

	                    this.form.reset();
	                    Fire.$emit('loadData');
	                    Toast.fire({
	                                icon: 'success',
	                                title: 'Update in successfully'
	                              });
	                    this.$Progress.finish();
	              })
	              .catch(()=>{
	                this.$Progress.fail();
	                 Toast.fire({
	                            icon: 'error',
	                            title: 'Ops !'
	                          });
	              })
               }
        	},
        	expenseDelete(id) {
        		if (this.$gate.isAdmin()) {
                Swal.fire({
                  title: 'Are you sure?',
                  text: "You won't be able to revert this!",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                  if (result.value) {

                    this.form.delete('api/expenditure/expenseDelete/'+id).then(()=>{
                        Swal.fire(
                          'Deleted!',
                          'Your file has been deleted.',
                          'success'
                        )
                      Fire.$emit('loadData');
                    })
                    .catch(()=>{
                      Swal.fire('Failed','Ops Something Wrong','warning');
                    })
                    
                  }
                })
            	}
            },
        	deleteDailyExpense(id) {
        		if (this.$gate.isAdmin()) {
                Swal.fire({
                  title: 'Are you sure?',
                  text: "You won't be able to revert this!",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                  if (result.value) {

                    this.form.delete('api/expenditure/'+id).then(()=>{
                        Swal.fire(
                          'Deleted!',
                          'Your file has been deleted.',
                          'success'
                        )
                      Fire.$emit('loadData');
                    })
                    .catch(()=>{
                      Swal.fire('Failed','Ops Something Wrong','warning');
                    })
                    
                  }
                })
            	}
            },
            
            loadExpenses(){
              if (this.$gate.isAdminOrAuthor()) {
                this.loading = true;
                // axios.get('api/user').then(({ data }) => (this.users = data.data));
                //for pagination
                axios.get('api/expenditure').then(( {data} ) => (
                	this.expenseTypes = data.expenseTypes,
                	this.expenses=data.expenses,
                	this.dailyExpenses=data.dailyExpenses,
                	this.loading = false
                ));
               }
            }

        },
        created() {
           this.loadExpenses();

           Fire.$on('loadData',()=>{
              this.loadExpenses();
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

table {
  border: 1px solid black;
}
table tr{
  border: 1px solid black;
}
table tr td {
  border: 1px solid black;
}


</style>
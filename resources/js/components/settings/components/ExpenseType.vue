<template>
  
          <div class="card card-primary mt-3">
              <div class="card-header">  Expense Type
                  <div class="card-tools" style="float:right">
             			<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                  </button>
                      <span class="loaderStyle ml-2" style="float:right;" v-if="loading">
                          <i class="fa fa-cog fa-spin fa-2x white"></i>
                      </span>
                  </div>
              </div>
              <div class="card-body" style="display:none">
            <form @submit.prevent="editmode ? updateExpenseType() : createExpenseType()" >

              <table class="table">
              	<tr>
              	   <td>
                      <input type="text" class="form-control" v-model="expenseType.expense_type" placeholder="Expense Type">
                    </td>
              	   <td>
                      <button v-show="!editmode" type="submit" class="btn btn-primary">Create</button>
                      <button v-show="editmode" type="submit" class="btn btn-primary">Update</button>
                    </td>
                 </tr>
              </table>
             </form>
              <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Type</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>

                    <tr v-for="(expenseType,index) in expenseTypes">
                      <td>
                        {{ index+1 }}
                      </td>
                      <td>{{ expenseType.expense_type }}</td>
                      <td>
                      	<a href="#" @click='editExpenseType(expenseType.e_type_id)'>
                      		<i class="fa fa-edit indigo"></i>
                      	</a> / 
                      	<a href="#" @click='deleteExpenseType(expenseType.e_type_id)'>
                      		<i class="fa fa-trash red"></i>
                      	</a>
                      </td>
                    </tr>
                    
                  </tbody>
                </table>
              

              </div>
              <div class="card-footer" style="display:none">
              </div>
          </div>
   

        
</template>
<script>


import { BCard} from 'bootstrap-vue'
export default {
	name: "expense-type",
        components: {
            'b-card': BCard
        },
        prop:{
        	expenseTypes:{}
        },
        data() {
            return {
              editmode: false,
              loading: false,
              expenseTypes:{},
              expenseType: new Form({
                  e_type_id:null,
                  expense_type:null
              })

            }
        },
        methods: {
          
          createExpenseType(){
          	if (this.$gate.isAdminOrAuthor()) {
          	  this.$Progress.start()
              this.expenseType.post('api/settings/create_expense_type')
              .then(()=>{
                Fire.$emit('loadExpenseTypeData');
                Toast.fire({
                            icon: 'success',
                            title: 'Create in successfully'
                          });
                this.$Progress.finish();
          	     this.expenseType.reset();

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
          editExpenseType(id){
          	  this.editmode = true;
              this.loading = true;
              this.expenseType.reset();
	            axios.get('api/settings/get_expense_type_by_id/'+id)
	            .then((data) => {
	                this.expenseType.fill(data.data);
	                this.loading = false;
	            })
	            .catch(()=>{
	                this.loading = false
	            });
              
          },
          updateExpenseType(){
          	if (this.$gate.isAdminOrAuthor()) {
          	  this.$Progress.start()
              this.expenseType.post('api/settings/update_expense_type')
              .then(()=>{
                Fire.$emit('loadExpenseTypeData');
                Toast.fire({
                            icon: 'success',
                            title: 'Create in successfully'
                          });
                this.$Progress.finish();
          	     this.editmode = false;
          	     this.expenseType.reset();

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
          deleteExpenseType(id) {
          	     this.expenseType.reset();

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

                    this.expenseType.delete('api/settings/delete_expense_type/'+id).then(()=>{
                        Swal.fire(
                          'Deleted!',
                          'Expense Type has been deleted.',
                          'success'
                        )
                      Fire.$emit('loadExpenseTypeData');
          	     this.expenseType.reset();

                    })
                    .catch(()=>{
                      Swal.fire('Failed','Ops Something Wrong','warning');
                    })
                    
                  }
                })
          },
          
          loadExpesData(){
          	if (this.$gate.isAdminOrAuthor()) {
                    this.loading = true
                    axios.get('api/settings/get_data_expense')
                    .then((data) => {
                        this.expenseTypes = data.data;
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
           this.loadExpesData();
          
          Fire.$on('loadExpenseTypeData',()=>{
           this.loadExpesData();

          });

        },
        computed:{
            
        }
    }
</script>


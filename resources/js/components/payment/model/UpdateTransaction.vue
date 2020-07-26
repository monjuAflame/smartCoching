  <template>
        <!-- editfee modal -->
    <div class="modal fade" id="editTransaction" tabindex='-1' role='dialog' aria-labelledby='addNewModalLabel' aria-hidden='true'>
        <div class="modal-dialog modal-dialog-centered" role='document'>
            <div class="modal-content">

                <div class="modal-header">
                  <span class="loaderStyle text-center"  v-if="loading">
                    <i class="fa fa-cog fa-spin fa-2x"></i>
                  </span>
                    <h5 class="modal-title"  id="addNewModalLabel">Update Transaction</h5>
                    <button class="close" type='button' data-dismiss='modal' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>

            <form @submit.prevent="updateTransaction()" id="form-transaction-create">
                <div class="modal-body">
                  
                    <div class="table-responsive">
             
                        <table class="table-fee" style="width:100%">
                          <tr>
                            <td><label>Transact Date</label></td>
                            <td>
                              <input type="text" v-model="transactionForm.transact_date" class="form-control" disabled>
                            </td>
                          </tr>
                          <tr>
                            <td><label>Username</label></td>
                            <td>
                              <input type="text" v-model="transactionForm.name" class="form-control"  disabled>
                            </td>
                          </tr>
                          <tr>
                            <td><label>Paid($)</label></td>
                            <td>
                              <input type="text" v-model="transactionForm.paid"  class="form-control">
                            </td>
                          </tr>
                          <tr>
                            <td><label>Remark</label></td>
                            <td>
                              <input type="text" v-model="transactionForm.remark" class="form-control">
                            </td>
                          </tr>
                          <tr>
                            <td><label>Description</label></td>
                            <td>
                              <input type="text" v-model="transactionForm.description" class="form-control">
                              
                            </td>
                          </tr>
                        </table>
          
            </div>
                 
                </div>

                <div class="modal-footer">
                    <button class="btn btn-danger" data-dismiss='modal'>
                        <i class="fa fa-trash fa-fw"></i>
                        Close
                    </button>
                    <button :disabled="form.busy" type="submit" class="btn btn-primary">
                        <i class="fa fa-save fa-fw"></i>
                        Save
                    </button>
                </div>
            </form>

            </div>
        </div>
    </div>
  </template>
    <!-- end academic modal -->
<script>
export default {
  name: "updateTransaction",
  components: {
    
  },
  props: {
    transactionForm:{},
    loading:{}
  },

  data(){
      return {
        form:{
          
        }
      }
  },

  
  methods:{
   
    updateTransaction () {
      
      this.transactionForm.post('api/payment/update-transaction')
      .then((data)=>{
        this.$Progress.start();

        this.transactionForm.reset();
        Fire.$emit('loadData');
        Toast.fire({
                    icon: 'success',
                    title: 'Create in successfully'
                  });
        this.$Progress.finish();
        $('#editTransaction').modal('hide');
      })
      .catch(()=>{
          this.$Progress.fail();
           Toast.fire({
                      icon: 'error',
                      title: 'Ops !'
            });
      })
        
    },
   

  
  },
  mounted() {

  },
  created(){
    
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">

.table-fee{
    border: none;
}
.table-fee tr ,td ,th {
  border: none;
}

</style>
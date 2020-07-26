  <template>
        <!-- editfee modal -->
    <div class="modal fade" id="editFee" tabindex='-1' role='dialog' aria-labelledby='addNewModalLabel' aria-hidden='true'>
        <div class="modal-dialog modal-dialog-centered" role='document'>
            <div class="modal-content">

                <div class="modal-header">
                    <span class="loaderStyle text-center"  v-if="loading">
                    <i class="fa fa-cog fa-spin fa-2x"></i>
                      </span>
                  <h5 class="modal-title"  id="addNewModalLabel">Update Fee</h5>
                    <button class="close" type='button' data-dismiss='modal' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>

            <form @submit.prevent="UpdateFee()" id="form-fee-create">
                <div class="modal-body">
                  
                    <div class="table-responsive">
             
              <table class="table-fee" style="width:100%">
                <tr>
                  <td><label>Programe</label></td>
                  <td>
                    <input type="text" v-model="studentFeeForm.programe" class="form-control" disabled>
                  </td>
                </tr>
                <tr>
                  <td><label>Level</label></td>
                  <td>
                    <input type="text" v-model="studentFeeForm.level" class="form-control"  disabled>
                  </td>
                </tr>
                <tr>
                  <td><label>Course Fee($)</label></td>
                  <td>
                    <input type="text" v-model="studentFeeForm.school_fee" id="school_fee" class="form-control" disabled>
                  </td>
                </tr>
                <tr>
                  <td><label>Student Amount($)</label></td>
                  <td>
                    <input type="text" v-model="studentFeeForm.student_amount" id="student_amount" class="form-control" @keyup="amount">
                  </td>
                </tr>
                <tr>
                  <td><label>Discount(%)</label></td>
                  <td>
                    <input type="text" v-model="studentFeeForm.discount" id="discount" class="form-control" disabled>
                    
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
  name: "updateFee",
  components: {
    
  },
  props: {
    studentFeeForm:{},
    loading:{}
  },

  data(){
      return {
        form:{
          
        }
      }
  },

  
  methods:{
    amount(){
      let fee =  this.studentFeeForm.school_fee
      let amount =  this.studentFeeForm.student_amount
      let discount = 0
      if (amount=='') {
          discount = (((fee - amount)  / fee) * 100)
          this.studentFeeForm.discount = discount
      } else {
          discount = (((fee - amount)  / fee) * 100)
          this.studentFeeForm.discount = discount
      }
      if (parseFloat(amount) > parseFloat(fee)) {
        document.getElementById('discount').style.color = 'red';
      } else{
        document.getElementById('discount').style.color = 'green';
      }
    },
    UpdateFee () {
      
      this.studentFeeForm.post('api/payment/update-student-fee')
      .then((data)=>{
        this.$Progress.start();
        this.studentFeeForm.reset();
        Fire.$emit('loadData');
        Toast.fire({
                    icon: 'success',
                    title: 'Create in successfully'
                  });
        this.$Progress.finish();
        $('#editFee').modal('hide');
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
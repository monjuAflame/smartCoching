  <template>
        <!-- academic modal -->
    <div class="modal fade" id="addFee" tabindex='-1' role='dialog' aria-labelledby='addNewModalLabel' aria-hidden='true'>
        <div class="modal-dialog modal-dialog-centered" role='document'>
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title"  id="addNewModalLabel">Create Fee</h5>
                    <button class="close" type='button' data-dismiss='modal' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>

            <form @submit.prevent="CreateFee()" id="form-fee-create">
                <div class="modal-body">
                  
                    <div class="table-responsive">
             
              <table class="table-fee" style="width:100%">
                <tr>
                  <td><label>Fee Type</label></td>
                  <td>
                    <select id="fee_type_id" v-if="feeTypes" class="form-control" disabled>
                      
                        <option v-for="feeType in feeTypes" :value="feeType.fee_type_id" id="fee_type_id">{{ feeType.fee_type }}</option>
                      
                    </select>
                  </td>
                </tr>
                <tr>
                  <td><label>Fee Type</label></td>
                  <td>
                    <input type="text" class="form-control" id="fee_heading" value="Fees" disabled>
                  </td>
                </tr>
                <tr>
                  <td><label>Academic Year</label></td>
                  <td>
                    <input class="form-control" type="text" v-if="studentDetails!=null" v-model="studentDetails.academic" disabled>
                    <input type="hidden" v-if="studentDetails!=null" :value="studentDetails.academic_id" id="academic_id">
                  </td>
                </tr>
                <tr>
                  <td><label>Program</label></td>
                  <td>
                    <input type="text" v-if="studentDetails!=null" v-model="studentDetails.programe" class="form-control" disabled>
                  </td>
                </tr>
                <tr>
                  <td><label>Level</label></td>
                  <td>
                    <input class="form-control" v-if="studentDetails!=null" type="text" v-model="studentDetails.level" disabled>
                    <input type="hidden" v-if="studentDetails!=null" :value="studentDetails.level_id" id="level_id" required>
                  </td>
                </tr>
                <tr>
                  <td><label>Course Fee($)</label></td>
                  <td>
                    <input type="text" name="amount" id="amount" placeholder="Amount" class="form-control" autocomplete="off" required>
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
  name: "createFee",
  components: {
    
  },
  props: {
    feeTypes:{},
    studentDetails:{},
  },

  data(){
      return {
        form: new Form({

        })
      }
  },

  
  methods:{
    CreateFee () {
        const fee_type_id = document.getElementById('fee_type_id').value;
        const fee_heading = document.getElementById('fee_heading').value;
        const academic_id = document.getElementById('academic_id').value;
        const level_id = document.getElementById('level_id').value;
        const amount = document.getElementById('amount').value;

        let data = {fee_type_id: fee_type_id,fee_heading: fee_heading,academic_id: academic_id,level_id: level_id,amount: amount,}
            
            this.$Progress.start()
            axios.post('api/payment/create/fee', data )
           .then(response => {
              var schoolFee = response.data;
              this.$emit('schoolFee', schoolFee);
              Toast.fire({ icon: 'success',title: 'Create in successfully'});
              this.$Progress.finish();
              $('#addFee').modal('hide'); 
              document.getElementById('amount').value = "";

            })
           .catch(()=>{
            this.$Progress.fail();
                 Toast.fire({icon: 'error',title: 'Ops !'});
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
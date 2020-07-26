  <template>
    <!-- shift modal -->
    <div class="modal fade" id="addShift" tabindex='-1' role='dialog' aria-labelledby='addNewModalLabel' aria-hidden='true'>
        <div class="modal-dialog modal-dialog-centered" role='document'>
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title"  id="addNewModalLabel">Add New Shift</h5>
                    <button class="close" type='button' data-dismiss='modal' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>

            <form @submit.prevent="CreateShift()" id="form-shift-create">
                <div class="modal-body">
                  
                    
                    <div class="form-group">
                        <input v-model="form.shift" type="text" name="shift" placeholder="Shift" class="form-control" :class="{ 'is-invalid': form.errors.has('shift') }">
                          <has-error :form="form" field="shift"></has-error>
                    </div>
                    <div class="form-group">
                        <input v-model="form.description" type="text" name="description" placeholder="Description" class="form-control" :class="{ 'is-invalid': form.errors.has('description') }">
                          <has-error :form="form" field="description"></has-error>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-danger" data-dismiss='modal'>
                        <i class="fa fa-trash fa-fw"></i>
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save fa-fw"></i>
                        Save
                    </button>
                </div>
            </form>

            </div>
        </div>
    </div>
    <!-- end shift modal -->
  </template>
<script>
export default {
  name: "shift",
  components: {
    
  },
  props: {
    msg: String
  },

  data(){
      return {
        form : new Form({
            shift: '',
            description: '',
        })
      }
  },

  
  methods:{
     CreateShift () {
              this.$Progress.start()
              this.form.post('api/course/shift/add')
              .then((data)=>{
                    // console.log(data.data);
                    $('#addShift').modal('hide');
                    this.form.reset();
                    $('#shift_id').append($('<option/>',{
                        value : data.data.shift_id,
                        text  : data.data.shift
                    }));
                    
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
            },
  
  },
  created(){
  

  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">



</style>
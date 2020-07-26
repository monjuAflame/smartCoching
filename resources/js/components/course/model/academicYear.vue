  <template>
        <!-- academic modal -->
    <div class="modal fade" id="addAcademic" tabindex='-1' role='dialog' aria-labelledby='addNewModalLabel' aria-hidden='true'>
        <div class="modal-dialog modal-dialog-centered" role='document'>
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title"  id="addNewModalLabel">Add New Academic</h5>
                    <button class="close" type='button' data-dismiss='modal' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>

            <form @submit.prevent="CreateAcademicYear()" id="form-academic-create">
                <div class="modal-body">
                  
                    <div class="form-group">
                        <input v-model="form.academic" type="text" name="academic"
                          placeholder="Academic" class="form-control" :class="{ 'is-invalid': form.errors.has('academic') }">
                          <has-error :form="form" field="academic"></has-error>
                        
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
  name: "academic",
  components: {
    
  },
  props: {
    msg: String
  },

  data(){
      return {
        form : new Form({
            academic: '',
        })
      }
  },

  
  methods:{
     CreateAcademicYear () {
              this.$Progress.start()
              this.form.post('api/course/academic/add')
              .then((data)=>{
                    // console.log(data.data);
                    $('#addAcademic').modal('hide');
                    this.form.reset();
                    $('#academic_id').append($('<option/>',{
                                value : data.data.academic_id,
                                text  : data.data.academic
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
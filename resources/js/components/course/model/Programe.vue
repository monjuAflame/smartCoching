  <template>
     <!-- programe modal -->
    <div class="modal fade" id="addPrograme" tabindex='-1' role='dialog' aria-labelledby='addNewModalLabel' aria-hidden='true'>
        <div class="modal-dialog modal-dialog-centered" role='document'>
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title"  id="addNewModalLabel">Add New Programe</h5>
                    <button class="close" type='button' data-dismiss='modal' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>

            <form @submit.prevent="CreateProgram()" id="form-programe-create">
                <div class="modal-body">
                  
                    <div class="form-group">
                        <input v-model="form.programe" type="text" name="programe" placeholder="Programe" class="form-control" :class="{ 'is-invalid': form.errors.has('programe') }" >
                          <has-error :form="form" field="programe"></has-error>
                    </div>
                    <div class="form-group">
                        <input v-model="form.description" type="text" name="description"
                          placeholder="Description" class="form-control" :class="{ 'is-invalid': form.errors.has('description') }">
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
    <!-- end programe modal -->
  </template>
<script>
export default {
  name: "programe",
  components: {
    
  },
  props: {
    msg: String
  },

  data(){
      return {
        form : new Form({
       
              programe: '',
              description: '',
                     
        })
      }
  },

  
  methods:{
    CreateProgram () {

              this.$Progress.start()
              this.form.post('api/course/programe/add')
              .then((data)=>{

                    this.form.reset();
                    $('#programe_id').append($('<option/>',{
                                value : data.data.programe_id,
                                text  : data.data.programe
                    }));
                    let programe = $('#form-level-create').find('#programe_id');
                    $(programe).append($('<option/>',{
                                value : data.data.programe_id,
                                text  : data.data.programe
                    }));

                    $('#addPrograme').modal('hide');
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
  <template>
 
    <!-- level modal -->
    <div class="modal fade" id="addLevel" tabindex='-1' role='dialog' aria-labelledby='addNewModalLabel' aria-hidden='true'>
        <div class="modal-dialog modal-dialog-centered" role='document'>
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title"  id="addNewModalLabel">Add New Level</h5>
                    <button class="close" type='button' data-dismiss='modal' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>

            <form @submit.prevent="CreateLevel()" id="form-level-create">
                <div class="modal-body">
                  
                    <div class="form-group">
                        <select class="form-control" v-model="form.programe_id" name="programe_id" id="programe_id" :class="{ 'is-invalid': form.errors.has('programe_id') }">
                            <option>-----</option>
                        </select>
                        <has-error :form="form" field="programe_id"></has-error>
                    </div>
                    <div class="form-group">
                        <input v-model="form.level" type="text" name="level" placeholder="Level" class="form-control" :class="{ 'is-invalid': form.errors.has('level') }">
                          <has-error :form="form" field="level"></has-error>
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
    <!-- end level modal -->
  </template>

<script>
export default {
  name: "level",
  components: {
    
  },
  props: {
    msg: String
  },

  data(){
      return {
        form : new Form({
            programe_id:'',
            level: '',
            description: '',
        })
      }
  },

  
  methods:{
      CreateLevel () {

              this.$Progress.start()
              this.form.post('api/course/level/add')
              .then((data)=>{

                    $('#addLevel').modal('hide');
                    this.form.reset();
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
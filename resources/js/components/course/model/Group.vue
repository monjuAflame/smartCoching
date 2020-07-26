  <template>
    <!-- group modal -->
    <div class="modal fade" id="addGroup" tabindex='-1' role='dialog' aria-labelledby='addNewModalLabel' aria-hidden='true'>
        <div class="modal-dialog modal-dialog-centered" role='document'>
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title"  id="addNewModalLabel">Add New Group</h5>
                    <button class="close" type='button' data-dismiss='modal' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>

            <form @submit.prevent="CreateGroup()" id="form-group-create">
                <div class="modal-body">
                  
                    
                    <div class="form-group">
                        <input v-model="form.group" type="text" name="group" placeholder="Group" class="form-control" :class="{ 'is-invalid': form.errors.has('group') }">
                          <has-error :form="form" field="group"></has-error>
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
    <!-- end group modal -->
  </template>
<script>
export default {
  name: "group",
  components: {
    
  },
  props: {
    msg: String
  },

  data(){
      return {
        form : new Form({
            group: '',
            description: '',
        })
      }
  },

  
  methods:{
     CreateGroup () {
          this.$Progress.start()
          this.form.post('api/course/group/add')
          .then((data)=>{
                // console.log(data.data);
                $('#addGroup').modal('hide');
                this.form.reset();
                $('#group_id').append($('<option/>',{
                    value : data.data.group_id,
                    text  : data.data.group
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
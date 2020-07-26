<template>
  
          <div class="card card-primary mt-3">
              <div class="card-header">  Fee Type

                  <div class="card-tools" style="float:right">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                  </button>
                      <span class="loaderStyle ml-2" style="float:right;" v-if="loading">
                          <i class="fa fa-cog fa-spin fa-2x white"></i>
                      </span>
                  </div>
              </div>
              <div class="card-body" style="display:none">
            <form @submit.prevent="editmodeFeeType ? updateFeeType() : createFeeType()" >

              <table class="table">
                <tr>
                   <td>
                      <input type="text" class="form-control" v-model="feeType.fee_type" placeholder="Fee Type">
                    </td>
                   <td>
                      <button v-show="!editmodeFeeType" type="submit" class="btn btn-primary">Create</button>
                      <button v-show="editmodeFeeType" type="submit" class="btn btn-primary">Update</button>
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

                    <tr v-for="(feeType,index) in feeTypes">
                      <td>
                        {{ index+1 }}
                      </td>
                      <td>{{ feeType.fee_type }}</td>
                      <td>
                        <a href="#" @click='editFeeType(feeType.fee_type_id)'>
                          <i class="fa fa-edit indigo"></i>
                        </a> / 
                        <a href="#" @click='deleteFeeType(feeType.fee_type_id)'>
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
	name: "fee-type",
        components: {
            'b-card': BCard
        },
        prop:{
        	feeTypes:{}
        },
        data() {
            return {
              editmodeFeeType: false,
              loading: false,
              feeTypes:{},
              feeType: new Form({
                  fee_type_id:null,
                  fee_type:null
              })

            }
        },
        methods: {
          
          createFeeType(){
            if (this.$gate.isAdminOrAuthor()) {
              this.$Progress.start()
              this.feeType.post('api/settings/create_fee_type')
              .then(()=>{
                Fire.$emit('loadData');
                Toast.fire({
                            icon: 'success',
                            title: 'Create in successfully'
                          });
                this.$Progress.finish();
                 this.feeType.reset();
                
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
          editFeeType(id){
              this.editmodeFeeType = true;
              this.loading = true;
              this.feeType.reset();
              axios.get('api/settings/get_fee_type_by_id/'+id)
              .then((data) => {
                  this.feeType.fill(data.data);
                  this.loading = false;
              })
              .catch(()=>{
                  this.loading = false
              });
              
          },
          updateFeeType(){
            if (this.$gate.isAdminOrAuthor()) {
              this.$Progress.start()
              this.feeType.post('api/settings/update_fee_type')
              .then(()=>{
                Fire.$emit('loadData');
                Toast.fire({
                            icon: 'success',
                            title: 'Create in successfully'
                          });
                this.$Progress.finish();
                 this.editmodeFeeType = false;
                 this.feeType.reset();

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
          deleteFeeType(id) {
                 this.feeType.reset();

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

                    this.feeType.delete('api/settings/delete_fee_type/'+id).then(()=>{
                        Swal.fire(
                          'Deleted!',
                          'Fee Type has been deleted.',
                          'success'
                        )
                      Fire.$emit('loadData');
                 this.feeType.reset();

                    })
                    .catch(()=>{
                      Swal.fire('Failed','Ops Something Wrong','warning');
                    })
                    
                  }
                })
          },
          loadFeeData(){
            if (this.$gate.isAdminOrAuthor()) {
                    this.loading = true
                    axios.get('api/settings/get_data_fee')
                    .then((data) => {
                        this.feeTypes = data.data;
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
           this.loadFeeData();
          Fire.$on('loadData',()=>{
           this.loadFeeData();

          });

        },
        computed:{
            
        }
    }
</script>


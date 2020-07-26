<template>
  
          <div class="card card-primary mt-3">
              <div class="card-header">  Course Fee
                  <div class="card-tools" style="float:right">
             			<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                  </button>
                      <span class="loaderStyle ml-2" style="float:right;" v-if="loading">
                          <i class="fa fa-cog fa-spin fa-2x white"></i>
                      </span>
                  </div>
              </div>
              <div class="card-body" style="display:none">
            <form @submit.prevent="editmode ? updateCourseFee() : createCourseFee()" >

              <table class="table">
              	<tr>
              	   <td>
                      <label for="academic-year">Academic Year</label>
                    <div class="input-group" v-if="academics">
                      <select v-model="form.academic_id"  name="academic_id" id="academic_id" class="form-control" :class="{ 'is-invalid': form.errors.has('academic_id') }" v-if="!editmode">
                           <option v-for="academic in academics" :value="academic.academic_id">{{ academic.academic }}</option>
                      </select>
                      <select v-model="form.academic_id"  name="academic_id" id="academic_id" class="form-control" v-if="editmode" disabled="">
                           <option v-for="academic in academics" :value="academic.academic_id">{{ academic.academic }}</option>
                      </select>
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="add-more-academic" @click='academicModel'><i class="fa fa-plus"></i></span>
                      </div>
                    </div>
                    </td>
                    <td>
                      <label for="programe">Programe</label>
                    <div class="input-group" v-if="programes">
                        <select name="programe_id"  v-model="form.programe_id" id="programe_id" class="form-control" :class="{ 'is-invalid': form.errors.has('programe_id') }"  @change='getLevel()' v-if="!editmode">
                            <option>-----</option>
                           <option v-for="programe in programes" :value="programe.programe_id">{{ programe.programe }}</option>
                        </select>
                        <select name="programe_id"  v-model="form.programe_id" id="programe_id" class="form-control" v-if="editmode" disabled>
                            <option>-----</option>
                           <option v-for="programe in programes" :value="programe.programe_id">{{ programe.programe }}</option>
                        </select>
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="add-more-program" @click='programeModel'><i class="fa fa-plus"></i></span>
                      </div>
                    </div>
                    </td>
                    <td>
                      <label for="level">Level</label>
                    <div class="input-group">
                        <select v-model="form.level_id" v-if="!editmode" name="level_id" id="level_id" class="form-control" :class="{ 'is-invalid': form.errors.has('level_id') }">
                            <option value="">-----</option>
                        </select>
                        <select v-model="form.level_id" v-if="editmode" id="level_id" class="form-control" disabled>
                           <option v-for="level in levels" :value="level.level_id">{{ level.level }}</option>
                            <option value="">-----</option>
                        </select>
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="add-more-level" @click='levelModel'><i class="fa fa-plus"></i></span>
                        </div>
                    </div>
                    </td>
                    <td>
                      <label for="feety">Fee Type</label>
                      
                    <select id="fee_type_id" v-model="form.fee_type_id" class="form-control" :class="{ 'is-invalid': form.errors.has('fee_type_id') }" v-if="!editmode">
                      
                        <option v-for="feeType in feeTypes" :value="feeType.fee_type_id" id="fee_type_id">{{ feeType.fee_type }}</option>
                      
                    </select>
                    <select id="fee_type_id" v-model="form.fee_type_id" class="form-control" v-if="editmode" disabled>
                      
                        <option v-for="feeType in feeTypes" :value="feeType.fee_type_id" id="fee_type_id">{{ feeType.fee_type }}</option>
                      
                    </select>
                  
                    </td>
                    <td>
                      <label for="feety">Amount($)</label>

                      <input type="text" class="form-control" v-model="form.amount" placeholder="Amount" :class="{ 'is-invalid': form.errors.has('amount') }" v-if="!editmode">
                      <input type="text" class="form-control" v-model="form.amount" placeholder="Amount" v-if="editmode" disabled>
                    </td>
              	   <td>
                      <button v-show="!editmode" type="submit" class="btn btn-primary mt-4">Create</button>
                      <button v-show="editmode" disabled type="submit" class="btn btn-primary mt-4">Update</button>
                    </td>
                 </tr>
              </table>
             </form>
              <table class="table table-hover text-center">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Academic Year</th>
                      <th>Programe</th>
                      <th>Level</th>
                      <th>Fee Type</th>
                      <th>Amount</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>

                    <tr v-for="(courseFee,index) in courseFees">
                      <td>
                        {{ index+1 }}
                      </td>
                      <td>{{ courseFee.academic }}</td>
                      <td>{{ courseFee.programe }}</td>
                      <td>{{ courseFee.level }}</td>
                      <td>{{ courseFee.fee_type }}</td>
                      <td>{{ courseFee.amount | currency }}</td>
                      <td>
                      	<a href="#" @click='editCourseFee(courseFee.fee_id)'>
                      		<i class="fa fa-edit indigo"></i>
                      	</a> 
                        / 
                      	<a href="#" @click='deleteCourseFee(courseFee.fee_id)'>
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


import { BCard} from 'bootstrap-vue';
export default {
	name: "course-fee",
        components: {
            'b-card': BCard
        },
        
        data() {
            return {
              editmode: false,
              loading: false,
              courseFees:{},
              academics:{},
              programes:{},
              levels:{},
              feeTypes:{},
              form: new Form({
                fee_id:null,
                academic_id:null,
                programe_id:null,
                level_id:null,
                fee_type_id:null,
                fee_heading:'Fees',
                amount:null,
              })

            }
        },
        methods: {
          createCourseFee(){
            if (this.$gate.isAdminOrAuthor()) {
              this.$Progress.start()
              this.form.post('api/settings/course_fee/create')
              .then(()=>{
                Fire.$emit('loadCourseFeeData');
                Toast.fire({
                            icon: 'success',
                            title: 'Create in successfully'
                          });
                this.$Progress.finish();
                 this.form.reset();

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
          editCourseFee(id){
              this.editmode = true;
              this.loading = true;
              this.form.reset();
              axios.get('api/settings/course_fee/get_by_id/'+id)
              .then((data) => {
                  this.form.fill(data.data);
                  this.loading = false;
              })
              .catch(()=>{
                  this.loading = false
              });
              
          },
          updateCourseFee(){
            if (this.$gate.isAdminOrAuthor()) {
              this.$Progress.start()
              this.form.post('api/settings/course_fee/update')
              .then(()=>{
                Fire.$emit('loadCourseFeeData');
                Toast.fire({
                            icon: 'success',
                            title: 'Create in successfully'
                          });
                this.$Progress.finish();
                 this.editmode = false;
                 this.form.reset();

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
          deleteCourseFee(id) {
                 this.form.reset();
                 this.editmode = false;
                Swal.fire({
                  title: 'Are you sure?',
                  text: "You can not delete This one. Beacuse it's fee already use",
                  icon: 'warning',
                  showCancelButton: true,
                  showConfirmButton: false,
                  // confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33'
                  // confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                  if (result.value) {

                    this.form.delete('api/settings/course_fee/delete/'+id).then(()=>{
                        Swal.fire(
                          'Deleted!',
                          'Expense Type has been deleted.',
                          'success'
                        )
                      Fire.$emit('loadExpenseTypeData');
                 this.form.reset();

                    })
                    .catch(()=>{
                      Swal.fire('Failed','Ops Something Wrong','warning');
                    })
                    
                  }
                })
          },
          
          academicModel(){
              $('#addAcademic').modal('show');
          },
          programeModel(){
              $('#addPrograme').modal('show');
            },
          levelModel(){
              $('#addLevel').modal('show');
          },
          getLevel(){
                if (this.$gate.isAdminOrAuthor()) {
                    let programe_id =  document.getElementById("programe_id").value;
                    var level = $('#level_id');
                     $(level).empty();
                    axios.get('api/course/level/get/'+programe_id)
                    .then((data)=>{
                            $.each(data.data,function(i,pro){
                                $(level).append($('<option/>',{
                                    value : pro.level_id,
                                    text  : pro.level
                                }))
                            })
                    })
                    .catch(()=>{

                    });
                }
          },
          loadProgrames(){
               if (this.$gate.isAdminOrAuthor()) {
                axios.get('api/course/programe/get')
                .then((data)=>{
                    this.programes = data.data;
                    let programe  = $('#form-level-create').find('#programe_id');
                    $.each(this.programes,function(i,pro){
                        $(programe).append($('<option/>',{
                            value : pro.programe_id,
                            text  : pro.programe
                        }))
                    })
                  })
                .catch(()=>{
                    
                });
                
               }
            },
          loadCourseFeeData(){
            if (this.$gate.isAdminOrAuthor()) {
                    this.loading = true
                    axios.get('api/settings/course_fee/get')
                    .then((data) => {
                      console.log(data);
                        this.courseFees = data.data.fees;
                        this.academics = data.data.academics;
                        this.programes = data.data.programes;
                        this.levels = data.data.levels;
                        this.feeTypes = data.data.feeTypes;
                        this.loading = false;
                    })
                    .catch(()=>{
                        this.loading = false
                    });


              }
          }
        },
        mounted() {
        },
        created() {
          this.loadProgrames();
          this.loadCourseFeeData();
          Fire.$on('loadCourseFeeData',()=>{
            this.loadCourseFeeData();
          });

        },
        computed:{
            
        }
    }
</script>


  <template>
     
    <div class="modal fade" id="addNew" tabindex='-1' role='dialog' aria-labelledby='addNewModalLabel' aria-hidden='true'>
        <div class="modal-dialog modal-lg" role='document'>
            <div class="modal-content">

                <div class="modal-header">
                    <h5 v-show='!editmode' class="modal-title"  id="addNewModalLabel">Add New Student</h5>
                    <h5 v-show='editmode' class="modal-title"  id="addNewModalLabel">Update Student</h5>
                    
                    <button class="close" type='button' data-dismiss='modal' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                    
                
                </div>


   <form @submit.prevent="editmode ? updateStudent() : createStudent()" id="form-student-update">
            
              <div class="row panel" v-if="estudent" style="overflow-y: auto; height: 450px;">
                
              <b-col md="12">
                    <div class="form-control courseErrorStyle" :class="{ 'is-invalid': form.errors.has('course_id') }">
                         <span class="badge badge-success badge-pill">
                              Program: {{ estudent.programe }}
                          </span>
                          <span class="badge badge-primary badge-pill">
                              Academic: {{ estudent.academic }}
                          </span>
                          <span class="badge badge-danger badge-pill">
                              Level: {{ estudent.level }}
                          </span>
                          <span class="badge badge-success badge-pill">
                              Shift: {{ estudent.shift }}
                          </span>
                          <span class="badge badge-primary badge-pill">
                              Time: {{ estudent.time }}
                          </span>
                          <span class="badge badge-danger badge-pill">
                              Batch: {{ estudent.batch }}
                          </span>
                          <span class="badge badge-primary badge-pill">
                              Group: {{ estudent.group }}
                          </span>
                          <span class="badge badge-success badge-pill">
                              StartDate: {{ estudent.start_date }}
                          </span>
                          <span class="badge badge-danger badge-pill">
                              EndDate: {{ estudent.end_date }}
                          </span>
                    </div>
                    <div style="float:left">
                      <div class="input-group-prepend" style="float: left;padding:0 0 30px 360px">
                        <span class="input-group-text" id="choice-course" title="please choice a course" @click='choiceCourseModel'><i class="fa fa-plus blue"></i></span>
                      </div>
                    </div>
                      

              </b-col> 

            <b-col md="3">
                  <div class="form-group mb-5">
                      <label for="name">
                          Full Name
                      </label>
                      <input type="text" v-model="form.name" name="name" id="name" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                      <has-error :form="form" field="name"></has-error>
                  </div>
                   <div class="form-group mb-5">
                      <label for="dob">
                          Date of Birth
                      </label>
                      <div class="input-group">
                          <date-pick
                              v-model="form.dob"
                              :format="'YYYY-MM-DD'"
                              :class="{ 'is-invalid': form.errors.has('dob') }">
                          </date-pick>
                          <has-error :form="form" field="dob"></has-error>
                      </div>
                  </div>
            </b-col>  

            <b-col md="3">
                <div class="form-group mb-5">
                    <label for="nick_name">
                        Nick Name
                    </label>
                    <input type="text" v-model="form.nick_name" name="nick_name" id="nick_name" class="form-control" :class="{ 'is-invalid': form.errors.has('nick_name') }">
                    <has-error :form="form" field="nick_name"></has-error>
                </div>
                <div class="form-group mb-5">
                    <label for="national_id">
                        National ID
                    </label>
                    <input type="text" v-model="form.national_id" name="national_id" id="national_id" class="form-control" :class="{ 'is-invalid': form.errors.has('national_id') }">
                    <has-error :form="form" field="national_id"></has-error>
                </div>
            </b-col>  

            <b-col md="3">
                <div class="form-group mb-5">
                    <fieldset style="margin-top: 0px;">
                        <label>Sex</label>
                        <table style="width: 100%; margin-top: 0px">
                            <tr class="form-control" :class="{ 'is-invalid': form.errors.has('sex') }">
                                <td>
                                    <label>
                                        <input type="radio" v-model="form.sex" name="sexMale" id="sexMale" value="0"> Male
                                    </label>
                                </td>
                                <td>
                                    <label>
                                        <input type="radio" v-model="form.sex" name="sexfemale" id="sexfemale" value="1"> Female
                                    </label>
                                </td>
                            </tr>
                            <has-error :form="form" field="sex"></has-error>
                        </table>
                    </fieldset>
                </div>
                <div class="form-group mb-4">
                    <fieldset>
                        <label >Status</label >
                        <table style="width: 100%; margin-top: 0px">
                            <tr class="form-control" :class="{ 'is-invalid': form.errors.has('status') }">
                                <td>
                                    <label>
                                        <input type="radio" v-model="form.status" name="statusSingle" id="singleStatus" value="0"> Single
                                    </label>
                                </td>
                                <td>
                                    <label>
                                        <input type="radio" v-model="form.status" name="statusMarried" id="marriedStatus" value="1"> Married
                                    </label>
                                </td>
                            </tr>
                            <has-error :form="form" field="status"></has-error>
                        </table>
                    </fieldset>
                </div>

            </b-col> 

            <b-col md="3">
                <div class="form-group">
                    <table style="margin: 0 auto">
                        <thead>
                            <tr class="info text-center">
                                <th class="student-id">
                                    {{ editmode ? this.form.student_id : max_student_id }}
                                </th>

                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="photo">

                                    
                                    <div v-if="this.form.photo">
                                        <img class="img-responsive student-photo" id="showPhoto" :src="getProfilePhoto()" alt=" "/>
                                    </div>
                                    <div v-else="this.form.photo">
                                        <img class="img-responsive student-photo" id="showPhoto" src="img/student/student.png" alt=" "/>
                                    </div>

                                    <input type="file" name="photo" id="photo" accept="image/*" @change="imageFile">
                                    
                                    
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: center; background: #ddd;">
                                    <input type="button" name="browsefile" id="browsefile" class="form-control btn-browse" value="Browse" @click='updateFile'>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
               
            </b-col>

            <b-col md="3">
               <div class="form-group">
                    <label for="nationality">
                        Nationality
                    </label>
                        <input type="text" v-model="form.nationality" name="nationality" id="nationality" class="form-control" :class="{ 'is-invalid': form.errors.has('nationality') }">
                    <has-error :form="form" field="nationality"></has-error>
                </div>
            </b-col>

            <b-col md="3">
                <div class="form-group">
                    <label for="zipcode">
                        Zipcode
                    </label>
                        <input type="text" v-model="form.zipcode" name="zipcode" id="zipcode" class="form-control" :class="{ 'is-invalid': form.errors.has('zipcode') }">
                    <has-error :form="form" field="zipcode"></has-error>
                </div>
            </b-col>   

            <b-col md="3">
                <div class="form-group">
                    <label for="passport">
                        Passport No.
                    </label>
                        <input type="text" v-model="form.passport" name="passport" id="passport" class="form-control" :class="{ 'is-invalid': form.errors.has('passport') }">
                    <has-error :form="form" field="passport"></has-error>
                </div>
            </b-col> 

            <b-col md="3">
                <div class="form-group">
                    <label for="phone">
                        Phone
                    </label>
                        <input type="text"  v-model="form.phone" name="phone" id="phone" class="form-control" :class="{ 'is-invalid': form.errors.has('phone') }">
                    <has-error :form="form" field="phone"></has-error>
                </div>
            </b-col>

            <b-col md="6">
                <div class="form-group">
                    <label for="email">
                        Email
                    </label>
                        <input type="text" v-model="form.email" name="email" id="email" class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                    <has-error :form="form" field="email"></has-error>
                </div>
            </b-col>

            <b-col md="6">
                <div class="form-group mb-4">
                    <label for="description">
                        Description
                    </label>
                        <input type="text" v-model="form.description" name="description" id="description" class="form-control" :class="{ 'is-invalid': form.errors.has('description') }">
                    <has-error :form="form" field="description"></has-error>
                </div>
            </b-col>

            <b-col md="12">
                <div class="form-group">
                    <label for="Address">
                        Address 
                    </label>
                        <hr/>
                </div>
            </b-col>

            <b-col md="3">
                <div class="form-group">
                    <label for="village">Village</label>
                    <input type="text" v-model="form.village" class="form-control" id="village" name="village" :class="{ 'is-invalid': form.errors.has('village') }">
                    <has-error :form="form" field="village"></has-error>
                </div>
            </b-col>

            <b-col md="3">
                <div class="form-group">
                    <label for="post">Post Office</label>
                    <input type="text" v-model="form.post_office" class="form-control" id="post_office" name="post_office" :class="{ 'is-invalid': form.errors.has('post_office') }">
                    <has-error :form="form" field="post_office"></has-error>
                </div>
            </b-col>

            <b-col md="3">
                <div class="form-group">
                    <label for="updistrict">Updistrict</label>
                    <input type="text" v-model="form.updistrict" class="form-control" id="updistrict" name="updistrict" :class="{ 'is-invalid': form.errors.has('updistrict') }">
                    <has-error :form="form" field="updistrict"></has-error>
                </div>
            </b-col>

            <b-col md="3">
                <div class="form-group">
                    <label for="district">District</label>
                    <input type="text" v-model="form.district" class="form-control" id="district" name="district" :class="{ 'is-invalid': form.errors.has('district') }">
                    <has-error :form="form" field="district"></has-error>
                </div>
            </b-col>

            <b-col md="12">
                <div class="form-group">
                    <label for="current_address">Current Address</label>
                    <input type="text" v-model="form.current_address" class="form-control" id="current_address" name="current_address" :class="{ 'is-invalid': form.errors.has('current_address') }">
                    <has-error :form="form" field="current_address"></has-error>
                </div>
            </b-col>


                

              </div>
              <div class="panel-footer" style="margin-top:0px">
                     <button v-if="!editmode" :disabled="form.busy" type="submit" class="btn btn-primary">
                         <i class="fa fa-save fa-fw"></i>Save
                     </button>
                     <button v-if="editmode" :disabled="form.busy" type="submit" class="btn btn-primary">
                         <i class="fa fa-save fa-fw"></i>Update
                      </button>
                       <span class="loaderStyle" style="float:right" v-if="loading">
                          <i class="fa fa-cog fa-spin fa-2x"></i>
                      </span> 
                </div>
              </form>
          </div>
        </div>
    </div>
  </template>
    <!-- end academic modal -->
<script>

import DatePick from 'vue-date-pick';
import 'vue-date-pick/dist/vueDatePick.css';
import { BContainer, BRow, BCol, BCard,  } from 'bootstrap-vue'
export default {
  name: "viewStudent",
  components: {
            DatePick,
            'b-container': BContainer,
            'b-row': BRow,
            'b-col': BCol,
            'b-card': BCard,
  },
  props: {
     estudent:{},
     form:{},
     loading:{},
     editmode:{},
     max_student_id:{}
  },
  data(){
      return {
        student:{},
      }
  },

  
  methods:{
    createStudent(){
        this.$Progress.start();
        this.form.post('api/student')
        .then((data)=>{
          Fire.$emit('loadData');
          this.form.reset();
          this.estudent={};  
          Toast.fire({
                      icon: 'success',
                      title: 'Create in successfully'
                    });
          this.$Progress.finish();

          let studentId = data.data;
          /*this.$router.push({ name: 'searchPayment', params: { studentId } })*/
          let route = this.$router.resolve({ name: 'payment',  query:{ studentId:studentId } });
          window.location.assign(route.href);
          

        })
        .catch(()=>{
          this.$Progress.fail();
           Toast.fire({
                      icon: 'error',
                      title: 'Ops !'
                    });
        })
    },
    choiceCourseModel(){
        $('#choiceCourse').modal('show');
    },
    updateStudent(){
        this.$Progress.start();
        this.form.put('api/student/'+this.form.id)
        .then(()=>{
            Fire.$emit('loadData');
            $('#addNew').modal('hide');
            Toast.fire({
                    icon: 'success',
                    title: 'Update in successfully'
                  });
        this.$Progress.finish();
        })
        .catch(()=>{
          this.$Progress.fail()
        })
    },
    updateFile(e){
        $('#photo').click();
    },
    imageFile(e){
      let file = e.target.files[0];
      // console.log(file);
      var reader = new FileReader();

      if (file['size'] < 2111775) {
          reader.onloadend = (file) => {
            // console.log('RESULT', reader.result)
            this.form.photo = reader.result;
          }
          reader.readAsDataURL(file);
         
      } else {
        Swal.fire({
          type : 'error',
          title: 'Ops...',
          text:'This is larger one!'
        })
      }
    },
    getProfilePhoto(){

        let photo = (this.form.photo.length > 200) ? this.form.photo : 'img/student/'+ this.form.photo;

        return photo;
    },
  
  },
  created(){
    
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">

.student-photo{
        height: 160px;
        padding-left: 3px;
        padding-right: 1px;
        border: 1px solid #ccc;
        background: #eee;
        width: 140px;
        margin: 0 auto;
    }
    .photo input {
        display: none;
    }
    .photo{
       width: 125px;
        height: 120px;
        border-radius: 100%;
    }
    .syudent-id{
        background-repeat: repeat-x;
        border-color: #ccc;
        padding: 5px;
        text-align: center;
        background: #eee;
        border-bottom: 1px solid #ccc;
    }
    .btn-browse{
        background-repeat: repeat-x;
        border-color: #ccc;
        padding: 5px;
        text-align: center;
        background: #eee;
        border-bottom: 1px solid #ccc;
    }
    fieldset{
        margin-top: 5px;

    }
    fieldset legend{
        display: block;width: 100%;
        padding: 0px;
        font-size: 15px;
        line-height: inherit;
        color: #797979;
        border: 0px;
        border-bottom: 1px solid #e5e5e5;
    }
    .courseErrorStyle{
      float: left;
    width: 100%;
    text-align: center;
    padding: 4px 0 0 0;
    margin: 10px 0 20px 0;
    }

</style>

<template>

    <b-container class="bv-example-row">
        <b-row class="justify-content-md-center">
           <b-col md="12">
                 <h3 class="page-header mt-2"><i class="fa fa-user"></i> Student</h3>
                 <b-breadcrumb :items="items"></b-breadcrumb>
                 
            </b-col>

        </b-row>

    <choice-course 
        v-bind:course="form.course_id"
        @getCourse="getCourse"
    >
    </choice-course>

        <b-row class="row panel mb-3">
            <div class="panel-header">
                Choice Course
                <div class="input-group-prepend" style="float:right">
                    <span class="input-group-text" id="choice-course" @click='choiceCourseModel'><i class="fa fa-plus blue"></i></span>
                  </div>
            </div>
            
            <b-col md="12">
                <div class="courseData text-center form-control" v-model="form.course_id" v-if="course" :value="course.course_id" :class="{ 'is-invalid': form.errors.has('course_id') }">
                          
                       <span class="badge badge-success badge-pill">
                            Program: {{ course.programe }}
                        </span>
                        <span class="badge badge-primary badge-pill">
                            Academic: {{ course.academic }}
                        </span>
                        <span class="badge badge-danger badge-pill">
                            Level: {{ course.level }}
                        </span>
                        <span class="badge badge-success badge-pill">
                            Shift: {{ course.shift }}
                        </span>
                        <span class="badge badge-primary badge-pill">
                            Time: {{ course.time }}
                        </span>
                        <span class="badge badge-danger badge-pill">
                            Batch: {{ course.batch }}
                        </span>
                        <span class="badge badge-primary badge-pill">
                            Group: {{ course.group }}
                        </span>
                        <span class="badge badge-success badge-pill">
                            StartDate: {{ course.start_date }}
                        </span>
                        <span class="badge badge-danger badge-pill">
                            EndDate: {{ course.end_date }}
                        </span>
                </div>
               
            </b-col>
            
            <div class="panel-footer">
            </div>
        </b-row>
   <form @submit.prevent="CreateStudent()" id="form-course-create" @keydown="form.onKeydown($event)">
        <b-row class="row panel mb-3">
        <div class="panel-header">
        Create Student
        </div>        
        

            <b-col md="3">
                <div class="form-group mb-5">
                    <label for="firstname">
                        Full Name
                    </label>
                    <input type="text" v-model="form.name" name="first_name" id="first_name" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
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
                                    {{ this.form.student_id }}
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
            
                            
                         
        <div class="panel-footer">
                    
            <button :disabled="form.busy" type="submit" class="btn btn-primary">
                 <i class="fa fa-save fa-fw"></i>Save
            </button>
        </div>
   

        </b-row>
     </form>
    </b-container>

   
</template>

<script>

import ChoiceCourse from './model/ChoiceCourse.vue';

import DatePick from 'vue-date-pick';
import 'vue-date-pick/dist/vueDatePick.css';

import { BBreadcrumb, BContainer, BRow, BCol, BCard, BCardText, BForm, BFormInput, BFormGroup, BButton, BButtonGroup, BFormCheckbox } from 'bootstrap-vue'

    export default {
        components: {
            DatePick,
            'b-breadcrumb': BBreadcrumb,
            'b-container': BContainer,
            'b-row': BRow,
            'b-col': BCol,
            'b-card': BCard,
            'b-card-text': BCardText,
            'b-form': BForm,
            'b-form-input': BFormInput,
            'b-form-group': BFormGroup,
            'b-button': BButton,
            'b-button-group': BButtonGroup,
            'b-form-checkbox': BFormCheckbox,
            'choice-course': ChoiceCourse,

        },
        data() {
            return {
                items: [
                  {text: 'Home',to: '/dashboard'},
                  {text: 'Student',to: '/student-create'},
                  {text: 'Create',active: true}
                ],
                course:{},
                form : new Form({
                    id: '',
                    course_id: '',
                    student_id:'',
                    user_id: window.user.id,
                    name:'',
                    nick_name:'',
                    dob:'',
                    national_id:'',
                    sex:'',
                    status:'',
                    nationality:'',
                    zipcode:'',
                    passport:'',
                    email:'',
                    phone:'',
                    photo:'',
                    active:'',
                    village:'',
                    post_office:'',
                    updistrict:'',
                    district:'',
                    current_address:'',
                    description:'',
                })
              }
        },
        methods: {
            choiceCourseModel(){
                $('#choiceCourse').modal('show');
            },
            getCourse(value){
                this.form.course_id = value.course_id
                this.course = value
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
            CreateStudent(){
                  this.$Progress.start()
                  this.form.post('api/student')
                  .then((data)=>{
                    Fire.$emit('loadData');
                    this.form.reset();
                    this.form.course_id = {},
                    this.course = {},
                    Toast.fire({
                                icon: 'success',
                                title: 'Create in successfully'
                              });
                    this.$Progress.finish();

                    let studentId = data.data;
                    /*this.$router.push({ name: 'searchPayment', params: { studentId } })*/
                    let route = this.$router.resolve({ name: 'searchPayment',  query:{ studentId:studentId } });
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
            loadStudentId(){
               if (this.$gate.isAdminOrAuthor()) {
                 axios.get('api/max_student_id')
                .then((data)=>{
                    this.form.student_id = data.data+1
                  })
                .catch(()=>{
                    
                });
               }
            },
            
        },
        
        mounted() {
            console.log('Component mounted.')
        },
        created() {
            this.loadStudentId()
            Fire.$on('loadData',()=>{
              this.loadStudentId()
            });
        },
        computed:{
            
        }
    }
</script>


<style scoped lang="scss">
.vdpComponent.vdpWithInput>input.form-control{
    display: block;
    width: 100%;
    height: calc(1.6em + 0.75rem + 2px);
    padding: 0.375rem 0.75rem;
    font-size: 0.9rem;
    font-weight: 400;
    line-height: 1.6;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    -webkit-transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}
.router-link-exact-active {
    background: none;
    color: #3490dc !important;
}
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

</style>
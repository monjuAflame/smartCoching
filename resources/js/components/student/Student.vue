
<template>
    <div class="container">
        <div class="row justify-content-center mt-2">
            <div class="col-md-12">
                <h3 class="page-header"><i class="fa fa-list-alt"></i> Student</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><i class="fa fa-home"></i> Home</li>
                    <li class="breadcrumb-item"><i class="fa fa-list-alt"></i> Student</li> 
                    <li class="breadcrumb-item"><i class="fa fa-edit"></i> Student List</li>                
                </ol>
            </div>
        </div>

    <view-student
        v-bind:vstudent="vstudent"
        v-bind:loading="loading">   
    </view-student>
    <student-create-update
        v-bind:estudent="estudent"
        v-bind:form="form"
        v-bind:loading="loading"
        v-bind:editmode="editmode"
        v-bind:max_student_id="max_student_id"
    >
    </student-create-update>
    <choice-course 
        @getCourse="getCourse"
    >
    </choice-course>
    
        

        
    
    <div class="card card-defult mt-3">
        <div class="card-header">Student Information 
             
          
            <!-- <span class="loaderStyle" style="float:right" v-else="loading">
                <input class="form-control" type="text" v-model="search_student_id" @keyup="searchStudentId"  placeholder="Search Student ID">
            </span>  -->
            <div class="card-tools" style="float:right">
                  <button class='btn btn-success' title="create student" @click='createStudent'>
                    <i class="fa fa-user-plus fa-fw"></i>
                    Create Student
                  </button>
                  <span class="loaderStyle" style="float:right;" v-if="loading">
                    <i class="fa fa-cog fa-spin fa-2x"></i>
                </span>
            </div>
        </div>
        <div class="card-body" id="show-class-info" >

            
           <table class="table table-hover table-striped table-bordered" id="table-class-info">
                <thead>
                    <tr class="text-center">
                        <th>ID</th>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Nick Name</th>
                        <th>Phone</th>
                        <th>Birth Date</th>
                        <th>District</th>
                        <th>Action</th>
                    </tr>
                </thead>
                
                <tbody>
                        
                        <tr v-for="student in students.data" :key='student.id'>
                            <td>{{ student.student_id }}</td>
                            <td>
                                <img :src="getStudentPhoto(student.photo)" style="height:50px; width:40px" alt="">
                            </td>
                            <td>{{ student.name | upText }}</td>
                            <td>{{ student.nick_name | upText }}</td>
                            <td>{{ student.phone }}</td>
                            <td>{{ student.dob }}</td>
                            <td>{{ student.updistrict }}</td>
                            <td>
                                <a href="#" @click='viewStudent(student.student_id)'>
                                    <i class="fa fa-eye green fa-fw"></i>
                                </a>
                                /
                                <a href="#" @click='editStudent(student.student_id)'>
                                    <i class="fa fa-edit pink fa-fw"></i>
                                </a>
                                /
                                <a href="#" @click='payStudentFee(student.student_id)'>
                                    <i class="fa fa-briefcase indigo fa-fw"></i>
                                </a>
                                /
                                <a href="#" @click="deleteStudent(student.student_id)">
                                    <i class="fa fa-trash red fa-fw"></i>
                                </a>
                            </td>
                        </tr>
              
                </tbody>
            </table>
         

        </div>
        <div class="card-footer">
            <pagination :data="students" @pagination-change-page="getResults"></pagination>
        </div>
    </div>


    </div>

   
</template>

<script>
import ViewStudent from './model/ViewStudent.vue';
import CreateAndUpdateStudent from './model/CreateAndUpdateStudent.vue';
import ChoiceCourse from './model/ChoiceCourse.vue';
import { BBreadcrumb, BContainer, BRow, BCol, BCard, BCardText, BForm, BFormInput, BFormGroup, BButton, BButtonGroup, BFormCheckbox } from 'bootstrap-vue'
export default {
        components: {
            'b-breadcrumb': BBreadcrumb,
            'b-container': BContainer,
            'b-row': BRow,
            'b-col': BCol,
            'b-card': BCard,
            'view-student': ViewStudent,
            'student-create-update': CreateAndUpdateStudent,
            'choice-course': ChoiceCourse,
        },
        data() {
            return {
                editmode: false,
                students :{},
                loading: false,
                search_student_id:null,
                vstudent:{},
                estudent:{},
                max_student_id:{},
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
                  dateregistered:'',
                  status_id:'',
              })
            }
        },
        methods: {
            createStudent(){
              this.editmode = false;
              this.form.reset();
              this.estudent = {};
              this.form.student_id = this.max_student_id;
              $('#addNew').modal('show');
            },
            viewStudent(student_id){
                $('#viewStudent').modal('show');
                this.loading = true;
                this.form.reset();
                axios.get('api/student_by_id/'+student_id)
                .then((data)=>{
                    this.vstudent = data.data
                    this.loading = false
                })
            },
            editStudent(student_id){
                $('#addNew').modal('show');
                this.editmode = true;
                this.loading = true;
                this.form.reset();
                axios.get('api/student_by_id/'+student_id)
                .then((data)=>{
                    this.form.fill(data.data)
                    this.estudent = data.data
                    this.loading = false
                })
            },
            getCourse(value){
                this.form.course_id = value.course_id
                this.estudent = value
            },
            getStudentPhoto(photos){
                let photo = 'img/student/'+ photos;
                return photo;
            },
            getResults(page = 1) {
              axios.get('api/student?page=' + page)
                .then(response => {
                  this.students = response.data;
                });
            },
            payStudentFee(student_id){
              let route = this.$router.resolve({ name: 'payment',  query:{ studentId:student_id } });
                window.location.assign(route.href);
            },
            deleteStudent(student_id){
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

                    this.form.delete('api/student/'+student_id).then(()=>{
                        Swal.fire(
                          'Deleted!',
                          'Your file has been deleted.',
                          'success'
                        )
                      Fire.$emit('loadData');
                    }).catch(()=>{
                      Swal.fire('Failed','Ops Something Wrong','warning');
                    })
                    
                  }
                })
            },
            /*searchStudentId(){
              this.loading = true
              if (this.search_student_id=='') {
                this.loading = false
              } else{
                axios.get('api/findStudent/' + this.search_student_id)
                .then((data)=>{
                  this.students = data.data;
                  this.loading = false
                })
                .then(()=>{
                  this.loading = false
                })
              }
            },*/
            loadStudents(){
               if (this.$gate.isAdminOrAuthor()) {
                    this.loading = true
                    axios.get('api/student')
                    .then(({data}) => (this.students = data,this.loading = false))
                    .catch(()=>{
                        this.loading = false
                    });


               }
            },
            loadStudentId(){
             if (this.$gate.isAdminOrAuthor()) {
               axios.get('api/max_student_id')
              .then((data)=>{
                  this.max_student_id = data.data+1
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
             this.loadStudentId();
             this.loadStudents();
            Fire.$on('searcing',()=>{
              this.loading = true
              let query = this.$parent.search;
              axios.get('api/globalFindStudent?q=' + query)
              .then((data)=>{
                this.students = data.data;
                this.loading = false
              })
              .then(()=>{
                this.loading = false
              })
            });
            
         
            Fire.$on('loadData',()=>{
                this.loadStudents()
                this.loadStudentId()
            });
           
          
        },
        computed:{
            
        }
    }
</script>


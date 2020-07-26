<template>
  <b-container class="bv-example-row" v-if='$gate.isAdminOrAuthor()'>
        <b-row class="justify-content-md-center mt-3">
           <b-col md="12">
                 <b-breadcrumb :items="items"></b-breadcrumb>
            </b-col>
        </b-row>
        
    
    <b-row class="justify-content-md-center">
        <b-col md="7">
          <div class="card card-defult mt-3">
              <div class="card-header">Student List  
                  <div class="card-tools" style="float:right">
                      <button class='btn btn-primary btn-sm' title="choice course" @click='choiceCourse'>
                          <i class="fa fa-search fa-fw"></i>
                      </button>
                      <span class="loaderStyle ml-2" style="float:right;" v-if="loading">
                          <i class="fa fa-cog fa-spin fa-2x indigo"></i>
                      </span>
                  </div>
              </div>
              <div class="card-body" id="show-student-info">
              <table class="table">
                  <tr>
                    <td>
                      <label>Academic Year</label>
                      <select  v-model="form.academic_id" class="form-control" v-if="academics" @change="getStudentByProgrameAndAcademicId">
                           
                           <option v-for="academic in academics" :value="academic.academic_id">{{ academic.academic }}</option>
                      </select>
                    </td>
                    <td>
                      <label>Programe</label>
                      <select id="programe_id" @change='getLevel(),getStudentByProgrameAndAcademicId()' v-model="form.programe_id" class="form-control" v-if="programes">
                           <option v-for="programe in programes" :value="programe.programe_id">{{ programe.programe }}</option>
                      </select>

                    </td>
                    <td>
                      <label>Level</label>
                      <select id="level_id" @change="getStudentByProgrameAndAcademicId()" v-model="form.level_id" class="form-control">
                            <option value="">-----</option>

                      </select>
                    </td>
                    <td>
                      <label>All Student</label><br>
                      <button type="submit" class="btn btn-primary" @click="reFreshAllStudent()">
                        <i class="fa fa-sync-alt"></i> Refresh
                      </button>
                    </td>
                  </tr>
                
              </table>
              <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>
                        <input type="checkbox" v-model="isCheckAll" @click="checkAll">
                      </th>
                      <th>Name</th>
                      <th>Nick Name</th>
                      <th>Mobile</th>
                    </tr>
                  </thead>
                  <tbody>

                    <tr v-for="student in students.data" :key='student.id' >
                      <td>
                        <input type="checkbox" v-model="mobiles" :value="student" @change="updateCheckAll()">
                      </td>
                      <td>{{ student.name }}</td>
                      <td>{{ student.nick_name }}</td>
                      <td>{{ student.phone }}</td>
                    </tr>
                    
                  </tbody>
                </table>

              </div>
              <div class="card-footer">
                  <pagination :data="students" @pagination-change-page="getResults"></pagination>
              </div>
          </div>
        </b-col>
        <b-col md="5">
          <div class="card mt-3">
              <div class="card-header">
                <h3 class="card-title">Send Message</h3>
                <div class="card-tools" style="float:right">
                    
                    <span class="loaderStyle" style="float:right;" v-if="loading">
                      <i class="fa fa-cog fa-spin indigo fa-2x"></i>
                  </span>
                </div>
              </div>
            <form @submit.prevent="sendMessage()">

              <div class="card-body">
                  <textarea class="form-control" :class="{ 'is-invalid': form.errors.has('message') }" v-model="form.message" rows="5" placeholder="Write Message"></textarea>
              </div>

              <div class="card-footer">
                <button type="submit" class="btn btn-primary">Send</button>
              </div>
            </form>
            </div>
          <div class="card mt-3">
              <div class="card-header">
                <h3 class="card-title">Message List</h3>
                <div class="card-tools" style="float:right">
                  <pagination :data="messages" @pagination-change-page="getMsg"></pagination>
                    
                    <span class="loaderStyle" style="float:right;" v-if="loading">
                      <i class="fa fa-cog fa-spin indigo fa-2x"></i>
                  </span>
                </div>

              </div>

              <div class="card-body">

                <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Message</th>
                        <th>Time</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>

                      <tr v-for="(message,index) in messages.data" :key='message.message_id' >
                        <td>{{ index+1 }}</td>
                        <td>{{ message.message | shortenTaxt}}</td>
                        <td>{{ message.send_date | reableTime}}
                        <td>
                         <a href="#">
                          <i class="fa fa-eye indigo" @click="messageShow(message.message_id)"></i>
                          </a> / 
                         <a href="#">
                          <i class="fa fa-trash red" @click="messageDelete(message.message_id)"></i>
                          </a>
                        </td>
                      </tr>
                      
                    </tbody>
                  </table>
              </div>

              <div class="card-footer">
                
              </div>
            </div>
        </b-col>
        <div class="modal fade" id="messageView" tabindex='-1' role='dialog' aria-labelledby='addNewModalLabel' aria-hidden='true'>
        <div class="modal-dialog modal-dialog-centered" role='document'>
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title"  id="addNewModalLabel">Message Details</h5>
                    <button class="close" type='button' data-dismiss='modal' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <ul>
                      <li>Message: <p>{{ messageDetails.message }}</p></li>
                      <li>Mobiles: <p>{{ messageDetails.mobiles }}</p></li>
                      <li>Sender: <p>{{ messageDetails.user_id }}</p></li>
                      <li>Date: <p>{{ messageDetails.send_date }}</p></li>
                    </ul>
                
                </div>

                <div class="modal-footer">
                    <button class="btn btn-danger" data-dismiss='modal'>
                        <i class="fa fa-trash fa-fw"></i>
                        Close
                    </button>
                </div>

            </div>
        </div>
    </div>
    </b-row>
<div v-if='!$gate.isAdminOrAuthor()'>
          <not-found></not-found>
</div>
<choice-course 
        @getCourse="getCourse"
    >
    </choice-course>
  </b-container>

        
</template>
<script>

import ChoiceCourse from './model/ChoiceCourse.vue';

import { BBreadcrumb, BContainer, BRow, BCol, BCard} from 'bootstrap-vue'
export default {
        components: {
            'b-breadcrumb': BBreadcrumb,
            'b-container': BContainer,
            'b-row': BRow,
            'b-col': BCol,
            'b-card': BCard,
            'choice-course': ChoiceCourse
        },
        data() {
            return {
              items: [
                { text: 'Home', to: '/home' },
                { text: 'Student Message', to: '/message'  },
                { text: 'Message', active: true }
              ],
              editmode: false,
              loading: false,
              isCheckAll: false,
              mobiles: [],
              students:{},
              academics:{},
              programes:{},
              messages:{},
              messageDetails:{},
              successMsg:null,
              form: new Form({
                  programe_id:null,
                  level_id:null,
                  academic_id:null,
                  message:null,
              })
            }
        },
        methods: {
          sendMessage(){
            if (this.$gate.isAdminOrAuthorOrUser()) {
                this.$Progress.start()
                
                let data = {mobile_number:this.mobiles,message:this.form.message}
                console.log(data);
                axios.post('api/message/send', data )
               .then(response => {
                  this.form.message = null;
                  Fire.$emit('loadData');
                  this.successMsg = "Message Send successfully";
                  Toast.fire({
                              icon: 'success',
                              title: 'Send in successfully'
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
            }
          },
          messageShow(id) {
            if (this.$gate.isAdminOrAuthor()) {
                    this.loading = true
                     $('#messageView').modal('show');

                    this.form.get('api/message/'+id)
                    .then((data) => {
                      // console.log(data),
                      this.messageDetails = data.data;
                      this.loading = false
                    })
                    .catch(()=>{
                        this.loading = false
                    });


              }
          },
          messageDelete(id) {
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

                    this.form.delete('api/message/'+id).then(()=>{
                        Swal.fire(
                          'Deleted!',
                          'Message has been deleted.',
                          'success'
                        )
                      Fire.$emit('loadData');
                    })
                    .catch(()=>{
                      Swal.fire('Failed','Ops Something Wrong','warning');
                    })
                    
                  }
                })
          },
          checkAll(){
            this.isCheckAll = !this.isCheckAll;
            this.mobiles = [];
            if (this.isCheckAll) {
              for (var key in this.students.data) {
                // console.log(this.students.data);
                this.mobiles.push(this.students.data[key]);
              }
            }
            // console.log(this.mobiles)
          },
          updateCheckAll(){

            if (this.mobiles.length == this.students.data.length) {
              this.isCheckAll = true;
            } else {
              this.isCheckAll = false;
            }
            
          },
          getStudentByProgrameAndAcademicId(){
            if (this.$gate.isAdminOrAuthor()) {
                this.loading = true
                let data = {academic_id:this.form.academic_id,programe_id:this.form.programe_id,level_id:this.form.level_id}
                axios.post('api/message/get/student', data )
               .then(response => {
                  this.loading = false;
                  this.students = response;
                  this.isCheckAll=false;
                })
            }
          },
          getLevel(){
                if (this.$gate.isAdminOrAuthor()) {
                    let programe_id = document.getElementById("programe_id").value;
                    console.log(programe_id);
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
          reFreshAllStudent(){
            if (this.$gate.isAdminOrAuthor()) {
                    this.loading = true
                    axios.get('api/message')
                    .then(({data}) => (
                      // console.log(data),
                      this.students = data.students,
                        this.loading = false,
                        this.form.reset(),
                        this.isCheckAll=false
                    ))
                    .catch(()=>{
                        this.loading = false
                    });


              }
          },
          getResults(page = 1) {
              axios.get('api/message?page=' + page)
                .then(response => {
                  this.students = response.data.students;
                });
          },
          getMsg(page = 1) {
              axios.get('api/message?page=' + page)
                .then(response => {
                  this.messages = response.data.messages;
                });
          },
          choiceCourse(){
            $('#choiceCourse').modal('show');
          },
          getCourse(course_id){
               axios.get('api/message/student/'+course_id)
                .then(({ data }) => (
                  console.log(data),
                    this.students = data
                    
                ))
                .catch(()=>{

                });

          },
          loadStudents(){
               if (this.$gate.isAdminOrAuthor()) {
                    this.loading = true
                    axios.get('api/message')
                    .then(({data}) => (
                      console.log(data),
                      this.students = data.students,
                      this.academics = data.academics,
                      this.programes = data.programes,
                      this.messages = data.messages,
                        this.loading = false
                    ))
                    .catch(()=>{
                        this.loading = false
                    });


               }
          },
            
        },
        
        mounted() {
            console.log('Component mounted.');
        },
        created() {
            Fire.$on('searcing',()=>{
              this.loading = true
              let query = this.$parent.search;
              axios.get('api/report/student/global_find_student?q=' + query)
              .then((data)=>{
                $('#show-student-info').empty().append(data.data);
                this.loading = false
              })
              .then(()=>{
                this.loading = false
              })
            });
          Fire.$on('loadData',()=>{
            this.loadStudents();

          });

          this.loadStudents();
        },
        computed:{
            
        }
    }
</script>


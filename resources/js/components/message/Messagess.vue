<template>
  <b-container class="bv-example-row" v-if='$gate.isAdminOrAuthor()'>
        <b-row class="justify-content-md-center">
           <b-col md="12">
                 <h3 class="page-header mt-2"><i class="fa fa-list"></i> Student Report</h3>
                 <b-breadcrumb :items="items"></b-breadcrumb>
            </b-col>
        </b-row>
        
    <choice-course 
        @getCourse="getCourse"
    >
    </choice-course>
    <b-row class="justify-content-md-center">
        <b-col md="8">
          <div class="card card-defult mt-3">
              <div class="card-header">Student List  
                  <div class="card-tools" style="float:right">
                  <button  type="button" class="btn btn-primary btn-sm according-toggle course-toggle-button indigo" data-toggle="collapse" data-target="#demo" title="default search"><i class="fa fa-arrow-down"></i></button>
                      <button class='btn btn-primary btn-sm' title="choice course" @click='choiceCourse'>
                          <i class="fa fa-search fa-fw"></i>
                      </button>
                      <span class="loaderStyle ml-2" style="float:right;" v-if="loading">
                          <i class="fa fa-cog fa-spin fa-2x indigo"></i>
                      </span>
                  </div>
              </div>
              <div class="card-body" id="show-student-info">
              <table class="table accordian-body collapse" id="demo">
                  <tr>
                    <td>
                      <label>Academic Year</label>
                      <select  v-model="form.academic_id" class="form-control" v-if="academics" @change="getStudentByProgrameAndAcademicId" id="academic_id">
                           
                           <option v-for="academic in academics" :value="academic.academic_id">{{ academic.academic }}</option>
                      </select>
                    </td>
                    <td>
                      <label>Programe</label>
                      <select id="programe_id"  v-model="form.programe_id" class="form-control" v-if="programes" @change='getLevel(),getStudentByProgrameAndAcademicId()'>
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
                        <i class="fa fa-sync-alt"></i>
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

                    <tr v-for="student in students.data" :key='student.id'>
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
        <b-col md="4">
          <div class="card mt-3">
              <div class="card-header">
                <h3 class="card-title">Send Message</h3>
                <div class="card-tools" style="float:right">
                    
                    <span class="loaderStyle" style="float:right;" v-if="loading">
                      <i class="fa fa-cog fa-spin fa-2x"></i>
                  </span>
                </div>
              </div>
              <div class="card-body">
                
              </div>

              <div class="card-footer">

              </div>
            </div>
        </b-col>
    </b-row>
<div v-if='!$gate.isAdminOrAuthor()'>
          <not-found></not-found>
</div>
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
              form: new Form({
                  programe_id:null,
                  level_id:null,
                  academic_id:null,
              })
            }
        },
        methods: {
          getLevel(){
                if (this.$gate.isAdminOrAuthor()) {

                    let programe_id =  document.getElementById("programe_id").value;

                    console.log(programe_id);
                    
                }
          },
          checkAll(){
            this.isCheckAll = !this.isCheckAll;
            this.mobiles = [];
            if (this.isCheckAll) {
              for (var key in this.students.data) {
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
          choiceCourse(){
            $('#choiceCourse').modal('show');
               this.students = '';

          },
          getCourse(value){

               let data = value;
               console.log(data)
               this.students = value;
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
            
          this.loadStudents();
        },
        computed:{
            
        }
    }
</script>


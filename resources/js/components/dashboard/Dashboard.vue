<template>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                  <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>

                  <div class="info-box-content">
                    <span class="info-box-text">Total Students</span>
                    <span class="info-box-number">
                      {{ totalStudents }}
                    </span>
                  </div>
                </div>
              </div>
              <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-flag"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Programes </span>
                <span class="info-box-number">{{ totalProgrames }}</span>
              </div>
            </div>
          </div>
          <div class="clearfix hidden-md-up"></div>
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Sales</span>
                <span class="info-box-number">760</span>
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Users</span>
                <span class="info-box-number">{{ totalUsers }}</span>
              </div>
            </div>
          </div>
            
        <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title">Revenue Info</h5>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              
              <div class="card-body">
                <div class="row">
                  <div class="col-sm-3 col-6">
                    <div class="description-block border-right">
                        <span class="description-percentage indigo"><i class="fas fa-caret-left"></i><i class="fas fa-caret-right"></i> 100 %</span>
                      <h5 class="description-header">{{ totalStudentAmount | currency }}</h5>
                      <span class="description-text">TOTAL Student Amount</span>
                    </div>
                  </div>
                  <div class="col-sm-2 col-6">
                    <div class="description-block border-right">
                      <span class="description-percentage green"><i class="fas fa-caret-left"></i> {{ (totalStudentPaid/totalStudentAmount) | parcentage }}</span>
                      <h5 class="description-header">{{ totalStudentPaid | currency }}</h5>
                      <span class="description-text">TOTAL COLLECT</span>
                    </div>
                  </div>
                  <div class="col-sm-2 col-6">
                    <div class="description-block border-right bgred">
                      <span class="description-percentage text-danger"><i class="fas fa-caret-right"></i> {{ ((totalStudentAmount-totalStudentPaid)/totalStudentAmount) | parcentage }}</span>
                      <h5 class="description-header">{{ (totalStudentAmount-totalStudentPaid) | currency }}</h5>
                      <span class="description-text">TOTAL Due</span>
                    </div>
                  </div>
                  <div class="col-sm-3 col-6">
                    <div class="description-block border-right">
                      <span class="description-percentage indigo"><i class="fas fa-caret-left"></i><i class="fas fa-caret-right"></i>100 %</span>
                      <h5 class="description-header">
                        <!-- <b class="green">1200</b> <b class="red">1200</b> -->
                        {{ totalExpenses | currency }}
                    </h5>
                      <span class="description-text">Total Expense</span>
                    </div>
                  </div>
                  <div class="col-sm-2 col-6">
                    <div class="description-block bggreen">
                      <span class="description-percentage green"><i class="fas fa-caret-left"></i><i class="fas fa-caret-right"></i></span>
                      <h5 class="description-header">
                        <!-- <b class="green">1200</b> <b class="red">1200</b> -->
                        {{ (totalStudentPaid-totalExpenses) | currency }}
                    </h5>
                      <span class="description-text">Revenue</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>

        
        <div class="col-md-10">
              <chat-admin></chat-admin>
        </div>

        <div class="col-md-5">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Latest Students</h3>

                <div class="card-tools">
                  <span class="badge badge-danger">{{ latestStudents }} New Students</span>
                  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <div class="card-body p-0">
                <ul class="users-list clearfix">

                  <li v-if="students" v-for="student in students">
                    <img :src="getStudentPhoto(student.photo)" style="height:50px; width:40px" alt="">
                    <a class="users-list-name" href="#">{{ student.name }}</a>
                    <span class="users-list-date">{{ student.dateregistered}}</span>
                  </li>
                  
                  
                </ul>
              </div>
              <div class="card-footer text-center">
                <router-link to="/students">
                  All Students
                </router-link>
              </div>
            </div>
        </div>



        <div class="col-md-3">
            <div class="card">
                <div class="card-header">Our Programes</div>

                <div class="card-body">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                      
                      <div v-for="programe in programes">
                        
                        <li class="nav-item has-treeview">
                          <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-circle"></i>
                            <p>
                              {{ programe.programe }}
                                <i class="right fas fa-angle-left" v-if="disable"></i>
                            </p>
                          </a>
                          <ul class="nav nav-treeview" style="margin-left:15px" v-if="levels">
                            <li class="nav-item" v-for="level in levels" v-if="programe.programe_id==level.programe_id">
                              <a href="#" class="nav-link">
                                <i class="fas fa-circle nav-icon"></i>
                                <p>{{ level.level }}</p>
                              </a>
                            </li>
                          </ul>
                        </li>
                      </div>

                    </ul>
                </div>
                <div class="card-footer">
                    <button  type="button" class="btn btn-primary btn-sm according-toggle course-toggle-button indigo" data-toggle="collapse" data-target="#demo" title="Create Course"><i class="fa fa-plus"></i></button>
                   <span class="loaderStyle" style="float:right;" v-if="loading">
                      <i class="fa fa-cog fa-spin fa-2x"></i>
                  </span>
                </div>
            </div>
        </div>
        
        </div>
    </div>
</template>

<script>
import ChatAdmin from './components/Chat_admin.vue';

export default {
  name: "dashboard",
  components: {
      'chat-admin': ChatAdmin,
  },

  data(){
      return {
        loading:false,
        disable:false,
        totalStudents:null,
        latestStudents:null,
        students:null,
        totalProgrames:null,
        programes:null,
        levels:null,
        totalUsers:null,
        users:null,
        totalCourseAmount:null,
        totalStudentAmount:null,
        totalStudentPaid:null,
        totalExpenses:null,
        adminChat : new Form({
          user_id: window.user.id,
          message:null
        })

      }
  },

  
  methods:{
    totalStudent(){
        if (this.$gate.isAdminOrAuthorOrUser()) {
                this.loading = true;
                axios.get('api/dashboard')
                .then(( data ) => {
                    // console.log(data);
                    this.totalStudents = data.data.tstudents
                    this.latestStudents = data.data.lstudents
                    this.students = data.data.students
                    this.totalProgrames = data.data.tprogrames
                    this.programes = data.data.programes
                    this.levels = data.data.levels
                    this.totalUsers = data.data.tusers
                    this.users = data.data.users
                    this.totalCourseAmount = data.data.totalcourseamount
                    this.totalStudentAmount = data.data.totalstuentamount
                    this.totalStudentPaid = data.data.totalstuentpaid
                    this.totalExpenses = data.data.totalExpenses

                    this.arowsPrograme(data.data.levels);
                })
                .catch(()=>{
                       Toast.fire({
                                icon: 'error',
                                title: 'Ops !'
                              });
                })
                //for pagination
                // axios.get('api/user').then(({ data }) => (this.users = data,this.loading = false));
        }
    },
    arowsPrograme(levels){
        this.levels.forEach(level => {
            let found = this.programes.filter(programe => programe.programe_id == level.programe_id)
                if(found){
                    this.disable = true;
                }
        })
    },
    getStudentPhoto(photos){
                let photo = 'img/student/'+ photos;
                return photo;
    },
  
  },
  created(){
    this.totalStudent();

  }
};
</script>

<style scoped lang="scss">


</style>

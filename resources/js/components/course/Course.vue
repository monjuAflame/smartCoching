
<template>
    <b-container class="bv-example-row" v-if='$gate.isAdminOrAuthor()'>
        <b-row class="justify-content-md-center mt-3">
           <b-col md="12">
                 <b-breadcrumb :items="items"></b-breadcrumb>
            </b-col>
        </b-row>
    
    <form @submit.prevent="editmode ? updateCourse() : CreateCourse()" id="form-course-create">
        
        
       
    <div class="row panel accordian-body collapse" id="demo">
        <div class="panel-header">
        Manage Course
        </div>
    
                <div class="col-md-3 mb-2">
                    <label for="academic-year">Academic Year</label>
                    <div class="input-group">
                      <select v-model="form.academic_id"  name="academic_id" id="academic_id" class="form-control" :class="{ 'is-invalid': form.errors.has('academic_id') }" v-if="academics" @change="searchCourse">
                           <option v-for="academic in academics" :value="academic.academic_id">{{ academic.academic }}</option>
                      </select>
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="add-more-academic" @click='academicModel'><i class="fa fa-plus"></i></span>
                      </div>
                    </div>
                </div>

                <div class="col-md-4 mb-2">
                    <label for="programe">Programe</label>
                    <div class="input-group">
                        <select name="programe_id"  v-model="form.programe_id" id="programe_id" class="form-control" :class="{ 'is-invalid': form.errors.has('programe_id') }"  @change='getLevel(),searchCourse()' v-if="programes">
                            <option>-----</option>
                           <option v-for="programe in programes" :value="programe.programe_id">{{ programe.programe }}</option>
                        </select>
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="add-more-program" @click='programeModel'><i class="fa fa-plus"></i></span>
                      </div>
                    </div>
                </div>

                <div class="col-md-5 mb-2">
                    <label for="level">Level</label>
                    <div class="input-group">
                        <select v-model="form.level_id" name="level_id" id="level_id" class="form-control" :class="{ 'is-invalid': form.errors.has('level_id') }" @change="searchCourse">
                            <option value="">-----</option>
                        </select>
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="add-more-level" @click='levelModel'><i class="fa fa-plus"></i></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-2">
                    <label for="shift">Shift</label>
                    <div class="input-group">
                        <select v-model="form.shift_id" name="shift_id" id="shift_id" class="form-control" :class="{ 'is-invalid': form.errors.has('shift_id') }" v-if="shifts" @change="searchCourse">
                           <option v-for="shift in shifts" :value="shift.shift_id">{{ shift.shift }}</option>
                        </select>
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="add-more-shift" @click='shiftModel'><i class="fa fa-plus"></i></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-2">
                    <label for="time">Time</label>
                    <div class="input-group">
                        <select v-model="form.time_id" name="time_id" id="time_id" class="form-control" :class="{ 'is-invalid': form.errors.has('time_id') }" v-if="times">
                            <option v-for="time in times" :value="time.time_id">{{ time.time }}</option>
                        </select>
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="add-more-time" @click='timeModel'><i class="fa fa-plus"></i></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-2">
                    <label for="batch">Batch</label>
                    <div class="input-group">
                        <select v-model="form.batch_id" name="batch_id" id="batch_id" class="form-control" :class="{ 'is-invalid': form.errors.has('batch_id') }" v-if="batches" @change="searchCourse">
                            <option v-for="batch in batches" :value="batch.batch_id">{{ batch.batch }}</option>
                        </select>
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="add-more-batch" @click='batchModel'><i class="fa fa-plus"></i></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-2 mb-2">
                    <label for="group">Group</label>
                    <div class="input-group">
                        <select v-model="form.group_id" name="group_id" id="group_id" class="form-control" :class="{ 'is-invalid': form.errors.has('group_id') }" v-if="groups" @change="searchCourse">
                            <option v-for="group in groups" :value="group.group_id">{{ group.group }}</option>
                        </select>
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="add-more-group" @click='groupModel'><i class="fa fa-plus"></i></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-2">
                    <label for="startDate">Start Date</label>
                    <div class="input-group">
                        <date-pick
                            v-model="form.start_date"
                            :format="'YYYY-MM-DD'"
                            :class="{ 'is-invalid': form.errors.has('start_date') }">
                        </date-pick>
                    </div>
                </div>

                <div class="col-md-3 mb-2">
                    <label for="endtDate">End Date</label>
                    <div class="input-group">
                        <date-pick
                            v-model="form.end_date"
                            :format="'YYYY-MM-DD'"
                            :class="{ 'is-invalid': form.errors.has('end_date') }">
                        </date-pick>
                    </div>
                </div>

                <div class="col-md-6 mb-2">
                    <label for="description">Description</label>
                    <div class="input-group">
                        <input v-model="form.description" type="text" class="form-control" name="description" :class="{ 'is-invalid': form.errors.has('description') }">
                    </div>
                </div>

                <div class="panel-footer">
                    
                    <button v-show='!editmode' :disabled="form.busy" type="submit" class="btn btn-primary">
                     <i class="fa fa-save fa-fw"></i>Save

                    </button>

                    <a @click="restCourse" class="btn btn-primary white">
                     <i class="fa fa-save fa-fw"></i>Reset
                    </a>

                    <button v-show='editmode' :disabled="form.busy" type="submit" class="btn btn-primary">
                     <i class="fa fa-save fa-fw"></i>Update
                    </button>
                </div>
      
        
    </div>
   </form> 
    <div class="card card-defult mt-3">
        <div class="card-header">
            Course Information
            <div class="card-tools" style="float:right">
                <button  type="button" class="btn btn-primary btn-sm according-toggle course-toggle-button indigo" data-toggle="collapse" data-target="#demo" title="Create Course"><i class="fa fa-plus"></i></button>
                   <span class="loaderStyle indigo" style="float:right;" v-if="loading">
                      <i class="fa fa-cog fa-spin fa-2x"></i>
                  </span>
            </div>

        </div>
        <div class="card-body" id="show-class-info" >

            <course-info 
                    v-bind:courses="courses"
                    v-bind:form="form"
                    v-bind:editmode="editmode"
                    @changeEditMode="editModeChanged"
            >
                
            </course-info>

        </div>
        <div class="card-footer">
                <pagination :data="courses" @pagination-change-page="getCourses"></pagination>
        </div>
    </div>

    
    <academic></academic>
    <programe></programe>
    <level></level>
    <shift></shift>
    <times></times>
    <batch></batch>
    <group></group>

    </b-container>

   
</template>

<script>
import Academic from './model/academicYear.vue';
import Programe from './model/Programe.vue';
import Level from './model/Level.vue';
import Shift from './model/Shift.vue';
import TimeModel from './model/Time.vue';
import Batch from './model/Batch.vue';
import Group from './model/Group.vue';
import CourseInfo from './info/Info.vue';
import DatePick from 'vue-date-pick';
import 'vue-date-pick/dist/vueDatePick.css';
import { BBreadcrumb, BContainer, BRow, BCol, BCard} from 'bootstrap-vue'

export default {
        components: {
            DatePick,
            'b-breadcrumb': BBreadcrumb,
            'b-container': BContainer,
            'b-row': BRow,
            'b-col': BCol,
            'b-card': BCard,
            'academic' : Academic,
            'programe' : Programe,
            'level' : Level,
            'shift' : Shift,
            'times' : TimeModel,
            'batch' : Batch,
            'group' : Group,
            'course-info' : CourseInfo,
        },
        data() {
            return {
                items: [
                    { text: 'Home', to: '/home' },
                    { text: 'Course', to: '/courses'  },
                    { text: 'Course Manage', active: true }
                ],
                editmode: false,
                loading: false,
                academics :{},
                programes :{},
                shifts :{},
                levels:{},
                times :{},
                batches :{},
                groups :{},
                courses :{},
                form : new Form({
                    course_id: '',
                    academic_id: '',
                    programe_id:'',
                    level_id: '',
                    shift_id: '',
                    time_id: '',
                    group_id: '',
                    batch_id: '',
                    start_date: '',
                    end_date: '',
                    active: '',
                    description: '',
                })
            }
        },
        methods: {
            editModeChanged(value){
                this.editmode = value
            },
            searchCourse(){
                this.loading = true;
                let data = {academic_id:this.form.academic_id,programe_id:this.form.programe_id,level_id:this.form.level_id,shift_id:this.form.shift_id,time_id:this.form.time_id,batch_id:this.form.batch_id,group_id:this.form.group_id}
                axios.post('api/course/get/filter', data )
               .then(response => {
                  this.loading = false;
                  this.courses = response
                  this.MergeCommonRows($('#table-class-info'));
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
            shiftModel(){
              $('#addShift').modal('show');
            },
            timeModel(){
              $('#addTime').modal('show');
            },
            batchModel(){
              $('#addBatch').modal('show');
            },
            groupModel(){
              $('#addGroup').modal('show');
            },        
            CreateCourse () {
              this.$Progress.start()
              this.form.post('api/course/add')
              .then((data)=>{

                    this.form.reset();
                    Fire.$emit('loadData');
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
            restCourse(){
                this.form.reset();
            },
            
            updateCourse(){
                  this.$Progress.start()
                  this.form.put('api/course/update/'+this.form.course_id)
                  .then((data)=>{

                        this.editmode = false;
                        this.form.reset();
                        Fire.$emit('loadData');
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
            deleteCourse(id){
                Swal.fire({
                  title: 'Are you sure?',
                  text: "You won't be able to Delete this!",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                  if (result.value) {

                    this.form.delete('api/course/delete/'+id).then(()=>{
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

            

            loadAcademics(){
               if (this.$gate.isAdminOrAuthor()) {
                axios.get('api/course/academic/get')
                .then((data)=>{
                    this.academics = data.data;
                  })
                .catch(()=>{
                    
                });
                //for pagination
                // axios.get('course/academic/get').then(({ data }) => (this.users = data));
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
                //for pagination
                // axios.get('course/academic/get').then(({ data }) => (this.users = data));
               }
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
            loadShifts(){
               if (this.$gate.isAdminOrAuthor()) {
                axios.get('api/course/shift/get')
                .then((data)=>{
                    this.shifts = data.data;
                  })
                .catch(()=>{
                    
                });
                //for pagination
                // axios.get('course/academic/get').then(({ data }) => (this.users = data));
               }
            },
            loadTimes(){
               if (this.$gate.isAdminOrAuthor()) {
                axios.get('api/course/time/get')
                .then((data)=>{
                    this.times = data.data;
                  })
                .catch(()=>{
                    
                });
                //for pagination
                // axios.get('course/academic/get').then(({ data }) => (this.users = data));
               }
            },
            loadBatches(){
               if (this.$gate.isAdminOrAuthor()) {
                axios.get('api/course/batch/get')
                .then((data)=>{
                    this.batches = data.data;
                  })
                .catch(()=>{
                    
                });
                //for pagination
                // axios.get('course/academic/get').then(({ data }) => (this.users = data));
               }
            },
            loadGroups(){
               if (this.$gate.isAdminOrAuthor()) {
                axios.get('api/course/group/get')
                .then((data)=>{
                    this.groups = data.data;
                  })
                .catch(()=>{
                    
                });
                //for pagination
                // axios.get('course/academic/get').then(({ data }) => (this.users = data));
               }
            },
            getCourses(page = 1){
                axios.get('api/course/get?page=' + page)
                .then(response => {
                  this.courses = response.data
                });
            },
            loadCourse(){
               if (this.$gate.isAdminOrAuthor()) {
                this.loading = true;
                axios.get('api/course/get')
                .then(({data}) => (this.courses = data,this.loading = false))
                .catch(()=>{
                    this.loading = false;
                });
                //for pagination
                // axios.get('course/academic/get').then(({ data }) => (this.users = data));
               }
            },
            MergeCommonRows(table) {
                var firstColumnBrakes = [];
                $.each(table.find('th'),function(i){
                    var previous = null, cellToExtend = null, rowspan = 1;
                    table.find("td:nth-child(" + i + ")").each(function(index, e){
                        var jthis = $(this), content = jthis.text();
                        if (previous == content && content !== "" && $.inArray(index, firstColumnBrakes) === -1) {
                            jthis.addClass('hidden');
                            cellToExtend.attr("rowspan", (rowspan = rowspan+1));
                        } else {
                            if (i === 1) firstColumnBrakes.push(index);
                            rowspan = 1;
                            previous = content;
                            cellToExtend = jthis;
                        }
                    });
                });
                $('td.hidden').remove();
            }
        },
        
        mounted() {
            console.log('Component mounted.')
        },
        created() {
            this.loadAcademics();
            this.loadProgrames();
            this.loadShifts();
            this.loadTimes();
            this.loadBatches();
            this.loadGroups();
            this.loadCourse();
            // setInterval(() => this.loadUsers(), 3000);
            Fire.$on('loadData',()=>{
              this.loadCourse();
            });
        },
        computed:{
            
        }
    }
</script>



  <template>
        <!-- academic modal -->
    <div class="modal fade" id="choiceCourse" tabindex='-1' role='dialog' aria-labelledby='addNewModalLabel' aria-hidden='true'>
        <div class="modal-dialog modal-lg" role='document'>
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title"  id="addNewModalLabel">Filtering Choice Course</h5>
                    <button class="close" type='button' data-dismiss='modal' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>

   
        <div class="row panel">
                <div class="col-md-4 mb-2">
                    <label for="academic-year">Academic Year</label>
                    <div class="input-group">
                      <select v-model="form.academic_id"  name="academic_id" id="academic_id" class="form-control" v-if="academics" @change="searchCourse">
                           <option v-for="academic in academics" :value="academic.academic_id">{{ academic.academic }}</option>
                      </select>
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="add-more-academic"><i class="fa fa-plus"></i></span>
                      </div>
                    </div>
                </div>

                <div class="col-md-4 mb-2">
                    <label for="programe">Programe</label>
                    <div class="input-group">
                        <select name="programe_id"  v-model="form.programe_id" id="programe_id" class="form-control"  @change='getLevel(),searchCourse()' v-if="programes">
                            <option>-----</option>
                           <option v-for="programe in programes" :value="programe.programe_id">{{ programe.programe }}</option>
                        </select>
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="add-more-program"><i class="fa fa-plus"></i></span>
                      </div>
                    </div>
                </div>

                <div class="col-md-4 mb-2">
                    <label for="level">Level</label>
                    <div class="input-group">
                        <select v-model="form.level_id" name="level_id" id="level_id" class="form-control" @change="searchCourse">
                            <option value="">-----</option>
                        </select>
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="add-more-level" ><i class="fa fa-plus"></i></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-2">
                    <label for="shift">Shift</label>
                    <div class="input-group">
                        <select v-model="form.shift_id" name="shift_id" id="shift_id" class="form-control" v-if="shifts" @change="searchCourse">
                           <option v-for="shift in shifts" :value="shift.shift_id">{{ shift.shift }}</option>
                        </select>
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="add-more-shift" ><i class="fa fa-plus"></i></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-2">
                    <label for="time">Time</label>
                    <div class="input-group">
                        <select v-model="form.time_id" name="time_id" id="time_id" class="form-control" v-if="times">
                            <option v-for="time in times" :value="time.time_id">{{ time.time }}</option>
                        </select>
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="add-more-time" ><i class="fa fa-plus"></i></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-2">
                    <label for="batch">Batch</label>
                    <div class="input-group">
                        <select v-model="form.batch_id" name="batch_id" id="batch_id" class="form-control" v-if="batches" @change="searchCourse">
                            <option v-for="batch in batches" :value="batch.batch_id">{{ batch.batch }}</option>
                        </select>
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="add-more-batch" ><i class="fa fa-plus"></i></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-2">
                    <label for="group">Group</label>
                    <div class="input-group">
                        <select v-model="form.group_id" name="group_id" id="group_id" class="form-control" v-if="groups" @change="searchCourse">
                            <option v-for="group in groups" :value="group.group_id">{{ group.group }}</option>
                        </select>
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="add-more-group" ><i class="fa fa-plus"></i></span>
                        </div>
                    </div>
                </div>

                <div class="panel-footer">
                   Course List
                </div>
      
        
        </div>
           
          <div class="panel" style="overflow-y: auto; height: 260px;">

            <table class="table table-hover table-striped table-bordered" id="table-class-info">
                <thead>
                    <tr class="text-center">
                        <!-- <th>No.</th> -->
                        <th>Program</th>
                        <th>Level</th>
                        <th>Shift</th>
                        <th>Time</th>
                        <th>Details</th>
                    </tr>
                </thead>
                
                <tbody>
                        
                        <tr v-for="(course, i) in courses.data" :key="course.course_id">
                            <!-- <td>{{ i+1 }}</td> -->
                            <td>{{ course.programe }}</td>
                            <td>{{ course.level }}</td>
                            <td>{{ course.shift }}</td>
                            <td>{{ course.time }}</td>
                            <!-- <td>{{ course.active ? 'Active' : 'Deactive' }}</td> -->
                            <td class="academic-details">
                                <button class="btn" @click="choiceCourse(course)" id="useCourse">
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
                                </button>
                            </td>
                        </tr>
              
                </tbody>
            </table>
          </div>
          <div class="panel-footer" style="margin-top:0px">
                <pagination :data="courses" @pagination-change-page="getCourses"></pagination>
          </div>

            </div>
        </div>
    </div>
  </template>
    <!-- end academic modal -->
<script>
export default {
  name: "choiceCourse",
  components: {
    
  },
  props: {
    course:{} 
  },
  data(){
      return {
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
        })
      }
  },

  
  methods:{

      choiceCourse(choiceCourse){
        
        var course = choiceCourse;
        this.$emit('getCourse', course);
        $('#choiceCourse').modal('hide');
      },
      searchCourse(){
          let data = {academic_id:this.form.academic_id,programe_id:this.form.programe_id,level_id:this.form.level_id,shift_id:this.form.shift_id,time_id:this.form.time_id,batch_id:this.form.batch_id,group_id:this.form.group_id}
          axios.post('api/course/get/filter', data )
         .then(response => {
            this.courses = response
            this.MergeCommonRows($('#table-class-info'));
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
              let programe_id =  document.getElementById("programe_id").value
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
      loadCourse(){
         if (this.$gate.isAdminOrAuthor()) {
          axios.get('api/course/get')
          .then(({data}) => (this.courses = data))
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
  created(){
    this.loadAcademics();
    this.loadProgrames();
    this.loadShifts();
    this.loadTimes();
    this.loadBatches();
    this.loadGroups();
    this.loadCourse();

  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">

.academic-details {
      width: 320px;
      color: #007aff;
  }
  #table-class-info{
    width: 100%;
  }
  table  tbody > tr > td {
    vertical-align: middle;
    text-align: center;
  }
  table thead tr > th {
    text-align: center;
  }
  .course-details button b {color: #007aff;}

</style>
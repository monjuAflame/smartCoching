  <template>
        <!-- academic modal -->
    <div class="modal fade" id="addNewCourse" tabindex='-1' role='dialog' aria-labelledby='addNewModalLabel' aria-hidden='true'>
        <div class="modal-dialog modal-dialog-centered" role='document'>
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title"  id="addNewModalLabel">Create New Course</h5>
                    <button class="close" type='button' data-dismiss='modal' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>

            <form @submit.prevent="CreateNewCourse()" id="form-fee-create">
                <div class="modal-body">
                  
                    <div>
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="choice-course" title="please choice a course" @click='choiceCourseModel'><i class="fa fa-plus blue"></i></span>
                      </div>
                    </div>
                    <div class="course-detail" v-if="courses!=null">
    
                       <span class="badge badge-success badge-pill">
                            Program: {{ courses.programe }}
                        </span>
                        <span class="badge badge-primary badge-pill">
                            Academic: {{ courses.academic }}
                        </span>
                        <span class="badge badge-danger badge-pill">
                            Level: {{ courses.level }}
                        </span>
                        <span class="badge badge-success badge-pill">
                            Shift: {{ courses.shift }}
                        </span>
                        <span class="badge badge-primary badge-pill">
                            Time: {{ courses.time }}
                        </span>
                        <span class="badge badge-danger badge-pill">
                            Batch: {{ courses.batch }}
                        </span>
                        <span class="badge badge-primary badge-pill">
                            Group: {{ courses.group }}
                        </span>
                        <span class="badge badge-success badge-pill">
                            StartDate: {{ courses.start_date }}
                        </span>
                        <span class="badge badge-danger badge-pill">
                            EndDate: {{ courses.end_date }}
                        </span>
                  </div>
                 
                </div>

                <div class="modal-footer">
                    <button class="btn btn-danger" data-dismiss='modal'>
                        <i class="fa fa-trash fa-fw"></i>
                        Close
                    </button>
                    <button :disabled="form.busy" type="submit" class="btn btn-primary">
                        <i class="fa fa-save fa-fw"></i>
                        Save
                    </button>
                </div>
            </form>

            </div>
        </div>
    </div>
  </template>
    <!-- end academic modal -->
<script>

export default {
  name: "CreateNewCourse",
  components: {
    
  },
  props: {
    courses:{},
    form:{}
  },

  data(){
      return {
        
      }
  },

  
  methods:{
    choiceCourseModel(){
        $('#choiceCourse').modal('show');
    },
    
    CreateNewCourse () {
            let data = {student_id:this.form.student_id,course_id:this.form.course_id}
            this.$Progress.start()
            axios.post('api/course/add_new', data )
           .then(response => {
              Toast.fire({ icon: 'success',title: 'Create in successfully'});

              console.log(response.data)
              let schoolFees = response.data.schoolFees;
              let studentDetails = response.data.studentDetails;
              let programes = response.data.programes;
              let levels = response.data.levels;
              this.$emit('getStudentDetailsByNewCourse', {studentDetails,programes,levels,schoolFees});

              $('#addNewCourse').modal('hide');


              this.$Progress.finish();
              

            })
           .catch(()=>{
                 this.$Progress.fail();
                 Toast.fire({icon: 'error',title: 'Ops !'});
            })
    },
   

  
  },
  mounted() {

  },
  created(){
    
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">

.table-fee{
    border: none;
}
.table-fee tr ,td ,th {
  border: none;
}

</style>
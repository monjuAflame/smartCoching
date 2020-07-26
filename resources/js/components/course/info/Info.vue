<template>
    <table class="table table-hover table-striped table-bordered" id="table-class-info">
                <thead>
                    <tr class="text-center">
                        <!-- <th>No.</th> -->
                        <th>Program</th>
                        <th>Level</th>
                        <th>Shift</th>
                        <th>Time</th>
                        <th>Details</th>
                        <th>Action</th>
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
                 
                        <span class="badge badge-primary badge-pill">
                            Academic: {{ course.academic }}
                        </span>
                        <span class="badge badge-primary badge-pill">
                            Program: {{ course.programe }}
                        </span>
                        <span class="badge badge-primary badge-pill">
                            Level: {{ course.level }}
                        </span>
                        <span class="badge badge-primary badge-pill">
                            Shift: {{ course.shift }}
                        </span>
                        <span class="badge badge-primary badge-pill">
                            Time: {{ course.time }}
                        </span>
                        <span class="badge badge-primary badge-pill">
                            Batch: {{ course.batch }}
                        </span>
                        <span class="badge badge-primary badge-pill">
                            Group: {{ course.group }}
                        </span>
                        <span class="badge badge-primary badge-pill">
                            StartDate: {{ course.start_date }}
                        </span>
                        <span class="badge badge-primary badge-pill">
                            EndDate: {{ course.end_date }}
                        </span>
                            </td>
                            <td>
                        <a href="#" @click='editCourse(course.course_id)'>
                            <i class="fa fa-edit blue fa-fw"></i>
                        </a>
                        
                        <a href="#" @click="deleteCourse(course.course_id)">
                            <i class="fa fa-trash red fa-fw"></i>
                        </a>
                            </td>
                        </tr>
              
                </tbody>
            </table>
</template>
<script>
export default {
  name: "course-list",
  components: {
    
  },
  props: {
    courses:{},
    form:{},
    editmode:{
        type: Boolean,
        default: false,
    } 
   
  },

  data(){
      return {
        
      }
  },

  
  methods:{
    editCourse(id){
            var editmode = !this.editmode;
            this.$emit('changeEditMode', editmode);
            if (editmode == false){
                //dothis
            } else {
                
                this.form.reset();
                axios.get('api/course/edit/'+id)
                .then((data)=>{
                    this.form.fill(data.data)
                        
                })
                .catch(()=>{

                });
            }
            
        
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
  
  },
  created(){
  

  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">

table  tbody > tr > td {
  vertical-align: middle;
  text-align: center;
}
table thead tr > th {
  text-align: center;
}

</style>
  <template>
     
    <div class="modal fade" id="viewStudent" tabindex='-1' role='dialog' aria-labelledby='addNewModalLabel' aria-hidden='true'>
        <div class="modal-dialog modal-lg" role='document'>
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title"  id="addNewModalLabel">Student Details</h5>
                    
                    <button class="close" type='button' data-dismiss='modal' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>

   
              <div class="row panel" v-if="vstudent">
                  
                <b-col md="3">
                  <div class="form-group mb-5">
                      <label for="firstname">
                          Full Name
                      </label>
                      <p>{{ vstudent.name }}</p>
                  </div>
                
                </b-col> 
                <b-col md="3">
                  <div class="form-group mb-5">
                      <label for="firstname">
                          Nick Name
                      </label>
                      <p>{{ vstudent.nick_name }}</p>
                  </div>
                
                </b-col>  


                <div class="panel-footer" style="margin-top:0px">
                      <span class="loaderStyle" style="float:right" v-if="loading">
                        <i class="fa fa-cog fa-spin fa-2x"></i>
                    </span> 
                </div>

              </div>
          </div>
        </div>
    </div>
  </template>
    <!-- end academic modal -->
<script>
import { BContainer, BRow, BCol, BCard,  } from 'bootstrap-vue'
export default {
  name: "viewStudent",
  components: {
            'b-container': BContainer,
            'b-row': BRow,
            'b-col': BCol,
            'b-card': BCard,
  },
  props: {
     vstudent:{},
     loading:{}
  },
  data(){
      return {
        student:{},
        form : new Form({
          student_id:''
            
        })
      }
  },

  
  methods:{

   

    
  
  },
  created(){
    console.log('sad')
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">



</style>
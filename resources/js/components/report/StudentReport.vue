
<template>
    <b-container class="bv-example-row">
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

    <div class="card card-defult mt-3">
        <div class="card-header">Student  
            <div class="card-tools" style="float:right">
                <button class='btn btn-success btn-sm' title="choice course" @click='choiceCourse'>
                    <i class="fa fa-search fa-fw"></i>
                </button>
                <span class="loaderStyle" style="float:right;" v-if="loading">
                    <i class="fa fa-cog fa-spin fa-2x indigo"></i>
                </span>
            </div>
        </div>
        <div class="card-body" id="show-student-info">
			

        </div>
        <div class="card-footer">
            
        </div>
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
	              { text: 'Student Report', to: '/report-student'  },
	              { text: 'Report', active: true }
	            ],
                editmode: false,
                loading: false,
                form: new Form({
                	
                })
            }
        },
        methods: {
        	choiceCourse(){
        		$('#choiceCourse').modal('show');
        	},
        	getCourse(value){
               let data = value;
                $('#show-student-info').empty().append(data);
            },
            loadStudents(){
               if (this.$gate.isAdminOrAuthor()) {
                    this.loading = true
                    axios.get('api/report/student/all_student')
                    .then((data) => {
                        $('#show-student-info').empty().append(data.data);
                        this.loading = false
                    })
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
         	this.loadStudents();
        },
        computed:{
            
        }
    }
</script>


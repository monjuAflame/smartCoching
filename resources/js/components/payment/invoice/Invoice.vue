<template>
	
	<div> 
		<span class="loaderStyle text-center"  v-if="loading">
                    <i class="fa fa-cog fa-spin fa-2x"></i>
        </span>
		<div id="appended"></div>
	</div>
</template>
<script>
import { BContainer, BRow, BCol, BCard} from 'bootstrap-vue'

export default {
    components: {
        'b-container': BContainer,
        'b-row': BRow,
        'b-col': BCol,
        'b-card': BCard,

    },
    props:{
      
      
    },
    data() {
        return {
        	receipt_id: this.$route.query.receipt_id,
            loading: false,
        }
    },
    methods: {
        
        loadPrintData(){
        	loading: true,
            axios.get('/api/payment/print-invoice/'+this.receipt_id)
            .then((data) => {
                $('#appended').html(data.data)
                this.loading = false
            })
            .catch(()=>{
                this.loading = false
            });
        },
        
    },
    
    mounted() {
    },
    computed:{
      
    },
    created() {
        this.loadPrintData()

    }
}
</script>
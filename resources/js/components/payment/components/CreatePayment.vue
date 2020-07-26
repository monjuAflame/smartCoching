<template>
	<table>			
		<thead>
			<tr>
				<th>Course</th>
				<th>Level</th>
				<th>School Fee($)</th>
				<th>Amount($)</th>
				<th>Dis(%)</th>
				<th>Paid($)</th>
				<th>Amount Due($)</th>
			</tr>
		</thead>
			<tr>
				<td>
					<select  name="program_id" id="program_id" class="d" v-if="programes">
						<option v-for="programe in programes" :value="programe.programe_id" v-if="programe.programe_id==studentDetails.programe_id ? 'selected' : null">{{ programe.programe }}</option>
					</select>
				</td>
				<td>
					<select  name="level_Id" id="level_Id" class="d" v-if="levels">
						
						<option v-for="level in levels" :value="level.programe_id" v-if="level.level_id==studentDetails.level_id ? 'selected' : null">{{ level.level }}</option>
					</select>
				</td>
				<td>
	            <div class="input-group">
	                
	                    
	                 <span class="create-fee" v-if="feeButton" title="create fee"><i class="fa fa-plus" @click="createFeeModel"></i></span>
	             
	                 	<input type="text" v-if="schoolFees==null" name="fee" id="Fee" readonly="true" class="d">
	                 	<input type="text" v-if="schoolFees!=null" name="fee" :value="schoolFees.amount" id="Fee" readonly="true" class="d">
	               
				
	            </div>
				</td>
				<td>
	            	<input type="text" v-model="form.amount" @keyup="amount" name="amount" id="Amount" class="d">
	            </td>
	            <td>
	                <input type="text" v-model="form.discount" @keyup="discount" name="discount" id="Discount" class="d">
	            </td>
	            <td>
	                <input type="text" v-if="!disable" v-model="form.paid" @keyup="paid" id="Paid" required="">
	                <input type="text" v-if="disable" v-model="form.extraPaid" @keyup="extraPay" id="Pay" required="">
	            </td>
	            <td>
	                <input type="text" v-model="form.due" name="due" id="Due" disabled>
	            </td>
	        </tr>

	        <thead>
	            <tr>
	                <th colspan="2">Remark</th>
	                <th colspan="5">Description</th>
	            </tr>
	            <tr>
	                <td colspan="2">
	                    <input type="text" v-model="form.remark" name="remark" id="remark" required="">
	                </td>
	                <td colspan="5">
	                    <input type="text" v-model="form.description" name="description" id="description" required="">
	                </td>
	            </tr>
	        </thead>
	</table> 
</template>
<script>



export default {
	 name: "Calculation",
    components: {

    },
    props:{
    	programes:{},
    	levels:{},
    	schoolFees:{},
    	studentDetails:{},
    	form:{},
    	feeButton:{},
    	disable:{}
    },
    data() {
        return {
            
          }
    },
    methods: {
    	createFeeModel(){
            $('#addFee').modal('show');
        },
        amount(){
				
				this.form.fee = document.getElementById('Fee').value
				let amount = this.form.amount
				let fee = this.form.fee
				let paid = amount
				this.form.paid = paid
            	if (amount=='') {
					this.form.discount = ''
					this.form.paid = ''
					this.form.due = ''
				} else {
					let discount = 0
	            	discount = (((fee - paid)  / fee) * 100)
	            	this.form.discount = discount
	            	this.form.due = this.form.amount - this.form.paid
            	}

            	if(parseFloat(amount) > parseFloat(fee)) {
					document.getElementById('Discount').style.color = 'red'
				} else {
					document.getElementById('Discount').style.color = 'black'
				}

        },
        discount(){
        	let fee = document.getElementById('Fee').value
			let discount = 0
			discount = (parseFloat(fee) * this.form.discount / 100)
			this.form.amount = parseFloat(fee) - parseFloat(discount)
			this.form.paid = this.form.amount
        },
        paid(){

        	
			let amount = this.form.amount
			var paid = this.form.paid
			if (paid=='') {
				this.form.due = 0
			}
			if (paid!='') {
				this.form.paid = parseFloat(paid)
			}

			if (paid!='' && amount!='') {
				this.form.due = parseFloat(amount) - parseFloat(paid)
			}
			if(parseFloat(paid) > parseFloat(amount)) {
				document.getElementById('Due').style.color = 'red'
			} else {
				document.getElementById('Due').style.color = 'black'
			}

        },
        extraPay(){
        	let balance = this.form.balance
        	var extraPaid = this.form.extraPaid
        	if (extraPaid=='') {
				this.form.due = 0
			}
			if (extraPaid!='') {
				this.form.extraPaid = parseFloat(extraPaid)
			}
			if (extraPaid!='' && balance!='') {
				this.form.due = parseFloat(balance) - parseFloat(extraPaid)
			}
			if(extraPaid > balance) {
				document.getElementById('Due').style.color = 'red'
			} else {
				document.getElementById('Due').style.color = 'black'
			}
        }
            
    },
    
    mounted() {
        
    },
    created() {
        
        Fire.$on('loadData',()=>{
         
        });
    },
    computed:{
        
    }
}
</script>


<style scoped lang="scss">

	table{
		border-collapse: collapse;
		width: 100%;
		white-space: nowrap;
		text-align:center;
	}
	
	th{
		padding: 3px;
	}
	tr,td,th{
		border: 1px solid #ccc;
		padding: 2px 2px 3px;
	}
	tbody.last-row > tr:last-child{
		background: #ffc;
	}
	tbody.first-row > tr:first-child{
		background: #ffc;

	}
	thead > th{
		border: 1px solid;
	}
	p{
		text-align: center;
		color: green;
		margin-top: -30px;
	}
	.list-student-fee > thead > tr > th{
		border: 1px solid;
		padding: 5px 5px;

	}
	table tr > td> input {
		height: 30px;
		width: 100%;
		padding: 5x;

	}
	select{
		height: 30px;
		width: 130px;
	}
	caption {
	    background: transparent;
	    border: none;
	    color: #fff;
	    font-size: 15px;
	    padding-bottom: 5px;
	    background: rgba(255, 255, 255, 0.2);
	    padding: 0 10px;
	    font-weight: bold;
	    margin-bottom: 20px;
	    text-align: center;
	}
	caption ul li {
	    list-style: none;
	    float: left;
	    text-align: center;
	    padding: 0 4% 0 4%;
	}
	.panel-heading{
		display: inline-flex;
		width: 100%;
	}
	
	.disabled-bg{
		background: #e5e5e5;

	}
	.balance-red{
		font-weight: bold;
		color: red;
	}
	.balance-green{
		font-weight: bold;
		color: green;

	}
	.bg-first{
		background: #eee;
		border: 1px solid #ccc;
	}
	#dilog-form-student-school-fee{
		display: none;
	}
	#dilog-form-list-sale-product{
		display: none;
	}
	#fees{
		height: 10px;
		width: 100%;
		padding: 5px;
		background: rgb(245, 245, 245);
	}
	.create-fee{
		    cursor: pointer;
		    color: blue;
		    padding: 3px 7px;
		    width: 100%;
		    float: right;
		    position: absolute;
		    overflow: hidden;
		    display: block;
		    text-align: right;
	}
	
</style>
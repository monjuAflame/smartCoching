export default{

	state: {
	    userList:[],
	    userMessage:[]
	},
	mutations: {
		userList(state,payload){
			state.userList=payload;
		},
		userMessage(state,payload){
			state.userMessage=payload;
		}
	},
	actions: { 
		userList(context){
			axios.get('api/user_list')
			.then(response=>{
                context.commit("userList",response.data);
            })
            .catch(()=>{})
		},
		userMessage(context,payload){
			axios.get('api/user_message/'+payload)
			.then(response=>{
                context.commit("userMessage",response.data);
            })
            .catch(()=>{})
		}
	},
	getters: {  
		userList(state){
			return state.userList;
		},
		userMessage(state){
			return state.userMessage;
		}
	}
	
}
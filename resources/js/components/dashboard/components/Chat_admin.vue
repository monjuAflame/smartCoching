<template>
  <div class="card direct-chat direct-chat-warning collapsed-card">
                  <div class="card-header">
                    <h3 class="card-title">Direct Chat</h3>

                    <div class="card-tools">
                      
                      <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                      </button>
                      <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
                      </button>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="container-chat">
                      <div class="clearfix">

                        <div class="people-list" id="people-list">
                          <div class="search">
                            <input type="text" placeholder="search" />
                            <i class="fa fa-search"></i>
                          </div>
                          <ul class="list">

                            <li class="clearfix" @click.prevent="selectUser(user.id)" v-for="user in userList">
                              <img :src="getUserPhoto(user.photo)" alt="avatar" />
                              <div class="about">
                                <div class="name">{{ user.name }}</div>
                                <div class="status">
                                  <i class="fa fa-circle online"></i> online
                                </div>
                              </div>
                            </li>
                            
                            
                          </ul>
                        </div>
                        
                        <div class="chat">
                          <div class="chat-header clearfix">
                            <img v-if="userMessage.user" :src="getUserPhoto(userMessage.user.photo)" alt="avatar" />
                            
                            <div v-if="userMessage.user" class="chat-about">
                              <div class="chat-with">
                              {{ userMessage.user.name }}
                              </div>
                              <div  class="chat-num-messages">
                                <p>already 1 902 messages</p>
                              </div>
                               
                            </div>
                            <div v-else="userMessage.user" class="chat-about">
                              <div class="chat-with">
                                Select User
                              </div>
                            </div>
                            <i class="fa fa-star"></i>
                            <ul  v-if="userMessage.user" style="float:right;list-style:none">
                                   <li class="nav-item dropdown">
                                      <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                           ...
                                      </a>

                                      <div class="dropdown-menu dropdown-menu-left" aria-labelledby="navbarDropdown">
                                          <a class="dropdown-item" style="cursor:pointer" @click="deleteAllMessage(userMessage.user.id)">
                                              delete all
                                          </a>

                                          <form id="logout-form"  method="POST" style="display: none;">
                                              @csrf
                                          </form>
                                      </div>
                                  </li>
                                </ul>
                          </div> <!-- end chat-header -->
                          
                          <div class="chat-history" v-chat-scroll="{always: false, smooth: true, scrollonremoved:true}">
                            <ul>
                              <li class="clearfix" v-if="userMessage.messages" v-for="message in userMessage.messages" :key="message.id">
                                <div class="message-data" :class="message.user.id==userMessage.user.id? 'align-right':'align-left'">
                                  
                                  <span class="message-data-name" >{{message.user.name}}</span> <i class="fa fa-circle me"></i>
                                  <span class="message-data-time" >{{ message.created_at | dateTime }}</span> &nbsp; &nbsp;
                                  <ul :class="message.user.id==userMessage.user.id? 'float-left':'float-right'">
                                   <li class="nav-item dropdown">
                                      <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                           ...
                                      </a>

                                      <div class="dropdown-menu dropdown-menu-left" aria-labelledby="navbarDropdown">
                                          <a class="dropdown-item" style="cursor:pointer" @click="deleteSingleMessage(message.id)">
                                              delete
                                          </a>

                                          <form id="logout-form"  method="POST" style="display: none;">
                                              @csrf
                                          </form>
                                      </div>
                                  </li>
                                </ul>
                                  
                                </div>
                                
                                <div v-if="message.user.id==userMessage.user.id" class="message float-right other-message">
                                  {{ message.message }}
                                </div>
                                <div v-else="message.user.id==userMessage.user.id" class="message float-right my-message">
                                  {{ message.message }}
                                </div>
                              </li>
                              
                              
                              
                             
                              
                              <li>
                                <div class="message-data">
                                  <span class="message-data-name"><i class="fa fa-circle online"></i> Vincent</span>
                                  <span class="message-data-time">10:31 AM, Today</span>
                                </div>
                                <i class="fa fa-circle online"></i>
                                <i class="fa fa-circle online" style="color: #AED2A6"></i>
                                <i class="fa fa-circle online" style="color:#DAE9DA"></i>
                              </li>
                              
                            </ul>
                            
                          </div> 
                          
                          <div class="chat-message clearfix" v-show='!disable' >
                            <textarea @keydown.enter="sendMessage" v-model="message" name="message-to-send" id="message-to-send" placeholder ="Type your message" rows="3" disabled></textarea>
                                    
                            <i class="fa fa-file-o"></i> &nbsp;&nbsp;&nbsp;
                            <i class="fa fa-file-image-o"></i>
                            
                            <button @click="sendMessage" disabled>Send</button>

                          </div>
                          <div class="chat-message clearfix" v-show='disable'>
                            <textarea @keydown.enter="sendMessage" v-model="message" name="message-to-send" id="message-to-send" placeholder ="Type your message" rows="3"></textarea>
                                    
                            <i class="fa fa-file-o"></i> &nbsp;&nbsp;&nbsp;
                            <i class="fa fa-file-image-o"></i>
                            
                            <button @click="sendMessage">Send</button>

                          </div>
                          
                        </div>
                    
                  </div> 
                </div>
                </div>
                  <div class="card-footer">
                    
                  </div>
                </div>
    
</template>

<script>

export default {
  name: "admin-chat",
  components: {
  },

  data(){
      return {
        loading:false,
        disable:false,
        message:null,
      }
  },

  mounted(){
    this.$store.dispatch('userList');
  },
  computed:{
    userList(){
      return this.$store.getters.userList;
    },
    userMessage(){
      return this.$store.getters.userMessage;
    }
  },
  methods:{
    selectUser(userId){
      this.$store.dispatch('userMessage',userId);
      this.disable=true;
    },
    sendMessage(e){
      e.preventDefault();
      if (this.message!='') {
        axios.post('api/send_message',{message:this.message,user_id:this.userMessage.user.id})
        .then(response=>{
          this.selectUser(this.userMessage.user.id);
          this.message='';
        })
        .catch(()=>{})
      }
    },
    deleteSingleMessage(id){
      axios.post('api/deletesinglemessage/'+id)
      .then(response=>{
        this.selectUser(this.userMessage.user.id);
      })
    },
    deleteAllMessage(id){
      axios.post('api/deleteallmessage/'+id)
      .then(response=>{
        this.selectUser(this.userMessage.user.id);
      })
    },

    getUserPhoto(photos){
        let photo = 'img/user/'+ photos;
        return photo;
    }
  },
  created(){
    Fire.$on('loadData',()=>{});
  }
};
</script>

<style scoped lang="scss">


</style>

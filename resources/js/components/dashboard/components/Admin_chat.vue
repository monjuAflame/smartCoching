<template>
  <div class="card direct-chat direct-chat-warning">
                  <div class="card-header">
                    <h3 class="card-title">Direct Chat</h3>

                    <div class="card-tools">
                      
                      <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                      </button>
                      <button type="button" class="btn btn-tool" data-toggle="tooltip" title="Contacts"
                              data-widget="chat-pane-toggle">
                        <i class="fas fa-comments"></i></button>
                      <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
                      </button>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="direct-chat-messages">

                      <div v-for="adminMessage in adminMessages.data" :key='adminMessage.chat_id'>
                          <div v-if="adminChat.user_id==adminMessage.user_id">
                               <div class="direct-chat-msg">
                                  <div class="direct-chat-infos clearfix">
                                    <span class="direct-chat-name float-left">{{ adminMessage.name }}</span>
                                    <span class="direct-chat-timestamp float-right">
                                      {{ adminMessage.chated_date | dateTime }}
                                    </span>
                                  </div>
                                  <img class="direct-chat-img" :src="getUserPhoto(adminMessage.photo)" alt="message user image">
                                  <div class="direct-chat-text">
                                    {{ adminMessage.message }}
                                  </div>
                                </div>
                          </div>
                          <div v-else>
                            <div class="direct-chat-msg right">
                              <div class="direct-chat-infos clearfix">
                                <span class="direct-chat-name float-right">
                                  {{ adminMessage.name }}
                                </span>
                                <span class="direct-chat-timestamp float-left">
                                      {{ adminMessage.chated_date | dateTime }}
                                  
                                </span>
                              </div>
                              <img class="direct-chat-img" :src="getUserPhoto(adminMessage.photo)" alt="message user image">
                              <div class="direct-chat-text">
                                    {{ adminMessage.message }}
                                 
                              </div>
                            </div>
                          </div>
                          

                      </div>
                      <pagination :data="adminMessages" @pagination-change-page="getResults">
                        
                      </pagination>

                      
                      
                      
                      

                    </div>
                    <div class="direct-chat-contacts">
                      <ul class="contacts-list">
                      
                        
                        <li v-for="admin in admins" :key="admin.id">
                          <router-link v-if="adminChat.user_id==admin.id" to="/profile">
                            <img class="contacts-list-img" :src="getUserPhoto(admin.photo)">

                            <div class="contacts-list-info">
                              <span class="contacts-list-name">
                                {{ admin.name }}
                                <small class="contacts-list-date float-right">
                                  {{ admin.created_at | dateTime}}
                                </small>
                              </span>
                            </div>
                          </router-link>
                          <router-link v-else to="/users">
                            <img class="contacts-list-img" :src="getUserPhoto(admin.photo)">

                            <div class="contacts-list-info">
                              <span class="contacts-list-name">
                                {{ admin.name }}
                                <small class="contacts-list-date float-right">
                                  {{ admin.created_at | dateTime}}
                                </small>
                              </span>
                            </div>
                          </router-link>
                        </li>

                      </ul>
                    </div>
                  </div>
                  <div class="card-footer">
                    <form @submit.prevent="sendMessage()">
                      <div class="input-group">
                        <input type="text" name="message" v-model="adminChat.message" placeholder="Type Message ..." class="form-control">
                        <span class="input-group-append">
                          <button type="submit" class="btn btn-warning">Send</button>
                        </span>
                      </div>
                    </form>
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
        adminMessages:{},
        admins:{},
        adminChat : new Form({
          user_id: window.user.id,
          message:null,
          chated_date:null
        })

      }
  },

  
  methods:{
   
    sendMessage(){
      this.$Progress.start()
          this.adminChat.post('api/admin-chat')
          .then((data)=>{
               
                Fire.$emit('loadData');
                this.adminChat.message=null;
                

                // Toast.fire({icon: 'success',title: 'Create in successfully'});
                this.$Progress.finish();
               
             
          })
          .catch(()=>{
            this.$Progress.fail();
             Toast.fire({icon: 'error',title: 'Ops !'});
          })
    },
    loadMessage(){
           if (this.$gate.isAdminOrAuthorOrUser()) {
            
            
            //for pagination
            axios.get('api/admin-chat').then(({ data }) => (this.adminMessages = data.adminChat, this.admins = data.admin));
          }
          
    },
    getResults(page = 1) {
              axios.get('api/admin-chat?page=' + page)
                .then(response => {
                  this.adminMessages = response.data;
                });
    },
    getUserPhoto(photos){
        let photo = 'img/user/'+ photos;
        return photo;
    },
  
  },
  created(){
    this.loadMessage();

    Fire.$on('loadData',()=>{
      setInterval(() => this.loadMessage(), 2000);
      // this.loadMessage();
    });
    

  }
};
</script>

<style scoped lang="scss">


</style>

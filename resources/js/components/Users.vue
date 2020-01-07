<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                    
                    <div class="card-body">

                          <div class="card">

                            <div class="card-header">
                              
                              <div class="mb-3" >
                            <div class="row">
                                <div class="col-md-2">
                                    <strong>Search By : </strong>
                                </div>
                                <div class="col-md-3">
                                    <select v-model="queryField" class="form-control" id="field">
                                        <option value="name">Name</option>
                                        <option value="email">Email</option>
                                        <option value="type">Type</option>
                                        <option value="bio">Bio</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input v-model="query" type="text" class="form-control" placeholder="Search">
                                </div>
                            </div>
                        </div>

                              <div class="card-tools">
                                 <button @click="add" type="button" class="btn btn-success" data-toggle="modal" data-target="#addModal">Add-New <i class="nav-icon fas fa-user-plus"></i></button>
                              </div>
                            </div>
							<!-- /.card-header -->

							<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
					        <div class="modal-dialog">
					          <div class="modal-content bg-success">

					            <div class="modal-header">
					              <h4 class="modal-title"  id="addModalLabel">Success Modal</h4>
					              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					                <span aria-hidden="true">×</span></button>
					            </div>

								<form @submit.prevent="editMode ? update() : createUser()" @keydown="form.onKeydown($event)">
					            <div class="modal-body">

					                <div class="form-group mb-1">
					                    <label>Username</label>
					                    <input v-model="form.name" type="text" name="name"
					                      class="form-control" :class="{ 'is-invalid': form.errors.has('name') }" placeholder="User-Name">
					                    <has-error :form="form" field="name"></has-error>
					                </div>

					                <div class="form-group mb-1">
					                    <label>Email</label>
					                    <input v-model="form.email" type="email" name="email"
					                      class="form-control" :class="{ 'is-invalid': form.errors.has('email') }" placeholder="Email">
					                    <has-error :form="form" field="email"></has-error>
					                </div>

					                <div class="form-group mb-1">
					                     <label>Password</label>
					                     <input v-model="form.password" type="password" name="password"
					                        class="form-control" :class="{ 'is-invalid': form.errors.has('password') }" placeholder="Password">
					                    <has-error :form="form" field="password"></has-error>
					                </div>

					                <div class="form-group mb-1">
								      <label>Bio</label>
								      <textarea v-model="form.bio" name="bio"
								        class="form-control" :class="{ 'is-invalid': form.errors.has('bio') }" placeholder="Bio"></textarea>

								      <has-error :form="form" field="bio"></has-error>
								    </div>

								    <div class="form-group mb-1">
					                    <label>Type</label>
					                    <select v-model="form.type" id="type" name="type"
					                      class="form-control" :class="{ 'is-invalid': form.errors.has('type') }" placeholder="Type">
					                      	<option value="">Select User Role</option>
					                      	<option value="admin">Admin</option>
					                      	<option value="user">Standered User</option>
					                      	<option value="author">Author</option>
					                      </select>

					                    <has-error :form="form" field="type"></has-error>
					                </div>

					                <div class="form-group">
					                    <label>Photo</label>
					                    <input type="file" class="form-control" name="photo" @change="onChange" :class="{ 'is-invalid': form.errors.has('photo') }" placeholder="Photo">

                                        <has-error :form="form" field="photo"></has-error>

                                        <img :src="avatar" alt="" style="height:50px;weight:50px;border-radius:50%;">
					                </div>

					            </div>

					            <div class="modal-footer justify-content-between">
					              <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
					              <button id="buttonText" type="submit" :disabled="form.busy" class="btn btn-outline-light">Save changes</button>
					            </div>

					        	</form>

					          </div>
					          <!-- /.modal-content -->
					        </div>
					        <!-- /.modal-dialog -->
					      </div>

                            <div class="card-body table-responsive p-0">
                              <table class="table table-hover">
                                <thead>
                                  <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Emal</th>
                                    <th>Type</th>
                                    <th>Photo</th>
                                    <th>Action</th>
                                  </tr>
                                </thead>
                                <tbody>

                                  <tr v-for="(result, index) in customers" :key="result.id">
                                    <td>{{ index+1 }}</td>
                                    <td>{{ result.name | upText }}</td>
                                    <td>{{ result.email }}</td>
                                    <td>{{ result.type |upText }}</td>
                                    <td>
                                        <img :src="'imgs/'+result.photo" alt="No image" style="height: 60px; width: 60px;border-radius:50%;">
                                    </td>
                                    <td>

                                    	<a href="#"><button type="button" class="btn btn-secondary"><i class="nav-icon fas fa-eye"></i></button></a>

                                    	<a href="#"><button @click="edit(result)" type="button" class="btn btn-primary"><i class="fas fa-edit"></i></button></a>

                                    	<a href="#"><button @click="destory(result)" type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal"><i class="fas fa-trash"></i></button></a>
                      					
                                    </td>
                                  </tr>
                                  
                                </tbody>
                              </table>
                            </div>
                            <!-- /.card-body -->

                            <!-- <pagination-component></pagination-component> -->

                            
                          </div>
                          <!-- /.card -->
                          <pagination-component v-if="pagination.last_page > 1" 
                                
                                :pagination="pagination"
                                :offset="5"
                                @paginate="query === '' ? getData() : searchData()"
                                ></pagination-component>


							
							<div class="modal fade show" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
							    <div class="modal-dialog">
							      <div class="modal-content bg-danger">

							        <div class="modal-header">
							          <h4 class="modal-title" id="deleteModalLabel">Danger Modal</h4>
							          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
							            <span aria-hidden="true">×</span>
							          </button>
							        </div>

									<form @submit.prevent="destory_info()" @keydown="form.onKeydown($event)">
							        <div class="modal-body">
							          <h5 class="card-title">Are you sure ?</h5>
							        </div>

							        <div class="modal-footer justify-content-between">
							          <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
							           <button :disabled="form.busy" type="submit" class="btn btn-primary">Yes</button>
							        </div>
									</form>

							      </div>
							          <!-- /.modal-content -->
							    </div>
							        <!-- /.modal-dialog -->
							  </div>
							
                        
                    </div>

                    <vue-snotify></vue-snotify>

            </div>

        </div>
    </div>

</template>

<script>
    export default {

    	data () {
    	  return {
            avatar: '',
    	  	editMode: false,
    	  	customers: [],

            query: '',
            queryField: 'name',
    	  	pagination:{
    			current_page: 1,
    		},

    	    // Create a new form instance
    	    form: new Form({
    	      id: '',
    	      name: '',
    	      email: '',
    	      password: '',
    	      type: '',
    	      bio: '',
    	      photo: '',
    	    })
    	  }
    	},

        watch: {
            query:function(newQ, old){
                if(newQ === ''){
                    this.getData();
                }
                else{
                    this.searchData()
                }
            }
        },

    	methods: {

    		getData(){
    			// axios.get('/api/visitor').then(({ data }) => (this.visitor = data));
    			axios.get('/api/visitor?page='+this.pagination.current_page)
        		.then(response => {
        			this.customers=response.data.data;
        			this.pagination=response.data.meta;
        		})

        		.catch(e => {
        			console.log(e);
        		})
    		},

            searchData(){
                axios.get('/api/search/visitor/'+this.queryField+'/'+this.query+'?page='+this.pagination.current_page)

                .then(response => {
                    this.customers=response.data.data;
                    this.pagination=response.data.meta;
                })

                .catch(e => {
                    console.log(e)
                })
            },

    		add(){
    			this.editMode = false;
    			$('#addModalLabel').text('Add Customer Info..');
    			$('#buttonText').text('Insert-Info');
    			this.form.reset();
        		this.form.clear();
    		},
            
        

            onChange(e){
                    let image = e.target.files[0];
                    let reader = new FileReader();
                    reader.readAsDataURL(image);
                    reader.onload = e => {
                        this.avatar = e.target.result
                        this.form.photo = e.target.result
                    }
                    
                },


    		createUser(){
    			this.form.busy = true;
        		this.form.post('/api/visitor',{photo: this.avatar})

        		.then(response => {
        			this.getData()
        			$('#addModal').modal('hide');

        			if(this.form.successful){
        				this.$snotify.success('Data Successfully Reloaded...')
        			}
        			else{
        				this.$snotify.error('Something is errror..!')
        			}
        		})

        		.catch(e => {
        			console.log(e)
        		})
                
    		},

    		edit(result){
    			this.editMode = true;
    			this.form.reset();
        		this.form.clear();
        		this.form.fill(result)
                this.avatar = "imgs/" + this.form.photo;
        		$('#addModal').modal('show');
        		$('#buttonText').text('Update');
        		$('#addModalLabel').text('Update Customer Info..');
    		},

    		update(){
        		this.form.busy = true;
        		this.form.put('/api/visitor/'+this.form.id)

        		.then(response => {
        			this.getData()
        			$('#addModal').modal('hide');

        			if(this.form.successful){
        				this.$snotify.success('Data Updated Successfully...')
        			}
        			else{
        				this.$snotify.error('Something is errror..!')
        			}
        		})

        		.catch(e => {
        			console.log(e)
        		})
        	},

    		destory(result){
    			this.form.reset();
        		this.form.clear();
        		this.form.fill(result)
        		$('#deleteModal').modal('show');
        		$('#deleteModalLabel').text('Delete Customer Info..');
    		},

    		destory_info(){
    			this.form.delete('/api/visitor/'+this.form.id)

        		.then(response => {
        			this.getData()
        			$('#deleteModal').modal('hide');

        			if(this.form.successful){
        				this.$snotify.success('User Info Deleted Successfully Reloaded...')
        			}
        			else{
        				this.$snotify.error('Something is errror..!')
        			}
        		})

        		.catch(e => {
        			console.log(e)
        		})
    		}


    			
    	},

        mounted() {
            this.getData()
            // setInterval(() => this.getData(), 3000)
        }
    }
</script>

<template>
    <div class="container">
        <div class="row pt-5" >
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3>User Management</h3>

                        <div class="card-tools">
                            <button type="" class="btn btn-primary" @click="newModal"><i class="fa fa-user-plus fa-fw"></i> Add New User</button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <tbody>
                            <tr class="bg-light">
                                <th>S.N.</th>
                                <th style="width:25%">Name</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            <tr v-for="(user, index) in users.data" :key="user.id">
                                <td>{{index + 1}}</td>
                                <td>{{user.name}}
                                </td>
                                <td>{{user.email}}</td>
                                <td>
                                <button v-if="user.is_verified == '1'" class="btn btn-sm btn-success"><i class="green fa fa-check"></i>Verified </button>   
                                <button v-else-if="user.is_verified == '2'" class="btn btn-sm btn-danger"><i class="red fa fa-check"></i>Disapproved </button>   
                                <button v-else class="btn btn-sm btn-success"><i class="orange fa fa-circle"></i>Pending </button>   
                                </td>
                                <td>
                                    <a href="#" @click="editModal(user)" class="btn btn-sm btn-success mr-2">Edit
                                        <i class="fa fa-edit"></i>
                                    </a>
                                     <a href="#" @click="updateProfileModal(user)" class="btn btn-sm btn-warning mr-2">Update Profile
                                        <i class="fa fa-user"></i>
                                    </a>
                                    <a href="#" @click="verify(user,1)" class="btn btn-sm btn-success mr-2">Verify User
                                        <i class="fa fa-check"></i>
                                    </a>
                                    <a href="#" @click="deleteUser(user.id)" class="btn btn-sm btn-danger mr-2">Delete
                                        <i class="fa fa-trash"></i>
                                    </a>
                                   
                                </td>
                            </tr>
                            </tbody></table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <pagination-wrapper class="mt-3" :data="this.users" :has_param="false" :api_url="api_url" pagination_title="Users"></pagination-wrapper>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
       
        <!-- Modal -->
        <div class="modal fade" id="addNewUser" tabindex="-1" role="dialog" aria-labelledby="addNewUserLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg  modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="!editmode" id="addNewUserLabel">Add New User</h5>
                        <h5 class="modal-title" v-show="editmode" id="addNewUserLabel">Update User</h5>
                        
                    </div>
                    <form  @submit.prevent="editmode ? updateUser() : createUser()">
                        <div class="modal-body">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="first_name" >Name *</label>
                                                <input type="text" v-model="form.name" class="form-control"  placeholder="Name" :class="{ 'is-invalid': form.errors.has('name') }">
                                                <has-error :form="form" field="name"></has-error>
                                        </div>

                            
                                        <div class="form-group col-md-6">
                                            <label for="" >Email *</label>
                                                <input type="email" v-model="form.email" class="form-control" id="inputEmail" placeholder="Email" :class="{ 'is-invalid': form.errors.has('email') }">
                                                <has-error :form="form" field="email"></has-error>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <h6>Roles</h6>
                                            <multiselect v-model="form.roles"
                                                tag-placeholder="Roles"
                                                placeholder="Select Roles"
                                                label="name" track-by="name"
                                                :options="roles"
                                                :multiple="true"
                                                :taggable="true">
                                            </multiselect>
                                        </div>
                                        
                                        <div class="form-group col-md-6">
                                            <label for="password" >Password *</label>

                                                <input type="password" v-model="form.password"  @input="check" class="form-control" :class="{ 'is-invalid': isActive, 'is-equal':fc}"  id="password" placeholder="Password">
                                                <has-error :form="form" field="password"></has-error>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="confirmpassword">Confirm password *</label>
                                                <input type="password" v-model="form.confirmpassword" @input="check" class="form-control" :class="{ 'is-invalid': isActive, 'is-equal':fc}" id="confirmpassword" placeholder="Password">
                                                <has-error :form="form" field="confirmpassword"></has-error>
                                        </div>
                                    </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa fa-times fa-fw"></i>Close</button>
                            <button v-show="editmode" type="submit" class="btn btn-success"><i class="fa fa-plus fa-fw"></i>Update</button>
                            <button v-show="!editmode" type="submit" class="btn btn-primary"><i class="fa fa-plus fa-fw"></i>Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="addNewProfile" tabindex="-1" role="dialog" aria-labelledby="addNewProfileLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg  modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addNewProfileLabel">Update Profile</h5>
                    </div>
                    <form  @submit.prevent="updateProfile()">
                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <h6>Group</h6>
                                    <multiselect v-model="pform.group_id"
                                        tag-placeholder="Select Group"
                                        placeholder="Select Group"
                                        label = "name"
                                        track_by = "id"
                                        :options="Object.keys(groups).map(Number)"
                                        :custom-label="opt => groups[opt]"
                                        :multiple="false"
                                        :allow-empty="false"
                                        :taggable="true">
                                    </multiselect>
                                    <has-error :form="pform" field="group_id"></has-error>

                                </div>
                                <div class="form-group col-md-6">
                                    <h6>Duty Station</h6>
                                    <multiselect v-model="pform.duty_station"
                                        tag-placeholder="Select Duty Station"
                                        placeholder="Select Duty Station"
                                        label = "name"
                                        track_by = "id"
                                        :options="Object.keys(duty_stations).map(Number)"
                                        :custom-label="opt => duty_stations[opt]"
                                        :multiple="false"
                                        :allow-empty="false"
                                        :taggable="true">
                                    </multiselect>
                                <has-error :form="pform" field="duty_station"></has-error>
                                </div>
                                <div class="form-group col-md-6">
                                    <h6>Supervisor</h6>
                                    <multiselect v-model="pform.supervisor"
                                        tag-placeholder="Select Supervisor"
                                        placeholder="Select Supervisor"
                                        label = "name"
                                        track_by = "id"
                                        :options="Object.keys(supervisors).map(Number)"
                                        :custom-label="opt => supervisors[opt]"
                                        :multiple="false"
                                        :allow-empty="false"
                                        :taggable="true">
                                    </multiselect>
                                <has-error :form="pform" field="supervisor"></has-error>
                                </div>
                            </div> 
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa fa-times fa-fw"></i>Close</button>
                            <button  type="submit" class="btn btn-success"><i class="fa fa-plus fa-fw"></i>Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import Multiselect from 'vue-multiselect'
    import Form from 'vform'
    import { Button, HasError, AlertError } from 'vform/src/components/bootstrap5'
    import PaginationWrapper from '../Pagination/PaginationWrapper.vue';

    export default {
        components: {
            Multiselect,
            HasError,
            PaginationWrapper,
        },
        /*Filling the data into form*/
        data() {

            return {
                editmode: false,
                totaluser: 0,
                users: {},
                roles: [],
                groups:[],
                duty_stations:[],
                supervisors:[],
                isActive: true,
                fc: false,
                form: new Form({
                    id: null,
                    name: null,
                    email: null,
                    password: null,
                    confirmpassword: null,
                    roles: null,
                    is_verified:null,
                }),
                pform: new Form({
                    id: null,
                    name:null,
                    email:null,
                    user_id:null,
                    duty_station:null,
                    group_id:null,
                    emp_code:null,
                    supervisor:null,
                }),
                api_url:null,
                
            }
        },
        methods: {
            /*===== Call add new user modal ====*/
            newModal() {
                this.editmode = false;
                this.form.reset();
                $('#addNewUser').modal('show');
            },
            updateProfileModal(user) {
                this.pform.reset();
                $('#addNewProfile').modal('show');
                if(user.profile) this.pform.fill(user.profile);
                this.pform.name = user.name
                this.pform.email = user.email
                this.pform.user_id = user.id
            },
            /*Create User Function Starts*/
            createUser() {
                this.$Progress.start(); //start a progress bar
                this.form.post('/api/user') // POST form data
                    //Start Condition to check form is validate
                    .then(() => {
                        this.emitter.emit('AfterCreate'); //custom event to reload data
                        $("#addNewUser").modal('hide'); //Hide the model
                        //Sweetalert notification for the result
                        this.$swal({
                            type: 'success',
                            title: 'User Created Successfully',
                            icon: 'success',
                        })

                        this.$Progress.finish(); //End the progress bar
                    })
                    //if form is not valid of handle any errors
                    .catch(() => {
                        this.$swal(
                            'Error!',
                            'Something Went Wrong.',
                            'warning'
                        )

                        this.$Progress.fail(); //End the progress bar
                    })
            },

            /*==== End of User Create ====*/

            /*==== Call edit Modal with user data ====*/
            editModal(user) {
                this.editmode = true;
                this.form.reset();
                $('#addNewUser').modal('show');
                this.form.fill(user);
            },
            /*Edit User Function*/
            updateUser(id) {
                this.$Progress.start();
                this.form.put('/api/user/' + this.form.id)
                    .then(() => {
                        $("#addNewUser").modal('hide'); //Hide the model
                        this.$swal(
                            'Updated!',
                            'User info. has been updated.',
                            'success'
                        )
                        this.$Progress.finish();
                        this.emitter.emit('AfterCreate'); //Fire an reload event

                    }).catch(() => {
                    this.$swal(
                        'Error!',
                        'Something Went Wrong.',
                        'warning'
                    )
                    this.$Progress.fail();
                });
            },
            /*==== End of edit user function ====*/
            /*==== Call Delete Modal uith user id ====*/
            deleteUser(id) {
                this.$swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {

                    //send an ajax request to the server
                    if (result.value) {
                        this.form.delete('/api/user/' + id).then(() => {
                            this.$swal(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            )
                            this.emitter.emit('AfterCreate'); //Fire an reload event
                        }).catch(() => {
                            this.$swal(
                                'Warning!',
                                'Unauthorized Access to delete.',
                                'warning'
                            )
                        })
                    }

                })
            },
            verify(user,val) {
                this.$swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, verify it!'
                }).then((result) => {

                    //send an ajax request to the server
                    if (result.value) {
                        this.form.fill(user);
                        this.form.is_verified = val;
                        this.form.put('/api/user/'+this.form.id).then(() => {
                            this.$swal(
                                'Updated!',
                                'User info. has been updated.',
                                'success'
                            )
                            this.emitter.emit('AfterCreate'); //Fire an reload event
                        }).catch(() => {
                            this.$swal(
                                'Warning!',
                                'Unauthorized Access to verify.',
                                'warning'
                            )
                        })
                    }

                })
            },
            /*==== End of Delete Modal ====*/
            check() {
                if (this.form.password == this.form.confirmpassword) {
                    this.isActive = false;
                    this.fc = true;
                } else {
                    this.isActive = true;
                    this.fc = false;
                }
            },
            async updateProfile() {
                try{
                    const response = await this.pform.put('/api/admin_updated_profile/'+this.pform.id);
                    if(response.data.error == 'false'){
                        this.$swal(
                                'Updated!',
                                response.data.message,
                                'success'
                            )
                        this.$Progress.finish();
                        $('#addNewProfile').modal('hide');
                    }else{
                        this.$swal(
                                'Error!',
                                response.data.message,
                                'warning'
                            )
                        this.$Progress.fail();
                    }
                }catch(error){
                    this.$swal(
                                'Error!',
                                error.response.data.message,
                                'warning'
                            )
                    this.$Progress.fail();
                }
            },

            /*==== Start of Show existing User function ====*/
            async loadUsers() {
                const {data}  = await  axios.get("/api/user")
                this.users = data.data,
                this.roles = data.roles
                this.groups = data.groups
                this.duty_stations = data.duty_stations
                this.supervisors = data.supervisors
                this.api_url = 'api/user'
                /*==== End of existing User ====*/
            },
        },
        created() {
            this.loadUsers(); //load the user in the table
            //Load the userlist if add or created a new user
            this.emitter.on("AfterCreate", () => {
                this.loadUsers();
            })
            //emit event on pagination
            this.emitter.on('paginating',(item)=>{
                this.users = item
            })
        }
    }
</script>

<style scoped>
.is-equal{

box-shadow: 0 0 0 0.2rem rgb(73 231 25 / 25%) !important;
}
</style>



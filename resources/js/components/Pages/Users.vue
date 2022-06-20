<template>
    <div class="container">
        <div class="row pt-5" >
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between p-2">
                        <h3>User Management</h3>

                        <div class="card-tools">
                            <button type="" class="btn btn-primary" @click="newModal"><i class="fa fa-user-plus fa-fw"></i> Add New User</button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover m-0">
                            <tbody>
                            <tr class="bg-light">
                                <th>S.N.</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Status</th>                                
                                <th>Contact</th>
                                <th>Actions</th>
                                <th>Contract Info</th>
                            </tr>
                            <tr v-for="(user, index) in users.data" :key="user.id">
                                <td>{{index + 1}}</td>
                                <td>{{user.name}}
                                </td>
                                <td>{{user.email}}</td>
                                <td>
                                <p v-if="user.is_verified == '1'" class="">Verified </p>   
                                <p v-else-if="user.is_verified == '2'" class="">Disapproved </p>   
                                <p v-else class="">Pending </p>   
                                </td>
                                <td>{{user.profile ? user.profile.phone : ''}}</td>
                                <td>
                                    <a href="#" @click="editModal(user)" class=" text-success mr-2">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                     <a href="#" @click="updateProfileModal(user)" class=" text-warning mr-2">
                                        <i class="fa fa-user"></i>
                                    </a>
                                    <a href="#" @click="verify(user,1)" class=" text-success mr-2">
                                        <i class="fa fa-check"></i>
                                    </a>
                                    <a href="#" @click="deleteUser(user.id)" class=" text-danger mr-2">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                   
                                </td>
                                  <td>
                                    <div class="accordion" id="accordionExample">
                                        <div class="accordion-item rounded-0">
                                            <button data-bs-toggle="collapse" :data-bs-target="'#history'+user.id" class="accordion-button rounded-0 " type="button">
                                                <a href="#" data-bs-toggle="collapse" :data-bs-target="'#history'+user.id" aria-expanded="true" aria-controls="collapseOne">Contract History</a>

                                            <a href="#" type="" class="ml-5" @click="newContractModal(user.id,user.name)"><i class="fa fa-plus fa-fw"></i> </a>
                                            </button>

                                            <div :id="'history'+user.id" class="accordion-collapse collapse " aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <table class="table table-hover">
                                                    <tbody>
                                                    <tr class="bg-light">
                                                        <th>Start Date </th>
                                                        <th>End Date</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                    <tr v-for="contract in user.contracts" :key="contract.id">
                                                        <td>{{contract.contract_start}}</td>
                                                        <td>{{contract.contract_end}}</td>
                                                        <td>
                                                            <a href="#" @click="editContractModal(contract)" class="m-2">
                                                                <i class="fa fa-edit"></i>
                                                            </a>
                                                            <a href="#" @click="deleteContract(contract.id)" class="">
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                                                            
                                                        </td>

                                                    </tr>
                                                    </tbody>
                                                </table>
                                

                                            </div>
                                            </div>
                                        </div>
                                    </div>
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

        <!-- contact Modal -->
         <modal :form="form" :modal_data="c_modal_data" :editmode="editmode" :api_url="'contract'">
            <h4 class="modal-header">Contract Information : {{this.form.name}}</h4>
            
            <div class="form-group col-md-6">
                <label for="first_name" >Contract Start Date*</label>
                    <input type="text" v-model="form.contract_start" class="form-control"  placeholder="Contract Start Date" :class="{ 'is-invalid': form.errors.has('contract_start') }">
                    <has-error :form="form" field="contract_start"></has-error>
            </div>
            <div class="form-group col-md-6">
                <label for="first_name" >Contract End Date*</label>
                    <input type="text" v-model="form.contract_end" class="form-control"  placeholder="Contract End Date" :class="{ 'is-invalid': form.errors.has('contract_end') }">
                    <has-error :form="form" field="contract_end"></has-error>
            </div>
            <div class="form-group col-md-6">
                <h6>Staff Type</h6>
                <multiselect v-model="form.staff_type_id"
                    tag-placeholder="Saff Type"
                    placeholder="Select Staff Type"
                    :options="Object.keys(staff_types).map(Number)"
                    :custom-label="opt => staff_types[opt]"
                    :multiple="false"
                    :allow-empty="false"
                    :taggable="true"
                    >
                </multiselect>
            </div> 

            <div class="form-group col-md-6">
                <h6>Designation</h6>
                <multiselect v-model="form.designation_id"
                    tag-placeholder="Designation"
                    placeholder="Select Designation"
                    :options="Object.keys(designations).map(Number)"
                    :custom-label="opt => designations[opt]"
                    :multiple="false"
                    :allow-empty="false"
                    :taggable="true"
                    >
                </multiselect>
            </div> 
            <div class="form-group col-md-6">
                <h6>Contract Type</h6>
                <multiselect v-model="form.contract_type_id"
                    tag-placeholder="Contract Type"
                    placeholder="Select Contract Type"
                    :options="Object.keys(contract_types).map(Number)"
                    :custom-label="opt => contract_types[opt]"
                    :multiple="false"
                    :allow-empty="false"
                    :taggable="true"
                    >
                </multiselect>
            </div> 
            <div class="form-group col-md-6">
                <h6>Staff Category</h6>
                <multiselect v-model="form.staff_category_id"
                    tag-placeholder="Staff Category"
                    placeholder="Select Staff Category"
                    :options="Object.keys(staff_categories).map(Number)"
                    :custom-label="opt => staff_categories[opt]"
                    :multiple="false"
                    :allow-empty="false"
                    :taggable="true"
                    >
                </multiselect>
            </div> 
            <div class="col-md-6 mt-3">
                <div class="form-group  form-control">
                    <input v-model="form.is_active" true-value="1" false-value="0" type="checkbox" name="is_active"  class="form-check-input" :class="{ 'is-invalid': form.errors.has('is_active') }">
                    <label class="form-check-label" for="1">
                        Is Active
                    </label>
                </div>
            </div>
        </modal>

    </div>
</template>
<script>
    import Multiselect from 'vue-multiselect'
    import Form from 'vform'
    import { Button, HasError, AlertError } from 'vform/src/components/bootstrap5'
    import PaginationWrapper from '../Pagination/PaginationWrapper.vue';
    import Modal from '../Modal.vue';

    export default {
        components: {
            Multiselect,
            HasError,
            PaginationWrapper,
            Modal
        },
        /*Filling the data into form*/
        data() {

            return {
                editmode: false,
                totaluser: 0,
                users: {},
                roles: this.$store.state.roles,
                groups:[],
                duty_stations:[],
                supervisors:[],
                designations:[],
                staff_categories:[],
                contract_types:[],
                staff_types:[],

                c_modal_data:{
                    modal_size:'modal-lg',
                    modal_name:'addNewContract'
                },
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
                    pillar_id: null,
                    supervisor_user_id:null,
                    user_id:null,
                    contract_type_id:null,
                    contract_start:null,
                    contract_end:null,
                    is_active: null,
                    staff_type_id:null,
                    designation_id:null,
                    staff_category_id:null,
                    contract_type_id:null
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

            newContractModal(id,name) {
                this.editmode = false;
                this.form.reset();
                this.form.user_id = id;
                this.form.name = name
                $('#addNewContract').modal('show');
            },
            editContractModal(contract){
                this.editmode = true;
                $('#addNewContract').modal('show');
                this.emitter.emit('editing', contract);
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
                this.users = data,
                
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




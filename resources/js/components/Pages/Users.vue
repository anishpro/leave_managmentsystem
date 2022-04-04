<template>
    <div class="container">
        <div class="row pt-5" v-if="$gate.isSuperAdminOrSuperDev()">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="header d-inline">User Management</h3>

                        <div class="card-tools">
                            <button type="" class="btn btn-primary" @click="newModal"><i class="fas fa-user-plus fa-fw"></i> Add New User</button>
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
                                <th>Actions</th>
                            </tr>
                            <tr v-for="(user, index) in users.data" :key="user.id">
                                <td>{{index + 1}}</td>
                                <td>
                                    <p v-if="user.name.middle_name != null">{{user.name.first_name}} {{user.name.middle_name}} {{user.name.last_name}}</p>
                                    <p v-else-if="user.name.first_name != null">{{user.name.first_name}}  {{user.name.last_name}}</p>
                                    <p v-else>{{user.name}}</p>
                                </td>
                                <td>{{user.email}}</td>
                                <td>
                                    <a href="#" @click="editModal(user)" class="btn btn-sm btn-success">Edit
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="#" @click="deleteUser(user.id)" class="btn btn-sm btn-danger">Delete
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            </tbody></table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-6 pull-left">
                                <span>Showing {{ users.data && users.data.length}} of {{this.totaluser}} Users.</span>
                            </div>
                            <div class="col-md-6 pull-right">
                                <pagination :data="users" @pagination-change-page="getResults"></pagination>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
        <div v-if="!$gate.isSuperAdminOrSuperDev()">
            <not-found></not-found>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addNewUser" tabindex="-1" role="dialog" aria-labelledby="addNewUserLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg  modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="!editmode" id="addNewUserLabel">Add New User</h5>
                        <h5 class="modal-title" v-show="editmode" id="addNewUserLabel">Update User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form  @submit.prevent="editmode ? updateUser() : createUser()">
                        <div class="modal-body">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="first_name" >पहिलो(First) *</label>
                                                <input type="text" v-model="form.name.first_name" class="form-control"  placeholder="First Name" :class="{ 'is-invalid': form.errors.has('name.first_name') }">
                                                <has-error :form="form" field="name.first_name"></has-error>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="middle_name" >बीचको(Middle)</label>
                                                <input type="" v-model="form.name.middle_name" class="form-control"  placeholder="Middle Name" :class="{ 'is-invalid': form.errors.has('name.middle_name') }">
                                                <has-error :form="form" field="name.middle_name"></has-error>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="last_name" >थर(Last) *</label>
                                                <input type="text" v-model="form.name.last_name" class="form-control"  placeholder="Last Name" :class="{ 'is-invalid': form.errors.has('name.last_name') }">
                                                <has-error :form="form" field="name.last_name"></has-error>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="" >इमेल:(Email) *</label>
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

                                                <input type="password" v-model="form.password" class="form-control" v-bind:class="{ 'is-invalid': isActive, 'is-equal':fc}"  id="password" placeholder="Password">
                                                <has-error :form="form" field="password"></has-error>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="confirmpassword">Confirm password</label>
                                                <input type="password" v-model="form.confirmpassword" @input="check" class="form-control" v-bind:class="{ 'is-invalid': isActive, 'is-equal':fc}" id="confirmpassword" placeholder="Password">
                                                <has-error :form="form" field="confirmpassword"></has-error>
                                        </div>
                                    </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times fa-fw"></i>Close</button>
                            <button v-show="editmode" type="submit" class="btn btn-success"><i class="fas fa-plus fa-fw"></i>Update</button>
                            <button v-show="!editmode" type="submit" class="btn btn-primary"><i class="fas fa-plus fa-fw"></i>Create</button>
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

    export default {
        components: {
            Multiselect,
            HasError
        },
        /*Filling the data into form*/
        data() {

            return {
                editmode: false,
                totaluser: 0,
                users: {},
                roles: [],
                isActive: true,
                fc: false,
                form: new Form({
                    id: '',
                    name: {
                        first_name: '',
                        middle_name: '',
                        last_name: '',
                    },
                    gender: '',
                    dob: '',
                    email: '',
                    contact_info: {
                        phone: '',
                        telephone: '',
                        fax: '',
                        website: '',
                    },
                    address_info: {
                        permanent_address: {
                            state: '',
                            district: '',
                            municipality: '',
                            ward: '',
                            tole: '',
                            house_no: '',
                        },
                        temporary_address: {
                            state: '',
                            district: '',
                            municipality: '',
                            ward: '',
                            tole: '',
                            house_no: '',
                        }
                    },
                    official_info: {
                        document_image: '',
                        document_type: '',
                        document_no: '',
                        issued_date: '',
                        issued_office: ''
                    },
                    relatives_info: {
                        spouse_detail: {
                            name: '',
                        },
                        father_detail: {
                            name: '',
                            address: '',
                            citizenship_no: ''
                        },
                        grandfather_detail: {
                            name: '',
                        },
                        mother_detail: {
                            name: '',
                        },
                    },
                    password: '',
                    confirmpassword: '',
                    slug: '',
                    photo: '',
                    role: '',
                    roles: ''
                })
            }
        },
        methods: {

            /*===== Start of pagination function =====*/
            getResults(page = 1) {
                axios.get('../api/user?page=' + page)
                    .then(response => {
                        this.users = response.data.users;
                    });
            },

            /*===== Call add new user modal ====*/
            newModal() {
                this.editmode = false;
                this.form.reset();
                $('#addNewUser').modal('show');
            },
            /*Create User Function Starts*/
            createUser() {
                this.$Progress.start(); //start a progress bar
                this.form.post('../api/user') // POST form data
                    //Start Condition to check form is validate
                    .then(() => {
                        Fire.$emit('AfterCreate'); //custom event to reload data

                        $("#addNewUser").modal('hide'); //Hide the model

                        //Sweetalert notification for the result
                        Toast.fire({
                            type: 'success',
                            title: 'User Created Successfully'
                        })

                        this.$Progress.finish(); //End the progress bar
                    })
                    //if form is not valid of handle any errors
                    .catch(() => {
                        swal.fire(
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
                //console.log('editing data');
                this.form.put('../api/user/' + this.form.id)
                    .then(() => {
                        $("#addNewUser").modal('hide'); //Hide the model
                        swal.fire(
                            'Updated!',
                            'User info. has been updated.',
                            'success'
                        )
                        this.$Progress.finish();
                        Fire.$emit('AfterCreate'); //Fire an reload event

                    }).catch(() => {
                    swal.fire(
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
                swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {

                    //send an ajax request to the server
                    if (result.value) {
                        this.form.delete('../api/user/' + id).then(() => {
                            swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            )
                            Fire.$emit('AfterCreate'); //Fire an reload event
                        }).catch(() => {
                            swal.fire(
                                'Warning!',
                                'Unauthorized Access to delete.',
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

            /*==== Start of Show existing User function ====*/
            loadUsers() {
                if (this.$gate.isSuperAdminOrSuperDev()) {
                    axios.get("../api/user")
                        .then(({data}) => (
                            this.users = data.users,
                                this.totaluser = data.users.total,
                                this.roles = data.roles

                        ));
                }
                /*==== End of existing User ====*/
            },
        },
        created() {
            Fire.$on('searching', () => {
                let query = this.$parent.search; //take information from root
                axios.get('../api/findUser?q=' + query)
                    .then((data) => {
                        this.users = data.data
                    }).catch(() => {

                })
            })
            this.loadUsers(); //load the user in the table
            //Load the userlist if add or created a new user
            Fire.$on("AfterCreate", () => {
                this.loadUsers();
            })
        }
    }
</script>



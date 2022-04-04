<template>
    <div class="container-fluid">
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Role Management</h3>

                        <div class="card-tools">
                            <button type="" class="btn btn-primary" @click="newModal"><i class="fa fa-user-plus fa-fw"></i>Add New Role</button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <tbody>
                            <tr>
                                <th>S.N.</th>
                                <th>Name</th>
                                <th>Guard Name</th>
                                <th>Actions</th>
                            </tr>
                            <tr v-for="(role,index) in roles" :key="role.id">
                                <td>{{index+1}}</td>
                                <td>{{role.name}}</td>
                                <td>{{role.guard_name}}</td>
                                <td>
                                    <a href="#" @click="editModal(role)" class="btn btn-sm btn-success">Edit
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="#" @click="deleteRole(role.id,role.name)" class="btn btn-sm btn-danger">Delete
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            </tbody></table>
                    </div>
                    <!-- /.card-body -->
                    
                </div>
                <!-- /.card -->
            </div>
        </div>

        <!--User Create Edit Modal-->
        <div class="modal fade" id="addNewRole" tabindex="-1" role="dialog" aria-labelledby="addNewRoleLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="!editmode" id="addNewRoleLabel">Add New Role</h5>
                        <h5 class="modal-title" v-show="editmode" id="addNewRoleLabel">Update {{role}}</h5>
                    </div>
                    <form  @submit.prevent="editmode ? updateRole() : createRole()">
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="input-group mb-2 mr-sm-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">Full Name</div>
                                    </div>
                                    <input v-model="form.name" type="text" name="name"
                                           placeholder="User"
                                           class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                    <has-error :form="form" field="name"></has-error>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <label class="typo__label">Permissions</label>
                                </div>
                                <div class="card-body">
                                    <multiselect
                                        v-model="form.permissions"
                                        track-by="name"
                                        name="permissions"
                                        label="name"
                                        placeholder="<--Select permissions-->"
                                        :close-on-select="false"
                                        :clear-on-select="false"
                                        :preserve-search="true"
                                        :options="permissions"
                                        :searchable="false"
                                        :allow-empty="true"
                                        :multiple="true">
                                    </multiselect>
                                </div>

                                <has-error :form="form" field="permissions"></has-error>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa fa-times fa-fw"> </i> Close</button>
                            <button v-show="editmode" type="submit" class="btn btn-success"><i class="fa fa-plus fa-fw"></i>Update</button>
                            <button v-show="!editmode" type="submit" class="btn btn-primary"><i class="fa fa-plus fa-fw"></i>Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>

<script>
    import Multiselect from 'vue-multiselect'
    import Form from 'vform'
    import { Button, HasError, AlertError } from 'vform/src/components/bootstrap5'


    export default {
        components: { Multiselect,HasError },
        data(){
            return {
                selected: false,
                editmode: false,
                totalroles:0,
                role:'',
                roles : {},
                permissions : [],
                form: new Form({
                    id:'',
                    name :'',
                    guard_name :'',
                    permissions:'',
                }),
            }
        },
        methods:{
            /*==== Show existing Role function ====*/
            loadRoles(){
                axios.get("/api/role")
                    .then(({ data }) => (
                        this.roles = data.roles,
                            this.permissions= data.permissions,
                            this.totalroles = data.roles.length
                    ));
            },
            /*==== End of existing Role ====*/
            /*===== Call add new user modal ====*/
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNewRole').modal('show');
            },
            /*Create User Function Starts*/
            createRole(){
                this.$Progress.start(); //start a progress bar
                this.user= this.form.name;
                this.form.post('/api/role') // POST form data
                    //Start Condition to check form is validate
                    .then((response)=>{
                        this.emitter.emit('AfterCreate'); //custom event to reload data
                        $("#addNewRole").modal('hide'); //Hide the model
                        this.$Progress.finish(); //End the progress bar
                        //Sweetalert notification for the result
                        this.$swal({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            icon: 'success',
                            title: response.data.message,
                            text:'',
                        })
                    })
                    //if form is not valid of handle any errors
                    .catch((response)=>{
                        this.$swal({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            icon: 'warning',
                            title: response.data.message,
                        })
                        this.$Progress.fail(); //End the progress bar
                    })
            },
            /*==== End of Role Create ====*/
            /*==== Call edit Modal with user data ====*/
            editModal(role){
                this.editmode = true;
                this.form.reset();
                this.role=role.name;
                $('#addNewRole').modal('show');
                this.form.fill(role);
            },
            /*Edit User Function*/
            updateRole(id){
                this.$Progress.start();
                //console.log('editing data');
                this.form.put('/api/role/'+this.form.id)
                    .then((response) =>{
                        if(response.data.error == 'true'){
                            this.$swal({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                icon: 'warning',
                                title: response.data.message,
                            })
                            this.$Progress.fail();
                        }
                        else{
                            $("#addNewRole").modal('hide'); //Hide the model
                            this.$swal({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                icon: 'success',
                                title: response.data.message,
                            })
                            // this.$swal(
                            //     'Updated!',
                            //     response.data.message,
                            //     'success'
                            // )
                            this.emitter.emit('AfterCreate'); //Fire an reload event
                            this.$Progress.finish();
                        }
                    }).catch(()=>{
                    this.$Progress.fail();
                });
            },
            /*==== End of edit user function ====*/
            /*==== Call Delete Modal uith role id ====*/
            deleteRole(id,name){
                this.$swal({
                title: 'Delete '+ name +' ?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    //send an ajax request to the server
                    if (result.value) {
                        this.form.delete('../api/role/' + id).
                        then(() => {
                            this.$swal(
                                'Deleted!',
                                'Role '+ name + ' has been deleted successfully',
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
            /*==== End of Delete Modal ====*/
        },
        created() {
            this.loadRoles(); //load the roles in the table
            //Load the userlist if add or created a new user
            this.emitter.on("AfterCreate",()=>{
                this.loadRoles();
            })
        },
        mounted() {
            this.$Progress.start();
            this.$Progress.finish();
        }
    }
</script>
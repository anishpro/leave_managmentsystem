<template>
    <breadcrumb :items="breadcrumb_items" :icon="'bx-layers-plus'" :title="'Permissions'"></breadcrumb>
    <div class="container-fluid">
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between p-3">
                        <h3 class="card-title">Permission list</h3>
                        <div class="card-tools float-right">
                            <a href="#" type="" class="" @click="newModal"><i class="bx  bx-sm align-middle m-b-3 bx-layer-plus" /> </a>
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
                            <tr v-for="(permission,index) in permissions" :key="permission.id">
                                <td>{{index+1}}</td>
                                <td>{{permission.name}}</td>
                                <td>{{permission.guard_name}}</td>
                                <td>
                                    <a href="#" @click="editModal(permission)" class="mr-3">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="#" @click="deletePermission(permission.id,permission.name)" class="">
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
        <div class="modal fade" id="addNewPermission" tabindex="-1" role="dialog" aria-labelledby="addNewPermissionLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="!editmode" id="addNewPermissionLabel">Add New Permission</h5>
                        <h5 class="modal-title" v-show="editmode" id="addNewPermissionLabel">Update {{permission}}</h5>
                        
                    </div>
                    <form  @submit.prevent="editmode ? updatePermission() : createPermission()">
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="input-group mb-2 mr-sm-2" >
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">Permission Name</div>
                                    </div>
                                    <input v-model="form.name" type="text" name="name"
                                           placeholder="User"
                                           class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                    <has-error :form="form" field="name"></has-error>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="input-group mb-2 mr-sm-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">Guard Name</div>
                                    </div>
                                    <input v-model="form.guard_name" type="text" required name="guard_name"
                                           placeholder="web"
                                           class="form-control" :class="{ 'is-invalid': form.errors.has('guard_name') }">
                                    <has-error :form="form" field="email"></has-error>
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
    </div>
</template>


<script>
    import Breadcrumb from "../Breadcrumb/Breadcrumb.vue";
    import Multiselect from 'vue-multiselect'
    import Form from 'vform'
    import { Button, HasError, AlertError } from 'vform/src/components/bootstrap5'

    export default {
        components: { Multiselect,HasError,Breadcrumb},
        data(){
            return {
                selected: false,
                editmode: false,
                totalpermission:0,
                permission: '',
                permissions : {},
                form: new Form({
                    id:'',
                    name :'',
                    guard_name :'',
                }),
                breadcrumb_items:[
                    {
                        text: 'Home',
                        href: '/home',
                    },
                    {
                        text: 'Permissions',
                        active: true,
                    },
                ],
            }
        },
        methods:{
            /*==== Show existing Role function ====*/
            loadPermissions(){
                axios.get("/api/permission")
                    .then(({ data }) => (
                        this.permissions= data.permissions,
                            this.totalpermission = data.permissions.length
                    ));

            },
            /*==== End of existing Role ====*/

            /*===== Call add new user modal ====*/
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNewPermission').modal('show');
            },
            /*Create User Function Starts*/
            createPermission(){
                this.$Progress.start(); //start a progress bar
                this.permission= this.form.name;
                this.form.post('/api/permission') // POST form data
                    //Start Condition to check form is validate
                    .then((response)=>{
                        this.emitter.emit('AfterCreate'); //custom event to reload data

                        $("#addNewPermission").modal('hide'); //Hide the model

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

            /*==== Call edit Modal with permission data ====*/
            editModal(permission){
                this.editmode = true;
                this.form.reset();
                this.permission=permission.name;
                $('#addNewPermission').modal('show');
                this.form.fill(permission);
            },
            /*Edit permission Function*/
            updatePermission(id){
                this.$Progress.start();
                //console.log('editing data');
                this.form.put('/api/permission/'+this.form.id)
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
                            $("#addNewPermission").modal('hide'); //Hide the model
                            this.$swal(
                                'Updated!',
                                response.data.message,
                                'success'
                            )
                            this.$Progress.finish();
                            this.emitter.emit('AfterCreate'); //Fire an reload event
                            this.$Progress.fail();
                        }
                    }).catch(()=>{
                    this.$Progress.fail();
                });
            },
            /*==== End of edit user function ====*/

            /*==== Call Delete Modal uith permission id ====*/
            deletePermission(id,name){
                this.$swal({
                title: 'Delete '+ name +' ?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    //send an ajax request to the server
                    if (result.value) {
                        this.form.delete('/api/permission/' + id).
                        then(() => {
                            this.$swal(
                                'Deleted!',
                                'Permission '+ name + ' has been deleted successfully',
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
            this.loadPermissions(); //load the roles in the table
            //Load the userlist if add or created a new user
            this.emitter.on("AfterCreate",()=>{
                this.loadPermissions();
            })
        },

    }
</script>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>

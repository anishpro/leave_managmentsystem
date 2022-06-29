<template>
    <div class="container">
        <div class="row pt-5" >
            <div class="col-md-12">
                <div class="card rounded-0">
                    <div class="card-header d-flex justify-content-between p-2">
                        <h3>Group Management</h3>

                        <div class="card-tools">
                            <button type="" class="btn btn-primary" @click="newModal"><i class="fa fa-user-plus fa-fw"></i> Add New Group</button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover m-0">
                            <tbody>
                            <tr class="bg-light">
                                <th>S.N.</th>
                                <th style="width:25%">Group Name</th>
                                <th>Weekend</th>
                                <th>Actions</th>
                            </tr>
                            <tr v-for="(group, index) in groups.data" :key="group.id">
                                <td>{{index + 1}}</td>
                                <td>{{group.group_name}}
                                </td>
                                <td> <span v-for="(weekend,index) in group.weekend" :key="weekend.id" > {{weekend}} <span v-if="index+1 < group.weekend.length">, </span></span> </td>

                                <td>
                                    <a href="#" @click="editModal(group)" class=" text-success mr-2">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="#" @click="deleteContent(group.id)" class=" text-danger mr-2">
                                        <i class="fa fa-trash"></i>
                                    </a>

                                </td>


                            </tr>
                            </tbody></table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <pagination-wrapper class="mt-3" :data="this.groups" :has_param="false" :api_url="api_url" pagination_title="Groups"></pagination-wrapper>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>


        <!-- contact Modal -->
         <modal :form="form" :modal_data="modal_data" :editmode="editmode" :api_url="'groups'">
            <!-- <h4 class="modal-header">Group Information</h4> -->

                    <div class="form-group row">
                        <label for="inputEmail3" class="col-md-12 col-form-label">Group Name <span class="text-danger">*</span></label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" v-model="form.group_name" id="group" placeholder="Enter Group Name" autocomplete="off" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputEmail3" class="col-md-12 col-form-label">Weekened <span class="text-danger">*</span></label>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chk-saturday" v-model="form.weekend" value="Sat">
                                <label class="form-check-label" for="chk-saturday">Saturday</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="chk-sunday" v-model="form.weekend" value="Sun">
                                <label class="form-check-label" for="chk-sunday">Sunday</label>
                            </div>
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
                disabled: false,
                groups:{},
                modal_data:{
                    modal_size:'',
                    modal_name:'addNewGroup'
                },
                form: new Form({
                    id: null,
                    group_name: null,
                    weekend: [],
                }),
                api_url : 'api/groups'

            }
        },
        methods: {
            /*===== Call add new user modal ====*/
            newModal() {
                this.editmode = false;
                this.form.reset();
                $('#'+this.modal_data.modal_name).modal('show');
            },

            editModal(val){
                this.editmode = true;
                $('#'+this.modal_data.modal_name).modal('show');
                this.emitter.emit('editing', val);
            },
            deleteContent(id) {
                this.$swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {

                    //send an ajax request to the server
                    if (result.value) {
                        this.$Progress.start();
                        this.disabled=true,

                        this.form.delete('/'+this.api_url+'/' + id).then((response) => {

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
                                this.disabled=false;
                            }
                            else{
                                $('#'+this.modal_data.modal_name).modal('hide'); //Hide the model
                                this.$swal({
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    icon: 'success',
                                    title: response.data.message,
                                })
                                this.disabled=false
                                this.emitter.emit('AfterCreate'); //Fire an reload event
                                this.$Progress.finish();
                            }


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
            async loadGroups() {
                const {data}  = await  axios.get("/"+this.api_url);
                this.groups = data
            },
        },
        created() {
            this.loadGroups(); //load the user in the table
            //Load the userlist if add or created a new user
            this.emitter.on("AfterCreate", () => {
                this.loadGroups();
            })
            //emit event on pagination
            this.emitter.on('paginating',(item)=>{
                this.groups = item
            })
        }
    }
</script>




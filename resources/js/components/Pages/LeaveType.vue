<template>
    <div class="container">
        <div class="row pt-5" >
            <div class="col-md-12">
                <div class="card rounded-0">
                    <div class="card-header d-flex justify-content-between p-2">
                        <h3>Manage Leave Type</h3>

                        <div class="card-tools">
                            <button type="" class="btn btn-primary" @click="newModal"><i class="fa fa-plus fa-fw"></i> Add New Leave Type</button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover m-0">
                            <tbody>
                            <tr class="bg-light">
                                <th>S.N.</th>
                                <th style="width:25%">Leave Type</th>
                                <th>Mapping Required</th>
                                <th>Contract Months </th>
                                <th>Actions</th>
                            </tr>
                            <tr v-for="(leave_type, index) in leave_types.data" :key="leave_type.id">
                                <td>{{index + 1}}</td>
                                <td>{{leave_type.leave_type}}
                                </td>
                                <td> {{leave_type.mapping_required}} </td>
                                <td >
                                    <span v-if="leave_type.mapping_required == 'no'"> </span>
                                    <a v-else href="#" @click="viewModal(leave_type.map_contract_leave,leave_type.leave_type)" class=" text-success mr-2">
                                        <i class="fa-solid fa-address-book"></i>
                                    </a>
                                 </td>

                                <td>
                                    <a href="#" @click="editModal(leave_type)" class=" text-success mr-2">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="#" @click="deleteContent(leave_type.id)" class=" text-danger mr-2">
                                        <i class="fa fa-trash"></i>
                                    </a>

                                </td>


                            </tr>
                            </tbody></table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <pagination-wrapper class="mt-3" :data="this.leave_types" :has_param="false" :api_url="api_url" pagination_title="Leave Types"></pagination-wrapper>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>


        <!-- leave Type Modal -->
         <modal  :form="form" :modal_data="modal_data" :editmode="editmode" :api_url="'leave-types'">

                    <div class="form-group row">
                        <label for="inputEmail3" class="col-md-12 col-form-label">Leave Type <span class="text-danger">*</span></label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" v-model="form.leave_type" placeholder="Enter Leave Type Name" autocomplete="off" required>
                            <has-error :form="form" field="leave_type"></has-error>

                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputEmail3" class="col-md-12 col-form-label">Mapping  Required? <span class="text-danger">*</span></label>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="mapping-yes" v-model="form.mapping_required" value="yes">
                                <label class="form-check-label" for="mapping-yes">Yes</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="mapping-no" v-model="form.mapping_required" value="no">
                                <label class="form-check-label" for="mapping-no">No</label>
                            </div>
                            <has-error :form="form" field="mapping_required"></has-error>

                        </div>
                    </div>

        </modal>

         <modal-view  :modal_data="v_modal_data">
            <h6 class="p-2">{{leave_type_name}}</h6>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover m-0">
                    <tbody>
                    <tr class="bg-light">
                        <th>S.N.</th>
                        <th>Contract Months </th>
                        <th>Leave Days</th>
                        <th>Actions</th>
                    </tr>
                    <tr v-for="(leave_contract, index) in map_contract_leave" :key="leave_contract.id">
                        <td>{{index + 1}}</td>
                        <td>{{leave_contract.contract_month}}
                        </td>
                        <td > {{leave_contract.leave_days}} </td>
                        <td>

                            <a href="#" @click="deleteContent(leave_contract.id,'api/contract-leave')" class=" text-danger mr-2">
                                <i class="fa fa-trash"></i>
                            </a>

                        </td>


                    </tr>
                    </tbody>
                </table>
            </div>
        </modal-view>




    </div>
</template>
<script>
    import Form from 'vform'
    import { Button, HasError, AlertError } from 'vform/src/components/bootstrap5'
    import PaginationWrapper from '../Pagination/PaginationWrapper.vue';
    import ModalView from '../ModalView.vue';
    import Modal from '../Modal.vue';

    export default {
        components: {
            HasError,
            PaginationWrapper,
            Modal,
            ModalView,

        },
        /*Filling the data into form*/
        data() {

            return {
                editmode: false,
                disabled: false,
                leave_types:{},
                modal_data:{
                    modal_size:'',
                    modal_name:'addNewLeaveType',
                },
                v_modal_data:{
                    modal_size:'modal-lg',
                    modal_name:'mapLeaveTypeContract',
                    title:'Contract Leave Mapping',
                },

                form: new Form({
                    id: null,
                    leave_type: null,
                    mapping_required: null,
                }),
                api_url : 'api/leave-types',
                map_contract_leave:{},

                leave_type_name:''

            }
        },
        methods: {
            /*===== Call add new user modal ====*/
            newModal() {
                this.editmode = false;
                this.form.reset();
                $('#'+this.modal_data.modal_name).modal('show');
            },
            viewModal(val,name) {
                this.map_contract_leave = val;
                this.leave_type_name = name;
                $('#'+this.v_modal_data.modal_name).modal('show');
            },

            editModal(val){
                this.editmode = true;
                $('#'+this.modal_data.modal_name).modal('show');
                this.emitter.emit('editing', val);
            },

            deleteContent(id,api_url = this.api_url) {
                this.api_url = api_url;
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
            async loadData() {
                const {data}  = await  axios.get("/"+this.api_url);
                this.leave_types = data
            },
        },
        created() {
            this.loadData(); //load the user in the table
            //Load the userlist if add or created a new user
            this.emitter.on("AfterCreate", () => {
                this.loadData();
            })
            //emit event on pagination
            this.emitter.on('paginating',(item)=>{
                this.leave_types = item
            })
        }
    }
</script>




<template>
    <div class="container">
        <div class="row pt-5" >
            <div class="col-md-12">
                <div class="card pb-3 rounded-0">
                    <div class="card-header d-flex justify-content-between p-2">
                        <h3>Manage Contract Leave Mapping</h3>

                        <div class="card-tools">
                            <button type="" class="btn btn-primary" @click="newModal"><i class="fa fa-plus fa-fw"></i> Add New Contract Leave Mapping</button>
                        </div>
                    </div>
                     <div class="col-md-12 mt-5">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item" @click="activeTab(leave_type.id+'-leave-type')" v-for="(leave_type, index) in leave_types.data" :key="leave_type.id+'-leave-type'"><a class="nav-link show" :class="index+1 == 1 ? 'active' : ''" :href="'#'+leave_type.id+'-leave-type'" data-toggle="tab">{{leave_type.leave_type}}</a></li>
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body p-0">
                        <div class="tab-content">
                            <div v-for="(leave_type, index) in leave_types.data" :key="leave_type.id" class="tab-pane show" :class="active_tab == leave_type.id+'-leave-type' ? 'active' : '' " :id="leave_type.id+'-leave-type'">
                                <div v-if="leave_type.map_contract_leave.length">
                                    <table class="table table-hover m-0">
                                        <tbody>
                                        <tr class="bg-light">
                                            <th>S.N.</th>
                                            <th>Contract Months </th>
                                            <th>Leave Days</th>
                                            <th>Actions</th>
                                        </tr>
                                        <tr v-for="(leave_contract, index) in leave_type.map_contract_leave" :key="leave_contract.id">
                                            <td>{{index + 1}}</td>
                                            <td>{{leave_contract.contract_month}}
                                            </td>
                                            <td > {{leave_contract.leave_days}} </td>
                                            <td>
                                                <a href="#" @click="editModal(leave_contract)" class=" text-success mr-2">
                                                        <i class="fa fa-edit"></i>
                                                    </a>

                                                <a href="#" @click="deleteContent(leave_contract.id,'api/contract-leave')" class=" text-danger mr-2">
                                                    <i class="fa fa-trash"></i>
                                                </a>

                                            </td>

                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                    <div class="text-center" v-else>
                                        <h4>Mapping Not Available</h4>
                                    </div>
                            </div>

                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div><!-- /.card-body -->
                </div>
                <!-- /.nav-tabs-custom -->
            </div>





                </div>
                <!-- /.card -->
            </div>
        </div>


        <!-- leave Type Modal -->
         <modal  :form="form" :modal_data="modal_data" :editmode="editmode" :api_url="'contract-leave'">

                    <div class="form-group row">
                        <label  class="col-md-12 col-form-label">Contract Month <span class="text-danger">*</span></label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" v-model="form.contract_month" id="contract_month" placeholder="Enter Contract Month" autocomplete="off" required>

                        </div>
                        <has-error :form="form" field="contract_month"></has-error>

                    </div>

                    <div class="form-group row">
                        <label for="inputPassword3" class="col-md-12 col-form-label">Leave Type <span class="text-danger">*</span></label>
                        <div class="col-md-12">
                            <multiselect v-model="form.leave_type_id"
                                tag-placeholder="Select Leave Type"
                                placeholder="Select Leave Type"
                                label = "name"
                                track_by = "id"
                                :options="Object.keys(leave_type_choice).map(Number)"
                                :custom-label="opt => leave_type_choice[opt]"
                                :multiple="false"
                                :allow-empty="false"
                                :taggable="true">
                            </multiselect>
                            <has-error :form="form" field="leave_type_id"></has-error>

                        </div>
                    </div>

                    <div class="form-group row">
                        <label  class="col-md-12 col-form-label">Leave Days <span class="text-danger">*</span></label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" v-model="form.leave_days" id="leave_days" placeholder="Enter Leave Days" autocomplete="off" required>
                        </div>
                        <has-error :form="form" field="leave_days"></has-error>

                    </div>




        </modal>





    </div>
</template>
<script>
    import Form from 'vform'
    import { Button, HasError, AlertError } from 'vform/src/components/bootstrap5'
    import PaginationWrapper from '../Pagination/PaginationWrapper.vue';
    import Modal from '../Modal.vue';
    import Multiselect from 'vue-multiselect'

    export default {
        components: {
            Multiselect,
            HasError,
            PaginationWrapper,
            Modal,

        },
        /*Filling the data into form*/
        data() {

            return {
                editmode: false,
                disabled: false,
                leave_type_choice: this.$store.state.leave_types,
                active_tab : '1-leave-type',
                leave_types:{},
                modal_data:{
                    modal_size:'',
                    modal_name:'addNewContractToLeaveMap'
                },
                form: new Form({
                    id: null,
                    leave_type_id: null,
                    contract_month: null,
                    leave_days: null,
                }),
                api_url : 'api/contract-leave',

            }
        },
        methods: {
            activeTab(val){
                this.active_tab = val

            },
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
                const {data}  = await  axios.get("/api/leave-types");
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




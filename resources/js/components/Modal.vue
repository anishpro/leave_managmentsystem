<template>
    <div class="modal fade " :id="modal_data.modal_name" tabindex="-1" role="dialog" :aria-labelledby="modal_data.modal_name+'Label'" aria-hidden="true">
        <div :class="modal_data.modal_size" class="modal-dialog modal-dialog-centered " role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode" >Add New </h5>
                    <h5 class="modal-title" v-show="editmode" >Update </h5>

                </div>
                <form  @submit.prevent="editmode ? updateInfo() : createInfo()">
                    <div class="modal-body">
                        <div class="row">
                            <slot></slot>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa fa-times fa-fw"></i>Close</button>
                        <button v-show="editmode" :disabled="disabled" type="submit" class="btn btn-success"><i class="fa fa-plus fa-fw"></i>Update</button>
                        <button v-show="!editmode" :disabled="disabled" type="submit" class="btn btn-secondary"><i class="fa fa-plus fa-fw"></i>Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>

export default {

    props: {
    modal_data: {
      type: Object,
      default: () => {

      }
    },
    form: {
      type: Object,
      default: () => {
      }
    },
    api_url: {
      type: String,
      default: ''
    },
    editmode: {
      type: Boolean,
      default: false
    },
    },
    data(){
        return{
            disabled:false,
        }
    },
    methods:{

        createInfo(){
            this.$Progress.start();
            this.disabled=true,
            this.form.post('/api/'+this.api_url).then((response) => {
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
            })
            .catch(({response}) => {
                if(response.status == 500) {
                     this.$swal(
                        'Error!',
                        "Something Went Wrong.",
                        'warning'
                    );
                } else {
                    this.$swal(
                        'Error!',
                        response.data.message,
                        'warning'
                    )
                }
                this.disabled=false
                this.$Progress.fail(); //End the progress bar
            })

        },
        updateInfo(){
            this.$Progress.start();
            this.disabled=true,
            this.form.put('/api/'+this.api_url+'/'+this.form.id).then((response) => {
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
            }).catch(({response}) => {

                if(response.status == 500) {
                     this.$swal(
                        'Error!',
                        "Something Went Wrong.",
                        'warning'
                    );
                } else {
                    this.$swal(
                        'Error!',
                        response.data.message,
                        'warning'
                    )
                }

                    this.disabled=false;
                    this.$Progress.fail();
            })


        },
    },
    created(){
        this.emitter.on('editing',(item)=>{
            this.form.fill(item);
        })

    }

}
</script>

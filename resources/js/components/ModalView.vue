<template>
    <div class="modal fade" :id="modal_data.modal_name" tabindex="-1" role="dialog" :aria-labelledby="modal_data.modal_name+'Label'" aria-hidden="true">
        <div :class="modal_data.modal_size" class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{modal_data.title}} </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    
                </div>
                    <div class="modal-body pb-0 pt-0">
                        <div class="row">
                            <slot></slot>
                        </div>
                    </div>
                    <div class="modal-footer p-0">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="fa fa-times fa-fw"></i>Close</button>
                    </div>

                    
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
    
    },
    data(){
        return{
            disabled:false,
        }
    },
    methods:{

       
    },
    created(){
       
       
    }
    
}
</script>
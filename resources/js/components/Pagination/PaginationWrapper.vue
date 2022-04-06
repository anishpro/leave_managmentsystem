<template>
    <div class="">
        <div class="row">
            <div class="col-md-3 pull-left">
                <span>Showing {{ data.data && data.data.length}} of {{this.data.total}} {{pagination_title}}.</span>
            </div>
            <div class="col-md-9 pagination-main d-flex justify-content-end">
                <pagination :limit="10" :data="data" @pagination-change-page="getResults"></pagination>
            </div>
        </div>
    </div>
</template>
<script>
import axios from 'axios';
import LaravelVuePagination from 'laravel-vue-pagination';

export default {
    props:['data','api_url','pagination_title','has_param'],
    components: {
        'Pagination': LaravelVuePagination
    },
    data(){
        return{
           response_data:'',
        }
    },
    methods:{
        getResults(page = 1) {
            if(this.has_param){
                axios.get('/'+this.api_url+'&page=' + page)
                    .then(response => {
                        this.emitter.emit('paginating', response.data.data);
                    });
            }
            else{
                axios.get('/'+this.api_url+'?page=' + page)
                    .then(response => {
                        this.emitter.emit('paginating', response.data.data);
                    });
            }
        },

    },

}
</script>
<style scoped>
.pagination-main ul{
float: right;
}
</style>

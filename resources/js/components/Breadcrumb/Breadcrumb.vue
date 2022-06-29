<template>
    <div class="row page-titles">
        <div class="col-md-5 align-self-center"> 
            <div class="pageheader">
                <span :class="'bx '+ icon +' bx-sm align-middle m-b-3'"></span> 
                <span>Manage {{title}}</span>  
            </div>
        </div><!-- /.col-md-5 -->

        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li v-for="(item,index) in items" :key="item.id" :class="{'active': item.active}"> <a  :href="item.href"> {{item.text}} </a> {{index+1 == items.length ? '':'/'}}</li>
            </ol>
            
        </div><!-- /.col-md-7 -->
    </div>
</template>
<script>
export default {
    props: {
        title: {
            type: String,
            default: 'Dashboard'
        },
        icon: {
            type: String,
            default: 'bx-right-arrow-circle'
        },
        items: {
            type: Array,
            default: () => []
        }
    },
    name: 'Breadcrumb',
    data() {
        return{

        }
    },
}

</script>

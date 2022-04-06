<template>
  <div  class="help-block invalid-feedback" v-html="message(field)"/>
</template>
<script>
export default {
  data(){
    return{
      hasfield:false
    }
  },
  
  props: {
    errors: {
      type: Object,
      required: true
    },
    field: {
      type: String,
      required: true
    }
  },
  methods:{
    message (field) {
    if (this.field) {
      return this.getAll(field)[0]
    }
  },
  getAll (field) {
    return Array.isArray(this.errors[field]) ? this.errors[field] : []
  },
  },
  created(){
    this.message();
  }
}

</script>



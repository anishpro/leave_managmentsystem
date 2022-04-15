<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card" >
          <div class="card-header">Dashboard Component</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue'

export default {

  setup(){
    const auth_user = ref('');

    return {
      auth_user,
    };
  },
  async mounted() {
    await this.$store.dispatch("fetchAuthUser");
    this.auth_user = this.$store.state.auth_user;     
  }
};
</script>
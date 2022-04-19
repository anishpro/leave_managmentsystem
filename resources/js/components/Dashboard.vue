<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card" v-role="'super-dev'">
          <div class="card-header">admin Component</div>
        </div>
        <div class="card" v-role="'applicant'">
          <div class="card-header">applicant Component</div>
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
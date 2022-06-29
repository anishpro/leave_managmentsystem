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

export default {
  methods: {
    async call(){

        await this.fetchAuthUser();

      },
    ...mapActions([
        'fetchAuthUser',
      ]),
    
  },
  created() {
    this.call();
  },
   

  
};
</script>
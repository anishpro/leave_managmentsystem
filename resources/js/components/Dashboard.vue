<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card" v-role="'super-dev'">
          <div class="card-header">admin Component</div>
        </div>
        <div class="card" v-role="'supervisor'">
          <div class="card-header">applicant Component</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

  import { mapActions } from 'vuex'
export default {
  
  data(){
    return{
      user:null,
    }
  },

  methods: {
    ...mapActions([
      'fetchAuthUser',
      'fetchAuthUserRoles',
      'fetchAuthUserPermissions',
      'fetchOptions'
    ]),
    setACL(){
        this.$gates.setPermissions(this.$store.state.auth_permissions);
        this.$gates.setRoles(this.$store.state.auth_roles);
    },
    async call(){
        await this.fetchAuthUser();
        await this.fetchAuthUserRoles();
        await this.fetchAuthUserPermissions();
        await this.fetchOptions();
        this.setACL();
      },
  },
  created() {
    this.call();
  },


}
</script>
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
// import { onBeforeMount } from '@vue/runtime-core';
// export default {
//   async created() {
//   const { data: permissions } = await axios.get("/api/auth_permissions");
//   const { data: roles } = await axios.get("/api/auth_roles");

//   this.$gates.setPermissions(permissions);
//   this.$gates.setRoles(roles);
//   console.log(roles)
// }
// };
export default {
  
};
</script>
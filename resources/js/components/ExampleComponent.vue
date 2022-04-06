<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card" v-role="'-dev'">
          <div class="card-header">Example Component</div>

          <div class="card-body">
            <button v-can:disabled="'click yeah'">Yeah!</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  async created() {
    const { data: roles } = await axios.get('/api/auth_roles');
    const { data: permissions } = await axios.get('/api/auth_permissions');
    this.$gates.setRoles(roles);
    this.$gates.setPermissions(permissions);
  },
};
</script>
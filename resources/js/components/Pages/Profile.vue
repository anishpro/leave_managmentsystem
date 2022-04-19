
<template>
    <div class="container">
        <div class="row pt-5 justify-content-center">
            <div class="col-md-5">
                <!-- Profile Image -->
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        
                        <div class="text-center">
                            
                            <div class="profile-pic">
                            <label class="-label" for="file">
                                <span class="glyphicon glyphicon-camera"></span>
                                <span>Change Profile</span>
                            </label>
                            <input id="file" type="file"  @change="$function.imageUpload($event, form ,'profile')" name="profile" class="form-input">

                            <img v-if="form.profile != null"   class="profile-user-img img-fluid img-circle" :src="getProfilePhoto()" alt="User profile picture">
                            <img  v-if="form.profile == null" src="https://www.gravatar.com/avatar/00000000000000000000000000000000?d=identicon&f=y" id="output" width="200" />
                            </div>
                        </div>
                        <ul class="list-group list-group-unbordered mb-3 mt-3">
                            <li class="list-group-item">
                                <b>{{form.name}}</b>
                                <a href="" traget="blank" class="float-right"><i class="fa fa-user"></i> </a>
                            </li>
                            <li class="list-group-item">
                                <b>{{form.email}}</b>
                                <a href="" traget="blank" class="float-right"><i class="fa fa-envelope"></i> </a>
                            </li>
                            <li class="list-group-item">
                                <b>{{form.phone}}</b>
                                <a href="" traget="blank" class="float-right"><i class="fa fa-phone"></i> </a>
                            </li>
                            
                           
                        </ul>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
          
            <!-- /.col -->
            <div class="col-md-12 mt-5">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active show" href="#settings" data-toggle="tab">Profile Details</a></li>
                            <li class="nav-item"><a class="nav-link" href="#activity" data-toggle="tab">Update Password</a></li>
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane" id="activity">
                                <!-- Post -->
                                <div class="post">
                                    <form class="form-horizontal">
                                        <div class="form-group">
                                            <label for="oldpassword" class=" control-label">Old password</label>
                                            <div class="col-md-12">
                                                <input type="password" v-model="form.oldpassword" class="form-control" id="oldpassword" placeholder="Password" :class="{ 'is-invalid': form.errors.has('oldpassword') }">
                                                <has-error :form="form" field="oldpassword"></has-error>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="newpassword" class=" control-label">New password</label>
                                            <div class="col-md-12">
                                                <input type="password" v-model="form.newpassword" @input="check" class="form-control" v-bind:class="{ 'is-invalid': isActive, 'is-equal':fc}"  id="newpassword" placeholder="Password">
                                                <has-error :form="form" field="newpassword"></has-error>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="confirmpassword" class=" control-label">Confirm password</label>
                                            <div class="col-md-12">
                                                <input type="password" v-model="form.confirmpassword" @input="check" class="form-control" v-bind:class="{ 'is-invalid': isActive, 'is-equal':fc}" id="confirmpassword" placeholder="Password">
                                                <has-error :form="form" field="confirmpassword"></has-error>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <button @click.prevent="updatePassword" type="submit" class="btn btn-success">Update</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.post -->
                            </div>
                            <div class="tab-pane active show" id="settings">
                                <div class="card-body" >

                                    <div class="row">

                                        <div class="form-group col-md-4">
                                            <label for="inputName" >Name *</label>

                                                <input type="text" v-model="form.name" class="form-control"  placeholder="First Name" :class="{ 'is-invalid': form.errors.has('name') }">
                                                <has-error :form="form" field="name"></has-error>
                                                

                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="" >Email *</label>

                                                <input type="email" v-model="form.email" class="form-control"  placeholder="Email" :class="{ 'is-invalid': form.errors.has('email') }">
                                                <has-error :form="form" field="email"></has-error>
                                                
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="" >Phone *</label>

                                                <input type="number" v-model="form.phone" class="form-control"  placeholder="Phone No." :class="{ 'is-invalid': form.errors.has('phone') }">
                                                <has-error :form="form" field="phone"></has-error>
                                                
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="" >Address *</label>

                                                <input type="text" v-model="form.address" class="form-control" placeholder="Address" :class="{ 'is-invalid': form.errors.has('address') }">
                                                <has-error :form="form" field="address"></has-error>
                                                
                                        </div>
                                        
                                        <div class="form-group col-md-4">
                                            <label for="photo" class="control-label">Signature Image *</label>
                                                <input type="file" accept="image/png" id="signature" @change="$function.imageUpload($event, form ,'signature')"  class="form-control">
                                                <img class="img-lg mb-3 elevation-3 float-right"  :src="(this.form.signature && this.form.signature.length > 50) ? form.signature : getImage(form.signature)"/>
                                            <has-error :form="form" field="signature"></has-error>
                                        </div>

                                        <div class="col-md-12">

                                        <div class="form-group ">
                                                <button @click.prevent="updateInfo" type="submit" class="btn btn-success">Update</button>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div><!-- /.card-body -->
                </div>
                <!-- /.nav-tabs-custom -->
            </div>
            <!-- /.col -->
        </div>
    </div>
</template>

<script>
import Multiselect from 'vue-multiselect'

    import Form from 'vform'
    import { Button, HasError, AlertError } from 'vform/src/components/bootstrap5'
    export default {
        components: {
            Multiselect,
            HasError
        },
        data() {
            return {
                isActive:true,
                fc:false, 
                hide:true,              
                updated:true,
                auth_user:null,
                form: new Form({
                    id: null,
                    name:null,
                    email: null,
                    user_id:null,
                    phone: null,
                    profile:null,
                    signature:null,
                    address: null,
                    group_id:null,
                    emp_code:null,
                    password: null,
                    newpassword: null,
                    oldpassword: null,
                    confirmpassword: null,
                })
            }
        },
        
        methods: {
            getImage(img){
                if(img) return "/images/users/"+this.form.user_id +"/" + img;
                return "/images/no-image.png"
            },
            check(){
                if(this.form.newpassword == this.form.confirmpassword){
                    this.isActive = false ;
                    this.fc = true;
                }
                else{
                    this.isActive = true ;
                    this.fc = false;
                }
            },
            getProfilePhoto(){
                if(this.form.profile != null){
                     let photo = (this.form.profile.length > 200) ? this.form.profile : "/images/users/"+this.form.user_id +"/thumbs/"+ 'small_'+this.form.profile;
                return photo;
                }
            },
            updatePassword() {
                this.$Progress.start();
                if(this.form.newpassword == this.form.confirmpassword){
                    this.form.post('/api/updatePassword')
                   .then(res =>{
                             this.$swal(
                                res.data.result,
                                res.data.message,
                                res.data.type,
                            )
                            this.form.oldpassword = null,
                            this.form.newpassword = null,
                            this.form.confirmpassword = null,
                            this.fc = false
                            this.$Progress.finish(); //End the progress bar
                        })
                        //if form is not valid of handle any errors
                        .catch(()=>{
                            this.$swal(
                                'Error!',
                                'Something Went Wrong.',
                                'warning'
                            )
                            this.$Progress.fail(); //End the progress bar
                        })
                }else{
                    this.$swal(
                        'Opps..!',
                        'Password do not match',
                        'warning'
                    )
                }
            },
            async updateInfo() {
                const response = await this.form.put('/api/profile/'+this.form.id);
                try{
                    if(response.data.error == 'false'){
                        this.$swal(
                                'Updated!',
                                response.data.message,
                                'success'
                            )
                        this.$Progress.finish();
                        await this.$store.dispatch("fetchAuthUser");
                        this.emitter.emit('AfterCreate'); //Fire an reload event
                        this.updated = true
                    }else{
                        this.$swal(
                                'Error!',
                                response.data.message,
                                'warning'
                            )
                        this.$Progress.fail();
                    }
                }catch(error){
                    console.log(error);
                    this.$swal(
                                'Error!',
                                'Something Went Wrong.',
                                'warning'
                            )
                    this.$Progress.fail();
                }
            },
            async loadProfile(){
                if(!this.auth_user ){
                    await this.$store.dispatch("fetchAuthUser");
                }
                this.auth_user = this.$store.state.auth_user
                if(this.$store.state.auth_user.profile){
                        this.form.fill(this.$store.state.auth_user.profile);
                }else{
                    this.form.name = this.auth_user.name;
                    this.form.email = this.auth_user.email;
                    this.form.user_id = this.auth_user.id;
                }
            }
        },
        created() {
            this.loadProfile();
            this.emitter.on("AfterCreate",()=>{
                this.loadProfile();
            })
        }

    }
</script>
<style  scoped>
[type="date"]::-webkit-calendar-picker-indicator {
  display: none;
}
[type="file"]::-webkit-file-upload-button {
    line-height: 20px;
}
.invalid-feedback{
    display: block;
}
.is-equal{
        border-color: green;
        box-shadow: 0 0 0 0.2rem rgba(73, 231, 25, 0.25) !important;
    }
.card-primary.card-outline {
    border-top: 3px solid #ff2300;
}
.profile-pic {
  color: transparent;
  transition: all 0.3s ease;
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
  transition: all 0.3s ease;
}
.profile-pic input {
  display: none;
}
.profile-pic img {
  position: absolute;
  object-fit: cover;
  width: 100px;
  height: 100px;
  box-shadow: 0 0 10px 0 rgba(255, 255, 255, 0.663);
  border-radius: 100px;
  z-index: 0;
}
.profile-pic .-label {
  cursor: pointer;
  height: 100px;
  width: 100px;
  margin-bottom: 0;
}
.profile-pic:hover .-label {
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: rgba(0, 0, 0, 0.173);
  z-index: 10000;
  color: #fafafa;
  border-radius: 100px;
  margin-bottom: 0;
}
.profile-pic span {
  display: inline-flex;
  padding: 0.2em;
  height: 2em;
}
.img-lg {
  width: 200px;
}
</style>

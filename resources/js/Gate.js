export default class Gate{
 constructor(user){
     this.user = user;
 }

    isSuperDev(){
        return this.user.includes("super-dev");
    }
    isSuperAdmin(){
    return this.user.includes("super-admin");
    }
    isAdmin(){
    return this.user.includes("admin");
    }
    isApplicant(){
        return this.user.includes("applicant");
    }
    isSuperAdminOrSuperDev(){
        if(this.user.includes("super-admin")|| this.user.includes("super-dev")){
            return true;
        }
    }
    

}

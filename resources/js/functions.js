

export default class Functions{
    imageUpload(e,data,field) {
        let file = e.target.files[0];
        let reader = new FileReader();
        if (file['size'] < 2500000) {
            reader.readAsDataURL(file);
            reader.onloadend = (file) => {
                data[field] = file.target.result;
            }
        } else {
          this.emitter.emit({
                type: "warning",
                title: 'Opps..!',
                text: 'File size is too large. Please upload a file less than 2.5MB',
              })
        }
    }
   
}





    
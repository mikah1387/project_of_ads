
window.onload =()=>{

let region = document.querySelector('#annonces_regions');
region.addEventListener('change', function(){
       
    let form = this.closest('form');
    let data = this.name + "=" + this.value;

    fetch(form.action,{

        method: form.getAttribute('method'),
        body:data,
        headers: {
            "Content-Type":"application/x-www-form-urlencoded ; charset:utf-8"
        }

    }).then(response=>response.text())
    .then(html=>{
        let content = document.createElement("html");
        content.innerHTML=html;
        let nouveauSelect = content.querySelector('#annonces_departements')
        document.querySelector('#annonces_departements').replaceWith(nouveauSelect);
        
    }).catch(error=>console.log(error))



})







}
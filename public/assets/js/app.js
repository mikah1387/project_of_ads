let checkannonces = document.querySelectorAll("[type=checkbox]")

let suprm_anno = document.querySelectorAll('.suprim_anno')

suprm_anno.forEach(btn => {
    
    btn.addEventListener('click',function(){
     
        document.querySelector('.confirm_delete').href = this.href
       document.querySelector('.modal-body p').innerText = 'êtes-vous sur(e) de supprimer l\'annonce '+this.dataset.title;

    })
});


checkannonces.forEach(element => {
    
    element.addEventListener('click', function(){
         
       let xmlhttp = new XMLHttpRequest;
       xmlhttp.open('GET', '/admin/annonces/active/'+ this.dataset.id)
       xmlhttp.send()

    //    fetch('/admin/annonces/active/'+ this.dataset.id)
    })

});

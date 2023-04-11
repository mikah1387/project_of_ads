window.onload =()=>{

document.querySelectorAll('[data-replay]').forEach(element => {
    element.addEventListener('click', function(){
          
        parentid = this.dataset.id;
        document.querySelector('#comments_parentid').value = parentid
        
    })
});

}
window.onload = ()=>{

 let filtersForm = document.querySelector("#filters")

 document.querySelectorAll('#filters input').forEach(input => {
    
    input.addEventListener('change', function(){
              //on recupere les donees de formulaire 
              const Form = new FormData(filtersForm)
            //   on fabrique la querystring
              const params = new URLSearchParams()
              Form.forEach((value,key) => {
                params.append(key,value)
                
              });
             
              // on recupere lurl active 

              const url = new URL(window.location.href)
              
            //   on lance la requete ajax

            fetch(url.pathname + '?' + params.toString()+ "&ajax=1",{
                headers: {
                    "X-Requested-With":"XMLHttpRequest"
                }
            }).then(response=>response.json())
            .then(data=>{
                // on remplace le contenu 
               document.querySelector('#content').innerHTML = data.content
            //    //on met a jour l'url 
                history.pushState({},null,url.pathname + '?' + params.toString())
            })
            .catch(e=>alert(e))
        
    })
 });





}
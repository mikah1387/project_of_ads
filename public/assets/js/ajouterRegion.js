window.onload = ()=>{

// let collection = document.querySelector('#departements');
// let span = collection.querySelector('span');
// let boutonAjout = document.createElement('button');
// boutonAjout.className = 'ajout-departement btn';
// boutonAjout.innerText = 'ajouter departement';

// let newbutton= span.append(boutonAjout);


// collection.dataset.index = collection.querySelectorAll('input').length;

// boutonAjout.addEventListener('click', function(){

//             adddepartement(collection, newbutton)  ;  
           
// })

// function adddepartement(collection,newbutton){

//     let prototype = collection.dataset.prototype;
//     let index = collection.dataset.index;

//     prototype= prototype.replace(/__name__/g,index)
  
//     let content = document.createElement('html');

//     content.innerHTML= prototype;
//     let newFrom = content.querySelector('div');
//     let buttondelete = document.createElement('button');
//     buttondelete.className = 'btn red';
//     buttondelete.id = 'delete-depart-'+index;
//     buttondelete.textContent = 'supprimer';
//     newFrom.append(buttondelete);
//     collection.dataset.index++; 
//     // span.insertBefore(newFrom,boutonAjout);
//     span.append(newFrom)

//     buttondelete.addEventListener('click', function(){

//         this.previousElementSibling.parentElement.remove();
//         collection.dataset.index--; 

//     })


// }

// ***************************************************

let collection = document.querySelector('#departements')
let span = collection.querySelector('span');

let btn_add_depart= document.createElement('button');
btn_add_depart.className = 'btn btn_add';
btn_add_depart.innerText = 'Ajouter un departement'
collection.dataset.index = collection.querySelectorAll('input').length
let newbtn = span.append(btn_add_depart);

btn_add_depart.addEventListener('click', function(){

   let prototype = collection.dataset.prototype;
   let index = collection.dataset.index
    prototype= prototype.replace(/__name__/g, index)

    let content = document.createElement('html');

    content.innerHTML = prototype;
    let newForm = content.querySelector('div')
    let btn_delete = document.createElement('button')
    btn_delete.className = 'btn delete_red'
    btn_delete.innerText = 'Supprimer ce department'
    btn_delete.id = 'btn_delete_' + index
    newForm.append(btn_delete)
   
    span.insertBefore(newForm, btn_add_depart)
    collection.dataset.index ++
    btn_delete.addEventListener('click', function(){

        this.previousElementSibling.parentElement.remove();
    })

})


}
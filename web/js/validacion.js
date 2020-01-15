<<<<<<< HEAD
//Función que permite solo Números
function ValidaSoloNumeros() {
 if ((event.keyCode != 45) && (event.keyCode < 48) || (event.keyCode > 57)) 
  event.returnValue = false;
}

function txNombres() {
 if ((event.keyCode != 32) && (event.keyCode < 65) || (event.keyCode > 90) && (event.keyCode < 97) || (event.keyCode > 122))
  event.returnValue = false;
=======
//Función que permite solo Números
function ValidaSoloNumeros() {
 if ((event.keyCode != 45) && (event.keyCode < 48) || (event.keyCode > 57)) 
  event.returnValue = false;
}

function txNombres() {
 if ((event.keyCode != 32) && (event.keyCode < 65) || (event.keyCode > 90) && (event.keyCode < 97) || (event.keyCode > 122))
  event.returnValue = false;
>>>>>>> 30fa032eaa121a2a644bd8fdcc615fe16e877a7d
}
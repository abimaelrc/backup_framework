function checkUncheck(id)
{
	var obj = document.getElementById(id);
	obj.checked = obj.checked == false ? true : false;
}

function confirmInactiveNotes(){
	if(confirm('Deseas desactivar las notas')){
		request('/notes/ajax/set-inactive-notes', {}, 'config-notes');
		$('<div/>', { 'class': 'empty', 'text': 'No hay notas' }).appendTo('#config-notes');
	}
}

function strlen(obj, id, maxNumber){
	counter = maxNumber - obj.value.length;
	document.getElementById(id).innerHTML = counter;
}
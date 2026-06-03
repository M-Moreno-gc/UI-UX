function Editar() {
            
            // Habilitar campos para edición
            document.getElementById('nombre').disabled = false;
            document.getElementById('email').disabled = false;
            document.getElementById('tel').disabled = false;
            document.getElementById('password').disabled = false;
            document.getElementById('tel').disabled = false;
            document.getElementById('guardarbtn').disabled = false;
            document.getElementById('cancearbtn').disabled = false;
        
        }

function EditarNo() {
    document.getElementById('nombre').disabled = true;
            document.getElementById('email').disabled = true;
            document.getElementById('tel').disabled = true;
            document.getElementById('password').disabled = true;
            document.getElementById('guardarbtn').disabled = true;
            document.getElementById('cancearbtn').disabled = true;
}

document.getElementById('buscarid').addEventListener('submit', function() {
            if (document.getElementById('id') == '') {
                alert('Primero busca un usuario');
                return;
            }
        
        });

        
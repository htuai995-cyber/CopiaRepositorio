/*document.addEventListener('DOMContentLoaded', function(){
    const formulario = document.getElementById('frmLogin');
    
    formulario.addEventListener('submit',function(e){
        e.preventDefault();

        const user = document.getElementById('user').value.trim();
        const pass = document.getElementById('pass').value.trim();

        if(user==="tito" && pass==="123"){        
            window.location.href="home.php";
        }else{
            alert("dato incorrectos");
        }
    });

 
}); */

function calcularParalelogramo(mostrarErrores = true) {
    const baseInput = document.getElementById('txtBase');
    const ladoInput = document.getElementById('txtLado');
    const alturaInput = document.getElementById('txtAltura');
    const areaInput = document.getElementById('txtArea');
    const perimetroInput = document.getElementById('txtPerimetro');

    if (!baseInput || !ladoInput || !alturaInput || !areaInput || !perimetroInput) {
        return;
    }

    const base = parseFloat(baseInput.value);
    const lado = parseFloat(ladoInput.value);
    const altura = parseFloat(alturaInput.value);

    areaInput.value = '';
    perimetroInput.value = '';

    if (isNaN(base) || base <= 0) {
        if (mostrarErrores) {
            alert('Ingrese una base válida mayor a cero');
            baseInput.focus();
        }
        return;
    }

    if (isNaN(lado) || lado <= 0) {
        if (mostrarErrores) {
            alert('Ingrese un lado válido mayor a cero');
            ladoInput.focus();
        }
        return;
    }

    if (isNaN(altura) || altura <= 0) {
        if (mostrarErrores) {
            alert('Ingrese una altura válida mayor a cero');
            alturaInput.focus();
        }
        return;
    }

    areaInput.value = (base * altura).toFixed(2);
    perimetroInput.value = (2 * (base + lado)).toFixed(2);
}

document.addEventListener('DOMContentLoaded', function () {
    ['txtBase', 'txtLado', 'txtAltura'].forEach(function (id) {
        const input = document.getElementById(id);
        if (input) {
            input.addEventListener('input', function () {
                calcularParalelogramo(false);
            });
        }
    });
});
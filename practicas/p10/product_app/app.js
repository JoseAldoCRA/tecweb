// JSON BASE A MOSTRAR EN FORMULARIO
var baseJSON = {
    "precio": 0.0,
    "unidades": 1,
    "modelo": "XX-000",
    "marca": "NA",
    "detalles": "NA",
    "imagen": "img/default.png"
};

// FUNCIÓN CALLBACK DE BOTÓN "Buscar por ID"
function buscarID(e) {
    e.preventDefault();

    // SE OBTIENE EL ID A BUSCAR
    var id = document.getElementById('search').value;

    // SE CREA EL OBJETO DE CONEXIÓN ASÍNCRONA AL SERVIDOR
    var client = getXMLHttpRequest();
    client.open('POST', './backend/read.php', true);
    client.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    client.onreadystatechange = function () {
        // SE VERIFICA SI LA RESPUESTA ESTÁ LISTA Y FUE SATISFACTORIA
        if (client.readyState == 4 && client.status == 200) {
            console.log('[CLIENTE]\n'+client.responseText);
            
            // SE OBTIENE EL OBJETO DE DATOS A PARTIR DE UN STRING JSON
            let productos = JSON.parse(client.responseText);
            
            // SE VERIFICA SI EL OBJETO JSON TIENE DATOS
            if(Object.keys(productos).length > 0) {
                // SE CREA UNA LISTA HTML CON LA DESCRIPCIÓN DEL PRODUCTO
                let descripcion = '';
                    descripcion += '<li>precio: '+productos.precio+'</li>';
                    descripcion += '<li>unidades: '+productos.unidades+'</li>';
                    descripcion += '<li>modelo: '+productos.modelo+'</li>';
                    descripcion += '<li>marca: '+productos.marca+'</li>';
                    descripcion += '<li>detalles: '+productos.detalles+'</li>';
                
                // SE CREA UNA PLANTILLA PARA CREAR LA(S) FILA(S) A INSERTAR EN EL DOCUMENTO HTML
                let template = '';
                    template += `
                        <tr>
                            <td>${productos.id}</td>
                            <td>${productos.nombre}</td>
                            <td><ul>${descripcion}</ul></td>
                        </tr>
                    `;

                // SE INSERTA LA PLANTILLA EN EL ELEMENTO CON ID "productos"
                document.getElementById("productos").innerHTML = template;
            }
        }
    };
    client.send("id="+id);
}

// NUEVA FUNCIÓN: Buscar por nombre, marca o detalles
function buscarProducto(e) {
    e.preventDefault();

    // SE OBTIENE EL TEXTO A BUSCAR
    var search = document.getElementById('search').value;

    // SE CREA EL OBJETO DE CONEXIÓN ASÍNCRONA AL SERVIDOR
    var client = getXMLHttpRequest();
    client.open('POST', './backend/read.php', true);
    client.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    client.onreadystatechange = function () {
        // SE VERIFICA SI LA RESPUESTA ESTÁ LISTA Y FUE SATISFACTORIA
        if (client.readyState == 4 && client.status == 200) {
            console.log('[CLIENTE]\n'+client.responseText);
            
            // SE OBTIENE EL ARREGLO DE PRODUCTOS A PARTIR DE UN STRING JSON
            let productos = JSON.parse(client.responseText);
            
            // SE VERIFICA SI EL ARREGLO JSON TIENE DATOS
            if(Array.isArray(productos) && productos.length > 0) {
                // SE CREA UNA PLANTILLA PARA MOSTRAR TODOS LOS PRODUCTOS
                let template = '';
                
                productos.forEach(producto => {
                    // SE CREA UNA LISTA HTML CON LA DESCRIPCIÓN DEL PRODUCTO
                    let descripcion = '';
                    descripcion += '<li>precio: '+producto.precio+'</li>';
                    descripcion += '<li>unidades: '+producto.unidades+'</li>';
                    descripcion += '<li>modelo: '+producto.modelo+'</li>';
                    descripcion += '<li>marca: '+producto.marca+'</li>';
                    descripcion += '<li>detalles: '+producto.detalles+'</li>';
                    
                    // SE AGREGA UNA FILA POR CADA PRODUCTO
                    template += `
                        <tr>
                            <td>${producto.id}</td>
                            <td>${producto.nombre}</td>
                            <td><ul>${descripcion}</ul></td>
                        </tr>
                    `;
                });

                // SE INSERTA LA PLANTILLA EN EL ELEMENTO CON ID "productos"
                document.getElementById("productos").innerHTML = template;
            } else {
                // NO SE ENCONTRARON PRODUCTOS
                document.getElementById("productos").innerHTML = '<tr><td colspan="3">No se encontraron productos</td></tr>';
            }
        }
    };
    client.send("search="+search);
}

// FUNCIÓN CALLBACK DE BOTÓN "Agregar Producto"
function agregarProducto(e) {
    e.preventDefault();

    // SE OBTIENE DESDE EL FORMULARIO EL JSON A ENVIAR
    var productoJsonString = document.getElementById('description').value;
    // SE CONVIERTE EL JSON DE STRING A OBJETO
    var finalJSON = JSON.parse(productoJsonString);
    // SE AGREGA AL JSON EL NOMBRE DEL PRODUCTO
    finalJSON['nombre'] = document.getElementById('name').value;
    
    // VALIDACIONES (mismas de la práctica anterior)
    var errores = [];
    
    // Validar nombre: requerido y <= 100 caracteres
    if (!finalJSON.nombre || finalJSON.nombre.trim() === '' || finalJSON.nombre.length > 100) {
        errores.push('El nombre es requerido y debe tener 100 caracteres o menos');
    }
    
    // Validar marca: requerida
    if (!finalJSON.marca || finalJSON.marca.trim() === '' || finalJSON.marca === 'NA') {
        errores.push('La marca es requerida');
    }
    
    // Validar modelo: requerido, alfanumérico y <= 25 caracteres
    var alfanumerico = /^[a-zA-Z0-9\s]+$/;
    if (!finalJSON.modelo || finalJSON.modelo === 'XX-000' || !alfanumerico.test(finalJSON.modelo) || finalJSON.modelo.length > 25) {
        errores.push('El modelo es requerido, debe ser alfanumérico y tener 25 caracteres o menos');
    }
    
    // Validar precio: requerido y > 99.99
    if (!finalJSON.precio || parseFloat(finalJSON.precio) <= 99.99) {
        errores.push('El precio es requerido y debe ser mayor a 99.99');
    }
    
    // Validar detalles: opcional, pero si existe <= 250 caracteres
    if (finalJSON.detalles && finalJSON.detalles !== 'NA' && finalJSON.detalles.length > 250) {
        errores.push('Los detalles deben tener 250 caracteres o menos');
    }
    
    // Validar unidades: requeridas y >= 0
    if (finalJSON.unidades === undefined || parseInt(finalJSON.unidades) < 0) {
        errores.push('Las unidades son requeridas y deben ser mayor o igual a 0');
    }
    
    // Validar imagen: opcional, si está vacía usar default
    if (!finalJSON.imagen || finalJSON.imagen.trim() === '') {
        finalJSON.imagen = 'img/default.png';
    }
    
    // Si hay errores, mostrarlos y detener el envío
    if (errores.length > 0) {
        alert('Errores de validación:\n\n' + errores.join('\n'));
        return;
    }
    
    // SE OBTIENE EL STRING DEL JSON FINAL
    productoJsonString = JSON.stringify(finalJSON, null, 2);

    // SE CREA EL OBJETO DE CONEXIÓN ASÍNCRONA AL SERVIDOR
    var client = getXMLHttpRequest();
    client.open('POST', './backend/create.php', true);
    client.setRequestHeader('Content-Type', "application/json;charset=UTF-8");
    client.onreadystatechange = function () {
        // SE VERIFICA SI LA RESPUESTA ESTÁ LISTA Y FUE SATISFACTORIA
        if (client.readyState == 4 && client.status == 200) {
            console.log(client.responseText);
            // MOSTRAR RESPUESTA DEL SERVIDOR
            window.alert(client.responseText);
        }
    };
    client.send(productoJsonString);
}

// SE CREA EL OBJETO DE CONEXIÓN COMPATIBLE CON EL NAVEGADOR
function getXMLHttpRequest() {
    var objetoAjax;

    try{
        objetoAjax = new XMLHttpRequest();
    }catch(err1){
        try{
            // IE7 y IE8
            objetoAjax = new ActiveXObject("Msxml2.XMLHTTP");
        }catch(err2){
            try{
                // IE5 y IE6
                objetoAjax = new ActiveXObject("Microsoft.XMLHTTP");
            }catch(err3){
                objetoAjax = false;
            }
        }
    }
    return objetoAjax;
}

function init() {
    /**
     * Convierte el JSON a string para poder mostrarlo
     */
    var JsonString = JSON.stringify(baseJSON, null, 2);
    document.getElementById("description").value = JsonString;
}
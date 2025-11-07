// CUANDO EL DOCUMENTO ESTÉ LISTO
$(document).ready(function() {
    // Listar productos al cargar la página
    listarProductos();
    
    // Event listener para el formulario de búsqueda
    $('#search-form').submit(function(e) {
        e.preventDefault();
        buscarProducto();
    });
    
    // Búsqueda en tiempo real mientras se escribe
    $('#search').keyup(function() {
        let search = $(this).val();
        if(search) {
            buscarProducto();
        } else {
            listarProductos();
            $('#product-result').addClass('d-none');
        }
    });
    
    // VALIDACIONES EN TIEMPO REAL (onChange/onBlur)
    
    // Validar nombre cuando pierde el foco
    $('#name').blur(function() {
        validarNombre();
        // Validar que no exista en BD (paso 7) - Comentado temporalmente para debugging
        // validarNombreExistente();
    });
    
    // Validar marca cuando cambia
    $('#marca').change(function() {
        validarMarca();
    });
    
    // Validar modelo cuando pierde el foco
    $('#modelo').blur(function() {
        validarModelo();
    });
    
    // Validar precio cuando pierde el foco
    $('#precio').blur(function() {
        validarPrecio();
    });
    
    // Validar detalles cuando pierde el foco
    $('#detalles').blur(function() {
        validarDetalles();
    });
    
    // Validar unidades cuando pierde el foco
    $('#unidades').blur(function() {
        validarUnidades();
    });
    
    // Event listener para el formulario de agregar/modificar producto
    $('#product-form').submit(function(e) {
        e.preventDefault();
        
        // Validar todos los campos antes de enviar
        let nombreValido = validarNombre();
        let marcaValida = validarMarca();
        let modeloValido = validarModelo();
        let precioValido = validarPrecio();
        let detallesValidos = validarDetalles();
        let unidadesValidas = validarUnidades();
        
        // Si todos los campos son válidos, proceder
        if(nombreValido && marcaValida && modeloValido && precioValido && detallesValidos && unidadesValidas) {
            agregarProducto();
            // PASO 3: Cambiar texto del botón después de enviar
            $('button.btn-primary').text("Agregar Producto");
        } else {
            alert('Por favor corrige los errores antes de continuar');
        }
    });
    
    // Event delegation para el botón eliminar
    $(document).on('click', '.product-delete', function() {
        if(confirm('¿De verdad deseas eliminar el Producto?')) {
            let productId = $(this).closest('tr').attr('productId');
            eliminarProducto(productId);
        }
    });
    
    // PASO 2: Event delegation para editar producto
    $(document).on('click', '.product-item', function(e) {
        let row = $(this);
        let productId = row.attr('productId');
        let nombre = row.find('td:eq(1)').text();
        
        // Obtener los datos del producto desde los <li>
        let precio = row.find('li:contains("precio:")').text().replace('precio: ', '');
        let unidades = row.find('li:contains("unidades:")').text().replace('unidades: ', '');
        let modelo = row.find('li:contains("modelo:")').text().replace('modelo: ', '');
        let marca = row.find('li:contains("marca:")').text().replace('marca: ', '');
        let detalles = row.find('li:contains("detalles:")').text().replace('detalles: ', '');
        let imagen = row.find('li:contains("imagen:")').text().replace('imagen: ', '') || 'img/default.png';
        
        // Llenar el formulario con los datos
        $('#productId').val(productId);
        $('#name').val(nombre);
        $('#marca').val(marca);
        $('#modelo').val(modelo);
        $('#precio').val(parseFloat(precio));
        $('#detalles').val(detalles);
        $('#unidades').val(parseInt(unidades));
        $('#imagen').val(imagen);
        
        // PASO 2: Cambiar el texto del botón a "Modificar Producto"
        $('button.btn-primary').text("Modificar Producto");
        
        // Scroll al formulario
        $('html, body').animate({
            scrollTop: $('#product-form').offset().top - 100
        }, 500);
    });
});

// ========== FUNCIONES DE VALIDACIÓN (PASO 5) ==========

function validarNombre() {
    let nombre = $('#name').val().trim();
    let valido = true;
    let mensaje = '';
    
    if(nombre === '') {
        valido = false;
        mensaje = 'El nombre es requerido';
    } else if(nombre.length > 100) {
        valido = false;
        mensaje = 'El nombre debe tener 100 caracteres o menos';
    } else {
        mensaje = '✓ Nombre válido';
    }
    
    mostrarValidacion('name', valido, mensaje);
    return valido;
}

// PASO 7: Validar que el nombre no exista en la BD
function validarNombreExistente() {
    // TEMPORALMENTE DESHABILITADO PARA DEBUGGING
    return;
    
    let nombre = $('#name').val().trim();
    let productId = $('#productId').val();
    
    if(nombre !== '') {
        $.ajax({
            url: './backend/product-validate-name.php',
            type: 'POST',
            data: JSON.stringify({ nombre: nombre, id: productId }),
            contentType: 'application/json',
            success: function(response) {
                let respuesta = JSON.parse(response);
                if(respuesta.exists) {
                    mostrarValidacion('name', false, '⚠ Este nombre de producto ya existe');
                } else {
                    mostrarValidacion('name', true, '✓ Nombre disponible');
                }
            }
        });
    }
}

function validarMarca() {
    let marca = $('#marca').val();
    let valido = true;
    let mensaje = '';
    
    if(marca === '') {
        valido = false;
        mensaje = 'Debes seleccionar una marca';
    } else {
        mensaje = '✓ Marca válida';
    }
    
    mostrarValidacion('marca', valido, mensaje);
    return valido;
}

function validarModelo() {
    let modelo = $('#modelo').val().trim();
    let valido = true;
    let mensaje = '';
    let alfanumerico = /^[a-zA-Z0-9\s\-]+$/;
    
    if(modelo === '') {
        valido = false;
        mensaje = 'El modelo es requerido';
    } else if(!alfanumerico.test(modelo)) {
        valido = false;
        mensaje = 'El modelo debe ser alfanumérico (puede incluir guiones y espacios)';
    } else if(modelo.length > 25) {
        valido = false;
        mensaje = 'El modelo debe tener 25 caracteres o menos';
    } else {
        mensaje = '✓ Modelo válido';
    }
    
    mostrarValidacion('modelo', valido, mensaje);
    return valido;
}

function validarPrecio() {
    let precio = parseFloat($('#precio').val());
    let valido = true;
    let mensaje = '';
    
    if(isNaN(precio)) {
        valido = false;
        mensaje = 'El precio es requerido';
    } else if(precio <= 99.99) {
        valido = false;
        mensaje = 'El precio debe ser mayor a 99.99';
    } else {
        mensaje = '✓ Precio válido';
    }
    
    mostrarValidacion('precio', valido, mensaje);
    return valido;
}

function validarDetalles() {
    let detalles = $('#detalles').val().trim();
    let valido = true;
    let mensaje = '';
    
    if(detalles.length > 250) {
        valido = false;
        mensaje = 'Los detalles deben tener 250 caracteres o menos';
    } else if(detalles.length > 0) {
        mensaje = '✓ Detalles válidos';
    } else {
        mensaje = 'Opcional';
    }
    
    mostrarValidacion('detalles', valido, mensaje);
    return valido;
}

function validarUnidades() {
    let unidades = parseInt($('#unidades').val());
    let valido = true;
    let mensaje = '';
    
    if(isNaN(unidades)) {
        valido = false;
        mensaje = 'Las unidades son requeridas';
    } else if(unidades < 0) {
        valido = false;
        mensaje = 'Las unidades deben ser mayor o igual a 0';
    } else {
        mensaje = '✓ Unidades válidas';
    }
    
    mostrarValidacion('unidades', valido, mensaje);
    return valido;
}

// PASO 6: Mostrar validación en la barra de estado de cada campo
function mostrarValidacion(campo, valido, mensaje) {
    let $input = $('#' + campo);
    let $mensaje = $('#' + campo + '-validation');
    
    if(valido) {
        $input.removeClass('is-invalid').addClass('is-valid');
        $mensaje.removeClass('invalid').addClass('valid');
    } else {
        $input.removeClass('is-valid').addClass('is-invalid');
        $mensaje.removeClass('valid').addClass('invalid');
    }
    
    $mensaje.text(mensaje);
}

// ========== FUNCIONES CRUD ==========

// LISTAR TODOS LOS PRODUCTOS
function listarProductos() {
    $.ajax({
        url: './backend/product-list.php',
        type: 'GET',
        success: function(response) {
            let productos = JSON.parse(response);
            
            if(Object.keys(productos).length > 0) {
                let template = '';
                
                productos.forEach(producto => {
                    let descripcion = '';
                    descripcion += '<li>precio: ' + producto.precio + '</li>';
                    descripcion += '<li>unidades: ' + producto.unidades + '</li>';
                    descripcion += '<li>modelo: ' + producto.modelo + '</li>';
                    descripcion += '<li>marca: ' + producto.marca + '</li>';
                    descripcion += '<li>detalles: ' + producto.detalles + '</li>';
                    descripcion += '<li>imagen: ' + producto.imagen + '</li>';
                    
                    template += `
                        <tr productId="${producto.id}" class="product-item" style="cursor: pointer;">
                            <td>${producto.id}</td>
                            <td>${producto.nombre}</td>
                            <td><ul>${descripcion}</ul></td>
                            <td>
                                <button class="product-delete btn btn-danger btn-sm">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    `;
                });
                
                $('#products').html(template);
            }
        }
    });
}

// BUSCAR PRODUCTOS
function buscarProducto() {
    let search = $('#search').val();
    
    $.ajax({
        url: './backend/product-search.php',
        type: 'GET',
        data: { search: search },
        success: function(response) {
            let productos = JSON.parse(response);
            
            if(Object.keys(productos).length > 0) {
                let template = '';
                let template_bar = '';
                
                productos.forEach(producto => {
                    let descripcion = '';
                    descripcion += '<li>precio: ' + producto.precio + '</li>';
                    descripcion += '<li>unidades: ' + producto.unidades + '</li>';
                    descripcion += '<li>modelo: ' + producto.modelo + '</li>';
                    descripcion += '<li>marca: ' + producto.marca + '</li>';
                    descripcion += '<li>detalles: ' + producto.detalles + '</li>';
                    descripcion += '<li>imagen: ' + producto.imagen + '</li>';
                    
                    template += `
                        <tr productId="${producto.id}" class="product-item" style="cursor: pointer;">
                            <td>${producto.id}</td>
                            <td>${producto.nombre}</td>
                            <td><ul>${descripcion}</ul></td>
                            <td>
                                <button class="product-delete btn btn-danger btn-sm">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    `;
                    
                    template_bar += `<li>${producto.nombre}</li>`;
                });
                
                $('#product-result').removeClass('d-none');
                $('#container').html(template_bar);
                $('#products').html(template);
            }
        }
    });
}

// AGREGAR O ACTUALIZAR PRODUCTO
function agregarProducto() {
    let productId = $('#productId').val();
    
    let finalJSON = {
        nombre: $('#name').val().trim(),
        marca: $('#marca').val(),
        modelo: $('#modelo').val().trim(),
        precio: parseFloat($('#precio').val()),
        detalles: $('#detalles').val().trim(),
        unidades: parseInt($('#unidades').val()),
        imagen: $('#imagen').val().trim() || 'img/default.png'
    };
    
    if(productId) {
        finalJSON['id'] = productId;
    }
    
    let url = productId ? './backend/product-edit.php' : './backend/product-add.php';
    
    console.log('Enviando datos:', finalJSON);
    console.log('URL:', url);
    
    $.ajax({
        url: url,
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(finalJSON),
        beforeSend: function() {
            console.log('Enviando solicitud...');
        },
        success: function(response) {
            console.log('Respuesta recibida:', response);
            try {
                let respuesta = JSON.parse(response);
                let template_bar = `
                    <li style="list-style: none;">status: ${respuesta.status}</li>
                    <li style="list-style: none;">message: ${respuesta.message}</li>
                `;
                
                $('#product-result').removeClass('d-none');
                $('#container').html(template_bar);
                
                // Limpiar formulario
                $('#product-form').trigger('reset');
                $('#productId').val('');
                $('.validation-message').text('');
                $('.form-control').removeClass('is-valid is-invalid');
                
                // Listar productos actualizados
                listarProductos();
            } catch(e) {
                console.error('Error al parsear JSON:', e);
                alert('Error al procesar la respuesta del servidor');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error AJAX:', status, error);
            console.error('Respuesta del servidor:', xhr.responseText);
            alert('Error al conectar con el servidor: ' + error);
        }
    });
}

// ELIMINAR PRODUCTO
function eliminarProducto(id) {
    $.ajax({
        url: './backend/product-delete.php',
        type: 'GET',
        data: { id: id },
        success: function(response) {
            let respuesta = JSON.parse(response);
            let template_bar = `
                <li style="list-style: none;">status: ${respuesta.status}</li>
                <li style="list-style: none;">message: ${respuesta.message}</li>
            `;
            
            $('#product-result').removeClass('d-none');
            $('#container').html(template_bar);
            
            listarProductos();
        }
    });
}
// JSON BASE A MOSTRAR EN FORMULARIO
const baseJSON = {
    "precio": 0.0,
    "unidades": 1,
    "modelo": "XX-000",
    "marca": "NA",
    "detalles": "NA",
    "imagen": "img/default.png"
};

// CUANDO EL DOCUMENTO ESTÉ LISTO
$(document).ready(function() {
    init();
    
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
    
    // Event listener para el formulario de agregar producto
    $('#product-form').submit(function(e) {
        e.preventDefault();
        agregarProducto();
    });
    
    // Event delegation para el botón eliminar (porque los botones se crean dinámicamente)
    $(document).on('click', '.product-delete', function() {
        if(confirm('¿De verdad deseas eliminar el Producto?')) {
            let productId = $(this).closest('tr').attr('productId');
            eliminarProducto(productId);
        }
    });
    
    // Event delegation para el botón editar
    $(document).on('click', '.product-edit', function() {
        let row = $(this).closest('tr');
        let productId = row.attr('productId');
        let nombre = row.find('td:eq(1)').text();
        
        // Obtener los datos del producto desde los <li>
        let precio = row.find('li:contains("precio:")').text().replace('precio: ', '');
        let unidades = row.find('li:contains("unidades:")').text().replace('unidades: ', '');
        let modelo = row.find('li:contains("modelo:")').text().replace('modelo: ', '');
        let marca = row.find('li:contains("marca:")').text().replace('marca: ', '');
        let detalles = row.find('li:contains("detalles:")').text().replace('detalles: ', '');
        
        // Llenar el formulario con los datos
        $('#productId').val(productId);
        $('#name').val(nombre);
        
        let productoJSON = {
            "precio": parseFloat(precio),
            "unidades": parseInt(unidades),
            "modelo": modelo,
            "marca": marca,
            "detalles": detalles,
            "imagen": "img/default.png"
        };
        
        $('#description').val(JSON.stringify(productoJSON, null, 2));
        
        // Cambiar el texto del botón
        $('#product-form button[type="submit"]').text('Actualizar Producto');
    });
});

function init() {
    let JsonString = JSON.stringify(baseJSON, null, 2);
    $('#description').val(JsonString);
}

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
                    
                    template += `
                        <tr productId="${producto.id}">
                            <td>${producto.id}</td>
                            <td>${producto.nombre}</td>
                            <td><ul>${descripcion}</ul></td>
                            <td>
                                <button class="product-edit btn btn-warning btn-sm">
                                    Editar
                                </button>
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
                    
                    template += `
                        <tr productId="${producto.id}">
                            <td>${producto.id}</td>
                            <td>${producto.nombre}</td>
                            <td><ul>${descripcion}</ul></td>
                            <td>
                                <button class="product-edit btn btn-warning btn-sm">
                                    Editar
                                </button>
                                <button class="product-delete btn btn-danger btn-sm">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    `;
                    
                    template_bar += `<li>${producto.nombre}</li>`;
                });
                
                // Mostrar barra de estado
                $('#product-result').removeClass('d-none');
                $('#container').html(template_bar);
                $('#products').html(template);
            }
        }
    });
}

// AGREGAR O ACTUALIZAR PRODUCTO
function agregarProducto() {
    let productoJsonString = $('#description').val();
    let finalJSON = JSON.parse(productoJsonString);
    finalJSON['nombre'] = $('#name').val();
    let productId = $('#productId').val();
    
    // VALIDACIONES
    let errores = [];
    
    if (!finalJSON.nombre || finalJSON.nombre.trim() === '' || finalJSON.nombre.length > 100) {
        errores.push('El nombre es requerido y debe tener 100 caracteres o menos');
    }
    
    if (!finalJSON.marca || finalJSON.marca.trim() === '' || finalJSON.marca === 'NA') {
        errores.push('La marca es requerida');
    }
    
    let alfanumerico = /^[a-zA-Z0-9\s\-]+$/;
    if (!finalJSON.modelo || finalJSON.modelo === 'XX-000' || !alfanumerico.test(finalJSON.modelo) || finalJSON.modelo.length > 25) {
        errores.push('El modelo es requerido, debe ser alfanumérico y tener 25 caracteres o menos');
    }
    
    if (!finalJSON.precio || parseFloat(finalJSON.precio) <= 99.99) {
        errores.push('El precio es requerido y debe ser mayor a 99.99');
    }
    
    if (finalJSON.detalles && finalJSON.detalles !== 'NA' && finalJSON.detalles.length > 250) {
        errores.push('Los detalles deben tener 250 caracteres o menos');
    }
    
    if (finalJSON.unidades === undefined || parseInt(finalJSON.unidades) < 0) {
        errores.push('Las unidades son requeridas y deben ser mayor o igual a 0');
    }
    
    if (!finalJSON.imagen || finalJSON.imagen.trim() === '') {
        finalJSON.imagen = 'img/default.png';
    }
    
    if (errores.length > 0) {
        alert('Errores de validación:\n\n' + errores.join('\n'));
        return;
    }
    
    // Si hay productId, es una edición
    if(productId) {
        finalJSON['id'] = productId;
    }
    
    let url = productId ? './backend/product-edit.php' : './backend/product-add.php';
    
    $.ajax({
        url: url,
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(finalJSON),
        success: function(response) {
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
            $('#product-form button[type="submit"]').text('Agregar Producto');
            init();
            
            // Listar productos actualizados
            listarProductos();
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
            
            // Listar productos actualizados
            listarProductos();
        }
    });
}
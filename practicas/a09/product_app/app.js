console.log(">>> APP.JS A09 REST CARGADO <<<");

// CUANDO EL DOCUMENTO ESTÉ LISTO
$(document).ready(function () {

    listarProductos();

    // Búsqueda manual
    $('#search-form').submit(function (e) {
        e.preventDefault();
        buscarProducto();
    });

    // Búsqueda en tiempo real
    $('#search').keyup(function () {
        let search = $(this).val().trim();
        if (search === '') {
            listarProductos();
            $('#product-result').addClass('d-none');
        } else {
            buscarProducto();
        }
    });

    // Validaciones en tiempo real
    $('#name').blur(validarNombre);
    $('#marca').change(validarMarca);
    $('#modelo').blur(validarModelo);
    $('#precio').blur(validarPrecio);
    $('#detalles').blur(validarDetalles);
    $('#unidades').blur(validarUnidades);

    // Enviar formulario (agregar / editar)
    $('#product-form').submit(function (e) {
        e.preventDefault();

        let valid =
            validarNombre() &&
            validarMarca() &&
            validarModelo() &&
            validarPrecio() &&
            validarDetalles() &&
            validarUnidades();

        if (valid) {
            agregarProducto();
            $('button.btn-primary').text("Agregar Producto");
        } else {
            alert('Corrige los errores antes de continuar.');
        }
    });

    // Eliminar producto
    $(document).on('click', '.product-delete', function () {
        if (confirm('¿Deseas eliminar este producto?')) {
            let productId = $(this).closest('tr').attr('productId');
            eliminarProducto(productId);
        }
    });

    // Editar producto
    $(document).on('click', '.product-item', function () {
        let row = $(this);

        let productId = row.attr('productId');
        let nombre = row.find('td:eq(1)').text();

        let precio = row.find('li:contains("precio:")').text().replace('precio: ', '');
        let unidades = row.find('li:contains("unidades:")').text().replace('unidades: ', '');
        let modelo = row.find('li:contains("modelo:")').text().replace('modelo: ', '');
        let marca = row.find('li:contains("marca:")').text().replace('marca: ', '');
        let detalles = row.find('li:contains("detalles:")').text().replace('detalles: ', '');
        let imagen = row.find('li:contains("imagen:")').text().replace('imagen: ', '');

        $('#productId').val(productId);
        $('#name').val(nombre);
        $('#marca').val(marca);
        $('#modelo').val(modelo);
        $('#precio').val(parseFloat(precio));
        $('#detalles').val(detalles);
        $('#unidades').val(parseInt(unidades));
        $('#imagen').val(imagen);

        $('button.btn-primary').text("Modificar Producto");

        $('html, body').animate({
            scrollTop: $('#product-form').offset().top - 100
        }, 500);
    });

});

/* ==========================
        VALIDACIONES
   ========================== */

function mostrarValidacion(campo, valido, mensaje) {
    let $input = $('#' + campo);
    let $mensaje = $('#' + campo + '-validation');

    if (valido) {
        $input.removeClass('is-invalid').addClass('is-valid');
        $mensaje.removeClass('invalid').addClass('valid');
    } else {
        $input.removeClass('is-valid').addClass('is-invalid');
        $mensaje.removeClass('valid').addClass('invalid');
    }
    $mensaje.text(mensaje);
}

function validarNombre() {
    let nombre = $('#name').val().trim();
    let valido = true;
    let mensaje = '';

    if (nombre === '') {
        valido = false;
        mensaje = 'El nombre es requerido';
    } else if (nombre.length > 100) {
        valido = false;
        mensaje = 'Máximo 100 caracteres';
    } else {
        mensaje = '✓ Nombre válido';
    }

    mostrarValidacion('name', valido, mensaje);
    return valido;
}

function validarMarca() {
    let marca = $('#marca').val();
    let valido = marca !== '';
    mostrarValidacion('marca', valido, valido ? '✓ Marca válida' : 'Debes seleccionar una marca');
    return valido;
}

function validarModelo() {
    let modelo = $('#modelo').val().trim();
    let alfanumerico = /^[a-zA-Z0-9\s\-]+$/;

    let valido = true;
    let mensaje = '';

    if (modelo === '') {
        valido = false;
        mensaje = 'Modelo requerido';
    } else if (!alfanumerico.test(modelo)) {
        valido = false;
        mensaje = 'Solo letras, números y guiones';
    } else if (modelo.length > 25) {
        valido = false;
        mensaje = 'Máximo 25 caracteres';
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

    if (isNaN(precio)) {
        valido = false;
        mensaje = 'El precio es requerido';
    } else if (precio <= 99.99) {
        valido = false;
        mensaje = 'Debe ser mayor a 99.99';
    } else {
        mensaje = '✓ Precio válido';
    }

    mostrarValidacion('precio', valido, mensaje);
    return valido;
}

function validarDetalles() {
    let detalles = $('#detalles').val().trim();
    let valido = detalles.length <= 250;
    mostrarValidacion('detalles', valido, valido ? '✓ Detalles válidos' : 'Máximo 250 caracteres');
    return valido;
}

function validarUnidades() {
    let unidades = parseInt($('#unidades').val());
    let valido = true;
    let mensaje = '';

    if (isNaN(unidades)) {
        valido = false;
        mensaje = 'Requerido';
    } else if (unidades < 0) {
        valido = false;
        mensaje = '>= 0';
    } else {
        mensaje = '✓ Unidades válidas';
    }

    mostrarValidacion('unidades', valido, mensaje);
    return valido;
}

/* ==========================
        CRUD (API REST)
   ========================== */

// GET /products  → listar
function listarProductos() {
    $.ajax({
        url: './backend/products',
        type: 'GET',
        dataType: 'json',
        success: function (productos) {
            let template = '';

            productos.forEach(producto => {
                let descripcion = `
                    <li>precio: ${producto.precio}</li>
                    <li>unidades: ${producto.unidades}</li>
                    <li>modelo: ${producto.modelo}</li>
                    <li>marca: ${producto.marca}</li>
                    <li>detalles: ${producto.detalles}</li>
                    <li>imagen: ${producto.imagen}</li>
                `;

                template += `
                    <tr productId="${producto.id}" class="product-item" style="cursor:pointer;">
                        <td>${producto.id}</td>
                        <td>${producto.nombre}</td>
                        <td><ul>${descripcion}</ul></td>
                        <td>
                            <button class="product-delete btn btn-danger btn-sm">Eliminar</button>
                        </td>
                    </tr>
                `;
            });

            $('#products').html(template);
        },
        error: function (xhr) {
            console.error('Error al cargar productos:', xhr.status, xhr.statusText);
            alert('Error al cargar productos: ' + xhr.statusText);
        }
    });
}

// GET /products/{search}  → buscar
function buscarProducto() {
    let search = $('#search').val().trim();

    $.ajax({
        url: `./backend/products/${encodeURIComponent(search)}`,
        type: 'GET',
        dataType: 'json',
        success: function (productos) {
            if (productos && productos.length > 0) {
                let template = '';
                let template_bar = '';

                productos.forEach(producto => {
                    let descripcion = `
                        <li>precio: ${producto.precio}</li>
                        <li>unidades: ${producto.unidades}</li>
                        <li>modelo: ${producto.modelo}</li>
                        <li>marca: ${producto.marca}</li>
                        <li>detalles: ${producto.detalles}</li>
                        <li>imagen: ${producto.imagen}</li>
                    `;

                    template += `
                        <tr productId="${producto.id}" class="product-item" style="cursor:pointer;">
                            <td>${producto.id}</td>
                            <td>${producto.nombre}</td>
                            <td><ul>${descripcion}</ul></td>
                            <td>
                                <button class="product-delete btn btn-danger btn-sm">Eliminar</button>
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

// POST /product  — agregar
// PUT  /product  — editar
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

    let metodo, url;

    if (productId) {
        finalJSON['id'] = productId;
        metodo = 'PUT';
        url = './backend/product';
    } else {
        metodo = 'POST';
        url = './backend/product';
    }

    $.ajax({
        url: url,
        type: metodo,
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify(finalJSON),
        success: function (respuesta) {

            let template_bar = `
                <li style="list-style:none;">status: ${respuesta.status}</li>
                <li style="list-style:none;">message: ${respuesta.message}</li>
            `;

            $('#product-result').removeClass('d-none');
            $('#container').html(template_bar);

            $('#product-form').trigger('reset');
            $('#productId').val('');
            $('.validation-message').text('');
            $('.form-control').removeClass('is-valid is-invalid');

            listarProductos();
        }
    });
}

// DELETE /product  — eliminar
function eliminarProducto(id) {
    $.ajax({
        url: './backend/product',
        type: 'DELETE',
        contentType: 'application/json',
        data: JSON.stringify({ id: id }),
        success: function (respuesta) {

            let template_bar = `
                <li style="list-style:none;">status: ${respuesta.status}</li>
                <li style="list-style:none;">message: ${respuesta.message}</li>
            `;

            $('#product-result').removeClass('d-none');
            $('#container').html(template_bar);

            listarProductos();
        }
    });
}

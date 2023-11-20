<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Reporte Donaciones
                </div>
                <!-- Listado-->
                <template v-if="listado == 1">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                        <option value="nombre_evento">Evento</option>
                                        <option value="fecha_hora">Fecha-Hora</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup="manejarKeyUp(1, buscar, criterio)"
                                        class="form-control" placeholder="Texto a buscar">
                                    <!--button type="submit" @click="listarVenta(1, buscar, criterio)" class="btn btn-primary"><i
                                            class="fa fa-search"></i> Buscar</button-->
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Usuario</th>
                                        <th>Evento</th>
                                        <th>Fecha Hora</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="venta in arrayVenta" :key="venta.id">
                                        <td v-text="venta.usuario"></td>
                                        <td v-text="venta.evento"></td>
                                        <td v-text="venta.fecha_hora"></td>
                                        <td>
                                            <button type="button" @click="verVenta(venta.id)"
                                                class="btn btn-success btn-sm">
                                                <i class="icon-eye"></i>
                                            </button> &nbsp;

                                            <button type="button" @click="pdfVenta(venta.id)" class="btn btn-info btn-sm">
                                            <i class="icon-doc"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPagina(pagination.current_page - 1, buscar, criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page"
                                    :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page, buscar, criterio)"
                                        v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPagina(pagination.current_page + 1, buscar, criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
<!--fin de listado productos-->
<!--inicio de listado ropa-->
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <label>ROPAS</label>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Usuario</th>
                                        <th>Evento</th>
                                        <th>Fecha Hora</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="venta in arrayVentaR" :key="venta.id">
                                        <td v-text="venta.usuario"></td>
                                        <td v-text="venta.evento"></td>
                                        <td v-text="venta.fecha_hora"></td>
                                        <td>
                                            <button type="button" @click="verVentaR(venta.id)"
                                                class="btn btn-success btn-sm">
                                                <i class="icon-eye"></i>
                                            </button> &nbsp;

                                            <button type="button" @click="pdfVentaR(venta.id)" class="btn btn-info btn-sm">
                                            <i class="icon-doc"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPagina(pagination.current_page - 1, buscar, criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page"
                                    :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page, buscar, criterio)"
                                        v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#"
                                        @click.prevent="cambiarPagina(pagination.current_page + 1, buscar, criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </template>
                <!--Fin Listado prducto y ropa-->
               
                <!--Ver ingreso-->
                <template v-else-if="listado == 2">
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="">Evento</label>
                                    <p v-text="evento"></p>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Cantidad</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="detalle in arrayDetalle" :key="detalle.id">
                                            <td v-text="detalle.producto">
                                            </td>
                                            <td v-text="detalle.cantidad">
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="5">
                                                No hay articulos agregados
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" @click="ocultarDetalle()" class="btn btn-secondary">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </template>
                <!--fin detalle donacion productos-->
                <!--detalle donacion ropa-->
                <template v-else-if="listado == 3">
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="">Eventito</label>
                                    <p v-text="evento"></p>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Ropa</th>
                                            <th>Cantidad</th>
                                            <th>Talla</th>
                                            <th>Sexo</th>

                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalleR.length">
                                        <tr v-for="detalle in arrayDetalleR" :key="detalle.id">
                                            <td v-text="detalle.nombre_ropa">
                                            </td>
                                            <td v-text="detalle.cantidad">
                                            </td>
                                            <td v-text="detalle.talla">
                                            </td>
                                            <td v-text="detalle.sexo">
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="5">
                                                No hay articulos agregados
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" @click="ocultarDetalle()" class="btn btn-secondary">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </template>
                <!--Fin ver ingreso-->
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
        <!--Fin del modal-->
    </main>
</template>

<script>
import vSelect from 'vue-select';
export default {
    data() {
        return {
            venta_id: 0,
            usuarioAutenticado: null,
            tipo_documento: '',
            complemento_id: '',
            evento : 0,
            arrayVenta: [],
            arrayVentaR: [],
            arrayDetalle: [],
            arrayDetalleR: [],
            arrayProductos: [],
            arrayCategorias : [],
            listado: 1,
            modal: 0,
            tituloModal: '',
            tipoAccion: 0,
            errorVenta: 0,
            errorMostrarMsjVenta: [],
            pagination: {
                'total': 0,
                'current_page': 0,
                'per_page': 0,
                'last_page': 0,
                'from': 0,
                'to': 0,
            },
            offset: 3,
            criterio: '',
            buscar: '',
            criterioA: 'nombre',
            buscarA: '',
            arrayArticulo: [],
            idarticulo: 0,
            codigo: '',
            articulo: '',
            categoria: '',
            cantidad: 1,
            cantidad: 0,
            cantidadDetalle : 0,
            cantidadRopaId :0,
            datos : [],
            arrayCantidadOriginal:[]
        }
    },
    components: {
        vSelect
    },
    computed: {
        isActived: function () {
            return this.pagination.current_page;
        },

        //Calcula los elementos de la paginación
        pagesNumber: function () {
            if (!this.pagination.to) {
                return [];
            }

            var from = this.pagination.current_page - this.offset;
            if (from < 1) {
                from = 1;
            }

            var to = from + (this.offset * 2);
            if (to >= this.pagination.last_page) {
                to = this.pagination.last_page;
            }

            var pagesArray = [];
            while (from <= to) {
                pagesArray.push(from);
                from++;
            }
            return pagesArray;

        },
    },
    methods: {
        atajoButton: function (event) {
            //console.log(event.keyCode);
            //console.log(event.ctrlKey);


            if (event.shiftKey && event.keyCode === 82) {
                event.preventDefault();
                this.$refs.articuloRef.focus();
            }

            if (event.shiftKey && event.keyCode === 89) {
                event.preventDefault();
                this.$refs.cantidadRef.focus();
            }

        },

        manejarKeyUp(page, buscar, criterio) {
        this.listarVenta(page, buscar, criterio);
        this.listarVentaR(page, buscar, criterio);
        },
        
        pdfVenta(id){
            axios.get('/factura/imprimir/'+id, { responseType: 'blob' })
                .then(function(response) {
                window.location.href = "docs/donacion.pdf";
                console.log("Se generó el PDF correctamente");
                })
                .catch(function(error) {
                console.log(error);
                });
            },

        pdfVentaR(id){
            axios.get('/facturaR/imprimir/'+id, { responseType: 'blob' })
                .then(function(response) {
                window.location.href = "docs/donacionRopa.pdf";
                console.log("Se generó el PDF correctamente");
                })
                .catch(function(error) {
                console.log(error);
                });
            },

        listarVenta(page, buscar, criterio) {
            let me = this;
            var url = '/salidaProductos?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayVenta = respuesta.ventas.data;
                me.pagination = respuesta.pagination;
            })
                .catch(function (error) {
                    console.log(error);
                });
        },

        listarVentaR(page, buscar, criterio) {
            let me = this;
            console.log("estas entrando al metodo listar venta")
            var url = '/salidaRopas?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayVentaR = respuesta.ventas.data;
                me.pagination = respuesta.pagination;
            })
                .catch(function (error) {
                    console.log(error);
                });
        },


        buscarArticulo() {
            let me = this;
            var url = '/producto/buscarProductoVenta?filtro=' + me.codigo;

            axios.get(url).then(function (response) {
                var respuesta = response.data;
                console.log("respuesta ",respuesta);
                me.arrayArticulo = respuesta.articulos;
                
                if (me.arrayArticulo.length > 0) {
                    me.articulo = me.arrayArticulo[0]['nombre_producto'];
                    me.categoria = me.arrayArticulo[0]['tipo_producto'];
                    me.idarticulo = me.arrayArticulo[0]['id'];
                    me.cantidad = me.arrayArticulo[0]['stock'];
                }
                else {
                    me.articulo = 'No existe este articulo';
                    me.idarticulo = 0;
                }
            })
                .catch(function (error) {
                    console.log(error);
                });
        },

        cambiarPagina(page, buscar, criterio) {
            let me = this;
            //Actualiza la página actual
            me.pagination.current_page = page;
            //Envia la petición para visualizar la data de esa página
            me.listarVenta(page, buscar, criterio);
        },
        encuentra(id) {
            var sw = 0;
            for (var i = 0; i < this.arrayDetalle.length; i++) {
                if (this.arrayDetalle[i].idarticulo == id) {
                    sw = true;
                }
            }
            return sw;
        },

        eliminarDetalle(index) {
            let me = this;
            me.arrayDetalle.splice(index, 1);
        },
        async buscarProductoId(id) {
            try {
                let me = this;
                let url = '/productos/buscarProductosId?id=' + id;

                // Utilizamos async/await para esperar la respuesta de la API
                let response = await axios.get(url);

                // Procesamos la respuesta una vez que se completa la solicitud
                let respuesta = response.data;
                me.datos = respuesta.resultados;

                console.log("datos async", me.datos);

                // Puedes devolver datos o realizar otras acciones según tus necesidades
                return me.datos;
            } catch (error) {
                console.log(error);
                // Manejar el error según sea necesario
                throw error; // Propaga el error para que pueda ser manejado por el código que llama a buscarRopaId
            }
        },
        async agregarDetalle() {
            let me = this;

            if (me.idarticulo == 0 || me.cantidad == 0) 
            {
                console.log("No hay nada")
            } else {
                if (me.encuentra(me.idarticulo)) {
                    swal({
                        type: 'error',
                        title: 'Error...',
                        text: 'Este Artículo ya se encuentra agregado!',
                    })
                } else {
                    await me.buscarProductoId(parseInt(me.idarticulo));
                    let resultado = parseInt(me.cantidad) > me.datos[0].cantidad;
                    if (resultado) {
                        swal({
                            type: 'error',
                            title: 'Error...',
                            text: 'No hay cantidad disponible!',
                        })
                    } else {
                        me.arrayDetalle.push({
                            idventa : me.idventa,
                            idarticulo: me.idarticulo,
                            articulo: me.articulo,
                            cantidad: me.cantidad,
                            categoria : me.categoria
                                                });

                        me.arrayCantidadOriginal.push({
                            cantidad: me.cantidad,
                        });
                        me.codigo = '';
                        me.articulo = '';
                        me.categoria = '';
                        me.cantidad = '';
                    }
                }

            }

        },


        obtenerDatosUsuario() {
            axios.get('/salidaProductos')
                .then(response => {
                    this.usuarioAutenticado = response.data.usuario.usuario;
                })
                .catch(error => {
                    console.error(error);
                });
        },

        obtenerDatosCategoria (){
                let me = this;
                var url = '/salidaProductos/obtenerDatosEvento';
                axios.get(url).then(function(response) {
                    var respuesta = response.data;
                    me.arrayCategorias = respuesta.categorias;
                    console.log("CATEGORIAS", me.arrayCategorias);
            })
            .catch(function(error) {
                console.log(error);
            });
            },
        
        ocultarDetalle() {
            this.listarVenta(1, this.buscar, this.criterio);            
            this.listarVentaR(1, this.buscar, this.criterio);
            this.listado = 1;
        },

        verVenta(id) {
            let me = this;
            me.listado = 2;

            //Obtener datos del ingreso
            var arrayVentaT = [];
            var url = '/salidaProductos/obtenerCabecera?id=' + id;

            axios.get(url).then(function (response) {
                var respuesta = response.data;
                arrayVentaT = respuesta.venta;

                me.usuario = arrayVentaT[0]['usuario'];
                me.evento = arrayVentaT[0]['evento'];
            })
                .catch(function (error) {
                    console.log(error);
                });

            //obtener datos de los detalles
            var url = '/salidaProductos/obtenerDetalles?id=' + id;

            axios.get(url).then(function (response) {
                //console.log(response);
                var respuesta = response.data;
                me.arrayDetalle = respuesta.detalles;

            })
                .catch(function (error) {
                    console.log(error);
                });
        },

        verVentaR(id) {
            let me = this;
            me.listado = 3;

            //Obtener datos del ingreso
            var arrayVentaR = [];
            var url = '/salidaRopas/obtenerCabecera?id=' + id;

            axios.get(url).then(function (response) {
                var respuesta = response.data;
                arrayVentaR = respuesta.venta;

                me.usuario = arrayVentaR[0]['usuario'];
                me.evento = arrayVentaR[0]['evento'];
            })
                .catch(function (error) {
                    console.log(error);
                });

            //obtener datos de los detalles
            var url = '/salidaRopas/obtenerDetalles?id=' + id;

            axios.get(url).then(function (response) {
                //console.log(response);
                var respuesta = response.data;
                me.arrayDetalleR = respuesta.detalles;

            })
                .catch(function (error) {
                    console.log(error);
                });
        },

        cerrarModal() {
            this.modal = 0;
            this.tituloModal = '';
        },
        abrirModal() {
            if (this.idAlmacen == 0) {
                return;
            }
            this.arrayArticulo = [];
            this.modal = 1;
            this.tituloModal = 'Seleccione los articulos que desee';
        },

    },

    mounted() {
        this.listarVenta(1, this.buscar, this.criterio);
        this.listarVentaR(1, this.buscar, this.criterio);
        window.addEventListener('keydown', this.atajoButton);
        this.obtenerDatosUsuario();
        this.obtenerDatosCategoria();

    }
}
</script>
<style>    .modal-content {
        width: 100% !important;
        position: absolute !important;
    }

    .mostrar {
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }

    .div-error {
        display: flex;
        justify-content: center;
    }

    .text-error {
        color: red !important;
        font-weight: bold;
    }

    @media (min-width: 600px) {
        .btnagregar {
            margin-top: 2rem;
        }
    }</style>

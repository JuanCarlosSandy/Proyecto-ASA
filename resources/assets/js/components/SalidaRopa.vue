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
                    <i class="fa fa-align-justify"></i> Donar
                    <button type="button" @click="mostrarDetalle()" class="btn btn-secondary">
                        <i class="icon-plus"></i>&nbsp;Nuevo
                    </button>
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
                                    <input type="text" v-model="buscar" @keyup="listarVenta(1, buscar, criterio)"
                                        class="form-control" placeholder="Texto a buscar">
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
                <!--Fin Listado-->
                <!-- Detalle-->
                <template v-else-if="listado == 0">
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-4">
                                <div class="form-group">
                                <label for="nombre_evento"><strong>Categoria</strong></label>
                                <select id="evento" v-model="evento" class="form-control">
                                    <option value="" disabled>Selecciona un Evento</option>
                                    <option v-for="categorias in arrayCategorias" :key="categorias.id" :value="categorias.id">{{ categorias.nombre_evento }}</option>
                                </select>
                        </div>
                            </div>
                            
                            <div class="col-md-12">
                                <div v-show="errorVenta" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjVenta" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="form-group row border">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Ropa <span style="color: red;" v-show="idarticulo == 0">(*Seleccione Ropa)</span></label>
                                    <div class="form-inline">
                                        <input type="text" class="form-control" v-model="codigo" ref="articuloRef"
                                            @keyup="buscarArticulo()" placeholder="Codigo de la ropa">
                                        <button @click="abrirModal()" class="btn btn-primary">...</button>
                                        <input type="text" id="nombre_producto" readonly class="form-control"
                                            v-model="articulo">
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" id="codigo" class="form-control" v-model="codigo" readonly>

                            <div class="col-md-1">
                                <div class="form-group">
                                    <label>Cantidad <span style="color: red;" v-show="cantidad == 0"></span></label>
                                    <input type="number" id="cantidad" value="0" class="form-control" v-model="cantidad" min="0"
                                        ref="cantidadRef">
                                </div>
                            </div>
                           
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button @click="agregarDetalle()" class="btn btn-success form-control btnagregar"><i
                                            class="icon-plus"></i></button>
                                </div>
                            </div>
                        </div>

                        
                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>

                                            <th>Producto</th>
                                            <th>Sexo</th>
                                            <th>Talla</th>
                                            <th>Cantidad</th>
                                            <th>Opciones</th>

                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="(detalle, index) in arrayDetalle" :key="detalle.id">
                                            
                                            <td v-text="detalle.nombre_ropa"></td>
                                            <td v-text="detalle.sexo"></td>
                                            <td v-text="detalle.talla"></td>

                                            <td>
                                                <span style="color:red;" v-show="detalle.cantidad > detalle.cantidad">Stock:
                                                    {{ detalle.cantidad }}</span>
                                                <input v-model="detalle.cantidad" type="number" class="form-control" min="0">
                                            </td>   
                                            <td>
                                                <button @click="eliminarDetalle(index)" type="button"
                                                    class="btn btn-danger btn-sm">
                                                    <i class="icon-close"></i>
                                                </button>
                                            </td>

                                        </tr>

                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="6">
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
                                <button type="button" class="btn btn-primary" @click="registrar()">Registrar
                                    Donacion</button>
                            </div>
                        </div>
                    </div>

                </template>
                <!-- Fin Detalle-->
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
                                            <th>Ropa</th>
                                            <th>Cantidad</th>
                                            <th>Talla</th>
                                            <th>Sexo</th>

                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="detalle in arrayDetalle" :key="detalle.id">
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

        <div class="modal fade" tabindex="-1" :class="{ 'mostrar': modal }" role="dialog" aria-labelledby="myModalLabel"
            style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterioA">
                                        <option value="nombre_ropa">Nombre</option>      
                                        <option value="id">Código</option>
                                    </select>
                                    <input type="text" v-model="buscarA" @keyup="listarArticulo(buscarA, criterioA)"
                                        class="form-control" placeholder="Texto a buscar">
                                    <!--button type="submit" @click="listarArticulo(buscarA, criterioA)"
                                        class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button-->
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>

                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>Stock</th>
                                        <th>Sexo</th>
                                        <th>Talla</th>
                                        <th>Opciones</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="ropa in arrayRopas" :key="ropa.id">
                                        
                                        <td v-text="ropa.id"></td>
                                        <td v-text="ropa.nombre_ropa"></td>
                                        <td v-text="ropa.cantidad"></td>
                                        <td v-text="ropa.sexo"></td>
                                        <td v-text="ropa.talla"></td>
                                        <td>
                                            <button type="button" @click="agregarDetalleModal(ropa)"
                                                class="btn btn-success btn-sm">
                                                <i class="icon-check"></i>
                                            </button>
                                        </td>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button type="button" v-if="tipoAccion == 1" class="btn btn-primary"
                            @click="registrarPersona()">Guardar</button>
                        <button type="button" v-if="tipoAccion == 2" class="btn btn-primary"
                            @click="actualizarPersona()">Actualizar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
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
            arrayDetalle: [],
            arrayCantidadOriginal: [],
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
            arrayRopas: [],
            idarticulo: 0,
            codigo: '',
            articulo: '',
            categoria: '',
            cantidad: 1,
            cantidad: 0,
            cantidadDetalle : 0,
            talla: '',
            sexo: '',
            cantidadRopaId :0,
            datos : []
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


        cuis() {
            axios.post('/venta/cuis')
                .then(function (response) {
                    if (response.data.RespuestaCuis.transaccion === false) {
                        document.getElementById("cuis").innerHTML = "CUIS: " + response.data.RespuestaCuis.codigo;
                        document.getElementById("cuis").className = "badge bg-primary";
                    } else {
                        document.getElementById("cuis").innerHTML = "CUIS: Inexistente";
                        document.getElementById("cuis").className = "badge bg-secondary";
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        cufd() {
            axios.post('/venta/cufd')
                .then(function (response) {
                    if (response.data.transaccion != false) {
                        document.getElementById("cufd").innerHTML = "CUFD vigente: " + response.data.fechaVigencia.substring(0, 16);
                        document.getElementById("direccion").innerHTML = response.data.direccion;
                        document.getElementById("cufdValor").innerHTML = response.data.codigo;
                        document.getElementById("cufd").className = "badge bg-info";
                    } else {
                        document.getElementById("cufd").innerHTML = "No existe CUFD vigente";
                        document.getElementById("cufd").className = "badge bg-secondary";
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
        },

        listarVenta(page, buscar, criterioA) {
            let me = this;
            console.log("estas entrando al metodo listar venta")
            var url = '/salidaRopas?page=' + page + '&buscar=' + buscar + '&criterio=' + criterioA;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayVenta = respuesta.ventas.data;
                me.pagination = respuesta.pagination;
            })
                .catch(function (error) {
                    console.log(error);
                });
        },


        buscarArticulo() {
            let me = this;
            var url = '/ropa/buscarRopaVenta?filtro=' + me.codigo;

            axios.get(url).then(function (response) {
                var respuesta = response.data;
                console.log(respuesta);
                me.arrayRopas = respuesta.ropas;

                if (me.arrayRopas.length > 0) {
                    me.articulo = me.arrayRopas[0]['nombre_ropa'];
                    me.sexo = me.arrayRopas[0]['sexo'];
                    me.idRopa = me.arrayRopas[0]['id'];
                    me.cantidad = me.arrayRopas[0]['stock'];
                    me.talla = me.arrayRopas[0]['talla']
                }
                else {
                    me.articulo = 'No existe este articulo';
                    me.idRopa = 0;
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
                if (this.arrayDetalle[i].idRopa == id) {
                    sw = true;
                }
            }
            return sw;
        },

        eliminarDetalle(index) {
            let me = this;
            me.arrayDetalle.splice(index, 1);
        },
        /*buscarRopaId(id){
            let me = this;
            var url = '/ropa/buscarRopasId?id=' + id;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.datos = respuesta.resultados;
            })
                .catch(function (error) {
                    console.log(error);
                });
        },*/
        async buscarRopaId(id) {
    try {
        let me = this;
        let url = '/ropa/buscarRopasId?id=' + id;

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
            
            if (me.idRopa == 0 || me.cantidad == 0) 
            {
                console.log("No hay nada")
            } else {
                if (me.encuentra(me.idRopa)) {
                    swal({
                        type: 'error',
                        title: 'Error...',
                        text: 'Este Artículo ya se encuentra agregado!',
                    })
                } else {
                    
                    await me.buscarRopaId(parseInt(me.idRopa));
                    
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
                            idRopa: me.idRopa,
                            nombre_ropa: me.articulo,
                            cantidad: me.cantidad,
                            sexo : me.sexo,
                            talla : me.talla
                                                });

                        me.arrayCantidadOriginal.push({
                            cantidad: me.cantidad,
                        });
                        me.codigo = '';
                        me.nombre_ropa = '';
                        me.sexo = '';
                        me.cantidad = '';
                        me.talla = '';
                    }
                }

            }

        },
        agregarDetalleModal(data = []) {
            let me = this;
            if (me.encuentra(data['id'])) {
                swal({
                    type: 'error',
                    title: 'Error...',
                    text: 'Este Artículo ya se encuentra agregado!',
                })
            } else {
                me.arrayDetalle.push({
                    idRopa: data['id'],
                    nombre_ropa: data['nombre_ropa'],
                    sexo : data['sexo'],
                    talla : data['talla'],
                    cantidad: data['cantidad'],
                    
                });
                me.arrayCantidadOriginal.push({
                            cantidad: data['cantidad'],
                        });
            }
            me.buscarA = '';
        },

        listarArticulo(buscar, criterio) {
            let me = this;
            var url = '/ropa/listarRopaVenta?buscar=' + buscar + '&criterio=' + criterio;
            axios.get(url).then(function (response) {
                var respuesta = response.data;
                me.arrayRopas = respuesta.ropas.data;
            })
                .catch(function (error) {
                    console.log(error);
                });
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

        registrar() {
            this.registrarVenta();
            
        },

        registrarVenta() {
            if (this.validarVenta()) {
                return;
            }

            let me = this;

            axios.post('/salidaRopas/registrar', {
                'evento': this.evento,
                'data': this.arrayDetalle

            }).then(function (response) {
                //console.log(response.data.id);
                if (response.data.id > 0) {
                    me.listarVenta(1, this.buscar, this.criterio);
                    me.evento = '';
                    me.idRopa = 0;
                    me.cantidad = 0;
                    me.arrayDetalle = [];
                    //window.open('/factura/imprimir/' + response.data.id);
                }

            }).catch(function (error) {
                console.log(error);
            });
            me.listarVenta(1, this.buscar, this.criterio);
            this.listado = 1;
        },
        
        getAlmacenProductos(almacen) {
            let me = this;
            me.idAlmacen = almacen.id;
            console.log(me.idAlmacen);
        },

        validarVenta() {
            let me = this;
            me.errorVenta = 0;
            me.errorMostrarMsjVenta = [];
            let art;

            /*me.arrayDetalle.map(function (x) {
                if (x.cantidadOriginal < x.cantidad) {
                    art = x.articulo + " cantidad insuficiente";
                    me.errorMostrarMsjVenta.push(art);
                }
            });*/
            this.arrayDetalle.forEach((detalle, index) => {
                if (detalle.cantidad > this.arrayCantidadOriginal[index].cantidad) {
                    art = detalle.nombre_ropa + " cantidad insuficiente";
                    
                    console.log("error ", art)
                    me.errorMostrarMsjVenta.push(art);
                }
                
            });
            this.arrayDetalle.forEach((detalle, index) => {
                if(detalle.cantidad <= 0) {
                    art = detalle.nombre_ropa + " No colocar cantidades negativas";
                    console.log("error ", art)
                    me.errorMostrarMsjVenta.push(art);
                }
                
            });

            
            if (!this.evento) this.errorMostrarMsjVenta.push("El nombre del evento no puede estar vacío.");
            if (me.errorMostrarMsjVenta.length) me.errorVenta = 1;
            return me.errorVenta;
        },


        mostrarDetalle() {
            let me = this;
            me.listado = 0;

            me.evento = '';
            me.idRopa = 0;
            me.articulo = '';
            me.cantidad = 0;
            me.arrayDetalle = [];
        },

        ocultarDetalle() {
            this.listarVenta(1, this.buscar, this.criterio);
            this.listado = 1;
        },

        verVenta(id) {
            let me = this;
            me.listado = 2;

            //Obtener datos del ingreso
            var arrayVentaT = [];
            var url = '/salidaRopas/obtenerCabecera?id=' + id;

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
            var url = '/salidaRopas/obtenerDetalles?id=' + id;

            axios.get(url).then(function (response) {
                //console.log(response);
                var respuesta = response.data;
                me.arrayDetalle = respuesta.detalles;

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
            this.arrayRopas = [];
            this.modal = 1;
            this.tituloModal = 'Seleccione los articulos que desee';
        },

    },

    mounted() {
        this.listarVenta(1, this.buscar, this.criterio);
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

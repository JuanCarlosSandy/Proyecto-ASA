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
                <i class="fa fa-align-justify"></i> Ropa - Vestimenta
                <button type="button" @click="abrirModal('ropa','registrar')" class="btn btn-secondary">
                    <i class="icon-plus"></i>&nbsp;Nuevo
                </button>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <select class="form-control col-md-3" v-model="criterio">
                              <option value="nombre_producto">Nombre</option>
                            </select>
                            <input type="text" v-model="buscar" @keyup.enter="listarProducto(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                            <button type="submit" @click="listarProducto(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Opciones</th>
                            <th>N°</th>
                            <th>Nombre</th>
                            <th>Cantidad</th>
                            <th>Sexo</th>
                            <th>Talla</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="producto in arrayRopas" :key="producto.id">
                            <td>
                                <button type="button" @click="abrirModal('ropa','actualizar',producto)" class="btn btn-warning btn-sm">
                                  <i class="icon-pencil"></i>
                                </button> &nbsp;                                
                                <button type="button" class="btn btn-danger btn-sm" @click="eliminarProducto(producto.id)">
                                    <i class="icon-trash"></i>
                                </button>
                                        
                                    
                            </td>
                            <td v-text="producto.id"></td>
                            <td v-text="producto.nombre_ropa"></td>
                            <td v-text="producto.cantidad"></td>
                            <td v-text="producto.sexo"></td>
                            <td v-text="producto.talla"></td>
                            
                            
                            
                        </tr>                                
                    </tbody>
                </table>
                <nav>
                    <ul class="pagination">
                        <li class="page-item" v-if="pagination.current_page > 1">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                        </li>
                        <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                        </li>
                        <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <!-- Fin ejemplo de tabla Listado -->
    </div>
    <!--Inicio del modal agregar/actualizar-->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-primary modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModal"></h4>
                    <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                      <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                        <div class="row" >
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="tipo_producto">Buscador CI</label>
                                    <div class="col-md-9">
                                    <input type="text-input" v-model="buscarCI" @input="buscarPersonasCI" >
                                    
                                    <ul>
                                        <li v-for="result in arrayDonadores" @click="selectPerson(result)">
                                            {{ result.nombre }}
                                        </li>
                                        <li v-if="arrayDonadores.length === 0 && buscarCI.length === 0">No se encontraron resultados</li>
                                    </ul>
                                </div>
                                </div>
                                <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Nombre Ropa</label>
                            <div class="col-md-9">
                                <input type="text" v-model="nombre_producto" class="form-control" placeholder="Nombre de la ropa" @input="buscarRopas" :disabled="opcionSeleccionada">
                                <ul v-if="!opcionSeleccionada">
                                        <li v-for="ropas in arrayRopasBuscador" @click="selectRopa(ropas)" >
                                            {{ ropas.nombre_ropa }}
                                        </li>
                                        <li v-if="arrayRopasBuscador.length === 0 || nombre_ropa.length === 0">No se encontraron resultados</li>
                                    </ul>
                            </div>
                        </div>
                        
                    
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="email-input">Cantidad</label>
                            <div class="col-md-9">
                                <input type="number" v-model="cantidad" class="form-control" placeholder="Ingrese cantidad en números" min="0">
                            </div>
                        </div>
                        
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Nombre Donador</label>
                            <div class="col-md-9">
                                <input type="text" v-model="nombre_donador" class="form-control" placeholder="Nombre del donador" :disabled="opcionDonador">
                                
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="tipo_producto"><strong>Sexo</strong></label>
                            <select id="sexo" v-model="sexo" class="form-control">
                                <option value="0" disabled>Selecciona un sexo</option>
                                <option value="femenino">Femenino</option>
                                <option value="masculino">Masculino</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tipo_producto"><strong>Talla</strong></label>
                            <select id="idTallas" v-model="idTallas" class="form-control">
                                <option value="0" disabled>Selecciona una Talla</option>
                                <option value="XS"> XS</option>
                                <option value="S"> S</option>
                                <option value="M"> M</option>
                                <option value="L"> L</option>
                                <option value="XL"> XL</option>

                            </select>
                        </div>
                        
                    
                        
                    </div>
                        </div>
                        <div v-show="errorProducto" class="form-group row div-error">
                            <div class="text-center text-error">
                                <div v-for="error in errorMostrarMsjProducto" :key="error" v-text="error">

                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                    <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarProducto()">Guardar</button>
                    <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarProducto()">Actualizar</button>
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
export default {
    data (){
        return {
            id: 0,
            idRopa: 0,
            opcionDonador:false,
            opcionSeleccionada: false,
            buscarCI : '',
            nombre_donador:'',
            arrayDonadores :[],
            arrayRopasBuscador :[],
            buscarRopa:'',
            nombre_ropa : '',
            nombre_producto : '',
            cantidad : '',
            arrayRopas : [],
            arrayTallas : [],
            arrayCategoriaRopa : [],
            idTallas : '',
            talla:'',
            idCategoriaRopa :'',
            sexo : '',
            modal : 0,
            tituloModal : '',
            tipoAccion : 0,
            errorProducto : 0,
            errorMostrarMsjProducto : [],
            pagination : {
                'total' : 0,
                'current_page' : 0,
                'per_page' : 0,
                'last_page' : 0,
                'from' : 0,
                'to' : 0,
            },
            offset : 3,
            criterio : 'nombre',
            buscar : ''
        }
    },
    computed:{
        isActived: function(){
            return this.pagination.current_page;
        },
        //Calcula los elementos de la paginación
        pagesNumber: function() {
            if(!this.pagination.to) {
                return [];
            }
            
            var from = this.pagination.current_page - this.offset; 
            if(from < 1) {
                from = 1;
            }

            var to = from + (this.offset * 2); 
            if(to >= this.pagination.last_page){
                to = this.pagination.last_page;
            }  

            var pagesArray = [];
            while(from <= to) {
                pagesArray.push(from);
                from++;
            }
            return pagesArray;             

        }
    },
    methods : {
        listarProducto (page,buscar,criterio){
            let me=this;
            var url= '/ropa?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
            axios.get(url).then(function (response) {
                var respuesta= response.data;
                me.arrayRopas = respuesta.ropa.data;
                me.pagination= respuesta.pagination;
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        cambiarPagina(page,buscar,criterio){
            let me = this;
            //Actualiza la página actual
            me.pagination.current_page = page;
            //Envia la petición para visualizar la data de esa página
            me.listarProducto(page,buscar,criterio);
        },
        /*registrarProducto(){
            if (this.validarProducto()){
                return;
            }
            let me = this;
            
            axios.post('/ropa/registrar',{
                'nombre_producto': this.nombre_producto,
                'cantidad': this.cantidad,
                'idTallas' : this.idTallas,
                'sexo':this.sexo,
                'idCategoriaRopa':this.idCategoriaRopa
                
            }).then(function (response) {
                me.cerrarModal();
                me.listarProducto(1,'','nombre');
            }).catch(function (error) {
                console.log("error"+error);
            });
            console.log("nombre_producto"+ this.nombre_producto+ "+"+typeof this.nombre_producto);
            console.log("cantidad "+ this.cantidad+ " +"+typeof this.cantidad);
            console.log("id talla "+ this.idTallas+ "+"+typeof this.idTallas);
            console.log("sexo "+ this.sexo+ "+"+typeof this.sexo);
            console.log("idCategoriaRopa "+ this.idCategoriaRopa + "+"+typeof this.idCategoriaRopa);
        },*/
        registrarProducto(){
            if (this.validarProducto()){
                return;
            }
            let me = this;
            axios.post('/entradaRopa/registrar',{
                'idDonador':this.arrayDonadores[0].idDonador,
                'idRopa' :this.idRopa,
                'nombre_ropa' : this.nombre_producto,
                'cantidad' : this.cantidad,
                'talla' : this.idTallas,
                'sexo':this.sexo,
                'estacion':this.idCategoriaRopa
            }).then(function (response) {
                me.cerrarModal();
                me.listarProducto(1,'','nombre');
            }).catch(function (error) {
                console.log("error"+error);
            });
            
        },
        actualizarProducto(){
            if (this.validarProducto()){
                return;
            }
            
            let me = this;
            axios.put('/ropa/actualizar',{
                'nombre_producto': this.nombre_producto,
                'cantidad': this.cantidad,
                'idTallas' : this.idTallas,
                'sexo': this.sexo,
                'idCategoriaRopa': this.idCategoriaRopa,
                'id':this.id
            }).then(function (response) {
                alert("Datos actualizados con éxito");
                me.cerrarModal();
                me.listarProducto(1,'','nombre');
            }).catch(function (error) {
                console.log(error);
            });
             
        },

        obtenerDatosTallas (){
            let me = this;
            var url = '/ropa/obtenerDatosTalla';
            axios.get(url).then(function(response) {
            var respuesta = response.data;
            me.arrayTallas = respuesta.tallas;
            console.log("CATEGORIAS", me.arrayCategorias);
            })
            .catch(function(error) {
                console.log(error);
            });
        },
        obtenerDatosCategoriaRopa (){
            let me = this;
            var url = '/ropa/obtenerDatosCategoriaRopa';
            axios.get(url).then(function(response) {
            var respuesta = response.data;
            me.arrayCategoriaRopa = respuesta.categoriaRopa;
            console.log("CATEGORIAS", me.arrayCategorias);
            })
            .catch(function(error) {
                console.log(error);
            });
        },
        buscarPersonasCI(){
            if (this.buscarCI === '') {
                this.arrayDonadores =[];
                this.buscarCI =''; 
                return;
            }
            let me = this;
            axios.get('/donador/buscarDonador',{ params: { ci: this.buscarCI } }).then(function(response){
                var respuesta = response.data;
                me.arrayDonadores= respuesta.resultados;

               
            })
            .catch(function(error){
                console.log(error);
            });
            
        },
        selectPerson(persona){
            this.buscarCI='';
            console.log("data",this.arrayDonadores[0].nombre)
            console.log("data",this.arrayDonadores[0].idDonador)
            this.nombre_donador = persona.nombre;
            this.idDonador=persona.idDonador;
            console.log("id donador "+this.idDonador );
            this.opcionDonador=true;
        },
        buscarRopas(){
            
            let me = this;
            axios.get('/ropa/buscarRopas',{ params: { nombre_ropa: this.nombre_producto } }).then(function(response){
                var respuesta = response.data;
                
                me.arrayRopasBuscador= respuesta.resultados;


            })
            .catch(function(error){
                console.log(error);
            });
            
        },
        selectRopa(ropa){
            this.idRopa =  ropa.id;
            this.nombre_producto=ropa.nombre_ropa
            this.opcionSeleccionada = true;
            console.log("id donador "+this.idDonador )
        },

        eliminarProducto(id){
            swal({
                title: '¿Está seguro de eliminar este producto?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.delete('/ropa/eliminar/' + id)
                    .then(function (response) {
                        me.listarProducto(1, '', 'nombre');
                        swal(
                            'Eliminado',
                            response.data.message,
                            'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });

                } else if (result.dismiss === swal.DismissReason.cancel) {
                }
            }) 
        },


        validarProducto(){
            this.errorProducto=0;
            this.errorMostrarMsjProducto =[];

            if (!this.nombre_producto) this.errorMostrarMsjProducto.push("El nombre del producto no puede estar vacío.");

            if (this.errorMostrarMsjProducto.length) this.errorProducto = 1;

            return this.errorProducto;
        },
        cerrarModal(){
            this.opcionSeleccionada=false;
            this.opcionDonador= false;
            this.modal=0;
            this.tituloModal='';
            this.nombre_producto='';
            this.cantidad='';
        },
        abrirModal(modelo, accion, data = []){
            switch(modelo){
                case "ropa":
                {
                    switch(accion){
                        case 'registrar':
                        {
                            this.nombre_donador='';
                            this.arrayDonadores=[];
                            this.modal = 1;
                            this.tituloModal = 'Registrar Ropa - Vestimenta';
                            this.nombre_producto= '';
                            this.cantidad='';
                            this.sexo=0;
                            this.idTallas=0;
                            this.idCategoriaRopa=0;
                            this.tipoAccion = 1;
                            break;
                        }
                        case 'actualizar':
                        {
                            //console.log(data);
                            console.log("id actualizar "+data['id'] + typeof data['id']);
                            console.log("nombre"+ data['nombre_ropa'] + typeof data['nombre_ropa'] );
                            console.log("cantidad"+ data['cantidad'] + typeof data['cantidad']);
                            console.log("tallas "+ data['talla'] + typeof data['talla']);
                            console.log("sexo"+ data['sexo'] + typeof data['sexo']);
                            console.log("estacin" + data['estacion'] + typeof  data['estacion'])
                            this.modal=1;
                            this.tituloModal='Actualizar Ropa - Vestimenta';
                            this.tipoAccion=2;
                            this.id=data['id'];
                            this.nombre_producto = data['nombre_ropa'];
                            this.cantidad= data['cantidad'];
                            this.idTallas= data['talla'];
                            this.sexo=data['sexo'];
                            this.idCategoriaRopa=data['estacion']
                            break;
                        }
                    }
                }
            }
        }
    },
    mounted() {
        this.listarProducto(1,this.buscar,this.criterio);
        this.obtenerDatosTallas();
        this.obtenerDatosCategoriaRopa();
        this.arrayDonadores=[];
    }
    }
</script>
<style>    
.modal-content{
width: 100% !important;
position: absolute !important;
}
.mostrar{
display: list-item !important;
opacity: 1 !important;
position: absolute !important;
background-color: #3c29297a !important;
}
.div-error{
display: flex;
justify-content: center;
}
.text-error{
color: red !important;
font-weight: bold;
}
</style>

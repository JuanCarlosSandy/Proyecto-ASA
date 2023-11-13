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
                <i class="fa fa-align-justify"></i> Historial
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
                            <th>Nombre Ropa</th>
                            <th>Nombre Donador</th>
                            <th>Cantidad</th>
                            <th>Talla</th>
                            <th>Sexo</th>
                            <th>Fecha registro</th>
                            <th>Encargado</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="entrada in arrayEntradasRopas" >
                            
                            <td v-text="entrada.nombre_ropa"></td>
                            <td v-text="entrada.nombre"></td>
                            <td v-text="entrada.cantidad"></td>
                            <td v-text="entrada.talla"></td>
                            <td v-text="entrada.sexo"></td>
                            <td v-text="entrada.created_at"></td>
                            <td v-text="entrada.encargado"></td>
                            <td>
                                <button type="button" @click="abrirModal('ropa','actualizar',entrada)" class="btn btn-warning btn-sm">
                                  <i class="icon-pencil"></i>
                                </button> &nbsp;                                
                                <button type="button" class="btn btn-danger btn-sm" @click="eliminarProducto(producto.id)">
                                    <i class="icon-trash"></i>
                                </button>
                                        
                                    
                            </td>
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

                                <div class="form-group">
                            <label  for="tipo_producto"><strong>Nombre Ropa</strong></label>
                            
                                <input type="text" v-model="nombre_producto" class="form-control" placeholder="Nombre de la ropa" :disabled="true">
                                
                            
                        </div>
                        <div class="form-group">
                            <label for="tipo_producto">Cantidad</label>
                            
                                <input type="number" v-model="cantidad" class="form-control" placeholder="Ingrese cantidad en números" min="0">
                            
                        </div>
                        
                    </div>
                    <div class="col-md-6">
                        
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
        nombre_producto : '',
        cantidad : '',
        arrayProducto : [],
        arrayCategorias : [],
        arrayEntradasRopas:[],
        idCategoria_Alimentos : 0,
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
        buscar : '',
        idTallas:'',
        sexo : ''
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
        var url= '/entradaRopa?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
        axios.get(url).then(function (response) {
            var respuesta= response.data;
            me.arrayEntradasRopas = respuesta.entradas;
            console.log("data: ",JSON.stringify(respuesta)); 
            
            me.pagination= respuesta.pagination;
        })
        .catch(function (error) {
            console.log(error);
        });
        console.log("ENTRADA",this.arrayEntradasRopas);
    },
    cambiarPagina(page,buscar,criterio){
        let me = this;
        //Actualiza la página actual
        me.pagination.current_page = page;
        //Envia la petición para visualizar la data de esa página
        me.listarProducto(page,buscar,criterio);
    },
    registrarProducto(){
        if (this.validarProducto()){
            return;
        }
        
        let me = this;

        axios.post('/producto/registrar',{
            'nombre_producto': this.nombre_producto,
            'cantidad': this.cantidad,
            'idCategoria_Alimentos' : this.idCategoria_Alimentos
            
        }).then(function (response) {
            me.cerrarModal();
            me.listarProducto(1,'','nombre');
        }).catch(function (error) {
            console.log(error);
        });
    },
    actualizarHistorialRopa(){
       if (this.validarProducto()){
            return;
        }
        
        let me = this;

        axios.put('/producto/actualizar',{
            'nombre_producto': this.nombre_producto,
            'cantidad': this.cantidad,
            'id': this.id,
            'idCategoria_Alimentos' : this.idCategoria_Alimentos
        }).then(function (response) {
            me.cerrarModal();
            me.listarProducto(1,'','nombre');
        }).catch(function (error) {
            console.log(error);
        }); 
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

                axios.delete('/producto/eliminar/' + id)
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
                        this.modal = 1;
                        this.tituloModal = 'Registrar Producto';
                        this.nombre_producto= '';
                        this.cantidad = '';
                        this.tipoAccion = 1;
                        break;
                    }
                    case 'actualizar':
                    {
                        //console.log(data);
                        this.modal=1;
                        this.tituloModal='Actualizar Producto';
                        this.tipoAccion=2;
                        this.id=data['id'];
                        this.nombre_producto = data['nombre_ropa'];
                        this.cantidad= data['cantidad'];
                        this.idTallas= data['talla'];
                        this.sexo = data['sexo'];

                        break;
                    }
                }
            }
        }
    }
},
mounted() {
    this.listarProducto(1,this.buscar,this.criterio);
    
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

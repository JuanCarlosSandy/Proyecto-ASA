<template>
<main class="main">
    <!-- Breadcrumb  -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
    </ol>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <button @click="showCardBody(1)" class="btn btn-info">
                    <strong>Ropas </strong>
                </button>
                 <button @click="showCardBody(2)" class="btn btn-info">
                    <strong>Productos </strong>
                </button>
            </div>
            <div class="car-body">
                <div v-if="activeButton === 1">
                    <div class="card-header">
                        <h6>Ropas</h6>
                    </div>
                <div class="row">
                    
                    <div class="col-md-6">
                        <div class="card card-chart">
                            <div class="card-header">
                                <h4>Ingresos</h4>
                            </div>
                            <div class="card-content">
                                <div class="ct-chart">
                                    <canvas id="ingresos">                                                
                                    </canvas>
                                </div>
                            </div>
                            <div class="card-footer">
                                <p>Ingresos de los últimos meses.</p>
                            </div>
                        </div>
                    </div>
                
                

                    <div class="col-md-6">
                        <div class="card card-chart">
                            <div class="card-header">
                                <h4>Donaciones</h4>
                            </div>
                            <div class="card-content">
                                <div class="ct-chart">
                                    <canvas id="ventas">                                                
                                    </canvas>
                                </div>
                            </div>
                            <div class="card-footer">
                                <p>Donaciones de los últimos meses.</p>
                            </div>
                        </div>
                    </div>
                
                </div>
                </div>
                <div v-if="activeButton === 2">
                    <div class="card-header">
                        <h6>Productos</h6>
                    </div>
                    <div class="row">
                    <div class="col-md-6">
                        <div class="card card-chart">
                            <div class="card-header">
                                <h4>Ingresos</h4>
                            </div>
                            <div class="card-content">
                                <div class="ct-chart">
                                    <canvas id="ingresosProducto">                                                
                                    </canvas>
                                </div>
                            </div>
                            <div class="card-footer">
                                <p>Ingresos de los últimos meses.</p>
                            </div>
                        </div>
                    </div>
                
                

                    <div class="col-md-6">
                        <div class="card card-chart">
                            <div class="card-header">
                                <h4>Donaciones</h4>
                            </div>
                            <div class="card-content">
                                <div class="ct-chart">
                                    <canvas id="ventasProductos">                                                
                                    </canvas>
                                </div>
                            </div>
                            <div class="card-footer">
                                <p>Donaciones de los últimos meses.</p>
                            </div>
                        </div>
                    </div>
                
                </div>
                </div>
            </div>
            <ropasbajostock></ropasbajostock>
            <productosbajostock></productosbajostock>
        </div>
    </div> 

</main>
</template>
<script>
    export default {
        data (){
            return {
                varIngreso:null,
                charIngreso:null,
                ingresos:[],
                varTotalIngreso:[],
                varMesIngreso:[], 
                
                varVenta:null,
                charVenta:null,
                ventas:[],
                varTotalVenta:[],
                varMesVenta:[],

                varIngresoProducto:null,
                charIngresoProducto:null,
                ingresosProducto:[],
                varTotalIngresoProducto:[],
                varMesIngresoProducto:[], 
                
                varVentaProducto:null,
                charVentaProducto:null,
                ventasProducto:[],
                varTotalVentaProducto:[],
                varMesVentaProducto:[],

                activeButton: null
               
            }
        },
        methods : {
            getIngresos(){
                let me=this;
                var url= '/dashboard';
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.ingresos = respuesta.ingresos;
                    //cargamos los datos del chart
                    me.loadIngresos();
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getIngresosProductos(){
                let me=this;
                var url= '/dashboard';
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.ingresosProducto = respuesta.ingresosProducto;
                    //cargamos los datos del chart
                    me.loadIngresosProductos();
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getVentas(){
                let me=this;
                var url= '/dashboard';
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.ventas = respuesta.ventas;
                    //cargamos los datos del chart
                    me.loadVentas();
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getVentasProductos(){
                let me=this;
                var url= '/dashboard';
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.ventasProducto = respuesta.ventasProductos;
                    //cargamos los datos del chart
                    me.loadVentasProductos();
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            loadIngresos(){
                let me=this;
                me.ingresos.map(function(x){
                    me.varMesIngreso.push(x.mes);
                    me.varTotalIngreso.push(x.total);
                });
                me.varIngreso=document.getElementById('ingresos').getContext('2d');
                
                const mesesDelAnio = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];

                // Valores de ingreso inicializados en 0 para todos los meses
                const valoresIngreso = Array(12).fill(0);

                // Asigna los valores de ingreso conocidos a sus respectivos meses
                me.varMesIngreso.forEach((mes, index) => {
                        const posicionMes = mes - 1; // Ajusta el índice del mes
                        valoresIngreso[posicionMes] = parseFloat(me.varTotalIngreso[index]); // Asigna el valor del ingreso
                });

                const ctx = document.getElementById("ingresos").getContext("2d");
                const gradient = ctx.createLinearGradient(600, 0, 600, 600);
                gradient.addColorStop(0, 'rgba(255, 193, 7, 0.5)');
                gradient.addColorStop(0.35, 'rgba(255, 193, 7, 0.25)');
                gradient.addColorStop(1, 'rgba(255, 193, 7, 0)');

                me.charIngreso = new Chart(me.varIngreso, {
                    type: 'line',
                    data: {
                        //labels: me.varMesIngreso,
                        labels: mesesDelAnio,
                        datasets: [{
                            label: 'Ingresos',
                            data: valoresIngreso,
                            /*backgroundColor: '#ffc107',
                            fill: false,
                            borderColor: 'rgba(255, 99, 132, 0.2)',
                            borderWidth: 1,
                            tension: 0.1
                            */
                            backgroundColor: gradient, // Color de fondo para el área sombreada
                            borderColor: '#ffc107',
                            borderWidth: 2, // Grosor de la línea
                            pointBackgroundColor: '#ffc107', // Color del punto en la línea
                            pointRadius: 2, // Tamaño del punto en la línea
                            pointHoverRadius: 8, // Tamaño del punto al pasar el ratón
                            fill: true, // Rellenar el área bajo la línea
                            
                            lineTension: 0.4 // Curvatura de la línea
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }
                });
                
                
            },
            loadVentas(){
                /*let me=this;
                me.ventas.map(function(x){
                    me.varMesVenta.push(x.mes);
                    me.varTotalVenta.push(x.total);
                });
                me.varVenta=document.getElementById('ventas').getContext('2d');

                me.charVenta = new Chart(me.varVenta, {
                    type: 'bar',
                    data: {
                        labels: me.varMesVenta,
                        datasets: [{
                            label: 'Ventas',
                            data: me.varTotalVenta,
                            backgroundColor: '#007bff',
                            borderColor: 'rgba(54, 162, 235, 0.2)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }
                });**/


                let me=this;
                me.ventas.map(function(x){
                    me.varMesVenta.push(x.mes);
                    me.varTotalVenta.push(x.total);
                });
                me.varVenta=document.getElementById('ventas').getContext('2d');
                
                const mesesDelAnio = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];

                // Valores de ingreso inicializados en 0 para todos los meses
                const valoresVenta = Array(12).fill(0);

                // Asigna los valores de ingreso conocidos a sus respectivos meses
                me.varMesVenta.forEach((mes, index) => {
                        const posicionMes = mes - 1; // Ajusta el índice del mes
                        valoresVenta[posicionMes] = parseFloat(me.varTotalVenta[index]); // Asigna el valor del ingreso
                });

                const ctx = document.getElementById("ventas").getContext("2d");
                const gradient = ctx.createLinearGradient(600, 0, 600, 600);;
                gradient.addColorStop(0, 'rgba(0, 123,255, 0.5)');
                gradient.addColorStop(0.35, 'rgba(0, 123,255, 0.25)');
                gradient.addColorStop(1, 'rgba(0, 123,255, 0)');

                me.charVenta = new Chart(me.varVenta, {
                    type: 'line',
                    data: {
                        //labels: me.varMesIngreso,
                        labels: mesesDelAnio,
                        datasets: [{
                            label: 'Ventas',
                            data: valoresVenta,
                            /*backgroundColor: '#ffc107',
                            fill: false,
                            borderColor: 'rgba(255, 99, 132, 0.2)',
                            borderWidth: 1,
                            tension: 0.1
                            */
                            backgroundColor: gradient, // Color de fondo para el área sombreada
                            borderColor: '#007bff',
                            borderWidth: 2, // Grosor de la línea
                            pointBackgroundColor: '#007bff', // Color del punto en la línea
                            pointRadius: 2, // Tamaño del punto en la línea
                            pointHoverRadius: 8, // Tamaño del punto al pasar el ratón
                            fill: true, // Rellenar el área bajo la línea
                            
                            lineTension: 0.4 // Curvatura de la línea
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }
                });
            },


            loadIngresosProductos(){
                let me=this;
                me.ingresosProducto.map(function(x){
                    me.varMesIngresoProducto.push(x.mes);
                    me.varTotalIngresoProducto.push(x.total);
                });
                me.varIngresoProducto=document.getElementById("ingresosProducto").getContext("2d");
                
                const mesesDelAnio = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];

                // Valores de ingreso inicializados en 0 para todos los meses
                const valoresIngresoProducto = Array(12).fill(0);

                // Asigna los valores de ingreso conocidos a sus respectivos meses
                me.varMesIngresoProducto.forEach((mes, index) => {
                        const posicionMes = mes - 1; // Ajusta el índice del mes
                        valoresIngresoProducto[posicionMes] = parseFloat(me.varTotalIngresoProducto[index]); // Asigna el valor del ingreso
                });

                const ctx = document.getElementById("ingresosProducto").getContext("2d");
                const gradient = ctx.createLinearGradient(600, 0, 600, 600);
                gradient.addColorStop(0, 'rgba(255, 193, 7, 0.5)');
                gradient.addColorStop(0.35, 'rgba(255, 193, 7, 0.25)');
                gradient.addColorStop(1, 'rgba(255, 193, 7, 0)');

                me.charIngresoProducto = new Chart(me.varIngresoProducto, {
                    type: 'line',
                    data: {
                        //labels: me.varMesIngreso,
                        labels: mesesDelAnio,
                        datasets: [{
                            label: 'Ingresos Producto',
                            data: valoresIngresoProducto,
                            /*backgroundColor: '#ffc107',
                            fill: false,
                            borderColor: 'rgba(255, 99, 132, 0.2)',
                            borderWidth: 1,
                            tension: 0.1
                            */
                            backgroundColor: gradient, // Color de fondo para el área sombreada
                            borderColor: '#ffc107',
                            borderWidth: 2, // Grosor de la línea
                            pointBackgroundColor: '#ffc107', // Color del punto en la línea
                            pointRadius: 2, // Tamaño del punto en la línea
                            pointHoverRadius: 8, // Tamaño del punto al pasar el ratón
                            fill: true, // Rellenar el área bajo la línea
                            
                            lineTension: 0.4 // Curvatura de la línea
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }
                });
                
                
            },

            loadVentasProductos(){
                let me=this;
                me.ventasProducto.map(function(x){
                    me.varMesVentaProducto.push(x.mes);
                    me.varTotalVentaProducto.push(x.total);
                });
                me.varVentaProducto=document.getElementById('ventasProductos').getContext('2d');
                
                const mesesDelAnio = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];

                // Valores de ingreso inicializados en 0 para todos los meses
                const valoresVentaProducto = Array(12).fill(0);

                // Asigna los valores de ingreso conocidos a sus respectivos meses
                me.varMesVentaProducto.forEach((mes, index) => {
                        const posicionMes = mes - 1; // Ajusta el índice del mes
                        valoresVentaProducto[posicionMes] = parseFloat(me.varTotalVentaProducto[index]); // Asigna el valor del ingreso
                });

                const ctx = document.getElementById("ventasProductos").getContext("2d");
                const gradient = ctx.createLinearGradient(600, 0, 600, 600);;
                gradient.addColorStop(0, 'rgba(0, 123,255, 0.5)');
                gradient.addColorStop(0.35, 'rgba(0, 123,255, 0.25)');
                gradient.addColorStop(1, 'rgba(0, 123,255, 0)');

                me.charVentaProducto = new Chart(me.varVentaProducto, {
                    type: 'line',
                    data: {
                        //labels: me.varMesIngreso,
                        labels: mesesDelAnio,
                        datasets: [{
                            label: 'Ventas Productos',
                            data: valoresVentaProducto,
                            /*backgroundColor: '#ffc107',
                            fill: false,
                            borderColor: 'rgba(255, 99, 132, 0.2)',
                            borderWidth: 1,
                            tension: 0.1
                            */
                            backgroundColor: gradient, // Color de fondo para el área sombreada
                            borderColor: '#007bff',
                            borderWidth: 2, // Grosor de la línea
                            pointBackgroundColor: '#007bff', // Color del punto en la línea
                            pointRadius: 2, // Tamaño del punto en la línea
                            pointHoverRadius: 8, // Tamaño del punto al pasar el ratón
                            fill: true, // Rellenar el área bajo la línea
                            
                            lineTension: 0.4 // Curvatura de la línea
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }
                });
            },
            showCardBody(buttonNumber) {
            // Método para cambiar el botón activo
            this.activeButton = buttonNumber;
            if (buttonNumber === 1) {
                this.getIngresos();
                this.getVentas();
            } else if (buttonNumber === 2) {
                this.getIngresosProductos();
                this.getVentasProductos();
            }
            }
        },
        mounted() {
            this.activeButton=1;
            this.getIngresos();
            this.getVentas();
            this.getIngresosProductos();
        }
    }
</script>

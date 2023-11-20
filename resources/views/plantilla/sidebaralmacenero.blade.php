<div class="sidebar">
            <nav class="sidebar-nav">
                <ul class="nav">
                    <li @click="menu=0" class="nav-item">
                        <a class="nav-link active" href="#"><i class="icon-speedometer"></i> Escritorio</a>
                    </li>
                    <li class="nav-title">
                        Mantenimiento
                    </li>
                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-bag"></i> Almacén</a>
                        <ul class="nav-dropdown-items">
                            <li @click="menu=1" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-bag" style="font-size: 11px;"></i> Productos</a>
                            </li>
                            <li @click="menu=2" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-bag" style="font-size: 11px;"></i> Categorias Productos</a>
                            </li>
                            <li @click="menu=3" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-bag" style="font-size: 11px;"></i> Ropa - Vestimenta</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-wallet"></i> Proveedores</a>
                        <ul class="nav-dropdown-items">
                            
                            <li @click="menu=8" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-notebook" style="font-size: 11px;"></i> Registrar Donadores</a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-line-chart"></i> Donar</a>
                        <ul class="nav-dropdown-items">

                            <li @click="menu=11" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Eventos </a>
                            </li>

                            <li @click="menu=10" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Salidas Producto</a>
                            </li>

                            <li @click="menu=12" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Salidas Ropas</a>
                            </li>
                        </ul>
                    </li> 
                    
                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-line-chart"></i> Reportes</a>
                        <ul class="nav-dropdown-items">
                            <li @click="menu=9" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Historial Ropa</a>
                            </li>

                            <li @click="menu=4" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Historial Productos</a>
                            </li>

                            <li @click="menu=13" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Reporte Donacion Productos</a>
                            </li>
                        </ul>
                    </li>
                    
                </ul>
            </nav>
            <button class="sidebar-minimizer brand-minimizer" type="button"></button>
        </div>
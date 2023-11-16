<div class="sidebar">
            <nav class="sidebar-nav">
                <ul class="nav">

                    <li class="nav-title">
                        Operaciones
                    </li>
                    
                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-tags"></i> Productos</a>
                        <ul class="nav-dropdown-items">
                            <li @click="menu=1" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Productos</a>
                            </li>
                            
                            <li @click="menu=2" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Categoria Alimento</a>
                            </li>

                            <li @click="menu=3" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Ropa - Vestimenta</a>
                            </li>

                        </ul>
                    </li>
                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-wallet"></i> Donadores</a>
                        <ul class="nav-dropdown-items">
                            
                            <li @click="menu=8" class="nav-item">
                                <a class="nav-link" href="#"><i class="fas fa-user" style="font-size: 11px;"></i> Registrar donadores</a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item nav-dropdown">
                        <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-lock"></i> Acceso</a>
                        <ul class="nav-dropdown-items">
                            <li @click="menu=5" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Usuarios</a>
                            </li>
                            <li @click="menu=6" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Roles</a>
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

                            <li @click="menu=1000" class="nav-item">
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

                            <li @click="menu=100" class="nav-item">
                                <a class="nav-link" href="#"><i class="icon-list" style="font-size: 11px;"></i> Reporte Ventas</a>
                            </li>
                        </ul>
                    </li>
     
                </ul>
            </nav>
            <button class="sidebar-minimizer brand-minimizer" type="button"></button>
        </div>
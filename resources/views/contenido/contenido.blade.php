    @extends('principal')
    @section('contenido')

    @if(Auth::check())
            @if (Auth::user()->idrol == 1)

            <template v-if="menu==1">
                <articulo></articulo>
            </template>
            <template v-if="menu==2">
                <linea></linea>
            </template>
            <template v-if="menu==3">
                <ropa></ropa>
            </template>
            <template v-if="menu==4">
                <catRopa></catRopa>
            </template>

            
            <template v-if="menu==5">
                <user></user>
            </template>
            
            <template v-if="menu==6">
                <rol></rol>
            </template>


            <template v-if="menu==17">
                <editarperfil></editarperfil>
            </template>



            <template v-if="menu==21">
                <configuracion></configuracion>
            </template>
            


            @elseif (Auth::user()->idrol == 2)
            <template v-if="menu==0">
                <dashboard></dashboard>
            </template>
            
            <template v-if="menu==5">
                <venta></venta>
            </template>

            <template v-if="menu==6">
                <cliente></cliente>
            </template>
            <template v-if="menu==10">
            <consultaventa></consultaventa>
            </template>

            <template v-if="menu==11">
                <h1>Ayuda</h1>
            </template>

            <template v-if="menu==12">
                <h1>Acerca de</h1>
            </template>

            <template v-if="menu==17">
                <editarperfil></editarperfil>
            </template>

            @elseif (Auth::user()->idrol == 3)
            <template v-if="menu==0">
                <dashboard></dashboard>
            </template>
            <template v-if="menu==1">
                <categoria></categoria>
            </template>

            <template v-if="menu==2">
                <articulo></articulo>
            </template>

            <template v-if="menu==3">
                <ingreso ></ingreso>
            </template>

            <template v-if="menu==4">
                <proveedor></proveedor>
            </template>
            <template v-if="menu==9">
            <consultaingreso></consultaingreso>
            </template>
            <template v-if="menu==11">
                <h1>Ayuda</h1>
            </template>

            <template v-if="menu==12">
                <h1>Acerca de</h1>
            </template>

            <template v-if="menu==17">
                <editarperfil></editarperfil>
            </template>


      



            @else

            @endif

    @endif
       
        
    @endsection
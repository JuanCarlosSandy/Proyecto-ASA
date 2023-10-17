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
                <catropa></catropa>
            </template>
            <template v-if="menu==7">
                <tallaropa></tallaropa>
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
                <catropa></catropa>
            </template>
            <template v-if="menu==7">
                <tallaropa></tallaropa>
            </template>
            <template v-if="menu==17">
                <editarperfil></editarperfil>
            </template>




      



            @else

            @endif

    @endif
       
        
    @endsection
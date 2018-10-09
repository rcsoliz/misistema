 @extends('principal')
    @section('contenido') 
       @if(Auth::check())
           @if (Auth::user()->idrol == 1)
                <template v-if="menu==0">
                    <dashboard :ruta='ruta'></dashboard>
                </template>
            
                <template v-if="menu==1">
                    <parametro :ruta="ruta"></parametro>
                </template>
                <template v-if="menu==2">
                    <zona :ruta="ruta"></zona>
                </template>
                <template v-if="menu==3">
                    <clase :ruta="ruta"></clase>
                </template>

                <template v-if="menu==4">
                    <item :ruta="ruta"></item>
                </template>

                <template v-if="menu==5">
                    <ganadero :ruta="ruta"></ganadero>
                </template>
                
                <template v-if="menu==15">
                    <ganaderobaja :ruta="ruta"></ganaderobaja>
                </template>
                <template v-if="menu==16">
                    <estanciabaja :ruta="ruta"></estanciabaja>
                </template>

                <template v-if="menu==6">
                    <estancia :ruta="ruta"></estancia>
                </template>
                <template v-if="menu==16">
                    
                </template>

                <template v-if="menu==7">
                    <user :ruta="ruta"></user>
                </template>

                <template v-if="menu==8">
                    <rol :ruta="ruta"></rol>
                </template>

                <template v-if="menu==9">
                   <visita :ruta="ruta"></visita>
                </template>

                <template v-if="menu==10">
                    <h1>Contenido del menú 10</h1>
                </template>

                <template v-if="menu==11">
                    <h1>Contenido del menú 11</h1>
                </template>

                <template v-if="menu==12">
                    <h1>Contenido del menú 12</h1>
                </template>

                <template v-if="menu==13">
                    <!-- <rpvisita></rpvisita> -->
                    <consultavisita :ruta="ruta"></consultavisita>
                </template>
                <template v-if="menu==14">
                    <rpvisita :ruta="ruta"></rpvisita>
                </template>
            @elseif (Auth::user()->idrol == 2)
                <template v-if="menu==0">
                    <!-- <dashboard></dashboard> -->
                    <h1>Escritorio</h1> 
                </template>
        
                <template v-if="menu==1">
                    <parametro :ruta="ruta"></parametro>
                </template>
                <template v-if="menu==2">
                    <zona :ruta="ruta"></zona>
                </template>
                <template v-if="menu==3">
                    <clase :ruta="ruta"></clase>
                </template>

                <template v-if="menu==4">
                    <item :ruta="ruta"></item>
                </template>

                <template v-if="menu==5">
                <ganadero :ruta="ruta"></ganadero>
                </template>

                <template v-if="menu==6">
                <estancia :ruta="ruta"></estancia>
                </template>

                <template v-if="menu==9">
                <visita :ruta="ruta"></visita>
                </template>

                <template v-if="menu==10">
                    <h1>Contenido del menú 10</h1>
                </template>

                <template v-if="menu==11">
                    <h1>Contenido del menú 11</h1>
                </template>

                <template v-if="menu==12">
                    <h1>Contenido del menú 12</h1>
                </template>
            @elseif (Auth::user()->idrol == 3)
                <template v-if="menu==0">
                    <!-- <dashboard></dashboard> -->
                    <h1>Escritorio</h1> 
                </template>
                <template v-if="menu==1">
                    <parametro :ruta="ruta"></parametro>
                </template>
                <template v-if="menu==2">
                    <zona :ruta="ruta"></zona>
                </template>
                <template v-if="menu==3">
                    <clase :ruta="ruta"></clase>
                </template>

                <template v-if="menu==4">
                    <item :ruta="ruta"></item>
                </template>

                <template v-if="menu==5">
                    <ganadero :ruta="ruta"></ganadero>
                </template>

                <template v-if="menu==6">
                    <estancia :ruta="ruta"></estancia>
                </template>

                <template v-if="menu==9">
                    <visita :ruta="ruta"></visita>
                </template>

                <template v-if="menu==10">
                    <h1>Contenido del menú 10</h1>
                </template>

                <template v-if="menu==11">
                    <h1>Contenido del menú 11</h1>
                </template>

                <template v-if="menu==12">
                    <h1>Contenido del menú 12</h1>
                </template>
                <template v-if="menu==13">
                    <consultavisita :ruta="ruta"></consultavisita>
                </template>
                <template v-if="menu==14">
                    <rpvisita :ruta="ruta"></rpvisita>
                </template>
                @else

            @endif

        @endif
        
    @endsection 

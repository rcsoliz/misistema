    <template>
        <main class="main">
            <!-- Breadcrumb -->
            <!-- <ol class="breadcrumb"> 
                <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
            </ol> -->
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Vistas
                        <button type="button" @click="mostarDetalle()" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>

                    <!-- Listado (1)-->
                    <template v-if="listado==1">
                    <div class="card-body">
                        <div class="form-group row"> <!-- Criterios de Busqueda-->
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                        <option value="usuario">Usuario</option>
                                        <option value="fecha_hora">Fecha-Hora</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarVisita(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarVisita(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="tabla-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Usuario</th>
                                        <th>Unidad Prod.</th>
                                        <th>Glosa</th>
                                        <th>Fecha</th>
                                        <th>Total</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="visita in arrayVisita" :key="visita.id">
                                        <td> 
                                            <button type="button" @click="verVisita(visita.id)" class="btn btn-success btn-sm">
                                                <i class="icon-eye"></i>
                                            </button> &nbsp;
                                            <button type="button" @click="pdfVisita(visita.id)" class="btn btn-info btn-sm">
                                                <i class="icon-doc"></i>
                                            </button> &nbsp;
                                            <template v-if="visita.condicion">
                                                <button type="button" class="btn btn-danger btn-sm" @click="desactivarVisita(visita.id)">
                                                    <i class="icon-trash"></i> <!-- icono de basura-->
                                                </button> 
                                            </template>
                                        </td>
                                        <!-- <td v-text="visita.id"></td> -->
                                        <td v-text="visita.usuario"></td>
                                        <td v-text="visita.estancia"></td>
                                        <td v-text="visita.glosa"></td>
                                        <td v-text="visita.fecha_hora"></td>
                                        <td v-text="visita.total"></td>
                                        <td>
                                            <div v-if="visita.condicion">
                                            <span class="badge badge-success">Activo</span>
                                            </div>
                                            <div v-else>
                                            <span class="badge badge-danger">Desactivado</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <nav>
                            <ul class="pagination"> <!-- Paginacion de Items -->
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1, buscar, criterio)">Ant</a>
                                </li>
                           
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page, buscar, criterio)" v-text="page"></a>
                                </li>
                                
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1, buscar, criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    </template>
                    <!-- Fin del Listado--> 
                    <!-- Detalle (2)-->
                    <template v-else-if="listado==0">
                    <div class="card-body"> 
                        <div class="form-group row border"> 
                            <div class="col-md-9"> 
                               <div class="form-group">
                                    
                                    <label for="">Estancia(*)</label>
                                    <v-select
                                        :on-search="selectEstancia"
                                        label="nombre"
                                        :options="arrayEstancia"
                                        placeholder="Buscar Estancias..."
                                        :onChange="getDatosGanadero"
                                    >

                                    </v-select>   
                               </div>
                            </div>
                            <!-- <div class="col-md-3"> 
                                <label for=""> Nro Documento(*) </label>
                                <input type="text" class="form-control" v-model="num_documento">
                            </div> -->
                            <!-- nuevo del 03/09/2018 -->
                            <div class="col-md-12">
                                <div v-show="errorVisita" class="form-group row div-error"> 
                                    <div class="text-center text-error">
                                       <div v-for="error in errorMostrarMsVisita" :key="error" v-text="error"> 
                                       </div>    
                                    </div>
                                </div>
                            </div>
                            <!-- end neonuevo del 03/09/2018 -->
                             <!-- <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo Documento(*)</label>
                                        <select class="form-control" v-model="tipo_documento">
                                            <option value="0">Seleccione</option>
                                            <option value="Boleta">Boleta</option>
                                            <option value="Ticket">Ticket</option>
                                        </select>
                                </div> 
                            </div> -->
                            
                            <div class="col-md-9"> 
                                <div class="form-group">
                                    <label for=""> Ganadero </label>
                                    <input type="text" class="form-control" v-model="ganadero" placeholder="000x">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row border"> 
                            <div class="col-md-6">
                               <div class="form-group">
                                    <label>Items <span style="color:red" v-show="iditem==0">(*Seleccione)</span> </label>
                                    <div class="form-inline"> 
                                        <input type="text" class="form-control" v-model="codigo" @keyup.enter="buscarItem()" placeholder="Ingrese un items">
                                        <button @click="abrirModal()" class="btn btn-primary">...</button>
                                        <input type="text" readonly class="form-control" v-model="item">
                                    </div>
                               </div>
                            </div>
                        </div>
                        <!--disenio del detalle-->
                        <div class="form-group row border"> 
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <th> Opciones</th> 
                                        <th> Items</th>
                                        <th> Cantidad Potencial</th>
                                        <th> Cantidad/Venta</th>
                                        <th> C.C.</th>
                                        <th> Peso</th>
                                        <th> Fecha de salida</th>
                                        <th> Observación</th>
                                        <th> Alimentación</th>
                                    </thead>

                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="(detalle, index) in arrayDetalle" :key="detalle.id" >
                                            <td> 
                                                <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm"> 
                                                    <i class="icon-close"></i>
                                                </button>
                                            </td>
                                            <td v-text="detalle.item"> 
                                            </td>
                                            <td> 
                                                <input v-model="detalle.cantparcial" type="number" value="3" class="form-control">
                                            </td>
                                            <td> 
                                                <input v-model="detalle.cantventa" type="number" value="2" class="form-control">
                                            </td>
                                            <td> 
                                                <select v-model="detalle.categoria" class="form-control">
                                                    <option value="0" disabled>Selecione</option>
                                                    <option v-for="cond in arraySelectCondicionCorporal" :key="cond.id" :value="cond.id" v-text="cond.nombre"></option>
                                                </select> 
                                            </td>
                                            <td> 
                                                <input v-model="detalle.pstandar" type="number" value="210" class="form-control">   
                                            </td>
                                            <td> 
                                                <!-- <input v-model="detalle.timpsalida"  type="number" value="30" class="form-control">     -->
                                                <select v-model="detalle.timpsalida" class="form-control">
                                                    <option value="0" disabled>Seleccione</option>
                                                    <option v-for="tipo in arrayRangoDeFecha" :key="tipo.id" :value="tipo.id" v-text="tipo.nombre"></option>
                                                </select> 
                                            </td>
                                            <td> 
                                                <input v-model="detalle.observacion" type="text" value="observacion" class="form-control">  
                                            </td>
                                            <td> 
                                                <!-- <input v-model="detalle.alimentacion" type="text" value="alimentacion" class="form-control">  -->
                                                <select v-model="detalle.alimentacion" class="form-control">
                                                    <option value="0" disabled>Seleccion</option>
                                                    <option v-for="tipo in arrayTipoAlimentacion" :key="tipo.id" :value="tipo.id" v-text="tipo.nombre"></option>
                                                </select>    
                                            </td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="8" align="right"><strong>Total Neto:</strong></td>
                                            <td> {{total=calcularTotal}} </td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>   
                                            <td colspan="9">
                                                No hay items agregados....  
                                            </td>      
                                        </tr> 
                                    </tbody> 
                                </table>
                            </div>
                        </div>

                        <div class="form-group row"> 
                            <div class="col-md-12"> 
	                            <button type="button" @click="ocultarDetalle()" class="btn btn-secondary" >Cerrar</button>
							    <button type="button" class="btn btn-primary" @click="registrarVisita()">Registrar</button>
                            </div>
                        </div>
                    </div>
                    </template>
                    <!-- Fin del Detalle-->
                    <!-- Ver Visita -->
                    <template v-else-if="listado==2">
                    <div class="card-body"> 
                        <div class="form-group row border"> 
                            <div class="col-md-9"> 
                               <div class="form-group">
                                    <label for="">Estancia</label>  
                                    <p v-text="estancia"></p>
                               </div>
                            </div>
                            <!-- <div class="col-md-3"> 
                                <label for=""> Nro Documento</label>
                                 <p v-text="num_documento"></p>
                            </div> -->
                            <!-- <div class="col-md-4"> 
                                <div class="form-group">
                                    <label>Tipo Documento(*)</label>
                                    <p v-text="tipo_documento"></p>
                                </div> 
                            </div>-->
                            <div class="col-md-8"> 
                                <label for=""> Ganadero </label>
                                <p v-text="ganadero"></p>
                            </div>
                        </div>
                        <!--disenio del detalle-->
                        <div class="form-group row border"> 
                            <div class="table-responsive col-md-12"> 
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>    
                                        <th> Items</th>
                                        <th> Cantidad Potencial</th>
                                        <th> Cantidad/Venta</th>
                                        <th> C.C.</th>
                                        <th> Peso</th>
                                        <th> Fecha de salida</th>
                                        <th> Observación</th>
                                        <th> Alimentación</th>
                                    </thead>

                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="detalle in arrayDetalle" :key="detalle.id" >
                                            <td v-text="detalle.item"> 
                                            </td>
                                            <td v-text="detalle.cantparcial"> 
                                            </td>
                                            <td v-text="detalle.cantventa"> 
                                            </td>
                                            <td v-text="detalle.categoria"> 
                                            </td>
                                            <td v-text="detalle.pstandar">  
                                            </td>
                                            <td v-text="detalle.timpsalida"> 
                                            </td>
                                            <td v-text="detalle.observacion"> 
                                            </td>
                                            <td v-text="detalle.alimentacion"> 
                                            </td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="8" align="right"><strong>Total Neto:</strong></td>
                                            <td> {{total}} </td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>   
                                            <td colspan="9">
                                                No hay items agregados....  
                                            </td>      
                                        </tr> 
                                    </tbody> 
                                </table>
                            </div>
                        </div>
                        <div class="form-group row"> 
                            <div class="col-md-12"> 
	                            <button type="button" @click="ocultarDetalle()" class="btn btn-secondary" >Cerrar</button>
                            </div>
                        </div>
                    </div>
                    </template>
                    <!-- fin Ver Visita -->
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <!--la directiva bit :class={} anexa un resultado al atributo class de html y diremos que la clase mostrar si la variable mostrar es verdadera -->   
            <div class="modal fade" tabindex="-1" :class="{'mostrar': modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <!--Ventana Modal -->
                        <div class="modal-body"> 
                            <!-- //Criterios de Busqueda -->
                            <div class="form-group row">   
                                <div class="col-md-6">
                                    <div class="input-group">                                
                                        <select class="form-control col-md-3" v-model="criterioI">
                                            <option value="nombre">Nombre</option>
                                            <option value="clase">Clase</option>
                                            <option value="id">Codigo</option>
                                        </select>
                                        <input type="text" v-model="buscarI" @keyup.enter="listarItem(buscarI,criterioI)" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listarItem(buscarI,criterioI)" class="btn btn-primary"><i class="fa fa-search"></i> Buscarss</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Nombre</th>
                                            <th>Clase</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody> 
                                        <tr v-for="item in arrayItem" :key="item.id">
                                            <td>
                                                <button type="button" @click="agregarModalDetalleModal(item)" 
                                                    class="btn btn-success btn-sm">
                                                    <i class="icon-check"></i>
                                                </button>
                                            </td>
                                            <td v-text="item.nombre"></td>
                                            <td v-text="item.clase"></td>
                                            <td>
                                                <div v-if="item.condicion">
                                                <span class="badge badge-success">Activo</span>
                                                </div>
                                                <div v-else>
                                                <span class="badge badge-danger">Desactivado</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div> 
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()" >Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarUsuario()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarUsuario()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
            <!-- Inicio del modal Eliminar -->
              
            <!-- Fin del modal Eliminar -->
            <!-- <example-component></example-component> -->
        </main>   
    </template>

    <script>
        import vSelect from 'vue-select';
        //importe componentes en este caso vue-select
        export default {
            props : ['ruta'],
            //propiedad data id, idestancia, idusuario, glosa, fecha_hora, condicion, total
            data (){
                return {
                    visita_id: 0,
                    estancia: '',
                    idestancia : 0,
                    num_documento:0,
                    tipo_documento : 'Boleta',
                    glosa : '',
                    ganadero : '',
                    total : '0.00',
                      
                    arraySelectItem: [],
                    arrayVisita: [],
                    arrayEstancia: [],

                    arrayDetalle: [], 
                    arrayTipoAlimentacion: [], 
                    arraySelectCondicionCorporal:[],
                    arrayRangoDeFecha : [],
                     
                    listado : 1,
                    //ganadero :'',

                    modal : 0,
                    tituloModal : '',
                    tipoAccion : 0, 
                    
                    // atributo para errores
                    errorVisita : 0,
                    errorMostrarMsVisita : [],
                    
                    // atributo para paginacion
                    pagination : {
                        'total' : 0,
                        'current_page' : 0,
                        'per_page'     : 0,
                        'last_page'   : 0,
                        'from'         : 0,
                        'to' : 0
                    } ,
                    offset : 3,
                    
                    //atributos de busqueda
                    criterio : 'glosa',
                    buscar : '',
                    buscarI: '',
                    criterioI:'',
    
                    arrayItem: [],
                    iditem :0,
                    codigo:'',
                    item :'',
                    cantparcial :0,
                    cantventa : 0,
                    categoria : '',  // cc:'',
                    pstandar : '',   // ps:'',
                    timpsalida : '', // dias: '',
                    observacion :'',
                    alimentacion:''

                }
            },
            components: {
                vSelect
            },

            // propiedad computada computed
            computed:{
                isActived: function(){
                    return this.pagination.current_page;
                },
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
                },

                calcularTotal: function(){
                    var resultado=0.0;
                    for(var i=0;i<this.arrayDetalle.length;i++){
                        resultado=resultado + (this.arrayDetalle[i].cantparcial*1)
                    }
                    return resultado;
                }
            },
            //metodos ...
            methods : {
                listarVista (page, buscar, criterio){
                    let me=this;
                    var url =this.ruta + '/visita?page=' + page + '&buscar='+ buscar + '&criterio=' + criterio;
                    axios.get(url).then(function (response) {
                        var respuesta = response.data;
                        me.arrayVisita = respuesta.visitas.data;
                        me.pagination = respuesta.pagination;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                },

                selectEstancia(search,loading){ 
                    let me=this;
                    loading(true)

                    var url= this.ruta + '/estancia/selectEstancia?filtro='+search;
                    axios.get(url).then(function (response) {
                        var respuesta = response.data;
                        q: search
                        me.arrayEstancia=respuesta.estancias;
                        loading(false)
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                }, 
                getDatosGanadero(val1){
                    let me = this;
                    me.loading = true;
                    me.idestancia =val1.id;

                    me.ganadero = val1.ganadero; //val.ganadero;
                },
                buscarItem(){
                    let me=this;
                    var url=this.ruta + '/item/buscarItem?filtro=' + me.codigo;

                    axios.get(url).then(function (response) {
                        var respuesta= response.data;
                        me.arrayItem = respuesta.items;

                        if (me.arrayItem.length>0){ 
                            me.item=me.arrayItem[0]['nombre'];
                            me.iditem=me.arrayItem[0]['id'];
                        }
                        else{
                            me.item='No existe artículo';
                            me.iditem=0;
                        }
                        })
                    .catch(function (error) {
                        console.log(error);
                    });
                }, 
                pdfVisita(id){
                    //window.open('http://127.0.0.1:8000/visita/pdf/'+ id +',' + '_blank');   
                    window.open(this.ruta + '/visita/pdf/'+ id +',' + '_blank');
                }, 
                
                cambiarPagina(page, buscar, criterio){
                    let me = this;
                    //actualiza la pagina actual
                    me.pagination.current_page = page;
                    me.listarVista(page, buscar, criterio);
                },
                encuentra(id){
                    var sw=0;
                    for(var i=0; i<this.arrayDetalle.length;i++){
                        if(this.arrayDetalle[i].iditem==id){
                            sw = true;
                        }
                    }
                    return sw;
                },
                agregarDetalle(){
                    let me=this;
                    if(me.iditem==0 || me.observacion==''){
                    }
                    else
                    {
                        if(me.encuentra(me.iditem)){
                            swal({
                                type:'Errror',
                                title:'Error...',
                                text: 'Ese items ya se encuentra agregado',   
                            })
                        }
                        else{
                            me.arrayDetalle.push({ 
                            iditem: me.iditem,
                            item: me.item,
                            cantparcial : me.cantparcial,
                            cantventa: me.cantventa,
                            categoria : me.categoria, //cc : me.categoria,
                            pstandar: me.pstandar, // ps: me.pstandar,
                            timpsalida: me.timpsalida, // dias: me.timpsalida,
                            observacion: me.observacion,
                            alimentacion: me.alimentacion 
                            });
                    
                            me.iditem ='0';
                            me.codigo='';
                            me.item ='';
                            me.cantparcial ='';
                            me.cantventa ='';
                            me.categoria ='';  // me.cc ='';
                            me.pstandar = '' ; // me.ps ='';
                            me.timpsalida =''; // me.dias ='0';
                            me.observacion='';
                            me.alimentacion='';
                        }
                    }
                }, 
                agregarModalDetalleModal(data =[]){
                    let me = this;
                    if(me.encuentra(data['id'])){
                        swal({
                            type:'Errror',
                            title:'Error...',
                            text: 'Ese items ya se encuentra agregado',   
                        })
                    }
                    else{
                        me.arrayDetalle.push({ 
                        iditem: data['id'],
                        item: data['nombre'],
                        cantparcial : '0',
                        cantventa: '0',
                        categoria: '0',  // cc :  '0',  // 5,
                        pstandar: '0',   // ps:   '0',  // 0
                        timpsalida: '0', // dias: '0',  // 30,
                        observacion: 'nn',
                        alimentacion: '0'
                        });
                    }
                },
                listarItem(buscar, criterio){
                    let me=this;
                    var url =this.ruta + '/item/listarItem?buscar='+ buscar + '&criterio=' + criterio;
                    axios.get(url).then(function (response) {
                        var respuesta = response.data;
                        me.arrayItem = respuesta.items.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                },
               
                selectItem(){
                    let me=this;
                    var url= this.ruta + '/item/selectItem';
                    axios.get(url).then(function (response) {
                        var respuesta= response.data;
                        me.arraySelectItem = respuesta.items;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                }, 

                selectCondicionCorporal(){
                    let me=this;
                    var url= this.ruta + '/parametro/selectCondicionCorporal';
                    axios.get(url).then(function (response) {
                        var respuesta= response.data;
                        me.arraySelectCondicionCorporal = respuesta.parametros;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                }, 
                selectTipoAlimentacion(){
                    let me=this;
                    var url=this.ruta +  '/parametro/selectTipoAlimentacion';
                    axios.get(url).then(function (response) {
                        var respuesta= response.data;
                        me.arrayTipoAlimentacion = respuesta.parametros;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                }, 
                selectRangoFecha(){
                    let me=this;
                    var url=this.ruta +  '/parametro/selectRangoFecha';
                    axios.get(url).then(function (response) {
                        var respuesta= response.data;
                        me.arrayRangoDeFecha = respuesta.parametros;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                }, 

                eliminarDetalle(index){
                    let me = this;
                    me.arrayDetalle.splice(index, 1); 
                },

                registrarVisita (){
                    if(this.validarVisita()){
                        return ;    
                    }

                    let me = this;
                    
                    axios.post(this.ruta + '/visita/registrar',{
                        'idestancia': this.idestancia,
                        'tipo_documento' : this.tipo_documento,
                        'glosa' : '',
                        'total' : this.total,
                        'data' : this.arrayDetalle
                    }).then(function (response) {
                        me.listado=1;
                        me.listarVista(1, '', 'tipo_documento');  

                        me.idestancia =0;
                        me.num_documento = 0;
                        me.tipo_documento = 'Boleta';
                        me.ganadero ='';
                        me.total = 0.0;

                        me.iditem =0;
                        me.cantparcial =0;
                        me.cantventa =0;
                        me.categoria ='';
                        me.pstandar ='';
                        me.timpsalida ='';
                        me.observacion ='';
                        me.alimentacion = '';
                        me.arrayDetalle =[];
                         
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                }, 
                /*................... corregir este metodo ................... */
                desactivarVisita(idvist){
                    swal({
                    title: 'Esta seguro de desactivar esta Visita?',
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

                    axios.put(this.ruta + '/visita/desactivar', {
                        'id': idvist
                    }).then(function (response){
                        //me.listarVisita('', 'usuario');
                        swal(
                        'Desactivado!',
                        'La visita ha sido anulada con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                    } else if (// Read more about handling dismissals
                        result.dismiss === swal.DismissReason.cancel
                    ) {
                        
                    }
                    }) 
                },
                validarVisita(){
                    this.errorVisita = 0 ;    
                    this.errorMostrarMsVisita =[];
                   
                    if (this.idestancia==0) this.errorMostrarMsVisita.push("Seleccione una estancia");
                    if (this.tipo_documento==0) this.errorMostrarMsVisita.push("Seleccione el tipo de documento");
                    if (this.arrayDetalle.length<=0) this.errorMostrarMsVisita.push("Ingrese detalles");

                    if(this.errorMostrarMsVisita.length) this.errorVisita=1;
                    
                    return this.errorVisita;
                },

                mostarDetalle(){
                    let me= this;
                    
                    this.listado =0;
                    
                    me.idestancia =0;
                    me.num_documento = 0;
                    me.tipo_documento = 'Boleta';
                    me.ganadero ='';
                    me.total = 0.0;

                    me.iditem =0;
                    me.cantparcial =0;
                    me.cantventa =0;
                    me.categoria ='';
                    me.pstandar ='';
                    me.timpsalida ='';
                    me.observacion ='';
                    me.alimentacion = '';
                    me.arrayDetalle =[];
                }, 
                ocultarDetalle(){
                    this.listado =1;
                },
                verVisita(id){
                    let me = this;
                    me.listado=2 ;
                    
                    // Obtener los datos de la visita
                    var arrayVisitaT=[];
                    var url = this.ruta +  '/visita/obtenerCabecera?id=' + id;
               
                    //arrayVisita=[]; 
                    axios.get(url).then(function (response) {
                        var respuesta=response.data;
                        arrayVisitaT=respuesta.visitaneo; //me.arrayPrueba 
  
                        me.estancia=arrayVisitaT[0]["estancia"];
                        me.num_documento=arrayVisitaT[0]['num_documento'];
                        me.tipo_documento=arrayVisitaT[0]['tipo_documento'];
                        me.ganadero=arrayVisitaT[0]['ganadero'];
                        me.total=arrayVisitaT[0]['total'];
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                    
                    // Obtener los datos del detalle
                    var urld = this.ruta + '/visita/obtenerDetalles?id=' + id;

                    axios.get(urld).then(function (response) {
                        console.log(response);
                        var respuesta = response.data;
                        me.arrayDetalle= respuesta.detallesneo;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                },    

                cerrarModal (){
                    this.modal=0;
                    this.tituloModal='';
                },
                abrirModal (){
                    //this.listarItem(buscarI,criterioI);
                    this.listarItem('','');

                    this.selectTipoAlimentacion();
                    this.selectItem();
                    this.selectCondicionCorporal();

                    this.selectRangoFecha();
 
                    //this.listarItem();

                    this.arrayItem=[];
                    this.modal = 1;
                    this.tituloModal = 'Seleccione los Tipos de Ganado';  
                   
                }

            },
            mounted() {
                this.listarVista(1, this.buscar, this.criterio); // this.listarItem(buscarI,criterioI);
            }
        }
    </script>
    <style>
        .modal-content{
            width : 100% !important;
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
        @media (min-width: 600px){
       .btnagregar {
           margin-top: 2rem;
       }   
   }
    </style>
    

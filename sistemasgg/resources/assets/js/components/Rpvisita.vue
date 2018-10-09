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
                        <i class="fa fa-align-justify"></i> Vistas
                    </div>

                    <!-- Listado (1)-->
                    <template v-if="listado==1">
                    <div class="card-body">
                        <div class="row"> 
                            <div class='col-sm-3'>
                                <div class="form-group">
                                    <div class='input-group date' id='datetimepicker1'> 
                                        <input type='text' class="form-control" />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>  
                                    </div>
                                </div>
                            </div> 
                        </div>   
                        <!-- <div class="form-group row"> --> <!--Criterios de Busqueda -->
                            <!-- <div class="col-md-8"> -->
                                <!-- <div class="input-group"> -->
                                    <!-- <select class="form-control col-md-3" v-model="criterio">
                                        <option value="usuario">Usuario</option>
                                        <option value="fecha_hora">Fecha-Hora</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarVisita(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarVisita(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> 
                                        Buscar</button> -->
                                    <!-- <label type="text">Desde: </label>
                                    <input type="date" class="form-control col-md-3" v-model="finicio" > 
                                    <label for="text-input">Al:</label>
                                    <input type="date" class="form-control col-md-3" v-model="ffin" >
                                    <button type="submit" @click="listarPorRangoDeFechas(1, buscar, criterio)" class="btn btn-primary">
                                    <i class="fa fa-search"></i> Buscar </button>  -->
                                <!-- </div> -->
                            <!-- </div> -->
                        <!-- </div> -->
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
                            <div class="col-md-3"> 
                                <label for=""> Nro Documento</label>
                                 <p v-text="num_documento"></p>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo Documento(*)</label>
                                    <p v-text="tipo_documento"></p>
                                </div> 
                            </div>
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
                                        <th> Cantidad</th>
                                        <th> C.P/Venta</th>
                                        <th> C.C.</th>
                                        <th> P.Standar</th>
                                        <th> Dias</th>
                                        <th> Observacion</th>
                                        <th> T. Alim.</th>
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
                    finicio : '',
                    ffin : '',
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
                    //var url =this.ruta + '/visita?page=' + page + '&buscar='+ buscar + '&criterio=' + criterio;
                    var url ='/visita?page=' + page + '&buscar='+ buscar + '&criterio=' + criterio;
                    axios.get(url).then(function (response) {
                        var respuesta = response.data;
                        me.arrayVisita = respuesta.visitas.data;
                        me.pagination = respuesta.pagination;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                },
                listarVistaPorFechas (page, buscar, criterio){
                    let me=this;
                    //var url =this.ruta + '/visita?page=' + page + '&buscar='+ buscar + '&criterio=' + criterio;
                    var url ='/visita/listarVisitasPorFechas?page=' + page + '&buscar='+ buscar + '&criterio=' + criterio;
                    axios.get(url).then(function (response) {
                        var respuesta = response.data;
                        me.arrayVisita = respuesta.visitas.data;
                        me.pagination = respuesta.pagination;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                },
               /*validarFechas(fechaInicial, fechaFinal){

                },*/
                cambiarPagina(page, buscar, criterio){
                    let me = this;
                    //actualiza la pagina actual
                    me.pagination.current_page = page;
                    me.listarVista(page, buscar, criterio);
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
                    var url =this.ruta + '/visita/obtenerCabecera?id=' + id;
               
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
                    var urld =this.ruta + '/visita/obtenerDetalles?id=' + id;

                    axios.get(urld).then(function (response) {
                        console.log(response);
                        var respuesta = response.data;
                        me.arrayDetalle= respuesta.detallesneo;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                },
                pdfVisita(id){
                    //window.open('http://127.0.0.1:8000/visita/pdf/'+ id +',' + '_blank');   
                    window.open(this.ruta + '/visita/pdf/'+ id +',' + '_blank');  
                },  

            },
            mounted() {
                this.listarVista(1, this.buscar, this.criterio);              
                
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
    
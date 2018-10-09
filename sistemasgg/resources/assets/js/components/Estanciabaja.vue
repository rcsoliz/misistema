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
                        <i class="fa fa-align-justify"></i> Estancia
                        <button type="button" @click="cargarPdf()" class="btn btn-info">
                            <i class="icon-doc"></i>&nbsp;Reporte
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row"> <!-- Criterios de Busqueda-->
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                        <option value="nombre">Nombre</option>
                                        <option value="ubicacion">ubicacion</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarEstancia(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarEstancia(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Ganadero</th>
                                    <th>ubicación</th>
                                    <!-- <th>Dimensión</th> 
                                    <th>Teléfono</th>
                                    <th>Nit</th>-->
                                    <th>Usuario</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="estancia in arrayEstancia" :key="estancia.id">
                                    <td>
                                        <button type="button" class="btn btn-info btn-sm" @click="activarEstancia(estancia.id)">
                                            <i class="icon-check"></i> <!-- icono de ok-->
                                        </button> 
                                    </td>
                                    <td v-text="estancia.nombre"></td>
                                    <td v-text="estancia.ganadero"></td>
                                    <td v-text="estancia.ubicacion"></td>
                                    <!-- <td v-text="estancia.tamanio"></td>
                                    <td v-text="estancia.distancia"></td>
                                    <td v-text="estancia.nit"></td>  -->
                                    <td v-text="estancia.usuario"></td>
                                </tr>
                            </tbody>
                        </table>
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
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
        </main>   
    </template>

    <script>
        export default {
            props : ['ruta'],
            data (){
                return {
                    estancia_id: 0,
                    nombre : '',
                    ubicacion : '',
                    tamanio : '',
                    distancia : '',
                    razonsocial : '',
                    nit : '',
                    rua: '',
                    observacion:'',
                    idtproveedor: '',
                    idzona : '',
                    idganadero: '',
                    iduser: '',
                    cantM: '',
                    cantH: '',

                    cantidadh:'',
                    cantidadm:'',

                    arrayEstancia : [],
                    arrayProveedor : [],
                    arrayZona : [],
                    arrayGanadero : [],
                    modal : 0,
                    tituloModal : '',
                    tipoAccion : 0, 
                    // atributo para errores
                    errorEstancia : 0,
                    errorMostrarMsEstancia : [],
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
                    criterio : 'nombre',
                    buscar : ''
                }
            },
            // propiedad computada computed
            computed:{
                isActived: function(){
                    return this.pagination.current_page;
                },
                pagesNumber: function() 
                {
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
            //metodos ...
            methods : {
                listarEstancia (page, buscar, criterio){
                    let me=this;
                    var url =this.ruta +  '/estancia/indexbaja?page=' + page + '&buscar='+ buscar + '&criterio=' + criterio;
                    axios.get(url).then(function (response) {
                        var respuesta = response.data;
                        me.arrayEstancia = respuesta.estancias.data;
                        me.pagination = respuesta.pagination;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                },
                cargarPdf(){ 
                    //window.open('http://127.0.0.1:8000/estancia/listarEstanciaPdf','_blank');   
                    window.open(this.ruta + '/estancia/listarEstanciaBajaPdf','_blank');
                },
                cambiarPagina(page, buscar, criterio){
                    let me = this;
                    //actualiza la pagina actual
                    me.pagination.current_page = page;
                    me.listarEstancia(page, buscar, criterio);
                },
                desactivarEstancia(id){
                    swal({
                    title: 'Esta seguro de desactivar esta unidad productiva?',
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

                    axios.put(this.ruta + '/estancia/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarEstancia(1, '', 'nombre');
                        swal(
                        'Desactivado!',
                        'El registro ha sido desactivado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                    } else if (
                        // Read more about handling dismissals
                        result.dismiss === swal.DismissReason.cancel
                    ) {
                        
                    }
                    }) 
                },
                activarEstancia (id){
                    swal({
                    title: 'Esta seguro de activar esta unidad productiva?',
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

                    axios.put(this.ruta + '/estancia/activar',{
                        'id': id
                    }).then(function (response) {
                        me.listarEstancia(1, '', 'nombre');
                        swal(
                        'Activado!',
                        'El registro ha sido activado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                    } else if (
                        // Read more about handling dismissals
                        result.dismiss === swal.DismissReason.cancel
                    ) {
                        
                    }
                    }) 
                },
                validarEstancia (){
                    this.errorEstancia = 0 ;    
                    this.errorMostrarMsEstancia =[];

                    if (!this.nombre) this.errorMostrarMsEstancia.push("El nombre de la estancia no puede estar vacío.");
                    if (!this.idganadero) this.errorMostrarMsEstancia.push("El nombre del ganadero no la estancia no puede estar vacío.");
                    if(this.errorMostrarMsEstancia.length) this.errorEstancia =1;
                    
                    return this.errorEstancia;
                },
                cerrarModal (){
                    this.modal=0;
                    this.tituloModal='';
                    this.nombre='';
                    this.ubicacion='';
                    this.tamanio='';
                    this.telefono='';
                    this.nit='';
                    this.rua='';
                    this.razonsocial='';
                    this.observacion='';
                    this.idtproveedor='';
                    this.idganadero=''; 
                    this.idzona='';
                    this.iduser='';
                    this.cantidadh='',
                    this.cantidadm='',
                    this.errorEstancia=0;
                },
                abrirModal (modelo, accion, data = []){
                    switch(modelo){
                        case  "estancia":
                        {
                            switch(accion){
                                case 'registrar':
                                {
                                    this.modal = 1;
                                    this.tituloModal = 'Registrar Unidad Producctiva';
                                    this.nombre= '';
                                    this.ubicacion='';
                                    this.tamanio='';
                                    this.distancia='';
                                    this.nit = '';
                                    this.rua='';
                                    this.razonsocial='';
                                    this.observacion='';
                                    this.idtproveedor ='';
                                    this.idzona = '';
                                    this.idganadero = '';
                                    this.iduser='',
                                    this.cantidadh ='',
                                    this.cantidadm ='',
                                    this.tipoAccion = 1;
                                    break;
                                }
                                case 'actualizar':
                                {
                                    this.modal=1;
                                    this.tituloModal='Actualizar Unidad Productiva';
                                    this.tipoAccion=2;
                                    this.estancia_id=data['id'];
                                    this.nombre = data['nombre'];
                                    this.ubicacion = data['ubicacion'];
                                    this.tamanio = data['tamanio'];
                                    this.distancia = data['distancia'];
                                    this.nit = data['nit'];
                                    this.rua = data['rua'];
                                    this.razonsocial = data['razonsocial'];
                                    this.observacion=data['observacion'];
                                    this.idtproveedor=data['idtproveedor'];
                                    this.idganadero = data['idganadero'];
                                    this.idzona= data['idzona'];
                                    this.cantidadh= data['cantidadh'];
                                    this.cantidadm= data['cantidadm'];
                                    this.iduser='1';
                                    break;
                                }
                            }      
                        }
                    }   
                }
            },
            mounted() {
                this.listarEstancia(1, this.buscar, this.criterio);
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
    </style>
    
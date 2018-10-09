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
                        <i class="fa fa-align-justify"></i> Ganaderos
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
                                        <option value="apellido">Apellidos</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarGanadero(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarGanadero(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Apellidos</th>
                                    <th>Dirección</th>
                                    <th>Teléfono</th>
                                    <th>Celular</th>
                                    <th>Usuario</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="ganadero in arrayGanadero" :key="ganadero.id">
                                    <td>
                                        <template v-if="ganadero.condicion">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarGanadero(ganadero.id)">
                                                <i class="icon-trash"></i> <!-- icono de basura-->
                                            </button> 
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarGanadero(ganadero.id)">
                                                <i class="icon-check"></i> <!-- icono de ok-->
                                            </button> 
                                        </template>
                                    </td>
                                    <td v-text="ganadero.nombre"></td>
                                    <td v-text="ganadero.apellido"></td>
                                    <td v-text="ganadero.direccion"></td>
                                    <td v-text="ganadero.telefeno"></td>
                                    <td v-text="ganadero.celular"></td>
                                    <td v-text="ganadero.usuario"></td>
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
                    ganadero_id: 0,
                    codigo : '',
                    nombre : '',
                    apellido : '',
                    direccion : '',
                    telefeno : '',
                    celular : '',
                    email : '',
                    razonsocial: '',
                    confactura:'',
                    iduser: '',
                    arrayGanadero : [],
                    //arrayRol : [],
                    modal : 0,
                    tituloModal : '',
                    tipoAccion : 0, 
                    // atributo para errores
                    errorGanadero : 0,
                    errorMostrarMsGanadero : [],
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
                listarGanadero (page, buscar, criterio){
                    let me=this;
                    var url = this.ruta + '/ganaderobaja/indexbaja?page=' + page + '&buscar='+ buscar + '&criterio=' + criterio;
                    axios.get(url).then(function (response) {
                        var respuesta = response.data;
                        me.arrayGanadero = respuesta.ganaderos.data;
                        me.pagination = respuesta.pagination;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                },
                cargarPdf(){ 
                    //window.open('http://127.0.0.1:8000/ganadero/listarGanaderoPdf','_blank');   
                    window.open(this.ruta + '/ganadero/listarGanaderoBajaPdf','_blank');   
                },
                cambiarPagina(page, buscar, criterio){
                    let me = this;
                    //actualiza la pagina actual
                    me.pagination.current_page = page;
                    me.listarGanadero(page, buscar, criterio);
                },
                desactivarGanadero(id){
                    swal({
                    title: 'Esta seguro de desactivar este ganadero?',
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

                    axios.put(this.ruta + '/ganadero/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarGanadero(1, '', 'nombre');
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
                activarGanadero (id){
                    swal({
                    title: 'Esta seguro de activar este ganadero?',
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

                    axios.put(this.ruta + '/ganadero/activar',{
                        'id': id
                    }).then(function (response) {
                        me.listarGanadero(1, '', 'nombre');
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
                validarGanadero (){
                    this.errorGanadero = 0 ;    
                    this.errorMostrarMsGanadero =[];

                    if (!this.nombre) this.errorMostrarMsGanadero.push("El nombre del ganadero no puede estar vacío.");
                    if (!this.apellido) this.errorMostrarMsGanadero.push("El apellido del ganadero no puede estar vacío.");
                    if(this.errorMostrarMsGanadero.length) this.errorGanadero =1;
                    
                    return this.errorGanadero;
                },
                cerrarModal (){
                    this.modal=0;
                    this.tituloModal='';
                    this.nombre='';
                    this.apellido='';
                    this.direccion='';
                    this.telefono='';
                    this.celular='';
                    this.email='';
                    this.razonsocial='';
                    this.confactura='SI';
                   // this.idrol=0;
                    this.errorGanadero=0;
                },
                abrirModal (modelo, accion, data = []){
                    switch(modelo){
                        case  "ganadero":
                        {
                            switch(accion){
                                case 'registrar':
                                {
                                    this.modal = 1;
                                    this.tituloModal = 'Registrar Ganadero';
                                    this.nombre= '';
                                    this.apellido='';
                                    this.direccion='';
                                    this.telefeno='';
                                    this.celular = '';
                                    this.email='';
                                    this.razonsocial='';
                                    this.confactura='SI';
                                    this.iduser='',
                                    this.tipoAccion = 1;
                                    break;
                                }
                                case 'actualizar':
                                {
                                    this.modal=1;
                                    this.tituloModal='Actualizar Ganadero';
                                    this.tipoAccion=2;
                                    this.ganadero_id=data['id'];
                                    this.nombre = data['nombre'];
                                    this.apellido = data['apellido'];
                                    this.direccion = data['direccion'];
                                    this.telefeno = data['telefeno'];
                                     this.celular = data['celular'];
                                    this.email = data['email'];
                                    this.razonsocial = data['razonsocial'];
                                    this.confactura=data['confactura'];
                                    this.iduser='1';
                                    //this.=data['idrol'];
                                    break;
                                }
                            }      
                        }
                    }   
                }
            },
            mounted() {
                this.listarGanadero(1, this.buscar, this.criterio);
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
    

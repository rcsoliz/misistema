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
                        <button type="button" @click="abrirModal('ganadero','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
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
                                    <!-- <th>Email</th> -->
                                    <!-- <th>R. Social</th> -->
                                    <!-- <th>C/Factura</th> -->
                                    <th>Usuario</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="ganadero in arrayGanadero" :key="ganadero.id">
                                    <td>
                                        <button type="button" @click="abrirModal('ganadero','actualizar',ganadero)" class="btn btn-warning btn-sm">
                                            <i class="icon-pencil"></i>
                                        </button> &nbsp;
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
                                    <!-- <td v-text="ganadero.email"></td> -->
                                    <!-- <td v-text="ganadero.razonsocial"></td> -->
                                    <!-- <td v-text="ganadero.confactura"></td> -->
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
                        <div class="modal-body">
                             <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre(*)</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Nombre del ganadero">                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Apellidos(*)</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="apellido" class="form-control" placeholder="Apellidos del ganadero">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Dirección</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="direccion" class="form-control" placeholder="Dirección">
                                    </div>
                                </div>
                               <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Teléfono</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="telefeno" class="form-control" placeholder="Teléfono">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Celular</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="celular" class="form-control" placeholder="Celular">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Email</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="email" class="form-control" placeholder="Email">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Razón Social</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="razonsocial" class="form-control" placeholder="Razón social">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Con Factura</label>
                                    <div class="col-md-9">
                                        <select v-model="confactura" class="form-control">
                                            <option value="SI">SI</option>
                                            <option value="NO">NO</option>
                                        </select>                                        
                                    </div>
                                </div>
                                <div v-show="errorGanadero" class="form-group row div-error"> 
                                    <div class="text-center text-error">
                                       <div v-for="error in errorMostrarMsGanadero" :key="error" v-text="error"> 
                                       </div>    
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()" >Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarGanadero()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarGanadero()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
            <!-- Inicio del modal Eliminar -->
                <!-- eliminamos el MODAL ELIMINAR.....-->
            <!-- Fin del modal Eliminar -->
            <!-- <example-component></example-component> -->
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
                    var url = this.ruta + '/ganadero?page=' + page + '&buscar='+ buscar + '&criterio=' + criterio;
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
                    window.open(this.ruta + '/ganadero/listarGanaderoPdf','_blank');   
                },
                cambiarPagina(page, buscar, criterio){
                    let me = this;
                    //actualiza la pagina actual
                    me.pagination.current_page = page;
                    me.listarGanadero(page, buscar, criterio);
                },
                registrarGanadero (){
                    if(this.validarGanadero()){
                        return ;    
                    }

                    let me = this;
                    
                    axios.post(this.ruta +'/ganadero/registrar', {
                        'codigo':'0',
                        'nombre': this.nombre,
                        'apellido': this.apellido,
                        'direccion' : this.direccion,
                        'telefeno' : this.telefeno,
                        'celular' : this.celular,
                        'email' : this.email,
                        'razonsocial': this.razonsocial,
                        'confactura': this.confactura,
                        'iduser' : 1
                    }).then(function (response) {
                        me.cerrarModal();
                        me.listarGanadero(1, '', 'nombre');  
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                }, 
                actualizarGanadero (){
                    if(this.validarGanadero()){
                        return ;    
                    }

                    let me =this;
                    
                    axios.put(this.ruta + '/ganadero/actualizar', {
                        'codigo':'1',
                        'nombre': this.nombre,
                        'apellido': this.apellido,
                        'direccion' : this.direccion,
                        'telefeno' : this.telefeno,
                        'celular' : this.celular,
                        'email' : this.email,
                        'razonsocial': this.razonsocial,
                        'confactura': this.confactura,
                        'iduser': '1',
                        'id': this.ganadero_id 
                    }).then(function (response) {
                        me.cerrarModal();
                        me.listarGanadero(1, '', 'nombre');  
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
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
    

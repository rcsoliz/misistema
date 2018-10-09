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
                        <i class="fa fa-align-justify"></i> Usuarios
                        <button type="button" @click="abrirModal('user','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row"> <!-- Criterios de Busqueda-->
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                        <option value="nombre">Nombre</option>
                                        <option value="num_documento">Documento</option>
                                        <!-- <option value="email">Email</option> -->
                                        <option value="telefono">Teléfono</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarUsuario(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarUsuario(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Tipo Documento</th>
                                    <th>Número</th>
                                    <th>Dirección</th>
                                    <th>Teléfono</th>
                                    <!-- <th>Email</th> -->
                                    <th>Usuario</th>
                                    <th>Rol</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="user in arrayUsuario" :key="user.id">
                                    <td>
                                        <button type="button" @click="abrirModal('user','actualizar',user)" class="btn btn-warning btn-sm">
                                            <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="user.condicion">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarUser(user.id)">
                                                <i class="icon-trash"></i> <!-- icono de basura-->
                                            </button> 
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarUser(user.id)">
                                                <i class="icon-check"></i> <!-- icono de ok-->
                                            </button> 
                                        </template>
                                    </td>
                                    <td v-text="user.nombre"></td>
                                    <td v-text="user.tipo_documento"></td>
                                    <td v-text="user.num_documento"></td>
                                    <td v-text="user.direccion"></td>
                                    <td v-text="user.telefono"></td>
                                    <!-- <td v-text="user.email"></td> -->
                                    <td v-text="user.usuario"></td>
                                    <td v-text="user.rol"></td>
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
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de la persona">                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Tipo documento</label>
                                    <div class="col-md-9">
                                        <select v-model="tipo_documento" class="form-control">
                                            <option value="CI">CEDULA IDENTIDAD</option>
                                            <option value="RUC">RUC</option>
                                            <option value="PASS">PASS</option>
                                        </select>                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Número documento</label>
                                    <div class="col-md-9">
                                        <input type="email" v-model="num_documento" class="form-control" placeholder="Número de documento">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Dirección</label>
                                    <div class="col-md-9">
                                        <input type="email" v-model="direccion" class="form-control" placeholder="Dirección">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Teléfono</label>
                                    <div class="col-md-9">
                                        <input type="email" v-model="telefono" class="form-control" placeholder="Teléfono">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Email</label>
                                    <div class="col-md-9">
                                        <input type="email" v-model="email" class="form-control" placeholder="Email">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Rol</label>
                                    <div class="col-md-9">
                                        <select v-model="idrol" class="form-control">
                                            <option value="0" disabled>Seleccione</option>
                                            <option v-for="role in arrayRol" :key="role.id" :value="role.id" v-text="role.nombre"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Usuario</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="usuario" class="form-control" placeholder="Nombre del usuario">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">password</label>
                                    <div class="col-md-9">
                                        <input type="password" v-model="password" class="form-control" placeholder="password del usuario">
                                    </div>
                                </div>
                                <div v-show="errorUsuario" class="form-group row div-error"> 
                                    <div class="text-center text-error">
                                       <div v-for="error in errorMostrarMsUsuario" :key="error" v-text="error"> 
                                       </div>    
                                    </div>
                                </div>
                            </form>
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
                <!-- eliminamos el MODAL ELIMINAR.....-->
            <!-- Fin del modal Eliminar -->
            <!-- <example-component></example-component> -->
        </main>   
    </template>

    <script>
        export default {
            props : ['ruta'],
            //propiedad data
            data (){
                return {
                    usuario_id: 0,
                    nombre : '',
                    tipo_documento : '',
                    num_documento : '',
                    direccion : '',
                    telefono : '',
                    email : '',
                    usuario: '',
                    password:'',
                    idrol: '',
                    arrayUsuario : [],
                    arrayRol : [],
                    modal : 0,
                    tituloModal : '',
                    tipoAccion : 0, 
                    // atributo para errores
                    errorUsuario : 0,
                    errorMostrarMsUsuario : [],
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
                listarUsuario (page, buscar, criterio){
                    let me=this;
                    var url =this.ruta + '/user?page=' + page + '&buscar='+ buscar + '&criterio=' + criterio;
                    axios.get(url).then(function (response) {
                        var respuesta = response.data;
                        me.arrayUsuario = respuesta.users.data;
                        me.pagination = respuesta.pagination;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                },
                selectRol(){
                    let me=this;
                    var url= this.ruta + '/rol/selectRol';
                    axios.get(url).then(function (response) {
                        //console.log(response);
                        var respuesta= response.data;
                        me.arrayRol = respuesta.roles;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                },
                cambiarPagina(page, buscar, criterio){
                    let me = this;
                    //actualiza la pagina actual
                    me.pagination.current_page = page;
                    me.listarUsuario(page, buscar, criterio);
                },
                registrarUsuario (){
                    if(this.validarUser()){
                        return ;    
                    }

                    let me = this;
                    
                    axios.post(this.ruta + '/user/registrar', {
                        'nombre': this.nombre,
                        'tipo_documento': this.tipo_documento,
                        'num_documento' : this.num_documento,
                        'direccion' : this.direccion,
                        'telefono' : this.telefono,
                        'email' : this.email,
                        'idrol' : this.idrol,
                        'usuario': this.usuario,
                        'password': this.password
                    }).then(function (response) {
                        me.cerrarModal();
                        me.listarUsuario(1, '', 'nombre');  
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                }, 
                actualizarUsuario (){
                    if(this.validarUser()){
                        return ;    
                    }

                    let me =this;
                    
                    axios.put(this.ruta + '/user/actualizar', {
                        'nombre': this.nombre,
                        'tipo_documento': this.tipo_documento,
                        'num_documento' : this.num_documento,
                        'direccion' : this.direccion,
                        'telefono' : this.telefono,
                        'email' : this.email,
                        'idrol' : this.idrol,
                        'usuario': this.usuario,
                        'password': this.password,
                        'id': this.usuario_id
                    }).then(function (response) {
                        me.cerrarModal();
                        me.listarUsuario(1, '', 'nombre');  
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                },
                desactivarUser(id){
                    swal({
                    title: 'Esta seguro de desactivar este usuario?',
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

                    axios.put(this.ruta + '/user/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarUsuario(1, '', 'nombre');
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
                activarUser (id){
                    swal({
                    title: 'Esta seguro de activar este usuario?',
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

                    axios.put(this.ruta + '/user/activar',{
                        'id': id
                    }).then(function (response) {
                        me.listarUsuario(1, '', 'nombre');
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
                validarUser (){
                    this.errorUsuario = 0 ;    
                    this.errorMostrarMsUsuario =[];

                    if (!this.nombre) this.errorMostrarMsUsuario.push("El nombre de la pesona no puede estar vacío.");
                    if (!this.usuario) this.errorMostrarMsUsuario.push("El nombre de usuario no puede estar vacío.");
                    if (!this.password) this.errorMostrarMsUsuario.push("La password del usuario no puede estar vacía.");
                    if (this.idrol==0) this.errorMostrarMsUsuario.push("Seleccione una Rol.");
                    if(this.errorMostrarMsUsuario.length) this.errorUsuario=1;
                    
                    return this.errorUsuario;
                },
                cerrarModal (){
                    this.modal=0;
                    this.tituloModal='';
                    this.nombre='';
                    this.tipo_documento='CI';
                    this.num_documento='';
                    this.direccion='';
                    this.telefono='';
                    this.email='';
                    this.usuario='';
                    this.password='';
                    this.idrol=0;
                    this.errorUsuario=0;
                },
                abrirModal (modelo, accion, data = []){
                    this.selectRol();
                    switch(modelo){
                        case  "user":
                        {
                            switch(accion){
                                case 'registrar':
                                {
                                    this.modal = 1;
                                    this.tituloModal = 'Registrar Usuario';
                                    this.nombre= '';
                                    this.tipo_documento='DNI';
                                    this.num_documento='';
                                    this.direccion='';
                                    this.telefono='';
                                    this.email='';
                                    this.usuario='';
                                    this.password='';
                                    this.idrol=0;
                                    this.tipoAccion = 1;
                                    break;
                                }
                                case 'actualizar':
                                {
                                    this.modal=1;
                                    this.tituloModal='Actualizar Usuario';
                                    this.tipoAccion=2;
                                    this.usuario_id=data['id'];
                                    this.nombre = data['nombre'];
                                    this.tipo_documento = data['tipo_documento'];
                                    this.num_documento = data['num_documento'];
                                    this.direccion = data['direccion'];
                                    this.telefono = data['telefono'];
                                    this.email = data['email'];
                                    this.usuario = data['usuario'];
                                    this.password=data['password'];
                                    this.idrol=data['idrol'];
                                    break;
                                }
                            }      
                        }
                    }   
                }

            },
            mounted() {
                this.listarUsuario(1, this.buscar, this.criterio);
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
    

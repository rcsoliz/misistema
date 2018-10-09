    <template>
        <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item"><a href="#">Admin</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card"> 
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Parámetros
                        <button type="button" @click="abrirModal('parametro','registrar')" class="btn btn-secondary">
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
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarParametro(1, buscar, criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarParametro(1, buscar, criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Codigo</th>
                                    <th>Nombre</th>
                                    <th>Valor1</th>
                                    <th>Valor2</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="parametro in arrayParametro" :key="parametro.id">
                                    <td>
                                        <button type="button" @click="abrirModal('parametro','actualizar',parametro)" class="btn btn-warning btn-sm">
                                            <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="parametro.condicion">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarParametro(parametro.id)">
                                                <i class="icon-trash"></i> <!-- icono de basura-->
                                            </button> 
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarParametro(parametro.id)">
                                                <i class="icon-check"></i> <!-- icono de ok-->
                                            </button> 
                                        </template>
                                    </td>
                                    <td v-text="parametro.codigo"></td>
                                    <td v-text="parametro.nombre"></td>
                                    <td v-text="parametro.valor1"></td>
                                    <td v-text="parametro.valor2"></td>
                                    <td>
                                        <div v-if="parametro.condicion">
                                           <span class="badge badge-success">Activo</span>
                                        </div>
                                        <div v-else>
                                           <span class="badge badge-danger">Desactivado</span>
                                           <!-- class=badge badge-danger -> "para mostrar un etiqueta de color rojo" -->
                                        </div>
                                    </td>
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
                                    <label class="col-md-3 form-control-label" for="text-input">Codigo</label>
                                    <div class="col-md-3">
                                        <input type="text" v-model="codigo" class="form-control" placeholder="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombresss</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Nombre del parámetro">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Valor1</label>
                                    <div class="col-md-9">
                                        <input type="number" v-model="valor1" class="form-control" placeholder="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Valor2</label>
                                    <div class="col-md-9">
                                        <input type="number" v-model="valor2" class="form-control" placeholder="">
                                    </div>
                                </div>
                                <div v-show="errorParametro" class="form-group row div-error"> 
                                    <div class="text-center text-error">
                                       <div v-for="error in errorMostrarMsParametro" :key="error" v-text="error"> 
                                       </div>    
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()" >Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarParametro()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarParametro()">Actualizar</button>
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
                    parametro_id : 0,
                    codigo : '',
                    nombre : '',
                    valor1 : '',
                    valor2 : '',
                    arrayParametro : [],
                    modal : 0,
                    tituloModal : '',
                    tipoAccion : 0, 
                    // atributo para errores
                    errorParametro : 0,
                    errorMostrarMsParametro : [],
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
                pagesNumber: function(){
                    if(!this.pagination.to){
                        return [];
                    }
                    var from= this.pagination.current_page - this.offset;
                    if(from < 1){
                        from = 1;
                    }

                    var to = from + (this.offset * 2);
                    if(to >= this.pagination.last_page){
                        to = this.pagination.last_page; 
                    }

                    var pagesArray = [];
                    while(from <= to){
                        pagesArray.push(from);
                        from ++ ;
                    }
                    return pagesArray;
                }   
            },
            //metodos ...
            methods : {
                listarParametro (page, buscar, criterio){
                    let me=this;
                    var url =this.ruta + '/parametro?page=' + page + '&buscar='+ buscar + '&criterio=' + criterio;
                    //var url ='/parametro?page=' + page + '&buscar='+ buscar + '&criterio=' + criterio;
                    axios.get(url).then(function (response) {
                        var respuesta = response.data;
                        me.arrayParametro = respuesta.parametros.data;
                        me.pagination = respuesta.pagination;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                },
                cargarPdf(){
                    //window.open('http://127.0.0.1:8000/parametro/listarParametroPdf','_blank');
                     window.open(this.ruta + '/parametro/listarParametroPdf','_blank');   
                },
                cambiarPagina(page, buscar, criterio){
                    let me = this;
                    //actualiza la pagina actual
                    me.pagination.current_page = page;
                    me.listarParametro(page, buscar, criterio);
                },
                registrarParametro (){
                    if(this.validarParametro()){
                        return ;    
                    }

                    let me = this;
                    
                    axios.post(this.ruta + '/parametro/registrar', {
                        'codigo' : this.codigo,
                        'nombre' : this.nombre,
                        'valor1' : this.valor1,
                        'valor2' : this.valor2
                    }).then(function (response) {
                        me.cerrarModal();
                        me.listarParametro(1, '', 'nombre');  
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                }, 
                actualizarParametro (){
                    if(this.validarParametro()){
                        return ;    
                    }

                    let me =this;
                    
                    axios.put(this.ruta +'/parametro/actualizar', {
                        'codigo' : this.codigo,
                        'nombre' : this.nombre,
                        'valor1' : this.valor1,
                        'valor2' : this.valor2,
                        'id' : this.parametro_id
                    }).then(function (response) {
                        me.cerrarModal();
                        me.listarParametro(1, '', 'nombre');  
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                },
                desactivarParametro(id){
                    swal({
                    title: 'Esta seguro de desactivar este parametro?',
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

                    axios.put(this.ruta + '/parametro/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarParametro(1, '', 'nombre');
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
                activarParametro (id){
                    swal({
                    title: 'Esta seguro de activar este parametro?',
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

                    axios.put(this.ruta + '/parametro/activar',{
                        'id': id
                    }).then(function (response) {
                        me.listarParametro(1, '', 'nombre');
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
                validarParametro (){
                    this.errorParametro = 0 ;    
                    this.errorMostrarMsParametro =[];

                    if(!this.codigo){
                        this.errorMostrarMsParametro.push('El codigo del parametro no puede estar vacio.');
                    }
                    if(!this.nombre){
                        this.errorMostrarMsParametro.push('El nombre del parametro no puede estar vacio.');
                    }

                    if(this.errorMostrarMsParametro.length) this.errorParametro=1;
                    
                    return this.errorParametro;
                },
                cerrarModal (){
                    this.modal = 0;
                    this.tituloModal = '';
                    this.codigo = '',
                    this.nombre = '';
                    this.valor1 = '0';
                    this.valor2 = '0';
                },
                abrirModal (modelo, accion, data = []){
                    switch(modelo){
                        case  "parametro":
                        {
                            switch(accion){
                                case 'registrar':
                                {
                                    this.modal = 1;
                                    this.tituloModal = 'Registrar Parámetros';
                                    this.codigo = '';
                                    this.nombre = '';
                                    this.valor1 = '';
                                    this.valor2 = '';
                                    this.tipoAccion = 1;
                                    break;
                                }
                                case 'actualizar':
                                {
                                    this.modal = 1;   
                                    this.tituloModal = 'Actualizar Parámetros';
                                    this.tipoAccion = 2;
                                    this.codigo = data['codigo'];
                                    this.nombre = data['nombre'];
                                    this.valor1 = data['valor1']; 
                                    this.valor2 = data['valor2']; 
                                    this.parametro_id = data['id']; 
                                    break;
                                }
                            }      
                        }
                    }   
                }

            },
            mounted() {
                this.listarParametro(1, this.buscar, this.criterio);
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
    

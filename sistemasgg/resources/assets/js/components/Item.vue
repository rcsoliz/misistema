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
                        <i class="fa fa-align-justify"></i> Items
                        <button type="button" @click="abrirModal('item','registrar')" class="btn btn-secondary">
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
                                        <option value="clase">Clase</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarItem(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarItem(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
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
                                        <button type="button" @click="abrirModal('item','actualizar',item)" class="btn btn-warning btn-sm">
                                            <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="item.condicion">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarItem(item.id)">
                                                <i class="icon-trash"></i> <!-- icono de basura-->
                                            </button> 
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarItem(item.id)">
                                                <i class="icon-check"></i> <!-- icono de ok-->
                                            </button> 
                                        </template>
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
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Nombre del item">                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Clase</label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="idclase" >
                                            <option value="0" disabled>Seleccione</option>
                                            <option v-for="clase in arrayClase" :key="clase.id" :value="clase.id" v-text="clase.nombre">
                                                
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div v-show="errorItem" class="form-group row div-error"> 
                                    <div class="text-center text-error">
                                       <div v-for="error in errorMostrarMsItem" :key="error" v-text="error"> 
                                       </div>    
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()" >Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarItem()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarItem()">Actualizar</button>
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
                    item_id: 0,
                    nombre : '',
                    idclase: '',
                    arrayItem : [],
                    arrayClase : [],
                    modal : 0,
                    tituloModal : '',
                    tipoAccion : 0, 
                    // atributo para errores
                    errorItem : 0,
                    errorMostrarMsItem : [],
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
                listarItem (page, buscar, criterio){
                    let me=this;
                    var url = this.ruta + '/item?page=' + page + '&buscar='+ buscar + '&criterio=' + criterio;
                    axios.get(url).then(function (response) {
                        var respuesta = response.data;
                        me.arrayItem = respuesta.items.data;
                        me.pagination = respuesta.pagination;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                },
                cargarPdf(){
                   //window.open('http://127.0.0.1:8000/item/listarPdf','_blank');   
                   window.open(this.ruta + '/item/listarPdf','_blank');   
                },
                selectClase(){
                    let me=this;
                    var url= this.ruta + '/clase/selectClase';
                    axios.get(url).then(function (response) {
                        //console.log(response);
                        var respuesta= response.data;
                        me.arrayClase = respuesta.clases;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                },
                cambiarPagina(page, buscar, criterio){
                    let me = this;
                    //actualiza la pagina actual
                    me.pagination.current_page = page;
                    me.listarItem(page, buscar, criterio);
                },
                registrarItem (){
                    if(this.validarItem()){
                        return ;    
                    }

                    let me = this;
                    
                    axios.post(this.ruta + '/item/registrar', {
                        'nombre': this.nombre,
                        'idclase' : this.idclase
                    }).then(function (response) {
                        me.cerrarModal();
                        me.listarItem(1, '', 'nombre');  
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                }, 
                actualizarItem (){
                    if(this.validarItem()){
                        return ;    
                    }

                    let me =this;
                    
                    axios.put(this.ruta + '/item/actualizar', {
                        'nombre': this.nombre,
                        'idclase' : this.idclase,
                        'id': this.item_id
                    }).then(function (response) {
                        me.cerrarModal();
                        me.listarItem(1, '', 'nombre');  
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                },
                desactivarItem(id){
                    swal({
                    title: 'Esta seguro de desactivar este item?',
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

                    axios.put(this.ruta + '/item/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarItem(1, '', 'nombre');
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
                activarItem (id){
                    swal({
                    title: 'Esta seguro de activar este item?',
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

                    axios.put(this.ruta + '/item/activar',{
                        'id': id
                    }).then(function (response) {
                        me.listarItem(1, '', 'nombre');
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
                validarItem (){
                    this.errorItem = 0 ;    
                    this.errorMostrarMsItem =[];

                    if (!this.nombre) this.errorMostrarMsItem.push("El nombre del item no puede estar vacío.");
                    if (this.idclase==0) this.errorMostrarMsItem.push("Seleccione una clase.");
                    if(this.errorMostrarMsItem.length) this.errorItem=1;
                    
                    return this.errorItem;
                },
                cerrarModal (){
                    this.modal=0;
                    this.tituloModal='';
                    this.nombre='';
                    this.idclase=0;
                    this.errorItem=0;
                },
                abrirModal (modelo, accion, data = []){
                    this.selectClase();
                    switch(modelo){
                        case  "item":
                        {
                            switch(accion){
                                case 'registrar':
                                {
                                    this.modal = 1;
                                    this.tituloModal = 'Registrar Item';
                                    this.nombre= '';
                                    this.idclase=0;
                                    this.tipoAccion = 1;
                                    break;
                                }
                                case 'actualizar':
                                {
                                    this.modal=1;
                                    this.tituloModal='Actualizar Item';
                                    this.tipoAccion=2;
                                    this.item_id=data['id'];
                                    this.nombre = data['nombre'];
                                    this.idclase=data['idclase'];
                                    break;
                                }
                            }      
                        }
                    }   
                }

            },
            mounted() {
                this.listarItem(1, this.buscar, this.criterio);
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
    

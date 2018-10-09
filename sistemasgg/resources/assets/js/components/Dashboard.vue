<template>
    <main class="main">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
        </ol> 
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                   <h1></h1>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-chart">
                                <div class="card-header">
                                    <h4>Visitas por Mes</h4>
                                </div>
                                <div class="card-content">
                                    <div class="ct-chart">
                                        <canvas id="visitas">
                                        </canvas>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <p>Visitas de los ultimos meses.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card card-chart">
                                <div class="card-header">
                                    <h4>Regitros por Captador</h4>
                                </div>
                                <div class="card-content">
                                    <div class="ct-chart">
                                        <canvas id="visitauser">                                                
                                        </canvas>
                                    </div> 
                                </div>
                                <div class="card-footer">
                                    <p>Vistas captador de ganado.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card card-chart">
                                <div class="card-header">
                                    <h4>Ganado por Mes</h4>
                                </div>
                                <div class="card-content">
                                    <div class="ct-chart">
                                        <canvas id="vistaGanado">
                                        </canvas>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <p>Cantidad de reses por mes.</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-chart">
                                <div class="card-header">
                                    <h4>Visitas por Mes</h4>
                                </div>
                                <div class="card-content">
                                    <div class="ct-chart">
                                        <canvas id="vistaGanado">
                                        </canvas>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <p>Visitas de los ultimos meses.</p>
                                </div>
                            </div>
                        </div>
                    </div>   
                </div>  -->
                
            </div>
        </div>
    </main>
</template>
<script>
    export default {
        props : ['ruta'],
        data (){
            return {
                varVisita: null,
                charVisita: null,
                visitas: [],
                varTotalVisita: [],
                varMesVisita: [],


                varVisitaUser: null,
                charVisitaUser: null,
                visitasUser: [],
                varTotalVisitaUser: [],
                varMesVisitaUser: [],

                varVistaGanado: null,
                charVistaGanado: null,
                vistaGanado: [],
                varTotalVistaGanado: [],
                varMesVistaGanado: []
            }
        },
        methods :{ 
            getVisitas(){
                let me=this;
                //var url=this.ruta + '/deshboard';
                var url=this.ruta + '/deshboard';
                axios.get(url).then(function (response){
                   var respuesta = response.data;
                   me.visitas = respuesta.visitas;
                   me.loadVisitas();
                })
                .catch(function (error){
                    console.log(error);
                });
            },
            getVisitasUser(){
                let me=this;
                //var url=this.ruta + '/deshboard';
                var url=this.ruta + '/deshboard';
                axios.get(url).then(function (response){
                   var respuesta = response.data;
                   me.visitasUser = respuesta.visitauser;
                   me.loadVisitasUser();
                })
                .catch(function (error){
                    console.log(error);
                });
            },
            getVistasGanado(){
                let me=this;
                var url=this.ruta + '/deshboard';
                axios.get(url).then(function (response){
                   var respuesta = response.data;
                   me.vistaGanado = respuesta.vistaganado;
                   me.loadVisitasGanado();
                })
                .catch(function (error){
                    console.log(error);
                });
            },   

            loadVisitas(){
                let me=this;
                me.visitas.map(function(x){
                    me.varMesVisita.push(x.mes);
                    me.varTotalVisita.push(x.total);  
                });
                me.varVisita=document.getElementById('visitas').getContext('2d');
                me.charVisita = new Chart(me.varVisita, {
                    type: 'bar',
                    data: {
                        labels:me.varMesVisita,
                        datasets: [{
                            label: 'Nº Visitas',
                            data: me.varTotalVisita,
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 0.2)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }
                });
            },
            loadVisitasUser(){
                let me=this;
                me.visitasUser.map(function(x){
                me.varMesVisitaUser.push(x.nombre);
                me.varTotalVisitaUser.push(x.total);  
                });
                me.varVisitaUser=document.getElementById('visitauser').getContext('2d');
                me.charVisitaUser = new Chart(me.varVisitaUser, {
                    type: 'horizontalBar',
                    data: {  
                        labels: me.varMesVisitaUser,
                        datasets: [{
                            label: 'Captador(es)',
                            data: me.varTotalVisitaUser,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 0.2)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }
                }); 
            },
            loadVisitasGanado(){
                let me=this;
                me.vistaGanado.map(function(x){
                    me.varMesVistaGanado.push(x.mes);
                    me.varTotalVistaGanado.push(x.total);  
                });
                me.varVistaGanado=document.getElementById('vistaGanado').getContext('2d');
                me.charVistaGanado = new Chart(me.varVistaGanado, {
                    type: 'bar',
                    data: {  
                        labels: me.varMesVistaGanado,
                        datasets: [{
                           // label: 'Cantadid de Ganado(es)',
                            label:'Cantidad de Reses', //  me.varMesVistaGanado,
                            data: me.varTotalVistaGanado,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 0.2)',
                            borderWidth: 1,
                           // fill: false 
                        }
                        ]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }
                });     
            }
        },
        mounted(){
            this.getVisitas();
            this.getVisitasUser();
            this.getVistasGanado();
        } 
    }
</script>
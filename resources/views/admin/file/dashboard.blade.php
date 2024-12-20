@extends('admin.template')

@section('title','Dashboard')

@section('body')
    
    <div class="container-fluid">
        <!--  Row 1 -->
        <div class="row">
            <div class="col-sm-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row alig n-items-start">
                            <div class="col-8">
                                <h5 class="card-title mb-9 fw-semibold"> Clients </h5>
                                <h4 class="fw-semibold mb-3">{{$client}}</h4>
                            </div>
                            <div class="col-4">
                                <div class="d-flex justify-content-end">
                                    <div class="text-white bg-warning rounded-circle p-6 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-users fs-6"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row alig n-items-start">
                            <div class="col-8">
                                <h5 class="card-title mb-9 fw-semibold"> Annonceurs </h5>
                                <h4 class="fw-semibold mb-3"> {{$announcer}} </h4>
                            </div>
                            <div class="col-4">
                                <div class="d-flex justify-content-end">
                                    <div class="text-white bg-primary rounded-circle p-6 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-home fs-6"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row alig n-items-start">
                            <div class="col-8">
                                <h5 class="card-title mb-9 fw-semibold"> Visites </h5>
                                <h4 class="fw-semibold mb-3">{{$visit}}</h4>
                            </div>
                            <div class="col-4">
                                <div class="d-flex justify-content-end">
                                    <div class="text-white bg-success rounded-circle p-6 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-layout-grid fs-6"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row alig n-items-start">
                            <div class="col-8">
                                <h5 class="card-title mb-9 fw-semibold"> Fond IYALO </h5>
                                <h5 class="fw-semibold mb-3">{{$iyalo}}<sup style="font-size:0.4em">XOF</sup></h5>
                            </div>
                            <div class="col-4">
                                <div class="d-flex justify-content-end">
                                    <div class="text-white bg-secondary rounded-circle p-6 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-cash fs-6"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 d-flex align-items-strech">
                <div class="card w-100">
                    <div class="card-body">
                    <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
                        <div class="mb-3 mb-sm-0">
                            <h5 class="card-title fw-semibold">Statistiques</h5>
                        </div>
                    </div>
                        <div id="chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <h5 class="card-title fw-semibold">Dernières Visites Validés</h5>
                        </div>
                        <ul class="timeline-widget mb-0 position-relative">
                            @foreach($recentes as $recent)
                                <li class="timeline-item d-flex position-relative overflow-hidden">
                                    <div class="timeline-time text-dark flex-shrink-0 text-end">{{ date('h:i A',strtotime($recent->date_visite))}}</div>
                                    <div class="timeline-badge-wrap d-flex flex-column align-items-center">
                                        <span class="timeline-badge border-2 border border-info flex-shrink-0 my-8"></span>
                                        <span class="timeline-badge-border d-block flex-shrink-0"></span>
                                    </div>
                                    <div class="timeline-desc fs-3 text-dark mt-n1 fw-semibold">{{ $recent->user->name }}  <span class="text-primary">{{ $recent->amount }} {{ $recent->property->device }}</span> <a
                                        href="javascript:void(0)" class="text-primary d-block fw-normal"> {{ $recent->property->label }} {{ $recent->property->user->name }}</a> 
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        const today = new Date();
        const dates = [];

        for (let i = 0; i < 10; i++) {
            const previousDate = new Date(today);
            previousDate.setDate(today.getDate() - i);

            const months = ['Jan', 'Fév', 'Mars', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Déc'];

            // Formatage au format d-m
            const formattedDate = `${previousDate.getDate()} ${months[previousDate.getMonth()]}`;
            dates.push(formattedDate);
        }

        $(function () {
            var chart = {
            series: [
                { name: "Clients", data: {{$users}} },
                { name: "Montant Visites", data: {{$visits}} },
            ],

            chart: {
                type: "bar",
                height: 345,
                offsetX: -15,
                toolbar: { show: true },
                foreColor: "#adb0bb",
                fontFamily: 'inherit',
                sparkline: { enabled: false },
            },


            colors: ["#20c997", "#5D87FF"],


            plotOptions: {
                bar: {
                horizontal: false,
                columnWidth: "70%",
                borderRadius: [6],
                borderRadiusApplication: 'end',
                borderRadiusWhenStacked: 'all'
                },
            },
            markers: { size: 0 },

            dataLabels: {
                enabled: false,
            },


            legend: {
                show: false,
            },


            grid: {
                borderColor: "rgba(0,0,0,0.1)",
                strokeDashArray: 3,
                xaxis: {
                lines: {
                    show: false,
                },
                },
            },

            xaxis: {
                type: "date",
                categories: dates,
                labels: {
                style: { cssClass: "grey--text lighten-2--text fill-color" },
                },
            },


            yaxis: {
                show: true,
                min: 0,
                tickAmount: 4,
                labels: {
                style: {
                    cssClass: "grey--text lighten-2--text fill-color",
                },
                },
            },
            stroke: {
                show: true,
                width: 3,
                lineCap: "butt",
                colors: ["transparent"],
            },


            tooltip: { theme: "light" },

            responsive: [
                {
                breakpoint: 600,
                options: {
                    plotOptions: {
                    bar: {
                        borderRadius: 3,
                    }
                    },
                }
                }
            ]


            };

            var chart = new ApexCharts(document.querySelector("#chart"), chart);
            chart.render();

        })
    </script>
@endsection
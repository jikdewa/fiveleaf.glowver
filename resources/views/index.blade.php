@extends('layouts.app')

@section('content')

 <!-- TITLE -->
            <h1 class="text-xl md:text-5xl font-bold mt-5">
                Dashboard
            </h1>

            <!-- ALERT -->
            <div class="mt-5 bg-red-100 text-red-600 rounded-xl p-4">

                <i class="fa-solid fa-circle-exclamation"></i>

                Ada produk yang hampir habis, segera restok.

                <a href="#" class="underline">
                    View More...
                </a>

            </div>

            <!-- CARDS -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mt-6">

                <!-- PROFIT -->
                <div class="bg-[#FFF] text-white rounded-3xl p-5 border-2 border-gray-200">

                    <div class="flex justify-between items-center">

                        <div class="space-y-4">
                            <p class="text-lg font-semibold text-gray-800">
                                Profit
                            </p>

                            <h2 class="text-3xl font-bold text-gray-900">
                                Rp.15,700<span class="text-gray-300">.00</span>
                            </h2>

                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center bg-green-100 text-green-600 text-sm font-medium px-3 py-1 rounded-full">
                                    ↑ 12.1%
                                </span>

                                <span class="text-gray-500 text-sm">
                                    vs last month
                                </span>
                            </div>
                        </div>

                        

                    </div>

                </div>

                <!-- SALES -->
                <div class="bg-[#FFF] text-white rounded-3xl p-5 border-2 border-gray-200">

                    <div class="flex justify-between items-center">

                        <div class="space-y-4">
                            <p class="text-lg font-semibold text-gray-800">
                                Sales
                            </p>

                            <h2 class="text-3xl font-bold text-gray-900">
                                Rp.15,700<span class="text-gray-300">.00</span>
                            </h2>

                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center bg-green-100 text-green-600 text-sm font-medium px-3 py-1 rounded-full">
                                    ↑ 12.1%
                                </span>

                                <span class="text-gray-500 text-sm">
                                    vs last month
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PURCHASE -->
                <div class="bg-[#FFF] text-white rounded-3xl p-5 border-2 border-gray-200">

                    <div class="flex justify-between items-center">

                        <div class="flex justify-between items-center">

                            <div class="space-y-4">
                                <p class="text-lg font-semibold text-gray-800">
                                    Expenses
                                </p>

                                <h2 class="text-3xl font-bold text-gray-900">
                                    Rp.15,700<span class="text-gray-300">.00</span>
                                </h2>

                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center bg-red-100 text-red-600 text-sm font-medium px-3 py-1 rounded-full">
                                        ↑ 12.1%
                                    </span>

                                    <span class="text-gray-500 text-sm">
                                        vs last month
                                    </span>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <!-- Users -->
                <div class="bg-[#FFF] text-white rounded-3xl p-5 border-2 border-gray-200">

                    <div class="flex justify-between items-center">

                        <div class="space-y-4">
                            <p class="text-lg font-semibold text-gray-800">
                                Total Users
                            </p>

                            <h2 class="text-3xl font-bold text-gray-900">
                                510
                            </h2>

                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center bg-green-100 text-green-600 text-sm font-medium px-3 py-1 rounded-full">
                                    ↑ 12.1%
                                </span>

                                <span class="text-gray-500 text-sm">
                                    vs last month
                                </span>
                            </div>
                        </div>

                        

                    </div>

                </div>

            </div>

            <!-- CHART & WIDGET -->
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mt-6">

                <!-- Money Flow -->
                <div class="xl:col-span-9">
                    <div class="bg-white border-2 border-gray-200 rounded-3xl p-6 h-full">

                        <!-- Header -->
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">

                            <h2 class="text-xl font-bold text-gray-900">
                                Money Flow
                            </h2>

                            <div class="flex flex-wrap items-center gap-3">

                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-violet-600"></span>
                                    <span class="text-sm text-gray-600">Income</span>
                                </div>

                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-violet-300"></span>
                                    <span class="text-sm text-gray-600">Expense</span>
                                </div>

                                <select class="px-4 py-2 border border-gray-200 rounded-xl text-sm">
                                    <option>All Accounts</option>
                                </select>

                                <select class="px-4 py-2 border border-gray-200 rounded-xl text-sm">
                                    <option>This Year</option>
                                </select>

                            </div>

                        </div>

                        <div id="moneyFlowChart"></div>

                    </div>
                </div>

                <!-- Pie Chart -->
                <div class="xl:col-span-3">
                    <div class="bg-white border-2 border-gray-200 rounded-3xl p-6 h-full">

                        <h2 class="text-xl font-bold text-gray-900 mb-6">
                            Sales Distribution
                        </h2>

                        <div id="pieChart"></div>

                    </div>
                </div>

            </div>


<script>
const options = {
    series: [{
        name: 'Income',
        data: [10000, 11000, 10500, 14000, 13000, 7500, 9000]
    },
    {
        name: 'Expense',
        data: [8500, 13000, 10000, 12500, 12200, 6000, 6500]
    }],

    chart: {
        type: 'bar',
        height: 350,
        toolbar: {
            show: false
        }
    },

    colors: ['#6D5DFC', '#C4B5FD'],

    plotOptions: {
        bar: {
            borderRadius: 8,
            columnWidth: '50%'
        }
    },

    dataLabels: {
        enabled: false
    },

    stroke: {
        show: false
    },

    xaxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul']
    },

    yaxis: {
        labels: {
            formatter: function(value) {
                return '$' + value.toLocaleString();
            }
        }
    },

    legend: {
        show: false
    },

    grid: {
        borderColor: '#f1f5f9'
    },

    tooltip: {
        y: {
            formatter: function(value) {
                return '$' + value.toLocaleString();
            }
        }
    },

    responsive: [{
        breakpoint: 768,
        options: {
            chart: {
                height: 280
            }
        }
    }]
};

const chart = new ApexCharts(
    document.querySelector("#moneyFlowChart"),
    options
);

chart.render();
</script>

@endsection
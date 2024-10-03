<div class="col-md-9">
    <div class="card border-0 rounded-lg">
        <div class="card-body">
            <h5 class="text-2xl text-center font-bold">
                Selamat Datang <strong>{{ auth()->user()->name }}</strong>
            </h5>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
                <div class="card bg-gradient-to-r from-purple-500 to-indigo-500 p-4 rounded-lg shadow-lg text-white">
                    <h6 class="font-semibold">Jumlah Kategori</h6>
                    <p class="text-2xl">{{ $categoriesCount }}</p>
                </div>
                <div class="card bg-gradient-to-r from-green-500 to-teal-500 p-4 rounded-lg shadow-lg text-white">
                    <h6 class="font-semibold">Jumlah Produk</h6>
                    <p class="text-2xl">{{ $productsCount }}</p>
                </div>
                <div class="card bg-gradient-to-r from-blue-500 to-cyan-500 p-4 rounded-lg shadow-lg text-white">
                    <h6 class="font-semibold">Jumlah Transaksi</h6>
                    <p class="text-2xl">{{ $transactionsCount }}</p>
                </div>
                <div class="card bg-gradient-to-r from-red-500 to-orange-500 p-4 rounded-lg shadow-lg text-white">
                    <h6 class="font-semibold">Jumlah Detail Transaksi</h6>
                    <p class="text-2xl">{{ $transactionDetailsCount }}</p>
                </div>
            </div>

            <div class="mt-6">
                <div class="w-full bg-white rounded-lg shadow dark:bg-gray-800 p-2 md:p-4">
                    <div id="column-chart" class="w-full h-96"></div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Include ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    let customerNames = @json($customerData->pluck('customer_name'));
    let totalAmounts = @json($customerData->pluck('total_amount'));
    let bayar = @json($customerData->pluck('bayar'));

    // Memfilter data untuk memastikan hanya mengambil data yang memiliki 'bayar' tidak kosong
    let customerData = customerNames.map((name, index) => ({
        name: name,
        amount: totalAmounts[index],
        bayar: bayar[index], // Menyimpan nilai bayar juga
    })).filter(customer => customer.bayar > 0); // Hanya ambil data jika 'bayar' > 0

    // Cek kondisi: Jika tidak ada data, tampilkan pesan di konsol dan keluar dari fungsi
    if (customerData.length === 0) {
        console.log('Tidak ada data pembayaran untuk ditampilkan.');
        return; // Jika tidak ada data, tidak perlu melanjutkan
    }

    // Mengurutkan dan mengambil 10 pelanggan teratas, atau 5 jika layar kecil
    const isSmallScreen = window.innerWidth < 768; // Deteksi layar kecil
    const topCustomersCount = isSmallScreen ? 5 : 10; // Menggunakan 5 untuk layar kecil dan 10 untuk layar besar
    customerData.sort((a, b) => b.amount - a.amount);
    const topCustomers = customerData.slice(0, topCustomersCount);

    customerNames = topCustomers.map(customer => customer.name);
    totalAmounts = topCustomers.map(customer => customer.amount);

    // Mengatur opsi untuk chart
    const options = {
        series: [{
            name: 'Total Amount',
            data: totalAmounts,
        }],
        chart: {
            type: 'bar',
            height: '100%',
            width: '100%',
            background: '#1A202C',
            redrawOnParentResize: true,
        },
        title: {
            text: isSmallScreen ? 'Top 5 Transactions by Total Amount' : 'Top 10 Transactions by Total Amount',
            align: 'center',
            style: {
                color: '#ffffff',
                fontSize: isSmallScreen ? '9px' : '16px', // Mengatur ukuran teks berdasarkan ukuran layar
            }
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '70%',
                endingShape: 'rounded',
            },
        },
        dataLabels: {
            enabled: false,
        },
        stroke: {
            show: true,
            width: 1,
            colors: ['transparent'],
        },
        xaxis: {
            categories: customerNames,
            labels: {
                style: {
                    colors: '#ffffff',
                    fontSize: isSmallScreen ? '8px' : '12px',
                }
            },
            axisBorder: {
                color: '#ffffff',
            },
            axisTicks: {
                color: '#ffffff',
            }
        },
        yaxis: {
            title: {
                text: 'Total Amount (Rp)',
                style: {
                    color: '#ffffff',
                    fontSize: isSmallScreen ? '8px' : '16px', // Mengatur ukuran teks berdasarkan ukuran layar
                }
            },
            labels: {
                style: {
                    colors: '#ffffff',
                    fontSize: isSmallScreen ? '8px' : '12px',
                },
                formatter: function (val) {
                    return 'Rp. ' + val;
                }
            }
        },
        fill: {
            opacity: 1,
            colors: ['#1A56DB']
        },
        tooltip: {
            theme: 'dark',
            y: {
                formatter: function (val) {
                    return 'Rp. ' + val;
                },
            },
        },
        grid: {
            borderColor: '#ffffff20',
        },
    };

    // Membuat dan merender chart
    const chart = new ApexCharts(document.querySelector("#column-chart"), options);
    chart.render();
});
</script>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analisis Kesiapan dan Kelayakan Alutsista TNI Berbasis Data Persenjataan Global</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        googleBlue: '#4285F4',
                        googleRed: '#EA4335',
                        googleYellow: '#FBBC05',
                        googleGreen: '#34A853',
                        googlePurple: '#A142F4',
                        googleTeal: '#24C1E0',
                        bgBase: '#f3f4f6'
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .dashboard-card {
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px 0 rgba(0, 0, 0, 0.03);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .dashboard-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px -8px rgba(0, 0, 0, 0.15);
            border-color: #d1d5db;
        }
    </style>
</head>
<body class="text-gray-800 antialiased min-h-screen flex flex-col">

    <!-- Top Navigation / Header (Edge to Edge) -->
    <header class="bg-white border-b border-gray-200 px-6 py-4 flex flex-col sm:flex-row justify-between items-center w-full shadow-sm sticky top-0 z-50">
        <div>
            <h1 class="text-xl font-bold text-gray-900 tracking-tight">Analisa Persenjataan Indonesia</h1>
            <p class="text-sm text-gray-500 mt-0.5">Tugas Besar Analitik dan Visualisasi Data</p>
        </div>
        
        <!-- Global Filters -->
        <div class="mt-4 sm:mt-0 flex space-x-3 w-full sm:w-auto">
            <div class="flex flex-col">
                <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wider">Kategori</label>
                <select id="filter-category" class="bg-white border border-gray-300 text-gray-700 text-sm rounded-md focus:ring-googleBlue focus:border-googleBlue block p-2 outline-none w-full sm:w-48 shadow-sm">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col">
                <label class="text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wider">Tahun</label>
                <select id="filter-year" class="bg-white border border-gray-300 text-gray-700 text-sm rounded-md focus:ring-googleBlue focus:border-googleBlue block p-2 outline-none w-full sm:w-40 shadow-sm">
                    <option value="">Semua Tahun</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </header>

    <!-- Tab Navigation -->
    <div class="bg-white border-b border-gray-200 px-6 w-full flex items-center space-x-6 text-sm font-medium overflow-x-auto">
        <a href="/dashboard/global" class="text-gray-500 hover:text-gray-700 py-3 border-b-2 border-transparent transition-colors whitespace-nowrap">Global Analytics</a>
        <a href="/dashboard/indonesia" class="text-googleBlue py-3 border-b-2 border-googleBlue whitespace-nowrap">Indonesia Analytics</a>
        <a href="/dashboard/eda" class="text-gray-500 hover:text-gray-700 py-3 border-b-2 border-transparent transition-colors whitespace-nowrap">EDA & Data Cleaning</a>
    </div>

    <!-- Main Content (Full Width) -->
    <main class="w-full px-4 sm:px-6 lg:px-8 py-6 flex-grow flex flex-col space-y-6">
        
        <!-- Scorecards / KPIs -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="dashboard-card p-5 flex items-center gap-4" data-aos="fade-up" data-aos-delay="0">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-googleBlue flex items-center justify-center text-2xl shrink-0">
                    <i class="ph-fill ph-crosshair"></i>
                </div>
                <div>
                    <h3 class="text-gray-500 text-[11px] font-bold uppercase tracking-wider mb-1">Total Inventaris Persenjataan</h3>
                    <div class="flex items-end">
                        <span class="text-3xl font-bold text-gray-900 leading-none" id="kpi-total">-</span>
                        <span class="ml-1 text-sm text-gray-500 font-medium">Sistem</span>
                    </div>
                </div>
            </div>
            
            <div class="dashboard-card p-5 flex items-center gap-4" data-aos="fade-up" data-aos-delay="100">
                <div class="w-12 h-12 rounded-xl bg-green-50 text-googleGreen flex items-center justify-center text-2xl shrink-0">
                    <i class="ph-fill ph-currency-dollar"></i>
                </div>
                <div>
                    <h3 class="text-gray-500 text-[11px] font-bold uppercase tracking-wider mb-1">Rata-rata Biaya Pengadaan</h3>
                    <div class="flex items-end">
                        <span class="text-3xl font-bold text-gray-900 leading-none" id="kpi-cost">-</span>
                        <span class="ml-1 text-sm text-gray-500 font-medium">Per Unit</span>
                    </div>
                </div>
            </div>
            
            <div class="dashboard-card p-5 flex items-center gap-4" data-aos="fade-up" data-aos-delay="200">
                <div class="w-12 h-12 rounded-xl bg-red-50 text-googleRed flex items-center justify-center text-2xl shrink-0">
                    <i class="ph-fill ph-fire"></i>
                </div>
                <div>
                    <h3 class="text-gray-500 text-[11px] font-bold uppercase tracking-wider mb-1">Total Teruji Tempur</h3>
                    <div class="flex items-end">
                        <span class="text-3xl font-bold text-gray-900 leading-none" id="kpi-proven">-</span>
                        <span class="ml-1 text-sm text-gray-500 font-medium">Sistem</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Grid 1 -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 h-auto">
            <!-- Stacked Bar Chart -->
            <div class="dashboard-card p-6 flex flex-col" data-aos="fade-up" data-aos-delay="300">
                <div class="flex justify-between items-start mb-1">
                    <h3 class="text-base font-semibold text-gray-800">Laporan penggunaan senjata berdasarkan matra/angkatan laut/darat/udara</h3>
                    <select id="sort-bar" class="text-xs border border-gray-300 text-gray-600 rounded p-1 outline-none focus:ring-1 focus:ring-googleBlue" onchange="renderBarChart()">
                        <option value="name_asc">A-Z</option>
                        <option value="name_desc">Z-A</option>
                        <option value="total_desc">Total Terbanyak</option>
                        <option value="total_asc">Total Terkecil</option>
                    </select>
                </div>
                <p class="text-xs text-gray-500 mb-4">Distribusi kategori aset per angkatan (Darat, Laut, Udara)</p>
                <div class="relative w-full" style="height: 350px;">
                    <canvas id="barChart"></canvas>
                </div>
            </div>

            <!-- Line / Area Chart -->
            <div class="dashboard-card p-6 flex flex-col" data-aos="fade-up" data-aos-delay="400">
                <div class="flex justify-between items-start mb-1">
                    <h3 class="text-base font-semibold text-gray-800">Tren Pengadaan Berdasarkan Tahun</h3>
                    <select id="sort-line" class="text-xs border border-gray-300 text-gray-600 rounded p-1 outline-none focus:ring-1 focus:ring-googleBlue" onchange="renderLineChart()">
                        <option value="year_asc">Tahun Lama-Baru</option>
                        <option value="year_desc">Tahun Baru-Lama</option>
                    </select>
                </div>
                <p class="text-xs text-gray-500 mb-4">Volume pengenalan persenjataan dari masa ke masa</p>
                <div class="relative w-full" style="height: 350px;">
                    <canvas id="lineChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Charts Grid 2 -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 h-auto">
            <!-- Pie Chart -->
            <div class="dashboard-card p-6 flex flex-col col-span-1" data-aos="fade-up" data-aos-delay="100">
                <div class="flex justify-between items-start mb-1">
                    <h3 class="text-base font-semibold text-gray-800">Status Teruji Tempur</h3>
                    <select id="sort-pie" class="text-xs border border-gray-300 text-gray-600 rounded p-1 outline-none focus:ring-1 focus:ring-googleBlue" onchange="renderPieChart()">
                        <option value="total_desc">Jumlah Terbanyak</option>
                        <option value="total_asc">Jumlah Terkecil</option>
                    </select>
                </div>
                <p class="text-xs text-gray-500 mb-4">Proporsi sistem yang telah tervalidasi</p>
                <div class="relative w-full flex-grow flex justify-center items-center" style="height: 320px;">
                    <canvas id="pieChart"></canvas>
                </div>
            </div>

            <!-- Scatter Plot -->
            <div class="dashboard-card p-6 flex flex-col col-span-1 lg:col-span-2" data-aos="fade-up" data-aos-delay="200">
                <h3 class="text-base font-semibold text-gray-800 mb-1">Analisis Value for Money (Harga vs Adopsi Global)</h3>
                <p class="text-xs text-gray-500 mb-4">Korelasi antara biaya unit (skala logaritmik) dengan jumlah negara operator</p>
                <div class="relative w-full flex-grow" style="height: 320px;">
                    <canvas id="scatterChart"></canvas>
                </div>
            </div>
        </div>
        
        <!-- NEW SECTION: Analisis Lanjutan & Wawasan AI -->
        <div class="mt-8 border-t border-gray-200 pt-8" data-aos="fade-up">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-16 h-16 shrink-0 bg-gradient-to-br from-blue-50 to-purple-50 rounded-2xl flex items-center justify-center shadow-inner border border-blue-100">
                    <i class="ph-fill ph-brain text-3xl text-googlePurple animate-bounce"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900 tracking-tight">Analisis Lanjutan & Wawasan AI</h2>
                    <p class="text-sm text-gray-500">Menganalisis kapabilitas lintas-matra dan model prediktif Machine Learning (Random Forest) untuk inventaris TNI</p>
                </div>
            </div>
            
            <!-- INFO CARDS (Data, Algoritma, Insight) -->
            <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-blue-50/50 border border-blue-100 p-4 rounded-lg transition-all duration-300 hover:shadow-md hover:-translate-y-1">
                    <h4 class="font-bold text-blue-800 text-xs uppercase mb-1 flex items-center"><i class="ph-fill ph-database mr-1"></i> Perbedaan Sumber Data</h4>
                    <p class="text-xs text-gray-600 leading-relaxed text-justify">Grafik <strong>Radar & Usia</strong> di bawah ini difilter eksklusif khusus alutsista <strong>Indonesia</strong>. Namun, metrik <strong>Model AI</strong> tetap dilatih menggunakan 10.000+ populasi <strong>Global</strong> agar model dapat mengidentifikasi pola kelayakan tempur yang valid dan tidak ter-<em>overfitting</em> oleh minimnya data lokal.</p>
                </div>
                <div class="bg-purple-50/50 border border-purple-100 p-4 rounded-lg transition-all duration-300 hover:shadow-md hover:-translate-y-1">
                    <h4 class="font-bold text-purple-800 text-xs uppercase mb-1 flex items-center"><i class="ph-fill ph-brain mr-1"></i> Algoritma & Justifikasi</h4>
                    <p class="text-xs text-gray-600 leading-relaxed text-justify">Selain algoritma agregasi <em>Single-Pass Mapping</em>, proyek ini dielevasi menggunakan <strong>Random Forest Classifier</strong>. Model AI ini jauh lebih unggul daripada model linier konvensional dalam memproses data dengan nilai outlier (seperti perbedaan drastis antar harga alutsista).</p>
                </div>
                <div class="bg-green-50/50 border border-green-100 p-4 rounded-lg transition-all duration-300 hover:shadow-md hover:-translate-y-1">
                    <h4 class="font-bold text-green-800 text-xs uppercase mb-1 flex items-center"><i class="ph-fill ph-lightbulb mr-1"></i> Kesimpulan & Insight TNI</h4>
                    <p class="text-xs text-gray-600 leading-relaxed text-justify">Analisis radar memvalidasi temuan bahwa postur TNI melemah pada indikator <strong>Modernisasi</strong>, sejalan dengan tingginya porsi alutsista Usang (>30 tahun). Berdasarkan standar kelayakan AI global, peremajaan alutsista (khususnya matra laut) merupakan prioritas mutlak.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 h-auto">
                <!-- Radar Chart -->
                <div class="dashboard-card p-6 flex flex-col col-span-1" data-aos="zoom-in" data-aos-delay="0">
                    <h3 class="text-base font-semibold text-gray-800 mb-1">Kapabilitas & Tolok Ukur (Radar)</h3>
                    <p class="text-xs text-gray-500 mb-4">Analisis multi-variabel kapabilitas alutsista (Volume, Kelayakan, dll)</p>
                    <div class="relative w-full flex-grow flex justify-center items-center" style="height: 320px;">
                        <canvas id="radarChart"></canvas>
                    </div>
                </div>

                <!-- Age Composition -->
                <div class="dashboard-card p-6 flex flex-col col-span-1" data-aos="zoom-in" data-aos-delay="150">
                    <h3 class="text-base font-semibold text-gray-800 mb-1">Komposisi Usia per Matra</h3>
                    <p class="text-xs text-gray-500 mb-4">Distribusi alutsista Modern (< 10 thn) vs Usang (> 30 thn)</p>
                    <div class="relative w-full flex-grow flex justify-center items-center" style="height: 320px;">
                        <canvas id="ageChart"></canvas>
                    </div>
                </div>

                <!-- ML Feature Importance -->
                <div class="dashboard-card p-6 flex flex-col col-span-1" data-aos="zoom-in" data-aos-delay="300">
                    <h3 class="text-base font-semibold text-gray-800 mb-1">Variabel Paling Berpengaruh terhadap Prediksi Status Combat Proven</h3>
                    <p class="text-xs text-gray-500 mb-4">Model Machine Learning: Random Forest Classifier</p>
                    <div class="relative w-full flex-grow flex justify-center items-center" style="height: 320px;">
                        <canvas id="mlChart"></canvas>
                    </div>
                    <div class="mt-4 p-3 bg-purple-50/70 rounded-lg border border-purple-100">
                        <div id="ml-accuracy" class="text-xs font-bold text-googlePurple text-center mb-2">Loading AI Model...</div>
                        <p class="text-[10px] text-gray-500 leading-relaxed text-justify italic">
                            *Catatan Akademis: Model ini bersifat eksploratif (Pattern Recognition). Akurasi model mengindikasikan bahwa kelayakan tempur turut dipengaruhi variabel eksternal (geopolitik, strategi militer) di luar dataset. Fitur ini berfungsi sebagai insight pendukung, bukan dasar mutlak keputusan pengadaan alutsista.
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <script>
        // Konfigurasi Dasar Grafik
        Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
        Chart.defaults.color = '#6b7280'; // gray-500
        Chart.defaults.scale.grid.color = '#f3f4f6'; // gray-100
        Chart.defaults.scale.grid.borderColor = '#e5e7eb'; // gray-200
        
        const tooltipDefaults = {
            backgroundColor: 'rgba(255, 255, 255, 0.95)',
            titleColor: '#111827',
            bodyColor: '#374151',
            borderColor: '#e5e7eb',
            borderWidth: 1,
            padding: 10,
            boxPadding: 4,
            usePointStyle: true,
            titleFont: { size: 13, weight: 'bold' },
            bodyFont: { size: 12 }
        };

        let charts = {};
        let currentData = null; // Menyimpan data hasil fetch secara global untuk fitur sorting

        async function fetchDashboardData() {
            const category = document.getElementById('filter-category').value;
            const year = document.getElementById('filter-year').value;
            
            const url = `/dashboard/data?scope=indonesia&category=${encodeURIComponent(category)}&year=${encodeURIComponent(year)}`;
            
            try {
                const response = await fetch(url);
                currentData = await response.json();
                
                updateKPIs(currentData.kpi);
                
                // Gambar ulang grafik dengan filter aktif
                renderBarChart();
                renderLineChart();
                renderPieChart();
                renderScatterChart();
                
                // Analitik Lanjutan
                renderAgeChart();
                renderRadarChart();
                
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        }

        async function fetchMLData() {
            try {
                const response = await fetch('/data/ml_insight.json');
                const data = await response.json();
                renderMLChart(data);
            } catch (error) {
                console.log("ML Insight not found or not generated yet.", error);
                document.getElementById('ml-accuracy').innerText = "Model JSON belum digenerate.";
            }
        }

        // --- LOGIKA PENGURUTAN (SORTING LOGIC) --- //

        function renderCategoryBarChart() {
            if (!currentData || !currentData.categoryBarChart) return;
            const sortOrder = document.getElementById('sort-category-bar').value;
            
            let labels = [...currentData.categoryBarChart.labels];
            let values = [...currentData.categoryBarChart.values];

            let items = labels.map((label, i) => ({ label, value: values[i] }));
            
            if (sortOrder === 'name_asc') items.sort((a, b) => a.label.localeCompare(b.label));
            else if (sortOrder === 'name_desc') items.sort((a, b) => b.label.localeCompare(a.label));
            else if (sortOrder === 'total_desc') items.sort((a, b) => b.value - a.value);
            else if (sortOrder === 'total_asc') items.sort((a, b) => a.value - b.value);

            updateCategoryBarChart(items.map(it => it.label), items.map(it => it.value));
        }

        function renderBarChart() {
            if (!currentData) return;
            const sortOrder = document.getElementById('sort-bar').value;
            
            // Salin data untuk menghindari mutasi (perubahan) pada data asli
            let labels = [...currentData.barChart.labels];
            let datasets = JSON.parse(JSON.stringify(currentData.barChart.datasets)); 

            // Gabungkan menjadi objek agar mudah diurutkan
            let items = labels.map((label, i) => {
                let total = datasets.reduce((sum, ds) => sum + ds.data[i], 0);
                return { label, total, data: datasets.map(ds => ds.data[i]) };
            });

            // Logika Pengurutan
            if (sortOrder === 'name_asc') items.sort((a, b) => a.label.localeCompare(b.label));
            else if (sortOrder === 'name_desc') items.sort((a, b) => b.label.localeCompare(a.label));
            else if (sortOrder === 'total_desc') items.sort((a, b) => b.total - a.total);
            else if (sortOrder === 'total_asc') items.sort((a, b) => a.total - b.total);

            // Ekstrak kembali data yang sudah diurutkan
            let sortedLabels = items.map(it => it.label);
            datasets.forEach((ds, dsIndex) => {
                ds.data = items.map(it => it.data[dsIndex]);
            });

            updateBarChart(sortedLabels, datasets);
        }

        function renderLineChart() {
            if (!currentData) return;
            const sortOrder = document.getElementById('sort-line').value;
            
            let labels = [...currentData.lineChart.labels];
            let values = [...currentData.lineChart.values];

            let items = labels.map((label, i) => ({ label, value: values[i] }));
            
            // Logika Pengurutan
            if (sortOrder === 'year_asc') items.sort((a, b) => a.label.localeCompare(b.label));
            else if (sortOrder === 'year_desc') items.sort((a, b) => b.label.localeCompare(a.label));

            updateLineChart(items.map(it => it.label), items.map(it => it.value));
        }

        function renderPieChart() {
            if (!currentData) return;
            const sortOrder = document.getElementById('sort-pie').value;
            
            let labels = [...currentData.pieChart.labels];
            let values = [...currentData.pieChart.values];

            let items = labels.map((label, i) => ({ label, value: values[i] }));
            
            // Logika Pengurutan
            if (sortOrder === 'total_desc') items.sort((a, b) => b.value - a.value);
            else if (sortOrder === 'total_asc') items.sort((a, b) => a.value - b.value);

            updatePieChart(items.map(it => it.label), items.map(it => it.value));
        }

        function renderScatterChart() {
            if (!currentData) return;
            // Titik koordinat scatter bersifat mutlak, tidak perlu pengurutan
            updateScatterChart(currentData.scatterChart.points);
        }

        // --- MENGGAMBAR GRAFIK (RENDER CHARTS) --- //

        function updateKPIs(kpi) {
            document.getElementById('kpi-total').innerText = kpi.total_weapons;
            document.getElementById('kpi-cost').innerText = kpi.avg_cost;
            document.getElementById('kpi-proven').innerText = kpi.combat_proven;
        }

        // Grafik batang kategori tidak digunakan di halaman ini

        function updateBarChart(labels, datasets) {
            const ctx = document.getElementById('barChart').getContext('2d');
            if(charts.bar) charts.bar.destroy();
            
            // Timpa warna bawaan untuk menyesuaikan dengan palet Indonesia
            datasets.forEach(ds => {
                if(ds.label === 'Land') ds.backgroundColor = '#4285F4'; // Biru Google
                if(ds.label === 'Air') ds.backgroundColor = '#F6B26B'; // Oranye
                if(ds.label === 'Sea') ds.backgroundColor = '#B4A7D6'; // Ungu
                ds.borderWidth = 0;
            });

            charts.bar = new Chart(ctx, {
                type: 'bar',
                data: { labels, datasets },
                options: {
                    indexAxis: 'y', // Mengubah grafik batang menjadi horizontal
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8 } },
                        tooltip: tooltipDefaults
                    },
                    scales: {
                        x: { stacked: true, beginAtZero: true, border: { dash: [4, 4] } },
                        y: { stacked: true, grid: { display: false } }
                    }
                }
            });
        }

        function updateLineChart(labels, values) {
            const ctx = document.getElementById('lineChart').getContext('2d');
            if(charts.line) charts.line.destroy();

            charts.line = new Chart(ctx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        label: 'Volume Pengadaan',
                        data: values,
                        borderColor: '#4285F4',
                        backgroundColor: 'rgba(66, 133, 244, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#4285F4',
                        pointBorderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: { 
                        legend: { display: false },
                        tooltip: tooltipDefaults
                    },
                    scales: { 
                        x: { grid: { display: false } },
                        y: { beginAtZero: true, border: { dash: [4, 4] } } 
                    }
                }
            });
        }

        function updatePieChart(labels, values) {
            const ctx = document.getElementById('pieChart').getContext('2d');
            if(charts.pie) charts.pie.destroy();
            
            const bgColors = labels.map(l => {
                if(l === 'No') return '#4285F4'; // Biru Google
                if(l === 'Yes') return '#F6B26B';  // Oranye
                if(l === 'Limited') return '#B4A7D6'; // Ungu
                return '#9ca3af'; // Abu-abu
            });

            charts.pie = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels,
                    datasets: [{
                        data: values,
                        backgroundColor: bgColors,
                        borderWidth: 2,
                        borderColor: '#ffffff',
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } },
                        tooltip: tooltipDefaults
                    }
                }
            });
        }

        function updateScatterChart(points) {
            const ctx = document.getElementById('scatterChart').getContext('2d');
            if(charts.scatter) charts.scatter.destroy();
            
            charts.scatter = new Chart(ctx, {
                type: 'scatter',
                data: {
                    datasets: [{
                        label: 'Sistem Persenjataan',
                        data: points,
                        backgroundColor: 'rgba(66, 133, 244, 0.5)',
                        borderColor: '#4285F4',
                        borderWidth: 1,
                        pointRadius: 4,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            ...tooltipDefaults,
                            callbacks: {
                                label: function(context) {
                                    let point = context.raw;
                                    return [
                                        point.name,
                                        'Harga: $' + point.x.toLocaleString(),
                                        'Pengguna: ' + point.y + ' negara'
                                    ];
                                }
                            }
                        }
                    },
                    scales: {
                        x: { 
                            title: { display: true, text: 'Harga per Unit (USD) - Skala Logaritmik', color: '#6b7280', font: {size: 11} },
                            type: 'logarithmic',
                            grid: { color: '#f3f4f6' }
                        },
                        y: { 
                            title: { display: true, text: 'Jumlah Negara Pengguna', color: '#6b7280', font: {size: 11} },
                            beginAtZero: true,
                            grid: { color: '#f3f4f6' }
                        }
                    }
                }
            });
        }

        // --- MENGGAMBAR GRAFIK LANJUTAN (AI & RADAR) --- //

        function renderAgeChart() {
            if (!currentData || !currentData.ageChart) return;
            const ctx = document.getElementById('ageChart').getContext('2d');
            if(charts.age) charts.age.destroy();
            
            let datasets = JSON.parse(JSON.stringify(currentData.ageChart.datasets));
            
            // Tetapkan warna berdasarkan kelompok usia (Menyesuaikan palet Indonesia)
            datasets.forEach(ds => {
                if(ds.label.includes('Modern')) ds.backgroundColor = '#4285F4'; // Biru Google
                if(ds.label.includes('Menengah')) ds.backgroundColor = '#F6B26B'; // Oranye
                if(ds.label.includes('Usang')) ds.backgroundColor = '#B4A7D6'; // Ungu
                ds.borderWidth = 0;
            });

            charts.age = new Chart(ctx, {
                type: 'bar',
                data: { labels: ['Darat', 'Udara', 'Laut'], datasets: datasets },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { tooltip: tooltipDefaults, legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8 } } },
                    scales: {
                        x: { stacked: true, grid: { display: false } },
                        y: { stacked: true, border: { dash: [4, 4] } }
                    }
                }
            });
        }

        function renderRadarChart() {
            if (!currentData || !currentData.radarChart) return;
            const ctx = document.getElementById('radarChart').getContext('2d');
            if(charts.radar) charts.radar.destroy();
            
            charts.radar = new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: currentData.radarChart.labels,
                    datasets: currentData.radarChart.datasets.map(ds => ({
                        ...ds,
                        backgroundColor: 'rgba(246, 178, 107, 0.2)', // Oranye transparan
                        borderColor: '#F6B26B', // Oranye
                        pointBackgroundColor: '#F6B26B',
                        borderWidth: 2,
                    }))
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { 
                        r: { 
                            min: 0, max: 100, 
                            ticks: { display: false },
                            grid: { color: '#e5e7eb' },
                            angleLines: { color: '#e5e7eb' }
                        } 
                    },
                    plugins: { tooltip: tooltipDefaults, legend: { display: false } }
                }
            });
        }

        function renderMLChart(mlData) {
            const ctx = document.getElementById('mlChart').getContext('2d');
            if(charts.ml) charts.ml.destroy();
            
            document.getElementById('ml-accuracy').innerHTML = `<i class="ph-fill ph-check-circle mr-1"></i>Akurasi Model Scikit-Learn: ${mlData.accuracy}%`;

            charts.ml = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: mlData.insights.map(i => i.factor),
                    datasets: [{
                        label: 'Tingkat Pengaruh (%)',
                        data: mlData.insights.map(i => i.importance_score),
                        backgroundColor: '#B4A7D6', // Purple to match theme
                        borderRadius: 4,
                        barPercentage: 0.6
                    }]
                },
                options: {
                    indexAxis: 'y', // Batang Horizontal
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false }, tooltip: tooltipDefaults },
                    scales: {
                        x: { beginAtZero: true, max: 100, border: { dash: [4, 4] } },
                        y: { grid: { display: false } }
                    }
                }
            });
        }

        // Inisialisasi dan pendaftaran pendengar aksi (event listeners)
        document.addEventListener('DOMContentLoaded', () => {
            // Inisialisasi Animasi AOS
            AOS.init({
                duration: 800,
                easing: 'ease-out-cubic',
                once: false,
                mirror: true,
                offset: 50
            });

            fetchDashboardData();
            fetchMLData();
            
            document.getElementById('filter-category').addEventListener('change', fetchDashboardData);
            document.getElementById('filter-year').addEventListener('change', fetchDashboardData);
        });
    </script>
</body>
</html>
<x-app-layout>
    <div class="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto">

        <!-- HERO GREETING BANNER -->
        <div class="mb-8 rounded-3xl bg-gradient-to-br from-indigo-600 via-indigo-700 to-violet-800 p-6 sm:p-8 text-white relative overflow-hidden shadow-xl shadow-indigo-200/50">
            <!-- Decorative circles -->
            <div class="absolute -right-10 -top-10 w-48 h-48 bg-white/5 rounded-full"></div>
            <div class="absolute right-16 -bottom-12 w-36 h-36 bg-white/5 rounded-full"></div>
            <div class="absolute left-1/2 -top-8 w-24 h-24 bg-white/5 rounded-full"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <p class="text-indigo-200 text-sm font-medium mb-1">Dashboard Wakil Kepala Sekolah</p>
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight">Selamat Datang, {{ ucwords(auth()->user()->name) }} 👋</h1>
                    <p class="text-indigo-200/80 text-sm mt-2 max-w-md">Pantau ringkasan data sekolah, kehadiran guru & siswa, serta laporan terkini dari satu tampilan.</p>
                </div>
                <div class="flex flex-wrap items-center gap-4">
                    <div class="bg-white/10 backdrop-blur-sm text-white px-4 py-2 rounded-xl text-sm font-medium border border-white/10 flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, DD MMMM YYYY') }}</span>
                    </div>
                    @if($tahunAktif)
                    <div class="bg-emerald-400/20 backdrop-blur-sm text-emerald-100 px-4 py-2 rounded-xl text-sm font-medium border border-emerald-300/20 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        <span>TA {{ $tahunAktif->nama_tahun }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- STATS GRID -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 mb-8">
            <!-- Siswa Hadir Hari Ini -->
            <div class="group bg-white rounded-2xl border border-slate-200/60 shadow-sm p-5 sm:p-6 hover:shadow-xl hover:shadow-indigo-100/60 hover:border-indigo-200/80 transition-all duration-300 relative overflow-hidden">
                <div class="absolute -right-8 -top-8 w-28 h-28 bg-gradient-to-br from-indigo-100/60 to-transparent rounded-full group-hover:scale-[1.8] transition-transform duration-700 ease-out"></div>
                <div class="relative z-10">
                    <div class="w-11 h-11 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300 mb-4 border border-indigo-100/50 group-hover:border-transparent shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
                    </div>
                    <p class="text-2xl sm:text-3xl font-bold text-slate-800 tracking-tight leading-none">{{ $siswaHadirHariIni }}<span class="text-base sm:text-lg text-slate-400 font-semibold"> / {{ $siswaAktif }}</span></p>
                    <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1.5">Siswa Hadir Hari Ini</p>
                </div>
            </div>

            <!-- Guru Hadir Hari Ini -->
            <div class="group bg-white rounded-2xl border border-slate-200/60 shadow-sm p-5 sm:p-6 hover:shadow-xl hover:shadow-emerald-100/60 hover:border-emerald-200/80 transition-all duration-300 relative overflow-hidden">
                <div class="absolute -right-8 -top-8 w-28 h-28 bg-gradient-to-br from-emerald-100/60 to-transparent rounded-full group-hover:scale-[1.8] transition-transform duration-700 ease-out"></div>
                <div class="relative z-10">
                    <div class="w-11 h-11 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300 mb-4 border border-emerald-100/50 group-hover:border-transparent shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"/></svg>
                    </div>
                    <p class="text-2xl sm:text-3xl font-bold text-slate-800 tracking-tight leading-none">{{ $guruHadirHariIni }}<span class="text-base sm:text-lg text-slate-400 font-semibold"> / {{ $totalGuru }}</span></p>
                    <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1.5">Guru Hadir Hari Ini</p>
                </div>
            </div>

            <!-- Total Kelas -->
            <div class="group bg-white rounded-2xl border border-slate-200/60 shadow-sm p-5 sm:p-6 hover:shadow-xl hover:shadow-amber-100/60 hover:border-amber-200/80 transition-all duration-300 relative overflow-hidden">
                <div class="absolute -right-8 -top-8 w-28 h-28 bg-gradient-to-br from-amber-100/60 to-transparent rounded-full group-hover:scale-[1.8] transition-transform duration-700 ease-out"></div>
                <div class="relative z-10">
                    <div class="w-11 h-11 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center group-hover:bg-amber-500 group-hover:text-white transition-colors duration-300 mb-4 border border-amber-100/50 group-hover:border-transparent shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21"/></svg>
                    </div>
                    <p class="text-2xl sm:text-3xl font-bold text-slate-800 tracking-tight leading-none">{{ $totalKelas }}</p>
                    <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1.5">Total Kelas</p>
                </div>
            </div>

            <!-- Mata Pelajaran -->
            <div class="group bg-white rounded-2xl border border-slate-200/60 shadow-sm p-5 sm:p-6 hover:shadow-xl hover:shadow-rose-100/60 hover:border-rose-200/80 transition-all duration-300 relative overflow-hidden">
                <div class="absolute -right-8 -top-8 w-28 h-28 bg-gradient-to-br from-rose-100/60 to-transparent rounded-full group-hover:scale-[1.8] transition-transform duration-700 ease-out"></div>
                <div class="relative z-10">
                    <div class="w-11 h-11 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center group-hover:bg-rose-500 group-hover:text-white transition-colors duration-300 mb-4 border border-rose-100/50 group-hover:border-transparent shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
                    </div>
                    <p class="text-2xl sm:text-3xl font-bold text-slate-800 tracking-tight leading-none">{{ $totalMapel }}</p>
                    <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1.5">Mata Pelajaran</p>
                </div>
            </div>
        </div>

        <!-- MAIN CONTENT AREA -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">

            <!-- QUICK LINKS -->
            <div class="lg:col-span-1 space-y-3">
                <h2 class="text-base font-bold text-slate-700 mb-3 flex items-center gap-2 px-1">
                    <div class="w-7 h-7 bg-indigo-50 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    Akses Cepat
                </h2>

                <a href="{{ route('wakepsek.laporan.siswa.index') }}" class="group block bg-white rounded-2xl border border-slate-200/60 p-4 sm:p-5 shadow-sm hover:shadow-lg hover:border-emerald-200 transition-all duration-300 hover:-translate-y-0.5">
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center text-white shadow-md shadow-emerald-200 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/></svg>
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-bold text-slate-800 text-sm group-hover:text-emerald-700 transition-colors">Laporan Siswa</h3>
                            <p class="text-slate-400 text-xs mt-0.5">Lihat rekap absensi siswa</p>
                        </div>
                        <svg class="w-4 h-4 text-slate-300 ml-auto group-hover:text-emerald-400 group-hover:translate-x-0.5 transition-all shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </a>

                <a href="{{ route('wakepsek.laporan.guru.index') }}" class="group block bg-white rounded-2xl border border-slate-200/60 p-4 sm:p-5 shadow-sm hover:shadow-lg hover:border-amber-200 transition-all duration-300 hover:-translate-y-0.5">
    <div class="flex items-center gap-4">
        <div class="w-11 h-11 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center text-white shadow-md shadow-emerald-200 shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/></svg>
        </div>
        <div class="min-w-0">
            <h3 class="font-bold text-slate-800 text-sm group-hover:text-amber-700 transition-colors">Laporan Guru</h3>
            <p class="text-slate-400 text-xs mt-0.5">Lihat rekap absensi guru</p>
        </div>
        <svg class="w-4 h-4 text-slate-300 ml-auto group-hover:text-amber-400 group-hover:translate-x-0.5 transition-all shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
    </div>
</a>
            </div>

            <!-- CHART SECTION -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl border border-slate-200/60 p-5 sm:p-6 shadow-sm h-full min-h-[380px] flex flex-col">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
                        <div class="flex items-center gap-4">
                            <div class="w-8 h-8 bg-indigo-50 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            </div>
                            <h2 class="text-base font-bold text-slate-700">Grafik Kehadiran Kelas</h2>
                        </div>
                        <select id="chartFilter" class="appearance-none bg-slate-50 border border-slate-200 text-slate-600 text-sm rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 py-2 pl-4 pr-10 min-w-[180px] cursor-pointer transition-colors hover:bg-slate-100" style="background-image: url(&quot;data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e&quot;); background-position: right 0.75rem center; background-repeat: no-repeat; background-size: 1.25em 1.25em;">
                            <option value="today">Hari Ini</option>
                            <option value="week">Minggu Ini</option>
                            <option value="month">Bulan Ini</option>
                            <option value="year">TA {{ $tahunAktif ? $tahunAktif->nama_tahun : 'Aktif' }}</option>
                        </select>
                    </div>

                    <div class="flex-1 w-full min-h-[300px]">
                        <canvas id="attendanceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    @vite('resources/js/chart-init.js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('attendanceChart').getContext('2d');
            const filterSelect = document.getElementById('chartFilter');
            let attendanceChart = null;

            function loadChartData(filter) {
                fetch(`{{ route('wakepsek.dashboard.chart') }}?filter=${filter}`)
                    .then(response => response.json())
                    .then(data => {
                        if (attendanceChart) {
                            attendanceChart.destroy();
                        }

                        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
                        gradient.addColorStop(0, 'rgba(99, 102, 241, 0.75)');
                        gradient.addColorStop(1, 'rgba(99, 102, 241, 0.05)');

                        attendanceChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: data.labels,
                                datasets: [{
                                    label: 'Persentase Kehadiran (%)',
                                    data: data.data,
                                    backgroundColor: gradient,
                                    borderColor: 'rgba(99, 102, 241, 0.9)',
                                    borderWidth: 1,
                                    borderRadius: 8,
                                    borderSkipped: false,
                                    barPercentage: 0.55,
                                    categoryPercentage: 0.8
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                animation: {
                                    duration: 600,
                                    easing: 'easeOutQuart'
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        max: 100,
                                        ticks: {
                                            callback: v => v + '%',
                                            font: { size: 12, weight: '500' },
                                            color: '#94a3b8',
                                            padding: 8
                                        },
                                        grid: {
                                            color: 'rgba(241, 245, 249, 1)',
                                            drawBorder: false
                                        },
                                        border: { display: false }
                                    },
                                    x: {
                                        ticks: {
                                            font: { size: 11, weight: '500' },
                                            color: '#64748b',
                                            padding: 4
                                        },
                                        grid: { display: false },
                                        border: { display: false }
                                    }
                                },
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        backgroundColor: 'rgba(30, 41, 59, 0.95)',
                                        titleFont: { size: 13, weight: '600' },
                                        bodyFont: { size: 12 },
                                        padding: { x: 14, y: 10 },
                                        cornerRadius: 10,
                                        displayColors: false,
                                        callbacks: {
                                            label: ctx => ctx.parsed.y + '% Hadir'
                                        }
                                    }
                                }
                            }
                        });
                    });
            }

            loadChartData(filterSelect.value);

            filterSelect.addEventListener('change', function() {
                loadChartData(this.value);
            });
        });
    </script>
    @endpush
</x-app-layout>

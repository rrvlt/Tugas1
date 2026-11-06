<div class="stats stats-four">
<x-card title="Total mahasiswa" :value="$stats['total']" icon="◎"><p class="stat-caption">Seluruh data terdaftar</p></x-card>
<x-card title="Mahasiswa aktif" :value="$stats['aktif']" icon="✓"><p class="stat-caption"><span class="status-dot active"></span> Mengikuti perkuliahan</p></x-card>
<x-card title="Mahasiswa cuti" :value="$stats['cuti']" icon="Ⅱ"><p class="stat-caption"><span class="status-dot leave"></span> Cuti akademik</p></x-card>
<x-card title="Mahasiswa nonaktif" :value="$stats['nonaktif']" icon="○"><p class="stat-caption"><span class="status-dot inactive"></span> Tidak aktif kuliah</p></x-card>
</div>

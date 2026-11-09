@csrf
<div class="form-grid">
<div class="field"><label for="nim">NIM *</label>
<input id="nim" name="nim" type="text" value="{{ old('nim', $item->nim) }}" required maxlength="20">
@error('nim')<p class="error">{{ $message }}</p>@enderror</div>
<div class="field"><label for="nama">Nama mahasiswa *</label>
<input id="nama" name="nama" type="text" value="{{ old('nama', $item->nama) }}" required maxlength="100">
@error('nama')<p class="error">{{ $message }}</p>@enderror</div>
<div class="field"><label for="email">Email *</label>
<input id="email" name="email" type="email" value="{{ old('email', $item->email) }}" required maxlength="150">
@error('email')<p class="error">{{ $message }}</p>@enderror</div>
<div class="field"><label for="program_studi_id">Program Studi *</label><select id="program_studi_id" name="program_studi_id" required><option value="">Pilih program studi</option>@foreach($programStudis as $prodi)<option value="{{ $prodi->id }}" @selected(old('program_studi_id',$item->program_studi_id)==$prodi->id)>{{ $prodi->kode }} — {{ $prodi->nama }}{{ !$prodi->aktif ? ' (nonaktif)' : '' }}</option>@endforeach</select></div>
<div class="field"><label for="angkatan">Angkatan *</label>
<input id="angkatan" name="angkatan" type="number" value="{{ old('angkatan', $item->angkatan) }}" required min="2000" max="{{ date('Y') + 1 }}">
@error('angkatan')<p class="error">{{ $message }}</p>@enderror</div>
<div class="field"><label for="status">Status *</label><select id="status" name="status" required>
@foreach(['aktif'=>'Aktif','cuti'=>'Cuti','nonaktif'=>'Nonaktif'] as $value=>$label)
<option value="{{ $value }}" @selected(old('status', $item->status ?? 'aktif') === $value)>{{ $label }}</option>
@endforeach
</select>@error('status')<p class="error">{{ $message }}</p>@enderror</div>
<h2 class="full-width">Profil mahasiswa (opsional)</h2><div class="field"><label for="profil_tempat_lahir">Tempat lahir</label><input id="profil_tempat_lahir" name="profil[tempat_lahir]" type="text" value="{{ old('profil.tempat_lahir', $item->profil?->tempat_lahir) }}"></div><div class="field"><label for="profil_tanggal_lahir">Tanggal lahir</label><input id="profil_tanggal_lahir" name="profil[tanggal_lahir]" type="date" value="{{ old('profil.tanggal_lahir', $item->profil?->tanggal_lahir?->format('Y-m-d')) }}"></div><div class="field"><label for="profil_telepon">Telepon</label><input id="profil_telepon" name="profil[telepon]" type="text" value="{{ old('profil.telepon', $item->profil?->telepon) }}"></div><div class="field"><label for="profil_alamat">Alamat</label><input id="profil_alamat" name="profil[alamat]" type="text" value="{{ old('profil.alamat', $item->profil?->alamat) }}"></div><div class="full-width"><h2>Pilihan KRS per periode</h2><p class="muted">Aktifkan perubahan KRS jika ingin menyimpan pilihan di bawah. Periode lain tidak berubah.</p><input type="hidden" name="krs_enabled" value="0"><label><input type="checkbox" name="krs_enabled" value="1" @checked(old('krs_enabled',false))> Simpan perubahan KRS periode ini</label></div>
<div class="field"><label for="tahun_akademik">Tahun akademik</label><input id="tahun_akademik" name="tahun_akademik" value="{{ old('tahun_akademik',$period['tahun_akademik']) }}" placeholder="2026/2027"></div>
<div class="field"><label for="semester_krs">Semester KRS</label><select id="semester_krs" name="semester_krs">@foreach(['ganjil','genap'] as $semester)<option @selected(old('semester_krs',$period['semester_krs'])===$semester)>{{ $semester }}</option>@endforeach</select></div>
<div class="field"><label for="krs_mode">Cara menyimpan</label><select id="krs_mode" name="krs_mode"><option value="sync" @selected(old('krs_mode','sync')==='sync')>Ganti pilihan periode ini (sync)</option><option value="add" @selected(old('krs_mode')==='add')>Tambahkan tanpa melepas yang lama</option></select></div>
<div class="full-width course-grid">@forelse($mataKuliahs as $mk)<label class="course-option"><input type="checkbox" name="mata_kuliah_ids[]" value="{{ $mk->id }}" @checked(in_array($mk->id, old('mata_kuliah_ids',session()->hasOldInput() ? [] : $selected)))><span>{{ $mk->kode }} — {{ $mk->nama }} ({{ $mk->sks }} SKS){{ !$mk->aktif ? ' · nonaktif' : '' }}</span></label>@empty<p>Belum ada mata kuliah aktif. Tambahkan master Mata Kuliah terlebih dahulu.</p>@endforelse</div>
<div class="full-width"><p class="muted">Mode ganti dengan pilihan kosong akan menghapus seluruh KRS pada periode terpilih. Saat mengganti periode, buka periode tersebut dari halaman Detail → Edit untuk memuat pilihan yang tersimpan.</p></div>
</div><div class="actions"><button class="btn" type="submit">Simpan data</button><a class="btn secondary" href="{{ route('admin.mahasiswa.index') }}">Batal</a></div>
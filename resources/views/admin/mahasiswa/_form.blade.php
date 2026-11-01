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
<div class="field"><label for="program_studi">Program studi *</label>
<input id="program_studi" name="program_studi" type="text" value="{{ old('program_studi', $item->program_studi) }}" required maxlength="100">
@error('program_studi')<p class="error">{{ $message }}</p>@enderror</div>
<div class="field"><label for="angkatan">Angkatan *</label>
<input id="angkatan" name="angkatan" type="number" value="{{ old('angkatan', $item->angkatan) }}" required min="2000" max="{{ date('Y') + 1 }}">
@error('angkatan')<p class="error">{{ $message }}</p>@enderror</div>
</div><div class="actions"><button class="btn" type="submit">Simpan data</button><a class="btn secondary" href="{{ route('admin.mahasiswa.index') }}">Batal</a></div>
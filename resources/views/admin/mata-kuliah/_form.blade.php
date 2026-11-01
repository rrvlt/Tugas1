@csrf
<div class="form-grid">
<div class="field"><label for="kode">Kode mata kuliah *</label>
<input id="kode" name="kode" type="text" value="{{ old('kode', $item->kode) }}" required maxlength="20">
@error('kode')<p class="error">{{ $message }}</p>@enderror</div>
<div class="field"><label for="nama">Nama mata kuliah *</label>
<input id="nama" name="nama" type="text" value="{{ old('nama', $item->nama) }}" required maxlength="150">
@error('nama')<p class="error">{{ $message }}</p>@enderror</div>
<div class="field"><label for="sks">SKS *</label>
<input id="sks" name="sks" type="number" value="{{ old('sks', $item->sks) }}" required min="1" max="6">
@error('sks')<p class="error">{{ $message }}</p>@enderror</div>
<div class="field"><label for="semester">Semester *</label>
<input id="semester" name="semester" type="number" value="{{ old('semester', $item->semester) }}" required min="1" max="14">
@error('semester')<p class="error">{{ $message }}</p>@enderror</div>
<div class="field"><label for="deskripsi">Deskripsi <span class="muted">(opsional)</span></label>
<textarea id="deskripsi" name="deskripsi" rows="5" maxlength="2000">{{ old('deskripsi', $item->deskripsi) }}</textarea>
@error('deskripsi')<p class="error">{{ $message }}</p>@enderror</div>
</div><div class="actions"><button class="btn" type="submit">Simpan data</button><a class="btn secondary" href="{{ route('admin.mata-kuliah.index') }}">Batal</a></div>
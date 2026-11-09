<?php
namespace App\Services;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
class KrsService {
    public function save(Mahasiswa $student, array $data): void {
        DB::transaction(function() use($student,$data) {
            Mahasiswa::whereKey($student->id)->lockForUpdate()->firstOrFail();
            $relation=$student->krsPeriode($data['tahun_akademik'],$data['semester_krs']);
            $existing=$relation->get()->keyBy('id'); $payload=[];
            foreach($data['mata_kuliah_ids'] ?? [] as $id) {
                $mk=MataKuliah::findOrFail($id);
                if (!$mk->aktif && !$existing->has($id)) throw ValidationException::withMessages(['mata_kuliah_ids'=>'Mata kuliah nonaktif tidak dapat ditambahkan.']);
                $payload[$id]=['tahun_akademik'=>$data['tahun_akademik'],'semester'=>$data['semester_krs'],
                    'status'=>$existing->get($id)?->pivot->status ?? 'draft'];
            }
            // Both reads and writes are scoped: no global sync across semesters.
            if (($data['krs_mode'] ?? 'sync')==='add') $relation->syncWithoutDetaching($payload);
            else $relation->sync($payload);
        });
    }
    public function attach(Mahasiswa $student, array $data): void {
        DB::transaction(function() use($student,$data) {
            Mahasiswa::whereKey($student->id)->lockForUpdate()->firstOrFail();
            $rel=$student->krsPeriode($data['tahun_akademik'],$data['semester_krs']);
            if ($rel->where('mata_kuliahs.id',$data['mata_kuliah_id'])->exists()) throw ValidationException::withMessages(['mata_kuliah_id'=>'Mata kuliah sudah ada pada periode ini.']);
            if (!MataKuliah::findOrFail($data['mata_kuliah_id'])->aktif) throw ValidationException::withMessages(['mata_kuliah_id'=>'Mata kuliah tidak aktif.']);
            $student->mataKuliahs()->attach($data['mata_kuliah_id'],['tahun_akademik'=>$data['tahun_akademik'],'semester'=>$data['semester_krs'],'status'=>'draft']);
        });
    }
    public function change(Mahasiswa $student, int $courseId, array $data, bool $delete=false): void {
        DB::transaction(function() use($student,$courseId,$data,$delete) {
            Mahasiswa::whereKey($student->id)->lockForUpdate()->firstOrFail();
            $rel=$student->krsPeriode($data['tahun_akademik'],$data['semester_krs']);
            abort_unless((clone $rel)->where('mata_kuliahs.id',$courseId)->exists(),404);
            if ($delete) $rel->detach($courseId);
            else $rel->updateExistingPivot($courseId,['status'=>$data['status']]);
        });
    }
}

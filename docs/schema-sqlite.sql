-- Reference generated from Laravel migrations on SQLite.
-- Use php artisan migrate; do not import over an existing database.
PRAGMA foreign_keys=ON;

CREATE TABLE "dosen_keahlian" ("id" integer primary key autoincrement not null, "dosen_id" integer not null, "keahlian_id" integer not null, "level" varchar check ("level" in ('pemula', 'menengah', 'ahli')) not null, "tahun_mulai" integer not null, "created_at" datetime, "updated_at" datetime, foreign key("dosen_id") references "dosens"("id") on delete cascade, foreign key("keahlian_id") references "keahlians"("id") on delete restrict);

CREATE TABLE "dosens" ("id" integer primary key autoincrement not null, "program_studi_id" integer not null, "nidn" varchar not null, "nama" varchar not null, "email" varchar not null, "created_at" datetime, "updated_at" datetime, foreign key("program_studi_id") references "program_studis"("id") on delete restrict);

CREATE TABLE "keahlians" ("id" integer primary key autoincrement not null, "kode" varchar not null, "nama" varchar not null, "created_at" datetime, "updated_at" datetime);

CREATE TABLE "krs" ("id" integer primary key autoincrement not null, "mahasiswa_id" integer not null, "mata_kuliah_id" integer not null, "tahun_akademik" varchar not null, "semester" varchar check ("semester" in ('ganjil', 'genap')) not null, "status" varchar check ("status" in ('draft', 'disetujui')) not null default 'draft', "created_at" datetime, "updated_at" datetime, foreign key("mahasiswa_id") references "mahasiswas"("id") on delete cascade, foreign key("mata_kuliah_id") references "mata_kuliahs"("id") on delete restrict);

CREATE TABLE "mahasiswas" ("id" integer primary key autoincrement not null, "nim" varchar not null, "nama" varchar not null, "email" varchar not null, "angkatan" integer not null, "created_at" datetime, "updated_at" datetime, "status" varchar not null default ('aktif'), "program_studi_id" integer not null, foreign key("program_studi_id") references program_studis("id") on delete restrict on update no action);

CREATE TABLE "mata_kuliahs" ("id" integer primary key autoincrement not null, "kode" varchar not null, "nama" varchar not null, "sks" integer not null, "semester" integer not null, "deskripsi" text, "created_at" datetime, "updated_at" datetime, "aktif" tinyint(1) not null default '1');

CREATE TABLE "profil_dosens" ("id" integer primary key autoincrement not null, "dosen_id" integer not null, "telepon" varchar, "alamat" text, "created_at" datetime, "updated_at" datetime, foreign key("dosen_id") references "dosens"("id") on delete cascade);

CREATE TABLE "profil_mahasiswas" ("id" integer primary key autoincrement not null, "mahasiswa_id" integer not null, "tempat_lahir" varchar, "tanggal_lahir" date, "telepon" varchar, "alamat" text, "created_at" datetime, "updated_at" datetime, foreign key("mahasiswa_id") references "mahasiswas"("id") on delete cascade);

CREATE TABLE "program_studis" ("id" integer primary key autoincrement not null, "kode" varchar not null, "nama" varchar not null, "aktif" tinyint(1) not null default '1', "created_at" datetime, "updated_at" datetime);

CREATE UNIQUE INDEX "dosen_keahlian_dosen_id_keahlian_id_unique" on "dosen_keahlian" ("dosen_id", "keahlian_id");

CREATE UNIQUE INDEX "dosens_email_unique" on "dosens" ("email");

CREATE UNIQUE INDEX "dosens_nidn_unique" on "dosens" ("nidn");

CREATE UNIQUE INDEX "keahlians_kode_unique" on "keahlians" ("kode");

CREATE UNIQUE INDEX "keahlians_nama_unique" on "keahlians" ("nama");

CREATE UNIQUE INDEX "krs_unique" on "krs" ("mahasiswa_id", "mata_kuliah_id", "tahun_akademik", "semester");

CREATE UNIQUE INDEX "mahasiswas_email_unique" on "mahasiswas" ("email");

CREATE UNIQUE INDEX "mahasiswas_nim_unique" on "mahasiswas" ("nim");

CREATE INDEX "mahasiswas_status_index" on "mahasiswas" ("status");

CREATE UNIQUE INDEX "mata_kuliahs_kode_unique" on "mata_kuliahs" ("kode");

CREATE UNIQUE INDEX "profil_dosens_dosen_id_unique" on "profil_dosens" ("dosen_id");

CREATE UNIQUE INDEX "profil_mahasiswas_mahasiswa_id_unique" on "profil_mahasiswas" ("mahasiswa_id");

CREATE UNIQUE INDEX "program_studis_kode_unique" on "program_studis" ("kode");

CREATE UNIQUE INDEX "program_studis_nama_unique" on "program_studis" ("nama");

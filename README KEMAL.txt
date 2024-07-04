Project Akunkeun menggunakan laravel 10, bagian untuk users

Before
Schema::create('keuangan_perjadinlangsungs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_perjadin');
            $table->integer('uang_harian');
            $table->integer('persen_pajak');
            $table->integer('uang_rep');
            $table->integer('jumlah');
            $table->foreignId('data_perjadinlangsung_id');
            $table->foreignId('akun_x_rkakl');
            $table->timestamps();
        });

After
Schema::create('keuangan_perjadinlangsungs', function (Blueprint $table) {
            $table->id();
            $table->string('info_perjadinlangsung_id');
            $table->integer('uang_harian');
            $table->integer('persen_pajak');
            $table->integer('uang_rep');
            $table->integer('jumlah');
            $table->foreignId('data_perjadinlangsung_id');
            $table->foreignId('akun_x_rkakl');
            $table->timestamps();
        });

================================================
Before :
Schema::create('fasilitas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_fasilitas');
            $table->timestamps();
        });

After
Schema::create('fasilitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_perjadinkegiatan_id');
            $table->string('nama_fasilitas');
            $table->timestamps();
        });

================================================
Before
Schema::create('perangkat_acaras', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->integer('jumlah_jam');
            $table->integer('jumlah_hari');
            $table->integer('jumlah_bulan');
            $table->integer('jumlah_frekuensi');
            $table->string('SK/file');
            $table->foreignId('fasilitas_id');
            $table->foreignId('non_pegawai_id');
            $table->foreignId('pegawai_id');
            $table->timestamps();
        });

After
Schema::create('perangkat_acaras', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->integer('jumlah_jam');
            $table->integer('jumlah_hari');
            $table->integer('jumlah_bulan');
            $table->integer('jumlah_frekuensi');
            $table->string('SK/file');
            $table->foreignId('fasilitas_id');
            $table->foreignId('non_pegawai_id');
            $table->foreignId('pegawai_id');
            $table->timestamps();
            $table->string('detail_satuan');
            $table->integer('satuan');
        });

==============================================
Before
Schema::create('operasionals', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->integer('jumlah_barang');
            $table->integer('jumlah_frekuensi');
            $table->integer('jumlah_jam');
            $table->integer('jumlah_hari');
            $table->integer('jumlah_bulan');
            $table->string('ket');
            $table->foreignId('kebutuhan_id');
            $table->timestamps();
        });

After
Schema::create('operasionals', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->integer('jumlah_frekuensi');
            $table->string('detail_satuan')->nullable();
            $table->integer('satuan')->nullable();
            $table->string('ket');
            $table->foreignId('fasilitas_id');
            $table->timestamps();
        }); 
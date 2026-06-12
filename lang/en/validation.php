<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'Isian :attribute harus diterima.',
    'accepted_if' => 'Isian :attribute harus diterima ketika :other berisi :value.',
    'active_url' => 'Isian :attribute bukan URL yang valid.',
    'after' => 'Isian :attribute harus berisi tanggal setelah :date.',
    'after_or_equal' => 'Isian :attribute harus berisi tanggal setelah atau sama dengan :date.',
    'alpha' => 'Isian :attribute hanya boleh berisi huruf.',
    'alpha_dash' => 'Isian :attribute hanya boleh berisi huruf, angka, strip, dan garis bawah.',
    'alpha_num' => 'Isian :attribute hanya boleh berisi huruf dan angka.',
    'any_of' => 'Isian :attribute tidak valid.',
    'array' => 'Isian :attribute harus berisi sebuah array.',
    'ascii' => 'Isian :attribute hanya boleh berisi karakter dan simbol alfanumerik single-byte.',
    'before' => 'Isian :attribute harus berisi tanggal sebelum :date.',
    'before_or_equal' => 'Isian :attribute harus berisi tanggal sebelum atau sama dengan :date.',
    'between' => [
        'array' => 'Isian :attribute harus memiliki :min sampai :max anggota.',
        'file' => 'Isian :attribute harus berukuran antara :min sampai :max kilobita.',
        'numeric' => 'Isian :attribute harus bernilai antara :min sampai :max.',
        'string' => 'Isian :attribute harus berisi antara :min sampai :max karakter.',
    ],
    'boolean' => 'Isian :attribute harus bernilai true atau false',
    'can' => 'Isian :attribute berisi nilai yang tidak sah.',
    'confirmed' => 'Konfirmasi :attribute tidak cocok.',
    'contains' => 'Isian :attribute tidak memiliki nilai yang diwajibkan.',
    'current_password' => 'Password salah.',
    'date' => 'Isian :attribute bukan tanggal yang valid.',
    'date_equals' => 'Isian :attribute harus berisi tanggal yang sama dengan :date.',
    'date_format' => 'Isian :attribute tidak cocok dengan format :format.',
    'decimal' => 'Isian :attribute harus memiliki :decimal tempat desimal.',
    'declined' => 'Isian :attribute harus ditolak.',
    'declined_if' => 'Isian :attribute harus ditolak ketika :other bernilai :value.',
    'different' => 'Isian :attribute dan :other harus berbeda.',
    'digits' => 'Isian :attribute harus terdiri dari :digits angka.',
    'digits_between' => 'Isian :attribute harus terdiri dari :min sampai :max angka.',
    'dimensions' => 'Dimensi gambar :attribute tidak valid.',
    'distinct' => 'Isian :attribute memiliki nilai yang duplikat.',
    'doesnt_contain' => 'Isian :attribute tidak boleh berisi apa pun dari berikut ini: :values.',
    'doesnt_end_with' => 'Isian :attribute tidak boleh diakhiri dengan salah satu dari berikut ini: :values.',
    'doesnt_start_with' => 'Isian :attribute tidak boleh dimulai dengan salah satu dari berikut ini: :values.',
    'email' => 'Isian :attribute harus berupa alamat email yang valid.',
    'encoding' => 'Isian :attribute harus dienkode dalam :encoding.',
    'ends_with' => 'Isian :attribute harus diakhiri salah satu dari berikut: :values',
    'enum' => 'Isian :attribute yang dipilih tidak valid.',
    'exists' => 'Isian :attribute yang dipilih tidak valid.',
    'extensions' => 'Isian :attribute harus memiliki salah satu ekstensi berikut: :values.',
    'file' => 'Isian :attribute harus berupa sebuah berkas.',
    'filled' => 'Isian :attribute harus memiliki nilai.',
    'gt' => [
        'array' => 'Isian :attribute harus memiliki lebih dari :value anggota.',
        'file' => 'Isian :attribute harus berukuran lebih besar dari :value kilobita.',
        'numeric' => 'Isian :attribute harus bernilai lebih besar dari :value.',
        'string' => 'Isian :attribute harus berisi lebih besar dari :value karakter.',
    ],
    'gte' => [
        'array' => 'Isian :attribute harus terdiri dari :value anggota atau lebih.',
        'file' => 'Isian :attribute harus berukuran lebih besar dari atau sama dengan :value kilobita.',
        'numeric' => 'Isian :attribute harus bernilai lebih besar dari atau sama dengan :value.',
        'string' => 'Isian :attribute harus berisi lebih besar dari atau sama dengan :value karakter.',
    ],
    'hex_color' => 'Isian :attribute harus berupa warna heksadesimal yang valid.',
    'image' => 'Isian :attribute harus berupa gambar.',
    'in' => 'Isian :attribute yang dipilih tidak valid.',
    'in_array' => 'Isian :attribute tidak ada di dalam :other.',
    'in_array_keys' => 'Isian :attribute harus berisi minimal salah satu kunci berikut: :values.',
    'integer' => 'Isian :attribute harus berupa bilangan bulat.',
    'ip' => 'Isian :attribute harus berupa alamat IP yang valid.',
    'ipv4' => 'Isian :attribute harus berupa alamat IPv4 yang valid.',
    'ipv6' => 'Isian :attribute harus berupa alamat IPv6 yang valid.',
    'json' => 'Isian :attribute harus berupa string JSON yang valid.',
    'list' => 'Isian :attribute harus berupa sebuah daftar.',
    'lowercase' => 'Isian :attribute harus berupa huruf kecil.',
    'lt' => [
        'array' => 'Isian :attribute harus memiliki kurang dari :value anggota.',
        'file' => 'Isian :attribute harus berukuran kurang dari :value kilobita.',
        'numeric' => 'Isian :attribute harus bernilai kurang dari :value.',
        'string' => 'Isian :attribute harus berisi kurang dari :value karakter.',
    ],
    'lte' => [
        'array' => 'Isian :attribute tidak boleh memiliki lebih dari :value anggota.',
        'file' => 'Isian :attribute harus berukuran kurang dari atau sama dengan :value kilobita.',
        'numeric' => 'Isian :attribute harus bernilai kurang dari atau sama dengan :value.',
        'string' => 'Isian :attribute harus berisi kurang dari atau sama dengan :value karakter.',
    ],
    'mac_address' => 'Isian :attribute harus berupa alamat MAC yang valid.',
    'max' => [
        'array' => 'Isian :attribute maksimal terdiri dari :max anggota.',
        'file' => 'Isian :attribute maksimal berukuran :max kilobita.',
        'numeric' => 'Isian :attribute maksimal bernilai :max.',
        'string' => 'Isian :attribute maksimal berisi :max karakter.',
    ],
    'max_digits' => 'Isian :attribute tidak boleh memiliki lebih dari :max angka.',
    'mimes' => 'Isian :attribute harus berupa berkas berjenis: :values.',
    'mimetypes' => 'Isian :attribute harus berupa berkas berjenis: :values.',
    'min' => [
        'array' => 'Isian :attribute minimal terdiri dari :min anggota.',
        'file' => 'Isian :attribute minimal berukuran :min kilobita.',
        'numeric' => 'Isian :attribute minimal bernilai :min.',
        'string' => 'Isian :attribute minimal berisi :min karakter.',
    ],
    'min_digits' => 'Isian :attribute harus memiliki minimal :min angka.',
    'missing' => 'Isian :attribute harus hilang.',
    'missing_if' => 'Isian :attribute harus hilang ketika :other bernilai :value.',
    'missing_unless' => 'Isian :attribute harus hilang kecuali :other bernilai :value.',
    'missing_with' => 'Isian :attribute harus hilang ketika :values ada.',
    'missing_with_all' => 'Isian :attribute harus hilang ketika semua :values ada.',
    'multiple_of' => 'Isian :attribute harus merupakan kelipatan dari :value',
    'not_in' => 'Isian :attribute yang dipilih tidak valid.',
    'not_regex' => 'Format isian :attribute tidak valid.',
    'numeric' => 'Isian :attribute harus berupa angka.',
    'password' => [
        'letters' => 'Isian :attribute harus berisi setidaknya satu huruf.',
        'mixed' => 'Isian :attribute harus berisi setidaknya satu huruf besar dan satu huruf kecil.',
        'numbers' => 'Isian :attribute harus berisi setidaknya satu angka.',
        'symbols' => 'Isian :attribute harus berisi setidaknya satu simbol.',
        'uncompromised' => 'Isian :attribute yang diberikan telah muncul dalam kebocoran data. Silakan pilih :attribute yang berbeda.',
    ],
    'present' => 'Isian :attribute wajib ada.',
    'present_if' => 'Isian :attribute wajib ada ketika :other bernilai :value.',
    'present_unless' => 'Isian :attribute wajib ada kecuali :other bernilai :value.',
    'present_with' => 'Isian :attribute wajib ada ketika :values ada.',
    'present_with_all' => 'Isian :attribute wajib ada ketika semua :values ada.',
    'prohibited' => 'Isian :attribute dilarang.',
    'prohibited_if' => 'Isian :attribute dilarang ketika :other bernilai :value.',
    'prohibited_if_accepted' => 'Isian :attribute dilarang ketika :other diterima.',
    'prohibited_if_declined' => 'Isian :attribute dilarang ketika :other ditolak.',
    'prohibited_unless' => 'Isian :attribute dilarang kecuali :other ada dalam :values.',
    'prohibits' => 'Isian :attribute melarang :other untuk ada.',
    'regex' => 'Format isian :attribute tidak valid.',
    'required' => 'Isian :attribute wajib diisi.',
    'required_array_keys' => 'Isian :attribute harus berisi entri untuk: :values.',
    'required_if' => 'Isian :attribute wajib diisi bila :other adalah :value.',
    'required_if_accepted' => 'Isian :attribute wajib diisi bila :other diterima.',
    'required_if_declined' => 'Isian :attribute wajib diisi bila :other ditolak.',
    'required_unless' => 'Isian :attribute wajib diisi kecuali :other memiliki nilai :values.',
    'required_with' => 'Isian :attribute wajib diisi bila terdapat :values.',
    'required_with_all' => 'Isian :attribute wajib diisi bila terdapat :values.',
    'required_without' => 'Isian :attribute wajib diisi bila tidak terdapat :values.',
    'required_without_all' => 'Isian :attribute wajib diisi bila sama sekali tidak terdapat :values.',
    'same' => 'Isian :attribute dan :other harus sama.',
    'size' => [
        'array' => 'Isian :attribute harus mengandung :size anggota.',
        'file' => 'Isian :attribute harus berukuran :size kilobyte.',
        'numeric' => 'Isian :attribute harus berukuran :size.',
        'string' => 'Isian :attribute harus berukuran :size karakter.',
    ],
    'starts_with' => 'Isian :attribute harus diawali salah satu dari berikut: :values',
    'string' => 'Isian :attribute harus berupa string.',
    'timezone' => 'Isian :attribute harus berupa zona waktu yang valid.',
    'unique' => 'Isian :attribute sudah ada sebelumnya.',
    'uploaded' => 'Isian :attribute gagal diunggah.',
    'uppercase' => 'Isian :attribute harus berupa huruf kapital.',
    'url' => 'Format isian :attribute tidak valid.',
    'ulid' => 'Isian :attribute harus berupa ULID yang valid.',
    'uuid' => 'Isian :attribute harus merupakan UUID yang valid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];

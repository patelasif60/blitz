<?php

return array (
  'user_added' => 'Pengguna Berhasil Ditambah',
  'user_invited' => 'Pengguna Berhasil Diundang',
  'user_deleted' => 'Pengguna Berhasil Dihapus',
  'email_already_exist' => 'Id email sudah ada',
  'user_data_exist' => 'Data Pengguna Sudah Ada',
  'approval_members' => 'Apakah Anda ingin menambahkan anggota untuk proses persetujuan?',
  'custom' => 
  array (
    'bankName' => 
    array (
      'required' => 'Bidang nama bank wajib diisi.',
    ),
    'bankAccountHolderName' => 
    array (
      'required' => 'Bidang nama pemilik rekening bank wajib diisi.',
      'max' => 'nama pemilik rekening bank tidak boleh lebih besar dari :max karakter.',
      'regex' => 'nama pemilik rekening bank hanya boleh berisi huruf dan spasi.',
    ),
    'bankAccountNumber' => 
    array (
      'required' => 'Bidang nomor rekening ban wajib diisi.',
      'min' => 'nomor rekening ban minimal harus :min karakter.',
      'BuyerBankunique' => 'Nomor rekening bank sudah ada.',
    ),
    'isPrimary' => 
    array (
      'BuyerBankPrimaryExist' => 'Harap periksa opsi bank utama.',
    ),
    'roleName' => 
    array (
      'placeholder' => 'Masukkan nama peran',
      'required' => 'Nama peran wajib diisi.',
      'BuyerRoleNameUnique' => 'Peran sudah ada.',
    ),
    'rolePermission' => 
    array (
      'required' => 'Harap pilih setidaknya satu izin.',
    ),
    'dateOfBirth' => 
    array (
      'required' => 'Tanggal lahir wajib diisi.',
      'date' => 'Tanggal lahir tidak sesuai dengan format dd-mm-YY.',
      'date_format' => 'Tanggal lahir tidak sesuai dengan format dd-mm-YY.',
    ),
    'education' => 
    array (
      'required' => 'Pendidikan itu diperlukan.',
    ),
    'email' => 
    array (
      'required' => 'Email diperlukan.',
      'email' => 'Email harus dalam format \'xyz@gmail.com\'.',
    ),
    'familyCardImage' => 
    array (
      'required' => 'Gambar kartu keluarga wajib diisi.',
    ),
    'firstName' => 
    array (
      'required' => 'Nama depan wajib diisi.',
    ),
    'gender' => 
    array (
      'required' => 'Jenis kelamin diperlukan.',
    ),
    'ktpImage' => 
    array (
      'required' => 'Gambar KTP diperlukan.',
    ),
    'lastName' => 
    array (
      'required' => 'Nama belakang wajib diisi.',
    ),
    'phoneNumber' => 
    array (
      'required' => 'Nomor telepon wajib diisi.',
      'min' => 'Nomor telepon harus minimal 9 karakter.',
    ),
    'maritalStatus' => 
    array (
      'required' => 'Status pernikahan diperlukan.',
    ),
    'religion' => 
    array (
      'required' => 'Agama itu wajib.',
    ),
    'occupation' => 
    array (
      'required' => 'Pendudukan diperlukan.',
    ),
    'otherIncome' => 
    array (
      'required' => 'Penghasilan lain diperlukan.',
    ),
    'netSalary' => 
    array (
      'required' => 'Gaji bersih diperlukan.',
    ),
    'otherSourceOfIncome' => 
    array (
      'required' => 'Sumber pendapatan lain diperlukan.',
    ),
    'ktpSelfiImage' => 
    array (
      'required' => 'Foto selfie KTP diperlukan.',
    ),
    'otherKtpImage' => 
    array (
      'required' => 'Diperlukan Gambar KTP lainnya.',
    ),
    'placeOfBirth' => 
    array (
      'required' => 'Tempat lahir diperlukan.',
    ),
    'myPosition' => 
    array (
      'required' => 'Posisi saya diperlukan.',
    ),
    'otherFirstName' => 
    array (
      'required' => 'Nama depan lainnya wajib diisi.',
    ),
    'otherLastName' => 
    array (
      'required' => 'Nama belakang lainnya wajib diisi.',
    ),
    'otherMemberPhone' => 
    array (
      'required' => 'Telepon anggota lain diperlukan.',
      'min' => 'Nomor telepon harus minimal 9 karakter.',
    ),
    'otherKtpNik' => 
    array (
      'required' => 'NIK KTP lainnya diperlukan.',
      'min' => 'Nomor KTP NIK harus 16 nomor saja.',
      'different' => 'Ktp Nik lainnya tidak boleh sama seperti Ktp NIK',
    ),
    'relationshipWithBorrower' => 
    array (
      'required' => 'Hubungan dengan peminjam diperlukan.',
    ),
    'ktpNik' => 
    array (
      'required' => 'KTP NIK diperlukan.',
      'min' => 'Nomor KTP NIK harus 16 nomor saja.',
    ),
    'loanApplicantAddressLine1' => 
    array (
      'required' => 'Jalur jalan 1 wajib diisi.',
    ),
    'loanApplicantAddressLine2' => 
    array (
      'required' => 'Jalur jalan 2 diperlukan.',
    ),
    'loanApplicantAddressName' => 
    array (
      'required' => 'Nama alamat wajib diisi.',
    ),
    'loanApplicantBankStatement' => 
    array (
      'required' => 'File laporan bank diperlukan.',
    ),
    'loanApplicantBusinessAverageSales' => 
    array (
      'required' => 'Rata-rata penjualan diperlukan.',
    ),
    'loanApplicantBusinessDescription' => 
    array (
      'required' => 'Deskripsi diperlukan.',
    ),
    'loanApplicantBusinessEmail' => 
    array (
      'required' => 'Email bisnis diperlukan.',
      'email' => 'Email harus dalam format \'xyz@gmail.com\'.',
      'unique' => 'Emailnya sudah ada.',
    ),
    'loanBusinessAddressSubDistrict' => 
    array (
      'required' => 'Kecamatan diperlukan.',
    ),
    'loanBusinessAddressProvinces' => 
    array (
      'required' => 'Provinsi diperlukan.',
    ),
    'loanBusinessAddressPostalCode' => 
    array (
      'required' => 'Kode pos diperlukan.',
    ),
    'loanBusinessAddressLine2' => 
    array (
      'required' => 'Jalur jalan 2 diperlukan.',
    ),
    'loanBusinessAddressLine1' => 
    array (
      'required' => 'Jalur jalan 1 wajib diisi.',
    ),
    'loanBusinessAddressDistrict' => 
    array (
      'required' => 'Kabupaten diperlukan.',
    ),
    'loanBusinessAddressCountry' => 
    array (
      'required' => 'Negara diperlukan.',
    ),
    'loanBusinessAddressCity' => 
    array (
      'required' => 'Kota diperlukan.',
    ),
    'loanApplicanthomeOwnershipStatus' => 
    array (
      'required' => 'Status kepemilikan rumah wajib diisi.',
    ),
    'loanApplicantSiupNumber' => 
    array (
      'required' => 'Nomor Siup wajib diisi.',
    ),
    'loanApplicantRelationshipWithBorrower' => 
    array (
      'required' => 'Hubungan dengan peminjam diperlukan.',
    ),
    'loanApplicantPostalCode' => 
    array (
      'required' => 'Kode pos diperlukan.',
      'min' => 'Panjang kode pos harus antara 5 hingga 8 digit.',
      'max' => 'Panjang kode pos harus antara 5 hingga 8 digit.',
    ),
    'loanApplicantOwnership' => 
    array (
      'required' => 'Kepemilikan diperlukan.',
      'gt' => '% Kepemilikan harus lebih dari 0 dan kurang dari 100.',
      'lte' => '% Kepemilikan harus lebih dari 0 dan kurang dari 100.',
      'numeric' => 'Kepemilikan harus berupa angka saja.',
    ),
    'loanApplicantHasLivedHere' => 
    array (
      'required' => 'Status telah tinggal di sini diperlukan.',
    ),
    'loanApplicantDurationOfStay' => 
    array (
      'required' => 'Durasi tinggal diperlukan.',
    ),
    'loanApplicantCountryId' => 
    array (
      'required' => 'Negara diperlukan.',
    ),
    'loanApplicantCategory' => 
    array (
      'required' => 'Kategori wajib diisi.',
    ),
    'loanApplicantBusinessWebsite' => 
    array (
      'required' => 'Situs web diperlukan.',
    ),
    'loanApplicantBusinessType' => 
    array (
      'required' => 'Jenisnya wajib diisi.',
    ),
    'loanApplicantBusinessPhone' => 
    array (
      'required' => 'Telepon diperlukan.',
      'min' => 'Nomor telepon harus minimal 9 karakter.',
    ),
    'loanApplicantBusinessNpwpImage' => 
    array (
      'required' => 'Gambar NPWP wajib diisi.',
    ),
    'loanApplicantBusinessNoOfEmployee' => 
    array (
      'required' => 'Jumlah karyawan yang dibutuhkan.',
    ),
    'loanApplicantBusinessName' => 
    array (
      'required' => 'Nama bisnis wajib diisi.',
    ),
    'loanApplicantBusinessLicenceImage' => 
    array (
      'required' => 'Gambar lisensi diperlukan.',
    ),
    'loanApplicantBusinessLastName' => 
    array (
      'required' => 'Nama belakang wajib diisi.',
    ),
    'loanApplicantBusinessFirstName' => 
    array (
      'required' => 'Nama depan wajib diisi.',
    ),
    'loanApplicantBusinessEstablish' => 
    array (
      'required' => 'Tanggal yang ditetapkan wajib diisi.',
      'date' => 'Tanggal penetapan bukan tanggal yang valid.',
    ),
    'provincesId' => 
    array (
      'required' => 'Provinsi diperlukan.',
    ),
    'subDistrict' => 
    array (
      'required' => 'Kecamatan diperlukan.',
    ),
    'district' => 
    array (
      'required' => 'Kabupaten diperlukan.',
    ),
    'cityId' => 
    array (
      'required' => 'Kota diperlukan.',
    ),
    'otherMemberEmail' => 
    array (
      'required' => 'Email anggota lain diperlukan',
      'email' => 'Email harus dalam format \'xyz@gmail.com\'.',
    ),
    'mobile' => 
    array (
      'max' => 'Ponsel harus antara 9 dan 16 digit.',
      'required' => 'Nomor ponsel wajib diisi.',
      'min' => 'Ponsel harus antara 9 dan 16 digit.',
    ),
    'city_business' => 
    array (
      'required_if' => 'Kota diperlukan.',
    ),
    'department' => 
    array (
      'required' => 'Departemen diperlukan.',
    ),
    'designation' => 
    array (
      'required' => 'Penunjukan diperlukan.',
    ),
    'approverPasswordMatch' => 'Kata sandi anda salah',
    'pkp_file' => 
    array (
      'required' => 'File PKP diperlukan.',
      'mimes' => 'File PKP harus berupa file dengan jenis: jpeg,png,doc,docs,pdf.',
    ),
    'company_name' => 
    array (
      'required' => 'Bidang Nama perusahaan wajib diisi.',
    ),
    'profile_username' => 
    array (
      'required' => 'Bidang Nama Pengguna Profil wajib diisi.',
    ),
    'contactPersonName' => 
    array (
      'max' => 'Nama Depan tidak boleh lebih besar dari 255.',
      'required' => 'Nama Depan diperlukan.',
    ),
    'contactPersonLastName' => 
    array (
      'max' => '',
    ),
    'contactPersonEmail' => 
    array (
      'required' => 'Kolom Email wajib diisi.',
      'unique' => 'Emailnya sudah ada.',
    ),
    'alternate_email' => 
    array (
      'email' => 'Email harus dalam format \'xyz@gmail.com\'.',
    ),
    'fax' => 
    array (
      'max' => 'Nomor Fax tidak boleh lebih besar dari 255.',
    ),
    'license' => 
    array (
      'max' => 'Lisensi tidak boleh lebih besar dari 255.',
    ),
    'facebook' => 
    array (
      'url' => 'Facebook harus berupa URL yang valid.',
    ),
    'twitter' => 
    array (
      'url' => 'Twitter harus berupa URL yang valid.',
    ),
    'linkedIn' => 
    array (
      'url' => 'LinkedIn harus berupa URL yang valid.',
    ),
    'youtube' => 
    array (
      'url' => 'YouTube harus berupa URL yang valid.',
    ),
    'instagram' => 
    array (
      'url' => 'Instagram harus berupa URL yang valid.',
    ),
    'nib' => 
    array (
      'max' => 'Nomor Induk Berusaha tidak boleh lebih besar dari 13.',
      'required' => '',
    ),
    'website' => 
    array (
      'url' => 'Website harus berupa URL yang valid.',
    ),
    'contactPersonMobile' => 
    array (
      'max' => '',
    ),
    'npwp' => 
    array (
      'required' => '',
    ),
  ),
  'attributes' => 
  array (
    'bankName' => 'nama bank',
    'bankAccountHolderName' => 'nama pemilik rekening bank',
    'bankAccountNumber' => 'nomor rekening bank',
  ),
  'values' => 
  array (
    'loanApplicantBusinessLicenceImage' => 
    array (
      '' => 'Gambar Izin Usaha diperlukan',
    ),
    'pkp_file' => 
    array (
      '' => '',
    ),
    'name' => 
    array (
      '' => '',
    ),
    'contactPersonEmail' => 
    array (
      '' => '',
      'test@gmail' => 
      array (
        'com' => '',
      ),
    ),
    'profile_username' => 
    array (
      '' => '',
    ),
    'email' => 
    array (
      's' => '',
      's1' => '',
      '' => '',
    ),
    'website' => 
    array (
      'dfsdsdf' => '',
    ),
    'alternate_email' => 
    array (
      's' => '',
    ),
    'contactPersonMobile' => 
    array (
      '2222222222222222222222222' => '',
    ),
    'facebook' => 
    array (
      'a' => '',
      'qw' => '',
      'asd' => '',
      'h' => '',
    ),
    'twitter' => 
    array (
      's' => '',
    ),
    'linkedIn' => 
    array (
      'd' => '',
    ),
    'youtube' => 
    array (
      'f' => '',
    ),
    'nib' => 
    array (
      '' => '',
      '1234567891230333333333333333333' => '',
    ),
    'npwp' => 
    array (
      '' => '',
    ),
    'password' => 
    array (
      '' => '',
    ),
  ),
  'user_updated' => 'Pengguna berhasil diperbarui',
  'loanApplicantBankStatement' => 'Laporan bank diperlukan',
  'loanApplicantBusinessLicenceImage' => 'Gambar Izin Usaha diperlukan',
  'loanApplicantBusinessNpwpImage' => 'Diperlukan gambar Npwp bisnis',
  'ktpImage' => 'Gambar ktp diperlukan',
  'ktpSelfiImage' => 'Diperlukan gambar KtpSelf\'',
  'familyCardImage' => 'Diperlukan gambar kartu keluarga.',
  'otherKtpImage' => 'Diperlukan gambar KTP lainnya.',
  'loan_amount_need_minimum' => 'Jumlah pinjaman harus kurang dari batas.',
  'loan_application_not_found' => 'Aplikasi Pinjaman Tidak Ditemukan.',
  'loanApplicantOwnership' => 
  array (
    'required' => 'Kepemilikan diperlukan.',
    'numeric' => 'Kepemilikan harus numerik.',
    'gt' => '% Kepemilikan harus lebih dari 0 dan kurang dari 100.',
    'lt' => '% Kepemilikan harus lebih dari 0 dan kurang dari 100.',
  ),
  'city' => 
  array (
    'required' => 'Kota lain diperlukan.',
  ),
  'state' => 
  array (
    'required' => 'Negara bagian lain diperlukan.',
  ),
  'city_business' => 
  array (
    'required' => 'Kota lain diperlukan.',
  ),
  'state_business' => 
  array (
    'required' => 'Negara bagian lain diperlukan.',
  ),
  'loanApplicantDurationOfStay' => 
  array (
    'gt' => 'Durasi tinggal pemohon pinjaman harus lebih besar dari 0.',
  ),
  'mobile_varify' => 'Verifikasi Seluler',
  'verifyotp' => 'Verifikasi OTP',
  'max_attempt' => 'OTP gagal karena terlalu banyak permintaan',
  'invalidotp' => 'Masukkan OTP yang valid.',
  'otpexpired' => 'OTP Anda Kedaluwarsa.',
  'mobvalida' => 'Masukkan nomor ponsel yang valid.',
  'preferctry' => 'Kami hanya menerima nomor telepon Indonesia dan India.',
  'emailvalid' => 'Harap masukkan ID Email yang valid.',
  'mobileexits' => 'nomor ponsel sudah ada',
  'max' => 
  array (
    'string' => '',
  ),
  'required' => 'Kolom :attribute wajib diisi.',
  'min' => 
  array (
    'numeric' => 'The :attribute setidaknya harus :min.',
    'string' => 'The :attribute harus setidaknya :min karakter.',
  ),
  'not_in' => ':attribute yang dipilih tidak valid.',
  'gt' => 
  array (
    'numeric' => ':attribute harus lebih besar dari :value.',
  ),
  'digits' => ':attribute harus :digits digits.',
  'mimes' => '',
  'numeric' => '',
);

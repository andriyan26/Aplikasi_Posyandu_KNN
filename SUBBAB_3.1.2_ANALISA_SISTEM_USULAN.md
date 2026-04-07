## 3.1.2 Analisa Sistem Usulan

Sistem usulan pada penelitian ini adalah aplikasi berbasis web untuk mendukung layanan Posyandu dalam pengelolaan data balita, pencatatan pemeriksaan pertumbuhan, serta klasifikasi risiko stunting secara terstruktur. Aplikasi dikembangkan menggunakan framework Laravel dengan pendekatan role-based access control agar pemakaian sistem sesuai tugas pengguna di lapangan.

Secara konseptual, sistem dirancang untuk menjawab kebutuhan utama Posyandu, yaitu: (1) penyimpanan data balita yang rapi dan mudah ditelusuri, (2) pencatatan hasil pemeriksaan antropometri secara periodik, (3) penyajian informasi kondisi stunting secara cepat, dan (4) dukungan analisis prediktif menggunakan metode K-Nearest Neighbor (KNN) yang dipadukan dengan perhitungan Z-Score.

### A. Gambaran Umum Sistem

Sistem usulan menerapkan pola client-server berbasis web, sehingga dapat diakses melalui peramban tanpa instalasi aplikasi desktop tambahan. Seluruh data disimpan terpusat pada basis data relasional, sehingga riwayat pemeriksaan setiap balita dapat dipantau dari waktu ke waktu.

Modul utama sistem meliputi:
1. Modul autentikasi pengguna.
2. Modul manajemen data balita.
3. Modul pemeriksaan dan klasifikasi status stunting.
4. Modul evaluasi model KNN.
5. Modul manajemen pengguna (khusus admin).
6. Modul dashboard untuk ringkasan data dan visualisasi.

### B. Aktor dan Hak Akses

Sistem usulan melibatkan dua aktor utama, yaitu:

1. **Admin**
   - Mengelola data pengguna.
   - Mengakses seluruh fitur sistem (dashboard, balita, pemeriksaan, evaluasi KNN, import data).
   - Melakukan pengawasan terhadap konsistensi data.

2. **Kader**
   - Mengelola data balita.
   - Menginput pemeriksaan dan melihat hasil klasifikasi.
   - Mengakses dashboard dan evaluasi KNN sesuai kewenangan.
   - Tidak memiliki akses manajemen akun pengguna.

Pembagian hak akses ini bertujuan menjaga keamanan data serta mencegah perubahan data sensitif oleh pihak yang tidak berwenang.

### C. Analisa Proses Bisnis Sistem Usulan

Alur proses sistem usulan dapat dijelaskan sebagai berikut:

1. **Autentikasi Pengguna**  
   Pengguna melakukan login menggunakan akun terdaftar. Setelah validasi berhasil, pengguna diarahkan ke halaman dashboard.

2. **Pengelolaan Data Balita**  
   Petugas memasukkan identitas balita, meliputi nama, tanggal lahir, jenis kelamin, dan nama orang tua. Data ini menjadi master untuk seluruh proses pemeriksaan berikutnya.

3. **Input Pemeriksaan Antropometri**  
   Pada setiap kunjungan, petugas mencatat data pemeriksaan seperti berat badan, tinggi badan, lingkar lengan atas, dan lingkar kepala sesuai kebutuhan.

4. **Perhitungan dan Klasifikasi**  
   Sistem menghitung umur dalam bulan dan nilai Z-Score berdasarkan parameter pengukuran. Selanjutnya:
   - Jika data latih KNN mencukupi, sistem melakukan klasifikasi menggunakan KNN.
   - Jika data latih belum mencukupi, sistem menggunakan klasifikasi berbasis ambang Z-Score.

5. **Penyimpanan dan Pelaporan**  
   Hasil pemeriksaan dan status klasifikasi disimpan ke basis data sebagai riwayat. Data kemudian ditampilkan pada daftar pemeriksaan serta diringkas pada dashboard.

6. **Evaluasi Model KNN**  
   Sistem menyediakan fitur evaluasi performa model menggunakan data latih (akurasi, precision, recall, F1-score, confusion matrix) serta grafik perbandingan nilai K.

### D. Analisa Data dan Struktur Informasi

Entitas data utama pada sistem usulan terdiri dari:

1. **Data Pengguna (users)**  
   Menyimpan identitas akun, email, kata sandi terenkripsi, dan role pengguna.

2. **Data Balita (balitas)**  
   Menyimpan identitas utama balita sebagai referensi pemeriksaan.

3. **Data Pemeriksaan (pemeriksaans)**  
   Menyimpan data pengukuran per kunjungan, nilai Z-Score, dan hasil status stunting.

4. **Data Latih KNN (data_latihs)**  
   Menyimpan dataset berlabel untuk kebutuhan klasifikasi KNN dan evaluasi model.

Relasi data utama adalah satu balita dapat memiliki banyak catatan pemeriksaan (one-to-many), sehingga histori pertumbuhan dapat dipantau secara longitudinal.

### E. Analisa Komponen Metode

1. **Metode Z-Score**  
   Digunakan untuk kuantifikasi kondisi pertumbuhan berdasarkan deviasi pengukuran terhadap nilai acuan. Nilai ini menjadi dasar penentuan kategori risiko.

2. **Metode K-Nearest Neighbor (KNN)**  
   Digunakan untuk klasifikasi status stunting dengan prinsip kedekatan jarak (Euclidean distance) terhadap data latih. Kelas ditentukan melalui mayoritas tetangga terdekat sejumlah K.

3. **Strategi Hibrida**  
   Sistem menerapkan fallback dari KNN ke Z-Score ketika data latih belum memadai. Pendekatan ini menjaga agar sistem tetap fungsional dalam kondisi data terbatas.

### F. Analisa Kebutuhan Fungsional

Kebutuhan fungsional yang dipenuhi sistem usulan meliputi:

1. Sistem harus memvalidasi login pengguna sebelum mengakses data.
2. Sistem harus menyediakan CRUD data balita.
3. Sistem harus menyediakan input dan penyimpanan data pemeriksaan.
4. Sistem harus menghitung nilai Z-Score secara otomatis.
5. Sistem harus mengklasifikasikan status stunting secara otomatis.
6. Sistem harus menampilkan dashboard statistik dan grafik ringkasan.
7. Sistem harus menyediakan fitur evaluasi model KNN.
8. Sistem harus menerapkan pembatasan fitur berdasarkan role.

### G. Analisa Kebutuhan Nonfungsional

1. **Keamanan**: autentikasi, otorisasi berbasis role, serta proteksi data sensitif.
2. **Kinerja**: waktu respon cukup cepat untuk kebutuhan input pemeriksaan harian.
3. **Keandalan**: data tersimpan konsisten dan dapat diakses kembali sebagai riwayat.
4. **Kemudahan penggunaan**: antarmuka sederhana agar mudah dipakai petugas Posyandu.
5. **Pemeliharaan**: struktur aplikasi berbasis framework memudahkan pengembangan lanjutan.

### H. Kelebihan Sistem Usulan

1. Proses pencatatan pemeriksaan menjadi lebih tertib dan terpusat.
2. Mendukung pengambilan keputusan berbasis data melalui klasifikasi otomatis.
3. Menyediakan visualisasi ringkasan kondisi balita secara cepat.
4. Memiliki mekanisme fallback klasifikasi sehingga tetap dapat digunakan saat data latih terbatas.
5. Mendukung evaluasi parameter K untuk peningkatan model KNN.

### I. Batasan Sistem Usulan

1. Kualitas klasifikasi sangat dipengaruhi jumlah dan kualitas data latih.
2. Evaluasi model masih dapat dikembangkan agar lebih representatif (misalnya pemisahan data latih dan data uji yang lebih ketat).
3. Akurasi pendekatan Z-Score bergantung pada kelengkapan standar referensi yang digunakan.
4. Sistem masih berfokus pada lingkungan Posyandu dan belum terintegrasi dengan sistem kesehatan eksternal.

### J. Kesimpulan Analisa Sistem Usulan

Berdasarkan analisa, sistem usulan telah memenuhi kebutuhan utama digitalisasi layanan Posyandu, khususnya pada pengelolaan data balita dan deteksi risiko stunting secara lebih sistematis. Integrasi pencatatan antropometri, perhitungan Z-Score, dan klasifikasi KNN menjadikan sistem mampu memberikan dukungan keputusan yang lebih objektif. Dengan pengembangan bertahap pada kualitas data dan evaluasi model, sistem ini berpotensi menjadi fondasi yang kuat untuk peningkatan layanan pemantauan gizi balita.

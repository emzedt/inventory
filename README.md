# 📦 Proses Bisnis Inventory Stok Barang

Dokumentasi ini menjelaskan alur proses pengelolaan stok barang dalam sistem inventory, mulai dari penerimaan barang hingga audit dan reorder, untuk memastikan pengelolaan stok berjalan efisien dan akurat.

## 🎯 Tujuan

Memastikan pengelolaan stok barang berjalan **efisien**, mulai dari **penerimaan**, **penyimpanan**, hingga **pengeluaran barang**, dengan dukungan monitoring otomatis dan pelaporan yang akurat.

---

## 1️⃣ Penerimaan Barang (Barang Masuk)

### ✅ Input

* Dokumen pembelian (PO / Invoice)
* Barang fisik yang diterima dari supplier

### ⚙️ Proses

1. Gudang menerima barang berdasarkan PO.
2. Pemeriksaan kualitas dan kuantitas barang.
3. Jika sesuai:

   * Data dicatat ke **modul Barang Masuk**:

     * Jumlah barang
     * Tanggal masuk
     * Nama supplier
   * Update stok di **modul Stok Barang**.

### 📤 Output

* **Stok barang** ter-update.
* **Dokumen GRN (Goods Received Note)** dihasilkan.
* Jika barang rusak/tidak sesuai, dibuat **laporan klaim ke supplier**.

---

## 2️⃣ Penyimpanan dan Monitoring Stok

### ✅ Input

* Data barang dari penerimaan
* Parameter **stok minimum (threshold)**

### ⚙️ Proses

1. Barang disimpan di lokasi gudang yang ditentukan.
2. Sistem secara otomatis memonitor ketersediaan stok.
3. Jika stok mendekati batas minimum:

   * Sistem mengirim **notifikasi reorder** ke admin gudang.

### 📤 Output

* Posisi dan saldo stok barang selalu **ter-update**.
* **Notifikasi reorder** jika stok menipis.

---

## 3️⃣ Pengeluaran Barang (Barang Keluar)

### ✅ Input

* Permintaan barang dari divisi / cabang / pelanggan
* Dokumen pengeluaran (Delivery Order)

### ⚙️ Proses

1. Divisi mengajukan permintaan barang.
2. Admin gudang memverifikasi ketersediaan stok.
3. Barang dikeluarkan dan dicatat dalam **modul Barang Keluar**:

   * Jumlah barang keluar
   * Tanggal pengeluaran
   * Tujuan pengeluaran
4. Update stok di **modul Stok Barang**.

### 📤 Output

* Data tercatat di **Tabel Barang Keluar**
* **Stok berkurang**
* **Dokumen DO** (Delivery Order) dihasilkan

---

## 4️⃣ Audit dan Pelaporan

### ✅ Input

* Data stok (masuk, keluar, saldo)
* Parameter waktu (harian/mingguan/bulanan)

### ⚙️ Proses

1. Sistem menghasilkan laporan dari **modul Laporan Stok**:

   * Pergerakan stok
   * Stok akhir per periode
   * Lokasi penyimpanan
2. **Audit fisik berkala** untuk validasi dengan data sistem

### 📤 Output

* Laporan stok barang (PDF / Excel)
* Identifikasi selisih jika terdapat ketidaksesuaian

---

## 5️⃣ Reorder Barang

### ✅ Input

* **Notifikasi stok minimum** dari sistem

### ⚙️ Proses

1. Sistem memberikan peringatan restock
2. Admin gudang membuat **PO baru** ke supplier
3. Proses kembali ke **penerimaan barang**

### 📤 Output

* Purchase Order (PO) baru ke supplier
* Barang diterima dan **stok diperbarui kembali**

---

## 📌 Catatan Tambahan

* Semua data dicatat dan dimonitor secara real-time melalui sistem informasi inventory.
* Audit fisik dan sistem saling melengkapi untuk menjaga akurasi data.
* Proses bisnis ini mendukung integrasi dengan modul pembelian dan penjualan lainnya.

---

<img src="Downloads/ERD Revisi.drawio.png" alt="Diagram Inventory" width="500"/>

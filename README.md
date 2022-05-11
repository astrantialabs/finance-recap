<h2 align="center"> ━━━━━━  ❖  ━━━━━━ </h2>

### ❖ Informasi

**Sistem Rekapitulasi Data Keuangan Dinas Tenaga Kerja dan Transmigrasi** atau disingkat **Disnaker Finance Recap** adalah sebuah aplikasi berbasis website yang bertujuan untuk melakukan ekstraksi data-data pada sebuah excel spreadsheet untuk di manipulasi dan ditampilkan ke website.

Technology stack:

- **Web Framework:** [Laravel](https://laravel.com/) [Livewire](https://laravel-livewire.com)
- **Database:** [SQLite](https://sqlite.org/) • [MongoDB Atlas](https://www.mongodb.com/atlas/database)
- **Programming Language:** [PHP](https://www.php.net/) • [Python](https://www.python.org/)

Application structure:

Sistem arsitektur menggunakan dua microservice independen yang disebut dengan **API service** dan **WEB service**.

- **WEB service** 
  - Menggunakan konsep metode MVC (Model-View-Controller) dengan [Laravel](https://laravel.com/) sebagai web framework.
  - Menerapkan sistem user authentication dan authorization dan menggunakan [SQLite](https://sqlite.org/) sebagai database user.

- **API service**
  - Memproses & menerjemahkan Excel ke [JSON](https://www.json.org/json-en.html) dengan menggunakan [Python](https://www.python.org/) sebagai programming language.
  - Menyimpan data-data penting dengan menggunakan cloud service [MongoDB Atlas](https://www.mongodb.com/atlas/database)
  - Menggunakan [PySimpleGUI](https://pysimplegui.readthedocs.io/en/latest/) untuk mengubah pengaturan & konfigurasi program melalui aplikasi berbasis GUI

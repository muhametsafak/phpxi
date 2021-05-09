# Sürüm Notları

### v1.5.2

-   Base kütüphanesi geliştirildi. Artık kütüphaneler evrensel parametreleri için ortak tek bir kütüphane kullanıyor.
-   Bellek tüketimi ve performans iyileştirmeleri yapıldı.
-   Bazı sistemlerde Logger kütüphanesinden kaynaklanan hatalar giderildi ve PSR-3 standartlarına uygun restore edildi.
-   Filtreler içinde `$request` ve `$responsive` olarak kontrolcüye veri gönderebilirsiniz.

### v1.5.1

-   Bildirilen hatalar giderildi.
-   Dahili bir validation kütüphanesi eklendi. (Geliştirilmeye Devam Edecek)
-   Logger içinde yer tutucular kullanılabilir oldu.

## v1.5.0

-   Dahili bir PDO kütüphanesi geliştirildi.
-   Kütüphane yapıları güncellendi.
-   Bellek tüketimi azaltıldı.
-   Dahili bir Token doğrulama kütüphanesi geliştirildi.
-   Filter sistemi eklendi. Artık kontrolcülerin öncesinde ve sonrasında filtreler uygulayabileceksiniz.
-   Sistem içerisinde kullanılan `mb_` fonksiyonları bazı sistemlerde sorunlara yol açıyordı. Sistemin MBString fonksiyon ihtiyaçları için bir yardımcı yazıldı.
-   Log tutma işlemleri için dahili bir logger kütüphanesi eklendi.
-   Rota (route) geliştirildi.
-   Yeni ilk açılış arayüzü eklendi.
-   Yeni bir hata bildirme arayüzü geliştirildi.

### v1.4.3

-   Bazı sistemlerdeki uyumsuzluk sorunları giderildi.
-   Proje modelleri için "autoload" fonksiyonu oluşturuldu.

### v1.4.2

-   Bazı sistemlerde HTTP ve Route kütüphanesinde meydana gelen hatalar düzeltildi.

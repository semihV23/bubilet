# BUBİLET: Bilet Satın Alma Platformu

## Kurulum

Repoyu bilgisayarınıza klonlayın.

```bash
$ git clone https://github.com/semihV23/bubilet.git
```

Repo klasörüne girin.
```bash
cd bubilet
```

Projeyi ayağa kaldırmak için aşağıdaki komutu projenin kök dizininde çalıştırmanız yeterlidir.
```bash
$ docker-compose up --build
```

Proje varsayılan olarak http://localhost/ adresinde yayınlanmaktadır.

Veritabanı tablolarını oluşturmak için http://localhost/setup/init/ adresi ziyaret edilmelidir.

# Önemli Açıklama
Tüm isterler henüz yerine getirilmemiştir. Dolayısıyla proje henüz tamamlanmamıştır. Bir ana sayfa olmadığı gibi site içerisinde rahat gezinmek için menü de bulunmamaktadır. Geliştirme aşamasında sayfaların adreslerini öğrenmek için `index.php` sayfasını ziyaret edebilirsiniz. Orada ana yönlendiricide tanımlı olan sayfalar aktiftir.

# Yapılacaklar
### Backend
- [x] Proje dizin yapısı belirlenecek.
- [x] Veritabanı tablolarını oluşturan init dosyası eklenecek.
- [x] Kayıt olma ve oturum açma sayfaları eklenecek. Oturum yönetimi yapılacak.
- [x] Admin paneli eklenecek. Kullanıcı ve firma CRUD özellikleri eklenecek.
- [x] Firma paneli eklenecek. Firma yetkilisi atama özelliği eklenecek. Sefer CRUD özellikleri eklenecek.
- [x] Bilet satın alma sayfası eklenecek.
- [x] Satın alınan biletler Hesabım sayfasında listelenecek.
- [ ] Şehre göre bilet arama sistemi eklenecek.
- [ ] Kupon yönetimi ve kupon kullanımı sistemi eklenecek.
- [ ] PDF bilet indirme özelliği eklenecek.
- [ ] Bilet iptal özelliği eklenecek.

### Frontend
- [ ] XSS zafiyetleri giderilecek.
- [ ] Renkler, fontlar ve ikonlar eklenecek.
<?php

namespace Database\Seeders;

use App\Models\School;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data Wilayah Jawa Timur (Same as Frontend)
        $wilayahJatim = [
            "Kota Surabaya" => ["Asemrowo", "Benowo", "Bubutan", "Bulak", "Dukuh Pakis", "Gayungan", "Genteng", "Gubeng", "Gunung Anyar", "Jambangan", "Karang Pilang", "Kenjeran", "Krembangan", "Lakarsantri", "Mulyorejo", "Pabean Cantian", "Pakal", "Rungkut", "Sambikerep", "Sawahan", "Semampir", "Simokerto", "Sukolilo", "Sukomanunggal", "Tambaksari", "Tandes", "Tegalsari", "Tenggilis Mejoyo", "Wiyung", "Wonocolo", "Wonokromo"],
            "Kota Malang" => ["Blimbing", "Kedungkandang", "Klojen", "Lowokwaru", "Sukun"],
            "Kota Madiun" => ["Kartoharjo", "Manguharjo", "Taman"],
            "Kota Kediri" => ["Kota", "Mojoroto", "Pesantren"],
            "Kota Mojokerto" => ["Kranggan", "Magersari", "Prajurit Kulon"],
            "Kota Blitar" => ["Kepanjenkidul", "Sananwetan", "Sukorejo"],
            "Kota Pasuruan" => ["Bugul Kidul", "Gadingrejo", "Panggungrejo", "Purworejo"],
            "Kota Probolinggo" => ["Kademangan", "Kanigaran", "Kedopok", "Mayangan", "Wonoasih"],
            "Kota Batu" => ["Batu", "Bumiaji", "Junrejo"],
            "Kab. Bangkalan" => ["Arosbaya", "Bangkalan", "Blega", "Burneh", "Galis", "Geger", "Kamal", "Klampis", "Kokop", "Konang", "Kwanyar", "Labang", "Modung", "Sepulu", "Socah", "Tanah Merah", "Tanjung Bumi", "Tragah"],
            "Kab. Banyuwangi" => ["Bangorejo", "Banyuwangi", "Blimbingsari", "Cluring", "Gambiran", "Genteng", "Giri", "Glagah", "Glenmore", "Kabat", "Kalibaru", "Kalipuro", "Licin", "Muncar", "Pesanggaran", "Purwoharjo", "Rogojampi", "Sempu", "Siliragung", "Singojuruh", "Songgon", "Srono", "Tegaldlimo", "Tegalsari", "Wongsorejo"],
            "Kab. Blitar" => ["Bakung", "Binangun", "Doko", "Gandusari", "Garum", "Kademangan", "Kanigoro", "Kesamben", "Nglegok", "Panggungrejo", "Ponggok", "Sanankulon", "Selorejo", "Selopuro", "Srengat", "Sutojayan", "Talun", "Udanawu", "Wates", "Wlingi", "Wonodadi", "Wonotirto"],
            "Kab. Bojonegoro" => ["Balen", "Baureno", "Bojonegoro", "Bubulan", "Dander", "Gayam", "Gondang", "Kadungadem", "Kalitidu", "Kanor", "Kapas", "Kasiman", "Kedungadem", "Kepohbaru", "Malo", "Margomulyo", "Ngambon", "Ngasem", "Ngraho", "Padangan", "Purwosari", "Sekar", "Sugihwaras", "Sukosewu", "Sumberejo", "Tambakrejo", "Temayang", "Trucuk"],
            "Kab. Bondowoso" => ["Binakal", "Bondowoso", "Botolinggo", "Cermee", "Curahdami", "Grujugan", "Jambesari Darus Sholah", "Klabang", "Maesan", "Pakem", "Prajekan", "Pujer", "Sempol (Ijen)", "Sukosari", "Sumberwringin", "Taman Krocok", "Tamanan", "Tapen", "Tegalampel", "Tenggarang", "Tlogosari", "Wonosari", "Wringin"],
            "Kab. Gresik" => ["Balongpanggang", "Benjeng", "Bungah", "Cerme", "Driyorejo", "Duduksampeyan", "Dukun", "Gresik", "Kebomas", "Kedamean", "Manyar", "Menganti", "Panceng", "Sangkapura", "Sidayu", "Tambak", "Ujungpangkah", "Wringinanom"],
            "Kab. Jember" => ["Ajung", "Ambulu", "Arjasa", "Balung", "Bangsalsari", "Gumukmas", "Jelbuk", "Jenggawah", "Jombang", "Kalisat", "Kaliwates", "Kencong", "Ledokombo", "Mayang", "Mumbulsari", "Pakusari", "Panti", "Patrang", "Puger", "Rambipuji", "Semboro", "Silo", "Sukorambi", "Sukowono", "Sumberbaru", "Sumberjambe", "Sumbersari", "Tanggul", "Tempurejo", "Umbulsari", "Wuluhan"],
            "Kab. Jombang" => ["Bandar Kedungmulyo", "Bareng", "Diwek", "Gudo", "Jogoroto", "Jombang", "Kabuh", "Kesamben", "Kudu", "Megaluh", "Mojoagung", "Mojowarno", "Ngoro", "Ngusikan", "Perak", "Peterongan", "Plandaan", "Ploso", "Sumobito", "Tembelang", "Wonosalam"],
            "Kab. Kediri" => ["Badas", "Banyakan", "Gampengrejo", "Grogol", "Gurah", "Kandangan", "Kandat", "Kayen Kidul", "Kepung", "Kras", "Kunjang", "Mojo", "Ngadiluwih", "Ngancar", "Ngasem", "Pagu", "Papar", "Pare", "Plemahan", "Plosoklaten", "Puncu", "Purwoasri", "Ringinrejo", "Semen", "Tarokan", "Wates"],
            "Kab. Lamongan" => ["Babat", "Bluluk", "Brondong", "Deket", "Glagah", "Kalitengah", "Karangbinangun", "Karanggeneng", "Kedungpring", "Kembangbahu", "Lamongan", "Laren", "Maduran", "Mantup", "Modo", "Ngimbang", "Paciran", "Pucuk", "Sambeng", "Sarirejo", "Sekaran", "Solokuro", "Sugio", "Sukodadi", "Sukorame", "Tikung", "Turi"],
            "Kab. Lumajang" => ["Candipuro", "Gucialit", "Jatiroto", "Kedungjajang", "Klakah", "Kunir", "Lumajang", "Padang", "Pasirian", "Pasrujambe", "Pronojiwo", "Randuagung", "Ranuyoso", "Rowokangkung", "Senduro", "Sukodono", "Sumbersuko", "Tekung", "Tempeh", "Tempursari", "Yosowilangun"],
            "Kab. Madiun" => ["Balerejo", "Dagangan", "Dolopo", "Geger", "Gemarang", "Jiwan", "Kare", "Kebonagung", "Madiun", "Mejayan", "Pilangkenceng", "Saradan", "Sawahan", "Wonoasri", "Wungu"],
            "Kab. Magetan" => ["Barat", "Bendo", "Karangrejo", "Karas", "Kartoharjo", "Kawedanan", "Lembeyan", "Magetan", "Maospati", "Ngariboyo", "Nguntoronadi", "Panekan", "Parang", "Plaosan", "Poncol", "Sidorejo", "Sukomoro", "Takeran"],
            "Kab. Malang" => ["Ampelgading", "Bantur", "Bululawang", "Dampit", "Dau", "Donomulyo", "Gedangan", "Gondanglegi", "Jabung", "Kalipare", "Karangploso", "Kasembon", "Kepanjen", "Kromengan", "Lawang", "Ngajum", "Ngantang", "Pagak", "Pagelaran", "Pakis", "Pakisaji", "Poncokusumo", "Pujon", "Singosari", "Sumbermanjing Wetan", "Sumberpucung", "Tajinan", "Tirtoyudo", "Tumpang", "Turen", "Wagir", "Wajak", "Wonosari"],
            "Kab. Mojokerto" => ["Bangsal", "Dawarblandong", "Dlanggu", "Gedeg", "Gondang", "Jatirejo", "Jetis", "Kemlagi", "Kutorejo", "Mojoanyar", "Mojosari", "Ngoro", "Pacet", "Pungging", "Puri", "Sooko", "Trawas", "Trowulan"],
            "Kab. Nganjuk" => ["Bagor", "Baron", "Berbek", "Gondang", "Jatikalen", "Kertosono", "Lengkong", "Loceret", "Nganjuk", "Ngetos", "Ngluyu", "Ngronggot", "Pace", "Patianrowo", "Prambon", "Rejoso", "Sawahan", "Sukomoro", "Tanjunganom", "Wilangan"],
            "Kab. Ngawi" => ["Bringin", "Geneng", "Gerih", "Jogorogo", "Karanganyar", "Karangjati", "Kasreman", "Kedunggalar", "Kendal", "Kwadungan", "Mantingan", "Ngawi", "Ngrambe", "Padas", "Pangkur", "Paron", "Pitu", "Sine", "Widodaren"],
            "Kab. Pacitan" => ["Arjosari", "Bandar", "Donorojo", "Kebonagung", "Nawangan", "Ngadirojo", "Pacitan", "Pringkuku", "Punung", "Sudimoro", "Tegalombo", "Tulakan"],
            "Kab. Pamekasan" => ["Batu Marmar", "Galis", "Kadur", "Larangan", "Pademawu", "Pakong", "Palengaan", "Pamekasan", "Pasean", "Pegantenan", "Proppo", "Tlanakan", "Waru"],
            "Kab. Pasuruan" => ["Bangil", "Beji", "Gempol", "Gondang Wetan", "Grati", "Kejayan", "Kraton", "Lekok", "Lumbang", "Nguling", "Pandaan", "Pasrepan", "Pohjentrek", "Prigen", "Purwodadi", "Purwosari", "Puspo", "Rejoso", "Rembang", "Sukorejo", "Tosari", "Tutur", "Winongan", "Wonorejo"],
            "Kab. Ponorogo" => ["Babadan", "Badegan", "Balong", "Bungkal", "Jambon", "Jenangan", "Jetis", "Kauman", "Mlarak", "Ngebel", "Ngrayun", "Ponorogo", "Pudak", "Pulung", "Sambit", "Sampung", "Sawoo", "Siman", "Slahung", "Sooko", "Sukorejo"],
            "Kab. Probolinggo" => ["Bantaran", "Banyuanyar", "Besuk", "Dringu", "Gading", "Gending", "Kotaanyar", "Kraksaan", "Krejengan", "Krucil", "Kuripan", "Leces", "Lumbang", "Maron", "Paiton", "Pajarakan", "Pakuniran", "Sukapura", "Sumber", "Sumberasih", "Tegalsiwalan", "Tiris", "Tongas", "Wonomerto"],
            "Kab. Sampang" => ["Banyuates", "Camplong", "Jrengik", "Karangpenang", "Kedungdung", "Ketapang", "Omben", "Pangarengan", "Robatal", "Sampang", "Sokobanah", "Sreseh", "Tambelangan", "Torjun"],
            "Kab. Sidoarjo" => ["Balongbendo", "Buduran", "Candi", "Gedangan", "Jabon", "Krembung", "Krian", "Porong", "Prambon", "Sedati", "Sidoarjo", "Sukodono", "Taman", "Tanggulangin", "Tarik", "Tulangan", "Waru", "Wonoayu"],
            "Kab. Situbondo" => ["Arjasa", "Asembagus", "Banyuglugur", "Banyuputih", "Besuki", "Bungatan", "Jangkar", "Jatibanteng", "Kapongan", "Kendit", "Mangaran", "Mlandingan", "Panarukan", "Panji", "Situbondo", "Suboh", "Sumbermalang"],
            "Kab. Sumenep" => ["Ambunten", "Arjasa", "Batang Batang", "Batuan", "Batuputih", "Bluto", "Dasuk", "Dungkek", "Ganding", "Gapura", "Gayam", "Giligenting", "Guluk-Guluk", "Kalianget", "Kangayan", "Kota Sumenep", "Lenteng", "Manding", "Masalembu", "Nonggunong", "Pasongsongan", "Pragaan", "Raas", "Rubaru", "Sapeken", "Saronggi", "Talango"],
            "Kab. Trenggalek" => ["Bandungan", "Dongko", "Durenan", "Gandusari", "Kampak", "Karangan", "Munjungan", "Panggul", "Pogalan", "Pule", "Suruh", "Trenggalek", "Tugu", "Watulimo"],
            "Kab. Tuban" => ["Bancar", "Bangilan", "Grabagan", "Jatirogo", "Jenu", "Kenduruan", "Kerek", "Merakurak", "Montong", "Palang", "Parengan", "Plumpang", "Rengel", "Semanding", "Senori", "Singgahan", "Soko", "Tambakboyo", "Tuban", "Widang"],
            "Kab. Tulungagung" => ["Bandung", "Besuki", "Boyolangu", "Campurdarat", "Gondang", "Kalidawir", "Karangrejo", "Kauman", "Kedungwaru", "Ngantru", "Ngunut", "Pagerwojo", "Pakel", "Pucanglaban", "Rejotangan", "Sendang", "Sumbergempol", "Tanggunggunung", "Tulungagung"]
        ];

        foreach ($wilayahJatim as $kotaKab => $kecamatans) {
            foreach ($kecamatans as $kecamatan) {
                // Buat 1 SD
                School::create([
                    'npsn' => fake()->unique()->numerify('########'),
                    'nama_sekolah' => "SDN {$kecamatan} 1 ({$kotaKab})",
                    'jenjang' => 'SD',
                    'kota_kab' => $kotaKab,
                    'kecamatan' => $kecamatan,
                    'kuota' => rand(30, 90),
                    'detail' => "Sekolah Dasar Negeri di kecamatan {$kecamatan}, {$kotaKab}."
                ]);

                // Buat 1 SMP
                School::create([
                    'npsn' => fake()->unique()->numerify('########'),
                    'nama_sekolah' => "SMPN " . rand(1, 5) . " {$kecamatan} ({$kotaKab})",
                    'jenjang' => 'SMP',
                    'kota_kab' => $kotaKab,
                    'kecamatan' => $kecamatan,
                    'kuota' => rand(100, 300),
                    'detail' => "Sekolah Menengah Pertama Negeri di kecamatan {$kecamatan}."
                ]);

                // Buat SMA (Hanya di beberapa kecamatan untuk variasi)
                if (rand(0, 1)) {
                    School::create([
                        'npsn' => fake()->unique()->numerify('########'),
                        'nama_sekolah' => "SMAN " . rand(1, 3) . " {$kotaKab} (Kec. {$kecamatan})",
                        'jenjang' => 'SMA',
                        'kota_kab' => $kotaKab,
                        'kecamatan' => $kecamatan,
                        'kuota' => rand(150, 400),
                        'detail' => "Sekolah Menengah Atas Negeri di kecamatan {$kecamatan}."
                    ]);
                }
            }
        }
    }
}

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>blitznet</title>
    <link rel="shortcut icon" href="{{ URL::asset('assets/images/favicon-32x32.png') }}" />
    <link href="{{ URL::asset('front-assets/css/front/bootstrap.min.css') }}" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

</head>
<style>
    th,
    td {
        padding: 15px;
        text-align: left;
        border: 1px solid black;
        vertical-align: top;

    }

    table {
        border-collapse: collapse;
        border: 1px solid black;
    }

    .printBtn {
        float: right;
    }

    .hide {
        display: none;
    }

</style>

<body>
    <div class="row col-12 mt-5">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div class="printBtn"><a class="btn btn-primary" id="printPdf">Print</a></div>
            <div class="text-center">
                <h2><u>JOINING AND NON DISCLOSURE AGREEMENT</u></h2>
                <table class="mt-5">
                    <tr>
                        <td>
                            Perjanjian ini disepakati antara pada tanggal <u>{{ date('d-m-Y') }}</u>:
                            <br><br><br>
                            1. <b>PT Blitznet Upaya Indonesia,</b> suatu Perseroan yang didirikan berdasarkan hukum
                            Negara
                            Republik Indonesia, beralamat di Gedung Centennial Tower Lt. 29 Unit D dan E, Jl. Jendral
                            Gatot Subroto RT02/RW02 Kav 24-25, Karet Semanggi, Setia Budi, Jakarta Selatan, DKI Jakarta,
                            (selanjutnya disebut “<b>BLITZNET</b>”);
                            <br><br>
                            2. <b>{{ isset($companyName) ? $companyName : '' }}</b>, suatu Perseroan yang didirikan
                            berdasarkan hukum Negara Republik/[. ]
                            Indonesia,
                            beralamat di[. ] (selanjutnya disebut “<b>Supplier</b>”).
                            <br><br>
                            BLITZNET dan Supplier secara bersama-sama disebut Para Pihak dan sendiri-sendiri sebagai
                            Pihak.
                        </td>
                        <td>
                            This Agreement is entered into by and between on <u>{{ date('d-m-Y') }}</u>:
                            <br><br><br>
                            1. <b> Blitznet Upaya Indonesia,</b> a limited liability company established under the laws
                            of
                            the Republic of Indonesia, having its address at Centennial Tower Building, 29th Floor Unit
                            D and E, Jendral Gatot Subroto Street RT02/RW02 Kav 24-25, Karet Semanggi, Setia Budi, South
                            Jakarta, DKI Jakarta, (hereinafter referred to as "<b>BLITZNET</b>");
                            <br><br>
                            2.<b> {{ $companyName }}</b>, a limited liability company established under the laws of
                            the
                            Republic of
                            Indonesia, having its address at [. ] (hereinafter referred to as "<b>Supplier</b>").
                            <br><br>
                            BLITZNET and Supplier hereinafter collectively referred to as the Parties and individually
                            as Party.

                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center">
                            <b>MENERANGKAN TERLEBIH DAHULU/WHEREAS</b>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            1. The Parties bermaksud bekerjasama dan menuangkan syarat dan ketentuan rencana
                            kerjasama untuk Layanan dalam Perjanjian ini (“Kerjasama Layanan”)
                            <br><br>
                            2. Dengan mempertimbangkan bahwa Layanan yang diberikan oleh Blitznet kepada Supplier adalah
                            untuk mencatatkan, menjual, serta kegiatan penjualan online lainnya melalui situs :
                            www.blitznet.co.id atau domain internet lainnya, dan melalui aplikasi BLITZNET yang dikelola
                            dan dimiliki oleh Blitzet, termasuk mengumpulkan, melakukan rekonsiliasi dan melakukan semua
                            kegiatan transaksi yang melibatkan Supplier melalui platform sebagai agen pemroses
                            pembayaran untuk Supplier, dan/atau produksi konten yang terkait, melakukan aktivitas
                            transaksi penjualan dan atau aktivitas layanan lainnya yang dikembangkan oleh Blitznet dari
                            waktu ke waktu untuk Supplier (“<b>Layanan</b>”) dan Supplier menerima data, dokumen,
                            informasi,
                            rahasia dagang dan segala informasi yang diterbitkan dan diberikan oleh Blitznet (secara
                            bersama-sama disebut sebagai “<b>Informasi Rahasia</b>”),
                            <br><br>
                            Para Pihak menyanggupi dan menyetujui hal-hal sebagai berikut:
                        </td>
                        <td>
                            1. The Parties wish to cooperate and set out the terms and conditions of the cooperation
                            plan for the Services in this Agreement (“Cooperation”)
                            <br><br>
                            2. In consideration of provided by Blitznet to Supplier are to record, sell, and other
                            online sales activities through the site: www.blitznet.co.id or other internet domains, and
                            through Blitznet which are managed and owned by Blitznet, including collecting, reconciling
                            and carrying out all transaction activities involving Supplier through the platform as a
                            payment processing agent for Supplier, and / or production of related content, conduct sales
                            transaction activities and or other service activities developed by Blitznet from time to
                            time for Supplier (“<b>Services</b>”) and Supplier receives data, documents, informatioan
                            and trade
                            secrets and other information issued and given by Blitznet (collectively referred to as
                            “<b>Confidential Information</b>”),
                            <br><br>
                            Now therefore:
                            the Parties undertakes and agrees as follows:

                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            1. Supplier bermaksud untuk menggunakan Layanan dan BLITZNET bersedia memberikan Layanan
                            kepada Supplier.
                            <br><br>
                            2. Para Pihak akan mengatur lebih lanjut ketentuan terkait Layanan dalam perjanjian supplier
                            tersendiri, termasuk ketentuan mengenai biaya.
                            <br><br>
                            3. Sebagai bahan pelaksanaan Layanan lebih lanjut, Supplier bersedia memperlakukan Informasi
                            Rahasia sesuai ketentuan Perjanjian ini sebagai berikut:
                            <br>
                            a. Seluruh Informasi Rahasia harus dijaga kerahasiaannya
                            dengan ketat dan tidak boleh
                            diungkapkan oleh Supplier tanpa izin tertulis dari BLITZNET.

                        </td>
                        <td>
                            1. The Supplier intends to use the Services and BLITZNET agrees to provide Services to the
                            Supplier.
                            <br><br>
                            2. The Parties agree to set out the terms and conditions of the Services in separate
                            supplier agreement, including in relation to fees.
                            <br><br>
                            3. As a consideration on providing the Services, the Supplier agrees to treat the
                            Confidential Information in accordance with this Agreement as follows:
                            <br>

                            a. All Confidential Information shall be kept
                            strictly confidential and shall not be
                            disclosed by Supplier without BLITZNET’s written consent.
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                        </td>
                    </tr>
                    <tr>
                        <td>b. Supplier tidak boleh menggunakan Informasi Rahasia untuk tujuan apapun selain untuk
                            Kerjasama Layanan. </td>
                        <td>b. Supplier shall not use the Confidential Information for any purpose whatsoever other than
                            to Services Cooperation.
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                        </td>
                    </tr>
                    <tr>
                        <td>c. Seluruh Informasi Rahasia tetap merupakan milik BLITZNET, dan Supplier harus segera
                            mengembalikan seluruh Informasi Rahasia tersebut ketika BLITZNET meminta kepada Supplier
                            untuk melakukan hal demikian. Pada saat yang sama, Supplier harus menghancurkan
                            salinan-salinan atau analisa-analisa yang dibuat dari Informasi Rahasia.</td>
                        <td>c. All Confidential Information shall remain the exclusive property of BLITZNET, and
                            Supplier shall promptly return all of it when BLITZNET asks Supplier to do so. At the same
                            time Supplier shall destroy any further copies or analyses made from the Confidential
                            Information.
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                        </td>
                    </tr>
                    <tr>
                        <td>d. Supplier harus memastikan bahwa para pegawai dan para agennya serta setiap rekanan dan
                            para pegawai dan para agen mereka, akan memenuhi kewajiban-kewajiban kerahasiaan seperti
                            dijelaskan di dalam dokumen ini.</td>
                        <td>d. Supplier shall ensure that its employees and agents, and any partners and their employees
                            and agents, comply with the confidentiality obligations set out in this document.
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                        </td>
                    </tr>
                    <tr>
                        <td>e. Supplier hanya boleh membuka Informasi Rahasia kepada setiap entitas selain dari yang
                            disebutkan di huruf d dengan persetujuan tertulis terlebih dahuliu dari BLITZNET. Supplier
                            menyanggupi untuk memastikan bahwa seluruh entitas yang disetujui tersebut akan memenuhi
                            kesepakatan - kesepakatan kerahasiaan seperti halnya Perjanjian ini.</td>
                        <td>e. Supplier shall only disclose Confidential Information to any entity other than those
                            mentioned in letter d with prior written consent from BLITZNET. Supplier undertakes to
                            ensure that all such entities shall comply with their confidentiality undertakings.
                        </td>
                    </tr>
                    <tr>
                        <td>
                            4. Perjanjian ini diatur berdasarkan hukum negara Republik Indonesia.
                            <br><br>
                            5. Bahasa yang digunakan untuk Perjanjian ini adalah Bahasa Indonesia.
                            <br><br>
                            6. Perjanjian ini berakhir dengan kesepakatan tertulis Para Pihak atau setelah perjanjian
                            supplier ditandatangani antara Para Pihak
                        </td>
                        <td>
                            4. This Agreement shall be governed by the laws of the Republic of Indonesia.
                            <br><br>
                            5. Bahasa Indonesia shall be the governing language of this Agreement.
                            <br><br>
                            6. This Agreement shall terminate by written agreement of the Parties or at the signing of
                            the supplier agreement by the Parties.
                        </td>
                    </tr>
                    <tr>
                        <td>Untuk dan atas nama BLITZNET</td>
                        <td>For and on behalf of Supplier: </td>
                    </tr>
                    <tr>
                        <td>
                            Company Name: {{ isset($companyName) ? $companyName : '' }}
                            <br>
                            Name: {{ isset($name) ? $name : '' }}
                        </td>
                        <td>
                            Company Name: {{ isset($companyName) ? $companyName : '' }}
                            <br>
                            Name: {{ isset($name) ? $name : '' }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>

                    </tr>
                </table>

            </div>
        </div>
        <div class="col-md-1"></div>
    </div>
    <script src="{{ URL::asset('front-assets/js/front/bootstrap.bundle.min.js') }}"></script>

</body>
<script>
    $(document).on('click', '#printPdf', function(e) {
        e.preventDefault();
        $('.printBtn').addClass('hide');
        window.print();
    });
</script>

</html>

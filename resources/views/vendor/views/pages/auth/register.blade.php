<x-auth-layout>

    <!--begin::Form-->
    <form class="form w-100" novalidate="novalidate" id="kt_sign_up_form" data-kt-redirect-url="{{ route('login') }}"
        action="{{ route('register') }}">
        @csrf
        <!--begin::Heading-->
        <div class="text-center mb-11">
            <!--begin::Title-->
            <h1 class="text-gray-900 fw-bolder mb-3">
                Sign Up
            </h1>
            <!--end::Title-->
        </div>
        <!--begin::Heading-->

        <!--begin::Input group--->
        <div class="fv-row mb-6">
            <!--begin::Name-->
            <input type="text" placeholder="Name" name="name" autocomplete="off"
                class="form-control bg-transparent" />
            <!--end::Name-->
        </div>

        <!--begin::Input group--->
        <div class="fv-row mb-6">
            <!--begin::Email-->
            <input type="text" placeholder="Email" name="email" autocomplete="off"
                class="form-control bg-transparent" />
            <!--end::Email-->
        </div>

        <!--begin::Input group--->
        <div class="fv-row mb-6">
            <!--begin::Whatsapp-->
            <input type="text" placeholder="Whatsapp" name="whatsapp" autocomplete="on"
                class="form-control bg-transparent" />
            <!--end::Whatsapp-->
        </div>

        <!--begin::Input group-->
        <div class="fv-row mb-6" data-kt-password-meter="true">
            <!--begin::Wrapper-->
            <div class="mb-1">
                <!--begin::Input wrapper-->
                <div class="position-relative mb-3">
                    <input class="form-control bg-transparent" type="password" placeholder="Password" name="password"
                        autocomplete="off" />

                    <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                        data-kt-password-meter-control="visibility">
                        <i class="bi bi-eye-slash fs-2"></i>
                        <i class="bi bi-eye fs-2 d-none"></i>
                    </span>
                </div>
                <!--end::Input wrapper-->

                <!--begin::Meter-->
                <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                </div>
                <!--end::Meter-->
            </div>
            <!--end::Wrapper-->

            <!--begin::Hint-->
            <div class="text-muted">
                Use 8 or more characters with a mix of letters, numbers & symbols.
            </div>
            <!--end::Hint-->
        </div>
        <!--end::Input group--->

        <!--end::Input group--->
        <div class="fv-row mb-8">
            <!--begin::Repeat Password-->
            <input placeholder="Repeat Password" name="password_confirmation" type="password" autocomplete="off"
                class="form-control bg-transparent" />
            <!--end::Repeat Password-->
        </div>
        <!--end::Input group--->

        <!--begin::Input group--->
        <div class="fv-row mb-10">
            <div class="form-check form-check-custom form-check-solid form-check-inline">
                <input class="form-check-input" type="checkbox" name="toc" value="1" />

                <label class="form-check-label fw-semibold text-gray-700 fs-6">
                    I Agree &

                    <a href="#" data-bs-toggle="modal" data-bs-target="#terms" class="ms-1 link-primary">Terms and
                        conditions</a>.
                </label>
            </div>
        </div>
        <!--end::Input group--->

        <!-- Start of Terms and Conditions Modal -->
        <div class="modal fade" id="terms" tabindex="-1" aria-labelledby="terms" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="terms">Terms</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <ol class="text-justify" type="1">
                            <li>Anda bertanggung jawab penuh terhadap penggunaan Layanan Anda sendiri. Selain informasi,
                                produk,
                                atau
                                layanan yang disediakan oleh S-Network, baik S-Network maupun afiliasinya tidak
                                mengendalikan, tidak
                                menyediakan, tidak menjalankan, atau dalam cara apapun tidak bertanggung jawab terhadap
                                seluruh
                                informasi, produk, atau layanan yang dapat diakses melalui Layanan Anda. S-Network tidak
                                mendukung
                                maupun bertanggung jawab terhadap akurasi konten dari pihak ketiga, dan Anda setuju
                                bahwa S-Network
                                tidak bertanggung jawab untuk segala kerugian atau kerusakan yang disebabkan karena
                                penggunaan Anda
                                atas
                                kepercayaan terhadap konten tersebut. Anda memiliki tanggung jawab terhadap informasi
                                atau konten
                                yang
                                Anda posting ke situs atau newsgroup di internet, termasuk namun tidak terbatas pada
                                posting ke
                                situs
                                web baik yang berada di peralatan S-Network maupun tidak, posting ke newsgroup, dan
                                partisipasi di
                                sesi chat online manapun. Anda setuju untuk menanggung kerugian dan menjaga S-Network
                                dan seluruh
                                karyawannya, dan customer dan pelanggan lain terhadap klaim, kerugian, biaya, tanggung
                                jawab,
                                kerusakan,
                                atau biaya yang timbul dari posting Anda.</li>
                            <li> Melanjutkan identifikasi spesifik pada konten yang diduga melanggar dan di lokasi
                                S-Network
                                tempat
                                konten itu ditemukan. Setelah menerima pemberitahuan tertulis, S-Network secepatnya akan
                                menghapus
                                atau memblokir akses konten yang diduga melanggar dan memberikan pemberitahuan kepada
                                orang yang
                                melakukan posting konten tersebut. Jika S-Network menerima pemberitahuan dari orang itu
                                yang
                                menunjukkan bahwa pengaduan pelanggaran terjadi karena kesalahan atau salah
                                identifikasi, maka
                                S-Network akan mengirim Anda salinan pemberitahuan tersebut. Kecuali Anda memberitahukan
                                S-Network
                                tindakan pengadilan yang sesuai untuk menahan tuduhan pelanggaran, maka konten tersebut
                                akan
                                dikembalikan atau dengan kebijakan, bisa diakses kembali.</li>
                            <li> Anda setuju bahwa, jika Anda menggunakan Layanan untuk mengirim atau menerima
                                komunikasi suara,
                                S-Network tidak bertindak sebagai perusahaan kurir telekomunikasi atau perusahaan
                                telepon, sehingga
                                tidak ada representasi yang dibuat S-Network mengenai kesesuaian Layanan untuk
                                penggunaan suara
                                tersebut, dan bahwa semua resiko sambungan, kualitas transmisi, dan ketepatan komunikasi
                                adalah
                                semata-mata tanggung jawab Anda, dan bahwa S-Network tidak bertanggung jawab untuk
                                setiap bentuk
                                kegagalan atau kurangnya kualitas penggunaan Layanan tersebut.</li>
                            <li> Anda setuju untuk bertanggung jawab terhadap segala kerusakan atau hilangnya layanan
                                yang
                                membahayakan S-Network sebagai akibat dari spamming atau pelanggaran lain. Kerusakan ini
                                termasuk,
                                namun tidak terbatas pada, sistem shutdown, serangan balasan atau pembanjiran data, dan
                                hilangnya
                                susunan peering. Anda setuju bawah S-Network dapat mengajukan klaim melawan Anda di
                                pengadilan.</li>
                        </ol>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Terms and Conditions Modal -->

        <!--begin::Submit button-->
        <div class="d-grid mb-10">
            <button type="submit" id="kt_sign_up_submit" class="btn btn-primary">
                @include('partials/general/_button-indicator', ['label' => 'Sign Up'])
            </button>
        </div>
        <!--end::Submit button-->

        <!--begin::Sign up-->
        <div class="text-gray-500 text-center fw-semibold fs-6">
            Already have an Account?

            <a href="/login" class="link-primary fw-semibold">
                Sign in
            </a>
        </div>
        <!--end::Sign up-->
    </form>
    <!--end::Form-->
    <style>
        .text-justify {
            text-align: justify;
        }
    </style>
</x-auth-layout>

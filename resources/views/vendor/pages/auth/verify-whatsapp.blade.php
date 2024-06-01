<x-auth-layout>
    <div class="d-flex flex-center flex-column align-items-stretch h-lg-100 w-md-400px">
        <!--begin::Wrapper-->
        <div class="d-flex flex-center flex-column flex-column-fluid pb-15 pb-lg-20">
            <!--begin::Form-->
            <form class="form w-100 mb-13" method="POST" action="{{ route('verification.whatsapp.verify') }}"
                id="kt_sing_in_two_factor_form">
                @csrf
                <!--begin::Icon-->
                <div class="text-center mb-10">
                    <img alt="Logo" class="mh-125px" src="assets/media/svg/misc/smartphone-2.svg" />
                </div>
                <!--end::Icon-->
                <!--begin::Heading-->
                <div class="text-center mb-10">
                    <!--begin::Title-->
                    <h1 class="text-gray-900 mb-3">Whatsapp Verification</h1>
                    <!--end::Title-->
                    <!--begin::Sub-title-->
                    <div class="text-muted fw-semibold fs-5 mb-5">Enter the verification code we sent to
                    </div>
                    <!--end::Sub-title-->
                    <!--begin::Mobile no-->
                    <div class="fw-bold text-gray-900 fs-3">********{{ substr($user->whatsapp, -4) }}</div>
                    <!--end::Mobile no-->
                </div>
                <!--end::Heading-->
                <!--begin::Section-->
                <div class="mb-10">
                    <!--begin::Label-->
                    <div class="fw-bold text-start text-gray-900 fs-6 mb-1 ms-1">Type your 6 digit security
                        code</div>
                    <!--end::Label-->
                    <!--begin::Input group-->
                    <div id="expected-code" data-expected-code="{{ Auth::user()->whatsapp_verification_code }}"></div>
                    <div class="mb-10">
                        <div class="d-flex flex-wrap flex-stack">
                            @for ($i = 1; $i <= 6; $i++)
                                <div>
                                    <label for="code_{{ $i }}" class="sr-only">Digit
                                        {{ $i }}</label>
                                    <input type="text" id="code_{{ $i }}" name="code_{{ $i }}"
                                        maxlength="1"
                                        class="form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2"
                                        value="" />
                                </div>
                            @endfor
                        </div>
                    </div>
                    <!--end::Input group-->
                </div>
                <!--end::Section-->
                <!--begin::Submit-->
                <div class="d-flex flex-center">
                    <button type="submit" id="kt_sing_in_two_factor_submit" class="btn btn-lg btn-primary fw-bold">
                        <span class="indicator-label">Submit</span>
                        <span class="indicator-progress">Please wait...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                </div>
                <!--end::Submit-->
            </form>
            <!--end::Form-->
            <!--begin::Notice-->
            <div class="text-center fw-semibold fs-5">
                <span class="text-gray-500 me-1">Didn’t get the code?</span>
                <form method="POST" action="{{ route('resend.whatsapp.verification.code') }}"
                      id="resend-verification-form" style="display:inline;">
                    @csrf
                    <button type="submit" class="link-primary fw-semibold fs-5" id="resend-verification-button"
                            style="background:none; border:none; color:inherit; cursor:pointer;">Resend
                    </button>
                </form>
            </div>
            <div class="text-center fw-semibold fs-5">
                <span class="text-gray-500 me-1">Or <a href="#" data-bs-toggle="modal"
                        data-bs-target="#changePhoneModal" class="link-primary fs-5 me-1">change</a>phone number</span>
            </div>
            <!--end::Notice-->
        </div>
        <!--end::Wrapper-->
    </div>

    <!--begin::Change Phone Modal-->
    <div class="modal fade" id="changePhoneModal" tabindex="-1" aria-labelledby="changePhoneModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-left">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bold">Change Phone Number</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                </div>
                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                    <form method="POST" action="{{ route('user.change-phone') }}" id="change-phone-form">
                        @csrf
                        <div
                            class="notice d-flex bg-light-primary rounded border-primary border border-dashed mb-9 p-6">
                            <i class="ki-duotone ki-information fs-2tx text-primary me-4">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                            <div class="d-flex flex-stack flex-grow-1">
                                <div class="fw-semibold">
                                    <div class="fs-6 text-gray-700">Please note that a valid phone number with active
                                        whatsapp is required
                                        to complete the whatsapp verification.</div>
                                </div>
                            </div>
                        </div>
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-semibold form-label mb-2">
                                <span class="required">Phone Number</span>
                            </label>
                            <input class="form-control form-control-solid" autocomplete="off"
                                placeholder="Enter new phone number" name="phone" value="" />
                        </div>
                        <div class="modal-footer flex-center">
                            <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Discard</button>
                            <button type="button" class="btn btn-primary" id="submit-change-phone-button"
                                onclick="checkPhone()">
                                <span class="indicator-label">Submit</span>
                                <span class="indicator-progress" style="display: none;">
                                    Please wait... <span
                                        class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--end::Change Phone Modal-->

    <style>
        .modal-dialog-left {
            position: absolute;
            left: 0;
            top: 0;
            margin: 0;
            height: 100%;
            width: 50%;
            max-width: none;
        }

        .modal-dialog-left .modal-content {
            height: 100%;
            border: 0;
            border-radius: 0;
        }
    </style>
    <script>
        var hostUrl = "assets/";
    </script>
    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    <script src="assets/plugins/global/plugins.bundle.js"></script>
    <script src="assets/js/scripts.bundle.js"></script>
    <!--end::Global Javascript Bundle-->
    <!--begin::Custom Javascript(used for this page only)-->
    <script src="assets/js/custom/authentication/sign-in/two-factor.js"></script>
    <!--end::Custom Javascript-->
    <!--end::Javascript-->
</x-auth-layout>

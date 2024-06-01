<x-auth-layout>
    <!--begin::Verify Email Form-->
    <div class="w-100">
        <div class="text-center mb-11">
            <!--begin::Title-->
            <h1 class="text-gray-900 fw-bolder mb-3">Verify Email</h1>
            <!--end::Title-->
            <!--begin::Subtitle-->
            <div class="text-gray-500 fw-semibold fs-6">
                Thanks for signing up! Before getting started, could you verify your email address by clicking on the
                link we just emailed to you? If you didn't receive the email, we will gladly send you another.
            </div>
            <!--begin::Session Status-->
            @if (session('status') === 'verification-link-sent')
                <p class="font-medium text-sm text-gray-500 mt-4">
                    {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                </p>
            @endif
            <!--end::Session Status-->
        </div>

        <!--begin::Actions-->
        <div class="d-flex flex-wrap justify-content-center pb-lg-0">
            <form method="POST" action="{{ route('verification.send') }}" id="resend-verification-form"
                data-kt-redirect-url="{{ route('login') }}>
                @csrf
                <button type="submit"
                class="btn btn-lg btn-primary fw-bolder me-4" id="resend-verification-button">
                <span class="indicator-label">{{ __('Resend Verification Email') }}</span>
                <span class="indicator-progress" style="display: none;">
                    Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-lg btn-light-primary fw-bolder me-4">{{ __('Log out') }}</button>
            </form>
        </div>
        <!--end::Actions-->

        <!--begin::Change Email Link-->
        <div class="text-center mt-5">
            Or you can <a href="#" data-bs-toggle="modal" data-bs-target="#changeEmailModal"
                class="text-primary fw-bold">change your email address</a>.
        </div>
        <!--end::Change Email Link-->

    </div>
    <!--end::Verify Email Form-->

    <!--begin::Change Email Modal-->
    <div class="modal fade" id="changeEmailModal" tabindex="-1" aria-labelledby="changeEmailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-left">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bold">Change Email Address</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                </div>
                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                    <form method="POST" action="{{ route('user.change-email') }}" id="change-email-form">
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
                                    <div class="fs-6 text-gray-700">Please note that a valid email address is required
                                        to complete the email verification.</div>
                                </div>
                            </div>
                        </div>
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-semibold form-label mb-2">
                                <span class="required">Email Address</span>
                            </label>
                            <input class="form-control form-control-solid" autocomplete="off"
                                placeholder="Enter new email address" name="email" value="" />
                        </div>
                        <div class="modal-footer flex-center">
                            <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Discard</button>
                            <button type="button" class="btn btn-primary" id="submit-change-email-button"
                                onclick="checkEmail()">
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
</x-auth-layout>

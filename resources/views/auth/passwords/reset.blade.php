@extends('front.layouts.app')

@section('content')
    <header>
        <div class="heroOverlay backaldrin">
            <img src="{{ asset('front/images/ourCategories.png') }}" alt="contact us banner">
        </div>


        <article class="heroFeedback">
            <h2 class="intro" data-aos="fade-up">Forgot Password</h2>
            <p data-aos="fade-up" data-aos-delay="200">
                Enter your registered email address and we'll send you a secure OTP code to reset your password and regain
                access to your Backaldrin USA account.
            </p>
        </article>
    </header>

    <section class="registration applicationForm" id="signUp">
        <div class="container" data-aos="zoom-in-up">
            <img class="logo" src="{{ asset('front/images/logo.png') }}" alt="logo">

            <form id="forgotPasswordEmailForm" action="{{ route('front.forgot-password.store') }}" method="POST">
                @csrf


                <article>
                    <h4> E-mail<span>*</span></h4>
                    <div class="inputHolder">
                        <input required type="email" name="email" id="email" placeholder="yourmail@gmail.com">
                        <label for="email"><i class="fa-solid fa-envelope"></i></label>
                    </div>
                </article>


                <button type="submit" class="contact">confirm email
                </button>
            </form>
            <div class="formFooter">

                <span>Don't have an account yet?<a href="{{ route('front.register') }}">Sign Up</a></span>
                <a class="goBack" href="{{ route('login') }}"><i class="fa-solid fa-arrow-right-long"></i></a>

            </div>
        </div>
    </section>
@endsection

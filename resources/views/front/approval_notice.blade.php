@extends('front.layouts.app')

@section('content')
    <main class="main-content" style="min-height: 80vh; display: flex; align-items: center; justify-content: center;">
        <div class="approval-card">

            @if (session('success') || auth()->user()->portfolio_link || auth()->user()->portfolio_pdf)
                <div class="icon-wrapper">
                    <i class="fa-solid fa-hourglass-half"></i>
                </div>

                <h1>Account Pending Approval</h1>

                <p class="description">
                    Hello <strong>{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</strong>,<br>
                    Thank you for providing your information. Your account is currently under review by our administrators.
                    You will gain access to your dashboard once your account has been approved.
                </p>

                <div class="info-box">
                    <h3>What happens next?</h3>
                    <ul>
                        <li>
                            <i class="fa-solid fa-check-circle"></i> We will review your registration
                            details.
                        </li>
                        <li>
                            <i class="fa-solid fa-check-circle"></i> You will be notified via email upon
                            approval.
                        </li>
                    </ul>
                </div>

                <a href="{{ route('front.home') }}" class="btn-primary">
                    <i class="fa-solid fa-house"></i> Return to Home
                </a>
            @else
                <div class="icon-wrapper">
                    <i class="fa-solid fa-file-arrow-up"></i>
                </div>

                <h1>Complete Profile</h1>

                <p class="description">
                    Please upload your portfolio (PDF) or provide a link to your work to proceed.
                </p>

                <form action="{{ route('front.approval.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="portfolio_link" class="form-label">Portfolio Link</label>
                        <input type="url" name="portfolio_link" id="portfolio_link" class="form-control"
                            placeholder="https://your-portfolio.com">
                        @error('portfolio_link')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="portfolio_pdf" class="form-label">Or Upload PDF Portfolio</label>
                        <input type="file" name="portfolio_pdf" id="portfolio_pdf" class="form-control"
                            accept="application/pdf">
                        <p class="form-text">(Max size: 10MB)</p>
                        @error('portfolio_pdf')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn-primary" style="width: 100%; justify-content: center;">
                        Submit & Wait for Approval
                    </button>

                    @if ($errors->any())
                        <div class="alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </form>
            @endif
        </div>
    </main>

    <style>
        .approval-card {
            max-width: 600px;
            width: 100%;
            text-align: center;
            padding: 3rem 2rem;
            border-radius: 16px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-lg);
        }

        .icon-wrapper {
            width: 80px;
            height: 80px;
            background: rgba(241, 198, 82, 0.1);
            color: var(--accent);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            border: 1px solid rgba(241, 198, 82, 0.2);
        }

        h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--text-primary);
        }

        .description {
            font-size: 1.1rem;
            color: var(--text-secondary);
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .info-box {
            background: var(--bg-body);
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            text-align: left;
            border: 1px solid var(--border);
        }

        .info-box h3 {
            font-size: 1rem;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }

        .info-box ul {
            list-style: none;
            padding: 0;
            margin: 0;
            color: var(--text-secondary);
            font-size: 0.95rem;
        }

        .info-box li {
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-box i {
            color: var(--success);
        }

        .form-group {
            text-align: left;
            margin-bottom: 1.5rem;
        }

        .form-text {
            margin: 0.25rem 0 0;
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .text-danger {
            color: var(--danger);
            font-size: 0.875rem;
            display: block;
            margin-top: 0.25rem;
        }

        .alert-danger {
            margin-top: 1rem;
            padding: 1rem;
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid var(--danger);
            border-radius: 8px;
            color: var(--danger);
            text-align: left;
        }
    </style>
@endsection

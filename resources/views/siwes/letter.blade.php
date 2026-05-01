<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>SIWES Introduction Letter</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        html, body {
            margin: 0;
            padding: 0;
            background: #e5e5e5;
            font-family: "Times New Roman", serif;
            color: #111;
        }

        .no-print {
            padding: 10px 16px;
            background: #f5f5f5;
            border-bottom: 1px solid #dcdcdc;
            text-align: right;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            position: relative;
            background-image: url('{{ asset('assets/images/siwes-letterhead.jpg') }}');
            background-size: 210mm 297mm;
            background-repeat: no-repeat;
            background-position: top left;
            box-sizing: border-box;
        }

        .content {
            position: absolute;
            top: 210px;
            left: 80px;
            right: 80px;
            bottom: 70px;
            font-size: 15px;
            line-height: 1.7;
        }

        .meta-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 26px;
            font-size: 15px;
        }

        .recipient-lines {
            margin-bottom: 14px;
            width: 55%;
        }

        .recipient-line {
            border-bottom: 1px solid #222;
            height: 20px;
            margin-bottom: 8px;
            width: 100%;
        }

        .salutation {
            margin: 12px 0 18px;
        }

        .subject {
            font-weight: 700;
            text-transform: uppercase;
            text-decoration: underline;
            margin-bottom: 18px;
            max-width: 88%;
        }

        .paragraph {
            margin-bottom: 16px;
            text-align: justify;
        }

        .closing {
            margin-top: 6px;
        }

        .signature-block {
            margin-top: 6px;
        }

        .signature-image {
            height: 70px;
            max-width: 220px;
            object-fit: contain;
            display: block;
            margin-bottom: 6px;
        }

        .signature-name {
            font-weight: 700;
        }

        @media print {
            body {
                background: #fff;
            }

            .no-print {
                display: none;
            }

            .page {
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()">Print</button>
    </div>

    <div class="page">
        <div class="content">
            <div class="meta-row">
                <div><strong>{{ $letter->reference_no }}</strong></div>
                <div>{{ optional($letter->issue_date)->format('jS F, Y') }}</div>
            </div>

            <div class="recipient-lines">
                <div class="recipient-line"></div>
                <div class="recipient-line"></div>
                <div class="recipient-line"></div>
            </div>

            <div class="salutation">Dear Sir/Madam</div>

            <div class="subject">
                Request for Placement of Student for Industrial Work Experience Scheme
            </div>

            <div class="paragraph">
                The bearer <strong>{{ strtoupper($participant->full_name ?? '') }}</strong>
                with Reg. No. <strong>{{ $participant->participant_no ?? '' }}</strong>
                is a
                <strong>{{ $participant->batch?->course?->title ?? $participant->course?->title ?? '' }}</strong>
                student of the Sustainable Procurement, Environmental and Social Standards Enhancement Centre of Excellence, Ahmadu Bello University, Zaria.
            </div>

            <div class="paragraph">
                As part of the requirements for the award of
                <strong>{{ $participant->batch?->course?->title ?? $participant->course?->title ?? '' }}</strong>,
                the student is required to undertake a <strong>One (1) week compulsory Industrial Training</strong>.
            </div>

            <div class="paragraph">
                I wish to therefore solicit for placement of the student in your organisation for the period mentioned above.
            </div>

            <div class="closing">Thank you</div>

            <div class="closing">Yours faithfully,</div>

            <div class="signature-block">
                @if(file_exists(public_path('assets/images/siwes-signature.png')))
                    <img
                        src="{{ asset('assets/images/siwes-signature.png') }}"
                        alt="Authorized Signature"
                        class="signature-image"
                    >
                @endif

                <div class="signature-name">M.D. Suleiman,</div>
                <div>Human Resource Officer,</div>
                <div>For: Project Manager/Secretary</div>
            </div>
        </div>
    </div>
</body>
</html>

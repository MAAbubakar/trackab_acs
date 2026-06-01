@extends('layouts.admin')

@section('content')
    <style>
        .message-compose-wrap {
            max-width: 980px;
            margin: 0 auto;
        }

        .message-compose-card {
            background: #ffffff;
            border: 1px solid #dbe7e2;
            border-radius: 24px;
            box-shadow: 0 18px 45px rgba(15, 23, 42, .08);
            overflow: hidden;
        }

        .message-compose-body {
            padding: 24px;
        }

        .message-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .message-field-full {
            grid-column: 1 / -1;
        }

        .message-label {
            display: block;
            font-size: 13px;
            font-weight: 900;
            color: #334155;
            margin-bottom: 7px;
        }

        .message-control {
            width: 100%;
            border: 1px solid #dbe3ea;
            border-radius: 14px;
            padding: 12px 13px;
            background: #ffffff;
            color: #0f172a;
            font-size: 14px;
            outline: none;
            box-sizing: border-box;
        }

        .message-control:focus {
            border-color: #0b6b57;
            box-shadow: 0 0 0 4px rgba(11, 107, 87, .08);
        }

        textarea.message-control {
            min-height: 190px;
            resize: vertical;
            line-height: 1.6;
        }

        .message-note {
            margin-top: 16px;
            padding: 14px 16px;
            border-radius: 16px;
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            color: #64748b;
            font-size: .92rem;
            font-weight: 650;
            line-height: 1.55;
        }

        .message-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 22px;
        }

        @media (max-width: 760px) {
            .message-grid {
                grid-template-columns: 1fr;
            }

            .message-actions .btn {
                width: 100%;
            }
        }
    </style>

    <div class="message-compose-wrap">
        <div class="page-header">
            <div>
                <h3 class="page-title">Compose Message</h3>
                <div class="page-subtitle">Send official messages to participants by batch, individual participant, or all active participants.</div>
            </div>

            <div class="actions-inline">
                <a href="{{ route('admin.messages.index') }}" class="btn btn-secondary">Message Logs</a>
            </div>
        </div>

        @if(session('error'))
            <div class="app-alert app-alert-danger">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="app-alert app-alert-danger">
                <strong>Please correct the following:</strong>
                <ul style="margin:10px 0 0 18px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="message-compose-card">
            <div class="message-compose-body">
                <form method="POST" action="{{ route('admin.messages.store') }}">
                    @csrf

                    <div class="message-grid">
                        <div>
                            <label for="recipient_scope" class="message-label">Recipient Scope</label>
                            <select name="recipient_scope" id="recipient_scope" class="message-control" required>
                                <option value="all" @selected(old('recipient_scope') === 'all')>All Active Participants</option>
                                <option value="batch" @selected(old('recipient_scope') === 'batch')>Selected Batch</option>
                                <option value="participant" @selected(old('recipient_scope') === 'participant')>Selected Participant</option>
                            </select>
                        </div>

                        <div id="batch-wrap">
                            <label for="batch_id" class="message-label">Batch</label>
                            <select name="batch_id" id="batch_id" class="message-control">
                                <option value="">Select Batch</option>
                                @foreach($batches as $batch)
                                    <option value="{{ $batch->id }}" @selected(old('batch_id') == $batch->id)>
                                        {{ $batch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div id="participant-wrap" class="message-field-full">
                            <label for="participant_id" class="message-label">Participant</label>
                            <select name="participant_id" id="participant_id" class="message-control">
                                <option value="">Select Participant</option>
                                @foreach($participants as $participant)
                                    <option value="{{ $participant->id }}" @selected(old('participant_id') == $participant->id)>
                                        {{ $participant->participant_no }} - {{ $participant->full_name }}{{ $participant->email ? ' - '.$participant->email : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="message-field-full">
                            <label for="subject" class="message-label">Subject</label>
                            <input
                                type="text"
                                name="subject"
                                id="subject"
                                class="message-control"
                                value="{{ old('subject') }}"
                                placeholder="Example: Evaluation Reminder"
                                required
                            >
                        </div>

                        <div class="message-field-full">
                            <label for="body" class="message-label">Message Body</label>
                            <textarea
                                name="body"
                                id="body"
                                class="message-control"
                                placeholder="Type the message to participants..."
                                required
                            >{{ old('body') }}</textarea>
                        </div>
                    </div>

                    <div class="message-note">
                        This message will be sent as an in-app notification and email where the participant has a linked user account and valid email address.
                    </div>

                    <div class="message-actions">
                        <a href="{{ route('admin.messages.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Send Message</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const scope = document.getElementById('recipient_scope');
            const batchWrap = document.getElementById('batch-wrap');
            const participantWrap = document.getElementById('participant-wrap');

            const toggleFields = () => {
                const value = scope.value;

                batchWrap.style.display = value === 'batch' ? '' : 'none';
                participantWrap.style.display = value === 'participant' ? '' : 'none';
            };

            scope.addEventListener('change', toggleFields);
            toggleFields();
        });
    </script>
@endsection

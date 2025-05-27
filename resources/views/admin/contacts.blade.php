@extends('layouts.admin')
@section('content')

<style>
    .table-striped th:nth-child(1), .table-striped td:nth-child(1) {
        width: 50px;
    }
    .table-striped th:nth-child(2), .table-striped td:nth-child(2) {
        width: 150px;
    }
</style>

<div class="main-content-inner">                            
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Nachricht Kontaktieren</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('admin.index') }}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>                                                                           
                <li><i class="icon-chevron-right"></i></li>
                <li><div class="text-tiny">Nachricht Kontaktieren</div></li>
            </ul>
        </div>
        
        <div class="wg-box">
            <div class="wg-table table-all-user">                
                <div class="table-responsive">
                    @if(Session::has('status'))
                        <p class="alert alert-success">{{ Session::get('status') }}</p>
                    @endif

                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Telefon</th>
                                <th>Kommentar</th>
                                <th>Datum</th>
                                <th>Aktion</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($contacts as $contact)
                            <tr>
                                <td>{{ $contact->id }}</td>
                                <td>{{ $contact->name }}</td>
                                <td>{{ $contact->email }}</td>
                                <td>{{ $contact->phone }}</td>
                                <td>{{ Str::limit($contact->comment, 50) }}</td>
                                <td>{{ $contact->created_at->format('d.m.Y H:i') }}</td>
                                <td>
                                    <div class="list-icon-function d-flex gap-2">
                                        {{-- Antwort mit EmailJS --}}
                                        <button type="button"
                                                class="btn btn-sm text-primary send-reply"
                                                data-name="{{ $contact->name }}"
                                                data-email="{{ $contact->email }}"
                                                title="Antwort senden"
                                                style="border: none; background: transparent; cursor: pointer;">
                                            <i class="icon-mail"></i>
                                        </button>

                                        {{-- Löschen --}}
                                        <form action="{{ route('admin.contact.delete', ['id' => $contact->id]) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm text-danger delete" title="Löschen" style="border: none; background: transparent; cursor: pointer;">
                                                <i class="icon-trash-2"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach                                  
                        </tbody>
                    </table>                
                </div>
            </div>

            <div class="divider"></div>
            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">                
                {{ $contacts->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- EmailJS SDK -->
<script src="https://cdn.emailjs.com/sdk/3.11.0/email.min.js"></script>

<script>
    $(function() {
        // SweetAlert für Löschen
        $(".delete").on('click', function(e) {
            e.preventDefault();
            var selectedForm = $(this).closest('form');
            swal({
                title: "Are you sure?",
                text: "You want to delete this message?",
                type: "warning",
                buttons: ["Cancel", "Delete"],
                confirmButtonColor: '#dc3545'
            }).then(function(result) {
                if (result) {
                    selectedForm.submit();  
                }
            });                             
        });

        // EmailJS initialisieren
        emailjs.init("DEIN_PUBLIC_KEY"); // <-- hier deinen echten Public Key eintragen

        // Antwort senden per Button
        $('.send-reply').on('click', function () {
            const name = $(this).data('name');
            const email = $(this).data('email');

            emailjs.send("DEIN_SERVICE_ID", "DEIN_TEMPLATE_ID", {
                to_name: name,
                to_email: email
            }).then(function(response) {
                alert("Mail erfolgreich gesendet an " + name);
            }, function(error) {
                alert("Fehler beim Senden: " + JSON.stringify(error));
            });
        });
    });
</script>
@endpush

@extends('layout')

@section('title', 'Admin - Programmation')

@section('content')

    <div class="admin-container">

        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="admin-header">
            <h2>Programmation</h2>

            <button class="btn-add" onclick="openModal('modal-add')">
                + Ajouter une séance
            </button>
        </div>


        <div class="admin-filters">

            <div class="date-selector">

                <a class="btn-action"
                   href="{{ route('admin.prog', ['date'=>$prevDate,'cinema'=>$cinema]) }}">
                    <
                </a>

                <span>
{{ \Carbon\Carbon::parse($date)->locale('fr')->translatedFormat('l j F Y') }}
</span>

                <a class="btn-action"
                   href="{{ route('admin.prog', ['date'=>$nextDate,'cinema'=>$cinema]) }}">
                    >
                </a>

            </div>


            <form method="GET" action="{{ route('admin.prog') }}">

                <input type="hidden" name="date" value="{{ $date }}">

                <select name="cinema" onchange="this.form.submit()">

                    <option value="all"
                        {{ $cinema=='all' ? 'selected':'' }}>
                        Tous les cinémas
                    </option>

                    @foreach($cinemas as $c)

                        <option value="{{ $c->idCin }}"
                            {{ $cinema==$c->idCin ? 'selected':'' }}>

                            {{ $c->nomCin }}

                        </option>

                    @endforeach

                </select>

            </form>

        </div>


        @if($seances->count()==0)

            <div class="admin-empty">

                <p>Aucune séance</p>

            </div>

        @else


            <div class="admin-table">

                <div class="table-header">

                    <div class="th">Heure</div>
                    <div class="th">Film</div>
                    <div class="th">Cinéma</div>
                    <div class="th">Salle</div>
                    <div class="th">Langue</div>
                    <div class="th">Prix</div>
                    <div class="th">Actions</div>

                </div>



                <div class="table-body">

                    @foreach($seances as $s)

                        <div class="table-row">

                            <div class="td">
                                {{ \Carbon\Carbon::parse($s->datHeuSea)->format('H:i') }}
                            </div>

                            <div class="td">
                                {{ $s->film_title }}
                            </div>

                            <div class="td">
                                {{ $s->cinema_name }}
                            </div>

                            <div class="td">
                                {{ $s->salle_name ?? 'Aucune salle' }}
                            </div>

                            <div class="td">
                                {{ $s->langue_label ?? '-' }}
                            </div>

                            <div class="td">
                                {{ number_format($s->priSea,2,',',' ') }} €
                            </div>


                            <div class="td actions">


                                <button class="btn-action"

                                        onclick="openEdit(

{{ $s->idSea }},
{{ $s->idFil }},
{{ $s->idCin }},
{{ $s->idSal ?? 'null' }},
{{ $s->idLan ?? 'null' }},
'{{ \Carbon\Carbon::parse($s->datHeuSea)->format('Y-m-d') }}',
'{{ \Carbon\Carbon::parse($s->datHeuSea)->format('H:i') }}',
'{{ $s->priSea }}'

)">

                                    Modifier

                                </button>



                                <form method="POST"
                                      action="{{ route('admin.prog.destroy',$s->idSea) }}?date={{ $date }}&cinema={{ $cinema }}">

                                    @csrf
                                    @method('DELETE')

                                    <button class="btn-danger">
                                        Supprimer
                                    </button>

                                </form>

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

        @endif

    </div>



    {{-- MODAL AJOUT --}}

    <div class="modal" id="modal-add">

        <div class="modal-content">

            <h3>Ajouter une séance</h3>

            <form method="POST" action="{{ route('admin.prog.store') }}">

                @csrf

                <label>Film</label>

                <select name="idFil">

                    @foreach($films as $f)

                        <option value="{{ $f->idFil }}">
                            {{ $f->nomFil }}
                        </option>

                    @endforeach

                </select>


                <label>Cinéma</label>

                <select name="idCin">

                    @foreach($cinemas as $c)

                        <option value="{{ $c->idCin }}">
                            {{ $c->nomCin }}
                        </option>

                    @endforeach

                </select>


                <label>Salle</label>

                <select name="idSal">

                    <option value="">Aucune salle</option>

                    @foreach($salles as $s)

                        <option value="{{ $s->idSal }}">
                            {{ $s->nomSal }}
                        </option>

                    @endforeach

                </select>


                <label>Date</label>

                <input type="date"
                       name="date"
                       value="{{ $date }}">


                <label>Heure</label>

                <input type="time"
                       name="time"
                       value="19:00">


                <label>Langue</label>

                <select name="idLan">

                    <option value="">-</option>

                    @foreach($langues as $l)

                        <option value="{{ $l->idLan }}">
                            {{ $l->langue }}
                        </option>

                    @endforeach

                </select>


                <label>Prix</label>

                <input type="number"
                       step="0.01"
                       name="priSea"
                       value="13.90">


                <div class="modal-actions">

                    <button type="button"
                            class="btn-cancel"
                            onclick="closeModal('modal-add')">

                        Annuler

                    </button>

                    <button type="submit"
                            class="btn-confirm">

                        Ajouter

                    </button>

                </div>

            </form>

        </div>

    </div>



    {{-- MODAL MODIFIER --}}

    <div class="modal" id="modal-edit">

        <div class="modal-content">

            <h3>Modifier une séance</h3>

            <form method="POST" id="editForm">

                @csrf
                @method('PUT')

                <label>Film</label>

                <select name="idFil" id="edit_idFil">

                    @foreach($films as $f)

                        <option value="{{ $f->idFil }}">
                            {{ $f->nomFil }}
                        </option>

                    @endforeach

                </select>


                <label>Cinéma</label>

                <select name="idCin" id="edit_idCin">

                    @foreach($cinemas as $c)

                        <option value="{{ $c->idCin }}">
                            {{ $c->nomCin }}
                        </option>

                    @endforeach

                </select>


                <label>Salle</label>

                <select name="idSal" id="edit_idSal">

                    <option value="">Aucune salle</option>

                    @foreach($salles as $s)

                        <option value="{{ $s->idSal }}">
                            {{ $s->nomSal }}
                        </option>

                    @endforeach

                </select>


                <label>Date</label>

                <input type="date"
                       name="date"
                       id="edit_date">


                <label>Heure</label>

                <input type="time"
                       name="time"
                       id="edit_time">


                <label>Langue</label>

                <select name="idLan" id="edit_idLan">

                    <option value="">-</option>

                    @foreach($langues as $l)

                        <option value="{{ $l->idLan }}">
                            {{ $l->langue }}
                        </option>

                    @endforeach

                </select>


                <label>Prix</label>

                <input type="number"
                       step="0.01"
                       name="priSea"
                       id="edit_priSea">


                <div class="modal-actions">

                    <button type="button"
                            class="btn-cancel"
                            onclick="closeModal('modal-edit')">

                        Annuler

                    </button>

                    <button type="submit"
                            class="btn-confirm">

                        Enregistrer

                    </button>

                </div>

            </form>

        </div>

    </div>



    <script>

        function openModal(id)
        {
            document.getElementById(id).style.display="flex";
        }

        function closeModal(id)
        {
            document.getElementById(id).style.display="none";
        }



        function openEdit(idSea,idFil,idCin,idSal,idLan,date,time,price)
        {

            document.getElementById("editForm").action="/admin/G_prog/"+idSea;

            document.getElementById("edit_idFil").value=idFil;
            document.getElementById("edit_idCin").value=idCin;
            document.getElementById("edit_idSal").value=idSal ?? "";
            document.getElementById("edit_idLan").value=idLan ?? "";
            document.getElementById("edit_date").value=date;
            document.getElementById("edit_time").value=time;
            document.getElementById("edit_priSea").value=price;

            openModal("modal-edit");

        }

    </script>

@endsection
